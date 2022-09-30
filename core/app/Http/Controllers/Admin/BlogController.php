<?php

namespace App\Http\Controllers\Admin;

use App\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Bcategory;
use App\Language;
use App\Blog;
use App\Megamenu;
use Validator;
use Session;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $_lang = Language::where('code', $request->language)->first();
        $languages = Language::all();
        foreach ($languages as $lang) {
            $data['bcats'][$lang->code] = Bcategory::where('language_id', $lang->id)->where('status', 1)->get();
        }
        $lang_id = $_lang->id;
        $data['lang_id'] = $lang_id;
        $data['blogs'] = Blog::where('language_id', $lang_id)->orderBy('id', 'DESC')->get();

        return view('admin.blog.blog.index', $data);
    }

    public function edit($id)
    {
        $data['blog'] = Blog::findOrFail($id);
        $data['bcats'] = Bcategory::where('language_id', $data['blog']->language_id)->where('status', 1)->get();
        return view('admin.blog.blog.edit', $data);
    }

    public function edit_modal($id)
    {
        $blog = Blog::findOrFail($id);
        $current_lang = Language::where('id', $blog->language_id)->first();
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $data = array();
        $data['langs'] = $languages;
        foreach ($languages as $lang) {
            if ($current_lang->id == $lang->id) {
                $data['blog'][$lang->code] = $blog;
            } else {
                $data['blog'][$lang->code] = $blog->assoc_id > 0 ? Blog::where('language_id', $lang->id)->where('assoc_id', $blog->assoc_id)->first() : null;
            }
            if ($data['blog'][$lang->code] == null) {
                $data['blog'][$lang->code] = new Blog;
                $data['scates'][$lang->code] = Blog::where('language_id', $lang->id)->get();
            }
            $data['bcats'][$lang->code] = Bcategory::where('language_id', $lang->id)->where('status', 1)->get();
        }
       // var_dump($data['bcats']['en']);die();
        return view('admin.blog.blog.edit-modal', $data);
    }

    public function store(Request $request)
    {
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');

        $languages = Language::all();
        $assoc_id = 0;
        $saved_ids = [];
        foreach ($languages as $lang) {

            $image = $request->{'image_' . $lang->code};
            $extImage = pathinfo($image, PATHINFO_EXTENSION);

            $messages = [
                'language_id.required' => 'The language field is required'
            ];

            $slug = make_slug($request->{'title_' . $lang->code});

            $rules = [
                'image_' . $lang->code => 'required',
                'title_' . $lang->code => [
                    'required',
                    'max:255',
                    function ($attribute, $value, $fail) use ($slug) {
                        $blogs = Blog::all();
                        foreach ($blogs as $key => $blog) {
                            if (strtolower($slug) == strtolower($blog->slug)) {
                                $fail('The title field must be unique.');
                            }
                        }
                    }
                ],
                'category_' . $lang->code => 'required',
                'content_' . $lang->code => 'required',
                'serial_number_' . $lang->code => 'required|integer',
            ];
            if ($request->filled('image_' . $lang->code)) {
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
        }
        foreach ($languages as $lang) {
            $blog = new Blog;
            $blog->language_id = $lang->id;;
            $blog->title = $request->{'title_' . $lang->code};
            $slug = make_slug($request->{'title_' . $lang->code});
            $blog->slug = $slug;
            $blog->assoc_id = $assoc_id;
            $blog->bcategory_id = $request->{'category_' . $lang->code};
            $blog->content = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'content_' . $lang->code});
            $blog->meta_keywords = $request->{'meta_keywords_' . $lang->code};
            $blog->meta_description = $request->{'meta_description_' . $lang->code};
            $blog->serial_number = $request->{'serial_number_' . $lang->code};

            if ($request->filled('image_' . $lang->code)) {
                $filename = uniqid() . '.' . $extImage;
                @copy($image, 'assets/front/img/blogs/' . $filename);
                $blog->main_image = $filename;
            }

            $blog->save();

            if($assoc_id == 0){
                $assoc_id = $blog->id;
            }

            $saved_ids[] = $blog->id;

        }
        foreach ($saved_ids as $saved_id) {
            $blog = Blog::findOrFail($saved_id);
            $blog->assoc_id = $assoc_id;
            $blog->save();
        }
        Session::flash('success', 'Blog added successfully!');
        return "success";
    }

    public function update(Request $request)
    {
        $languages = Language::all();
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        $assoc_id = 0;
        $saved_ids = [];

        foreach ($languages as $lang) {
            if ($request->filled('blog_id' . $lang->code) || !$request->filled('blog_assoc_id_' . $lang->code)) {//Validation
                $slug = make_slug($request->{'title_' . $lang->code});
                $blogId = $request->{'blog_id_' . $lang->code};


                $image = $request->{'image_' . $lang->code};
                $extImage = pathinfo($image, PATHINFO_EXTENSION);

                $rules = [
                    'title_' . $lang->code => [
                        'required',
                        'max:255',
                        function ($attribute, $value, $fail) use ($slug, $blogId) {
                            $blogs = Blog::all();
                            foreach ($blogs as $key => $blog) {
                                if ($blog->id != $blogId && strtolower($slug) == strtolower($blog->slug)) {
                                    $fail('The title field must be unique.');
                                }
                            }
                        }
                    ],
                    'category_'  . $lang->code => 'required',
                    'content_'  . $lang->code => 'required',
                    'serial_number_'  . $lang->code => 'required|integer',
                ];

                if ($request->filled('image_'  . $lang->code)) {
                    $rules['image_'  . $lang->code] = [
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
        }

        foreach ($languages as $lang) {
            if ($request->filled('blog_id_' . $lang->code)) {//update
                $blog = Blog::findOrFail($request->{'blog_id_' . $lang->code});
                $blog->title = $request->{'title_' . $lang->code};
                $slug = make_slug($request->{'title_' . $lang->code});
                $blog->slug = $slug;
                $blog->language_id = $lang->id;
                $blog->bcategory_id = $request->{'category_' . $lang->code};
                $blog->content = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'content_'} . $lang->code);
                $blog->meta_keywords = $request->{'meta_keywords_' . $lang->code};
                $blog->meta_description = $request->{'meta_description_' . $lang->code};
                $blog->serial_number = $request->{'serial_number_' . $lang->code};

                $blogId = $request->{'blog_id_' . $lang->code};
                if ($assoc_id == 0) {
                    $assoc_id = $blogId;
                }

                $blog->assoc_id = $assoc_id;

                $image = $request->{'image_' . $lang->code};
                $extImage = pathinfo($image, PATHINFO_EXTENSION);

                if ($request->filled('image_' . $lang->code)) {
                    @unlink('assets/front/img/blogs/' . $blog->main_image);
                    $filename = uniqid() . '.' . $extImage;
                    @copy($image, 'assets/front/img/blogs/' . $filename);
                    $blog->main_image = $filename;
                }

                $blog->save();
            }else {
                if (!$request->filled('blog_assoc_id_' . $lang->code)) {//create
                    $blog = new Blog;
                    $blog->title = $request->{'title_' . $lang->code};
                    $slug = make_slug($request->{'title_' . $lang->code});
                    $blog->slug = $slug;
                    $blog->language_id = $lang->id;
                    $blog->bcategory_id = $request->{'category_' . $lang->code};
                    $blog->content = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'content_'} . $lang->code);
                    $blog->meta_keywords = $request->{'meta_keywords_' . $lang->code};
                    $blog->meta_description = $request->{'meta_description_' . $lang->code};
                    $blog->serial_number = $request->{'serial_number_' . $lang->code};

                    $image = $request->{'image_' . $lang->code};
                    $extImage = pathinfo($image, PATHINFO_EXTENSION);

                    if ($request->filled('image_' . $lang->code)) {
                        $filename = uniqid() . '.' . $extImage;
                        @copy($image, 'assets/front/img/blogs/' . $filename);
                        $blog->main_image = $filename;
                    }

                    $blog->save();
                    $saved_ids[] = $blog->id;
                }else {
                    $saved_ids[] = $request->{'blog_assoc_id_' . $lang->code};
                }
            }
        }
        foreach ($saved_ids as $saved_id) {
            $blog = Blog::findOrFail($saved_id);
            $blog->assoc_id = $assoc_id;
            $blog->save();
        }
        Session::flash('success', 'Blog updated successfully!');
        return "success";
    }

    public function deleteFromMegaMenu($blog) {
        // unset service from megamenu for service_category = 1
        $megamenu = Megamenu::where('language_id', $blog->language_id)->where('category', 1)->where('type', 'blogs');
        if ($megamenu->count() > 0) {
            $megamenu = $megamenu->first();
            $menus = json_decode($megamenu->menus, true);
            $catId = $blog->bcategory->id;
            if (is_array($menus) && array_key_exists("$catId", $menus)) {
                if (in_array($blog->id, $menus["$catId"])) {
                    $index = array_search($blog->id, $menus["$catId"]);
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

    public function delete(Request $request)
    {

        $_blog = Blog::findOrFail($request->blog_id);
        if($_blog->assoc_id > 0){
            $blogs = Blog::where('assoc_id', $_blog->assoc_id)->get();
            foreach ($blogs as $blog){
                @unlink('assets/front/img/blogs/' . $blog->main_image);

                $this->deleteFromMegaMenu($blog);

                $blog->delete();
            }
        }else {
            @unlink('assets/front/img/blogs/' . $_blog->main_image);

            $this->deleteFromMegaMenu($_blog);

            $_blog->delete();
        }


        Session::flash('success', 'Blog deleted successfully!');
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $_blog = Blog::findOrFail($id);
            if($_blog->assoc_id > 0){
                $blogs = Blog::where('assoc_id', $_blog->assoc_id)->get();
                foreach ($blogs as $blog){
                    @unlink('assets/front/img/blogs/' . $blog->main_image);

                    $this->deleteFromMegaMenu($blog);

                    $blog->delete();
                }
            }else {
                @unlink('assets/front/img/blogs/' . $_blog->main_image);

                $this->deleteFromMegaMenu($_blog);

                $_blog->delete();
            }
        }

        Session::flash('success', 'Blogs deleted successfully!');
        return "success";
    }

    public function getcats($langid)
    {
        $bcategories = Bcategory::where('language_id', $langid)->get();

        return $bcategories;
    }

    public function sidebar(Request $request)
    {
        $blog = Blog::find($request->blog_id);
        $blog->sidebar = $request->sidebar;
        $blog->save();

        if ($request->sidebar == 1) {
            Session::flash('success', 'Enabled successfully!');
        } else {
            Session::flash('success', 'Disabled successfully!');
        }

        return back();
    }
}
