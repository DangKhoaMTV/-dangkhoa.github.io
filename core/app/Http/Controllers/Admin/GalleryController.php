<?php

namespace App\Http\Controllers\Admin;

use App\BasicExtra;
use App\Gallery;
use App\GalleryCategory;
use App\Http\Controllers\Controller;
use App\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $lang = Language::where('code', $request->language)->first();

        $lang_id = $lang->id;

        foreach ($languages as $lang) {
            $data['categories'][$lang->code] = GalleryCategory::where('language_id', $lang->id)->orderBy('id', 'DESC')->get();
        }
        $data['galleries'] = Gallery::where('language_id', $lang_id)->orderBy('id', 'DESC')->get();
        if($request->type && ($request->type == 'image' || $request->type == 'video')){
            $data['galleries'] = Gallery::where('language_id', $lang_id)->where('type', $request->type)->orderBy('id', 'DESC')->get();
        }

        $data['langs'] = $languages;
        $data['lang_id'] = $lang_id;

        $data['categoryInfo'] = BasicExtra::first();

        return view('admin.gallery.index', $data);
    }

    public function edit(Request $request, $id)
    {
        $lang = Language::where('code', $request->language)->first();

        $data['categories'] = GalleryCategory::where('language_id', $lang->id)
            ->where('status', 1)
            ->get();

        $data['gallery'] = Gallery::findOrFail($id);

        $data['categoryInfo'] = BasicExtra::first();

        return view('admin.gallery.edit', $data);
    }

   public function edit_modal(Request $request, $id)
    {
        $_lang = Language::where('code', $request->language)->first();
        $gallery = Gallery::findOrFail($id);
        $current_lang = Language::where('id', $gallery->language_id)->first();
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $data = array();
        $data['langs'] = $languages;

        foreach ($languages as $lang) {
            if ($current_lang->id == $lang->id) {
                $data['gallery'][$lang->code] = $gallery;
            } else {
                $data['gallery'][$lang->code] = $gallery->assoc_id > 0 ? Gallery::where('language_id', $lang->id)->where('assoc_id', $gallery->assoc_id)->first() : null;
            }
            if ($data['gallery'][$lang->code] == null) {
                $data['gallery'][$lang->code] = new Gallery;
                $data['gcates'][$lang->code] = Gallery::where('language_id', $lang->id)->get();
            }
            $data['categories'][$lang->code] = GalleryCategory::where('language_id', $lang->id)
                ->where('status', 1)
                ->get();
        }

        $data['categoryInfo'] = BasicExtra::first();

        return view('admin.gallery.edit-modal', $data);
    }

    public function getCategories($langId)
    {
        $gallery_categories = GalleryCategory::where('language_id', $langId)
            ->where('status', 1)
            ->get();

        return $gallery_categories;
    }

    public function store(Request $request)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $categoryInfo = BasicExtra::first();

        $assoc_id = 0;
        $saved_ids = [];
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        foreach ($languages as $lang) {
            $image = $request->{'image_' . $lang->code};
            $type = $request->{'type_' . $lang->code};
            $extImage = pathinfo($image, PATHINFO_EXTENSION);

            $messages = [
                'language_id.required' => 'The language field is required',
            ];

            if ($categoryInfo->gallery_category_status == 1) {
                $messages['category_id.required'] = 'The category field is required';
            }

            $rules = [
                'image_' . $lang->code => 'required',
                'title_' . $lang->code => 'required|max:255',
                'serial_number_' . $lang->code => 'required|integer',
            ];

            if ($categoryInfo->gallery_category_status == 1) {
                $rules['category_id_' . $lang->code] = 'required';
            }

            if($type == 'video'){
                $rules['video_url_' . $lang->code] = 'required';
            }

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
        foreach ($languages as $lang) {
            $gallery = new Gallery;
            $gallery->language_id = $lang->id;
            $gallery->title = $request->{'title_' . $lang->code};
            $gallery->serial_number = $request->{'serial_number_' . $lang->code};
            $gallery->category_id = $request->{'category_id_' . $lang->code};
            $type = $request->{'type_' . $lang->code};
            $gallery->type = $request->{'type_' . $lang->code};
            if($type == 'video'){
                $videoLink = $request->{'video_url_' . $lang->code};
                if (strpos($videoLink, "&") != false) {
                    $videoLink = substr($videoLink, 0, strpos($videoLink, "&"));
                }
                $gallery->video_url = $videoLink;
            }
            $image = $request->{'image_' . $lang->code};
            $extImage = pathinfo($image, PATHINFO_EXTENSION);

            if ($request->filled('image_' . $lang->code)) {
                $filename = uniqid() . '.' . $extImage;
                @mkdir('assets/front/img/gallery', 775, true);
                @copy($image, 'assets/front/img/gallery/' . $filename);
                $gallery->image = $filename;
            }

            $gallery->save();
            if($assoc_id == 0){
                $assoc_id = $gallery->id;
            }

            $saved_ids[] = $gallery->id;
        }
        foreach ($saved_ids as $saved_id) {
            $gallery = Gallery::findOrFail($saved_id);
            $gallery->assoc_id = $assoc_id;
            $gallery->save();
        }
        Session::flash('success', 'Image added successfully!');
        return "success";
    }

    public function update(Request $request)
    {
        $categoryInfo = BasicExtra::first();
        $message = [];

        if ($categoryInfo->gallery_category_status == 1) {
            $message['category_id.required'] = 'The category field is required';
        }
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        $assoc_id = 0;
        $saved_ids = [];

        foreach ($languages as $lang) {
            $type = $request->{'type_' . $lang->code};
            if ($request->filled('gallery_id_' . $lang->code) || !$request->filled('gallery_assoc_id_' . $lang->code)) {//Validation
                $image = $request->{'image_' . $lang->code};
                $extImage = pathinfo($image, PATHINFO_EXTENSION);

                $rules = [
                    'title_' . $lang->code => 'required|max:255',
                    'serial_number_' . $lang->code => 'required|integer',
                ];

                if($type == 'video'){
                    $rules['video_url_' . $lang->code] = 'required';
                }

                if ($categoryInfo->gallery_category_status == 1) {
                    $rules['category_id_' . $lang->code] = 'required';
                }

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

        foreach ($languages as $lang) {
            if ($request->filled('gallery_id_' . $lang->code)) {//update

                $gallery = Gallery::findOrFail($request->{'gallery_id_' . $lang->code});
                $gallery->title = $request->{'title_' . $lang->code};
                $gallery->serial_number = $request->{'serial_number_' . $lang->code};
                $gallery->category_id = $request->{'category_id_' . $lang->code};

                $galleryId = $request->{'gallery_id_' . $lang->code};
                if ($assoc_id == 0) {
                    $assoc_id = $galleryId;
                }

                $gallery->assoc_id = $assoc_id;

                $image = $request->{'image_' . $lang->code};
                $extImage = pathinfo($image, PATHINFO_EXTENSION);
                if ($request->filled('image_' . $lang->code)) {
                    @unlink('assets/front/img/gallery/' . $gallery->image);
                    $filename = uniqid() . '.' . $extImage;
                    if(!is_dir('assets/front/img/gallery/')){
                        mkdir('assets/front/img/gallery/',0755, true);
                    }
                    @copy($image, 'assets/front/img/gallery/' . $filename);
                    $gallery->image = $filename;
                }

                $gallery->type = $request->{'type_' . $lang->code};
                if($request->{'type_' . $lang->code} == 'video'){
                    $videoLink = $request->{'video_url_' . $lang->code};
                    if (strpos($videoLink, "&") != false) {
                        $videoLink = substr($videoLink, 0, strpos($videoLink, "&"));
                    }
                    $gallery->video_url = $videoLink;
                }

                $gallery->save();

            }else {
                if (!$request->filled('gallery_assoc_id_' . $lang->code)) {//create
                    $gallery = new Gallery;
                    $gallery->title = $request->{'title_' . $lang->code};
                    $gallery->serial_number = $request->{'serial_number_' . $lang->code};
                    $gallery->category_id = $request->{'category_id_' . $lang->code};

                    $galleryId = $request->{'gallery_id_' . $lang->code};
                    if ($assoc_id == 0) {
                        $assoc_id = $galleryId;
                    }

                    $gallery->assoc_id = $assoc_id;

                    $image = $request->{'image_' . $lang->code};
                    $extImage = pathinfo($image, PATHINFO_EXTENSION);
                    if ($request->filled('image_' . $lang->code)) {
                        @unlink('assets/front/img/gallery/' . $gallery->image);
                        $filename = uniqid() . '.' . $extImage;
                        if(!is_dir('assets/front/img/gallery/')){
                            mkdir('assets/front/img/gallery/',0755,true);
                        }
                        @copy($image, 'assets/front/img/gallery/' . $filename);
                        $gallery->image = $filename;
                    }

                    $gallery->type = $request->{'type_' . $lang->code};
                    if($request->{'type_' . $lang->code} == 'video'){
                        $videoLink = $request->{'video_url_' . $lang->code};
                        if (strpos($videoLink, "&") != false) {
                            $videoLink = substr($videoLink, 0, strpos($videoLink, "&"));
                        }
                        $gallery->video_url = $videoLink;
                    }

                    $gallery->save();
                    $saved_ids[] = $gallery->id;
                }else {
                    $saved_ids[] = $request->{'gallery_assoc_id_' . $lang->code};
                }
            }
        }
        foreach ($saved_ids as $saved_id) {
            $gallery = Gallery::findOrFail($saved_id);
            $gallery->assoc_id = $assoc_id;
            $gallery->save();
        }
        Session::flash('success', 'Gallery updated successfully!');
        return "success";
    }

    public function delete(Request $request)
    {
        $_gallery = Gallery::findOrFail($request->gallery_id);
        if($_gallery->assoc_id > 0) {
            $galleries = Gallery::where('assoc_id', $_gallery->assoc_id)->get();
            foreach ($galleries as $gallery) {
                @unlink('assets/front/img/gallery/' . $gallery->image);
                $gallery->delete();
            }
        }else {
            @unlink('assets/front/img/gallery/' . $_gallery->image);
            $_gallery->delete();
        }

        Session::flash('success', 'Image deleted successfully!');
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $_gallery = Gallery::findOrFail($id);
            if($_gallery->assoc_id > 0) {
                $galleries = Gallery::where('assoc_id', $_gallery->assoc_id)->get();
                foreach ($galleries as $gallery) {
                    @unlink('assets/front/img/gallery/' . $gallery->image);
                    $gallery->delete();
                }
            }else {
                @unlink('assets/front/img/gallery/' . $_gallery->image);
                $_gallery->delete();
            }
        }

        Session::flash('success', 'Image deleted successfully!');
        return "success";
    }
}
