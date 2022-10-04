<?php

namespace App\Http\Controllers\Admin;

use App\BasicExtended;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Language;
use App\Feature;
use Validator;
use Session;

class FeatureController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->first();
        $lang_id = $lang->id;
        $data['features'] = Feature::where('language_id', $lang_id)->orderBy('id', 'DESC')->get();
        $data['lang_id'] = $lang_id;

        return view('admin.home.feature.index', $data);
    }

    public function edit($id)
    {
        $data['feature'] = Feature::findOrFail($id);
        return view('admin.home.feature.edit', $data);
    }
    public function edit_modal($id)
    {
        $feature = Feature::findOrFail($id);
        $current_lang = Language::where('id',$feature->language_id)->first();
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $data= array();
        $data['langs'] = $languages;
        foreach ($languages as $lang) {
            if($current_lang->id == $lang->id){
                $data['feature'][$lang->code] = $feature;
            }else{
                $data['feature'][$lang->code] = $feature->assoc_id>0?Feature::where('language_id',$lang->id)->where('assoc_id',$feature->assoc_id)->first():null;
            }
            if($data['feature'][$lang->code]==null){
                $data['feature'][$lang->code] = new Feature;
                $data['scates'][$lang->code] = Feature::where('language_id',$lang->id)->get();
            }
        }
        return view('admin.home.feature.edit-modal', $data);
    }

    public function store(Request $request)
    {
        $count = Feature::where('language_id', $request->language_id)->count();
        if ($count == 4) {
            Session::flash('warning', 'You cannot add more than 4 features!');
            return "success";
        }
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id= 0;
        $messages = [];
        $be = BasicExtended::select('theme_version')->first();
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        foreach ($languages as $lang) {
            $type = $request->{'type_'.$lang->code};
            $rules = [
                'type_'.$lang->code => 'required',
                'title_'.$lang->code => 'required|max:50',
                'color_'.$lang->code => 'required',
                'serial_number_'.$lang->code => 'required|integer',
            ];

            if($type == 'icon'){
                $rules['icon_'.$lang->code] = 'required';
            }

            if ($request->filled('image_' . $lang->code)) {
                $image = $request->{'image_' . $lang->code};
                $extImage = pathinfo($image, PATHINFO_EXTENSION);
                $rules['image_' . $lang->code] = [
                    function ($attribute, $value, $fail) use ($extImage, $allowedExts) {
                        if (!in_array($extImage, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg, svg image is allowed");
                        }
                    }
                ];
            }

            if ($be->theme_version == 'car') {
                $rules['color_'.$lang->code] = 'nullable';
            }

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $errmsgs = $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }
        }
        foreach ($languages as $lang) {
            $feature = new Feature;
            $feature->type = $request->{'type_'.$lang->code};
            $feature->icon = $request->{'icon_'.$lang->code};
            $feature->language_id = $lang->id;
            $feature->title = $request->{'title_'.$lang->code};

            if ($be->theme_version != 'car') {
                $feature->color = $request->{'color_'.$lang->code};
            }

            $image = $request->{'image_' . $lang->code};
            $extImage = pathinfo($image, PATHINFO_EXTENSION);

            if ($request->filled('image_' . $lang->code)) {
                $filename = uniqid() . '.' . $extImage;
                @mkdir('assets/front/img/featured', 775, true);
                @copy($image, 'assets/front/img/featured/' . $filename);
                $feature->image = $filename;
            }

            $feature->serial_number = $request->{'serial_number_'.$lang->code};
            $feature->save();
            if($assoc_id>0){
                $feature->assoc_id = $assoc_id;
            }
            $feature->save();
            if($assoc_id==0){
                $assoc_id = $feature->assoc_id = $feature->id;
                $feature->save();
            }
        }
        Session::flash('success', 'Feature added successfully!');
        if($request->ajax())
            return "success";
        return back();
    }

    public function update(Request $request)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id= 0;
        $saved_ids = [];
        $be = BasicExtended::select('theme_version')->first();
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        foreach ($languages as $lang) {
            $type = $request->{'type_'.$lang->code};
            if(!$request->filled('feature_assoc_id_' . $lang->code)||$request->filled('feature_id_'.$lang->code)){
                $rules = [
                    'title_' . $lang->code => 'required|max:50',
                    'type_' . $lang->code => 'required',
                    'color_' . $lang->code => 'required',
                    'serial_number_' . $lang->code => 'required|integer',
                ];
            }else{
                $rules = [];
            }
            if ($be->theme_version == 'car') {
                $rules['color_' . $lang->code] = 'nullable';
            }

            if($type == 'icon'){
                $rules['icon_'.$lang->code] = 'required';
            }

            if ($request->filled('image_' . $lang->code)) {
                $image = $request->{'image_' . $lang->code};
                $extImage = pathinfo($image, PATHINFO_EXTENSION);
                $rules['image_' . $lang->code] = [
                    function ($attribute, $value, $fail) use ($extImage, $allowedExts) {
                        if (!in_array($extImage, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg, svg image is allowed");
                        }
                    }
                ];
            }

            $request->validate($rules);
        }
        foreach ($languages as $lang) {
            if($request->filled('feature_id_'.$lang->code)) {
                $feature = Feature::findOrFail($request->{'feature_id_' . $lang->code});
                if($assoc_id==0){
                    $assoc_id = $feature->id;
                }
                $feature->type = $request->{'type_'.$lang->code};
                $feature->icon = $request->{'icon_' . $lang->code};
                $feature->title = $request->{'title_' . $lang->code};

                if ($be->theme_version != 'car') {
                    $feature->color = $request->{'color_' . $lang->code};
                }

                if ($request->filled('image_' . $lang->code)) {
                    $image = $request->{'image_' . $lang->code};
                    $extImage = pathinfo($image, PATHINFO_EXTENSION);
                    @unlink('assets/front/img/featured/' . $feature->image);
                    $filename = uniqid() . '.' . $extImage;
                    @copy($image, 'assets/front/img/featured/' . $filename);
                    $feature->image = $filename;
                }

                $feature->serial_number = $request->{'serial_number_' . $lang->code};
                $feature->save();
                $saved_ids[] = $feature->id;
            }else {
                if (!$request->filled('feature_assoc_id_' . $lang->code)) {
                    $feature = new Feature;
                    $feature->type = $request->{'type_'.$lang->code};
                    $feature->icon = $request->{'icon_'.$lang->code};
                    $feature->language_id = $lang->id;
                    $feature->title = $request->{'title_'.$lang->code};

                    if ($be->theme_version != 'car') {
                        $feature->color = $request->{'color_'.$lang->code};
                    }

                    if ($request->filled('image_' . $lang->code)) {
                        $image = $request->{'image_' . $lang->code};
                        $extImage = pathinfo($image, PATHINFO_EXTENSION);
                        @unlink('assets/front/img/featured/' . $feature->image);
                        $filename = uniqid() . '.' . $extImage;
                        @copy($image, 'assets/front/img/featured/' . $filename);
                        $feature->image = $filename;
                    }

                    $feature->serial_number = $request->{'serial_number_'.$lang->code};
                    $feature->save();
                    $saved_ids[] = $feature->id;
                }
                else{
                    $saved_ids[] = $request->{'feature_assoc_id_'.$lang->code};
                }
            }
        }
        foreach ($saved_ids as $saved_id){
            $feature = Feature::findOrFail($saved_id);
            $feature->assoc_id = $assoc_id;
            $feature->save();
        }
        Session::flash('success', 'Feature updated successfully!');
        if($request->ajax())
            return "success";
        return back();
    }

    public function delete(Request $request)
    {

        $mainfeature = Feature::findOrFail($request->feature_id);
        if($mainfeature->assoc_id>0){
            $features = Feature::where('assoc_id',$mainfeature->assoc_id)->get();
            foreach ($features as $feature){
                $feature1 = Feature::findOrFail($feature->id);
                $feature1->delete();
            }
        }else{
            $mainfeature->delete();
        }

        Session::flash('success', 'Feature deleted successfully!');
        if($request->ajax())
            return "success";
        return back();
    }
}
