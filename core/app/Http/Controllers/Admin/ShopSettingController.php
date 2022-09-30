<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BasicExtended as BE;
use App\Language;
use App\ShippingCharge;
use Validator;
use Session;

class ShopSettingController extends Controller
{
    public function index(Request $request)
    {

        $lang = Language::where('code', $request->language)->first();
        $lang_id = $lang->id;
        $data['shippings'] = ShippingCharge::where('language_id', $lang_id)->orderBy('id', 'DESC')->paginate(10);
        $data['lang_id'] = $lang_id;
        return view('admin.product.shop_setting.index', $data);
    }


    public function store(Request $request)
    {
        $languages = Language::all();
        $assoc_id = 0;
        $saved_ids = [];
        foreach ($languages as $lang) {
            $rules = [
                'title_' . $lang->code => 'required',
                'text_' . $lang->code => 'required|max:255',
                'charge_' . $lang->code => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errmsgs = $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }
        }

        foreach ($languages as $lang) {
            $shipping = new ShippingCharge;
            $shipping->language_id = $lang->id;
            $shipping->title = $request->{'title_' . $lang->code};
            $shipping->text = $request->{'text_' . $lang->code};
            $shipping->charge = $request->{'charge_' . $lang->code};
            $shipping->save();
            if ($assoc_id == 0) {
                $assoc_id = $shipping->id;
            }

            $saved_ids[] = $shipping->id;
        }

        foreach ($saved_ids as $saved_id) {
            $shipping = ShippingCharge::findOrFail($saved_id);
            $shipping->assoc_id = $assoc_id;
            $shipping->save();
        }

        Session::flash('success', 'Shipping Charge added successfully!');
        return "success";
    }

    public function edit($id)
    {
        $shipping = ShippingCharge::findOrFail($id);
        $current_lang = Language::where('id', $shipping->language_id)->first();
        $languages = Language::all();
        foreach ($languages as $lang) {
            if ($current_lang->id == $lang->id) {
                $data['shipping'][$lang->code] = $shipping;
            } else {
                $data['shipping'][$lang->code] = $shipping->assoc_id > 0 ? ShippingCharge::where('language_id', $lang->id)->where('assoc_id', $shipping->assoc_id)->first() : null;
            }
            if ($data['shipping'][$lang->code] == null) {
                $data['shipping'][$lang->code] = new ShippingCharge();
                $data['scates'][$lang->code] = ShippingCharge::where('language_id', $lang->id)->get();
            }
        }

        return view('admin.product.shop_setting.edit', $data);
    }

    public function update(Request $request)
    {
        $languages = Language::all();
        $assoc_id = 0;
        $saved_ids = [];
        foreach ($languages as $lang) {
            if ($request->filled('shipping_id_' . $lang->code) || !$request->filled('shipping_assoc_id_' . $lang->code)) {//Validation
                $rules = [
                    'title_' . $lang->code => 'required',
                    'text_' . $lang->code => 'required|max:255',
                    'charge_' . $lang->code => 'required',
                ];

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $errmsgs = $validator->getMessageBag()->add('error', 'true');
                    return response()->json($validator->errors());
                }
            }
        }
        foreach ($languages as $lang) {
            if ($request->filled('shipping_id_' . $lang->code)) {//update
                $shipping = ShippingCharge::findOrFail($request->{'shipping_id_' . $lang->code});
                $shipping->language_id = $lang->id;
                $shipping->title = $request->{'title_' . $lang->code};
                $shipping->text = $request->{'text_' . $lang->code};
                $shipping->charge = $request->{'charge_' . $lang->code};

                if ($assoc_id == 0) {
                    $assoc_id = $shipping->id;
                }

                $shipping->assoc_id = $assoc_id;
                $shipping->save();
            }else {
                if (!$request->filled('shipping_assoc_id_' . $lang->code)) {//create
                    $shipping = new ShippingCharge;
                    $shipping->language_id = $lang->id;
                    $shipping->title = $request->{'title_' . $lang->code};
                    $shipping->text = $request->{'text_' . $lang->code};
                    $shipping->charge = $request->{'charge_' . $lang->code};

                    $shipping->save();
                    $saved_ids[] = $shipping->id;
                }else {
                    $saved_ids[] = $request->{'shipping_assoc_id_' . $lang->code};
                }
            }
        }
        foreach ($saved_ids as $saved_id) {
            $shipping = ShippingCharge::findOrFail($saved_id);
            $shipping->assoc_id = $assoc_id;
            $shipping->save();
        }
        Session::flash('success', 'Shipping charge update successfully!');
        return "success";

    }


    public function delete(Request $request)
    {
        $_shipping = ShippingCharge::findOrFail($request->shipping_id);
        if($_shipping->assoc_id > 0) {
            $shippings = ShippingCharge::where('assoc_id', $_shipping->assoc_id)->get();
            foreach ($shippings as $shipping) {
                $shipping->delete();
            }
        }else {
            $_shipping->delete();
        }
        Session::flash('success', 'Shipping charge delete successfully!');
        return back();
    }


}
