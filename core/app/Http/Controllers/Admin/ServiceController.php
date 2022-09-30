<?php

namespace App\Http\Controllers\Admin;

use App\Attribute;
use App\BasicExtended;
use App\BasicExtra;
use App\ServiceAttribute;
use App\ServiceImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Service;
use App\Scategory;
use App\Language;
use App\Megamenu;
use Validator;
use Session;

class ServiceController extends Controller
{

    public function settings()
    {
        $data['abex'] = BasicExtra::first();
        return view('admin.service.settings', $data);
    }

    public function updateSettings(Request $request)
    {
        $bexs = BasicExtra::all();
        foreach ($bexs as $bex) {
            $bex->service_category = $request->service_category;
            if ($request->filled('service_page_bg_image')) {
                $image = $request->{'service_page_bg_image'};
                $extImage = pathinfo($image, PATHINFO_EXTENSION);
                @unlink('assets/front/img/services/' . $bex->service_page_bg_image);
                $filename = uniqid() . '.' . $extImage;
                @copy($image, 'assets/front/img/services/' . $filename);
                $bex->service_page_bg_image = $filename;
            }
            $bex->save();
        }

        $request->session()->flash('success', 'Settings updated successfully!');
        return back();
    }

    public function index(Request $request)
    {
        $_lang = Language::where('code', $request->language)->first();

        $lang_id = $_lang->id;
        $data['services'] = Service::where('language_id', $lang_id)->orderBy('id', 'DESC')->get();

        if($request->type && ($request->type == 'indoor' || $request->type == 'outdoor')){
            $data['services'] = Service::where('language_id', $lang_id)->where('type', $request->type)->orderBy('id', 'DESC')->get();
        }

        $data['lang_id'] = $lang_id;
        $data['abe'] = BasicExtended::where('language_id', $lang_id)->first();
        $languages = Language::orderBy('is_default', 'DESC')->get();
        foreach ($languages as $lang) {
            $data['attributes'][$lang->code] = Attribute::where('language_id', $lang->id)->where('type', 'service')->get();
        }

        return view('admin.service.service.index', $data);
    }

