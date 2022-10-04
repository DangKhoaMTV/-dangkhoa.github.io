<?php

namespace App\Http\Controllers\Admin;

use App\BasicExtended;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BasicSetting as BS;
use App\Language;
use Validator;
use Session;

class IntrosectionController extends Controller
{
    public function index(Request $request)
    {
        $_lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $_lang->id;
        $languages = Language::orderBy('is_default', 'DESC')->get();
        foreach ($languages as $lang) {
            $data['abs'][$lang->code] = $lang->basic_setting;
            $data['abe'][$lang->code] = $lang->basic_extended;
        }
        return view('admin.home.intro-section', $data);
    }

    public function update(Request $request, $langid)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        foreach ($languages as $lang) {
            $image = $request->{'image_'.$lang->code};
            $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
            $extImage = pathinfo($image, PATHINFO_EXTENSION);

            $image2 = $request->{'image_2_'.$lang->code};
            $extImage2 = pathinfo($image2, PATHINFO_EXTENSION);

            $rules = [
                'intro_section_title_'.$lang->code => 'required|max:25',
                'intro_section_text_'.$lang->code => 'required',
                'intro_section_button_text_'.$lang->code => 'nullable|max:15',
                'intro_section_button_url_'.$lang->code => 'nullable|max:255',
                'intro_section_video_link_'.$lang->code => 'nullable'
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

            if ($request->filled('image_2_'.$lang->code)) {
                $rules['image_2_'.$lang->code] = [
                    function ($attribute, $value, $fail) use ($extImage2, $allowedExts) {
                        if (!in_array($extImage2, $allowedExts)) {
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

            $bs = BS::where('language_id', $lang->id)->firstOrFail();
            $bs->intro_section_title = $request->{'intro_section_title_'.$lang->code};
            $bs->intro_section_text = $request->{'intro_section_text_'.$lang->code};
            $bs->intro_section_button_text = $request->{'intro_section_button_text_'.$lang->code};
            $bs->intro_section_button_url = $request->{'intro_section_button_url_'.$lang->code};
            $videoLink = $request->{'intro_section_video_link_'.$lang->code};
            if (strpos($videoLink, "&") != false) {
                $videoLink = substr($videoLink, 0, strpos($videoLink, "&"));
            }
            $bs->intro_section_video_link = $videoLink;

            if ($request->filled('image_'.$lang->code)) {
                @unlink('assets/front/img/' . $bs->intro_bg);
                $filename = uniqid() . '.' . $extImage;
                @copy($image, 'assets/front/img/' . $filename);

                $bs->intro_bg = $filename;
            }

            $bs->save();

            $be = BasicExtended::where('language_id', $lang->id)->firstOrFail();
            if ($request->filled('image_2_'.$lang->code)) {
                @unlink('assets/front/img/' . $be->intro_bg2);
                $filename = uniqid() . '.' . $extImage2;
                @copy($image2, 'assets/front/img/' . $filename);

                $be->intro_bg2 = $filename;
            }
            $be->save();
        }
        Session::flash('success', 'Informations updated successfully!');
        return "success";
    }
}
