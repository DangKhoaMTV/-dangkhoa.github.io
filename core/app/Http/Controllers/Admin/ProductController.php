<?php

namespace App\Http\Controllers\Admin;

use App\Attribute;
use App\ProductAttribute;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\BasicExtended as BE;
use App\BasicExtra;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Language;
use App\Megamenu;
use App\Pcategory;
use App\ProductImage;
use App\Product;
use App\ProductOrder;
use Illuminate\Support\Facades\Validator;
use Session;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $data['langs'] = Language::orderBy('is_default', 'DESC')->get();
        $lang = Language::where('code', $request->language)->first();

        $lang_id = $lang->id;
        $data['products'] = Product::where('language_id', $lang_id)->orderBy('id', 'DESC')->get();
        $data['lang_id'] = $lang_id;
        $data['digitalCount'] = Product::where('type', 'digital')->count();
        $data['physicalCount'] = Product::where('type', 'physical')->count();

        $languages = Language::orderBy('is_default', 'DESC')->get();
        foreach ($languages as $lang) {
            $data['attributes'][$lang->code] = Attribute::where('language_id', $lang->id)->where('type', 'product')->get();
        }

        return view('admin.product.index',$data);
    }


    public function type(Request $request) {
        $data['digitalCount'] = Product::where('type', 'digital')->count();
        $data['physicalCount'] = Product::where('type', 'physical')->count();
        return view('admin.product.type', $data);
    }


    public function create(Request $request)
    {
        $lang = Language::where('code', $request->language)->first();
        $abx = $lang->basic_extra;
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $data = array();
        $data['langs'] = Language::orderBy('is_default', 'DESC')->get();
        foreach ($languages as $lang) {
            $categories[$lang->code] = Pcategory::where('language_id', $lang->id)->where('status', 1)->get();
        }
        return view('admin.product.create',compact('categories','abx'));
    }
    public function create_modal(Request $request)
    {
        $lang = Language::where('code', $request->language)->first();

        $languages = Language::orderBy('is_default', 'DESC')->get();
        $data = array();
        $data['langs'] = $languages;
        $data['abx'] = $lang->basic_extra;
        foreach ($languages as $lang) {
            $data['categories'][$lang->code] = Pcategory::where('language_id', $lang->id)->where('status', 1)->get();
            $data['attributes'][$lang->code] = Attribute::where('language_id', $lang->id)->where('type', 'product')->get();
        }
        return view('admin.product.create-modal',$data);
    }


    public function uploadUpdate(Request $request, $id)
    {
        $img = $request->file('file');
        $allowedExts = array('jpg', 'png', 'jpeg');

        $rules = [
            'file' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    if (!empty($img)) {
                        $ext = $img->getClientOriginalExtension();
                        if (!in_array($ext, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg image is allowed");
                        }
                    }
                },
            ],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json(['errors' => $validator->errors(), 'id' => 'slider']);
        }

        $product = Product::findOrFail($id);
        if ($request->hasFile('file')) {
            $filename = time() . '.' . $img->getClientOriginalExtension();
            $request->file('file')->move('assets/front/img/product/featured/', $filename);

            @unlink('assets/front/img/product/featured/' . $product->feature_image);
            $product->feature_image = $filename;
            $product->save();
        }

        return response()->json(['status' => "success", "image" => "Product image", 'product' => $product]);
    }


    public function getCategory($langid)
    {
        $category = Pcategory::where('language_id', $langid)->get();
        return $category;
    }


    public function store(Request $request)
    {

        $bex = BasicExtra::firstOrFail();
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $saved_ids = [];
        $allowedExts = array('jpg', 'png', 'jpeg');
        foreach ($languages as $lang) {
            $slug = make_slug($request->{'title_' . $lang->code});
            $sliders = !empty($request->{'slider_' . $lang->code}) ? explode(',', $request->{'slider_' . $lang->code}) : [];
            $featredImg = $request->{'featured_image_' . $lang->code};
            $extFeatured = pathinfo($featredImg, PATHINFO_EXTENSION);

            $rules = [];

            $rules = [
                'slider_' . $lang->code => 'required',
                'title_' . $lang->code => [
                    'required',
                    'max:255',
                    function ($attribute, $value, $fail) use ($slug) {
                        $products = Product::all();
                        foreach ($products as $key => $product) {
                            if (strtolower($slug) == strtolower($product->slug)) {
                                $fail('The title field must be unique.');
                            }
                        }
                    }
                ],
                'category_id_' . $lang->code => 'required',
                'featured_image_' . $lang->code => 'required',
                'status_' . $lang->code => 'required',
                'is_home_' . $lang->code => 'required',
            ];

            if ($bex->catalog_mode == 0) {
                $rules['current_price_' . $lang->code] = 'required|numeric';
                $rules['previous_price_' . $lang->code] = 'nullable|numeric';
            }

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

            if ($request->filled('featured_image_' . $lang->code)) {
                $rules['featured_image_' . $lang->code] = [
                    function ($attribute, $value, $fail) use ($extFeatured, $allowedExts) {
                        if (!in_array($extFeatured, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg image is allowed");
                        }
                    }
                ];
            }

            // if product type is 'physical'
            if ($request->type == 'physical') {
                $rules['stock_' . $lang->code] = 'required';
                $rules['sku_' . $lang->code] = 'required|unique:products';
            }

            // if product type is 'digital'
            if ($request->type == 'digital') {
                $rules['file_type_' . $lang->code] = 'required';

                // if 'file upload' is chosen
                if ($request->has('file_type_' . $lang->code) && $request->{'file_type_' . $lang->code} == 'upload') {
                    $allowedExts = array('zip');
                    $rules['download_file_' . $lang->code] = [
                        'required',
                        function ($attribute, $value, $fail) use ($request, $allowedExts, $lang) {
                            $file = $request->file('download_file_' . $lang->code);
                            $ext = $file->getClientOriginalExtension();
                            if (!in_array($ext, $allowedExts)) {
                                return $fail("Only zip file is allowed");
                            }
                        }
                    ];
                } // if 'file donwload link' is chosen
                elseif ($request->has('file_type_' . $lang->code) && $request->{'file_type_' . $lang->code} == 'link') {
                    $rules['download_link_' . $lang->code] = 'required';
                }
            }

            $messages = [
                'category_id.required' => 'Category is required',
            ];

            //Validate Attribute
            if(!empty($request->{'product_attribute_' . $lang->code})) {
                foreach ($request->{'product_attribute_' . $lang->code} as $key => $val) {
                    $rules['product_attribute_' . $lang->code . '.' . $key . '.text'] = 'required';
                    $messages['product_attribute_' . $lang->code . '.' . $key . '.text.required'] = 'Attribute Text Is Required';
                }
            }

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $errmsgs = $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }
        }
        foreach ($languages as $lang) {
            $data = [];
            $data['language_id'] = $lang->id;
            $slug = make_slug($request->{'title_' . $lang->code});
            $sliders = !empty($request->{'slider_' . $lang->code}) ? explode(',', $request->{'slider_' . $lang->code}) : [];

            $data['title'] = $request->{'title_' . $lang->code};
            $data['category_id'] = $request->{'category_id_' . $lang->code};
            $data['slug'] = $slug;
            $data['tags'] = $request->{'tags_' . $lang->code};

            // store featured image
            if ($request->filled('featured_image_' . $lang->code)) {
                $featredImg = $request->{'featured_image_' . $lang->code};
                $filename = uniqid() . '.' . $extFeatured;
                if(!is_dir('assets/front/img/product/featured/')){
                    mkdir('assets/front/img/product/featured/',0755,true);
                }
                @copy($featredImg, 'assets/front/img/product/featured/' . $filename);
                $data['feature_image'] = $filename;
            }

            // if the type is digital && 'upload file' method is selected, then store the downloadable file
            if ($request->type == 'digital' && $request->{'file_type_' . $lang->code} == 'upload') {
                if ($request->hasFile('download_file_' . $lang->code)) {
                    $digitalFile = $request->file('download_file_' . $lang->code);
                    $filename = $slug . '-' . uniqid() . "." . $digitalFile->extension();
                    $directory = 'core/storage/digital_products/';
                    @mkdir($directory, 0775, true);
                    $digitalFile->move($directory, $filename);

                    $data['download_file'] = $filename;
                }
            }

            if ($request->type == 'physical') {
                $data['stock'] = $request->{'stock_' . $lang->code};
                $data['sku'] = $request->{'sku_' . $lang->code};
            }
            $data['summary'] = $request->{'summary_' . $lang->code};
            $data['current_price'] = $request->{'current_price_' . $lang->code};
            $data['previous_price'] = $request->{'previous_price_' . $lang->code};
            $data['status'] = $request->{'status_' . $lang->code};
            $data['is_home'] = $request->{'is_home_' . $lang->code};
            $data['meta_keywords'] = $request->{'meta_keywords_' . $lang->code};
            $data['meta_description'] = $request->{'meta_description_' . $lang->code};
            $data['type'] = $request->type ;
            $data['description'] = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'description_' . $lang->code});

            if ($request->has('file_type_' . $lang->code) && $request->{'file_type_' . $lang->code} == 'link') {
                $data['download_link'] = $request->{'download_link_' . $lang->code};
            }

            $product = Product::create($data);

            if($assoc_id == 0){
                $assoc_id = $product->id;
            }

            if(!empty($request->{'product_attribute_' . $lang->code})) {
                foreach ($request->{'product_attribute_' . $lang->code} as $key => $val) {
                    $product_attribute = new ProductAttribute;
                    $product_attribute->product_id = $product->id;
                    $product_attribute->attribute_id = $val['attribute_id'];
                    $product_attribute->text = $val['text'];
                    $product_attribute->language_id = $lang->id;

                    $attr = Attribute::where('id', $val['attribute_id'])->where('language_id', $lang->id)->first();
                    $product_attribute->serial_number = $attr->serial_number;

                    $check_attr = ProductAttribute::where('product_id', $product->id)->where('attribute_id', $val['attribute_id'])->where('language_id', $lang->id)->first();
                    if (!empty($check_attr)) {
                        continue;
                    }
                    $product_attribute->save();
                }
            }

            $saved_ids[] = $product->id;

            foreach ($sliders as $key => $slider) {
                $extSlider = pathinfo($slider, PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extSlider;
                if(!is_dir('assets/front/img/product/sliders/')){
                    mkdir('assets/front/img/product/sliders/',0755,true);
                }
                @copy($slider, 'assets/front/img/product/sliders/' . $filename);

                $pi = new ProductImage;
                $pi->product_id = $product->id;
                $pi->image = $filename;
                $pi->save();
            }
        }
        foreach ($saved_ids as $saved_id) {
            $product = Product::findOrFail($saved_id);
            $product->assoc_id = $assoc_id;
            $product->save();
        }
        Session::flash('success', 'Product added successfully!');
        return "success";
    }


    public function edit(Request $request, $id)
    {
        $lang = Language::where('code', $request->language)->first();
        $abx = $lang->basic_extra;
        // $categories = $lang->pcategories()->where('status',1)->get();
        $data = Product::findOrFail($id);
        return view('admin.product.edit',compact('categories','data','abx'));
    }

    public function edit_modal(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $lang = Language::where('code', $request->language)->first();
        $current_lang = Language::where('id', $product->language_id)->first();
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $data = array();
        $data['abx'] = $lang->basic_extra;
        $data['langs'] = $languages;
        foreach ($languages as $lang) {
            if ($current_lang->id == $lang->id) {
                $data['product'][$lang->code] = $product;
            } else {
                $data['product'][$lang->code] = $product->assoc_id > 0 ? Product::where('language_id', $lang->id)->where('assoc_id', $product->assoc_id)->first() : null;
            }
            if ($data['product'][$lang->code] == null) {
                $data['product'][$lang->code] = new Product;
                $data['pcates'][$lang->code] = Product::where('language_id', $lang->id)->get();
            }
            $data['categories'][$lang->code] = Pcategory::where('language_id', $lang->id)->get();
            $data['pattributes'][$lang->code] = ProductAttribute::where('language_id', $lang->id)->where('product_id', $data['product'][$lang->code]->id)->orderBy('serial_number', 'ASC')->get();
            $data['attributes'][$lang->code] = Attribute::where('language_id', $lang->id)->where('type', 'product')->get();
        }
        return view('admin.product.edit-modal', $data);
    }

    public function images($portid)
    {
        $images = ProductImage::select('image')->where('product_id', $portid)->get();
        $convImages = [];

        foreach ($images as $key => $image) {
            $convImages[] = url("assets/front/img/product/sliders/$image->image");
        }

        return $convImages;
    }

    public function update(Request $request)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        $assoc_id = 0;
        $saved_ids = [];

        foreach ($languages as $lang) {
            if ($request->filled('product_id_' . $lang->code) || !$request->filled('product_assoc_id_' . $lang->code)) {//Validation
                $product = Product::findOrFail($request->{'product_id_' . $lang->code});
                $productId = $product->id;
                $slug = make_slug($request->{'title_' . $lang->code});
                $sliders = !empty($request->{'slider_' . $lang->code}) ? explode(',', $request->{'slider_' . $lang->code}) : [];
                $featredImg = $request->{'featured_image_' . $lang->code};
                $extFeatured = pathinfo($featredImg, PATHINFO_EXTENSION);
                $allowedExts = array('jpg', 'png', 'jpeg');

                $rules = [
                    'slider_' . $lang->code => 'required',
                    'title_' . $lang->code => [
                        'required',
                        'max:255',

                        function ($attribute, $value, $fail) use ($slug, $productId) {
//                            $products = Product::all();
//                            foreach ($products as $key => $product) {
//                                if ($product->id != $productId && strtolower($slug) == strtolower($product->slug)) {
//                                    $fail('The title field must be unique.');
//                                }
//                            }
                        }
                    ],
                    'category_id_' . $lang->code => 'required',
                    'status_' . $lang->code => 'required'
                ];

                $bex = BasicExtra::firstOrFail();
                if ($bex->catalog_mode == 0) {
                    $rules['current_price_' . $lang->code] = 'required|numeric';
                    $rules['previous_price_' . $lang->code] = 'nullable|numeric';
                }

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

                if ($request->filled('featured_image_' . $lang->code)) {
                    $rules['featured_image_' . $lang->code] = [
                        function ($attribute, $value, $fail) use ($extFeatured, $allowedExts) {
                            if (!in_array($extFeatured, $allowedExts)) {
                                return $fail("Only png, jpg, jpeg image is allowed");
                            }
                        }
                    ];
                }

                // if product type is 'physical'
                if ($product->type == 'physical') {
                    $rules['stock_' . $lang->code] = 'required';
                    $rules['sku_' . $lang->code] = [
                        'required',
                        Rule::unique('products')->ignore($request->{'product_id_' . $lang->code}),
                    ];
                }

                // if product type is 'digital'
                if ($product->type == 'digital') {
                    $rules['file_type_' . $lang->code] = 'required';

                    // if 'file upload' is chosen
                    if ($request->has('file_type_' . $lang->code) && $request->{'file_type_' . $lang->code} == 'upload') {

                        if (empty($product->download_file)) {
                            $rules['download_file'][] = 'required';
                        }
                        $rules['download_file_' . $lang->code][] = function ($attribute, $value, $fail) use ($product, $request, $lang) {
                            $allowedExts = array('zip');
                            if ($request->hasFile('download_file_' . $lang->code)) {
                                $file = $request->file('download_file_' . $lang->code);
                                $ext = $file->getClientOriginalExtension();
                                if (!in_array($ext, $allowedExts)) {
                                    return $fail("Only zip file is allowed");
                                }
                            }
                        };
                    } // if 'file donwload link' is chosen
                    elseif ($request->has('file_type_' . $lang->code) && $request->{'file_type_' . $lang->code} == 'link') {
                        $rules['download_link_' . $lang->code] = 'required';
                    }
                }

                $messages = [
                    'category_id.required' => 'Service is required',
                    'description.min' => 'Description is required'
                ];

                //Validate Attribute
                if(!empty($request->{'product_attribute_' . $lang->code})) {
                    foreach ($request->{'product_attribute_' . $lang->code} as $key => $val) {
                        $rules['product_attribute_' . $lang->code . '.' . $key . '.text'] = 'required';
                        $messages['product_attribute_' . $lang->code . '.' . $key . '.text.required'] = 'Attribute Text Is Required';
                    }
                }


                $validator = Validator::make($request->all(), $rules, $messages);
                if ($validator->fails()) {
                    $errmsgs = $validator->getMessageBag()->add('error', 'true');
                    return response()->json($validator->errors());
                }
            }
        }

        foreach ($languages as $lang) {
            $data = array();
            if ($request->filled('product_id_' . $lang->code)) {//update
                $product = Product::findOrFail($request->{'product_id_' . $lang->code});
                $slug = make_slug($request->{'title_' . $lang->code});
                $data['slug'] = $slug;

                // if the type is digital && 'link' method is selected, then store the downloadable file
                if ($product->type == 'digital' && $request->file_type == 'link') {
                    @unlink('core/storage/digital_products/' . $product->download_file);
                    $data['download_file'] = NULL;
                }

                // if the type is digital && 'upload file' method is selected, then store the downloadable file
                if ($product->type == 'digital' && $request->{'file_type_' . $lang->code} == 'upload') {
                    if ($request->hasFile('download_file_' . $lang->code)) {
                        @unlink('core/storage/digital_products/' . $product->download_file);

                        $digitalFile = $request->file('download_file_' . $lang->code);
                        $filename = $slug . '-' . uniqid() . "." . $digitalFile->extension();
                        $directory = 'core/storage/digital_products/';
                        @mkdir($directory, 0775, true);
                        $digitalFile->move($directory, $filename);

                        $data['download_file'] = $filename;
                        $data['download_link'] = NULL;
                    }
                }
                $data['description'] = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'description_' . $lang->code});


                $featredImg = $request->{'featured_image_' . $lang->code};
                // update featured image
                if ($request->filled('featured_image_' . $lang->code)) {
                    @unlink('assets/front/img/product/featured/' . $product->feature_image);
                    $filename = uniqid() . '.' . $extFeatured;
                    if(!is_dir('assets/front/img/product/featured/')){
                        mkdir('assets/front/img/product/featured/',0755,true);
                    }
                    @copy($featredImg, 'assets/front/img/product/featured/' . $filename);
                    $data['feature_image'] = $filename;
                }

                $data['title'] = $request->{'title_' . $lang->code};
                $data['category_id'] = $request->{'category_id_' . $lang->code};
                $data['tags'] = $request->{'tags_' . $lang->code};

                $data['stock'] = $request->{'stock_' . $lang->code};
                $data['sku'] = $request->{'sku_' . $lang->code};

                $data['summary'] = $request->{'summary_' . $lang->code};
                $data['current_price'] = $request->{'current_price_' . $lang->code};
                $data['previous_price'] = $request->{'previous_price_' . $lang->code};
                $data['status'] = $request->{'status_' . $lang->code};
                $data['meta_keywords'] = $request->{'meta_keywords_' . $lang->code};
                $data['meta_description'] = $request->{'meta_description_' . $lang->code};

                if ($assoc_id == 0) {
                    $assoc_id = $product->id;
                }
                $data['assoc_id'] = $assoc_id;
                $product->fill($data)->save();


                // copy the sliders first
                $sliders = !empty($request->{'slider_' . $lang->code}) ? explode(',', $request->{'slider_' . $lang->code}) : [];
                $fileNames = [];
                foreach ($sliders as $key => $slider) {
                    $extSlider = pathinfo($slider, PATHINFO_EXTENSION);
                    $filename = uniqid() . '.' . $extSlider;
                    if(!is_dir('assets/front/img/product/sliders/')){
                        mkdir('assets/front/img/product/sliders/',0755,true);
                    }
                    @copy($slider, 'assets/front/img/product/sliders/' . $filename);
                    $fileNames[] = $filename;
                }

                // delete & unlink previous slider images
                $pis = ProductImage::where('product_id', $product->id)->get();
                foreach ($pis as $key => $pi) {
                    @unlink('assets/front/img/product/sliders/' . $pi->image);
                    $pi->delete();
                }

                // delete product attribute
                $pas = ProductAttribute::where('product_id', $product->id)->get();
                foreach ($pas as $key => $pa) {
                    $pa->delete();
                }

                //Add new Product Attribute
