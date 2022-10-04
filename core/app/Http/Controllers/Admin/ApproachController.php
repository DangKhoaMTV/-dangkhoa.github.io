<?php

namespace App\Http\Controllers\Admin;

use App\BasicExtended;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BasicSetting as BS;
use App\Point;
use App\Language;
use Session;
use Validator;

class ApproachController extends Controller
{
    public function index(Request $request)
    {
        $_lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $_lang->id;
        $languages = Language::all();
        foreach ($languages as $lang) {
            $data['abs'][$lang->code] = $lang->basic_setting;
        }
        $data['points'] = Point::where('language_id', $data['lang_id'])->orderBy('id', 'DESC')->get();

        return view('admin.home.approach.index', $data);
    }

    public function store(Request $request)
    {
        $languages = Language::all();
        $assoc_id= 0;
        $saved_ids = [];
        $messages = [
            /*'language_id.required' => 'The language field is required'*/
        ];
        $be = BasicExtended::first();
        $version = $be->theme_version;
        foreach ($languages as $lang) {
            $rules = [
                //'language_id' => 'required',
                'title_'.$lang->code => 'required',
                'short_text_'.$lang->code => 'required',
                'serial_number_'.$lang->code => 'required|integer',
            ];


            if ($version == 'cleaning') {
                $rules['color_'.$lang->code] = 'required';
            }

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $errmsgs = $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }
        }
        foreach ($languages as $lang) {
            $point = new Point;
            $point->language_id = $lang->id;
            $point->icon = $request->{'icon_' . $lang->code};
            if ($version == 'cleaning') {
                $point->color = $request->{'color_' . $lang->code};
            }
            $point->title = $request->{'title_' . $lang->code};
            $point->short_text = $request->{'short_text_' . $lang->code};
            $point->serial_number = $request->{'serial_number_' . $lang->code};
            $point->save();
            if($assoc_id>0){
                $point->assoc_id = $assoc_id;
                $point->save();
            }
            if($assoc_id==0){
                $assoc_id = $point->assoc_id = $point->id;
                $point->save();
            }
        }
        Session::flash('success', 'New point added successfully!');
        if($request->ajax())
            return "success";
        return back();
    }

    public function pointedit($id)
    {
        $data['point'] = Point::findOrFail($id);
        return view('admin.home.approach.edit', $data);
    }
    public function pointedit_modal($id)
    {
        $point = Point::findOrFail($id);
        $current_lang = Language::where('id',$point->language_id)->first();
        $languages = Language::all();
        $data= array();
        //$data['langs'] = $languages;
        foreach ($languages as $lang) {
            if($current_lang->id == $lang->id){
                $data['point'][$lang->code] = $point;
            }else{
                $data['point'][$lang->code] = $point->assoc_id>0?Point::where('language_id',$lang->id)->where('assoc_id',$point->assoc_id)->first():null;
            }
            if($data['point'][$lang->code]==null){
                $data['point'][$lang->code] = new Point;
                $data['scates'][$lang->code] = Point::where('language_id',$lang->id)->get();
            }
        }
        return view('admin.home.approach.edit-modal', $data);
    }

    public function update(Request $request, $langid)
    {
        $languages = Language::all();
        foreach ($languages as $lang) {
            $request->validate([
                'approach_section_title_'.$lang->code => 'required|max:25',
                'approach_section_subtitle_'.$lang->code => 'required|max:80',
                'approach_section_button_text_'.$lang->code => 'nullable|max:15',
                'approach_section_button_url_'.$lang->code => 'nullable|max:255',
            ]);
            $bs = BS::where('language_id', $lang->id)->firstOrFail();
            $bs->approach_title = $request->{'approach_section_title_' . $lang->code};
            $bs->approach_subtitle = $request->{'approach_section_subtitle_' . $lang->code};
            $bs->approach_button_text = $request->{'approach_section_button_text_' . $lang->code};
            $bs->approach_button_url = $request->{'approach_section_button_url_' . $lang->code};
            $bs->save();
        }
        Session::flash('success', 'Text updated successfully!');
        if($request->ajax())
            return "success";
        return back();
    }

    public function pointupdate(Request $request)
    {
        $languages = Language::all();
        $be = BasicExtended::first();
        $version = $be->theme_version;
        $assoc_id = 0;
        $saved_ids = [];
        foreach ($languages as $lang){
            if($request->filled('pointid_'.$lang->code)||!$request->filled('point_assoc_id_'.$lang->code)){
                $rules = [
                    'title_'.$lang->code => 'required',
                    'short_text_'.$lang->code => 'required',
                    'serial_number_'.$lang->code => 'required|integer',
                ];
                if ($version == 'cleaning') {
                    $rules['color_'.$lang->code] = 'required';
                }
            }
            else{
                $rules = [];
            }

            $request->validate($rules);
        }

        foreach ($languages as $lang) {
            if($request->filled('pointid_'.$lang->code)){
                $point = Point::findOrFail($request->{'pointid_'.$lang->code});
                if($assoc_id==0){
                    $assoc_id = $point->id;
                }
                $point->icon = $request->{'icon_'.$lang->code};
                if ($version == 'cleaning') {
                    $point->color = $request->{'color_'.$lang->code};
                }
                $point->title = $request->{'title_'.$lang->code};
                $point->short_text = $request->{'short_text_'.$lang->code};
                $point->serial_number = $request->{'serial_number_'.$lang->code};
                $point->save();
                $saved_ids[] = $point->id;
            }
            else{
                if(!$request->filled('point_assoc_id_'.$lang->code)){
                    $point = new Point;
                    $point->language_id = $lang->id;
                    $point->icon = $request->{'icon_'.$lang->code};
                    if ($version == 'cleaning') {
                        $point->color = $request->{'color_'.$lang->code};
                    }
                    $point->title = $request->{'title_'.$lang->code};
                    $point->short_text = $request->{'short_text_'.$lang->code};
                    $point->serial_number = $request->{'serial_number_'.$lang->code};
                    $point->save();
                    $saved_ids[] = $point->id;
                }else{
                    $saved_ids[] = $request->{'point_assoc_id_'.$lang->code};
                }
            }
        }
        foreach ($saved_ids as $saved_id){
            $point = Point::findOrFail($saved_id);
            $point->assoc_id = $assoc_id;
            $point->save();
        }
        Session::flash('success', 'Point updated successfully!');
        if($request->ajax())
            return "success";
        return back();
    }

    public function pointdelete(Request $request)
    {

        $point = Point::findOrFail($request->pointid);
        $point->delete();

        Session::flash('success', 'Point deleted successfully!');
        if($request->ajax())
            return "success";
        return back();
    }
}
