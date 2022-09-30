<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BasicSetting as BS;
use App\Language;
use Validator;
use Session;

class CtaController extends Controller
{
    public function index(Request $request)
    {
        $_lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $_lang->id;
        $languages = Language::orderBy('is_default', 'DESC')->get();
        foreach ($languages as $lang){
            $data['abs'][$lang->code] = $lang->basic_setting;
            $data['abe'][$lang->code] = $lang->basic_extended;
        }


        return view('admin.home.cta', $data);
    }

    public function update(Request $request, $langid)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        foreach ($languages as $lang) {
            $background = $request->{'background_' . $lang->code};
            $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
            $extBackground = pathinfo($background, PATHINFO_EXTENSION);

            $rules = [
                'cta_section_text_'.$lang->code => 'required|max:80',
                'cta_section_button_text_'.$lang->code => 'required|max:15',
                'cta_section_button_url_'.$lang->code => 'required|max:255',
            ];

            if ($request->filled('background_' . $lang->code)) {
                $rules['background_' . $lang->code] = [
                    function ($attribute, $value, $fail) use ($extBackground, $allowedExts) {
                        if (!in_array($extBackground, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg, svg image is allowed");
                        }
                    }
                ];
            }

            $request->validate($rules);
            $bs = BS::where('language_id', $lang->id)->firstOrFail();
            $bs->cta_section_text = $request->{'cta_section_text_'.$lang->code};
            $bs->cta_section_button_text = $request->{'cta_section_button_text_'.$lang->code};
            $bs->cta_section_button_url = $request->{'cta_section_button_url_'.$lang->code};

            if ($request->filled('background_' . $lang->code)) {
                @unlink('assets/front/img/' . $bs->cta_bg);
                $filename = uniqid() . '.' . $extBackground;
                @copy($background, 'assets/front/img/' . $filename);
                $bs->cta_bg = $filename;
            }

            $bs->save();
        }
        Session::flash('success', 'Texts updated successfully!');
        if($request->ajax())
            return 'success';
        return back();
    }
}
