<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BasicSetting as BS;
use App\Language;
use Validator;
use Session;

class PortfoliosectionController extends Controller
{
    public function index(Request $request)
    {
        if (empty($request->language)) {
            $data['lang_id'] = 0;
            //$data['abs'] = BS::firstOrFail();
        } else {
            $_lang = Language::where('code', $request->language)->firstOrFail();
            $data['lang_id'] = $_lang->id;

        }
        $languages = Language::orderBy('is_default', 'DESC')->get();
        foreach ($languages as $lang){
            $data['abs'][$lang->code] = $lang->basic_setting;
        }

        return view('admin.home.portfolio-section', $data);
    }

    public function update(Request $request, $langid)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        foreach ($languages as $lang) {
            $rules = [
                'portfolio_section_text_'.$lang->code => 'required|max:80',
                'portfolio_section_title_'.$lang->code => 'required|max:25'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errmsgs = $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }
        }
        foreach ($languages as $lang) {
            $bs = BS::where('language_id', $lang->id)->firstOrFail();
            $bs->portfolio_section_text = $request->{'portfolio_section_text_'.$lang->code};
            $bs->portfolio_section_title = $request->{'portfolio_section_title_'.$lang->code};
            $bs->save();
        }
        Session::flash('success', 'Texts updated successfully!');
        return "success";
    }
}
