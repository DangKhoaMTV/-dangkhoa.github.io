<?php

namespace App\Http\Controllers\Admin;

use App\BasicExtended;
use App\BasicExtra;
use App\Course;
use App\CourseCategory;
use App\CoursePurchase;
use App\Exports\EnrollExport;
use App\Http\Controllers\Controller;
use App\Language;
use App\Megamenu;
use App\OfflineGateway;
use App\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Carbon\Carbon;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $language = Language::where('code', $request->language)->first();
        $language_id = $language->id;

        $courses = Course::where('language_id', $language_id)
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.course.course.index', compact('courses'));
    }

    public function create()
    {
        $data = [];
        $languages = Language::orderBy('is_default', 'DESC')->get();
        foreach ($languages as $lang) {
            $data['course_categories'][$lang->code] = CourseCategory::where('language_id', $lang->id)
                ->where('status', 1)
                ->orderBy('serial_number', 'desc')
                ->get();
        }
        return view('admin.course.course.create', $data);
    }

    public function getCategories($langId)
    {
        $course_categories = CourseCategory::where('language_id', $langId)
            ->where('status', 1)
            ->get();

        return $course_categories;
    }

    public function store(Request $request)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $saved_ids = [];
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        foreach ($languages as $lang) {
            $slug = slug_create($request->{'title_' . $lang->code});
            $image = $request->{'image_' . $lang->code};
            $insImage = $request->{'instructor_image_' . $lang->code};
            $extImage = pathinfo($image, PATHINFO_EXTENSION);

            $extInsImage = pathinfo($insImage, PATHINFO_EXTENSION);

            $rules = [
                'course_category_id_' . $lang->code => 'required',
                'title_' . $lang->code => [
                    'required',
                    'max:255',
                    function ($attribute, $value, $fail) use ($slug) {
                        $courses = Course::all();
                        foreach ($courses as $key => $course) {
                            if (strtolower($slug) == strtolower($course->slug)) {
                                $fail('The title field must be unique.');
                            }
                        }
                    }
                ],
                'duration_' . $lang->code => 'required',
                'video_link_' . $lang->code => 'required',
                'overview_' . $lang->code => 'required',
                'instructor_name_' . $lang->code => 'required',
                'instructor_occupation_' . $lang->code => 'required',
                'instructor_details_' . $lang->code => 'required',
                'instructor_image_' . $lang->code => 'required',
                'image_' . $lang->code => 'required'
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

            if ($request->filled('instructor_image_' . $lang->code)) {
                $rules['instructor_image_' . $lang->code] = [
                    function ($attribute, $value, $fail) use ($extInsImage, $allowedExts) {
                        if (!in_array($extInsImage, $allowedExts)) {
                            return $fail("Only png, jpg, jpeg, svg image is allowed");
                        }
                    }
                ];
            }

            $messages = [
                'language_id.required' => 'The language field is required',
                'course_category_id.required' => 'The course category field is required'
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                $validator->getMessageBag()->add('error', 'true');
                return response()->json($validator->errors());
            }
        }

        foreach ($languages as $lang) {
            $slug = slug_create($request->{'title_' . $lang->code});
            $image = $request->{'image_' . $lang->code};
            $insImage = $request->{'instructor_image_' . $lang->code};
            $course = new Course;
            $course->language_id = $lang->id;
            $course->course_category_id = $request->{'course_category_id_' . $lang->code};
            $course->title = $request->{'title_' . $lang->code};
            $course->slug = $slug;
            $course->duration = $request->{'duration_' . $lang->code};
            $course->current_price = $request->{'current_price_' . $lang->code};
            $course->previous_price = $request->{'previous_price_' . $lang->code};
            $course->summary = $request->{'summary_' . $lang->code};

            if ($request->filled('image_' . $lang->code)) {
                $filename = uniqid() . '.' . $extImage;

                $dir = 'assets/front/img/courses/';
                @mkdir($dir, 0775, true);
                @copy($image, $dir . $filename);

                $course->course_image = $filename;
            }

            if ($request->filled('instructor_image_' . $lang->code)) {
                $filename = uniqid() . '.' . $extInsImage;

                $directory = "assets/front/img/instructors/";
                @mkdir($dir, 0775, true);
                @copy($insImage, $directory . $filename);

                $course->instructor_image = $filename;
            }

            $link = $request->{'video_link_' . $lang->code};

            if (strpos($link, "&") != 0) {
                $custom_link = substr($link, 0, strpos($link, "&"));
                $course->video_link = $custom_link;
            } else {
                $course->video_link = $request->{'video_link_' . $lang->code};
            }

            $course->overview = $request->{'overview_' . $lang->code};
            $course->instructor_name = $request->{'instructor_name_' . $lang->code};
            $course->instructor_occupation = $request->{'instructor_occupation_' . $lang->code};
            $course->instructor_details = $request->{'instructor_details_' . $lang->code};
            $course->instructor_facebook = $request->{'instructor_facebook_' . $lang->code};
            $course->instructor_instagram = $request->{'instructor_instagram_' . $lang->code};
            $course->instructor_twitter = $request->{'instructor_twitter_' . $lang->code};
            $course->instructor_linkedin = $request->{'instructor_linkedin_' . $lang->code};
            $course->save();

            if($assoc_id == 0){
                $assoc_id = $course->id;
            }

            $saved_ids[] = $course->id;
        }
        foreach ($saved_ids as $saved_id) {
            $course = Course::findOrFail($saved_id);
            $course->assoc_id = $assoc_id;
            $course->save();
        }
        Session::flash('success', 'Course Added Successfully');

        return 'success';
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $current_lang = Language::where('id', $course->language_id)->first();
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $data['langs'] = $languages;
        foreach ($languages as $lang) {
            if ($current_lang->id == $lang->id) {
                $data['course'][$lang->code] = $course;
            } else {
                $data['course'][$lang->code] = $course->assoc_id > 0 ? Course::where('language_id', $lang->id)->where('assoc_id', $course->assoc_id)->first() : null;
            }
            if ($data['course'][$lang->code] == null) {
                $data['course'][$lang->code] = new Course();
                $data['ccates'][$lang->code] = Course::where('language_id', $lang->id)->get();
            }
            $data['course_categories'][$lang->code] = CourseCategory::where('language_id', $lang->id)
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->get();
        }


        return view('admin.course.course.edit', $data);
    }

    public function update(Request $request)
    {
        $languages = Language::orderBy('is_default', 'DESC')->get();
        $assoc_id = 0;
        $saved_ids = [];
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        foreach ($languages as $lang) {
            if ($request->filled('course_id_' . $lang->code) || !$request->filled('course_assoc_id_' . $lang->code)) {//Validation
                $slug = slug_create($request->{'title_' . $lang->code});

                $insImage = $request->{'instructor_image_' . $lang->code};
                $image = $request->{'image_' . $lang->code};

                $courseId = $request->{'course_id_' . $lang->code};
                $extImage = pathinfo($image, PATHINFO_EXTENSION);
                $insExtImage = pathinfo($insImage, PATHINFO_EXTENSION);

                $rules = [
                    'title_' . $lang->code => [
                        'required',
                        'max:255',
                        function ($attribute, $value, $fail) use ($slug, $courseId) {
                            $courses = Course::all();
                            foreach ($courses as $key => $course) {
                                if ($course->id != $courseId && strtolower($slug) == strtolower($course->slug)) {
                                    $fail('The title field must be unique.');
                                }
                            }
                        }
                    ],
                    'duration_' . $lang->code => 'required',
                    'video_link_' . $lang->code => 'required',
                    'overview_' . $lang->code => 'required',
                    'instructor_name_' . $lang->code => 'required',
                    'instructor_occupation_' . $lang->code => 'required',
                    'instructor_details_' . $lang->code => 'required'
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

                if ($request->filled('instructor_image_' . $lang->code)) {
                    $rules['instructor_image'] = [
                        function ($attribute, $value, $fail) use ($insExtImage, $allowedExts) {
                            if (!in_array($insExtImage, $allowedExts)) {
                                return $fail("Only png, jpg, jpeg, svg image is allowed");
                            }
                        }
                    ];
                }

                $messages = [
                    'language_id.required' => 'The language field is required',
                    'course_category_id.required' => 'The course category field is required'
                ];

                $validator = Validator::make($request->all(), $rules, $messages);

                if ($validator->fails()) {
                    $validator->getMessageBag()->add('error', 'true');
                    return response()->json($validator->errors());
                }
            }
        }
        foreach ($languages as $lang) {
            if ($request->filled('course_id_' . $lang->code)) {//update
                $course = Course::findOrFail($request->{'course_id_' . $lang->code});
                $slug = slug_create($request->{'title_' . $lang->code});
                $insImage = $request->{'instructor_image_' . $lang->code};
                $image = $request->{'image_' . $lang->code};

                $course->course_category_id = $request->{'course_category_id_' . $lang->code};
                $course->title = $request->{'title_' . $lang->code};
                $course->slug = $slug;
                $course->current_price = $request->{'current_price_' . $lang->code};
                $course->previous_price = $request->{'previous_price_' . $lang->code};
                $course->duration = $request->{'duration_' . $lang->code};
                $course->summary = $request->{'summary_' . $lang->code};

                $link = $request->{'video_link_' . $lang->code};

                if (strpos($link, "&") != 0) {
                    $custom_link = substr($link, 0, strpos($link, "&"));
                    $course->video_link = $custom_link;
                } else {
                    $course->video_link = $request->{'video_link_' . $lang->code};
                }

                $course->overview = $request->{'overview_' . $lang->code};
                $course->instructor_name = $request->{'instructor_name_' . $lang->code};
                $course->instructor_occupation = $request->{'instructor_occupation_' . $lang->code};
                $course->instructor_details = $request->{'instructor_details_' . $lang->code};
                $course->instructor_facebook = $request->{'instructor_facebook_' . $lang->code};
                $course->instructor_instagram = $request->{'instructor_instagram_' . $lang->code};
                $course->instructor_twitter = $request->{'instructor_twitter_' . $lang->code};
                $course->instructor_linkedin = $request->{'instructor_linkedin_' . $lang->code};

                if ($request->filled('image_' . $lang->code)) {
                    @unlink('assets/front/img/courses/' . $course->course_image);
                    $filename = uniqid() . '.' . $extImage;
                    @copy($image, 'assets/front/img/courses/' . $filename);
                    $course->course_image = $filename;
                }

                if ($request->filled('instructor_image_' . $lang->code)) {
                    @unlink('assets/front/img/instructors/' . $course->instructor_image);
                    $filename = uniqid() . '.' . $insExtImage;
                    @copy($insImage, 'assets/front/img/instructors/' . $filename);
                    $course->instructor_image = $filename;
                }
                if ($assoc_id == 0) {
                    $assoc_id = $course->id;
                }

                $course->assoc_id = $assoc_id;
                $course->save();
            }else {
                if (!$request->filled('course_assoc_id_' . $lang->code)) {//create
                    $course = new Course;
                    $slug = slug_create($request->{'title_' . $lang->code});
                    $insImage = $request->{'instructor_image_' . $lang->code};
                    $image = $request->{'image_' . $lang->code};

                    $course->language_id = $lang->id;
                    $course->course_category_id = $request->{'course_category_id_' . $lang->code};
                    $course->title = $request->{'title_' . $lang->code};
                    $course->slug = $slug;
                    $course->current_price = $request->{'current_price_' . $lang->code};
                    $course->previous_price = $request->{'previous_price_' . $lang->code};
                    $course->duration = $request->{'duration_' . $lang->code};
                    $course->summary = $request->{'summary_' . $lang->code};

                    $link = $request->{'video_link_' . $lang->code};

                    if (strpos($link, "&") != 0) {
                        $custom_link = substr($link, 0, strpos($link, "&"));
                        $course->video_link = $custom_link;
                    } else {
                        $course->video_link = $request->{'video_link_' . $lang->code};
                    }

                    $course->overview = $request->{'overview_' . $lang->code};
                    $course->instructor_name = $request->{'instructor_name_' . $lang->code};
                    $course->instructor_occupation = $request->{'instructor_occupation_' . $lang->code};
                    $course->instructor_details = $request->{'instructor_details_' . $lang->code};
                    $course->instructor_facebook = $request->{'instructor_facebook_' . $lang->code};
                    $course->instructor_instagram = $request->{'instructor_instagram_' . $lang->code};
                    $course->instructor_twitter = $request->{'instructor_twitter_' . $lang->code};
                    $course->instructor_linkedin = $request->{'instructor_linkedin_' . $lang->code};

                    if ($request->filled('image_' . $lang->code)) {
                        @unlink('assets/front/img/courses/' . $course->course_image);
                        $filename = uniqid() . '.' . $extImage;
                        @copy($image, 'assets/front/img/courses/' . $filename);
                        $course->course_image = $filename;
                    }

                    if ($request->filled('instructor_image_' . $lang->code)) {
                        @unlink('assets/front/img/instructors/' . $course->instructor_image);
                        $filename = uniqid() . '.' . $insExtImage;
                        @copy($insImage, 'assets/front/img/instructors/' . $filename);
                        $course->instructor_image = $filename;
                    }
                    $course->save();
                    $saved_ids[] = $course->id;
                }else {
                    $saved_ids[] = $request->{'course_assoc_id_' . $lang->code};
                }
            }
        }
        foreach ($saved_ids as $saved_id) {
            $course = Course::findOrFail($saved_id);
            $course->assoc_id = $assoc_id;
            $course->save();
        }
        Session::flash('success', 'Course Updated Successfully');

        return 'success';
    }


    public function deleteFromMegaMenu($course) {
        // unset service from megamenu for service_category = 1
        $megamenu = Megamenu::where('language_id', $course->language_id)->where('category', 1)->where('type', 'courses');
        if ($megamenu->count() > 0) {
            $megamenu = $megamenu->first();
            $menus = json_decode($megamenu->menus, true);
            $catId = $course->courseCategory->id;
            if (is_array($menus) && array_key_exists("$catId", $menus)) {
                if (in_array($course->id, $menus["$catId"])) {
                    $index = array_search($course->id, $menus["$catId"]);
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
        $_course = Course::findOrFail($request->course_id);
        if($_course->assoc_id > 0) {
            $courses = Course::where('assoc_id', $_course->assoc_id)->get();
            foreach ($courses as $course) {
                if ($course->modules->count() > 1) {
                    Session::flash('warning', 'First Delete All The Modules of This Course');

                    return back();
                }

                if (File::exists('assets/front/img/courses/' . $course->course_image)) {
                    File::delete('assets/front/img/courses/' . $course->course_image);
                }
                if (File::exists('assets/front/img/instructors/' . $course->instructor_image)) {
                    File::delete('assets/front/img/instructors/' . $course->instructor_image);
                }

                $this->deleteFromMegaMenu($course);

                $course->delete();

            }
        }else {
            if ($_course->modules->count() > 1) {
                Session::flash('warning', 'First Delete All The Modules of This Course');

                return back();
            }

            if (File::exists('assets/front/img/courses/' . $_course->course_image)) {
                File::delete('assets/front/img/courses/' . $_course->course_image);
            }
            if (File::exists('assets/front/img/instructors/' . $_course->instructor_image)) {
                File::delete('assets/front/img/instructors/' . $_course->instructor_image);
            }

            $this->deleteFromMegaMenu($_course);

            $_course->delete();
        }
        Session::flash('success', 'Course Deleted Successfully');

        return back();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $_course = Course::findOrFail($id);
            if($_course->assoc_id > 0) {
                $courses = Course::where('assoc_id', $_course->assoc_id)->get();
                foreach ($courses as $course) {
                    if ($course->modules->count() > 1) {
                        Session::flash('warning', 'First Delete All The Modules of Those Courses');

                        return 'success';
                    }
                }
            }else {
                if ($_course->modules->count() > 1) {
                    Session::flash('warning', 'First Delete All The Modules of Those Courses');

                    return 'success';
                }
            }
        }

        foreach ($ids as $id) {
            $_course = Course::findOrFail($id);
            if($_course->assoc_id > 0) {
                $courses = Course::where('assoc_id', $_course->assoc_id)->get();
                foreach ($courses as $course) {
                    if (File::exists('assets/front/img/courses/' . $course->course_image)) {
                        File::delete('assets/front/img/courses/' . $course->course_image);
                    }
                    if (File::exists('assets/front/img/instructors/' . $course->instructor_image)) {
                        File::delete('assets/front/img/instructors/' . $course->instructor_image);
                    }

                    $this->deleteFromMegaMenu($course);

                    $course->delete();
                }
            }else {
                if (File::exists('assets/front/img/courses/' . $_course->course_image)) {
                    File::delete('assets/front/img/courses/' . $_course->course_image);
                }
                if (File::exists('assets/front/img/instructors/' . $_course->instructor_image)) {
                    File::delete('assets/front/img/instructors/' . $_course->instructor_image);
                }

                $this->deleteFromMegaMenu($_course);

                $_course->delete();
            }
        }

        Session::flash('success', 'Courses Deleted Successfully');

        return 'success';
    }

    public function featured(Request $request)
    {
        $course = Course::findOrFail($request->course_id);
        $course->is_featured = $request->is_featured;
        $course->save();

        if ($request->is_featured == 1) {
            Session::flash('success', 'This Course Has Featured');
        } else {
            Session::flash('success', 'This Course Has Unfeatured');
        }

        return back();
    }

    public function settings()
    {
        $data['abex'] = BasicExtra::first();
        return view('admin.course.settings', $data);
    }

    public function updateSettings(Request $request)
    {
        $bexs = BasicExtra::all();
        foreach ($bexs as $bex) {
            $bex->is_course = $request->is_course;
            $bex->is_course_rating = $request->is_course_rating;
            $bex->save();
        }

        $request->session()->flash('success', 'Settings updated successfully!');
        return back();
    }

    public function purchaseLog(Request $request) {
        $orderNum = $request->order_number;
        $data['purchases'] = CoursePurchase::orderBy('id', 'DESC')
                            ->when($orderNum, function ($query, $orderNum) {
                                return $query->where('order_number', $orderNum);
                            })
                            ->paginate(9);
        return view('admin.course.course.purchase', $data);
    }


    public function purchasePaymentStatus(Request $request)
    {
        $purchase = CoursePurchase::findOrFail($request->purchase_id);
        $purchase->payment_status = $request->payment_status;
        $purchase->save();

        $be = BasicExtended::first();
        $sub = 'Payment Status Updated';

        $to = $purchase->email;
        $fname = $purchase->first_name;

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
                $mail->Body    = 'Hello <strong>' . $fname . '</strong>,<br/>Your payment status for course <strong>' . $purchase->course->title . '</strong> is changed to ' . $request->payment_status . '.<br/>Thank you.';
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
                $mail->Subject = $sub;
                $mail->Body    = 'Hello <strong>' . $fname . '</strong>,<br/>Your payment status for course <strong>' . $purchase->course->title . '</strong> is changed to ' . $request->payment_status . '.<br/>Thank you.';

                $mail->send();
            } catch (Exception $e) {
                // die($e->getMessage());
            }
        }

        Session::flash('success', 'Payment status changed successfully!');
        return back();
    }


    public function purchaseBulkOrderDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $purchase = CoursePurchase::findOrFail($id);
            @unlink('assets/front/receipt/'.$purchase->receipt);
            @unlink('assets/front/invoices/course/'.$purchase->invoice);
            $purchase->delete();
        }

        Session::flash('success', 'Deleted successfully!');
        return "success";
    }

    public function purchaseDelete(Request $request)
    {
        $purchase = CoursePurchase::findOrFail($request->purchase_id);
        @unlink('assets/front/invoices/course/'.$purchase->invoice);
        @unlink('assets/front/receipt/'.$purchase->receipt);
        $purchase->delete();

        Session::flash('success', 'Deleted successfully!');
        return back();
    }

    public function report(Request $request) {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $paymentStatus = $request->payment_status;
        $paymentMethod = $request->payment_method;

        if (!empty($fromDate) && !empty($toDate)) {
            $enrolls = CoursePurchase::when($fromDate, function ($query, $fromDate) {
                return $query->whereDate('created_at', '>=', Carbon::parse($fromDate));
            })->when($toDate, function ($query, $toDate) {
                return $query->whereDate('created_at', '<=', Carbon::parse($toDate));
            })->when($paymentMethod, function ($query, $paymentMethod) {
                return $query->where('payment_method', $paymentMethod);
            })->when($paymentStatus, function ($query, $paymentStatus) {
                return $query->where('payment_status', $paymentStatus);
            })->select('order_number', 'user_id','first_name','email','course_id','current_price','payment_method','payment_status','created_at')->orderBy('id', 'DESC');
            Session::put('course_enroll_report', $enrolls->get());
            $data['enrolls'] = $enrolls->paginate(10);
        } else {
            Session::put('course_enroll_report', []);
            $data['enrolls'] = [];
        }

        $data['onPms'] = PaymentGateway::where('status', 1)->get();
        $data['offPms'] = OfflineGateway::where('course_checkout_status', 1)->get();


        return view('admin.course.course.report', $data);
    }

    public function exportReport() {
        $enrolls = Session::get('course_enroll_report');
        if (empty($enrolls) || count($enrolls) == 0) {
            Session::flash('warning', 'There are no enrollments to export');
            return back();
        }
        return Excel::download(new EnrollExport($enrolls), 'course-enrollments.csv');
    }
}
