<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Job;
use App\Jcategory;
use App\Language;
use Validator;
use Session;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $lang = Language::where('code', $request->language)->first();
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $lang_id = $lang->id;
        $data['jobs'] = Job::where('language_id', $lang_id)->orderBy('id', 'DESC')->get();
        foreach ($languages as $lang) {
            $data['jcats'][$lang->code] = Jcategory::where('status', 1)->where('language_id', $lang->id)->get();
        }

        return view('admin.job.job.index', $data);
    }

    public function edit($id)
    {
        $job = Job::findOrFail($id);
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $current_lang = Language::where('id', $job->language_id)->first();
        $data['langs'] = $languages;
        foreach ($languages as $lang) {
            if ($current_lang->id == $lang->id) {
                $data['job'][$lang->code] = $job;
            } else {
                $data['job'][$lang->code] = $job->assoc_id > 0 ? Job::where('language_id', $lang->id)->where('assoc_id', $job->assoc_id)->first() : null;
            }
            if ($data['job'][$lang->code] == null) {
                $data['job'][$lang->code] = new Job();
                $data['jcates'][$lang->code] = Job::where('language_id', $lang->id)->get();
            }
            $data['jcats'][$lang->code] = Jcategory::where('status', 1)->where('language_id', $lang->id)->get();
        }

        return view('admin.job.job.edit', $data);
    }

    public function create()
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $data['langs'] = $languages;
        foreach ($languages as $lang) {
            $data['jcats'][$lang->code] = Jcategory::where('status', 1)->where('language_id', $lang->id)->get();
        }
        $data['tjobs'] = Job::where('language_id', 0)->get();
        return view('admin.job.job.create', $data);
    }

    public function store(Request $request)
    {
        $messages = [
            'jcategory_id.required' => 'The category field is required',
            'language_id.required' => 'The language field is required'
        ];

        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $saved_ids = [];
        foreach ($languages as $lang) {
            $slug = make_slug($request->{'title_'. $lang->code});
            $rules = [
                'deadline_' . $lang->code => 'required|date',
                'experience_' . $lang->code => 'required',
                'jcategory_id_' . $lang->code => 'required',
                'title_' . $lang->code => [
                    'required',
                    'max:255',
                    function ($attribute, $value, $fail) use ($slug) {
                        $jobs = Job::all();
                        foreach ($jobs as $key => $job) {
                            if (strtolower($slug) == strtolower($job->slug)) {
                                $fail('The title field must be unique.');
                            }
                        }
                    }
                ],
                'vacancy_'. $lang->code => 'required|integer',
                'employment_status_'. $lang->code => 'required|max:255',
                'job_responsibilities_'. $lang->code => 'required',
                'educational_requirements_'. $lang->code => 'required',
                'experience_requirements_'. $lang->code => 'required',
                'additional_requirements_'. $lang->code => 'nullable',
                'job_location_'. $lang->code => 'required|max:255',
                'salary_'. $lang->code => 'required',
                'email_'. $lang->code => 'required|email|max:255',
                'benefits_'. $lang->code => 'nullable',
                'read_before_apply_'. $lang->code => 'nullable',
                'serial_number_'. $lang->code => 'required|integer',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $errmsgs = $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }
        }
        foreach ($languages as $lang) {
            $job = new Job;
            $slug = make_slug($request->{'title_'. $lang->code});

            $job->language_id = $lang->id;
            $job->jcategory_id = $request->{'jcategory_id_' . $lang->code};
            $job->title = $request->{'title_' . $lang->code};
            $job->slug = $slug;
            $job->vacancy = $request->{'vacancy_' . $lang->code};
            $job->deadline = $request->{'deadline_' . $lang->code};
            $job->deadline = $request->{'deadline_' . $lang->code};
            $job->experience = $request->{'experience_' . $lang->code};

            $job->job_responsibilities = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'job_responsibilities_' . $lang->code});

            $job->employment_status = $request->{'employment_status_' . $lang->code};

            $job->educational_requirements = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'educational_requirements_' . $lang->code});

            $job->experience_requirements = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'experience_requirements_' . $lang->code});

            $job->additional_requirements = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'additional_requirements_' . $lang->code});

            $job->job_location = $request->{'job_location_' . $lang->code};

            $job->salary = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'salary_' . $lang->code});

            $job->benefits = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'benefits_' . $lang->code});

            $job->read_before_apply = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'read_before_apply_' . $lang->code});

            $job->email = $request->{'email_' . $lang->code};
            $job->serial_number = $request->{'serial_number_' . $lang->code};
            $job->meta_keywords = $request->{'meta_keywords_' . $lang->code};
            $job->meta_description = $request->{'meta_description_' . $lang->code};

            $job->save();

            if($assoc_id == 0){
                $assoc_id = $job->id;
            }

            $saved_ids[] = $job->id;
        }

        foreach ($saved_ids as $saved_id) {
            $job = Job::findOrFail($saved_id);
            $job->assoc_id = $assoc_id;
            $job->save();
        }
        Session::flash('success', 'Job posted successfully!');
        return "success";
    }

    public function update(Request $request)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $saved_ids = [];
        foreach ($languages as $lang) {
            if ($request->filled('job_id_' . $lang->code) || !$request->filled('job_assoc_id_' . $lang->code)) {//Validation
                $slug = make_slug($request->{'title_' . $lang->code});
                $jobId = $request->{'job_id_' . $lang->code};

                $messages = [
                    'jcategory_id.required' => 'The category field is required'
                ];

                $rules = [
                    'deadline_' . $lang->code => 'required|date',
                    'experience_' . $lang->code => 'required',
                    'jcategory_id_' . $lang->code => 'required',
                    'title_' . $lang->code => [
                        'required',
                        'max:255',
                        function ($attribute, $value, $fail) use ($slug, $jobId) {
                            $jobs = Job::all();
                            foreach ($jobs as $key => $job) {
                                if ($job->id != $jobId && strtolower($slug) == strtolower($job->slug)) {
                                    $fail('The title field must be unique.');
                                }
                            }
                        }
                    ],
                    'vacancy_'. $lang->code => 'required|integer',
                    'employment_status_'. $lang->code => 'required|max:255',
                    'job_responsibilities_'. $lang->code => 'required',
                    'educational_requirements_'. $lang->code => 'required',
                    'experience_requirements_'. $lang->code => 'required',
                    'additional_requirements_'. $lang->code => 'nullable',
                    'job_location_'. $lang->code => 'required|max:255',
                    'salary_'. $lang->code => 'required',
                    'email_'. $lang->code => 'required|email|max:255',
                    'benefits_'. $lang->code => 'nullable',
                    'read_before_apply_'. $lang->code => 'nullable',
                    'serial_number_'. $lang->code => 'required|integer',
                ];

                $validator = Validator::make($request->all(), $rules, $messages);
                if ($validator->fails()) {
                    $errmsgs = $validator->getMessageBag()->add('error', 'true');
                    return response()->json($validator->errors());
                }

            }
        }
        foreach ($languages as $lang) {
            if ($request->filled('job_id_' . $lang->code)) {//update
                $job = Job::findOrFail($request->{'job_id_' . $lang->code});
                $slug = make_slug($request->{'title_'. $lang->code});

                $job->language_id = $lang->id;
                $job->jcategory_id = $request->{'jcategory_id_' . $lang->code};
                $job->title = $request->{'title_' . $lang->code};
                $job->slug = $slug;
                $job->vacancy = $request->{'vacancy_' . $lang->code};
                $job->deadline = $request->{'deadline_' . $lang->code};
                $job->deadline = $request->{'deadline_' . $lang->code};
                $job->experience = $request->{'experience_' . $lang->code};

                $job->job_responsibilities = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'job_responsibilities_' . $lang->code});

                $job->employment_status = $request->{'employment_status_' . $lang->code};

                $job->educational_requirements = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'educational_requirements_' . $lang->code});

                $job->experience_requirements = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'experience_requirements_' . $lang->code});

                $job->additional_requirements = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'additional_requirements_' . $lang->code});

                $job->job_location = $request->{'job_location_' . $lang->code};

                $job->salary = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'salary_' . $lang->code});

                $job->benefits = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'benefits_' . $lang->code});

                $job->read_before_apply = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'read_before_apply_' . $lang->code});

                $job->email = $request->{'email_' . $lang->code};
                $job->serial_number = $request->{'serial_number_' . $lang->code};
                $job->meta_keywords = $request->{'meta_keywords_' . $lang->code};
                $job->meta_description = $request->{'meta_description_' . $lang->code};

                if($assoc_id == 0){
                    $assoc_id = $job->id;
                }
                $job->assoc_id = $assoc_id;
                $job->save();
            }else {
                if (!$request->filled('job_assoc_id_' . $lang->code)) {//create
                    $job = new Job;
                    $slug = make_slug($request->{'title_'. $lang->code});

                    $job->language_id = $lang->id;
                    $job->jcategory_id = $request->{'jcategory_id_' . $lang->code};
                    $job->title = $request->{'title_' . $lang->code};
                    $job->slug = $slug;
                    $job->vacancy = $request->{'vacancy_' . $lang->code};
                    $job->deadline = $request->{'deadline_' . $lang->code};
                    $job->deadline = $request->{'deadline_' . $lang->code};
                    $job->experience = $request->{'experience_' . $lang->code};

                    $job->job_responsibilities = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'job_responsibilities_' . $lang->code});

                    $job->employment_status = $request->{'employment_status_' . $lang->code};

                    $job->educational_requirements = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'educational_requirements_' . $lang->code});

                    $job->experience_requirements = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'experience_requirements_' . $lang->code});

                    $job->additional_requirements = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'additional_requirements_' . $lang->code});

                    $job->job_location = $request->{'job_location_' . $lang->code};

                    $job->salary = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'salary_' . $lang->code});

                    $job->benefits = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'benefits_' . $lang->code});

                    $job->read_before_apply = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->{'read_before_apply_' . $lang->code});

                    $job->email = $request->{'email_' . $lang->code};
                    $job->serial_number = $request->{'serial_number_' . $lang->code};
                    $job->meta_keywords = $request->{'meta_keywords_' . $lang->code};
                    $job->meta_description = $request->{'meta_description_' . $lang->code};

                    $job->save();

                    $saved_ids[] = $job->id;
                }else {
                    $saved_ids[] = $request->{'job_assoc_id_' . $lang->code};
                }
            }
        }
        foreach ($saved_ids as $saved_id) {
            $job = Job::findOrFail($saved_id);
            $job->assoc_id = $assoc_id;
            $job->save();
        }
        Session::flash('success', 'Job details updated successfully!');
        return "success";
    }

    public function delete(Request $request)
    {
        $_job = Job::findOrFail($request->job_id);
        if($_job->assoc_id > 0) {
            $jobs = Job::where('assoc_id', $_job->assoc_id)->get();
            foreach ($jobs as $job) {
                $job->delete();
            }
        }else {
            $_job->delete();
        }

        Session::flash('success', 'Job deleted successfully!');
        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $_job = Job::findOrFail($id);
            if($_job->assoc_id > 0) {
                $jobs = Job::where('assoc_id', $_job->assoc_id)->get();
                foreach ($jobs as $job) {
                    $job->delete();
                }
            }else {
                $_job->delete();
            }
        }

        Session::flash('success', 'Jobs deleted successfully!');
        return "success";
    }

    public function getcats($langid)
    {
        $jcategories = Jcategory::where('language_id', $langid)->get();

        return $jcategories;
    }
}
