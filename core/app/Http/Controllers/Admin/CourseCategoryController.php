<?php

namespace App\Http\Controllers\Admin;

use App\CourseCategory;
use App\Http\Controllers\Controller;
use App\Language;
use App\Megamenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CourseCategoryController extends Controller
{
    public function index(Request $request)
    {
        $language = Language::where('code', $request->language)->first();
        $language_id = $language->id;

        $course_categories = CourseCategory::where('language_id', $language_id)
            ->orderBy('serial_number', 'asc')
            ->paginate(10);

        return view('admin.course.course_category.index', compact('course_categories'));
    }

    public function edit($id)
    {
        $category = CourseCategory::findOrFail($id);
        $current_lang = Language::where('id', $category->language_id)->first();
        $languages = Language::all();
        $data['langs'] = $languages;
        foreach ($languages as $lang) {
            if ($current_lang->id == $lang->id) {
                $data['category'][$lang->code] = $category;
            } else {
                $data['category'][$lang->code] = $category->assoc_id > 0 ? CourseCategory::where('language_id', $lang->id)->where('assoc_id', $category->assoc_id)->first() : null;
            }
            if ($data['category'][$lang->code] == null) {
                $data['category'][$lang->code] = new CourseCategory();
                $data['ccates'][$lang->code] = CourseCategory::where('language_id', $lang->id)->get();
            }
        }

        return view('admin.course.course_category.edit', $data);
    }

    public function store(Request $request)
    {
        $languages = Language::all();
        $assoc_id = 0;
        $saved_ids = [];
        foreach ($languages as $lang) {
            $rules = [
                'name_' . $lang->code => 'required',
                'status_' . $lang->code => 'required',
                'serial_number_' . $lang->code => 'required'
            ];

            $rules_msg = [
                'language_id.required' => 'The language field is required'
            ];

            $validator = Validator::make($request->all(), $rules, $rules_msg);

            if ($validator->fails()) {
                $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }
        }
        foreach ($languages as $lang) {
            $course_category = new CourseCategory;
            $course_category->language_id = $lang->id;
            $course_category->name = $request->{'name_' . $lang->code};
            $course_category->status = $request->{'status_' . $lang->code};
            $course_category->serial_number = $request->{'serial_number_' . $lang->code};
            $course_category->save();
            if($assoc_id == 0){
                $assoc_id = $course_category->id;
            }

            $saved_ids[] = $course_category->id;
        }
        foreach ($saved_ids as $saved_id) {
            $course_category = CourseCategory::findOrFail($saved_id);
            $course_category->assoc_id = $assoc_id;
            $course_category->save();
        }
        Session::flash('success', 'New Course Category Has Added');

        return 'success';
    }

    public function update(Request $request)
    {
        $languages = Language::all();
        $assoc_id = 0;
        $saved_ids = [];
        foreach ($languages as $lang) {
            if ($request->filled('course_category_id_' . $lang->code) || !$request->filled('course_category_assoc_id_' . $lang->code)) {//Validation
                $rules = [
                    'name_' . $lang->code => 'required',
                    'status_' . $lang->code => 'required',
                    'serial_number_' . $lang->code => 'required|integer'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    $validator->getMessageBag()->add('error', 'true');
                    return response()->json($validator->errors());
                }
            }
        }
        foreach ($languages as $lang) {
            if ($request->filled('course_category_id_' . $lang->code)) {//update
                $course_category = CourseCategory::findOrFail($request->{'course_category_id_' . $lang->code});
                $course_category->name = $request->{'name_' . $lang->code};
                $course_category->status = $request->{'status_' . $lang->code};
                $course_category->serial_number = $request->{'serial_number_' . $lang->code};
                if ($assoc_id == 0) {
                    $assoc_id = $course_category->id;
                }

                $course_category->assoc_id = $assoc_id;
                $course_category->save();
            }else {
                if (!$request->filled('course_category_assoc_id_' . $lang->code)) {//create
                    $course_category = new CourseCategory;
                    $course_category->language_id = $lang->id;
                    $course_category->name = $request->{'name_' . $lang->code};
                    $course_category->status = $request->{'status_' . $lang->code};
                    $course_category->serial_number = $request->{'serial_number_' . $lang->code};
                    $course_category->save();
                    $saved_ids[] = $course_category->id;
                }else {
                    $saved_ids[] = $request->{'course_category_assoc_id_' . $lang->code};
                }
            }
        }
        foreach ($saved_ids as $saved_id) {
            $course_category = CourseCategory::findOrFail($saved_id);
            $course_category->assoc_id = $assoc_id;
            $course_category->save();
        }
        Session::flash('success', 'Course Category Has Updated Successfully');

        return 'success';
    }

    public function deleteFromMegaMenu($category)
    {
        $megamenu = Megamenu::where('language_id', $category->language_id)->where('category', 1)->where('type', 'courses');
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

    public function delete(Request $request)
    {
        $_course_category = CourseCategory::findOrFail($request->course_category_id);
        if($_course_category->assoc_id > 0) {
            $course_categories = CourseCategory::where('assoc_id', $_course_category->assoc_id)->get();
            foreach ($course_categories as $course_category) {
                if ($course_category->courses->count() > 0) {
                    Session::flash('warning', 'First Delete All The Courses of This Category');

                    return back();
                }

                $this->deleteFromMegaMenu($course_category);

                $course_category->delete();
            }
        }else {
            if ($_course_category->courses->count() > 0) {
                Session::flash('warning', 'First Delete All The Courses of This Category');

                return back();
            }

            $this->deleteFromMegaMenu($_course_category);

            $_course_category->delete();
        }

        Session::flash('success', 'Course Category Has Deleted Successfully');

        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $_course_category = CourseCategory::findOrFail($id);
            if($_course_category->assoc_id > 0) {
                $course_categories = CourseCategory::where('assoc_id', $_course_category->assoc_id)->get();
                foreach ($course_categories as $course_category) {
                    if ($course_category->courses->count() > 0) {
                        Session::flash('warning', 'First Delete All The Courses of Those Categories');

                        return 'success';
                    }
                }
            }else {
                if ($_course_category->courses->count() > 0) {
                    Session::flash('warning', 'First Delete All The Courses of Those Categories');

                    return 'success';
                }
            }
        }

        foreach ($ids as $id) {
            $_course_category = CourseCategory::findOrFail($id);
            if($_course_category->assoc_id > 0) {
                $course_categories = CourseCategory::where('assoc_id', $_course_category->assoc_id)->get();
                foreach ($course_categories as $course_category) {
                    $this->deleteFromMegaMenu($course_category);

                    $course_category->delete();
                }
            }else {
                $this->deleteFromMegaMenu($_course_category);

                $_course_category->delete();
            }
        }

        Session::flash('success', 'Course Categories Has Deleted');

        return 'success';
    }
}
