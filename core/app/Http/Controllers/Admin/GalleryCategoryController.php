<?php

namespace App\Http\Controllers\Admin;

use App\BasicExtra;
use App\GalleryCategory;
use App\Http\Controllers\Controller;
use App\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class GalleryCategoryController extends Controller
{
  public function settings()
  {
    $data['abex'] = BasicExtra::first();

    return view('admin.gallery.settings', $data);
  }

  public function updateSettings(Request $request)
  {
    $bexs = BasicExtra::all();
    foreach ($bexs as $bex) {
        $filename = '';
        if ($request->filled('gallery_category_bg')) {
            $image = $request->gallery_category_bg ;
            $extImage = pathinfo($image, PATHINFO_EXTENSION);
            @unlink('assets/front/img/gallery/' . $bex->gallery_category_bg);
            $filename = uniqid() . '.' . $extImage;
            if(!is_dir('assets/front/img/gallery/')){
                mkdir('assets/front/img/gallery/',0755,true);
            }
            @copy($image, 'assets/front/img/gallery/' . $filename);
            $bex->gallery_category_bg = $filename;
            $bex->save();
        }

      $bex->update([
        'gallery_category_status' => $request->gallery_category_status,
      ]);
    }

    Session::flash('success', 'Settings updated successfully.');

    return redirect()->back();
  }


  public function index(Request $request)
  {
    $language = Language::where('code', $request->language)->first();

    $categories = GalleryCategory::where('language_id', $language->id)
      ->orderBy('id', 'desc')
      ->paginate(10);

    return view('admin.gallery.categories', compact('categories'));
  }

    public function edit_modal($id)
    {
        $gcategory = GalleryCategory::findOrFail($id);
        $current_lang = Language::where('id', $gcategory->language_id)->first();
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $data['langs'] = $languages;
        foreach ($languages as $lang) {
            if ($current_lang->id == $lang->id) {
                $data['gcategory'][$lang->code] = $gcategory;
            } else {
                $data['gcategory'][$lang->code] = $gcategory->assoc_id > 0 ? GalleryCategory::where('language_id', $lang->id)->where('assoc_id', $gcategory->assoc_id)->first() : null;
            }
            if ($data['gcategory'][$lang->code] == null) {
                $data['gcategory'][$lang->code] = new GalleryCategory();
                $data['gcates'][$lang->code] = GalleryCategory::where('language_id', $lang->id)->get();
            }
        }

        return view('admin.gallery.edit-category-modal', $data);
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
          $gcategory = new GalleryCategory;
          $gcategory->language_id = $lang->id;
          $gcategory->name = $request->{'name_' . $lang->code};
          $gcategory->status = $request->{'status_' . $lang->code};
          $gcategory->serial_number = $request->{'serial_number_' . $lang->code};

          $gcategory->save();

          if($assoc_id == 0){
              $assoc_id = $gcategory->id;
          }

          $saved_ids[] = $gcategory->id;
      }

      foreach ($saved_ids as $saved_id) {
          $gcategory = GalleryCategory::findOrFail($saved_id);
          $gcategory->assoc_id = $assoc_id;
          $gcategory->save();
      }

    Session::flash('success', 'New gallery category added successfully.');

    return 'success';
  }

  public function update(Request $request)
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
          if ($request->filled('categoryId_' . $lang->code)) {//update
              $gcategory = GalleryCategory::findOrFail($request->{'categoryId_' . $lang->code});
              $gcategory->language_id = $lang->id;
              $gcategory->name = $request->{'name_' . $lang->code};

              $gcategoryId = $gcategory->id;

              if ($assoc_id == 0) {
                  $assoc_id = $gcategoryId;
              }

              $gcategory->assoc_id = $assoc_id;

              $gcategory->status = $request->{'status_' . $lang->code};
              $gcategory->serial_number = $request->{'serial_number_' . $lang->code};
              $gcategory->save();
          }else {
              if (!$request->filled('category_assoc_id_' . $lang->code)) {//create
                  $gcategory = new GalleryCategory;
                  $gcategory->language_id = $lang->id;
                  $gcategory->name = $request->{'name_' . $lang->code};
                  $gcategory->status = $request->{'status_' . $lang->code};
                  $gcategory->serial_number = $request->{'serial_number_' . $lang->code};
                  $gcategory->save();
                  $saved_ids[] = $gcategory->id;
              }else{
                  $saved_ids[] = $request->{'category_assoc_id_' . $lang->code};
              }
          }
      }
      foreach ($saved_ids as $saved_id) {
          $gcategory = GalleryCategory::findOrFail($saved_id);
          $gcategory->assoc_id = $assoc_id;
          $gcategory->save();
      }

    Session::flash('success', 'Gallery category updated successfully.');

    return 'success';
  }

  public function delete(Request $request)
  {
    $_category = GalleryCategory::findOrFail($request->categoryId);

    if($_category->assoc_id > 0){
        $categories = GalleryCategory::where('assoc_id', $_category->assoc_id)->get();
        foreach ($categories as $category) {
            if ($category->galleryImg->count() > 0) {
                Session::flash('warning', 'First delete all the images of this category');

                return redirect()->back();
            }
            $category->delete();
        }
    }else {
        if ($_category->galleryImg->count() > 0) {
            Session::flash('warning', 'First delete all the images of this category');

            return redirect()->back();
        }
        $_category->delete();
    }

    Session::flash('success', 'Gallery category deleted successfully.');

    return redirect()->back();
  }

  public function bulkDelete(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $_category = GalleryCategory::findOrFail($id);
        if($_category->assoc_id > 0) {
            $categories = GalleryCategory::where('assoc_id', $_category->assoc_id)->get();
            foreach ($categories as $category){
                if ($category->galleryImg->count() > 0) {
                    Session::flash('warning', 'First delete all the images of those categories');
                    return 'success';
                }
            }
        } else {
            if ($_category->galleryImg->count() > 0) {
                Session::flash('warning', 'First delete all the images of those categories');
                return 'success';
            }
        }
    }

    foreach ($ids as $id) {
      $_category = GalleryCategory::findOrFail($id);
        if($_category->assoc_id > 0) {
            $categories = GalleryCategory::where('assoc_id', $_category->assoc_id)->get();
            foreach ($categories as $category){
                $category->delete();
            }
        }else {
            $_category->delete();
        }

    }

    Session::flash('success', 'Gallery categories deleted successfully.');

    return 'success';
  }
}
