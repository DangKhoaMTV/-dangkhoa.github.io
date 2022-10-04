<?php

namespace App\Http\Controllers\Admin;

use App\BasicExtended;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Pcategory;
use App\Language;
use App\Megamenu;
use Validator;
use Session;

class ProductCategory extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->first();
        $lang_id = $lang->id;
        $data['pcategories'] = Pcategory::where('language_id', $lang_id)->orderBy('id', 'DESC')->paginate(10);
        $languages = Language::orderBy('is_default', 'DESC')->get();
        foreach ($languages as $lang) {
            $data['categories'][$lang->code] = Pcategory::where('language_id', $lang->id)->where('status', 1)->get();
        }
        
        $data['lang_id'] = $lang_id;
        return view('admin.product.category.index', $data);
    }
    
    public function store(Request $request)
    {
        $messages = [
            'language_id.required' => 'The language field is required'
        ];
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $saved_ids = [];
        foreach ($languages as $lang) {
            $rules = [
                'name_' . $lang->code => 'required|max:255',
                'status_' . $lang->code => 'required',
                // 'parent_id_' . $lang->code => 'required',
            ];

            $be = BasicExtended::first();
            if ($be->theme_version == 'ecommerce') {
                $image = $request->{'image_' . $lang->code};
                $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
                $extImage = pathinfo($image, PATHINFO_EXTENSION);

                if ($request->filled('image')) {
                    $rules['image_' . $lang->code] = [
                        function ($attribute, $value, $fail) use ($extImage, $allowedExts) {
                            if (!in_array($extImage, $allowedExts)) {
                                return $fail("Only png, jpg, jpeg, svg image is allowed");
                            }
                        }
                    ];
                }
            }

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $errmsgs = $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }

        }
        foreach ($languages as $lang) {
            $pcategory = new Pcategory;
            $pcategory->language_id = $lang->id;
            $slug = make_slug($request->{'name_' . $lang->code});
            $pcategory->slug = $slug;
            if ($be->theme_version == 'ecommerce' && $request->filled('image_' . $lang->code)) {
                $filename = uniqid() . '.' . $extImage;
                @mkdir('assets/front/img/product/categories/', 0775, true);
                @copy($image, 'assets/front/img/product/categories/' . $filename);
                $pcategory['image'] = $filename;
            }
            $pcategory->name = $request->{'name_' . $lang->code};
            $pcategory->status = $request->{'status_' . $lang->code};
            $pcategory->parent_id = $request->{'parent_id_' . $lang->code};
            $pcategory->save();
            if ($assoc_id == 0) {
                $assoc_id = $pcategory->id;
            }

            $saved_ids[] = $pcategory->id;
        }
        foreach ($saved_ids as $saved_id) {
            $pcategory = Pcategory::findOrFail($saved_id);
            $pcategory->assoc_id = $assoc_id;
            $pcategory->save();
        }
        Session::flash('success', 'Category added successfully!');
        return "success";
    }


    public function edit($id)
    {
        $pcategory = Pcategory::findOrFail($id);
        $current_lang = Language::where('id', $pcategory->language_id)->first();
        $languages = Language::orderBy('is_default', 'DESC')->get();
        foreach ($languages as $lang) {
            if ($current_lang->id == $lang->id) {
                $data['pcategory'][$lang->code] = $pcategory;
            } else {
                $data['pcategory'][$lang->code] = $pcategory->assoc_id > 0 ? Pcategory::where('language_id', $lang->id)->where('assoc_id', $pcategory->assoc_id)->first() : null;
            }
            if ($data['pcategory'][$lang->code] == null) {
                $data['pcategory'][$lang->code] = new Pcategory();
                
                $data['pcates'][$lang->code] = Pcategory::where('language_id', $lang->id)->get();
            }
            $data['categories'][$lang->code] = Pcategory::where('language_id', $lang->id)->get();
        }
        return view('admin.product.category.edit', $data);
    }

    public function update(Request $request)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $saved_ids = [];
        foreach ($languages as $lang) {
            if ($request->filled('category_id_' . $lang->code) || !$request->filled('pcategory_assoc_id_' . $lang->code)) {//Validation
                $rules = [
                    'name_' . $lang->code => 'required|max:255',
                    'status_' . $lang->code => 'required',
                    // 'parent_id_' . $lang->code => 'required',
                ];


                $be = BasicExtended::first();
                if ($be->theme_version == 'ecommerce') {
                    $image = $request->{'image_' . $lang->code};
                    $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
                    $extImage = pathinfo($image, PATHINFO_EXTENSION);

                    if ($request->filled('image_' . $lang->code)) {
                        $rules['image'] = [
                            function ($attribute, $value, $fail) use ($extImage, $allowedExts) {
                                if (!in_array($extImage, $allowedExts)) {
                                    return $fail("Only png, jpg, jpeg, svg image is allowed");
                                }
                            }
                        ];
                    }
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $errmsgs = $validator->getMessageBag()->add('error', 'true');
                    return response()->json($validator->errors());
                }
            }
        }
        foreach ($languages as $lang) {
            if ($request->filled('category_id_' . $lang->code)) {//update
                $pcategory = Pcategory::findOrFail($request->{'category_id_' . $lang->code});
                $slug = make_slug($request->{'name_' . $lang->code});
                $pcategory->slug = $slug;
                if ($be->theme_version == 'ecommerce' && $request->filled('image_' . $lang->code)) {
                    @unlink('assets/front/img/product/categories/' . $pcategory->image);
                    $filename = uniqid() . '.' . $extImage;
                    if(!is_dir('assets/front/img/product/categories/')){
                        mkdir('assets/front/img/product/categories/',0755,true);
                    }
                    @copy($image, 'assets/front/img/product/categories/' . $filename);
                    $pcategory->image = $filename;
                }
                $pcategory->name = $request->{'name_' . $lang->code};
                $pcategory->status = $request->{'status_' . $lang->code};
                $pcategory->parent_id = $request->{'parent_id_' . $lang->code};
                if ($assoc_id == 0) {
                    $assoc_id = $pcategory->id;
                }

                $pcategory->assoc_id = $assoc_id;
                $pcategory->save();
            } else {
                if (!$request->filled('pcategory_assoc_id_' . $lang->code)) {//create
                    $pcategory = new Pcategory;
                    $slug = make_slug($request->{'name_' . $lang->code});
                    $pcategory->slug = $slug;
                    if ($be->theme_version == 'ecommerce' && $request->filled('image_' . $lang->code)) {
                        @unlink('assets/front/img/product/categories/' . $pcategory->image);
                        $filename = uniqid() . '.' . $extImage;
                        @copy($image, 'assets/front/img/product/categories/' . $filename);
                        $pcategory->image = $filename;
                    }
                    $pcategory->name = $request->{'name_' . $lang->code};
                    $pcategory->status = $request->{'status_' . $lang->code};
                    $pcategory->parent_id = $request->{'parent_id_' . $lang->code};

                    $pcategory->save();
                    $saved_ids[] = $pcategory->id;
                } else {
                    $saved_ids[] = $request->{'pcategory_assoc_id_' . $lang->code};
                }
            }
        }
        foreach ($saved_ids as $saved_id) {
            $pcategory = Pcategory::findOrFail($saved_id);
            $pcategory->assoc_id = $assoc_id;
            $pcategory->save();
        }
        Session::flash('success', 'Category Update successfully!');
        return "success";
    }

    public function deleteFromMegaMenu($category)
    {
        $megamenu = Megamenu::where('language_id', $category->language_id)->where('category', 1)->where('type', 'products');
        if ($megamenu->count() > 0) {
            $megamenu = $megamenu->first();
            $menus = json_decode($megamenu->menus, true);
            $catId = $category->id;
            if (is_array($menus) && array_key_exists("$catId", $menus)) {
                unset($menus["$catId"]);
                $megamenu->menus = json_encode($menus);
                $megamenu->save();
            }
        }
    }


    public function feature(Request $request)
    {
        $category = Pcategory::findOrFail($request->category_id);
        $category->is_feature = $request->is_feature;
        $category->save();

        if ($request->is_feature == 1) {
            Session::flash('success', 'Category featured successfully!');
        } else {
            Session::flash('success', 'Category unfeatured successfully!');
        }
        return back();
    }


    public function home(Request $request)
    {
        $category = Pcategory::findOrFail($request->category_id);
        $category->products_in_home = $request->products_in_home;
        $category->save();

        if ($request->products_in_home == 1) {
            Session::flash('success', 'Products of this category will be available in Home Page!');
        } else {
            Session::flash('success', 'Products of this category will be unavailable in Home Page!');
        }


        return back();
    }


    public function delete(Request $request)
    {
        $_category = Pcategory::findOrFail($request->category_id);
        if ($_category->assoc_id > 0) {
            $categories = Pcategory::where('assoc_id', $_category->assoc_id)->get();
            foreach ($categories as $category) {
                if ($category->products()->count() > 0) {
                    Session::flash('warning', 'First, delete all the product under the selected categories!');
                    return back();
                }

                $this->deleteFromMegaMenu($category);

                $category->delete();
            }
        }else {
            if ($_category->products()->count() > 0) {
                Session::flash('warning', 'First, delete all the product under the selected categories!');
                return back();
            }

            $this->deleteFromMegaMenu($_category);

            $_category->delete();
        }

        Session::flash('success', 'Category deleted successfully!');
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $_category = Pcategory::findOrFail($id);
            if ($_category->assoc_id > 0) {
                $categories = Pcategory::where('assoc_id', $_category->assoc_id)->get();
                foreach ($categories as $category) {
                    if ($category->products()->count() > 0) {
                        Session::flash('warning', 'First, delete all the product under the selected categories!');
                        return "success";
                    }
                }
            }else {
                if ($_category->products()->count() > 0) {
                    Session::flash('warning', 'First, delete all the product under the selected categories!');
                    return "success";
                }
            }
        }

        foreach ($ids as $id) {
            $_category = Pcategory::findOrFail($id);
            if ($_category->assoc_id > 0) {
                $categories = Pcategory::where('assoc_id', $_category->assoc_id)->get();
                foreach ($categories as $category) {
                    $this->deleteFromMegaMenu($category);

                    $category->delete();
                }
            }else {
                $this->deleteFromMegaMenu($_category);

                $_category->delete();
            }
        }

        Session::flash('success', 'product categories deleted successfully!');
        return "success";
    }

}
