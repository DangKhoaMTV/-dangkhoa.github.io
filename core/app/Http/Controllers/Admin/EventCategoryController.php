<?php

namespace App\Http\Controllers\Admin;

use App\EventCategory;
use App\Http\Requests\EventCategory\EventCategoryStoreRequest;
use App\Http\Requests\EventCategory\EventCategoryUpdateRequest;
use App\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Megamenu;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class EventCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return
     */
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->first();
        $lang_id = $lang->id;
        $data['lang_id'] = $lang_id;
        $data['event_categories'] = EventCategory::where('lang_id', $lang_id)->orderBy('id', 'DESC')->paginate(10);
        return view('admin.event.event_category.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function store(Request $request)
    {
       /* EventCategory::create($request->all()+[
                'slug' => make_slug($request->name)
            ]);*/
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $saved_ids = [];
        foreach ($languages as $lang) {
            $rules = [
                'name_' . $lang->code => 'required',
                'status_' . $lang->code => 'required',
            ];

            $rules_msg = [];

            $validator = Validator::make($request->all(), $rules, $rules_msg);

            if ($validator->fails()) {
                $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }
        }
        foreach ($languages as $lang) {
            $event_category = new EventCategory;
            $event_category->lang_id = $lang->id;
            $event_category->slug = make_slug($request->{'name_' . $lang->code});
            $event_category->name = $request->{'name_' . $lang->code};
            $event_category->status = $request->{'status_' . $lang->code};
            $event_category->save();
            if($assoc_id == 0){
                $assoc_id = $event_category->id;
            }

            $saved_ids[] = $event_category->id;
        }
        foreach ($saved_ids as $saved_id) {
            $event_category = EventCategory::findOrFail($saved_id);
            $event_category->assoc_id = $assoc_id;
            $event_category->save();
        }
        Session::flash('success', 'Event category added successfully!');
        return "success";
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = EventCategory::findOrFail($id);
        $current_lang = Language::where('id', $category->lang_id)->first();
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $data['langs'] = $languages;
        foreach ($languages as $lang) {
            if ($current_lang->id == $lang->id) {
                $data['category'][$lang->code] = $category;
            } else {
                $data['category'][$lang->code] = $category->assoc_id > 0 ? EventCategory::where('lang_id', $lang->id)->where('assoc_id', $category->assoc_id)->first() : null;
            }
            if ($data['category'][$lang->code] == null) {
                $data['category'][$lang->code] = new EventCategory();
                $data['ecates'][$lang->code] = EventCategory::where('lang_id', $lang->id)->get();
            }
        }
        return view('admin.event.event_category.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
       /* EventCategory::findOrFail($request->event_category_id)->update($request->all()+[
            'slug' => make_slug($request->name)
        ]);*/
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $saved_ids = [];
        foreach ($languages as $lang) {
            if ($request->filled('event_category_id_' . $lang->code) || !$request->filled('event_category_assoc_id_' . $lang->code)) {//Validation
                $rules = [
                    'name_' . $lang->code => 'required',
                    'status_' . $lang->code => 'required',
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    $validator->getMessageBag()->add('error', 'true');
                    return response()->json($validator->errors());
                }
            }
        }
        foreach ($languages as $lang) {
            if ($request->filled('event_category_id_' . $lang->code)) {//update
                $event_category = EventCategory::findOrFail($request->{'event_category_id_' . $lang->code});
                $event_category->name = $request->{'name_' . $lang->code};
                $event_category->status = $request->{'status_' . $lang->code};
                $event_category->slug = make_slug($request->{'name_' . $lang->code});
                if ($assoc_id == 0) {
                    $assoc_id = $event_category->id;
                }

                $event_category->assoc_id = $assoc_id;
                $event_category->save();
            }else {
                if (!$request->filled('event_category_assoc_id_' . $lang->code)) {//create
                    $event_category = new EventCategory;
                    $event_category->lang_id = $lang->id;
                    $event_category->name = $request->{'name_' . $lang->code};
                    $event_category->status = $request->{'status_' . $lang->code};
                    $event_category->slug = make_slug($request->{'name_' . $lang->code});
                    $event_category->save();
                    $saved_ids[] = $event_category->id;
                }else {
                    $saved_ids[] = $request->{'event_category_assoc_id_' . $lang->code};
                }
            }
        }
        foreach ($saved_ids as $saved_id) {
            $event_category = EventCategory::findOrFail($saved_id);
            $event_category->assoc_id = $assoc_id;
            $event_category->save();
        }
        Session::flash('success', 'Event category updated successfully!');
        return "success";
    }

    public function deleteFromMegaMenu($ecat) {
        $megamenu = Megamenu::where('language_id', $ecat->lang_id)->where('category', 1)->where('type', 'events');
        if ($megamenu->count() > 0) {
            $megamenu = $megamenu->first();
            $menus = json_decode($megamenu->menus, true);
            $catId = $ecat->id;
            if (is_array($menus) && array_key_exists("$catId", $menus)) {
                unset($menus["$catId"]);
                $megamenu->menus = json_encode($menus);
                $megamenu->save();
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $_ecat = EventCategory::findOrFail($request->event_category_id);
        if($_ecat->assoc_id > 0) {
            $ecats = EventCategory::where('assoc_id', $_ecat->assoc_id)->get();
            foreach ($ecats as $ecat) {
                $this->deleteFromMegaMenu($ecat);
                $ecat->delete();
            }
        }else {
            $this->deleteFromMegaMenu($_ecat);
            $_ecat->delete();
        }
        Session::flash('success', 'Event category deleted successfully!');
        return back();
    }

    public function bulkDelete(Request $request)
    {
        return DB::transaction(function() use ($request){
            $ids = $request->ids;
            foreach ($ids as $id) {
                $_ecat = EventCategory::findOrFail($id);
                if($_ecat->assoc_id > 0) {
                    $ecats = EventCategory::where('assoc_id', $_ecat->assoc_id)->get();
                    foreach ($ecats as $ecat) {
                        $this->deleteFromMegaMenu($ecat);
                        $ecat->delete();
                    }
                }else {
                    $this->deleteFromMegaMenu($_ecat);
                    $_ecat->delete();
                }
            }
            Session::flash('success', 'Event category deleted successfully!');
            return "success";
        });
    }
}
