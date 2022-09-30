<?php

namespace App\Http\Controllers\Admin;

use App\BasicExtra;
use App\FAQCategory;
use App\Http\Controllers\Controller;
use App\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class FAQCategoryController extends Controller
{
  public function settings()
  {
    $data['abex'] = BasicExtra::first();

    return view('admin.home.faq.settings', $data);
  }

  public function updateSettings(Request $request)
  {
    $bexs = BasicExtra::all();

    foreach ($bexs as $bex) {
      $bex->update([
        'faq_category_status' => $request->faq_category_status
      ]);
    }

    Session::flash('success', 'Settings updated successfully.');

    return redirect()->back();
  }


  public function index(Request $request)
  {
    $language = Language::where('code', $request->language)->first();

    $categories = FAQCategory::where('language_id', $language->id)
      ->orderBy('id', 'desc')
      ->paginate(10);

    return view('admin.home.faq.categories', compact('categories'));
  }

    public function edit($id)
    {
        $fcategory = FAQCategory::findOrFail($id);
        $current_lang = Language::where('id', $fcategory->language_id)->first();
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $data['langs'] = $languages;
        foreach ($languages as $lang) {
            if ($current_lang->id == $lang->id) {
                $data['fcategory'][$lang->code] = $fcategory;
            } else {
                $data['fcategory'][$lang->code] = $fcategory->assoc_id > 0 ? FAQCategory::where('language_id', $lang->id)->where('assoc_id', $fcategory->assoc_id)->first() : null;
            }
            if ($data['fcategory'][$lang->code] == null) {
                $data['fcategory'][$lang->code] = new FAQCategory();
                $data['fcates'][$lang->code] = FAQCategory::where('language_id', $lang->id)->get();
            }
        }

        return view('admin.home.faq.edit_category', $data);
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
          $fcategory = new FAQCategory;

          $fcategory->language_id = $lang->id;
          $fcategory->name = $request->{'name_' . $lang->code};
          $fcategory->status = $request->{'status_' . $lang->code};
          $fcategory->serial_number = $request->{'serial_number_' . $lang->code};
          $fcategory->save();
          if($assoc_id == 0){
              $assoc_id = $fcategory->id;
          }

          $saved_ids[] = $fcategory->id;
      }
      foreach ($saved_ids as $saved_id) {
          $fcategory = FAQCategory::findOrFail($saved_id);
          $fcategory->assoc_id = $assoc_id;
          $fcategory->save();
      }
    Session::flash('success', 'New faq category added successfully.');

    return 'success';
  }

  public function update(Request $request)
  {
      $languages = Language::orderBy('is_default', 'DESC')->get();
      $assoc_id = 0;
      $saved_ids = [];
      foreach ($languages as $lang) {
          if ($request->filled('categoryId_' . $lang->code) || !$request->filled('fcategory_assoc_id_' . $lang->code)) {//Validation
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
              $fcategory = FAQCategory::findOrFail($request->{'categoryId_' . $lang->code});
              $fcategory->language_id = $lang->id;
              $fcategory->name = $request->{'name_' . $lang->code};

              $fcategoryId = $fcategory->id;

              if ($assoc_id == 0) {
                  $assoc_id = $fcategoryId;
              }

              $fcategory->assoc_id = $assoc_id;

              $fcategory->status = $request->{'status_' . $lang->code};
              $fcategory->serial_number = $request->{'serial_number_' . $lang->code};
              $fcategory->save();

          }else {
              if (!$request->filled('fcategory_assoc_id_' . $lang->code)) {//create
                  $fcategory = new FAQCategory;
                  $fcategory->language_id = $lang->id;
                  $fcategory->name = $request->{'name_' . $lang->code};
                  $fcategory->status = $request->{'status_' . $lang->code};
                  $fcategory->serial_number = $request->{'serial_number_' . $lang->code};
                  $fcategory->save();
                  $saved_ids[] = $fcategory->id;
              }else{
                  $saved_ids[] = $request->{'fcategory_assoc_id_' . $lang->code};
              }
          }
      }
      foreach ($saved_ids as $saved_id) {
          $fcategory = FAQCategory::findOrFail($saved_id);
          $fcategory->assoc_id = $assoc_id;
          $fcategory->save();
      }
    Session::flash('success', 'FAQ category updated successfully.');

    return 'success';
  }

  public function delete(Request $request)
  {
    $_category = FAQCategory::findOrFail($request->categoryId);
      if($_category->assoc_id > 0) {
          $categories = FAQCategory::where('assoc_id', $_category->assoc_id)->get();
          foreach ($categories as $category) {
              if ($category->frequentlyAskedQuestion->count() > 0) {
                  Session::flash('warning', 'First delete all the faqs of this category');

                  return redirect()->back();
              }

              $category->delete();
          }
      }else {
          if ($_category->frequentlyAskedQuestion->count() > 0) {
              Session::flash('warning', 'First delete all the faqs of this category');

              return redirect()->back();
          }

          $_category->delete();
      }

    Session::flash('success', 'FAQ category deleted successfully.');

    return redirect()->back();
  }

  public function bulkDelete(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $_category = FAQCategory::findOrFail($id);
        if($_category->assoc_id > 0) {
            $categories = FAQCategory::where('assoc_id', $_category->assoc_id)->get();
            foreach ($categories as $category) {
                if ($category->frequentlyAskedQuestion->count() > 0) {
                    Session::flash('warning', 'First delete all the faqs of those categories');

                    return 'success';
                }
            }
        }else {
            if ($_category->frequentlyAskedQuestion->count() > 0) {
                Session::flash('warning', 'First delete all the faqs of those categories');

                return 'success';
            }
        }
    }

    foreach ($ids as $id) {
      $_category = FAQCategory::findOrFail($id);
        if($_category->assoc_id > 0) {
            $categories = FAQCategory::where('assoc_id', $_category->assoc_id)->get();
            foreach ($categories as $category) {
                $category->delete();
            }
        }else {
            $_category->delete();
        }
    }

    Session::flash('success', 'FAQ categories deleted successfully.');

    return 'success';
  }
}
