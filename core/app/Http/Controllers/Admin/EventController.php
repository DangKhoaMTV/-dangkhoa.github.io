<?php

namespace App\Http\Controllers\Admin;

use App\BasicExtra;
use App\Event;
use App\EventCategory;
use App\EventDetail;
use App\Exports\EventBookingExport;
use App\Http\Middleware\EncryptCookies;
use App\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Front\EventController as FrontEventController;
use App\Megamenu;
use App\OfflineGateway;
use App\PaymentGateway;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Maatwebsite\Excel\Facades\Excel;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return
     */
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->first();
        $lang_id = $lang->id;
        $data['lang_id'] = $lang_id;
        $data['abx'] = $lang->basic_extra;
        $data['events'] = Event::where('lang_id', $lang_id)->orderBy('id', 'DESC')->get();
        $languages = Language::orderBy('is_default', 'DESC')->get();
        foreach ($languages as $lang) {
            $data['event_categories'][$lang->code] = EventCategory::where('lang_id', $lang->id)->where('status', '1')->get();
        }
        return view('admin.event.event.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    public function store(Request $request)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $saved_ids = [];
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        $videoExts = array('mp4');
        foreach ($languages as $lang) {
            $slug = make_slug($request->{'title_' . $lang->code});
            $video = $request->{'video_' . $lang->code};
            $extVideo = pathinfo($video, PATHINFO_EXTENSION);
            $sliders = !empty($request->{'slider_' . $lang->code}) ? explode(',', $request->{'slider_' . $lang->code}) : [];


            $rules = [
                'title_' . $lang->code => [
                    'required',
                    'max:255',
                    function ($attribute, $value, $fail) use ($slug) {
                        $events = Event::all();
                        foreach ($events as $key => $event) {
                            if (strtolower($slug) == strtolower($event->slug)) {
                                $fail('The title field must be unique.');
                            }
                        }
                    }
                ],
                'date_' . $lang->code => 'required',
                'time_' . $lang->code => 'required',
                'cost_' . $lang->code => 'required',
                'available_tickets_' . $lang->code => 'required',
                'organizer_' . $lang->code => 'required',
                'venue_' . $lang->code => 'required',
                'cat_id_' . $lang->code => 'required',
                'slider_' . $lang->code => 'required',
                'video_' . $lang->code => 'required'
            ];
            if ($request->filled('video_' . $lang->code)) {
                $rules['video_' . $lang->code] = [
                    function ($attribute, $value, $fail) use ($extVideo, $videoExts) {
                        if (!in_array($extVideo, $videoExts)) {
                            return $fail("Only mp4 video is allowed");
                        }
                    }
                ];
            }

            if ($request->filled('slider_' . $lang->code)) {
                $rules['slider_' . $lang->code] = [
                    function ($attribute, $value, $fail) use ($sliders, $allowedExts) {
                        foreach ($sliders as $key => $slider) {
                            $extSlider = pathinfo($slider, PATHINFO_EXTENSION);
                            if (!in_array($extSlider, $allowedExts)) {
                                return $fail("Only png, jpg, jpeg images are allowed");
                            }
                        }
                    }
                ];
            }

            $messages = [
                'title.required' => 'The title field is required',
                'date.required' => 'The date field is required',
                'time.required' => 'The time field is required',
                'cost.required' => 'The cost field is required',
                'available_tickets.required' => 'Number of tickets field is required',
                'organizer.required' => 'The organizer name field is required',
                'venue.required' => 'The venue field is required',
                'cat_id.required' => 'The category field is required'
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $errmsgs = $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }
        }
        foreach ($languages as $lang) {
            $images = [];
            $slug = make_slug($request->{'title_' . $lang->code});
            $video = $request->{'video_' . $lang->code};
            $extVideo = pathinfo($video, PATHINFO_EXTENSION);
            $sliders = !empty($request->{'slider_' . $lang->code}) ? explode(',', $request->{'slider_' . $lang->code}) : [];
            foreach ($sliders as $key => $slider) {
                $extSlider = pathinfo($slider, PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extSlider;

                $directory = 'assets/front/img/events/sliders/';
                @mkdir($directory, 0775, true);

                @copy($slider, $directory . $filename);
                $images[] = $filename;
            }

            if ($request->filled('video_' . $lang->code)) {
                $videoFile = uniqid() . '.' . $extVideo;
                $directory = "assets/front/img/events/videos/";
                @mkdir($directory, 0775, true);
                @copy($video, $directory . $videoFile);
            }

            $event = new Event;
            $event->title = $request->{'title_' . $lang->code};
            $event->slug = $slug;
            $event->content = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'content_' . $lang->code});
            $event->date = $request->{'date_' . $lang->code};
            $event->time = $request->{'time_' . $lang->code};
            $event->cost = $request->{'cost_' . $lang->code};
            $event->available_tickets = $request->{'available_tickets_' . $lang->code};
            $event->organizer = $request->{'organizer_' . $lang->code};
            $event->organizer_email = $request->{'organizer_email_' . $lang->code};
            $event->organizer_phone = $request->{'organizer_phone_' . $lang->code};
            $event->organizer_website = $request->{'organizer_website_' . $lang->code};
            $event->venue = $request->{'venue_' . $lang->code};
            $event->venue_location = $request->{'venue_location_' . $lang->code};
            $event->venue_phone = $request->{'venue_phone_' . $lang->code};
            $event->meta_tags = $request->{'meta_tags_' . $lang->code};
            $event->meta_description = $request->{'meta_description_' . $lang->code};
            $event->image = json_encode($images);
            $event->video = $videoFile;
            $event->lang_id  = $lang->id;
            $event->cat_id   = $request->{'cat_id_' . $lang->code};
            $event->save();

            if($assoc_id == 0){
                $assoc_id = $event->id;
            }

            $saved_ids[] = $event->id;
        }
        foreach ($saved_ids as $saved_id) {
            $event = Event::findOrFail($saved_id);
            $event->assoc_id = $assoc_id;
            $event->save();
        }
        Session::flash('success', 'Event added successfully!');
        return "success";
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $data['abx'] = BasicExtra::select('base_currency_text')->where('language_id', $event->lang_id)->first();
        $current_lang = Language::where('id', $event->lang_id)->first();
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $data['langs'] = $languages;
        foreach ($languages as $lang) {
            if ($current_lang->id == $lang->id) {
                $data['event'][$lang->code] = $event;
            } else {
                $data['event'][$lang->code] = $event->assoc_id > 0 ? Event::where('lang_id', $lang->id)->where('assoc_id', $event->assoc_id)->first() : null;
            }
            if ($data['event'][$lang->code] == null) {
                $data['event'][$lang->code] = new Event();
                $data['ccates'][$lang->code] = Event::where('lang_id', $lang->id)->get();
            }
            $data['event_categories'][$lang->code] = EventCategory::where('lang_id', $lang->id)
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->get();
        }
        return view('admin.event.event.edit', $data);
    }

    public function edit_modal($id)
    {
        $event = Event::findOrFail($id);
        $data['abx'] = BasicExtra::select('base_currency_text')->where('language_id', $event->lang_id)->first();
        $current_lang = Language::where('id', $event->lang_id)->first();
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $data['langs'] = $languages;
        foreach ($languages as $lang) {
            if ($current_lang->id == $lang->id) {
                $data['event'][$lang->code] = $event;
            } else {
                $data['event'][$lang->code] = $event->assoc_id > 0 ? Event::where('lang_id', $lang->id)->where('assoc_id', $event->assoc_id)->first() : null;
            }
            if ($data['event'][$lang->code] == null) {
                $data['event'][$lang->code] = new Event();
                $data['ccates'][$lang->code] = Event::where('lang_id', $lang->id)->get();
            }
            $data['event_categories'][$lang->code] = EventCategory::where('lang_id', $lang->id)
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->get();
        }
        return view('admin.event.event.edit-modal', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $saved_ids = [];
        $allowedExts = array('jpg', 'png', 'jpeg');
        foreach ($languages as $lang) {
            if ($request->filled('event_id_' . $lang->code) || !$request->filled('event_assoc_id_' . $lang->code)) {//Validation
                $slug = make_slug($request->{'title_' . $lang->code});
                $eventId = $request->{'event_id_' . $lang->code};

                $sliders = !empty($request->{'slider_' . $lang->code}) ? explode(',', $request->{'slider_' . $lang->code}) : [];

                $video = $request->{'video_' . $lang->code};
                $videoExts = array('mp4');
                $extVideo = pathinfo($video, PATHINFO_EXTENSION);

                $rules = [
                    'slider_' . $lang->code => 'required',
                    'title_' . $lang->code => [
                        'required',
                        'max:255',
                        function ($attribute, $value, $fail) use ($slug, $eventId) {
                            $events = Event::all();
                            foreach ($events as $key => $event) {
                                if ($event->id != $eventId && strtolower($slug) == strtolower($event->slug)) {
                                    $fail('The title field must be unique.');
                                }
                            }
                        }
                    ],
                    'date_' . $lang->code => 'required',
                    'time_' . $lang->code => 'required',
                    'cost_' . $lang->code => 'required',
                    'available_tickets_' . $lang->code => 'required',
                    'organizer_' . $lang->code => 'required',
                    'venue_' . $lang->code => 'required',
                    'cat_id_' . $lang->code => 'required',
                ];

                if ($request->filled('video_' . $lang->code)) {
                    $rules['video_' . $lang->code] = [
                        function ($attribute, $value, $fail) use ($extVideo, $videoExts) {
                            if (!in_array($extVideo, $videoExts)) {
                                return $fail("Only mp4 video is allowed");
                            }
                        }
                    ];
                }

                if ($request->filled('slider_' . $lang->code)) {
                    $rules['slider_' . $lang->code] = [
                        function ($attribute, $value, $fail) use ($sliders, $allowedExts) {
                            foreach ($sliders as $key => $slider) {
                                $extSlider = pathinfo($slider, PATHINFO_EXTENSION);
                                if (!in_array($extSlider, $allowedExts)) {
                                    return $fail("Only png, jpg, jpeg images are allowed");
                                }
                            }
                        }
                    ];
                }

                $messages = [
                    'title.required' => 'The title field is required',
                    'date.required' => 'The date field is required',
                    'time.required' => 'The time field is required',
                    'cost.required' => 'The cost field is required',
                    'available_tickets.required' => 'Number of tickets field is required',
                    'organizer.required' => 'The organizer name field is required',
                    'venue.required' => 'The venue field is required',
                    'cat_id.required' => 'The category field is required'
                ];

                $validator = Validator::make($request->all(), $rules, $messages);
                if ($validator->fails()) {
                    $errmsgs = $validator->getMessageBag()->add('error', 'true');
                    return response()->json($validator->errors());
                }
            }
        }
        foreach ($languages as $lang) {
            if ($request->filled('event_id_' . $lang->code)) {//update

                $event = Event::findOrFail($request->{'event_id_' . $lang->code});
                $slug = make_slug($request->{'title_' . $lang->code});
                $sliders = !empty($request->{'slider_' . $lang->code}) ? explode(',', $request->{'slider_' . $lang->code}) : [];
                $video = $request->{'video_' . $lang->code};
                $extVideo = pathinfo($video, PATHINFO_EXTENSION);

                if ($request->filled('video_' . $lang->code)) {
                    @unlink('assets/front/img/events/videos/' . $event->video);
                    $videoFile = uniqid() . '.' . $extVideo;
                    @copy($video, 'assets/front/img/events/videos/' . $videoFile);
                    $videoFile = $videoFile;
                } else {
                    $videoFile = $event->video;
                }

                /*$event->update($request->except('image', 'video', 'content') + [
                        'slug' => $slug,
                        'content' => str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->content),
                        'video' => $videoFile
                    ]);*/

                // copy the sliders first
                $fileNames = [];
                foreach ($sliders as $key => $slider) {
                    $extSlider = pathinfo($slider, PATHINFO_EXTENSION);
                    $filename = uniqid() . '.' . $extSlider;
                    @copy($slider, 'assets/front/img/events/sliders/' . $filename);
                    $fileNames[] = $filename;
                }

                // delete & unlink previous slider images
                $preImages = json_decode($event->image, true);
                foreach ($preImages as $key => $pi) {
                    @unlink('assets/front/img/events/sliders/' . $pi);
                }

                $event->title = $request->{'title_' . $lang->code};
                $event->slug = $slug;
                $event->content = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'content_' . $lang->code});
                $event->date = $request->{'date_' . $lang->code};
                $event->time = $request->{'time_' . $lang->code};
                $event->cost = $request->{'cost_' . $lang->code};
                $event->available_tickets = $request->{'available_tickets_' . $lang->code};
                $event->organizer = $request->{'organizer_' . $lang->code};
                $event->organizer_email = $request->{'organizer_email_' . $lang->code};
                $event->organizer_phone = $request->{'organizer_phone_' . $lang->code};
                $event->organizer_website = $request->{'organizer_website_' . $lang->code};
                $event->venue = $request->{'venue_' . $lang->code};
                $event->venue_location = $request->{'venue_location_' . $lang->code};
                $event->venue_phone = $request->{'venue_phone_' . $lang->code};
                $event->meta_tags = $request->{'meta_tags_' . $lang->code};
                $event->meta_description = $request->{'meta_description_' . $lang->code};
                $event->video = $videoFile;
                $event->lang_id  = $lang->id;
                $event->cat_id   = $request->{'cat_id_' . $lang->code};

                $event->image = json_encode($fileNames);

                if ($assoc_id == 0) {
                    $assoc_id = $event->id;
                }

                $event->assoc_id = $assoc_id;
                $event->save();
            }else {
                if (!$request->filled('event_assoc_id_' . $lang->code)) {//create

                    $event = new Event;
                    $slug = make_slug($request->{'title_' . $lang->code});
                    $sliders = !empty($request->{'slider_' . $lang->code}) ? explode(',', $request->{'slider_' . $lang->code}) : [];
                    $video = $request->{'video_' . $lang->code};
                    $extVideo = pathinfo($video, PATHINFO_EXTENSION);

                    if ($request->filled('video_' . $lang->code)) {
                        $videoFile = uniqid() . '.' . $extVideo;
                        @copy($video, 'assets/front/img/events/videos/' . $videoFile);
                        $videoFile = $videoFile;
                    } else {
                        $videoFile = '';
                    }

                    // copy the sliders first
                    $fileNames = [];
                    foreach ($sliders as $key => $slider) {
                        $extSlider = pathinfo($slider, PATHINFO_EXTENSION);
                        $filename = uniqid() . '.' . $extSlider;
                        @copy($slider, 'assets/front/img/events/sliders/' . $filename);
                        $fileNames[] = $filename;
                    }


                    $event->title = $request->{'title_' . $lang->code};
                    $event->slug = $slug;
                    $event->content = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'content_' . $lang->code});
                    $event->date = $request->{'date_' . $lang->code};
                    $event->time = $request->{'time_' . $lang->code};
                    $event->cost = $request->{'cost_' . $lang->code};
                    $event->available_tickets = $request->{'available_tickets_' . $lang->code};
                    $event->organizer = $request->{'organizer_' . $lang->code};
                    $event->organizer_email = $request->{'organizer_email_' . $lang->code};
                    $event->organizer_phone = $request->{'organizer_phone_' . $lang->code};
                    $event->organizer_website = $request->{'organizer_website_' . $lang->code};
                    $event->venue = $request->{'venue_' . $lang->code};
                    $event->venue_location = $request->{'venue_location_' . $lang->code};
                    $event->venue_phone = $request->{'venue_phone_' . $lang->code};
                    $event->meta_tags = $request->{'meta_tags_' . $lang->code};
                    $event->meta_description = $request->{'meta_description_' . $lang->code};
                    $event->video = $videoFile;
                    $event->lang_id  = $lang->id;
                    $event->cat_id   = $request->{'cat_id_' . $lang->code};

                    $event->image = json_encode($fileNames);

                    $saved_ids[] = $event->id;
                }else {
                    $saved_ids[] = $request->{'event_assoc_id_' . $lang->code};
                }
            }
        }
        foreach ($saved_ids as $saved_id) {
            $event = Event::findOrFail($saved_id);
            $event->assoc_id = $assoc_id;
            $event->save();
        }
        Session::flash('success', 'Event updated successfully!');
        return "success";
    }

    public function uploadUpdate(Request $request, $id)
    {
        $rules = [
            'file' => 'required | mimes:jpeg,jpg,png',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json(['errors' => $validator->errors(), 'id' => 'blog']);
        }
        $img = $request->file('file');
        $event = Event::findOrFail($id);
        if ($request->hasFile('file')) {
            $filename = time() . '.' . $img->getClientOriginalExtension();
            $request->file('file')->move('assets/front/img/events/', $filename);
            @unlink('assets/front/img/events/' . $event->image);
            $event->image = $filename;
            $event->save();
        }

        return response()->json(['status' => "success", "image" => "Event image", 'event' => $event]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getCategories($lang_id)
    {
        return EventCategory::where('lang_id', $lang_id)->where('status', '1')->get();
    }

    public function upload(Request $request)
    {
        $rules = ['upload_video' => 'mimes:mp4|required'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json(['errors' => $validator->errors(), 'id' => 'blog']);
        }
        $img = $request->file('upload_video');
        $filename = uniqid("event-") . '.' . $img->getClientOriginalExtension();
        //if directory not exist than create directory with permission
        $directory = "assets/front/img/events/videos/";
        if (!file_exists($directory)) mkdir($directory, 0777, true);
        $img->move($directory, $filename);
        return response()->json(['filename' => $filename, 'status' => 200]);
    }

    public function sliderRemove(Request $request)
    {
        $event = Event::findOrFail($request->id);
        $images = json_decode($event->image, true);
        @unlink('assets/front/img/events/sliders/' . $images["$request->key"]);
        unset($images["$request->key"]);
        $newImages = array_values($images);
        $event->image = json_encode($newImages);
        $event->save();
        return response()->json(['status' => 200, 'message' => 'success']);
    }

    public function deleteFromMegaMenu($event) {
        // unset service from megamenu for service_category = 1
        $megamenu = Megamenu::where('language_id', $event->lang_id)->where('category', 1)->where('type', 'events');
        if ($megamenu->count() > 0) {
            $megamenu = $megamenu->first();
            $menus = json_decode($megamenu->menus, true);
            $catId = $event->eventCategories->id;
            if (is_array($menus) && array_key_exists("$catId", $menus)) {
                if (in_array($event->id, $menus["$catId"])) {
                    $index = array_search($event->id, $menus["$catId"]);
                    unset($menus["$catId"]["$index"]);
                    $menus["$catId"] = array_values($menus["$catId"]);
                    if (count($menus["$catId"]) == 0) {
                        unset($menus["$catId"]);
                    }
                    $megamenu->menus = json_encode($menus);
                    $megamenu->save();
                }
            }
        }
    }

    public function delete(Request $request)
    {
        $_event = Event::findOrFail($request->event_id);
        if($_event->assoc_id > 0) {
            $events = Event::where('assoc_id', $_event->assoc_id)->get();
            foreach ($events as $event) {
                $images = json_decode($event->image, true);
                if (count($images) > 0) {
                    foreach ($images as $image) {
                        $directory = 'assets/front/img/events/sliders/' . $image;
                        if (file_exists($directory)) {
                            @unlink($directory);
                        }
                    }
                }
                if (!is_null($event->video)) {
                    $directory = "assets/front/img/events/videos/" . $event->video;
                    if (file_exists($directory)) {
                        @unlink($directory);
                    }
                }
                $event_details = EventDetail::query()->where('event_id', $event->id)->get();
                foreach ($event_details as $event_detail) {
                    if (!is_null($event_detail->receipt)) {
                        $directory = "assets/front/img/events/receipt/" . $event_detail->receipt;
                        if (file_exists($directory)) {
                            @unlink($directory);
                        }
                    }
                    $event_detail->delete();
                }

                $this->deleteFromMegaMenu($event);
                $event->delete();
            }
        }else {
            $images = json_decode($_event->image, true);
            if (count($images) > 0) {
                foreach ($images as $image) {
                    $directory = 'assets/front/img/events/sliders/' . $image;
                    if (file_exists($directory)) {
                        @unlink($directory);
                    }
                }
            }
            if (!is_null($_event->video)) {
                $directory = "assets/front/img/events/videos/" . $_event->video;
                if (file_exists($directory)) {
                    @unlink($directory);
                }
            }
            $event_details = EventDetail::query()->where('event_id', $_event->id)->get();
            foreach ($event_details as $event_detail) {
                if (!is_null($event_detail->receipt)) {
                    $directory = "assets/front/img/events/receipt/" . $event_detail->receipt;
                    if (file_exists($directory)) {
                        @unlink($directory);
                    }
                }
                $event_detail->delete();
            }

            $this->deleteFromMegaMenu($_event);
            $_event->delete();
        }

        Session::flash('success', 'Event deleted successfully!');
        return back();
    }

    public function bulkDelete(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $ids = $request->ids;
            foreach ($ids as $id) {
                $_event = Event::findOrFail($id);
                if($_event->assoc_id > 0) {
                    $events = Event::where('assoc_id', $_event->assoc_id)->get();
                    foreach ($events as $event) {
                        $images = json_decode($event->image, true);
                        if (count($images) > 0) {
                            foreach ($images as $image) {
                                $directory = 'assets/front/img/events/sliders/' . $image;
                                if (file_exists($directory)) {
                                    @unlink($directory);
                                }
                            }
                        }
                        if (!is_null($event->video)) {
                            $directory = "assets/front/img/events/videos/" . $event->video;
                            if (file_exists($directory)) {
                                @unlink($directory);
                            }
                        }
                        $event_details = EventDetail::where('event_id', $event->id)->get();
                        foreach ($event_details as $event_detail) {
                            if (!is_null($event_detail->receipt)) {
                                $directory = "assets/front/img/events/receipt/" . $event_detail->receipt;
                                if (file_exists($directory)) {
                                    @unlink($directory);
                                }
                            }
                            $event_detail->delete();
                        }

                        $this->deleteFromMegaMenu($event);
                        $event->delete();
                    }
                }else {
                    $images = json_decode($_event->image, true);
                    if (count($images) > 0) {
                        foreach ($images as $image) {
                            $directory = 'assets/front/img/events/sliders/' . $image;
                            if (file_exists($directory)) {
                                @unlink($directory);
                            }
                        }
                    }
                    if (!is_null($_event->video)) {
                        $directory = "assets/front/img/events/videos/" . $_event->video;
                        if (file_exists($directory)) {
                            @unlink($directory);
                        }
                    }
                    $event_details = EventDetail::where('event_id', $_event->id)->get();
                    foreach ($event_details as $event_detail) {
                        if (!is_null($event_detail->receipt)) {
                            $directory = "assets/front/img/events/receipt/" . $event_detail->receipt;
                            if (file_exists($directory)) {
                                @unlink($directory);
                            }
                        }
                        $event_detail->delete();
                    }

                    $this->deleteFromMegaMenu($_event);
                    $_event->delete();
                }
            }
            Session::flash('success', 'Events deleted successfully!');
            return "success";
        });
    }

    public function paymentLog(Request $request)
    {
        $search = $request->search;
        $data['events'] = EventDetail::when($search, function ($query, $search) {
            return $query->where('transaction_id', $search);
        })
            ->orderBy('id', 'DESC')
            ->paginate(10);
        return view('admin.event.payment.index', $data);
    }

    public function paymentLogDelete(Request $request) {
        $payment = EventDetail::findOrFail($request->payment_id);
        @unlink('assets/front/img/events/receipt', $payment->receipt);
        $payment->delete();

        $request->session()->flash('success', 'Payment deleted successfully!');
        return back();
    }


    public function paymentLogBulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $payment = EventDetail::findOrFail($id);
            @unlink('assets/front/img/events/receipt', $payment->receipt);
            $payment->delete();
        }

        Session::flash('success', 'Payments deleted successfully!');
        return "success";
    }

    public function paymentLogUpdate(Request $request)
    {
        $currentLang = session()->has('lang') ?
            (Language::where('code', session()->get('lang'))->first())
            : (Language::where('is_default', 1)->first());
        $be = $currentLang->basic_extended;
        if ($request->status == "success") {
            $event_details = EventDetail::query()
                ->findOrFail($request->id);
            if ($event_details->status == "Rejected") {
                $event = Event::query()->findOrFail($event_details->event_id);
                $event->available_tickets = $event->available_tickets - $event_details->quantity;
                $event->save();
            }
            $event_details->status = "Success";
            $event_details->save();
            $event = new FrontEventController;
            $fileName = $event->makeInvoice($event_details);
            $request['name'] = $event_details->name;
            $request['email'] = $event_details->email;
            $event->sendMailPHPMailer($request, $fileName, $be);
            Session::flash('success', 'Event payment updated successfully!');
        } elseif ($request->status == "rejected") {
            $event_details = EventDetail::query()->findOrFail($request->id);
            $event_details->status = "Rejected";
            $event_details->save();
            $event = Event::query()->findOrFail($event_details->event_id);
            $event->available_tickets = $event->available_tickets + $event_details->quantity;
            $event->save();
            Session::flash('success', 'Event payment rejected successfully!');
        } else {
            $event_details = EventDetail::query()
                ->findOrFail($request->id)
                ->update(['status' => 'Pending']);
            Session::flash('success', 'Event payment to pending successfully!');
        }

        $sub = "Ticket Booking Status Changed";
        if (!empty($event_details->email)) {
            // Send Mail to Customer
            $mail = new PHPMailer(true);
            if ($be->is_smtp == 1) {
                try {
                    $mail->isSMTP();
                    $mail->Host       = $be->smtp_host;
                    $mail->SMTPAuth   = true;
                    $mail->Username   = $be->smtp_username;
                    $mail->Password   = $be->smtp_password;
                    $mail->SMTPSecure = $be->encryption;
                    $mail->Port       = $be->smtp_port;

                    //Recipients
                    $mail->setFrom($be->from_mail, $be->from_name);
                    $mail->addAddress($event_details->email, $event_details->name);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = $sub;
                    $mail->Body    = 'Hello <strong>' . $event_details->name . '</strong>,<br/><br>Your ticket booking status of <strong>' . $event_details->event->title . '</strong> is changed to: <strong>'.ucfirst($request->status).'</strong>.<br/><br>Thank you.';
                    $mail->send();
                } catch (Exception $e) {
                    // die($e->getMessage());
                }
            } else {
                try {

                    //Recipients
                    $mail->setFrom($be->from_mail, $be->from_name);
                    $mail->addAddress($event_details->email, $event_details->name);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = $sub ;
                    $mail->Body    = 'Hello <strong>' . $event_details->name . '</strong>,<br/><br>Your ticket booking status of <strong>' . $event_details->event->title . '</strong> is changed to: <strong>'.ucfirst($request->status).'</strong>.<br/><br>Thank you.';
                    $mail->send();
                } catch (Exception $e) {
                    // die($e->getMessage());
                }
            }
        }
        return redirect()->route('admin.event.payment.log');
    }

    public function settings() {
        $data['abex'] = BasicExtra::first();
        return view('admin.event.settings', $data);
    }

    public function updateSettings(Request $request) {
        $bexs = BasicExtra::all();
        foreach($bexs as $bex) {
            $bex->event_guest_checkout = $request->event_guest_checkout;
            $bex->is_event = $request->is_event;
            $bex->save();
        }

        $request->session()->flash('success', 'Settings updated successfully!');
        return back();
    }

    public function images($eventid)
    {
        $event = Event::find($eventid);
        $images = json_decode($event->image, true);
        $convImages = [];

        foreach ($images as $key => $image) {
            $convImages[] = url("assets/front/img/events/sliders/$image");
        }

        return $convImages;
    }

    public function report(Request $request) {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $status = $request->status;
        $paymentMethod = $request->payment_method;

        if (!empty($fromDate) && !empty($toDate)) {
            $bookings = EventDetail::when($fromDate, function ($query, $fromDate) {
                return $query->whereDate('created_at', '>=', Carbon::parse($fromDate));
            })->when($toDate, function ($query, $toDate) {
                return $query->whereDate('created_at', '<=', Carbon::parse($toDate));
            })->when($paymentMethod, function ($query, $paymentMethod) {
                return $query->where('payment_method', $paymentMethod);
            })->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })->select('transaction_id','event_id','name','email','phone','amount', 'quantity','payment_method','status','created_at')->orderBy('id', 'DESC');
            Session::put('event_booking_report', $bookings->get());
            $data['bookings'] = $bookings->paginate(10);
        } else {
            Session::put('event_booking_report', []);
            $data['bookings'] = [];
        }

        $data['onPms'] = PaymentGateway::where('status', 1)->get();
        $data['offPms'] = OfflineGateway::where('event_checkout_status', 1)->get();


        return view('admin.event.report', $data);
    }

    public function exportReport() {
        $bookings = Session::get('event_booking_report');
        if (empty($bookings) || count($bookings) == 0) {
            Session::flash('warning', 'There are no bookings to export');
            return back();
        }
        return Excel::download(new EventBookingExport($bookings), 'event-bookings.csv');
    }
}
