<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BasicSetting as BS;
use App\Language;
use Validator;
use Session;

class ServicesectionController extends Controller
{
    public function index(Request $request)
    {
        $_lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $_lang->id;
        $languages = Language::all();
        foreach ($languages as $lang) {
            $data['abs'][$lang->code] = $lang->basic_setting;
        }
        return view('admin.home.service-section', $data);
    }

    public function update(Request $request, $langid)
    {
        $languages = Language::all();
        foreach ($languages as $lang) {
            $rules = [
                'service_section_subtitle_'.$lang->code => 'required|max:80',
                'service_section_title_'.$lang->code => 'required|max:25'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errmsgs = $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }

            $bs = BS::where('language_id', $lang->id)->firstOrFail();
            $bs->service_section_subtitle = $request->{'service_section_subtitle_'.$lang->code};
            $bs->service_section_title = $request->{'service_section_title_'.$lang->code};
            $bs->save();
        }
        Session::flash('success', 'Texts updated successfully!');
        return "success";
    }
}
