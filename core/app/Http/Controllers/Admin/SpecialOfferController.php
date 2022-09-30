<?php

namespace App\Http\Controllers\Admin;

use App\SpecialOffer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BasicSetting as BS;
use App\Language;
use Validator;
use Session;

class SpecialOfferController extends Controller
{
    public function index(Request $request)
    {
        $_lang = Language::where('code', $request->language)->first();

        $lang_id = $_lang->id;
        $data['special_offers'] = SpecialOffer::where('language_id', $lang_id)->orderBy('id', 'DESC')->get();

        $data['lang_id'] = $lang_id;
        $data['abs'] = BS::where('language_id', $lang_id)->first();

        return view('admin.home.special_offer.manage', $data);
    }
    public function edit($id)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $special_offer = SpecialOffer::findOrFail($id);
        $current_lang_id = $special_offer->language_id;
        $data = array();
        foreach ($languages as $lang) {
            if ($current_lang_id == $lang->id) {
                $data['special_offer'][$lang->code] = $special_offer;
            } else {
                $data['special_offer'][$lang->code] = $special_offer->assoc_id > 0 ? SpecialOffer::where('language_id', $lang->id)->where('assoc_id', $special_offer->assoc_id)->first() : null;
            }
            if ($data['special_offer'][$lang->code] == null) {
                $data['special_offer'][$lang->code] = new SpecialOffer;
            }
        }
        return view('admin.home.special_offer.edit', $data);
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

            $rules = [
                'image_' . $lang->code => 'required',
                'title_' . $lang->code => ['required','max:255'],
                'serial_number_' . $lang->code => 'required',
                'content_' . $lang->code => 'required',
                'btn_text_' . $lang->code => 'required',
                'btn_url_' . $lang->code => 'required',
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

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $errmsgs = $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }
        }
        foreach ($languages as $lang) {
            $image = $request->{'image_' . $lang->code};
            $extImage = pathinfo($image, PATHINFO_EXTENSION);
            $special_offer = new SpecialOffer;
            $special_offer->language_id = $lang->id;
            $special_offer->title = $request->{'title_' . $lang->code};
            if ($request->filled('image_' . $lang->code)) {
                $filename = uniqid() . '.' . $extImage;
                @copy($image, 'assets/front/img/special_offers/' . $filename);
                $special_offer->image = $filename;
            }

            $special_offer->btn_url = $request->{'btn_url_' . $lang->code};
            $special_offer->btn_text = $request->{'btn_text_' . $lang->code};
            $special_offer->serial_number = $request->{'serial_number_' . $lang->code};
            $special_offer->content = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'content_' . $lang->code});
            $special_offer->save();
            if ($assoc_id == 0) {
                $assoc_id = $special_offer->id;
            }
            if ($assoc_id > 0) {
                $special_offer->assoc_id = $assoc_id;
                $special_offer->save();
            }
        }
        Session::flash('success', 'Special Offer added successfully!');
        return "success";
    }

    public function update(Request $request)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $saved_ids = [];
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        foreach ($languages as $lang) {
            if ($request->filled('special_offer_id_' . $lang->code)) {
                $image = $request->{'image_' . $lang->code};
                $extImage = pathinfo($image, PATHINFO_EXTENSION);

                $rules = [
                    'title_' . $lang->code => ['required','max:255'],
                    'content_' . $lang->code => 'required',
                    'serial_number_' . $lang->code => 'required',
                    'btn_text_' . $lang->code => 'required',
                    'btn_url_' . $lang->code => 'required',
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

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $errmsgs = $validator->getMessageBag()->add('error', 'true');
                    return response()->json($validator->errors());
                }
            }
        }
        foreach ($languages as $lang) {
            if ($request->filled('special_offer_id_' . $lang->code)) {//update
                $image = $request->{'image_' . $lang->code};
                $extImage = pathinfo($image, PATHINFO_EXTENSION);
                $special_offer = SpecialOffer::findOrFail($request->{'special_offer_id_' . $lang->code});
                $special_offerId = $request->{'special_offer_id_' . $lang->code};
                if ($assoc_id == 0) {
                    $assoc_id = $special_offerId;
                }
                $special_offer->title = $request->{'title_' . $lang->code};
                $special_offer->serial_number = $request->{'serial_number_' . $lang->code};
                $special_offer->btn_url = $request->{'btn_url_' . $lang->code};
                $special_offer->btn_text = $request->{'btn_text_' . $lang->code};
                $special_offer->content = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'content_' . $lang->code});

                if ($request->filled('image_' . $lang->code)) {
                    @unlink('assets/front/img/special_offers/' . $special_offer->image);
                    $filename = uniqid() . '.' . $extImage;
                    if(!is_dir('assets/front/img/special_offers/')){
                        mkdir('assets/front/img/special_offers/',0755,true);
                    }
                    @copy($image, 'assets/front/img/special_offers/' . $filename);
                    $special_offer->image = $filename;
                }

                $special_offer->save();
                $saved_ids[] = $special_offer->id;
            }
        }
        foreach ($saved_ids as $saved_id) {
            $special_offer = SpecialOffer::findOrFail($saved_id);
            $special_offer->assoc_id = $assoc_id;
            $special_offer->save();
        }
        Session::flash('success', 'Special Offer updated successfully!');
        return "success";
    }
    public function feature(Request $request)
    {
        $special_offer = SpecialOffer::find($request->special_offer_id);
        $special_offer->feature = $request->feature;
        $special_offer->save();

        if ($request->feature == 1) {
            Session::flash('success', 'Featured successfully!');
        } else {
            Session::flash('success', 'Unfeatured successfully!');
        }

        return back();
    }
    public function delete(Request $request)
    {
        $_special_offer = SpecialOffer::findOrFail($request->special_offer_id);
        if($_special_offer->assoc_id > 0) {
            $special_offers = SpecialOffer::where('assoc_id', $_special_offer->assoc_id)->get();
            foreach ($special_offers as $special_offer) {
                @unlink('assets/front/img/special_offers/' . $special_offer->image);

                $special_offer->delete();
            }
        }else {
            @unlink('assets/front/img/special_offers/' . $_special_offer->image);

            $_special_offer->delete();
        }

        Session::flash('success', 'Special Offer deleted successfully!');
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $_special_offer = SpecialOffer::findOrFail($id);
            if($_special_offer->assoc_id > 0) {
                $special_offers = SpecialOffer::where('assoc_id', $_special_offer->assoc_id)->get();
                foreach ($special_offers as $special_offer) {
                    @unlink('assets/front/img/special_offers/' . $special_offer->image);

                    $special_offer->delete();
                }
            }else {
                @unlink('assets/front/img/special_offers/' . $_special_offer->image);

                $_special_offer->delete();
            }
        }

        Session::flash('success', 'Special Offers deleted successfully!');
        return "success";
    }
}
