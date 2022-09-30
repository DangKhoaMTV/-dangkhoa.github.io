<?php

namespace App\Http\Controllers\Admin;

use App\BasicExtra;
use App\Http\Controllers\Controller;
use App\Language;
use App\PackageCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PackageCategoryController extends Controller
{
  public function index(Request $request)
  {
    $language = Language::where('code', $request->language)->first();

    $categories = PackageCategory::where('language_id', $language->id)
      ->orderBy('id', 'desc')
      ->paginate(10);

    return view('admin.package.categories', compact('categories'));
  }

    public function edit($id)
    {
        $pcategory = PackageCategory::findOrFail($id);
        $current_lang = Language::where('id', $pcategory->language_id)->first();
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $data['langs'] = $languages;
        foreach ($languages as $lang) {
            if ($current_lang->id == $lang->id) {
                $data['pcategory'][$lang->code] = $pcategory;
            } else {
                $data['pcategory'][$lang->code] = $pcategory->assoc_id > 0 ? PackageCategory::where('language_id', $lang->id)->where('assoc_id', $pcategory->assoc_id)->first() : null;
            }
            if ($data['pcategory'][$lang->code] == null) {
                $data['pcategory'][$lang->code] = new PackageCategory();
                $data['pcates'][$lang->code] = PackageCategory::where('language_id', $lang->id)->get();
            }
        }

        return view('admin.package.edit_category', $data);
    }

  public function store(Request $request)
  {
      $languages = Language::orderBy('is_default', 'DESC')->get();
      $assoc_id = 0;
      $saved_ids = [];
      foreach ($languages as $lang) {
          $rules = [
              'name_' . $lang->code => 'required',
              'status_' . $lang->code => 'required',
              'serial_number_' . $lang->code => 'required'
          ];

          $validator = Validator::make($request->all(), $rules);

          if ($validator->fails()) {
              $validator->getMessageBag()->add('error', 'true');

              return response()->json($validator->errors());
          }
      }

      foreach ($languages as $lang) {
          $pcategory = new PackageCategory;

          $pcategory->language_id = $lang->id;
          $pcategory->name = $request->{'name_' . $lang->code};
          $pcategory->status = $request->{'status_' . $lang->code};
          $pcategory->serial_number = $request->{'serial_number_' . $lang->code};
          $pcategory->save();
          if($assoc_id == 0){
              $assoc_id = $pcategory->id;
          }

          $saved_ids[] = $pcategory->id;
      }
      foreach ($saved_ids as $saved_id) {
          $pcategory = PackageCategory::findOrFail($saved_id);
          $pcategory->assoc_id = $assoc_id;
          $pcategory->save();
      }

    Session::flash('success', 'New package category added successfully.');

    return 'success';
  }

  public function update(Request $request)
  {
      $languages = Language::orderBy('is_default', 'DESC')->get();
      $assoc_id = 0;
      $saved_ids = [];
      foreach ($languages as $lang) {
          if ($request->filled('categoryId_' . $lang->code) || !$request->filled('pcategory_assoc_id_' . $lang->code)) {//Validation
              $rules = [
                  'name_' . $lang->code => 'required',
                  'status_' . $lang->code => 'required',
                  'serial_number_' . $lang->code => 'required'
              ];

              $validator = Validator::make($request->all(), $rules);

              if ($validator->fails()) {
                  $validator->getMessageBag()->add('error', 'true');

                  return response()->json($validator->errors());
              }
          }
      }
      foreach ($languages as $lang) {
          if ($request->filled('categoryId_' . $lang->code)) {//update
              $pcategory = PackageCategory::findOrFail($request->{'categoryId_' . $lang->code});
              $pcategory->language_id = $lang->id;
              $pcategory->name = $request->{'name_' . $lang->code};

              $pcategoryId = $pcategory->id;

              if ($assoc_id == 0) {
                  $assoc_id = $pcategoryId;
              }

              $pcategory->assoc_id = $assoc_id;

              $pcategory->status = $request->{'status_' . $lang->code};
              $pcategory->serial_number = $request->{'serial_number_' . $lang->code};
              $pcategory->save();

          }else {
              if (!$request->filled('pcategory_assoc_id_' . $lang->code)) {//create
                  $pcategory = new PackageCategory;
                  $pcategory->language_id = $lang->id;
                  $pcategory->name = $request->{'name_' . $lang->code};
                  $pcategory->status = $request->{'status_' . $lang->code};
                  $pcategory->serial_number = $request->{'serial_number_' . $lang->code};
                  $pcategory->save();
                  $saved_ids[] = $pcategory->id;
              }else{
                  $saved_ids[] = $request->{'pcategory_assoc_id_' . $lang->code};
              }
          }
      }
      foreach ($saved_ids as $saved_id) {
          $pcategory = PackageCategory::findOrFail($saved_id);
          $pcategory->assoc_id = $assoc_id;
          $pcategory->save();
      }
    Session::flash('success', 'Package category updated successfully.');

    return 'success';
  }

  public function delete(Request $request)
  {
    $_category = PackageCategory::findOrFail($request->categoryId);
      if($_category->assoc_id > 0) {
          $categories = PackageCategory::where('assoc_id', $_category->assoc_id)->get();
          foreach ($categories as $category) {

              if ($category->packageList()->count() > 0) {
                  Session::flash('warning', 'First delete all the packages of this category');

                  return redirect()->back();
              }
              $category->delete();
          }
      }else {
          if ($_category->packageList()->count() > 0) {
              Session::flash('warning', 'First delete all the faqs of this category');

              return redirect()->back();
          }

          $_category->delete();
      }

    Session::flash('success', 'Package category deleted successfully.');

    return redirect()->back();
  }

  public function bulkDelete(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $_category = PackageCategory::findOrFail($id);
        if($_category->assoc_id > 0) {
            $categories = PackageCategory::where('assoc_id', $_category->assoc_id)->get();
            foreach ($categories as $category) {
                if ($category->packageList()->count() > 0) {
                    Session::flash('warning', 'First delete all the packages of those categories');

                    return 'success';
                }
            }
        }else {
            if ($_category->packageList()->count() > 0) {
                Session::flash('warning', 'First delete all the packages of those categories');

                return 'success';
            }
        }
    }

    foreach ($ids as $id) {
        $_category = PackageCategory::findOrFail($id);
        if($_category->assoc_id > 0) {
            $categories = PackageCategory::where('assoc_id', $_category->assoc_id)->get();
            foreach ($categories as $category) {

                $category->delete();
            }
        }else {
            $_category->delete();
        }
    }

    Session::flash('success', 'Package categories deleted successfully.');

    return 'success';
  }
}
