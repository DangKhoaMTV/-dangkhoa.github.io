<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Language;
use App\Megamenu;
use App\Portfolio;
use App\PortfolioImage;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    public function __construct(){
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $data['langs'] = $languages;
        foreach ($languages as $lang) {
            $data['scates'][$lang->code] = Portfolio::where('language_id', $lang->id)->get();
            $data['services'][$lang->code] = Service::where('language_id', $lang->id)->get();
        }
        View::share('data', $data);
    }
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->first();

        $lang_id = $lang->id;
        $data['portfolios'] = Portfolio::where('language_id', $lang_id)->orderBy('id', 'DESC')->get();

        $data['lang_id'] = $lang_id;

        return view('admin.portfolio.index', $data);
    }

    public function create()
    {
        $data['tportfolios'] = Portfolio::where('language_id', 0)->get();
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $data = array();
        $data['langs'] = $languages;
        foreach ($languages as $lang) {
            $data['portfolio'][$lang->code] = new Portfolio;
            $data['scates'][$lang->code] = Portfolio::where('language_id', $lang->id)->get();
            $data['services'][$lang->code] = Service::where('language_id', $lang->id)->get();
        }
        return view('admin.portfolio.create', $data);
    }

    public function create_modal()
    {
        $data['services'] = Service::all();
        $data['tportfolios'] = Portfolio::where('language_id', 0)->get();
        return view('admin.portfolio.create', $data);
    }

    public function edit($id)
    {
        $data['portfolio'] = Portfolio::findOrFail($id);
        $data['services'] = Service::where('language_id', $data['portfolio']->language_id)->get();
        return view('admin.portfolio.edit', $data);
    }

    public function edit_modal($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        $current_lang = Language::where('id', $portfolio->language_id)->first();
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $data = array();
        $data['langs'] = $languages;
        foreach ($languages as $lang) {
            if ($current_lang->id == $lang->id) {
                $data['portfolio'][$lang->code] = $portfolio;
            } else {
                $data['portfolio'][$lang->code] = $portfolio->assoc_id > 0 ? Portfolio::where('language_id', $lang->id)->where('assoc_id', $portfolio->assoc_id)->first() : null;
            }
            if ($data['portfolio'][$lang->code] == null) {
                $data['portfolio'][$lang->code] = new Portfolio;
                $data['scates'][$lang->code] = Portfolio::where('language_id', $lang->id)->get();
            }
            $data['services'][$lang->code] = Service::where('language_id', $lang->id)->get();
        }
        //var_dump($data['services']);die();
        return view('admin.portfolio.edit-modal', $data);
    }

    public function sliderrmv(Request $request)
    {
        $pi = PortfolioImage::findOrFail($request->fileid);
        @unlink('assets/front/img/portfolios/sliders/' . $pi->image);
        $pi->delete();
        return $pi->id;
    }


    public function store(Request $request)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $saved_ids = [];
        foreach ($languages as $lang) {
            $slug = make_slug($request->{'title_' . $lang->code});

            $sliders = !empty($request->{'slider_' . $lang->code}) ? explode(',', $request->{'slider_' . $lang->code}) : [];
            $image = $request->{'image_' . $lang->code};
            $extImage = pathinfo($image, PATHINFO_EXTENSION);
            $allowedExts = array('jpg', 'png', 'jpeg', 'svg');

            $rules = [
                'slider_' . $lang->code => 'required',
                'title_' . $lang->code => [
                    'required',
                    'max:255',
                    function ($attribute, $value, $fail) use ($slug) {
                        $portfolios = Portfolio::all();
                        foreach ($portfolios as $key => $portfolio) {
                            if (strtolower($slug) == strtolower($portfolio->slug)) {
                                $fail('The title field must be unique.');
                            }
                        }
                    }
                ],
                'client_name_' . $lang->code => 'required|max:255',
                'service_id_' . $lang->code => 'required',
                'tags_' . $lang->code => 'required',
                'content_' . $lang->code => 'required',
                'image_' . $lang->code => 'required',
                'status_' . $lang->code => 'required',
                'serial_number_' . $lang->code => 'required|integer',
            ];

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

            if ($request->filled('image_' . $lang->code)) {
                $rules['image_' . $lang->code] = [
                    function ($attribute, $value, $fail) use ($extImage, $allowedExts) {
                        if (!in_array($extImage, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg, svg image is allowed");
                        }
                    }
                ];
            }

            $messages = [
                'service_id.required' => 'service is required'
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                $errmsgs = $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }
        }

        foreach ($languages as $lang){
            $in = $request->all();
            $data = array();
            $data['content'] = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'content_' . $lang->code});
            $slug = make_slug($request->{'title_' . $lang->code});
            $data['assoc_id'] = $assoc_id;
            $data['slug'] = $slug;
            $data['language_id'] = $lang->id;
            $data['title'] = $in['title_' . $lang->code];
            $data['start_date'] = $in['start_date_' . $lang->code];
            $data['submission_date'] = $in['submission_date_' . $lang->code];
            $data['client_name'] = $in['client_name_' . $lang->code];
            $data['tags'] = $in['tags_' . $lang->code];
            $data['service_id'] = $in['service_id_' . $lang->code];
            $data['status'] = $in['status_' . $lang->code];
            $data['serial_number'] = $in['serial_number_' . $lang->code];
            $data['meta_keywords'] = $in['meta_keywords_' . $lang->code];
            $data['meta_description'] = $in['meta_description_' . $lang->code];
            $data['website_link'] = $in['website_link_' . $lang->code];

            if ($request->filled('image_' . $lang->code)) {
                $image = $request->{'image_' . $lang->code};
                $extImage = pathinfo($image, PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extImage;
                @copy($image, 'assets/front/img/portfolios/featured/' . $filename);
                $data['featured_image'] = $filename;
            }

            $portfolio = Portfolio::create($data);
            if($assoc_id == 0){
                $assoc_id = $portfolio->id;
            }

            $saved_ids[] = $portfolio->id;
            $sliders = !empty($request->{'slider_' . $lang->code}) ? explode(',', $request->{'slider_' . $lang->code}) : [];
            foreach ($sliders as $key => $slider) {
                $extSlider = pathinfo($slider, PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $extSlider;
                @copy($slider, 'assets/front/img/portfolios/sliders/' . $filename);

                $pi = new PortfolioImage;
                $pi->portfolio_id = $portfolio->id;
                $pi->image = $filename;
                $pi->save();
            }
        }

        foreach ($saved_ids as $saved_id) {
            $portfolio = Portfolio::findOrFail($saved_id);
            $portfolio->assoc_id = $assoc_id;
            $portfolio->save();
        }

        Session::flash('success', 'Portfolio added successfully!');
        return "success";
    }

    public function images($portid)
    {
        $images = PortfolioImage::select('image')->where('portfolio_id', $portid)->get();
        $convImages = [];

        foreach ($images as $key => $image) {
            $convImages[] = url("assets/front/img/portfolios/sliders/$image->image");
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
            if ($request->filled('portfolio_id_' . $lang->code) || !$request->filled('portfolio_assoc_id_' . $lang->code)) {//Validation
                $slug = make_slug($request->{'title_' . $lang->code});
                $portfolioId = $request->{'portfolio_id_' . $lang->code};

                $sliders = !empty($request->{'slider_' . $lang->code}) ? explode(',', $request->{'slider_' . $lang->code}) : [];
                $image = $request->{'image_' . $lang->code};

                $extImage = pathinfo($image, PATHINFO_EXTENSION);
                $rules = [
                    'slider_' . $lang->code => 'required',
                    'title_' . $lang->code => [
                        'required',
                        'max:255',
                        function ($attribute, $value, $fail) use ($slug, $portfolioId, $lang) {
                            $portfolios = Portfolio::whereRaw('LOWER(slug) = ? AND id <> ? AND language_id = ?', [strtolower($slug), $portfolioId, $lang->id])->count();

                            if ($portfolios > 0) {
                                $fail('The title field must be unique.');
                            }
                        }
                    ],
                    'client_name_' . $lang->code => 'required|max:255',
                    'service_id_' . $lang->code => 'required',
                    'tags_' . $lang->code => 'required',
                    'content_' . $lang->code => 'required',
                    'status_' . $lang->code => 'required',
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

                $messages = [
                    'service_id.required' => 'Service is required'
                ];

                $validator = Validator::make($request->all(), $rules, $messages);

                if ($validator->fails()) {
                    $errmsgs = $validator->getMessageBag()->add('error', 'true');
                    return response()->json($validator->errors());
                }
            }
        }

        foreach ($languages as $lang) {
            $in = $request->all();
            $data = array();
            if ($request->filled('portfolio_id_' . $lang->code)) {//update

                $portfolio = Portfolio::findOrFail($request->{'portfolio_id_' . $lang->code});
                $data['content'] = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'content_' . $lang->code});
                $data['slug'] = $slug;

                $portfolioId = $request->{'portfolio_id_' . $lang->code};
                if ($assoc_id == 0) {
                    $assoc_id = $portfolioId;
                }

                $data['assoc_id'] = $assoc_id;
                $data['language_id'] = $lang->id;
                $data['title'] = $in['title_' . $lang->code];
                $data['start_date'] = $in['start_date_' . $lang->code];
                $data['submission_date'] = $in['submission_date_' . $lang->code];
                $data['client_name'] = $in['client_name_' . $lang->code];
                $data['tags'] = $in['tags_' . $lang->code];
                $data['service_id'] = $in['service_id_' . $lang->code];
                $data['status'] = $in['status_' . $lang->code];
                $data['serial_number'] = $in['serial_number_' . $lang->code];
                $data['meta_keywords'] = $in['meta_keywords_' . $lang->code];
                $data['meta_description'] = $in['meta_description_' . $lang->code];
                $data['website_link'] = $in['website_link_' . $lang->code];

                if ($request->filled('image_' . $lang->code)) {

                    $image = $request->{'image_' . $lang->code};
                    $extImage = pathinfo($image, PATHINFO_EXTENSION);

                    @unlink('assets/front/img/portfolios/featured/' . $portfolio->featured_image);
                    $filename = uniqid() . '.' . $extImage;
                    @copy($image, 'assets/front/img/portfolios/featured/' . $filename);
                    $data['featured_image'] = $filename;
                }
                // copy the sliders first
                $fileNames = [];
                $sliders = !empty($request->{'slider_' . $lang->code}) ? explode(',', $request->{'slider_' . $lang->code}) : [];
                foreach ($sliders as $key => $slider) {
                    $extSlider = pathinfo($slider, PATHINFO_EXTENSION);
                    $filename = uniqid() . '.' . $extSlider;
                    @copy($slider, 'assets/front/img/portfolios/sliders/' . $filename);
                    $fileNames[] = $filename;
                }

                // delete & unlink previous slider images
                $pis = PortfolioImage::where('portfolio_id', $portfolio->id)->get();
                foreach ($pis as $key => $pi) {
                    @unlink('assets/front/img/portfolios/sliders/' . $pi->image);
                    $pi->delete();
                }

                // store new slider images
                foreach ($fileNames as $key => $fileName) {
                    $pi = new PortfolioImage;
                    $pi->portfolio_id = $portfolio->id;
                    $pi->image = $fileName;
                    $pi->save();
                }

                $portfolio->fill($data)->save();
            } else {
                if (!$request->filled('portfolio_assoc_id_' . $lang->code)) {//create
                    $image = $request->{'image_' . $lang->code};
                    $extImage = pathinfo($image, PATHINFO_EXTENSION);
                    $slug = make_slug($request->{'title_' . $lang->code});
                    $portfolio = new Portfolio;
                    $portfolio->language_id = $lang->id;
                    $portfolio->title = $request->{'title_' . $lang->code};

                    if ($request->filled('image_' . $lang->code)) {
                        $filename = uniqid() . '.' . $extImage;
                        @copy($image, 'assets/front/img/portfolios/featured/' . $filename);
                        $portfolio->featured_image = $filename;
                    }

                    $portfolio->slug = $slug;
                    $portfolio->start_date = $request->{'start_date_' . $lang->code};
                    $portfolio->submission_date = $request->{'submission_date_' . $lang->code};
                    $portfolio->client_name = $request->{'client_name_' . $lang->code};
                    $portfolio->tags = $request->{'tags_' . $lang->code};
                    $portfolio->service_id = $request->{'service_id_' . $lang->code};
                    $portfolio->status = $request->{'status_' . $lang->code};
                    $portfolio->serial_number = $request->{'serial_number_' . $lang->code};
                    $portfolio->meta_description = $request->{'meta_description_' . $lang->code};
                    $portfolio->meta_keywords = $request->{'meta_keywords_' . $lang->code};
                    $portfolio->website_link = $request->{'website_link_' . $lang->code};
                    $portfolio->content = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'content_' . $lang->code});


                    $portfolio->save();
                    $saved_ids[] = $portfolio->id;

                    foreach ($sliders as $key => $slider) {
                        $extSlider = pathinfo($slider, PATHINFO_EXTENSION);
                        $filename = uniqid() . '.' . $extSlider;
                        @copy($slider, 'assets/front/img/portfolios/sliders/' . $filename);

                        $pi = new PortfolioImage;
                        $pi->portfolio_id = $portfolio->id;
                        $pi->image = $filename;
                        $pi->save();
                    }

                } else {
                    $saved_ids[] = $request->{'portfolio_assoc_id_' . $lang->code};
                }
            }
        }

        foreach ($saved_ids as $saved_id) {
            $portfolio = Portfolio::findOrFail($saved_id);
            $portfolio->assoc_id = $assoc_id;
            $portfolio->save();
        }

        Session::flash('success', 'Portfolio updated successfully!');
        return "success";
    }

    public function delete(Request $request)
    {
        $_portfolio = Portfolio::findOrFail($request->portfolio_id);
        if($_portfolio->assoc_id > 0){
            $portfolios = Portfolio::where('assoc_id', $_portfolio->assoc_id)->get();
            foreach ($portfolios as $portfolio){
                foreach ($portfolio->portfolio_images as $key => $pi) {
                    @unlink('assets/front/img/portfolios/sliders/' . $pi->image);
                    $pi->delete();
                }
                @unlink('assets/front/img/portfolios/featured/' . $portfolio->featured_image);

                $this->deleteFromMegaMenu($portfolio);

                $portfolio->delete();
            }
        }else {
            foreach ($_portfolio->portfolio_images as $key => $pi) {
                @unlink('assets/front/img/portfolios/sliders/' . $pi->image);
                $pi->delete();
            }
            @unlink('assets/front/img/portfolios/featured/' . $_portfolio->featured_image);

            $this->deleteFromMegaMenu($_portfolio);

            $_portfolio->delete();

        }

        Session::flash('success', 'Portfolio deleted successfully!');
        return back();
    }

    public function deleteFromMegaMenu($portfolio)
    {
        // unset portfolio from megamenu for service_category = 1
        $megamenu = Megamenu::where('language_id', $portfolio->language_id)->where('category', 1)->where('type', 'portfolios');
        if ($megamenu->count() > 0) {
            $megamenu = $megamenu->first();
            $menus = json_decode($megamenu->menus, true);
            if (!empty($portfolio->service) && !empty($portfolio->service->scategory)) {
                $catId = $portfolio->service->scategory->id;
                if (is_array($menus) && array_key_exists("$catId", $menus)) {
                    if (in_array($portfolio->id, $menus["$catId"])) {
                        $index = array_search($portfolio->id, $menus["$catId"]);
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

        // unset portfolio from megamenu for service_category = 0
        $megamenu = Megamenu::where('language_id', $portfolio->language_id)->where('category', 0)->where('type', 'portfolios');
        if ($megamenu->count() > 0) {
            $megamenu = $megamenu->first();
            $menus = json_decode($megamenu->menus, true);
            if (is_array($menus)) {
                if (in_array($portfolio->id, $menus)) {
                    $index = array_search($portfolio->id, $menus);
                    unset($menus["$index"]);
                    $menus = array_values($menus);
                    $megamenu->menus = json_encode($menus);
                    $megamenu->save();
                }
            }
        }
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $_portfolio = Portfolio::findOrFail($id);
            if($_portfolio->assoc_id > 0){
                $portfolios = Portfolio::where('assoc_id', $_portfolio->assoc_id)->get();
                foreach ($portfolios as $portfolio) {
                    foreach ($portfolio->portfolio_images as $key => $pi) {
                        @unlink('assets/front/img/portfolios/sliders/' . $pi->image);
                        $pi->delete();
                    }

                    @unlink('assets/front/img/portfolios/featured/' . $portfolio->featured_image);

                    $this->deleteFromMegaMenu($portfolio);
                    $portfolio->delete();
                }
            }else {
                foreach ($_portfolio->portfolio_images as $key => $pi) {
                    @unlink('assets/front/img/portfolios/sliders/' . $pi->image);
                    $pi->delete();
                }

                @unlink('assets/front/img/portfolios/featured/' . $_portfolio->featured_image);

                $this->deleteFromMegaMenu($_portfolio);
                $_portfolio->delete();
            }

        }

        Session::flash('success', 'Portfolios deleted successfully!');
        return "success";
    }

    public function getservices($langid)
    {
        $services = Service::where('language_id', $langid)->get();

        return $services;
    }

    public function feature(Request $request)
    {
        $portfolio = Portfolio::find($request->portfolio_id);
        $portfolio->feature = $request->feature;
        $portfolio->save();

        if ($request->feature == 1) {
            Session::flash('success', 'Featured successfully!');
        } else {
            Session::flash('success', 'Unfeatured successfully!');
        }

        return back();
    }
}