//                dd($request->{'product_attribute_' . $lang->code});

                if(!empty($request->{'product_attribute_' . $lang->code})) {
                    foreach ($request->{'product_attribute_' . $lang->code} as $key => $val) {
                        $product_attribute = new ProductAttribute;
                        $product_attribute->product_id = $product->id;
                        $product_attribute->attribute_id = $val['attribute_id'];
                        $product_attribute->text = $val['text'];
                        $product_attribute->language_id = $lang->id;

                        $attr = Attribute::where('id', $val['attribute_id'])->where('language_id', $lang->id)->first();
                        $product_attribute->serial_number = $attr->serial_number;

                        $check_attr = ProductAttribute::where('product_id', $product->id)->where('attribute_id', $val['attribute_id'])->where('language_id', $lang->id)->first();

                        if (!empty($check_attr)) {
                            continue;
                        }

                        $product_attribute->save();
                    }
                }

                // store new slider images
                foreach ($fileNames as $key => $fileName) {
                    $pi = new ProductImage;
                    $pi->product_id = $product->id;
                    $pi->image = $fileName;
                    $pi->save();
                }
            }else {
                if (!$request->filled('product_assoc_id_' . $lang->code)) {//create
                    $product = new Product;
                    $data = array();
                    $slug = make_slug($request->{'title_' . $lang->code});
                    $data['slug'] = $slug;

                    // if the type is digital && 'link' method is selected, then store the downloadable file
                    if ($request->{'file_type_' . $lang->code} == 'link') {
                        $data['download_file'] = NULL;
                    }

                    // if the type is digital && 'upload file' method is selected, then store the downloadable file
                    if ($request->type == 'digital' && $request->{'file_type_' . $lang->code} == 'upload') {
                        if ($request->hasFile('download_file_' . $lang->code)) {
                            $digitalFile = $request->file('download_file_' . $lang->code);
                            $filename = $slug . '-' . uniqid() . "." . $digitalFile->extension();
                            
                           $directory = 'core/storage/digital_products/';
                           @mkdir($directory, 0775, true);
                            $digitalFile->move($directory, $filename);

                            $data['download_file'] = $filename;
                            $data['download_link'] = NULL;
                        }
                    }
                    $data['description'] = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'description_' . $lang->code});


                    $featredImg = $request->{'featured_image_' . $lang->code};
                    // update featured image
                    if ($request->filled('featured_image_' . $lang->code)) {
                        $filename = uniqid() . '.' . $extFeatured;
                        if(!is_dir('assets/front/img/product/featured/')){
                            mkdir('assets/front/img/product/featured/',0755,true);
                        }
                        @copy($featredImg, 'assets/front/img/product/featured/' . $filename);
                        $data['feature_image'] = $filename;
                    }

                    $data['title'] = $request->{'title_' . $lang->code};
                    $data['category_id'] = $request->{'category_id_' . $lang->code};
                    $data['tags'] = $request->{'tags_' . $lang->code};

                    $data['stock'] = $request->{'stock_' . $lang->code};
                    $data['sku'] = $request->{'sku_' . $lang->code};

                    $data['summary'] = $request->{'summary_' . $lang->code};
                    $data['current_price'] = $request->{'current_price_' . $lang->code};
                    $data['previous_price'] = $request->{'previous_price_' . $lang->code};
                    $data['status'] = $request->{'status_' . $lang->code};
                    $data['meta_keywords'] = $request->{'meta_keywords_' . $lang->code};
                    $data['meta_description'] = $request->{'meta_description_' . $lang->code};

                    $product->fill($data)->save();
                    $saved_ids[] = $product->id;

                    // copy the sliders first
                    $sliders = !empty($request->{'slider_' . $lang->code}) ? explode(',', $request->{'slider_' . $lang->code}) : [];
                    $fileNames = [];
                    foreach ($sliders as $key => $slider) {
                        $extSlider = pathinfo($slider, PATHINFO_EXTENSION);
                        $filename = uniqid() . '.' . $extSlider;
                        if(!is_dir('assets/front/img/product/sliders/')){
                            mkdir('assets/front/img/product/sliders/',0755,true);
                        }
                        @copy($slider, 'assets/front/img/product/sliders/' . $filename);
                        $fileNames[] = $filename;
                    }

                    // store new slider images
                    foreach ($fileNames as $key => $fileName) {
                        $pi = new ProductImage;
                        $pi->product_id = $product->id;
                        $pi->image = $fileName;
                        $pi->save();
                    }

                    //Add new Product Attribute
                    if(!empty($request->{'product_attribute_' . $lang->code})) {
                        foreach ($request->{'product_attribute_' . $lang->code} as $key => $val) {
                            $product_attribute = new ProductAttribute;
                            $product_attribute->product_id = $product->id;
                            $product_attribute->attribute_id = $val['attribute_id'];
                            $product_attribute->text = $val['text'];
                            $product_attribute->language_id = $lang->id;

                            $attr = Attribute::where('id', $val['attribute_id'])->where('language_id', $lang->id)->first();
                            $product_attribute->serial_number = $attr->serial_number;

                            $check_attr = ProductAttribute::where('product_id', $product->id)->where('attribute_id', $val['attribute_id'])->where('language_id', $lang->id)->first();
                            if (!empty($check_attr)) {
                                continue;
                            }
                            $product_attribute->save();
                        }
                    }
                } else {
                    $saved_ids[] = $request->{'product_assoc_id_' . $lang->code};
                }
            }
        }
        foreach ($saved_ids as $saved_id) {
            $product = Product::findOrFail($saved_id);
            $product->assoc_id = $assoc_id;
            $product->save();
        }

        Session::flash('success', 'Product updated successfully!');
        return "success";
    }


    public function deleteFromMegaMenu($product) {
        // unset service from megamenu for service_category = 1
        $megamenu = Megamenu::where('language_id', $product->language_id)->where('category', 1)->where('type', 'products');
        if ($megamenu->count() > 0) {
            $megamenu = $megamenu->first();
            $menus = json_decode($megamenu->menus, true);
            $catId = $product->category->id;
            if (is_array($menus) && array_key_exists("$catId", $menus)) {
                if (in_array($product->id, $menus["$catId"])) {
                    $index = array_search($product->id, $menus["$catId"]);
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
    }


    public function feature(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $product->is_feature = $request->is_feature;
        $product->save();

        if ($request->is_feature == 1) {
            Session::flash('success', 'Product featured successfully!');
        } else {
            Session::flash('success', 'Product unfeatured successfully!');
        }
        return back();
    }


    public function delete(Request $request)
    {
        $_product = Product::findOrFail($request->product_id);
        if($_product->assoc_id > 0) {
            $products = Product::where('assoc_id', $_product->assoc_id)->get();
            foreach ($products as $product) {
                foreach ($product->product_images as $key => $pi) {
                    @unlink('assets/front/img/product/sliders/' . $pi->image);
                    $pi->delete();
                }

                foreach ($product->product_attributes as $key => $pa) {
                    $pa->delete();
                }

                @unlink('assets/front/img/product/featured/' . $product->feature_image);
                @unlink('core/storage/digital_products/' . $product->download_file);

                $this->deleteFromMegaMenu($product);

                $product->delete();
            }
        }else {
            foreach ($_product->product_images as $key => $pi) {
                @unlink('assets/front/img/product/sliders/' . $pi->image);
                $pi->delete();
            }
            foreach ($_product->product_attributes as $key => $pa) {
                $pa->delete();
            }
            @unlink('assets/front/img/product/featured/' . $_product->feature_image);
            @unlink('core/storage/digital_products/' . $_product->download_file);

            $this->deleteFromMegaMenu($_product);

            $_product->delete();
        }

        Session::flash('success', 'Product deleted successfully!');
        return back();
    }


    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $_product = Product::findOrFail($id);
            if($_product->assoc_id > 0) {
                $products = Product::where('assoc_id', $_product->assoc_id)->get();
                foreach ($products as $product) {
                    foreach ($product->product_images as $key => $pi) {
                        @unlink('assets/front/img/product/sliders/' . $pi->image);
                        $pi->delete();
                    }

                    foreach ($product->product_attributes as $key => $pa) {
                        $pa->delete();
                    }
                }
            }else {
                foreach ($_product->product_images as $key => $pi) {
                    @unlink('assets/front/img/product/sliders/' . $pi->image);
                    $pi->delete();
                }

                foreach ($_product->product_attributes as $key => $pa) {
                    $pa->delete();
                }
            }
        }

        foreach ($ids as $id) {
            $_product = Product::findOrFail($id);
            if($_product->assoc_id > 0) {
                $products = Product::where('assoc_id', $_product->assoc_id)->get();
                foreach ($products as $product) {
                    @unlink('assets/front/img/product/featured/' . $product->feature_image);

                    $this->deleteFromMegaMenu($product);

                    $product->delete();
                }
            }else {
                @unlink('assets/front/img/product/featured/' . $_product->feature_image);

                $this->deleteFromMegaMenu($_product);

                $_product->delete();
            }
        }

        Session::flash('success', 'Product deleted successfully!');
        return "success";
    }


    public function populerTag(Request $request)
    {
        /*$_lang = Language::where('code', $request->language)->first();
        $lang_id = $lang->id;*/
        $languages = Language::orderBy('is_default', 'DESC')->get();
        foreach ($languages as $lang) {
            $data[$lang->code] = BE::where('language_id', $lang->id)->first();
        }
        return view('admin.product.tag.index',compact('data'));
    }

    public function populerTagupdate(Request $request)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        foreach ($languages as $lang) {
            $rules = [
                'popular_tags_' . $lang->code => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $errmsgs = $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }

        }
        foreach ($languages as $lang) {
            //$lang = Language::where('code', $request->language_id)->first();
            $be = BE::where('language_id', $lang->id)->first();
            $be->popular_tags = $request->{'popular_tags_' . $lang->code};
            $be->save();
        }
        Session::flash('success', 'Populer tags update successfully!');
        return "success";
    }

    public function paymentStatus(Request $request) {
        $order = ProductOrder::find($request->order_id);
        $order->payment_status = $request->payment_status;
        $order->save();

        // $user = User::findOrFail($po->user_id);
        $be = BE::first();
        $sub = 'Payment Status Updated';

        $to = $order->billing_email;
        $fname = $order->billing_fname;

         // Send Mail to Buyer
         $mail = new PHPMailer(true);
         if ($be->is_smtp == 1) {
             try {
                 $mail->isSMTP();
                 $mail->Host       = $be->smtp_host;
                 $mail->SMTPAuth   = true;
                 $mail->Username   = $be->smtp_username;
                 $mail->Password   = $be->smtp_password;
                 $mail->SMTPSecure = $be->encryption;
                 $mail->Port       = $be->smtp_port;

                 //Recipients
                 $mail->setFrom($be->from_mail, $be->from_name);
                 $mail->addAddress($to, $fname);

                 // Content
                 $mail->isHTML(true);
                 $mail->Subject = $sub;
                 $mail->Body    = 'Hello <strong>' . $fname . '</strong>,<br/>Your payment status is changed to '.$request->payment_status.'.<br/>Thank you.';
                 $mail->send();
             } catch (Exception $e) {
                 // die($e->getMessage());
             }
         } else {
             try {

                 //Recipients
                 $mail->setFrom($be->from_mail, $be->from_name);
                 $mail->addAddress($to, $fname);


                 // Content
                 $mail->isHTML(true);
                 $mail->Subject = $sub ;
                 $mail->Body    = 'Hello <strong>' . $fname . '</strong>,<br/>Your payment status is changed to '.$request->payment_status.'.<br/>Thank you.';

                 $mail->send();
             } catch (Exception $e) {
                 // die($e->getMessage());
             }
         }

        Session::flash('success', 'Payment Status updated!');
        return back();
    }

    public function settings() {
        $data['abex'] = BasicExtra::first();
        return view('admin.product.settings', $data);
    }

    public function updateSettings(Request $request) {
        $bexs = BasicExtra::all();
        foreach($bexs as $bex) {
            $bex->product_rating_system = $request->product_rating_system;
            $bex->product_guest_checkout = $request->product_guest_checkout;
            $bex->is_shop = $request->is_shop;
            $bex->catalog_mode = $request->catalog_mode;
            $bex->tax = $request->tax ? $request->tax : 0.00;
            $videoLink = $request->product_page_video_url;
            if (strpos($videoLink, "&") != false) {
                $videoLink = substr($videoLink, 0, strpos($videoLink, "&"));
            }
            $bex->product_page_video_url = $videoLink;
            $bex->product_page_type = $request->product_page_type;
            if ($request->filled('product_page_image')) {
                $image = $request->product_page_image;
                @unlink('assets/front/img/' . $bex->product_page_image);
                $extImage = pathinfo($image, PATHINFO_EXTENSION);
                $filename = uniqid() .'.'. $extImage;
                if(!is_dir('assets/front/img/')){
                    mkdir('assets/front/img/',0755,true);
                }
                @copy($image, 'assets/front/img/' . $filename);
                $bex->product_page_image = $filename;
            }
           if ($request->filled('product_page_video_bg')) {
                $image_bg = $request->product_page_video_bg;
                @unlink('assets/front/img/' . $bex->product_page_video_bg);
                if(!is_dir('assets/front/img/')){
                    mkdir('assets/front/img/',0755,true);
                }
                $extImageBg = pathinfo($image_bg, PATHINFO_EXTENSION);
                $filenameBg = uniqid() .'.'. $extImageBg;
                @copy($image_bg, 'assets/front/img/' . $filenameBg);
                $bex->product_page_video_bg = $filenameBg;
            }
            $bex->save();
        }

        $request->session()->flash('success', 'Settings updated successfully!');
        return back();
    }

    public function attribute(Request $request) {
        $lang = Language::where('code', $request->language)->first();

        $lang_id = $lang->id;
        $data['attributes'] = Attribute::where('language_id', $lang_id)->where('type', 'product')->orderBy('id', 'DESC')->get();
        return view('admin.product.attribute.index', $data);
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
            if ($request->{'icon_type_' . $lang->code}=='image' && $request->filled('icon_' . $lang->code)) {
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
            $attribute->icon_type = $request->{'icon_type_' . $lang->code};
            $attribute->name = $request->{'name_' . $lang->code};
            $attribute->language_id = $lang->id;
            if ($request->{'icon_type_' . $lang->code}=='image' && $request->filled('icon_' . $lang->code)) {
                $filename = uniqid() . '.' . $extImage;
                @mkdir('assets/front/img/product_attribute', 775, true);
                @copy($image, 'assets/front/img/product_attribute/' . $filename);
                $attribute->icon = $filename;
            }else{
                $attribute->icon = $request->{'icon_' . $lang->code};
            }

            $attribute->serial_number = $request->{'serial_number_' . $lang->code};
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
        return view('admin.product.attribute.edit', $data);
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
            if ($request->{'icon_type_' . $lang->code}=='image' && $request->filled('icon_' . $lang->code)) {
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
            if ($request->{'icon_type_' . $lang->code}=='image' && $request->filled('icon_' . $lang->code)) {
                $image = $request->{'icon_' . $lang->code};
                $extImage = pathinfo($image, PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extImage;
                @unlink('assets/front/img/product_attribute/' . $attribute->icon);
                if(!is_dir('assets/front/img/product_attribute/')){
                    mkdir('assets/front/img/product_attribute/',0755,true);
                }
                @copy($image, 'assets/front/img/product_attribute/' . $filename);
                $attribute->icon = $filename;
            }else{
                $attribute->icon = $request->{'icon_' . $lang->code};
            }
            $attribute->icon_type = $request->{'icon_type_' . $lang->code};
            $attribute->name = $request->{'name_' . $lang->code};
            $attribute->serial_number = $request->{'serial_number_' . $lang->code};

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
                if ($attribute->product_attributes()->count() > 0) {
                    Session::flash('warning', 'First, delete all the attribute under the selected products!');
                    return back();
                }
                $attribute->delete();
            }
        }else {
            if ($_attribute->product_attributes()->count() > 0) {
                Session::flash('warning', 'First, delete all the attribute under the selected products!');
                return back();
            }
            $_attribute->delete();
        }

        Session::flash('success', 'Product attribute deleted successfully!');
        return back();
    }
}
