<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Bcategory;
use App\Language;
use App\Megamenu;
use Validator;
use Session;

class BcategoryController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->first();

        $lang_id = $lang->id;
        $data['bcategorys'] = Bcategory::where('language_id', $lang_id)->orderBy('id', 'DESC')->paginate(10);

        $data['lang_id'] = $lang_id;

        return view('admin.blog.bcategory.index', $data);
    }

    public function edit($id)
    {
        $bcategory = Bcategory::findOrFail($id);
        $current_lang = Language::where('id', $bcategory->language_id)->first();
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $data['langs'] = $languages;
        foreach ($languages as $lang) {
            if ($current_lang->id == $lang->id) {
                $data['bcategory'][$lang->code] = $bcategory;
            } else {
                $data['bcategory'][$lang->code] = $bcategory->assoc_id > 0 ? Bcategory::where('language_id', $lang->id)->where('assoc_id', $bcategory->assoc_id)->first() : null;
            }
            if ($data['bcategory'][$lang->code] == null) {
                $data['bcategory'][$lang->code] = new Bcategory();
                $data['bcates'][$lang->code] = Bcategory::where('language_id', $lang->id)->get();
            }
        }

        return view('admin.blog.bcategory.edit-modal', $data);
    }

    public function store(Request $request)
    {
        $languages = Language::all();
        $assoc_id = 0;
        $saved_ids = [];
        foreach ($languages as $lang) {
            $messages = [
                'language_id.required' => 'The language field is required'
            ];

            $rules = [
                'name_' . $lang->code => 'required|max:255',
                'status_' . $lang->code => 'required',
                'serial_number_' . $lang->code => 'required|integer',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $errmsgs = $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }
        }
        foreach ($languages as $lang) {
            $bcategory = new Bcategory;
            $bcategory->language_id = $lang->id;
            $bcategory->name = $request->{'name_' . $lang->code};
            $bcategory->slug = slug_create($request->{'name_' . $lang->code});
            $bcategory->status = $request->{'status_' . $lang->code};
            $bcategory->serial_number = $request->{'serial_number_' . $lang->code};
            $bcategory->save();

            if($assoc_id == 0){
                $assoc_id = $bcategory->id;
            }

            $saved_ids[] = $bcategory->id;
        }
        foreach ($saved_ids as $saved_id) {
            $bcategory = Bcategory::findOrFail($saved_id);
            $bcategory->assoc_id = $assoc_id;
            $bcategory->save();
        }
        Session::flash('success', 'Blog category added successfully!');
        return "success";
    }

    public function update(Request $request)
    {
        $languages = Language::all();
        $assoc_id = 0;
        $saved_ids = [];
        foreach ($languages as $lang) {
            if ($request->filled('bcategory_id_' . $lang->code) || !$request->filled('bcategory_assoc_id_' . $lang->code)) {//Validation
                $rules = [
                    'name_' . $lang->code => 'required|max:255',
                    'status_' . $lang->code => 'required',
                    'serial_number_' . $lang->code => 'required|integer',
                ];

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $errmsgs = $validator->getMessageBag()->add('error', 'true');
                    return response()->json($validator->errors());
                }
            }
        }
        foreach ($languages as $lang) {
            if ($request->filled('bcategory_id_' . $lang->code)) {//update
                $bcategory = Bcategory::findOrFail($request->{'bcategory_id_' . $lang->code});
                $bcategory->language_id = $lang->id;
                $bcategory->name = $request->{'name_' . $lang->code};
                $bcategory->slug = slug_create($request->{'name_' . $lang->code});

                $bcategoryId = $bcategory->id;

                if ($assoc_id == 0) {
                    $assoc_id = $bcategoryId;
                }

                $bcategory->assoc_id = $assoc_id;

                $bcategory->status = $request->{'status_' . $lang->code};
                $bcategory->serial_number = $request->{'serial_number_' . $lang->code};
                $bcategory->save();

            }else {
                if (!$request->filled('bcategory_assoc_id_' . $lang->code)) {//create
                    $bcategory = new Bcategory;
                    $bcategory->language_id = $lang->id;
                    $bcategory->name = $request->{'name_' . $lang->code};
                    $bcategory->slug = slug_create($request->{'name_' . $lang->code});
                    $bcategory->status = $request->{'status_' . $lang->code};
                    $bcategory->serial_number = $request->{'serial_number_' . $lang->code};
                    $bcategory->save();
                    $saved_ids[] = $bcategory->id;
                }else{
                    $saved_ids[] = $request->{'bcategory_assoc_id_' . $lang->code};
                }
            }
        }
        foreach ($saved_ids as $saved_id) {
            $bcategory = Bcategory::findOrFail($saved_id);
            $bcategory->assoc_id = $assoc_id;
            $bcategory->save();
        }

        Session::flash('success', 'Blog category updated successfully!');
        return "success";
    }

    public function deleteFromMegaMenu($bcategory)
    {
        $megamenu = Megamenu::where('language_id', $bcategory->language_id)->where('category', 1)->where('type', 'blogs');
        if ($megamenu->count() > 0) {
            $megamenu = $megamenu->first();
            $menus = json_decode($megamenu->menus, true);
            $catId = $bcategory->id;
            if (is_array($menus) && array_key_exists("$catId", $menus)) {
                unset($menus["$catId"]);
                $megamenu->menus = json_encode($menus);
                $megamenu->save();
            }
        }
    }

    public function delete(Request $request)
    {
        $_bcategory = Bcategory::findOrFail($request->bcategory_id);
        if($_bcategory->assoc_id > 0) {
            $bcategories = Bcategory::where('assoc_id', $_bcategory->assoc_id)->get();
            foreach ($bcategories as $bcategory) {
                if ($bcategory->blogs()->count() > 0) {
                    Session::flash('warning', 'First, delete all the blogs under this category!');
                    return back();
                }
            }
            foreach ($bcategories as $bcategory) {
                $this->deleteFromMegaMenu($bcategory);
                $bcategory->delete();
            }
        }else {
            if ($_bcategory->blogs()->count() > 0) {
                Session::flash('warning', 'First, delete all the blogs under this category!');
                return back();
            }

            $this->deleteFromMegaMenu($_bcategory);

            $_bcategory->delete();
        }

        Session::flash('success', 'Blog category deleted successfully!');
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $_bcategory = Bcategory::findOrFail($id);
            if($_bcategory->assoc_id > 0){
                $bcategories = Bcategory::where('assoc_id', $_bcategory->assoc_id)->get();
                foreach ($bcategories as $bcategory){
                    if ($bcategory->blogs()->count() > 0) {
                        Session::flash('warning', 'First, delete all the blogs under the selected categories!');
                        return "success";
                    }
                }
                foreach ($bcategories as $bcategory){
                    $this->deleteFromMegaMenu($bcategory);
                    $bcategory->delete();
                }
            }else {
                if ($_bcategory->blogs()->count() > 0) {
                    Session::flash('warning', 'First, delete all the blogs under the selected categories!');
                    return "success";
                }
                $this->deleteFromMegaMenu($_bcategory);
                $_bcategory->delete();
            }

        }

        Session::flash('success', 'Blog categories deleted successfully!');
        return "success";
    }
}
