<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jcategory;
use App\Language;
use Validator;
use Session;

class JcategoryController extends Controller
{
    public function index(Request $request) {
        $lang = Language::where('code', $request->language)->first();

        $lang_id = $lang->id;
        $data['jcategorys'] = Jcategory::where('language_id', $lang_id)->orderBy('id', 'DESC')->paginate(10);

        return view('admin.job.jcategory.index', $data);
    }

    public function edit($id) {
        $jcategory = Jcategory::findOrFail($id);
        $current_lang = Language::where('id', $jcategory->language_id)->first();
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $data['langs'] = $languages;
        foreach ($languages as $lang) {
            if ($current_lang->id == $lang->id) {
                $data['jcategory'][$lang->code] = $jcategory;
            } else {
                $data['jcategory'][$lang->code] = $jcategory->assoc_id > 0 ? Jcategory::where('language_id', $lang->id)->where('assoc_id', $jcategory->assoc_id)->first() : null;
            }
            if ($data['jcategory'][$lang->code] == null) {
                $data['jcategory'][$lang->code] = new Jcategory();
                $data['jcates'][$lang->code] = Jcategory::where('language_id', $lang->id)->get();
            }
        }
        return view('admin.job.jcategory.edit', $data);
    }

    public function store(Request $request) {
        $messages = [
            'language_id.required' => 'The language field is required',
        ];
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $saved_ids = [];
        foreach ($languages as $lang) {
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
            $jcategory = new Jcategory;
            $jcategory->language_id = $lang->id;
            $jcategory->name = $request->{'name_' . $lang->code};
            $jcategory->status = $request->{'status_' . $lang->code};
            $jcategory->serial_number = $request->{'serial_number_' . $lang->code};
            $jcategory->save();

            if($assoc_id == 0){
                $assoc_id = $jcategory->id;
            }

            $saved_ids[] = $jcategory->id;
        }

        foreach ($saved_ids as $saved_id) {
            $jcategory = Jcategory::findOrFail($saved_id);
            $jcategory->assoc_id = $assoc_id;
            $jcategory->save();
        }

        Session::flash('success', 'Category added successfully!');
        return "success";
    }

    public function update(Request $request) {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $saved_ids = [];
        foreach ($languages as $lang) {
            if ($request->filled('jcategory_id_' . $lang->code) || !$request->filled('jcategory_assoc_id_' . $lang->code)) {//Validation
                $rules = [
                    'name_' . $lang->code => 'required',
                    'status_' . $lang->code => 'required',
                    'serial_number_' . $lang->code => 'required|integer'
                ];

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $errmsgs = $validator->getMessageBag()->add('error', 'true');
                    return response()->json($validator->errors());
                }
            }
        }
        foreach ($languages as $lang) {
            if ($request->filled('jcategory_id_' . $lang->code)) {//update
                $jcategory = Jcategory::findOrFail($request->{'jcategory_id_' . $lang->code});
                $jcategory->name = $request->{'name_' . $lang->code};
                $jcategory->status = $request->{'status_' . $lang->code};
                $jcategory->serial_number = $request->{'serial_number_' . $lang->code};
                $jcategoryId = $jcategory->id;

                if ($assoc_id == 0) {
                    $assoc_id = $jcategoryId;
                }

                $jcategory->assoc_id = $assoc_id;

                $jcategory->save();
            }else{
                if (!$request->filled('jcategory_assoc_id_' . $lang->code)) {//create
                    $jcategory = new Jcategory;
                    $jcategory->language_id = $lang->id;
                    $jcategory->name = $request->{'name_' . $lang->code};
                    $jcategory->status = $request->{'status_' . $lang->code};
                    $jcategory->serial_number = $request->{'serial_number_' . $lang->code};

                    $jcategory->save();
                    $saved_ids[] = $jcategory->id;
                }else{
                    $saved_ids[] = $request->{'jcategory_assoc_id_' . $lang->code};
                }
            }
        }
        foreach ($saved_ids as $saved_id) {
            $jcategory = Jcategory::findOrFail($saved_id);
            $jcategory->assoc_id = $assoc_id;
            $jcategory->save();
        }
        Session::flash('success', 'Category updated successfully!');
        return "success";
    }

    public function delete(Request $request) {
        $_jcategory = Jcategory::findOrFail($request->jcategory_id);
        if($_jcategory->assoc_id > 0) {
            $jcategories = Jcategory::where('assoc_id', $_jcategory->assoc_id)->get();
            foreach ($jcategories as $jcategory) {
                if ($jcategory->jobs()->count() > 0) {
                    Session::flash('warning', 'First, delete all the jobs under this category!');
                    return back();
                }
                $jcategory->delete();
            }
        }else {
            if ($_jcategory->jobs()->count() > 0) {
                Session::flash('warning', 'First, delete all the jobs under this category!');
                return back();
            }
            $_jcategory->delete();
        }

        Session::flash('success', 'Category deleted successfully!');
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $_jcategory = Jcategory::findOrFail($id);
            if($_jcategory->assoc_id > 0) {
                $jcategories = Jcategory::where('assoc_id', $_jcategory->assoc_id)->get();
                foreach ($jcategories as $jcategory) {
                    if ($jcategory->jobs()->count() > 0) {
                        Session::flash('warning', 'First, delete all the jobs under the selected categories!');
                        return "success";
                    }
                }
            }else {
                if ($_jcategory->jobs()->count() > 0) {
                    Session::flash('warning', 'First, delete all the jobs under the selected categories!');
                    return "success";
                }
            }
        }

        foreach ($ids as $id) {
            $_jcategory = Jcategory::findOrFail($id);
            if($_jcategory->assoc_id > 0) {
                $jcategories = Jcategory::where('assoc_id', $_jcategory->assoc_id)->get();
                foreach ($jcategories as $jcategory) {
                    $jcategory->delete();
                }
            }else{
                $_jcategory->delete();
            }
        }

        Session::flash('success', 'Job categories deleted successfully!');
        return "success";
    }
}
