<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CalendarEvent;
use App\Language;
use Validator;
use Session;

class CalendarController extends Controller
{
    public function index(Request $request) {
        $lang = Language::where('code', $request->language)->first();

        $lang_id = $lang->id;
        $data['events'] = CalendarEvent::where('language_id', $lang_id)->orderBy('id', 'DESC')->get();

        $data['lang_id'] = $lang_id;

        return view('admin.calendar.index', $data);
    }

    public function edit($calendarID)
    {
        $calendar = CalendarEvent::findOrFail($calendarID);
        $languages = Language::all();
        $current_lang = Language::where('id', $calendar->language_id)->first();
        $data['langs'] = $languages;
        foreach ($languages as $lang) {
            if ($current_lang->id == $lang->id) {
                $data['calendar'][$lang->code] = $calendar;
            } else {
                $data['calendar'][$lang->code] = $calendar->assoc_id > 0 ? CalendarEvent::where('language_id', $lang->id)->where('assoc_id', $calendar->assoc_id)->first() : null;
            }
            if ($data['calendar'][$lang->code] == null) {
                $data['calendar'][$lang->code] = new CalendarEvent();
                $data['ccates'][$lang->code] = CalendarEvent::where('language_id', $lang->id)->get();
            }
        }

        return view('admin.calendar.edit', $data);
    }

    public function store(Request $request)
    {
        $languages = Language::all();
        $assoc_id = 0;
        $saved_ids = [];
        foreach ($languages as $lang) {
            $rules = [
                'title_'. $lang->code => 'required|max:255',
                'start_date_'. $lang->code => 'required',
                'end_date_'. $lang->code => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errmsgs = $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }
        }
        foreach ($languages as $lang) {
            $calendar = new CalendarEvent;
            $calendar->language_id = $lang->id;
            $calendar->title = $request->{'title_' . $lang->code};
            $calendar->start_date = $request->{'start_date_' . $lang->code};
            $calendar->end_date = $request->{'end_date_' . $lang->code};

            $calendar->save();

            if($assoc_id == 0){
                $assoc_id = $calendar->id;
            }

            $saved_ids[] = $calendar->id;
        }
        foreach ($saved_ids as $saved_id) {
            $calendar = CalendarEvent::findOrFail($saved_id);
            $calendar->assoc_id = $assoc_id;
            $calendar->save();
        }
        Session::flash('success', 'Event added to calendar successfully!');
        return "success";
    }

    public function update(Request $request)
    {
        $languages = Language::all();
        $assoc_id = 0;
        $saved_ids = [];
        foreach ($languages as $lang) {
            if ($request->filled('event_id_' . $lang->code) || !$request->filled('calendar_assoc_id_' . $lang->code)) {//Validation
                $messages = [
                    'start_date.required' => 'Event period is required',
                    'end_date.required' => 'Event period is required',
                ];

                $rules = [
                    'title_' . $lang->code => 'required|max:255',
                    'start_date_' . $lang->code => 'required',
                    'end_date_' . $lang->code => 'required',
                ];

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    $errmsgs = $validator->getMessageBag()->add('error', 'true');
                    return response()->json($validator->errors());
                }
            }
        }
        foreach ($languages as $lang) {
            if ($request->filled('event_id_' . $lang->code)) {//update
                $calendar = CalendarEvent::findOrFail($request->{'event_id_' . $lang->code});
                $calendar->title = $request->{'title_' . $lang->code};
                $calendar->start_date = $request->{'start_date_' . $lang->code};
                $calendar->end_date = $request->{'end_date_' . $lang->code};
                if($assoc_id == 0){
                    $assoc_id = $calendar->id;
                }
                $calendar->assoc_id = $assoc_id;
                $calendar->save();
            }else {
                if (!$request->filled('calendar_assoc_id_' . $lang->code)) {//create
                    $calendar = new CalendarEvent;
                    $calendar->language_id = $lang->id;
                    $calendar->title = $request->{'title_' . $lang->code};
                    $calendar->start_date = $request->{'start_date_' . $lang->code};
                    $calendar->end_date = $request->{'end_date_' . $lang->code};

                    $calendar->save();
                    $saved_ids[] = $calendar->id;
                }else {
                    $saved_ids[] = $request->{'calendar_assoc_id_' . $lang->code};
                }
            }
        }
        foreach ($saved_ids as $saved_id) {
            $calendar = CalendarEvent::findOrFail($saved_id);
            $calendar->assoc_id = $assoc_id;
            $calendar->save();
        }
        Session::flash('success', 'Event date updated in calendar successfully!');
        return "success";
    }

    public function delete(Request $request)
    {
        $_calendar = CalendarEvent::findOrFail($request->event_id);
        if($_calendar->assoc_id > 0) {
            $calendars = CalendarEvent::where('assoc_id', $_calendar->assoc_id)->get();
            foreach ($calendars as $calendar) {
                $calendar->delete();
            }
        }else {
            $_calendar->delete();
        }

        Session::flash('success', 'Event deleted successfully!');
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $_calendar = CalendarEvent::findOrFail($id);
            if($_calendar->assoc_id > 0) {
                $calendars = CalendarEvent::where('assoc_id', $_calendar->assoc_id)->get();
                foreach ($calendars as $calendar) {
                    $calendar->delete();
                }
            }else {
                $_calendar->delete();
            }
        }

        Session::flash('success', 'Events deleted successfully!');
        return "success";
    }
}