    public function edit($id)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $service = Service::findOrFail($id);
        $current_lang_id = $service->language_id;
        $data = array();
        $data['language_id'] = $current_lang_id;
        foreach ($languages as $lang) {
            if ($current_lang_id == $lang->id) {
                $data['service'][$lang->code] = $service;
            } else {
                $data['service'][$lang->code] = $service->assoc_id > 0 ? Service::where('language_id', $lang->id)->where('assoc_id', $service->assoc_id)->first() : null;
            }
            if ($data['service'][$lang->code] == null) {
                $data['service'][$lang->code] = new Service;
                $data['scates'][$lang->code] = Service::where('language_id', $lang->id)->get();
            }
            $data['sattributes'][$lang->code] = ServiceAttribute::where('language_id', $lang->id)->where('service_id', $data['service'][$lang->code]->id)->orderBy('serial_number', 'ASC')->get();
            $data['attributes'][$lang->code] = Attribute::where('language_id', $lang->id)->where('type', 'service')->get();
            $data['ascats'][$lang->code] = Scategory::where('status', 1)->where('language_id', $lang->id)->get();
            $data['abe'][$lang->code] = BasicExtended::where('language_id', $lang->id)->first();
        }
        return view('admin.service.service.edit', $data);
    }

    public function edit_modal($id)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $service = Service::findOrFail($id);
        $current_lang_id = $service->language_id;
        $data = array();
        foreach ($languages as $lang) {
            if ($current_lang_id == $lang->id) {
                $data['service'][$lang->code] = $service;
            } else {
                $data['service'][$lang->code] = $service->assoc_id > 0 ? Service::where('language_id', $lang->id)->where('assoc_id', $service->assoc_id)->first() : null;
            }
            if ($data['service'][$lang->code] == null) {
                $data['service'][$lang->code] = new Service;
                $data['scates'][$lang->code] = Service::where('language_id', $lang->id)->get();
            }
            $data['sattributes'][$lang->code] = ServiceAttribute::where('language_id', $lang->id)->where('service_id', $data['service'][$lang->code]->id)->orderBy('serial_number', 'ASC')->get();
            $data['attributes'][$lang->code] = Attribute::where('language_id', $lang->id)->where('type', 'service')->get();
            $data['ascats'][$lang->code] = Scategory::where('status', 1)->where('language_id', $lang->id)->get();
            $data['abe'][$lang->code] = BasicExtended::where('language_id', $lang->id)->first();
        }
        return view('admin.service.service.edit-modal', $data);
    }

    public function store(Request $request)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        foreach ($languages as $lang) {
            $image = $request->{'image_' . $lang->code};
            $extImage = pathinfo($image, PATHINFO_EXTENSION);

            $messages = [];

            $slug = make_slug($request->{'title_' . $lang->code});
            $type = $request->{'type_' . $lang->code};
            $rules = [
                'image_' . $lang->code => 'required',
                'type_' . $lang->code => 'required',
                'title_' . $lang->code => [
                    'required',
                    'max:255',
                    function ($attribute, $value, $fail) use ($slug) {
                        $count_services = Service::whereRaw('LOWER(slug) = ?', [strtolower($slug)])->count();
                        if ($count_services > 0) {
                            $fail('The title field must be unique.');
                        }
                    }
                ],
                'serial_number_' . $lang->code => 'required',
                'content_' . $lang->code => 'required',
                'details_page_status_' . $lang->code => 'required',
                'service_attribute_status_' . $lang->code => 'required',
                'summary_' . $lang->code => 'required',
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

            // if 'theme version'contains service category
            if (serviceCategory()) {
                $rules['category_' . $lang->code] = 'required';
            }

            // if 'theme version' doesn't contain service category
            if ($request->{'details_page_status_' . $lang->code} == 0) {
                $rules['content_' . $lang->code] = 'nullable';
            }

            //Checked service attribute
            if ($request->{'service_attribute_status_' . $lang->code} == 1 && $type == 'indoor') {
                //Validate Attribute
                if (!empty($request->{'service_attribute_' . $lang->code})) {
                    foreach ($request->{'service_attribute_' . $lang->code} as $key => $val) {
                        $rules['service_attribute_' . $lang->code . '.' . $key . '.text'] = 'required';
                        $messages['service_attribute_' . $lang->code . '.' . $key . '.text.required'] = 'Attribute Text Is Required';
                    }
                }
            }

            $sliders = !empty($request->{'slider_' . $lang->code}) ? explode(',', $request->{'slider_' . $lang->code}) : [];
            if ($request->filled('slider_' . $lang->code)) {
                $rules['slider_' . $lang->code] = [
                    function ($attribute, $value, $fail) use ($sliders, $allowedExts) {
                        foreach ($sliders as $key => $slider) {
                            $extSlider = pathinfo($slider, PATHINFO_EXTENSION);
                            if (!in_array($extSlider, $allowedExts)) {
                                return $fail("Only png, jpg, jpeg images are allowed");
                            }
                        }
                    }
                ];
            }

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $errmsgs = $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }
        }
        foreach ($languages as $lang) {
            $service = new Service;
            $type = $request->{'type_' . $lang->code};
            $service->language_id = $lang->id;
            $service->feature = 1;
            $service->title = $request->{'title_' . $lang->code};
            $service->type = $request->{'type_' . $lang->code};
            $slug = make_slug($request->{'title_' . $lang->code});
            if ($request->filled('image_' . $lang->code)) {
                $image = $request->{'image_' . $lang->code};
                $extImage = pathinfo($image, PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extImage;
                @copy($image, 'assets/front/img/services/' . $filename);
                $service->main_image = $filename;
            }

            $service->slug = $slug;
            // if 'theme version'contains service category
            if (serviceCategory()) {
                $service->scategory_id = $request->{'category_' . $lang->code};
            }
            $service->summary = $request->{'summary_' . $lang->code};
            $service->details_page_status = $request->{'details_page_status_' . $lang->code};
            $service->service_attribute_status = $request->{'service_attribute_status_' . $lang->code};
            $service->meta_description = $request->{'meta_description_' . $lang->code};
            $service->meta_keywords = $request->{'meta_keywords_' . $lang->code};
            $service->serial_number = $request->{'serial_number_' . $lang->code};
            $service->content = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'content_' . $lang->code});
            $service->save();

            if ($request->{'service_attribute_status_' . $lang->code} == 1 && $type == 'indoor') {
                //Add service Attribute
                if (!empty($request->{'service_attribute_' . $lang->code})) {
                    foreach ($request->{'service_attribute_' . $lang->code} as $key => $val) {
                        $service_attribute = new ServiceAttribute;
                        $service_attribute->service_id = $service->id;
                        $service_attribute->attribute_id = $val['attribute_id'];
                        $service_attribute->text = $val['text'];
                        $service_attribute->language_id = $lang->id;

                        $attr = Attribute::where('id', $val['attribute_id'])->where('language_id', $lang->id)->first();
                        $service_attribute->serial_number = $attr->serial_number;

                        $check_attr = ServiceAttribute::where('service_id', $service->id)->where('attribute_id', $val['attribute_id'])->where('language_id', $lang->id)->first();
                        if (!empty($check_attr)) {
                            continue;
                        }
                        $service_attribute->save();
                    }
                }
            }

            if ($assoc_id == 0) {
                $assoc_id = $service->id;
            }
            if ($assoc_id > 0) {
                $service->assoc_id = $assoc_id;
                $service->save();
            }

            $sliders = !empty($request->{'slider_' . $lang->code}) ? explode(',', $request->{'slider_' . $lang->code}) : [];
            foreach ($sliders as $key => $slider) {
                $extSlider = pathinfo($slider, PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extSlider;
                @copy($slider, 'assets/front/img/services/sliders/' . $filename);

                $pi = new ServiceImage();
                $pi->service_id = $service->id;
                $pi->image = $filename;
                $pi->save();
            }
        }
        Session::flash('success', 'Service added successfully!');
        return "success";
    }

    public function update(Request $request)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $saved_ids = [];
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        $messages = [];
        $type = '';
        foreach ($languages as $lang) {
            if (!$request->filled('service_assoc_id_' . $lang->code) || $request->filled('service_id_' . $lang->code)) {
                $slug = make_slug($request->{'title_' . $lang->code});
                $serviceId = $request->{'service_id_' . $lang->code};
                $type = $request->{'type_' . $lang->code};

                $image = $request->{'image_' . $lang->code};
                $extImage = pathinfo($image, PATHINFO_EXTENSION);

                $rules = [
                    'title_' . $lang->code => [
                        'required',
                        'max:255',
                        function ($attribute, $value, $fail) use ($slug, $serviceId, $lang) {
                            $count_services = Service::whereRaw('LOWER(slug) = ? AND id <> ? AND language_id = ?', [strtolower($slug), $serviceId, $lang->id])->count();
                            if ($count_services > 0) {
                                $fail('The title field must be unique.');
                            }
                        }
                    ],
                    'type_' . $lang->code => 'required',
                    'content_' . $lang->code => 'required',
                    'serial_number_' . $lang->code => 'required',
                    'details_page_status_' . $lang->code => 'required',
                    'summary_' . $lang->code => 'required',
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

                //Checked service attribute
                if ($request->{'service_attribute_status_' . $lang->code} == 1 && $type == 'indoor') {
                    //Validate Attribute
                    if (!empty($request->{'service_attribute_' . $lang->code})) {
                        foreach ($request->{'service_attribute_' . $lang->code} as $key => $val) {
                            $rules['service_attribute_' . $lang->code . '.' . $key . '.text'] = 'required';
                            $messages['service_attribute_' . $lang->code . '.' . $key . '.text.required'] = 'Attribute Text Is Required';
                        }
                    }
                }

                if (serviceCategory()) {
                    $rules['category_' . $lang->code] = 'required';
                }

                if ($request->{'details_page_status_' . $lang->code} == 0) {
                    $rules['content_' . $lang->code] = 'nullable';
                }

                $sliders = !empty($request->{'slider_' . $lang->code}) ? explode(',', $request->{'slider_' . $lang->code}) : [];
                if ($request->filled('slider_' . $lang->code)) {
                    $rules['slider_' . $lang->code] = [
                        function ($attribute, $value, $fail) use ($sliders, $allowedExts) {
                            foreach ($sliders as $key => $slider) {
                                $extSlider = pathinfo($slider, PATHINFO_EXTENSION);
                                if (!in_array($extSlider, $allowedExts)) {
                                    return $fail("Only png, jpg, jpeg images are allowed");
                                }
                            }
                        }
                    ];
                }

                $validator = Validator::make($request->all(), $rules, $messages);
                if ($validator->fails()) {
                    $errmsgs = $validator->getMessageBag()->add('error', 'true');
                    return response()->json($validator->errors());
                }
            }
        }
        foreach ($languages as $lang) {
            if ($request->filled('service_id_' . $lang->code)) {//update
                $slug = make_slug($request->{'title_' . $lang->code});
                $service = Service::findOrFail($request->{'service_id_' . $lang->code});
                $serviceId = $request->{'service_id_' . $lang->code};
                if ($assoc_id == 0) {
                    $assoc_id = $serviceId;
                }
                $service->title = $request->{'title_' . $lang->code};
                $service->type = $request->{'type_' . $lang->code};
                $service->slug = $slug;
                if (serviceCategory()) {
                    $service->scategory_id = $request->{'category_' . $lang->code};
                }
                $service->summary = $request->{'summary_' . $lang->code};
                $service->details_page_status = $request->{'details_page_status_' . $lang->code};
                $service->service_attribute_status = $request->{'service_attribute_status_' . $lang->code};
                $service->serial_number = $request->{'serial_number_' . $lang->code};
                $service->meta_keywords = $request->{'meta_keywords_' . $lang->code};
                $service->meta_description = $request->{'meta_description_' . $lang->code};
                $service->content = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'content_' . $lang->code});

                if ($request->filled('image_' . $lang->code)) {
                    $image = $request->{'image_' . $lang->code};
                    $extImage = pathinfo($image, PATHINFO_EXTENSION);
                    @unlink('assets/front/img/services/' . $service->main_image);
                    $filename = uniqid() . '.' . $extImage;
                    @copy($image, 'assets/front/img/services/' . $filename);
                    $service->main_image = $filename;
                }

                // copy the sliders first
                $sliders = !empty($request->{'slider_' . $lang->code}) ? explode(',', $request->{'slider_' . $lang->code}) : [];
                $fileNames = [];
                foreach ($sliders as $key => $slider) {
                    $extSlider = pathinfo($slider, PATHINFO_EXTENSION);
                    $filename = uniqid() . '.' . $extSlider;
                    @copy($slider, 'assets/front/img/services/sliders/' . $filename);
                    $fileNames[] = $filename;
                }

                // delete & unlink previous slider images
                $pis = ServiceImage::where('service_id', $service->id)->get();
                foreach ($pis as $key => $pi) {
                    @unlink('assets/front/img/services/sliders/' . $pi->image);
                    $pi->delete();
                }

                // store new slider images
                foreach ($fileNames as $key => $fileName) {
                    $pi = new ServiceImage;
                    $pi->service_id = $service->id;
                    $pi->image = $fileName;
                    $pi->save();
                }

                if ($request->{'service_attribute_status_' . $lang->code} == 1 && $type == 'indoor') {
                    // delete Service attribute
                    $sas = ServiceAttribute::where('service_id', $service->id)->get();
                    foreach ($sas as $key => $sa) {
                        $sa->delete();
                    }

                    //Add service Attribute
                    if (!empty($request->{'service_attribute_' . $lang->code})) {
                        foreach ($request->{'service_attribute_' . $lang->code} as $key => $val) {
                            $service_attribute = new ServiceAttribute;
                            $service_attribute->service_id = $service->id;
                            $service_attribute->attribute_id = $val['attribute_id'];
                            $service_attribute->text = $val['text'];
                            $service_attribute->language_id = $lang->id;

                            $attr = Attribute::where('id', $val['attribute_id'])->where('language_id', $lang->id)->first();
                            $service_attribute->serial_number = $attr->serial_number;

                            $check_attr = ServiceAttribute::where('service_id', $service->id)->where('attribute_id', $val['attribute_id'])->where('language_id', $lang->id)->first();
                            if (!empty($check_attr)) {
                                continue;
                            }
                            $service_attribute->save();
                        }
                    }
                }

                $service->save();
                $saved_ids[] = $service->id;
            } else {
                if (!$request->filled('service_assoc_id_' . $lang->code)) {//create
                    $image = $request->{'image_' . $lang->code};
                    $extImage = pathinfo($image, PATHINFO_EXTENSION);
                    $slug = make_slug($request->{'title_' . $lang->code});
                    $service = new Service;
                    $service->language_id = $lang->id;
                    $service->title = $request->{'title_' . $lang->code};
                    $service->type = $request->{'type_' . $lang->code};

                    if ($request->filled('image_' . $lang->code)) {
                        $filename = uniqid() . '.' . $extImage;
                        @copy($image, 'assets/front/img/services/' . $filename);
                        $service->main_image = $filename;
                    }

                    $service->slug = $slug;
                    // if 'theme version'contains service category
                    if (serviceCategory()) {
                        $service->scategory_id = $request->{'category_' . $lang->code};
                    }

                    if ($request->{'service_attribute_status_' . $lang->code} == 1 && $type == 'indoor') {
                        //Add service Attribute
                        if (!empty($request->{'service_attribute_' . $lang->code})) {
                            foreach ($request->{'service_attribute_' . $lang->code} as $key => $val) {
                                $service_attribute = new ServiceAttribute;
                                $service_attribute->service_id = $service->id;
                                $service_attribute->attribute_id = $val['attribute_id'];
                                $service_attribute->text = $val['text'];
                                $service_attribute->language_id = $lang->id;

                                $attr = Attribute::where('id', $val['attribute_id'])->where('language_id', $lang->id)->first();
                                $service_attribute->serial_number = $attr->serial_number;

                                $check_attr = ServiceAttribute::where('service_id', $service->id)->where('attribute_id', $val['attribute_id'])->where('language_id', $lang->id)->first();
                                if (!empty($check_attr)) {
                                    continue;
                                }
                                $service_attribute->save();
                            }
                        }
                    }

                    $service->summary = $request->{'summary_' . $lang->code};
                    $service->details_page_status = $request->{'details_page_status_' . $lang->code};
                    $service->service_attribute_status = $request->{'service_attribute_status_' . $lang->code};
                    $service->meta_description = $request->{'meta_description_' . $lang->code};
                    $service->meta_keywords = $request->{'meta_keywords_' . $lang->code};
                    $service->serial_number = $request->{'serial_number_' . $lang->code};
                    $service->content = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'content_' . $lang->code});
                    $service->save();
                    $saved_ids[] = $service->id;

                    // copy the sliders first
                    $sliders = !empty($request->{'slider_' . $lang->code}) ? explode(',', $request->{'slider_' . $lang->code}) : [];
                    $fileNames = [];
                    foreach ($sliders as $key => $slider) {
                        $extSlider = pathinfo($slider, PATHINFO_EXTENSION);
                        $filename = uniqid() . '.' . $extSlider;
                        @copy($slider, 'assets/front/img/service/sliders/' . $filename);
                        $fileNames[] = $filename;
                    }

                    // store new slider images
                    foreach ($fileNames as $key => $fileName) {
                        $pi = new ServiceImage;
                        $pi->service_id = $service->id;
                        $pi->image = $fileName;
                        $pi->save();
                    }

                } else {
                    $saved_ids[] = $request->{'service_assoc_id_' . $lang->code};
                }
            }
        }
        foreach ($saved_ids as $saved_id) {
            $service = Service::findOrFail($saved_id);
            $service->assoc_id = $assoc_id;
            $service->save();
        }
        Session::flash('success', 'Service updated successfully!');
        return "success";
    }

    public function deleteFromMegaMenu($service)
    {
        // unset service from megamenu for service_category = 1
        $megamenu = Megamenu::where('language_id', $service->language_id)->where('category', 1)->where('type', 'services');
        if ($megamenu->count() > 0) {
            $megamenu = $megamenu->first();
            $menus = json_decode($megamenu->menus, true);
            $catId = $service->scategory->id;
            if (is_array($menus) && array_key_exists("$catId", $menus)) {
                if (in_array($service->id, $menus["$catId"])) {
                    $index = array_search($service->id, $menus["$catId"]);
                    unset($menus["$catId"]["$index"]);
                    $menus["$catId"] = array_values($menus["$catId"]);
                    if (count($menus["$catId"]) == 0) {
                        unset($menus["$catId"]);
                    }
                    $megamenu->menus = json_encode($menus);
                    $megamenu->save();
                }
            }
        }

        // unset service from megamenu for service_category = 0
        $megamenu = Megamenu::where('language_id', $service->language_id)->where('category', 0)->where('type', 'services');
        if ($megamenu->count() > 0) {
            $megamenu = $megamenu->first();
            $menus = json_decode($megamenu->menus, true);
            if (is_array($menus)) {
                if (in_array($service->id, $menus)) {
                    $index = array_search($service->id, $menus);
                    unset($menus["$index"]);
                    $menus = array_values($menus);
                    $megamenu->menus = json_encode($menus);
                    $megamenu->save();
                }
            }
        }
    }

    public function delete(Request $request)
    {
        $_service = Service::findOrFail($request->service_id);
        if($_service->assoc_id > 0) {
            $services = Service::where('assoc_id', $_service->assoc_id)->get();
            foreach ($services as $service) {

                foreach ($service->service_images as $key => $pi) {
                    @unlink('assets/front/img/services/sliders/' . $pi->image);
                    $pi->delete();
                }

                @unlink('assets/front/img/services/' . $service->main_image);
                if (serviceCategory()) {
                    $this->deleteFromMegaMenu($service);
                }

                $service->delete();
            }
        }else {

            foreach ($_service->service_images as $key => $pi) {
                @unlink('assets/front/img/services/sliders/' . $pi->image);
                $pi->delete();
            }

            @unlink('assets/front/img/services/' . $_service->main_image);
            if (serviceCategory()) {
                $this->deleteFromMegaMenu($_service);
            }

            $_service->delete();
        }

        Session::flash('success', 'Service deleted successfully!');
        return back();
    }

    public function images($sertid)
    {
        $images = ServiceImage::select('image')->where('service_id', $sertid)->get();
        $convImages = [];

        foreach ($images as $key => $image) {
            $convImages[] = url("assets/front/img/services/sliders/$image->image");
        }

        return $convImages;
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $_service = Service::findOrFail($id);
            if($_service->assoc_id > 0) {
                $services = Service::where('assoc_id', $_service->assoc_id)->get();
                foreach ($services as $service) {

                    foreach ($service->service_images as $key => $pi) {
                        @unlink('assets/front/img/services/sliders/' . $pi->image);
                        $pi->delete();
                    }

                    @unlink('assets/front/img/services/' . $service->main_image);
                    if (serviceCategory()) {
                        $this->deleteFromMegaMenu($service);
                    }

                    $service->delete();
                }
            }else {

                foreach ($_service->service_images as $key => $pi) {
                    @unlink('assets/front/img/services/sliders/' . $pi->image);
                    $pi->delete();
                }

                @unlink('assets/front/img/services/' . $_service->main_image);

                if (serviceCategory()) {
                    $this->deleteFromMegaMenu($_service);
                }

                $_service->delete();
            }
        }

        Session::flash('success', 'Services deleted successfully!');
        return "success";
    }

    public function getcats($langid)
    {
        $scategories = Scategory::where('language_id', $langid)->get();

        return $scategories;
    }

    public function feature(Request $request)
    {
        $service = Service::find($request->service_id);
        $service->feature = $request->feature;
        $service->save();

        if ($request->feature == 1) {
            Session::flash('success', 'Featured successfully!');
        } else {
            Session::flash('success', 'Unfeatured successfully!');
        }

        return back();
    }

    public function sidebar(Request $request)
    {
        $service = Service::find($request->service_id);
        $service->sidebar = $request->sidebar;
        $service->save();

        if ($request->sidebar == 1) {
            Session::flash('success', 'Enabled successfully!');
        } else {
            Session::flash('success', 'Disabled successfully!');
        }

        return back();
    }

    //Service Attribute
    public function attribute(Request $request) {
        $lang = Language::where('code', $request->language)->first();

        $lang_id = $lang->id;
        $data['attributes'] = Attribute::where('language_id', $lang_id)->where('type', 'service')->orderBy('id', 'DESC')->get();
        return view('admin.service.attribute.index', $data);
    }

    public function attribute_store(Request $request) {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $saved_ids = [];
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        foreach ($languages as $lang) {
            $rules = [
                'name_' . $lang->code => 'required',
                'serial_number_' . $lang->code => 'required|integer',
            ];
            if ($request->filled('icon_' . $lang->code)) {
                $image = $request->{'icon_' . $lang->code};
                $extImage = pathinfo($image, PATHINFO_EXTENSION);
                $rules['icon_' . $lang->code] = [
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
        }
        foreach ($languages as $lang) {
            $attribute = new Attribute;
            $attribute->name = $request->{'name_' . $lang->code};
            $attribute->language_id = $lang->id;
            if ($request->filled('icon_' . $lang->code)) {
                $filename = uniqid() . '.' . $extImage;
                @mkdir('assets/front/img/service_attribute', 775, true);
                @copy($image, 'assets/front/img/service_attribute/' . $filename);
                $attribute->icon = $filename;
            }

            $attribute->serial_number = $request->{'serial_number_' . $lang->code};
            $attribute->type = 'service';
            $attribute->save();
            if($assoc_id == 0){
                $assoc_id = $attribute->id;
            }

            $saved_ids[] = $attribute->id;
        }
        foreach ($saved_ids as $saved_id) {
            $attribute = Attribute::findOrFail($saved_id);
            $attribute->assoc_id = $assoc_id;
            $attribute->save();
        }
        Session::flash('success', 'New attribute added successfully!');
        return "success";
    }

    public function attribute_edit($id) {
        $attribute = Attribute::findOrFail($id);
        $current_lang = Language::where('id', $attribute->language_id)->first();
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $data = array();
        $data['langs'] = $languages;

        foreach ($languages as $lang) {
            if ($current_lang->id == $lang->id) {
                $data['attribute'][$lang->code] = $attribute;
            } else {
                $data['attribute'][$lang->code] = $attribute->assoc_id > 0 ? Attribute::where('language_id', $lang->id)->where('assoc_id', $attribute->assoc_id)->first() : null;
            }
            if ($data['attribute'][$lang->code] == null) {
                $data['attribute'][$lang->code] = new Attribute;
            }
        }
        return view('admin.service.attribute.edit', $data);
    }

    public function attribute_update(Request $request) {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $saved_ids = [];
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        foreach ($languages as $lang) {
            $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
            $rules = [
                'name_' . $lang->code => 'required',
                'serial_number_' . $lang->code => 'required|integer',
            ];
            if ($request->filled('icon_' . $lang->code)) {
                $image = $request->{'icon_' . $lang->code};
                $extImage = pathinfo($image, PATHINFO_EXTENSION);
                $rules['icon_' . $lang->code] = [
                    function ($attribute, $value, $fail) use ($extImage, $allowedExts) {
                        if (!in_array($extImage, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg, svg image is allowed");
                        }
                    }
                ];
            }

            $request->validate($rules);
        }
        foreach ($languages as $lang) {
            $attribute = Attribute::findOrFail($request->{'attribute_id_' . $lang->code});
            if ($request->filled('icon_' . $lang->code)) {
                $image = $request->{'icon_' . $lang->code};
                $extImage = pathinfo($image, PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extImage;
                @unlink('assets/front/img/service_attribute/' . $attribute->icon);
                @copy($image, 'assets/front/img/service_attribute/' . $filename);
                $attribute->icon = $filename;
            }
            $attribute->name = $request->{'name_' . $lang->code};
            $attribute->serial_number = $request->{'serial_number_' . $lang->code};
            $attribute->type = 'service';
            $attribute_id = $request->{'attribute_id_' . $lang->code};
            if ($assoc_id == 0) {
                $assoc_id = $attribute_id;
            }
            $attribute->assoc_id = $assoc_id;
            $attribute->language_id = $lang->id;
            $attribute->save();
        }
        Session::flash('success', 'Attribute updated successfully!');
        return "success";
    }

    public function attribute_delete(Request $request) {

        $_attribute = Attribute::findOrFail($request->attribute_id);
        if($_attribute->assoc_id > 0) {
            $attributes = Attribute::where('assoc_id', $_attribute->assoc_id)->get();
            foreach ($attributes as $attribute) {
                if ($attribute->service_attributes()->count() > 0) {
                    Session::flash('warning', 'First, delete all the attribute under the selected services!');
                    return back();
                }
                $attribute->delete();
            }
        }else {
            if ($_attribute->service_attributes()->count() > 0) {
                Session::flash('warning', 'First, delete all the attribute under the selected services!');
                return back();
            }
            $_attribute->delete();
        }

        Session::flash('success', 'Service attribute deleted successfully!');
        return back();
    }
}
