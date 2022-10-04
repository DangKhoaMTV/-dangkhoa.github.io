<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Language;
use App\Testimonial;
use App\BasicSetting as BS;
use Validator;
use Session;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $_lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $_lang->id;
        $languages = Language::orderBy('is_default', 'DESC')->get();
        foreach ($languages as $lang){
            $data['abs'][$lang->code] = $lang->basic_setting;
        }

        $data['testimonials'] = Testimonial::where('language_id', $data['lang_id'])->orderBy('id', 'DESC')->get();

        return view('admin.home.testimonial.index', $data);
    }

    public function edit($id)
    {
        $data['testimonial'] = Testimonial::findOrFail($id);
        return view('admin.home.testimonial.edit', $data);
    }
    public function edit_modal($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $current_lang = Language::where('id',$testimonial->language_id)->first();
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $data= array();
        //$data['langs'] = $languages;
        foreach ($languages as $lang) {
            if($current_lang->id == $lang->id){
                $data['testimonial'][$lang->code] = $testimonial;
            }else{
                $data['testimonial'][$lang->code] = $testimonial->assoc_id>0?Testimonial::where('language_id',$lang->id)->where('assoc_id',$testimonial->assoc_id)->first():null;
            }
            if($data['testimonial'][$lang->code]==null){
                $data['testimonial'][$lang->code] = new Testimonial;
                $data['scates'][$lang->code] = Testimonial::where('language_id',$lang->id)->get();
            }
        }
        return view('admin.home.testimonial.edit-modal', $data);
    }

    public function store(Request $request)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        foreach ($languages as $lang) {
            $image = $request->{'image_'.$lang->code};
            $extImage = pathinfo($image, PATHINFO_EXTENSION);

            $messages = [
                /*'language_id.required' => 'The language field is required'*/
            ];

            $rules = [
                //'language_id' => 'required',
                'image_'.$lang->code => 'required',
                'comment_'.$lang->code => 'required',
                'name_'.$lang->code => 'required|max:50',
                'rank_'.$lang->code => 'required|max:50',
                'channel_'.$lang->code => 'required|max:50',
                'serial_number_'.$lang->code => 'required|integer',
            ];

            if ($request->filled('image_'.$lang->code)) {
                $rules['image_'.$lang->code] = [
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
            $image = $request->{'image_' . $lang->code};
            $extImage = pathinfo($image, PATHINFO_EXTENSION);
            $testimonial = new Testimonial;
            $testimonial->language_id = $lang->id;
            $testimonial->comment = $request->{'comment_'.$lang->code};
            $testimonial->name = $request->{'name_'.$lang->code};
            $testimonial->rank = $request->{'rank_'.$lang->code};
            $testimonial->channel = $request->{'channel_'.$lang->code};
            $testimonial->image = $request->{'testimonial_image_'.$lang->code};
            $testimonial->serial_number = $request->{'serial_number_'.$lang->code};

            if ($request->filled('image_' . $lang->code)) {
                $filename = uniqid() . '.' . $extImage;
                @copy($image, 'assets/front/img/testimonials/' . $filename);
                $testimonial->image = $filename;
            }

            $testimonial->save();
            if($assoc_id==0){
                $assoc_id = $testimonial->id;
            }
            if($assoc_id>0){
                $testimonial->assoc_id = $assoc_id;
                $testimonial->save();
            }
        }
        Session::flash('success', 'Testimonial added successfully!');
        if($request->ajax())
            return "success";
        return back();
    }

    public function update(Request $request)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $saved_ids = [];
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        foreach ($languages as $lang) {
            $image = $request->{'image_' . $lang->code};
            $extImage = pathinfo($image, PATHINFO_EXTENSION);
            if($request->filled('testimonial_id_'.$lang->code)||!$request->filled('testimonial_assoc_id_'.$lang->code)) {
                $rules = [
                    'comment_'.$lang->code => 'required',
                    'name_'.$lang->code => 'required|max:50',
                    'rank_'.$lang->code => 'required|max:50',
                    'channel_'.$lang->code => 'required|max:50',
                    'serial_number_'.$lang->code => 'required|integer',
                ];
            }else{
                $rules = [];
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
        foreach ($languages as $lang) {
            if($request->filled('testimonial_id_'.$lang->code)) {
                $image = $request->{'image_' . $lang->code};
                $extImage = pathinfo($image, PATHINFO_EXTENSION);
                $testimonial = Testimonial::findOrFail($request->{'testimonial_id_' . $lang->code});
                if($assoc_id==0){
                    $assoc_id = $testimonial->id;
                }
                $testimonial->comment = $request->{'comment_' . $lang->code};
                $testimonial->name = $request->{'name_' . $lang->code};
                $testimonial->rank = $request->{'rank_' . $lang->code};
                $testimonial->channel = $request->{'channel_' . $lang->code};
                $testimonial->serial_number = $request->{'serial_number_' . $lang->code};

                if ($request->filled('image_' . $lang->code)) {
                    @unlink('assets/front/img/testimonials/' . $testimonial->image);
                    $filename = uniqid() . '.' . $extImage;
                    @copy($image, 'assets/front/img/testimonials/' . $filename);
                    $testimonial->image = $filename;
                }
                $testimonial->save();
                $saved_ids[] = $testimonial->id;
            }
            else {
                if (!$request->filled('testimonial_assoc_id_' . $lang->code)) {
                    $image = $request->{'image_' . $lang->code};
                    $extImage = pathinfo($image, PATHINFO_EXTENSION);
                    $testimonial = new Testimonial;
                    $testimonial->language_id = $lang->id;
                    $testimonial->comment = $request->{'comment_'.$lang->code};
                    $testimonial->name = $request->{'name_'.$lang->code};
                    $testimonial->rank = $request->{'rank_'.$lang->code};
                    $testimonial->channel = $request->{'channel_'.$lang->code};
                    $testimonial->image = $request->{'testimonial_image_'.$lang->code};
                    $testimonial->serial_number = $request->{'serial_number_'.$lang->code};

                    if ($request->filled('image_' . $lang->code)) {
                        $filename = uniqid() . '.' . $extImage;
                        @copy($image, 'assets/front/img/testimonials/' . $filename);
                        $testimonial->image = $filename;
                    }

                    $testimonial->save();
                    $saved_ids[] = $testimonial->id;
                }else{
                    $saved_ids[] = $request->{'testimonial_assoc_id_'.$lang->code};
                }
            }
        }
        foreach ($saved_ids as $saved_id){
            $testimonial = Testimonial::findOrFail($saved_id);
            $testimonial->assoc_id = $assoc_id;
            $testimonial->save();
        }
        Session::flash('success', 'Testimonial updated successfully!');
        if($request->ajax())
            return "success";
        return back();
    }

    public function textupdate(Request $request, $langid)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        foreach ($languages as $lang){
            $request->validate([
                'testimonial_section_title_'.$lang->code => 'required|max:25',
                'testimonial_section_subtitle_'.$lang->code => 'required|max:255',
            ]);

            $bs = BS::where('language_id', $lang->id)->firstOrFail();
            $bs->testimonial_title = $request->{'testimonial_section_title_'.$lang->code};
            $bs->testimonial_subtitle = $request->{'testimonial_section_subtitle_'.$lang->code};
            $bs->save();
        }

        Session::flash('success', 'Text updated successfully!');
        if($request->ajax())
            return "success";
        return back();
    }

    public function delete(Request $request)
    {
        $maintestimonial = Testimonial::findOrFail($request->testimonial_id);
        if($maintestimonial->assoc_id>0){
            $testimonials = Testimonial::where('assoc_id',$maintestimonial->assoc_id)->get();
            foreach ($testimonials as $testimonial){
                $testimonial1 = Testimonial::findOrFail($testimonial->id);
                @unlink('assets/front/img/testimonials/' . $testimonial1->image);
                $testimonial1->delete();
            }
        }else{
            @unlink('assets/front/img/testimonials/' . $maintestimonial->image);
            $maintestimonial->delete();
        }


        Session::flash('success', 'Testimonial deleted successfully!');
        if($request->ajax())
            return "success";
        return back();
    }
}
