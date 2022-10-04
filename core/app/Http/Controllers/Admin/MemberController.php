<?php

namespace App\Http\Controllers\Admin;

use App\BasicExtended;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Member;
use App\Language;
use App\BasicSetting as BS;
use Validator;
use Session;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $_lang = Language::where('code', $request->language)->firstOrFail();
        $data['lang_id'] = $_lang->id;
        $languages = Language::orderBy('is_default', 'DESC')->get();
        foreach ($languages as $lang){
            $data['abs'][$lang->code] = $lang->basic_setting;
        }

        $data['members'] = Member::where('language_id', $data['lang_id'])->get();

        return view('admin.home.member.index', $data);
    }

    public function create()
    {
        return view('admin.home.member.create');
    }

    public function edit($id)
    {
        $data['member'] = Member::findOrFail($id);
        return view('admin.home.member.edit', $data);
    }
    public function edit_modal($id)
    {
        $member = Member::findOrFail($id);
        $current_lang = Language::where('id',$member->language_id)->first();
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $data= array();
        //$data['langs'] = $languages;
        foreach ($languages as $lang) {
            if($current_lang->id == $lang->id){
                $data['member'][$lang->code] = $member;
            }else{
                $data['member'][$lang->code] = $member->assoc_id>0?Member::where('language_id',$lang->id)->where('assoc_id',$member->assoc_id)->first():null;
            }
            if($data['member'][$lang->code]==null){
                $data['member'][$lang->code] = new Member;
                $data['scates'][$lang->code] = Member::where('language_id',$lang->id)->get();
            }
        }
        return view('admin.home.member.edit-modal', $data);
    }

    public function store(Request $request)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        $assoc_id = 0;
        foreach ($languages as $lang) {
            $image = $request->{'image_'.$lang->code};
            $extImage = pathinfo($image, PATHINFO_EXTENSION);

            $messages = [
                /*'language_id.required' => 'The language field is required'*/
            ];

            $rules = [
                //'language_id' => 'required',
                'image_' . $lang->code => 'required',
                'name_' . $lang->code => 'required|max:50',
                'rank_' . $lang->code => 'required|max:50',
                'facebook_' . $lang->code => 'nullable|max:50',
                'twitter_' . $lang->code => 'nullable|max:50',
                'linkedin_' . $lang->code => 'nullable|max:50',
                'instagram_' . $lang->code => 'nullable|max:50',
            ];
            if ($request->filled('image_'.$lang->code)) {
                $rules['image_'.$lang->code] = [
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
            $image = $request->{'image_'.$lang->code};
            $extImage = pathinfo($image, PATHINFO_EXTENSION);

            $member = new Member;
            $member->language_id = $lang->id;
            $member->image = $request->{'member_image_'.$lang->code};
            $member->name = $request->{'name_'.$lang->code};
            $member->rank = $request->{'rank_'.$lang->code};
            $member->facebook = $request->{'facebook_'.$lang->code};
            $member->twitter = $request->{'twitter_'.$lang->code};
            $member->linkedin = $request->{'linkedin_'.$lang->code};
            $member->instagram = $request->{'instagram_'.$lang->code};

            if ($request->filled('image_'.$lang->code)) {
                $filename = uniqid() . '.' . $extImage;
                @copy($image, 'assets/front/img/members/' . $filename);
                $member->image = $filename;
            }

            $member->save();
            if($assoc_id==0){
                $assoc_id = $member->id;
            }
            if($assoc_id>0){
                $member->assoc_id = $assoc_id;
                $member->save();
            }
        }
        Session::flash('success', 'Member added successfully!');
        return "success";
    }

    public function update(Request $request)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        $assoc_id = 0;
        $saved_ids = [];
        foreach ($languages as $lang) {
            $image = $request->{'image_'.$lang->code};
            $extImage = pathinfo($image, PATHINFO_EXTENSION);

            if($request->filled('member_id_'.$lang->code)||!$request->filled('member_assoc_id_'.$lang->code)) {
                $rules = [
                    'name_'.$lang->code => 'required|max:50',
                    'rank_'.$lang->code => 'required|max:50',
                    'facebook_'.$lang->code => 'nullable|max:50',
                    'twitter_'.$lang->code => 'nullable|max:50',
                    'linkedin_'.$lang->code => 'nullable|max:50',
                    'instagram_'.$lang->code => 'nullable|max:50',
                ];
            }else{
                $rules = [];
            }

            if ($request->filled('image_'.$lang->code)) {
                $rules['image_'.$lang->code] = [
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
            $image = $request->{'image_'.$lang->code};
            $extImage = pathinfo($image, PATHINFO_EXTENSION);
            if($request->filled('member_id_'.$lang->code)) {
                $member = Member::findOrFail($request->{'member_id_'.$lang->code});
                if($assoc_id==0){
                    $assoc_id = $member->id;
                }
                $member->name = $request->{'name_'.$lang->code};
                $member->rank = $request->{'rank_'.$lang->code};
                $member->facebook = $request->{'facebook_'.$lang->code};
                $member->twitter = $request->{'twitter_'.$lang->code};
                $member->linkedin = $request->{'linkedin_'.$lang->code};
                $member->instagram = $request->{'instagram_'.$lang->code};

                if ($request->filled('image_' . $lang->code)) {
                    @unlink('assets/front/img/members/' . $member->image);
                    $filename = uniqid() . '.' . $extImage;
                    @copy($image, 'assets/front/img/members/' . $filename);
                    $member->image = $filename;
                }

                $member->save();
                $saved_ids[] = $member->id;
            }else{
                if(!$request->filled('member_assoc_id_'.$lang->code)){
                    $member = new Member;
                    $member->language_id = $lang->id;
                    $member->image = $request->{'member_image_'.$lang->code};
                    $member->name = $request->{'name_'.$lang->code};
                    $member->rank = $request->{'rank_'.$lang->code};
                    $member->facebook = $request->{'facebook_'.$lang->code};
                    $member->twitter = $request->{'twitter_'.$lang->code};
                    $member->linkedin = $request->{'linkedin_'.$lang->code};
                    $member->instagram = $request->{'instagram_'.$lang->code};

                    if ($request->filled('image_'.$lang->code)) {
                        $filename = uniqid() . '.' . $extImage;
                        @copy($image, 'assets/front/img/members/' . $filename);
                        $member->image = $filename;
                    }

                    $member->save();
                    $saved_ids[] = $member->id;
                }else{
                    $saved_ids[] = $request->{'member_assoc_id_'.$lang->code};
                }
            }
        }
        foreach ($saved_ids as $saved_id){
            $member = Member::findOrFail($saved_id);
            $member->assoc_id = $assoc_id;
            $member->save();
        }
        Session::flash('success', 'Member updated successfully!');
        if($request->ajax())
            return "success";
        return back();
    }

    public function textupdate(Request $request, $langid)
    {
        $be = BasicExtended::firstOrFail();
        $version = $be->theme_version;

        $languages = Language::orderBy('is_default', 'DESC')->get();
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        foreach ($languages as $lang) {
            $rules = [
                'team_section_title_' . $lang->code => 'required|max:25',
                'team_section_subtitle_' . $lang->code => 'required|max:80',
            ];

            if (($version == 'default' || $version == 'dark') && $request->filled('background_' . $lang->code)) {
                $background = $request->{'background_' . $lang->code};
                $extBackground = pathinfo($background, PATHINFO_EXTENSION);
                $rules['background_'.$lang->code] = [
                    function ($attribute, $value, $fail) use ($extBackground, $allowedExts) {
                        if (!in_array($extBackground, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg, svg image is allowed");
                        }
                    }
                ];
            }

            $request->validate($rules);
        }
        foreach ($languages as $lang) {
            $bs = BS::where('language_id', $lang->id)->firstOrFail();
            $bs->team_section_title = $request->{'team_section_title_' . $lang->code};
            $bs->team_section_subtitle = $request->{'team_section_subtitle_' . $lang->code};

            if (($version == 'default' || $version == 'dark') && $request->filled('background_' . $lang->code)) {
                $background = $request->{'background_' . $lang->code};
                $extBackground = pathinfo($background, PATHINFO_EXTENSION);
                @unlink('assets/front/img/' . $bs->team_bg);
                $filename = uniqid() . '.' . $extBackground;
                @copy($background, 'assets/front/img/' . $filename);
                $bs->team_bg = $filename;
            }

            $bs->save();
        }
        Session::flash('success', 'Text & Background updated successfully!');
        if($request->ajax())
            return "success";
        return back();
    }

    public function delete(Request $request)
    {

        $member = Member::findOrFail($request->member_id);
        @unlink('assets/front/img/members/' . $member->image);
        $member->delete();

        Session::flash('success', 'Member deleted successfully!');
        if($request->ajax())
            return "success";
        return back();
    }

    public function feature(Request $request)
    {
        $_member = Member::find($request->member_id);
        $members = Member::where('assoc_id',$_member->assoc_id)->get();
        foreach ($members as $member){
            $member->feature = $request->feature;
            $member->save();
        }

        if ($request->feature == 1) {
            Session::flash('success', 'Featured successfully!');
        } else {
            Session::flash('success', 'Unfeatured successfully!');
        }
        if($request->ajax())
            return "success";
        return back();
    }
}
