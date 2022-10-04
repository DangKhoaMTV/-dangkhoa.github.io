<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Language;
use App\Ulink;
use Validator;
use Session;

class UlinkController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->first();
        $lang_id = $lang->id;
        $data['aulinks'] = Ulink::where('language_id', $lang_id)->get();
        $data['lang_id'] = $lang_id;
        return view('admin.footer.ulink.index', $data);
    }

    public function edit($id)
    {
        $ulink = Ulink::findOrFail($id);
        $current_lang = Language::where('id', $ulink->language_id)->first();
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $data = array();
        $data['langs'] = $languages;

        foreach ($languages as $lang) {
            if ($current_lang->id == $lang->id) {
                $data['ulink'][$lang->code] = $ulink;
            } else {
                $data['ulink'][$lang->code] = $ulink->assoc_id > 0 ? Ulink::where('language_id', $lang->id)->where('assoc_id', $ulink->assoc_id)->first() : null;
            }
            if ($data['ulink'][$lang->code] == null) {
                $data['ulink'][$lang->code] = new Ulink;
                $data['gcates'][$lang->code] = Ulink::where('language_id', $lang->id)->get();
            }
        }
        return view('admin.footer.ulink.edit', $data);
    }

    public function store(Request $request)
    {
        $messages = [];
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $saved_ids = [];
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        foreach ($languages as $lang) {
            $type = $request->{'type_' . $lang->code};
            $rules = [
                'name_' . $lang->code => 'required|max:255',
            ];

            if($type == 'link'){
                $rules['url_' . $lang->code] = 'required|max:255';
            }

            if($type == 'popup'){
                $rules['content_' . $lang->code] = 'required';
            }

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $errmsgs = $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }
        }
        foreach ($languages as $lang) {
            $ulink = new Ulink;
            $type = $request->{'type_' . $lang->code};
            $ulink->language_id = $lang->id;
            $ulink->type = $type;
            $ulink->name = $request->{'name_' . $lang->code};
            if($type == 'link'){
                $ulink->url = $request->{'url_' . $lang->code};
            }

            if($type == 'popup'){
                $ulink->content = $request->{'content_' . $lang->code};
            }

            $ulink->save();
            if($assoc_id == 0){
                $assoc_id = $ulink->id;
            }

            $saved_ids[] = $ulink->id;
        }

        foreach ($saved_ids as $saved_id) {
            $ulink = Ulink::findOrFail($saved_id);
            $ulink->assoc_id = $assoc_id;
            $ulink->save();
        }

        Session::flash('success', 'Useful link added successfully!');
        return "success";
    }

    public function update(Request $request)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        $assoc_id = 0;
        $saved_ids = [];

        foreach ($languages as $lang) {
            $type = $request->{'type_' . $lang->code};
            if ($request->filled('ulink_id_' . $lang->code) || !$request->filled('ulink_assoc_id_' . $lang->code)) {//Validation
                $rules = [
                    'name_' . $lang->code => 'required|max:255',
                ];

                if($type == 'link'){
                    $rules['url_' . $lang->code] = 'required|max:255';
                }

                if($type == 'popup'){
                    $rules['content_' . $lang->code] = 'required';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $errmsgs = $validator->getMessageBag()->add('error', 'true');
                    return response()->json($validator->errors());
                }

            }
        }
        foreach ($languages as $lang) {
            if ($request->filled('ulink_id_' . $lang->code)) {//update
                $ulink = Ulink::findOrFail($request->{'ulink_id_' . $lang->code});
                $ulink->name = $request->{'name_' . $lang->code};
                $ulink->type = $type;
                if($type == 'link'){
                    $ulink->url = $request->{'url_' . $lang->code};
                }

                if($type == 'popup'){
                    $ulink->content = $request->{'content_' . $lang->code};
                }
                if ($assoc_id == 0) {
                    $assoc_id = $ulink->id;
                }
                $ulink->assoc_id = $assoc_id;
                $ulink->save();
            }else {
                if (!$request->filled('ulink_assoc_id_' . $lang->code)) {//create
                    $ulink = new Ulink;
                    $type = $request->{'type_' . $lang->code};
                    $ulink->language_id = $lang->id;
                    $ulink->type = $type;
                    $ulink->name = $request->{'name_' . $lang->code};
                    if($type == 'link'){
                        $ulink->url = $request->{'url_' . $lang->code};
                    }

                    if($type == 'popup'){
                        $ulink->content = $request->{'content_' . $lang->code};
                    }
                    $ulink->save();

                    if($assoc_id == 0){
                        $assoc_id = $ulink->id;
                    }

                    $saved_ids[] = $ulink->id;
                }else {
                    $saved_ids[] = $request->{'ulink_assoc_id_' . $lang->code};
                }
            }
        }
        foreach ($saved_ids as $saved_id) {
            $ulink = Ulink::findOrFail($saved_id);
            $ulink->assoc_id = $assoc_id;
            $ulink->save();
        }
        Session::flash('success', 'Useful link updated successfully!');
        return "success";
    }

    public function delete(Request $request)
    {

        $ulink = Ulink::findOrFail($request->ulink_id);
        $ulink->delete();

        Session::flash('success', 'Ulink deleted successfully!');
        return back();
    }
}
