<?php

namespace App\Http\Controllers\Admin;

use App\BasicExtended;
use App\BasicExtra;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Dinning;
use App\Language;
use App\Megamenu;
use Validator;
use Session;

class DinningController extends Controller
{

    public function settings()
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        foreach ($languages as $lang){
            $data['d_bex'][$lang->code] = BasicExtra::where('language_id', $lang->id)->first();
        }
        $data['langs'] = $languages;
       // var_dump($data['bex'][$lang->code]->dinning_page_video_url);die();
        return view('admin.dinning.settings', $data);
    }

    public function updateSettings(Request $request)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        foreach ($languages as $lang) {

            $messages = [];
            $rules = [
                'dinning_page_summary_' . $lang->code => 'required',
                'dinning_page_video_url_' . $lang->code => 'required',
                'dinning_page_content_' . $lang->code => 'required',
            ];
            if ($request->filled('dinning_page_bg_image_' . $lang->code)) {
                $image = $request->{'dinning_page_bg_image_' . $lang->code};
                $extImage = pathinfo($image, PATHINFO_EXTENSION);
                $rules['dinning_page_bg_image_' . $lang->code] = [
                    function ($attribute, $value, $fail) use ($extImage, $allowedExts) {
                        if (!in_array($extImage, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg, svg image is allowed");
                        }
                    }
                ];
            }

            if ($request->filled('dinning_page_slider_' . $lang->code)) {
                $sliders = !empty($request->{'dinning_page_slider_' . $lang->code}) ? explode(',', $request->{'dinning_page_slider_' . $lang->code}) : [];
            $rules['dinning_page_slider_' . $lang->code] = [
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

            if ($request->filled('dinning_page_slider_box_' . $lang->code)) {
                $sliders = !empty($request->{'dinning_page_slider_box_' . $lang->code}) ? explode(',', $request->{'dinning_page_slider_box_' . $lang->code}) : [];
            $rules['dinning_page_slider_box_' . $lang->code] = [
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

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $errmsgs = $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }
        }
        foreach ($languages as $lang) {
            $dinning_setting = BasicExtra::where('language_id', $lang->id)->first();
            $dinning_setting->language_id = $lang->id;

            $videoLink = $request->{'dinning_page_video_url_' . $lang->code};
            if (strpos($videoLink, "&") != false) {
                $videoLink = substr($videoLink, 0, strpos($videoLink, "&"));
            }
            $dinning_setting->dinning_page_video_url = $videoLink;

            if ($request->filled('dinning_page_bg_image_' . $lang->code)) {
                $image = $request->{'dinning_page_bg_image_' . $lang->code};
                $extImage = pathinfo($image, PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extImage;
                @mkdir('assets/front/img/dinnings', 775, true);
                @copy($image, 'assets/front/img/dinnings/' . $filename);
                $dinning_setting->dinning_page_bg_image = $filename;
            }

            $dinning_setting->dinning_page_summary = $request->{'dinning_page_summary_' . $lang->code};
            $dinning_setting->is_dinning_bg = $request->{'is_dinning_bg_' . $lang->code};
            $dinning_setting->dinning_page_content = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'dinning_page_content_' . $lang->code});

            $sliders = !empty($request->{'dinning_page_slider_' . $lang->code}) ? explode(',', $request->{'dinning_page_slider_' . $lang->code}) : [];

            // unlink previous slider images
            $curr_dinning_page_slider = $dinning_setting->dinning_page_slider;
            $curr_dinning_page_slider = unserialize($curr_dinning_page_slider);
            foreach ($curr_dinning_page_slider as $img => $pi) {
                @unlink('assets/front/img/dinnings/sliders/' . $pi);
            }
            $list_slider = [];
            foreach ($sliders as $key => $slider) {
                $extSlider = pathinfo($slider, PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extSlider;
                @mkdir('assets/front/img/dinnings/sliders', 775, true);
                @copy($slider, 'assets/front/img/dinnings/sliders/' . $filename);

                $list_slider[] = $filename;
            }
            $dinning_setting->dinning_page_slider = serialize($list_slider);

            $sliders_box = !empty($request->{'dinning_page_slider_box_' . $lang->code}) ? explode(',', $request->{'dinning_page_slider_box_' . $lang->code}) : [];

            $list_slider_box = [];

            // unlink previous slider box images
            $curr_dinning_page_slider_box = $dinning_setting->dinning_page_slider_box;
            $curr_dinning_page_slider_box = unserialize($curr_dinning_page_slider_box);
            foreach ($curr_dinning_page_slider_box as $key_img => $pimg) {
                @unlink('assets/front/img/dinnings/sliders/' . $pimg);
            }

            foreach ($sliders_box as $key1 => $slider_box) {
                $extSlider_box = pathinfo($slider_box, PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extSlider_box;
                @mkdir('assets/front/img/dinnings/sliders', 775, true);
                @copy($slider_box, 'assets/front/img/dinnings/sliders/' . $filename);

                $list_slider_box[] = $filename;
            }
            $dinning_setting->dinning_page_slider_box = serialize($list_slider_box);

            $dinning_setting->save();

        }

        $request->session()->flash('success', 'Settings updated successfully!');
        return back();
    }

    public function index(Request $request)
    {
        $_lang = Language::where('code', $request->language)->first();

        $lang_id = $_lang->id;
        $data['dinnings'] = Dinning::where('language_id', $lang_id)->orderBy('id', 'DESC')->get();

        $data['lang_id'] = $lang_id;
        $data['abe'] = BasicExtended::where('language_id', $lang_id)->first();

        return view('admin.dinning.index', $data);
    }

    public function edit($id)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $dinning = Dinning::findOrFail($id);
        $current_lang_id = $dinning->language_id;
        $data = array();
        $data['language_id'] = $current_lang_id;
        foreach ($languages as $lang) {
            if ($current_lang_id == $lang->id) {
                $data['dinning'][$lang->code] = $dinning;
            } else {
                $data['dinning'][$lang->code] = $dinning->assoc_id > 0 ? Dinning::where('language_id', $lang->id)->where('assoc_id', $dinning->assoc_id)->first() : null;
            }
            if ($data['dinning'][$lang->code] == null) {
                $data['dinning'][$lang->code] = new Dinning;
                $data['scates'][$lang->code] = Dinning::where('language_id', $lang->id)->get();
            }
            $data['abe'][$lang->code] = BasicExtended::where('language_id', $lang->id)->first();
        }
        return view('admin.dinning.edit', $data);
    }

    public function store(Request $request)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        foreach ($languages as $lang) {
            $image = $request->{'image_' . $lang->code};
            $extImage = pathinfo($image, PATHINFO_EXTENSION);

            $messages = [];

            $slug = make_slug($request->{'title_' . $lang->code});

            $rules = [
                'image_' . $lang->code => 'required',
                'title_' . $lang->code => [
                    'required',
                    'max:255',
                    function ($attribute, $value, $fail) use ($slug) {
                        $count_dinnings = Dinning::whereRaw('LOWER(slug) = ?', [strtolower($slug)])->count();
                        if ($count_dinnings > 0) {
                            $fail('The title field must be unique.');
                        }
                    }
                ],
                'pdf_link_' . $lang->code => 'required',
                'serial_number_' . $lang->code => 'required',
                'content_' . $lang->code => 'required',
                'details_page_status_' . $lang->code => 'required',
                'summary_' . $lang->code => 'required',
            ];
            if ($request->filled('image_' . $lang->code)) {
                $rules['image_' . $lang->code] = [
                    function ($attribute, $value, $fail) use ($extImage, $allowedExts) {
                        if (!in_array($extImage, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg, svg image is allowed");
                        }
                    }
                ];
            }

            // if 'theme version' doesn't contain dinning category
            if ($request->{'details_page_status_' . $lang->code} == 0) {
                $rules['content_' . $lang->code] = 'nullable';
            }

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $errmsgs = $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }
        }
        foreach ($languages as $lang) {
            $image = $request->{'image_' . $lang->code};
            $extImage = pathinfo($image, PATHINFO_EXTENSION);
            $dinning = new Dinning;
            $dinning->language_id = $lang->id;
            $dinning->title = $request->{'title_' . $lang->code};
            $slug = make_slug($request->{'title_' . $lang->code});

            if ($request->filled('image_' . $lang->code)) {
                $filename = uniqid() . '.' . $extImage;
                @copy($image, 'assets/front/img/dinnings/' . $filename);
                $dinning->main_image = $filename;
            }

            $dinning->slug = $slug;
            $dinning->pdf_link = str_replace('view', 'preview', $request->{'pdf_link_' . $lang->code});
            $dinning->summary = $request->{'summary_' . $lang->code};
            $dinning->details_page_status = $request->{'details_page_status_' . $lang->code};
            $dinning->meta_description = $request->{'meta_description_' . $lang->code};
            $dinning->meta_keywords = $request->{'meta_keywords_' . $lang->code};
            $dinning->serial_number = $request->{'serial_number_' . $lang->code};
            $dinning->content = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'content_' . $lang->code});
            $dinning->save();
            if ($assoc_id == 0) {
                $assoc_id = $dinning->id;
            }
            if ($assoc_id > 0) {
                $dinning->assoc_id = $assoc_id;
                $dinning->save();
            }
        }
        Session::flash('success', 'Dinning added successfully!');
        return "success";
    }

    public function update(Request $request)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $saved_ids = [];
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        foreach ($languages as $lang) {
            if (!$request->filled('dinning_assoc_id_' . $lang->code) || $request->filled('dinning_id_' . $lang->code)) {
                $slug = make_slug($request->{'title_' . $lang->code});
                $dinning = Dinning::findOrFail($request->{'dinning_id_' . $lang->code});
                $dinningId = $request->{'dinning_id_' . $lang->code};

                $image = $request->{'image_' . $lang->code};
                $extImage = pathinfo($image, PATHINFO_EXTENSION);

                $rules = [
                    'title_' . $lang->code => [
                        'required',
                        'max:255',
                        function ($attribute, $value, $fail) use ($slug, $dinningId, $lang) {
                            $count_dinnings = Dinning::whereRaw('LOWER(slug) = ? AND id <> ? AND language_id = ?', [strtolower($slug), $dinningId, $lang->id])->count();
                            if ($count_dinnings > 0) {
                                $fail('The title field must be unique.');
                            }
                        }
                    ],
                    'content_' . $lang->code => 'required',
                    'serial_number_' . $lang->code => 'required',
                    'pdf_link_' . $lang->code => 'required',
                    'details_page_status_' . $lang->code => 'required',
                    'summary_' . $lang->code => 'required',
                ];

                if ($request->filled('image_' . $lang->code)) {
                    $rules['image_' . $lang->code] = [
                        function ($attribute, $value, $fail) use ($extImage, $allowedExts) {
                            if (!in_array($extImage, $allowedExts)) {
                                return $fail("Only png, jpg, jpeg, svg image is allowed");
                            }
                        }
                    ];
                }

                if ($request->{'details_page_status_' . $lang->code} == 0) {
                    $rules['content_' . $lang->code] = 'nullable';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $errmsgs = $validator->getMessageBag()->add('error', 'true');
                    return response()->json($validator->errors());
                }
            }
        }
        foreach ($languages as $lang) {
            if ($request->filled('dinning_id_' . $lang->code)) {//update
                $image = $request->{'image_' . $lang->code};
                $extImage = pathinfo($image, PATHINFO_EXTENSION);
                $slug = make_slug($request->{'title_' . $lang->code});
                $dinning = Dinning::findOrFail($request->{'dinning_id_' . $lang->code});
                $dinningId = $request->{'dinning_id_' . $lang->code};
                if ($assoc_id == 0) {
                    $assoc_id = $dinningId;
                }
                $dinning->title = $request->{'title_' . $lang->code};
                $dinning->slug = $slug;
                $dinning->summary = $request->{'summary_' . $lang->code};
                $dinning->details_page_status = $request->{'details_page_status_' . $lang->code};
                $dinning->serial_number = $request->{'serial_number_' . $lang->code};
                $dinning->pdf_link = str_replace('view', 'preview', $request->{'pdf_link_' . $lang->code});
                $dinning->meta_keywords = $request->{'meta_keywords_' . $lang->code};
                $dinning->meta_description = $request->{'meta_description_' . $lang->code};
                $dinning->content = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'content_' . $lang->code});

                if ($request->filled('image_' . $lang->code)) {
                    @unlink('assets/front/img/dinnings/' . $dinning->main_image);
                    $filename = uniqid() . '.' . $extImage;
                    @copy($image, 'assets/front/img/dinnings/' . $filename);
                    $dinning->main_image = $filename;
                }

                $dinning->save();
                $saved_ids[] = $dinning->id;
            } else {
                if (!$request->filled('dinning_assoc_id_' . $lang->code)) {//create
                    $image = $request->{'image_' . $lang->code};
                    $extImage = pathinfo($image, PATHINFO_EXTENSION);
                    $slug = make_slug($request->{'title_' . $lang->code});
                    $dinning = new Dinning;
                    $dinning->language_id = $lang->id;
                    $dinning->title = $request->{'title_' . $lang->code};

                    if ($request->filled('image_' . $lang->code)) {
                        $filename = uniqid() . '.' . $extImage;
                        @copy($image, 'assets/front/img/dinnings/' . $filename);
                        $dinning->main_image = $filename;
                    }

                    $dinning->slug = $slug;
                    $dinning->summary = $request->{'summary_' . $lang->code};
                    $dinning->pdf_link = str_replace('view', 'preview', $request->{'pdf_link_' . $lang->code});
                    $dinning->details_page_status = $request->{'details_page_status_' . $lang->code};
                    $dinning->meta_description = $request->{'meta_description_' . $lang->code};
                    $dinning->meta_keywords = $request->{'meta_keywords_' . $lang->code};
                    $dinning->serial_number = $request->{'serial_number_' . $lang->code};
                    $dinning->content = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'content_' . $lang->code});
                    $dinning->save();
                    $saved_ids[] = $dinning->id;
                } else {
                    $saved_ids[] = $request->{'dinning_assoc_id_' . $lang->code};
                }
            }
        }
        foreach ($saved_ids as $saved_id) {
            $dinning = Dinning::findOrFail($saved_id);
            $dinning->assoc_id = $assoc_id;
            $dinning->save();
        }
        Session::flash('success', 'Dinning updated successfully!');
        return "success";
    }

    public function deleteFromMegaMenu($dinning)
    {
        // unset dinning from megamenu for dinning_category = 1
        $megamenu = Megamenu::where('language_id', $dinning->language_id)->where('category', 1)->where('type', 'dinnings');
        if ($megamenu->count() > 0) {
            $megamenu = $megamenu->first();
            $menus = json_decode($megamenu->menus, true);
            $catId = $dinning->scategory->id;
            if (is_array($menus) && array_key_exists("$catId", $menus)) {
                if (in_array($dinning->id, $menus["$catId"])) {
                    $index = array_search($dinning->id, $menus["$catId"]);
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

        // unset dinning from megamenu for dinning_category = 0
        $megamenu = Megamenu::where('language_id', $dinning->language_id)->where('category', 0)->where('type', 'dinnings');
        if ($megamenu->count() > 0) {
            $megamenu = $megamenu->first();
            $menus = json_decode($megamenu->menus, true);
            if (is_array($menus)) {
                if (in_array($dinning->id, $menus)) {
                    $index = array_search($dinning->id, $menus);
                    unset($menus["$index"]);
                    $menus = array_values($menus);
                    $megamenu->menus = json_encode($menus);
                    $megamenu->save();
                }
            }
        }
    }

    public function delete(Request $request)
    {
        $_dinning = Dinning::findOrFail($request->dinning_id);
        if($_dinning->assoc_id > 0) {
            $dinnings = Dinning::where('assoc_id', $_dinning->assoc_id)->get();
            foreach ($dinnings as $dinning) {
                @unlink('assets/front/img/dinnings/' . $dinning->main_image);

                $this->deleteFromMegaMenu($dinning);

                $dinning->delete();
            }
        }else {
            @unlink('assets/front/img/dinnings/' . $_dinning->main_image);

            $this->deleteFromMegaMenu($_dinning);

            $_dinning->delete();
        }

        Session::flash('success', 'Dinning deleted successfully!');
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $_dinning = Dinning::findOrFail($id);
            if($_dinning->assoc_id > 0) {
                $dinnings = Dinning::where('assoc_id', $_dinning->assoc_id)->get();
                foreach ($dinnings as $dinning) {
                    @unlink('assets/front/img/dinnings/' . $dinning->main_image);

                    $this->deleteFromMegaMenu($dinning);

                    $dinning->delete();
                }
            }else {
                @unlink('assets/front/img/dinnings/' . $_dinning->main_image);

                $this->deleteFromMegaMenu($_dinning);

                $_dinning->delete();
            }
        }

        Session::flash('success', 'Dinnings deleted successfully!');
        return "success";
    }

    public function images($id)
    {
        $s_images = BasicExtra::select('dinning_page_slider')->where('language_id', $id)->first();
        $convImages = [];
        $images = unserialize($s_images->dinning_page_slider);

        foreach ($images as $key => $image) {
            $convImages[] = url("assets/front/img/dinnings/sliders/$image");
        }

        return $convImages;
    }

    public function images_box($id)
    {
        $s_images = BasicExtra::select('dinning_page_slider_box')->where('language_id', $id)->first();
        $convImages = [];
        $images = unserialize($s_images->dinning_page_slider_box);

        foreach ($images as $key => $image) {
            $convImages[] = url("assets/front/img/dinnings/sliders/$image");
        }

        return $convImages;
    }

    public function feature(Request $request)
    {
        $dinning = Dinning::find($request->dinning_id);
        $dinning->feature = $request->feature;
        $dinning->save();

        if ($request->feature == 1) {
            Session::flash('success', 'Featured successfully!');
        } else {
            Session::flash('success', 'Unfeatured successfully!');
        }

        return back();
    }
}
