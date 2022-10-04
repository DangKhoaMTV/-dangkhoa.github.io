<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Scategory;
use App\Language;
use App\Megamenu;
use Illuminate\Support\Facades\Lang;
use Validator;
use Session;

class ScategoryController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->first();

        $lang_id = $lang->id;
        $data['scategorys'] = Scategory::where('language_id', $lang_id)->orderBy('id', 'DESC')->paginate(10);

        $data['lang_id'] = $lang_id;
        return view('admin.service.scategory.index', $data);
    }

    public function edit($id)
    {
        $langs = Language::orderBy('is_default', 'DESC')->get();
        $scategory = Scategory::findOrFail($id);
        $current_lang = Language::where('id',$scategory->language_id)->first();
        foreach ($langs as $lang){
            if($current_lang->id == $lang->id){
                $data['scategory'][$lang->code] = $scategory;
            }else{
                $data['scategory'][$lang->code] = $scategory->assoc_id>0?Scategory::where('language_id',$lang->id)->where('assoc_id',$scategory->assoc_id)->first():null;
            }
            if($data['scategory'][$lang->code]==null){
                $data['scategory'][$lang->code] = new Scategory;
                $data['scates'][$lang->code] = Scategory::where('language_id',$lang->id)->get();
            }
        }

        return view('admin.service.scategory.edit', $data);
    }
    public function edit_modal($id)
    {
        $langs = Language::orderBy('is_default', 'DESC')->get();
        $scategory = Scategory::findOrFail($id);
        $current_lang = Language::where('id',$scategory->language_id)->first();
        foreach ($langs as $lang){
            if($current_lang->id == $lang->id){
                $data['scategory'][$lang->code] = $scategory;
            }else{
                $data['scategory'][$lang->code] = $scategory->assoc_id>0?Scategory::where('language_id',$lang->id)->where('assoc_id',$scategory->assoc_id)->first():null;
            }
            if($data['scategory'][$lang->code]==null){
                $data['scategory'][$lang->code] = new Scategory;
                $data['scates'][$lang->code] = Scategory::where('language_id',$lang->id)->get();
            }
        }

        return view('admin.service.scategory.edit-modal', $data);
    }

    public function store(Request $request)
    {
        $langs = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        foreach ($langs as $lang) {
            $image = $request->{'image_'.$lang->code};
            $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
            $extImage = pathinfo($image, PATHINFO_EXTENSION);

            $messages = [
                'language_id_'.$lang->code.'.required' => 'The language field is required'
            ];

            $rules = [
                'language_id_'.$lang->code => 'required',
                'image_'.$lang->code => 'nullable',
                'name_'.$lang->code => 'required|max:255',
                'short_text_'.$lang->code => 'required',
                'status_'.$lang->code => 'required',
                'serial_number_'.$lang->code => 'required|integer',
            ];
            if ($request->filled('image_'.$lang->code)) {
                $rules['image'] = [
                    function ($attribute, $value, $fail) use ($extImage, $allowedExts) {
                        if (!in_array($extImage, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg, svg image is allowed");
                        }
                    }
                ];
            }

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $errmsgs = $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }

            $scategory = new Scategory;
            $scategory->language_id = $lang->id;
            $scategory->name = $request->{'name_'.$lang->code};
            $scategory->status = $request->{'status_'.$lang->code};
            $scategory->short_text = $request->{'short_text_'.$lang->code};
            $scategory->serial_number = $request->{'serial_number_'.$lang->code};

            if ($request->filled('image_'.$lang->code)) {
                $filename = uniqid() . '.' . $extImage;
                @copy($image, 'assets/front/img/service_category_icons/' . $filename);
                $scategory->image = $filename;
            }
            if($assoc_id>0){
                $scategory->assoc_id = $assoc_id;
            }
            $scategory->save();
            if($assoc_id==0){
                $assoc_id = $scategory->assoc_id = $scategory->id;
                $scategory->save();
            }
        }
        Session::flash('success', 'Category added successfully!');
        return "success";
    }

    public function update(Request $request)
    {
        $langs = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id= 0;
        $saved_ids = [];
        foreach ($langs as $lang) {
            if($request->filled('scategory_id_'.$lang->code)){
                $image = $request->{'image_' . $lang->code};
                $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
                $extImage = pathinfo($image, PATHINFO_EXTENSION);

                $rules = [
                    'name_' . $lang->code => 'required|max:255',
                    'status_' . $lang->code => 'required',
                    'short_text_' . $lang->code => 'required',
                    'serial_number_' . $lang->code => 'required|integer',
                ];

                if ($request->filled('image_' . $lang->code)) {
                    $rules['image_' . $lang->code] = [
                        function ($attribute, $value, $fail) use ($extImage, $allowedExts) {
                            if (!in_array($extImage, $allowedExts)) {
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
                $scategory = Scategory::findOrFail($request->{'scategory_id_'.$lang->code});
                if($assoc_id==0){
                    $assoc_id = $scategory->id;
                }

                $scategory->name = $request->{'name_'.$lang->code};
                $scategory->status = $request->{'status_'.$lang->code};
                $scategory->short_text = $request->{'short_text_'.$lang->code};
                $scategory->serial_number = $request->{'serial_number_'.$lang->code};

                if ($request->filled('image_'.$lang->code)) {
                    @unlink('assets/front/img/service_category_icons/' . $scategory->image);
                    $filename = uniqid() . '.' . $extImage;
                    @copy($image, 'assets/front/img/service_category_icons/' . $filename);
                    $scategory->image = $filename;
                }

                $scategory->save();

                $saved_ids[] = $scategory->id;
            }else{
                if (!$request->filled('scategory_assoc_id_' . $lang->code)) {
                    $image = $request->{'image_' . $lang->code};
                    $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
                    $extImage = pathinfo($image, PATHINFO_EXTENSION);

                    $rules = [
                        'name_' . $lang->code => 'required|max:255',
                        'status_' . $lang->code => 'required',
                        'short_text_' . $lang->code => 'required',
                        'serial_number_' . $lang->code => 'required|integer',
                    ];

                    if ($request->filled('image_' . $lang->code)) {
                        $rules['image_' . $lang->code] = [
                            function ($attribute, $value, $fail) use ($extImage, $allowedExts) {
                                if (!in_array($extImage, $allowedExts)) {
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
                    $scategory = new Scategory;
                    $scategory->language_id = $lang->id;
                    $scategory->name = $request->{'name_' . $lang->code};
                    $scategory->status = $request->{'status_' . $lang->code};
                    $scategory->short_text = $request->{'short_text_' . $lang->code};
                    $scategory->serial_number = $request->{'serial_number_' . $lang->code};

                    if ($request->filled('image_' . $lang->code)) {
                        @unlink('assets/front/img/service_category_icons/' . $scategory->image);
                        $filename = uniqid() . '.' . $extImage;
                        @copy($image, 'assets/front/img/service_category_icons/' . $filename);
                        $scategory->image = $filename;
                    }

                    $scategory->save();
                    $saved_ids[] = $scategory->id;
                }else{
                    $saved_ids[] = $request->{'scategory_assoc_id_'.$lang->code};
                }
            }
        }
        foreach ($saved_ids as $saved_id){
            $scategory = Scategory::findOrFail($saved_id);
            $scategory->assoc_id = $assoc_id;
            $scategory->save();
        }
        Session::flash('success', 'Category updated successfully!');
        return "success";
    }

    public function delete(Request $request)
    {
        $scategory = Scategory::findOrFail($request->scategory_id);

        if ($scategory->services()->count() > 0) {
            Session::flash('warning', 'First, delete all the services under this category!');
            return back();
        }
        @unlink('assets/front/img/service_category_icons/' . $scategory->image);

        $this->deleteFromMegaMenu($scategory);

        $scategory->delete();

        Session::flash('success', 'Scategory deleted successfully!');
        return back();
    }

    public function deleteFromMegaMenu($scategory) {
        $megamenu = Megamenu::where('language_id', $scategory->language_id)->where('category', 1)->where('type', 'services');
        if ($megamenu->count() > 0) {
            $megamenu = $megamenu->first();
            $menus = json_decode($megamenu->menus, true);
            $catId = $scategory->id;
            if (is_array($menus) && array_key_exists("$catId", $menus)) {
                unset($menus["$catId"]);
                $megamenu->menus = json_encode($menus);
                $megamenu->save();
            }
        }
        $megamenu = Megamenu::where('language_id', $scategory->language_id)->where('category', 1)->where('type', 'portfolios');
        if ($megamenu->count() > 0) {
            $megamenu = $megamenu->first();
            $menus = json_decode($megamenu->menus, true);
            $catId = $scategory->id;
            if (is_array($menus) && array_key_exists("$catId", $menus)) {
                unset($menus["$catId"]);
                $megamenu->menus = json_encode($menus);
                $megamenu->save();
            }
        }
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $scategory = Scategory::findOrFail($id);
            if ($scategory->services()->count() > 0) {
                Session::flash('warning', 'First, delete all the services under the selected categories!');
                return "success";
            }
        }

        foreach ($ids as $id) {
            $scategory = Scategory::findOrFail($id);
            @unlink('assets/front/img/service_category_icons/' . $scategory->image);

            $this->deleteFromMegaMenu($scategory);

            $scategory->delete();
        }

        Session::flash('success', 'Service categories deleted successfully!');
        return "success";
    }

    public function feature(Request $request)
    {
        $scategory = Scategory::find($request->scategory_id);
        $scategory->feature = $request->feature;
        $scategory->save();

        if ($request->feature == 1) {
            Session::flash('success', 'Featured successfully!');
        } else {
            Session::flash('success', 'Unfeatured successfully!');
        }

        return back();
    }
}
