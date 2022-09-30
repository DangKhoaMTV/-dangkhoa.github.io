<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Partner;
use App\Language;
use Validator;
use Session;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->first();

        $lang_id = $lang->id;
        $data['partners'] = Partner::where('language_id', $lang_id)->orderBy('id', 'DESC')->get();

        $data['lang_id'] = $lang_id;
        return view('admin.home.partner.index', $data);
    }

    public function edit($id)
    {
        $data['partner'] = Partner::findOrFail($id);
        return view('admin.home.partner.edit', $data);
    }
    public function edit_modal($id)
    {
        $partner = Partner::findOrFail($id);
        $current_lang = Language::where('id',$partner->language_id)->first();
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $data= array();
        //$data['langs'] = $languages;
        foreach ($languages as $lang) {
            if($current_lang->id == $lang->id){
                $data['partner'][$lang->code] = $partner;
            }else{
                $data['partner'][$lang->code] = $partner->assoc_id>0?Partner::where('language_id',$lang->id)->where('assoc_id',$partner->assoc_id)->first():null;
            }
            if($data['partner'][$lang->code]==null){
                $data['partner'][$lang->code] = new Partner;
                $data['scates'][$lang->code] = Partner::where('language_id',$lang->id)->get();
            }
        }
        return view('admin.home.partner.edit-modal', $data);
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
                'url_'.$lang->code => 'required|max:255',
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
            $image = $request->{'image_'.$lang->code};
            $extImage = pathinfo($image, PATHINFO_EXTENSION);
            $partner = new Partner;
            $partner->language_id = $lang->id;
            $partner->url = $request->{'url_'.$lang->code};
            $partner->serial_number = $request->{'serial_number_'.$lang->code};

            if ($request->filled('image_'.$lang->code)) {
                $filename = uniqid() . '.' . $extImage;
                @copy($image, 'assets/front/img/partners/' . $filename);
                $partner->image = $filename;
            }

            $partner->save();
            if($assoc_id==0){
                $assoc_id = $partner->id;
            }
            if($assoc_id>0){
                $partner->assoc_id = $assoc_id;
                $partner->save();
            }
        }
        Session::flash('success', 'Partner added successfully!');
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
            $image = $request->{'image_'.$lang->code};
            $extImage = pathinfo($image, PATHINFO_EXTENSION);

            if($request->filled('partner_id_'.$lang->code)||!$request->filled('partner_assoc_id_'.$lang->code)) {
                $rules = [
                    'url_'.$lang->code => 'required|max:255',
                    'serial_number_'.$lang->code => 'required|integer',
                ];
            }else{
                $rules = [];
            }

            if ($request->filled('image_'.$lang->code)) {
                $rules['image_'.$lang->code] = [
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
            $image = $request->{'image_'.$lang->code};
            $extImage = pathinfo($image, PATHINFO_EXTENSION);
            if($request->filled('partner_id_'.$lang->code)) {
                $partner = Partner::findOrFail($request->{'partner_id_'.$lang->code});
                if($assoc_id==0){
                    $assoc_id = $partner->id;
                }
                $partner->url = $request->{'url_'.$lang->code};
                $partner->serial_number = $request->{'serial_number_'.$lang->code};

                if ($request->filled('image_'.$lang->code)) {
                    @unlink('assets/front/img/partners/' . $partner->image);
                    $filename = uniqid() . '.' . $extImage;
                    @copy($image, 'assets/front/img/partners/' . $filename);
                    $partner->image = $filename;
                }

                $partner->save();
                $saved_ids[] = $partner->id;
            }else {
                if (!$request->filled('partner_assoc_id_' . $lang->code)) {
                    $partner = new Partner;
                    $partner->language_id = $lang->id;
                    $partner->url = $request->{'url_'.$lang->code};
                    $partner->serial_number = $request->{'serial_number_'.$lang->code};

                    if ($request->filled('image_'.$lang->code)) {
                        $filename = uniqid() . '.' . $extImage;
                        @copy($image, 'assets/front/img/partners/' . $filename);
                        $partner->image = $filename;
                    }

                    $partner->save();
                    $saved_ids[] = $partner->id;
                }else{
                    $saved_ids[] = $request->{'partner_assoc_id_'.$lang->code};
                }
            }
        }
        foreach ($saved_ids as $saved_id){
            $partner = Partner::findOrFail($saved_id);
            $partner->assoc_id = $assoc_id;
            $partner->save();
        }
        Session::flash('success', 'Partner updated successfully!');
        if($request->ajax())
            return "success";
        return back();
    }

    public function delete(Request $request)
    {

        $partner = Partner::findOrFail($request->partner_id);
        @unlink('assets/front/img/partners/' . $partner->image);
        $partner->delete();

        Session::flash('success', 'Partner deleted successfully!');
        if($request->ajax())
            return "success";
        return back();
    }
}
