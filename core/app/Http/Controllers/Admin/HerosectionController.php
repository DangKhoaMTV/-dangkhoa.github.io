<?php

namespace App\Http\Controllers\Admin;

use App\BasicExtended;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BasicSetting as BS;
use App\Language;
use Validator;
use Session;

class HerosectionController extends Controller
{
    public function static(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $languages = Language::orderBy('is_default', 'DESC')->get();
        foreach ($languages as $lang) {
            $data['abs'][$lang->code] = $lang->basic_setting;
            $data['abe'][$lang->code] = $lang->basic_extended;
        }
        return view('admin.home.hero.static', $data);
    }

    public function update(Request $request, $langid)
    {
        $langs = Language::orderBy('is_default', 'DESC')->get();
        foreach ($langs as $lang) {
            $image = $request->{'image_'.$lang->code};
            $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
            $extImage = pathinfo($image, PATHINFO_EXTENSION);

            $rules = [
                'hero_section_title_'.$lang->code => 'nullable',
                'hero_section_title_font_size_'.$lang->code => 'required|integer|digits_between:1,3',
                'hero_section_text_'.$lang->code => 'nullable',
                'hero_section_text_font_size_'.$lang->code => 'required|integer|digits_between:1,3',
                'hero_section_button_text_'.$lang->code => 'nullable',
                'hero_section_button_text_font_size_'.$lang->code => 'required|integer|digits_between:1,3',
                'hero_section_button_url_'.$lang->code => 'nullable',
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

            $be = BasicExtended::where('language_id', $lang->id)->firstOrFail();
            $version = $be->theme_version;

            if ($version == 'gym' || $version == 'car' || $version == 'cleaning') {
                $rules['hero_section_bold_text_'.$lang->code] = 'nullable';
                $rules['hero_section_bold_text_font_size_'.$lang->code] = 'required|integer|digits_between:1,3';
            }

            if ($version == 'cleaning') {
                $rules['hero_section_bold_text_color_'.$lang->code] = 'required';
            }

            if ($version == 'cleaning') {
                $rules['hero_section_text_font_size_'.$lang->code] = 'nullable';
            }


            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errmsgs = $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }

            $bs = BS::where('language_id', $lang->id)->firstOrFail();
            $bs->hero_section_title = $request->{'hero_section_title_'.$lang->code};
            if ($version == 'gym' || $version == 'car' || $version == 'cleaning') {
                $bs->hero_section_bold_text = $request->hero_section_bold_text[$lang->code];
            }
            if ($version != 'cleaning') {
                $bs->hero_section_text = $request->{'hero_section_text_'.$lang->code};
            }
            $bs->hero_section_button_text = $request->{'hero_section_button_text_'.$lang->code};
            $bs->hero_section_button_url = $request->{'hero_section_button_url_'.$lang->code};
            if ($request->filled('image')) {
                @unlink('assets/front/img/' . $bs->hero_bg);
                $filename = uniqid() . '.' . $extImage;
                @copy($image, 'assets/front/img/' . $filename);

                $bs->hero_bg = $filename;
            }
            $bs->save();


            $be->hero_section_title_font_size = $request->{'hero_section_title_font_size_'.$lang->code};
            if ($version == 'gym' || $version == 'car' || $version == 'cleaning') {
                $be->hero_section_bold_text_font_size = $request->{'hero_section_bold_text_font_size_'.$lang->code};
            }
            if ($version == 'cleaning') {
                $be->hero_section_bold_text_color = $request->{'hero_section_bold_text_color_'.$lang->code};
            }
            if ($version != 'cleaning') {
                $be->hero_section_text_font_size = $request->{'hero_section_text_font_size_'.$lang->code};
            }
            $be->hero_section_button_text_font_size = $request->{'hero_section_button_text_font_size_'.$lang->code};

            $be->save();
        }
        Session::flash('success', 'Informations updated successfully!');
        return "success";
    }

    public function video(Request $request)
    {
        $lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang->id;
        $languages = Language::orderBy('is_default', 'DESC')->get();
        foreach ($languages as $lang) {
            $data['abs'][$lang->code] = $lang->basic_setting;
        }
        return view('admin.home.hero.video', $data);
    }

    public function videoupdate(Request $request, $langid)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $rules = [];
        foreach ($languages as $lang) {
            $rules['video_link_' . $lang->code] = 'required|max:255';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errmsgs = $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        foreach ($languages as $lang) {
            $bs = BS::where('language_id', $lang->id)->firstOrFail();
            $videoLink = $request->{'video_link_'.$lang->code};
            if (strpos($videoLink, "&") != false) {
                $videoLink = substr($videoLink, 0, strpos($videoLink, "&"));
            }
            $bs->hero_section_video_link = $videoLink;
            $bs->save();
        }
        Session::flash('success', 'Informations updated successfully!');
        return "success";
    }
}
