<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BasicSetting as BS;
use App\Language;
use Validator;
use Session;

class FooterController extends Controller
{
    public function index(Request $request)
    {
        $lang1 = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $lang1->id;
        $languages = Language::orderBy('is_default', 'DESC')->get();
        foreach ($languages as $lang){
            $data['abs'][$lang->code] = $lang->basic_setting;
        }

        return view('admin.footer.logo-text', $data);
    }


    public function update(Request $request, $langid)
    {
        $langs = Language::orderBy('is_default', 'DESC')->get();
        if(count($langs)>1){
            foreach ($langs as $lang){
                $footerLogo = $request->{'footer_logo_'.$lang->code};
                $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
                $extFooterLogo = pathinfo($footerLogo, PATHINFO_EXTENSION);

                $rules = [
                    'footer_text_'.$lang->code => 'required',
                    'newsletter_text_'.$lang->code => 'required|max:255',
                    'copyright_text_'.$lang->code => 'required',
                ];

                if ($request->filled('footer_logo_'.$lang->code)) {
                    $rules['footer_logo_'.$lang->code] = [
                        function ($attribute, $value, $fail) use ($extFooterLogo, $allowedExts) {
                            if (!in_array($extFooterLogo, $allowedExts)) {
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
                $bs->footer_text = $request->{'footer_text_'.$lang->code};
                $bs->newsletter_text = $request->{'newsletter_text_'.$lang->code};
                $bs->copyright_text = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'copyright_text_'.$lang->code});

                if ($request->filled('footer_logo_'.$lang->code)) {
                    @unlink('assets/front/img/' . $bs->footer_logo);
                    $filename = uniqid() .'.'. $extFooterLogo;
                    @copy($footerLogo, 'assets/front/img/' . $filename);
                    $bs->footer_logo = $filename;
                }

                $bs->save();
            }


            Session::flash('success', 'Footer text updated successfully!');
            return "success";
        }else{
            $footerLogo = $request->footer_logo;
            $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
            $extFooterLogo = pathinfo($footerLogo, PATHINFO_EXTENSION);

            $rules = [
                'footer_text' => 'required',
                'newsletter_text' => 'required|max:255',
                'copyright_text' => 'required',
            ];

            if ($request->filled('footer_logo')) {
                $rules['footer_logo'] = [
                    function ($attribute, $value, $fail) use ($extFooterLogo, $allowedExts) {
                        if (!in_array($extFooterLogo, $allowedExts)) {
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

            $bs = BS::where('language_id', $langid)->firstOrFail();
            $bs->footer_text = $request->footer_text;
            $bs->newsletter_text = $request->newsletter_text;
            $bs->copyright_text = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->copyright_text);

            if ($request->filled('footer_logo')) {
                @unlink('assets/front/img/' . $bs->footer_logo);
                $filename = uniqid() .'.'. $extFooterLogo;
                @copy($footerLogo, 'assets/front/img/' . $filename);
                $bs->footer_logo = $filename;
            }

            $bs->save();

            Session::flash('success', 'Footer text updated successfully!');
            return "success";
        }
    }
}
