<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BasicSetting as BS;
use App\Language;
use Validator;
use Session;

class BlogsectionController extends Controller
{
    public function index(Request $request)
    {
        if (empty($request->language)) {
            $data['lang_id'] = 0;
            //$data['abs'] = BS::firstOrFail();
        } else {
            $lang = Language::where('code', $request->language)->firstOrFail();
            $data['lang_id'] = $lang->id;
            //$data['abs'] = $lang->basic_setting;
        }
        $languages = Language::all();
        foreach ($languages as $lang){
            $data['abs'][$lang->code] = $lang->basic_setting;
        }
        return view('admin.home.blog-section', $data);
    }

    public function update(Request $request, $langid)
    {
        $languages = Language::all();
        foreach ($languages as $lang) {
            $rules = [
                'blog_section_subtitle_'.$lang->code => 'required|max:80',
                'blog_section_title_'.$lang->code => 'required|max:25'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errmsgs = $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }
        }
        foreach ($languages as $lang) {
            $bs = BS::where('language_id', $lang->id)->firstOrFail();
            $bs->blog_section_subtitle = $request->{'blog_section_subtitle_'.$lang->code};
            $bs->blog_section_title = $request->{'blog_section_title_'.$lang->code};
            $bs->save();
        }
        Session::flash('success', 'Texts updated successfully!');
        return "success";
    }
}
