<?php

namespace App\Http\Controllers\Admin;

use App\BasicExtended;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Slider;
use App\Language;
use Validator;
use Session;

class SliderController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->first();

        $lang_id = $lang->id;
        $data['sliders'] = Slider::where('language_id', $lang_id)->orderBy('id', 'DESC')->get();

        $data['lang_id'] = $lang_id;
        return view('admin.home.hero.slider.index', $data);
    }

    public function edit($id)
    {
        $data['slider'] = Slider::findOrFail($id);
        return view('admin.home.hero.slider.edit', $data);
    }

    public function edit_modal($id)
    {
        $slider = Slider::findOrFail($id);
        $current_lang = Language::where('id',$slider->language_id)->first();
        $languages = Language::orderBy('id', 'DESC')->get();
        $data= array();
        $data['langs'] = $languages;
        foreach ($languages as $lang) {
            if($current_lang->id == $lang->id){
                $data['slider'][$lang->code] = $slider;
            }else{
                $data['slider'][$lang->code] = $slider->assoc_id>0?Slider::where('language_id',$lang->id)->where('assoc_id',$slider->assoc_id)->first():null;
            }
            if($data['slider'][$lang->code]==null){
                $data['slider'][$lang->code] = new Slider;
                $data['scates'][$lang->code] = Slider::where('language_id',$lang->id)->get();
            }
        }
        return view('admin.home.hero.slider.edit-modal', $data);
    }

    public function store(Request $request)
    {
        $langs = Language::all();
        $assoc_id= 0;
        $saved_ids = [];
        foreach ($langs as $lang) {
            $image = $request->{'image_' . $lang->code};
            $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
            $extImage = pathinfo($image, PATHINFO_EXTENSION);

            $messages = [
                /*'language_id.required' => 'The language field is required'*/
            ];

            $rules = [
                //'language_id' => 'required',
                'image_' . $lang->code => 'required',
                'title_' . $lang->code => 'nullable',
                'title_font_size_' . $lang->code => 'required|integer|digits_between:1,3',
                'text_' . $lang->code => 'nullable',
                'text_font_size_' . $lang->code => 'required|integer|digits_between:1,3',
                'button_text_' . $lang->code => 'nullable',
                'button_text_font_size_' . $lang->code => 'required|integer|digits_between:1,3',
                'button_url_' . $lang->code => 'nullable|max:255',
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

            $be = BasicExtended::first(); //
            $version = $be->theme_version;


            if ($version == 'cleaning') {
                $rules['text_font_size'] = 'nullable';
            }

            if ($version == 'gym' || $version == 'car' || $version == 'cleaning') {
                $rules['bold_text_' . $lang->code] = 'nullable';
                $rules['bold_text_font_size_' . $lang->code] = 'required|integer|digits_between:1,3';
            }

            if ($version == 'cleaning') {
                $rules['bold_text_color_' . $lang->code] = 'required';
            }

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $errmsgs = $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }

            $slider = new Slider;
            $slider->language_id = $lang->id;
            $slider->title = $request->{'title_' . $lang->code};
            $slider->title_font_size = $request->{'title_font_size_'.$lang->code};

            if ($version == 'gym' || $version == 'car' || $version == 'cleaning') {
                $slider->bold_text = $request->{'bold_text_'.$lang->code};
                $slider->bold_text_font_size = $request->{'bold_text_font_size_'.$lang->code};
            }
            if ($version == 'cleaning') {
                $slider->bold_text_color = $request->{'bold_text_color_'.$lang->code};
            }

            if ($version != 'cleaning') {
                $slider->text = $request->{'text_'.$lang->code};
                $slider->text_font_size = $request->{'text_font_size_'.$lang->code};
            }


            $slider->button_text = $request->{'button_text_'.$lang->code};
            $slider->button_text_font_size = $request->{'button_text_font_size_'.$lang->code};
            $slider->button_url = $request->{'button_url_'.$lang->code};

            if ($request->filled('image_'.$lang->code)) {
                $filename = uniqid() . '.' . $extImage;
                @copy($image, 'assets/front/img/sliders/' . $filename);
                $slider->image = $filename;
            }

            $slider->serial_number = $request->{'serial_number_'.$lang->code};
            if($assoc_id>0){
                $slider->assoc_id = $assoc_id;
            }
            $slider->save();
            if($assoc_id==0){
                $assoc_id = $slider->assoc_id = $slider->id;
                $slider->save();
            }
        }
        Session::flash('success', 'Slider added successfully!');
        return "success";
    }

    public function update(Request $request)
    {
        $langs = Language::all();
        $assoc_id= 0;
        $saved_ids = [];
        foreach ($langs as $lang) {
            if($request->filled('slider_id_'.$lang->code)) {
                $image = $request->{'image_'.$lang->code};
                $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
                $extImage = pathinfo($image, PATHINFO_EXTENSION);

                $rules = [
                    'title_'.$lang->code => 'nullable',
                    'title_font_size_'.$lang->code => 'required|integer|digits_between:1,3',
                    'text_'.$lang->code => 'nullable',
                    'text_font_size_'.$lang->code => 'required|integer|digits_between:1,3',
                    'button_text_'.$lang->code => 'nullable',
                    'button_text_font_size_'.$lang->code => 'required|integer|digits_between:1,3',
                    'button_url_'.$lang->code => 'nullable|max:255',
                    'serial_number_'.$lang->code => 'required|integer',
                ];

                if ($request->filled('image_'.$lang->code)) {
                    $rules['image'] = [
                        function ($attribute, $value, $fail) use ($extImage, $allowedExts) {
                            if (!in_array($extImage, $allowedExts)) {
                                return $fail("Only png, jpg, jpeg, svg image is allowed");
                            }
                        }
                    ];
                }

                $be = BasicExtended::first();
                $version = $be->theme_version;

                if ($version == 'cleaning') {
                    $rules['text_font_size_'.$lang->code] = 'nullable';
                }

                if ($version == 'gym' || $version == 'car' || $version == 'cleaning') {
                    $rules['bold_text_'.$lang->code] = 'nullable';
                    $rules['bold_text_font_size_'.$lang->code] = 'required|integer|digits_between:1,3';
                }

                if ($version == 'cleaning') {
                    $rules['bold_text_color_'.$lang->code] = 'required';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $errmsgs = $validator->getMessageBag()->add('error', 'true');
                    return response()->json($validator->errors());
                }

                $slider = Slider::findOrFail($request->{'slider_id_'.$lang->code});
                if($assoc_id==0){
                    $assoc_id = $slider->id;
                }
                $slider->title = $request->{'title_'.$lang->code};
                $slider->title_font_size = $request->{'title_font_size_'.$lang->code};

                if ($request->filled('image_'.$lang->code)) {
                    @unlink('assets/front/img/sliders/' . $slider->image);
                    $filename = uniqid() . '.' . $extImage;
                    if(!is_dir('assets/front/img/sliders/')){
                        mkdir('assets/front/img/sliders/',0755,true);
                    }
                    @copy($image, 'assets/front/img/sliders/' . $filename);
                    $slider->image = $filename;
                }

                if ($version == 'gym' || $version == 'car' || $version == 'cleaning') {
                    $slider->bold_text = $request->{'bold_text_'.$lang->code};
                    $slider->bold_text_font_size = $request->{'bold_text_font_size_'.$lang->code};
                }

                if ($version == 'cleaning') {
                    $slider->bold_text_color = $request->{'bold_text_color_'.$lang->code};
                }

                if ($version != 'cleaning') {
                    $slider->text = $request->{'text_'.$lang->code};
                    $slider->text_font_size = $request->{'text_font_size_'.$lang->code};
                }

                $slider->button_text = $request->{'button_text_'.$lang->code};
                $slider->button_text_font_size = $request->{'button_text_font_size_'.$lang->code};
                $slider->button_url = $request->{'button_url_'.$lang->code};
                $slider->serial_number = $request->{'serial_number_'.$lang->code};
                $slider->save();
                $saved_ids[] = $slider->id;
            }else{
                if (!$request->filled('slider_assoc_id_' . $lang->code)) {
                    $image = $request->{'image_' . $lang->code};
                    $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
                    $extImage = pathinfo($image, PATHINFO_EXTENSION);

                    $messages = [
                        /*'language_id.required' => 'The language field is required'*/
                    ];

                    $rules = [
                        //'language_id' => 'required',
                        'image_' . $lang->code => 'required',
                        'title_' . $lang->code => 'nullable',
                        'title_font_size_' . $lang->code => 'required|integer|digits_between:1,3',
                        'text_' . $lang->code => 'nullable',
                        'text_font_size_' . $lang->code => 'required|integer|digits_between:1,3',
                        'button_text_' . $lang->code => 'nullable',
                        'button_text_font_size_' . $lang->code => 'required|integer|digits_between:1,3',
                        'button_url_' . $lang->code => 'nullable|max:255',
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

                    $be = BasicExtended::first();
                    $version = $be->theme_version;


                    if ($version == 'cleaning') {
                        $rules['text_font_size'] = 'nullable';
                    }

                    if ($version == 'gym' || $version == 'car' || $version == 'cleaning') {
                        $rules['bold_text_' . $lang->code] = 'nullable';
                        $rules['bold_text_font_size_' . $lang->code] = 'required|integer|digits_between:1,3';
                    }

                    if ($version == 'cleaning') {
                        $rules['bold_text_color_' . $lang->code] = 'required';
                    }

                    $validator = Validator::make($request->all(), $rules, $messages);
                    if ($validator->fails()) {
                        $errmsgs = $validator->getMessageBag()->add('error', 'true');
                        return response()->json($validator->errors());
                    }

                    $slider = new Slider;
                    $slider->language_id = $lang->id;
                    $slider->title = $request->{'title_' . $lang->code};
                    $slider->title_font_size = $request->{'title_font_size_'.$lang->code};

                    if ($version == 'gym' || $version == 'car' || $version == 'cleaning') {
                        $slider->bold_text = $request->{'bold_text_'.$lang->code};
                        $slider->bold_text_font_size = $request->{'bold_text_font_size_'.$lang->code};
                    }
                    if ($version == 'cleaning') {
                        $slider->bold_text_color = $request->{'bold_text_color_'.$lang->code};
                    }

                    if ($version != 'cleaning') {
                        $slider->text = $request->{'text_'.$lang->code};
                        $slider->text_font_size = $request->{'text_font_size_'.$lang->code};
                    }


                    $slider->button_text = $request->{'button_text_'.$lang->code};
                    $slider->button_text_font_size = $request->{'button_text_font_size_'.$lang->code};
                    $slider->button_url = $request->{'button_url_'.$lang->code};

                    if ($request->filled('image_'.$lang->code)) {
                        $filename = uniqid() . '.' . $extImage;
                        @copy($image, 'assets/front/img/sliders/' . $filename);
                        $slider->image = $filename;
                    }

                    $slider->serial_number = $request->{'serial_number_'.$lang->code};

                    $slider->save();
                    $saved_ids[] = $slider->id;
                }
                else{
                    $saved_ids[] = $request->{'slider_assoc_id_'.$lang->code};
                }
            }
        }
        foreach ($saved_ids as $saved_id){
            $slider = Slider::findOrFail($saved_id);
            $slider->assoc_id = $assoc_id;
            $slider->save();
        }
        Session::flash('success', 'Slider updated successfully!');
        return "success";
    }

    public function delete(Request $request)
    {

        $slider = Slider::findOrFail($request->slider_id);
        @unlink('assets/front/img/sliders/' . $slider->image);
        $slider->delete();

        Session::flash('success', 'Slider deleted successfully!');
        return back();
    }
}
