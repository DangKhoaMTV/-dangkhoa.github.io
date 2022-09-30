<?php

namespace App\Http\Controllers\Admin;


use App\BasicExtra;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Page;
use App\Language;
use Session;
use Validator;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->first();
        $lang_id = $lang->id;
        $data['apages'] = Page::where('language_id', $lang_id)->orderBy('id', 'DESC')->get();

        $data['lang_id'] = $lang_id;
        return view('admin.page.index', $data);
    }

    public function settings(Request $request)
    {
        $data['abex'] = BasicExtra::first();

        return view('admin.page.settings', $data);
    }

    public function updateSettings(Request $request)
    {
        $bexs = BasicExtra::all();

        foreach ($bexs as $key => $bex) {
            $bex->custom_page_pagebuilder = $request->custom_page_pagebuilder;
            $bex->save();
        }

        Session::flash('success', "Page settings updated!");
        return back();
    }

    public function create() {
        return view('admin.page.create');
    }

    public function store(Request $request)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $saved_ids = [];
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        foreach ($languages as $lang) {
            $image = $request->{'image_' . $lang->code};
            $extImage = pathinfo($image, PATHINFO_EXTENSION);
            $slug = make_slug($request->{'name_'. $lang->code});

            $messages = [
                'language_id.required' => 'The language field is required',
            ];

            $rules = [
                'name_'. $lang->code => [
                    'required',
                    'max:25',
                    function ($attribute, $value, $fail) use ($slug) {
                        $pages = Page::all();
                        foreach ($pages as $key => $page) {
                            if (strtolower($slug) == strtolower($page->slug)) {
                                $fail('The title field must be unique.');
                            }
                        }
                    }
                ],
                'status_'. $lang->code => 'required',
                'serial_number_'. $lang->code => 'required|integer',
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

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $errmsgs = $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }
        }

        $bex = BasicExtra::firstOrFail();
        foreach ($languages as $lang) {
            $page = new Page;
            $slug = make_slug($request->{'name_'. $lang->code});
            $page->language_id = $lang->id;
            $page->name = $request->{'name_' . $lang->code};
            $page->title = $request->{'breadcrumb_title_' . $lang->code};
            $page->subtitle = $request->{'breadcrumb_subtitle_' . $lang->code};
            $page->slug = $slug;
            $page->status = $request->{'status_' . $lang->code};

            $page->serial_number = $request->{'serial_number_' . $lang->code};
            $page->meta_keywords = $request->{'meta_keywords_' . $lang->code};
            $page->meta_description = $request->{'meta_description_' . $lang->code};
            if ($bex->custom_page_pagebuilder == 0) {
                $page->body = $request->{'body_' . $lang->code};

                $videoLink = $request->{'video_url_' . $lang->code};
                if (strpos($videoLink, "&") != false) {
                    $videoLink = substr($videoLink, 0, strpos($videoLink, "&"));
                }
                $page->video_url = $videoLink;
                if ($request->filled('image_' . $lang->code)) {
                    $image = $request->{'image_' . $lang->code};
                    $extImage = pathinfo($image, PATHINFO_EXTENSION);
                    $filename = uniqid() . '.' . $extImage;
                    @mkdir('assets/front/img/Signature', 775, true);
                    @copy($image, 'assets/front/img/Signature/' . $filename);
                    $page->image = $filename;
                    $page->image_name = $request->{'image_name_' . $lang->code};
                }
            }
            $page->save();
            if($assoc_id == 0){
                $assoc_id = $page->id;
            }

            $saved_ids[] = $page->id;
        }
        foreach ($saved_ids as $saved_id) {
            $page = Page::findOrFail($saved_id);
            $page->assoc_id = $assoc_id;
            $page->save();
        }

        Session::flash('success', 'Page created successfully!');
        return "success";
    }

    public function edit($pageID)
    {
        $page = Page::findOrFail($pageID);
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $current_lang = Language::where('id', $page->language_id)->first();
        $data['langs'] = $languages;
        foreach ($languages as $lang) {
            if ($current_lang->id == $lang->id) {
                $data['page'][$lang->code] = $page;
            } else {
                $data['page'][$lang->code] = $page->assoc_id > 0 ? Page::where('language_id', $lang->id)->where('assoc_id', $page->assoc_id)->first() : null;
            }
            if ($data['page'][$lang->code] == null) {
                $data['page'][$lang->code] = new Page();
                $data['pcates'][$lang->code] = Page::where('language_id', $lang->id)->get();
            }
        }

        return view('admin.page.edit', $data);
    }

    public function update(Request $request)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $saved_ids = [];
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        foreach ($languages as $lang) {
            if ($request->filled('pageid_' . $lang->code) || !$request->filled('page_assoc_id_' . $lang->code)) {//Validation
                $slug = make_slug($request->{'name_' . $lang->code});
                $pageID = $request->{'pageid_' . $lang->code};
                $image = $request->{'image_' . $lang->code};
                $extImage = pathinfo($image, PATHINFO_EXTENSION);
                $rules = [
                    'name_' . $lang->code => [
                        'required',
                        'max:25',
                        function ($attribute, $value, $fail) use ($slug, $pageID) {
                            $pages = Page::all();
                            foreach ($pages as $key => $page) {
                                if ($page->id != $pageID && strtolower($slug) == strtolower($page->slug)) {
                                    $fail('The title field must be unique.');
                                }
                            }
                        }
                    ],
                    'status_' . $lang->code => 'required',
                    'serial_number_' . $lang->code => 'required|integer',
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


                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $errmsgs = $validator->getMessageBag()->add('error', 'true');
                    return response()->json($validator->errors());
                }
            }
        }

        $bex = BasicExtra::firstOrFail();
        foreach ($languages as $lang) {
            if ($request->filled('pageid_' . $lang->code)) {//update
                $pageID = $request->{'pageid_' . $lang->code};
                $page = Page::findOrFail($pageID);
                $slug = make_slug($request->{'name_' . $lang->code});

                $page->name = $request->{'name_' . $lang->code};
                $page->title = $request->{'breadcrumb_title_' . $lang->code};
                $page->subtitle = $request->{'breadcrumb_subtitle_' . $lang->code};
                $page->slug = $slug;
                $page->status = $request->{'status_' . $lang->code};
                $page->serial_number = $request->{'serial_number_' . $lang->code};
                $page->meta_keywords = $request->{'meta_keywords_' . $lang->code};
                $page->meta_description = $request->{'meta_description_' . $lang->code};
                if ($bex->custom_page_pagebuilder == 0) {
                    $page->body = $request->{'body_' . $lang->code};

                    $videoLink = $request->{'video_url_' . $lang->code};
                    if (strpos($videoLink, "&") != false) {
                        $videoLink = substr($videoLink, 0, strpos($videoLink, "&"));
                    }
                    $page->video_url = $videoLink;
                    if ($request->filled('image_' . $lang->code)) {
                        $image = $request->{'image_' . $lang->code};
                        $extImage = pathinfo($image, PATHINFO_EXTENSION);
                        $filename = uniqid() . '.' . $extImage;
                        @mkdir('assets/front/img/Signature', 775, true);
                        @copy($image, 'assets/front/img/Signature/' . $filename);
                        $page->image = $filename;
                        $page->image_name = $request->{'image_name_' . $lang->code};
                    }
                }

                if($assoc_id == 0){
                    $assoc_id = $page->id;
                }
                $page->assoc_id = $assoc_id;
                $page->save();

            }else {
                if (!$request->filled('page_assoc_id_' . $lang->code)) {//create
                    $page = new Page;
                    $slug = make_slug($request->{'name_' . $lang->code});

                    $page->language_id = $lang->id;
                    $page->name = $request->{'name_' . $lang->code};
                    $page->title = $request->{'breadcrumb_title_' . $lang->code};
                    $page->subtitle = $request->{'breadcrumb_subtitle_' . $lang->code};
                    $page->slug = $slug;
                    $page->status = $request->{'status_' . $lang->code};
                    $page->serial_number = $request->{'serial_number_' . $lang->code};
                    $page->meta_keywords = $request->{'meta_keywords_' . $lang->code};
                    $page->meta_description = $request->{'meta_description_' . $lang->code};
                    if ($bex->custom_page_pagebuilder == 0) {
                        $page->body = $request->{'body_' . $lang->code};

                        $videoLink = $request->{'video_url_' . $lang->code};
                        if (strpos($videoLink, "&") != false) {
                            $videoLink = substr($videoLink, 0, strpos($videoLink, "&"));
                        }
                        $page->video_url = $videoLink;
                        if ($request->filled('image_' . $lang->code)) {
                            $image = $request->{'image_' . $lang->code};
                            $extImage = pathinfo($image, PATHINFO_EXTENSION);
                            $filename = uniqid() . '.' . $extImage;
                            @mkdir('assets/front/img/Signature', 775, true);
                            @copy($image, 'assets/front/img/Signature/' . $filename);
                            $page->image = $filename;
                            $page->image_name = $request->{'image_name_' . $lang->code};
                        }
                    }

                    $page->save();
                    $saved_ids[] = $page->id;
                }else {
                    $saved_ids[] = $request->{'page_assoc_id_' . $lang->code};
                }
            }
        }
        foreach ($saved_ids as $saved_id) {
            $page = Page::findOrFail($saved_id);
            $page->assoc_id = $assoc_id;
            $page->save();
        }
        Session::flash('success', 'Page updated successfully!');
        return "success";
    }

    public function delete(Request $request)
    {
        $pageID = $request->pageid;
        $_page = Page::findOrFail($pageID);
        if($_page->assoc_id > 0) {
            $pages = Page::where('assoc_id', $_page->assoc_id)->get();
            foreach ($pages as $page) {
                $page->delete();
            }
        }else {
            $_page->delete();
        }
        Session::flash('success', 'Page deleted successfully!');
        return redirect()->back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $_page = Page::findOrFail($id);
            if($_page->assoc_id > 0) {
                $pages = Page::where('assoc_id', $_page->assoc_id)->get();
                foreach ($pages as $page) {
                    $page->delete();
                }
            }else {
                $_page->delete();
            }
        }

        Session::flash('success', 'Pages deleted successfully!');
        return "success";
    }

    public function uploadPbImage(Request $request)
    {
        $files = $request->file('files');
        $assets = [];

        foreach ($files as $key => $file) {
            $directory = "assets/front/img/pagebuilder/";
            @mkdir($directory, 0775, true);
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($directory, $filename);


            $path = url($directory. $filename);
            $name = $file->getClientOriginalName();

            $assets[] = [
                'name' => $name,
                'type' => 'image',
                'src' =>  $path,
                'height' => 350,
                'width' => 250
            ];
        }

        return response()->json(['data' => $assets]);
    }

    public function removePbImage(Request $request) {
        $path = str_replace(url('/') . '/', '', $request->path);
        @unlink($path);
    }

    public function uploadPbTui(Request $request) {
        $image = $request->base_64;  // your base64 encoded
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = uniqid().'.'.'png';

        $path = 'assets/front/img/pagebuilder/' . $imageName;
        \File::put($path, base64_decode($image));

        $assets[] = [
            'name' => $imageName,
            'type' => 'image',
            'src' =>  url($path),
            'height' => 350,
            'width' => 250
        ];

        return response()->json(['data' => $assets]);
    }
}
