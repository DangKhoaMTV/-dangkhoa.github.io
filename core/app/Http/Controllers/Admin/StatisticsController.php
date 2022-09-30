<?php

namespace App\Http\Controllers\Admin;

use App\BasicExtended;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BasicSetting as BS;
use App\Statistic;
use App\Language;
use Session;
use Validator;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $_lang = Language::where('code', $request->language)->first();
        $languages = Language::all();
        $lang_id = $_lang->id;
        $data['statistics'] = Statistic::where('language_id', $lang_id)->orderBy('id', 'DESC')->get();

        $data['lang_id'] = $lang_id;
        foreach ($languages as $lang){
            $data['abe'][$lang->code] = $lang->basic_extended;
        }
        $data['selLang'] = Language::where('code', $request->language)->first();

        return view('admin.home.statistics.index', $data);
    }

    public function store(Request $request)
    {
        $languages = Language::all();
        $assoc_id= 0;
        $messages = [
            //'language_id.required' => 'The language field is required'
        ];

        $count = Statistic::where('language_id', $request->language_id)->count();
        if ($count == 4) {
            Session::flash('warning', 'You cannot add more than 4 statistics!');
            return "success";
        }
        foreach ($languages as $lang) {
            $rules = [
                //'language_id' => 'required',
                'title_'.$lang->code => 'required|max:20',
                'quantity_'.$lang->code => 'required|integer',
                'serial_number_'.$lang->code => 'required|integer',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $errmsgs = $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }
        }
        foreach ($languages as $lang) {
            $statistic = new Statistic;
            $statistic->language_id = $lang->id;
            $statistic->icon = $request->{'icon_'.$lang->code};
            $statistic->title = $request->{'title_'.$lang->code};
            $statistic->quantity = $request->{'quantity_'.$lang->code};
            $statistic->serial_number = $request->{'serial_number_'.$lang->code};
            $statistic->save();
            if($assoc_id==0){
                $assoc_id = $statistic->id;
            }
            if($assoc_id>0){
                $statistic->assoc_id = $assoc_id;
                $statistic->save();
            }
        }
        Session::flash('success', 'New statistic added successfully!');
        if($request->ajax())
            return "success";
        return back();
    }

    public function edit($id)
    {
        $data['statistic'] = Statistic::findOrFail($id);
        if (!empty($data['statistic']->language)) {
            $data['selLang'] = $data['statistic']->language;
        }

        return view('admin.home.statistics.edit', $data);
    }
    public function edit_modal($id)
    {
        $statistic = Statistic::findOrFail($id);
        $current_lang = Language::where('id',$statistic->language_id)->first();
        $languages = Language::all();
        $data= array();
        //$data['langs'] = $languages;
        foreach ($languages as $lang) {
            if($current_lang->id == $lang->id){
                $data['statistic'][$lang->code] = $statistic;
            }else{
                $data['statistic'][$lang->code] = $statistic->assoc_id>0?Statistic::where('language_id',$lang->id)->where('assoc_id',$statistic->assoc_id)->first():null;
            }
            if($data['statistic'][$lang->code]==null){
                $data['statistic'][$lang->code] = new Statistic;
                $data['scates'][$lang->code] = Statistic::where('language_id',$lang->id)->orderBy('assoc_id','ASC')->get();
            }
        }
        return view('admin.home.statistics.edit-modal', $data);
    }

    public function update(Request $request)
    {
        $languages = Language::all();
        $assoc_id = 0;
        $saved_ids = [];
        foreach ($languages as $lang) {
            $rules = [];
            if($request->filled('statisticid_' . $lang->code)||!$request->filled('statistic_assoc_id_' . $lang->code)){
                $rules = [
                    'title_'.$lang->code => 'required|max:20',
                    'quantity_'.$lang->code => 'required|integer',
                    'serial_number_'.$lang->code => 'required|integer',
                ];
            }
            $request->validate($rules);
        }
        foreach ($languages as $lang) {
            if($request->filled('statisticid_' . $lang->code)){
                $statistic = Statistic::findOrFail($request->{'statisticid_' . $lang->code});
                if($assoc_id==0){
                    $assoc_id = $statistic->id;
                }
                $statistic->icon = $request->{'icon_' . $lang->code};
                $statistic->title = $request->{'title_' . $lang->code};
                $statistic->quantity = $request->{'quantity_' . $lang->code};
                $statistic->serial_number = $request->{'serial_number_' . $lang->code};
                $statistic->save();
                $saved_ids[] = $statistic->id;
            }
            else{
                if(!$request->filled('statistic_assoc_id_' . $lang->code)){
                    $statistic = new Statistic;
                    $statistic->language_id = $lang->id;
                    $statistic->icon = $request->{'icon_'.$lang->code};
                    $statistic->title = $request->{'title_'.$lang->code};
                    $statistic->quantity = $request->{'quantity_'.$lang->code};
                    $statistic->serial_number = $request->{'serial_number_'.$lang->code};
                    $statistic->save();
                    $saved_ids[] = $statistic->id;
                }else{
                    $saved_ids[] = $request->{'statistic_assoc_id_' . $lang->code};
                }
            }
        }
        foreach ($saved_ids as $saved_id){
            $statistic = Statistic::findOrFail($saved_id);
            $statistic->assoc_id = $assoc_id;
            $statistic->save();
        }
        Session::flash('success', 'Statistic updated successfully!');
        if($request->ajax())
            return "success";
        return back();
    }

    public function upload(Request $request, $langid)
    {
        $languages = Language::all();
        foreach ($languages as $lang) {
            $image = $request->{'background_image_' . $lang->code};
            $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
            $extImage = pathinfo($image, PATHINFO_EXTENSION);

            $rules = [];

            if ($request->filled('background_image_' . $lang->code)) {
                $rules['background_image'] = [
                    function ($attribute, $value, $fail) use ($extImage, $allowedExts) {
                        if (!in_array($extImage, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg, svg image is allowed");
                        }
                    }
                ];
            }

            $request->validate($rules);
            if ($request->filled('background_image_' . $lang->code)) {

                $be = BasicExtended::where('language_id', $lang->id)->first();

                @unlink('assets/front/img/' . $be->statistics_bg);
                $filename = uniqid() . '.' . $extImage;
                @copy($image, 'assets/front/img/' . $filename);

                $be->statistics_bg = $filename;
                $be->save();

            }
        }

        $request->session()->flash('success', 'Statistics section background image');
        if($request->ajax())
            return "success";
        return back();
    }

    public function delete(Request $request)
    {

        $statistic = Statistic::findOrFail($request->statisticid);
        $statistic->delete();

        Session::flash('success', 'Statistic deleted successfully!');
        if($request->ajax())
            return "success";
        return back();
    }
}
