@extends('admin.layout')

@if(!empty($job->language) && $job->language->rtl == 1)
@section('styles')
<style>
    form input,
    form textarea,
    form select {
        direction: rtl;
    }
    form .note-editor.note-frame .note-editing-area .note-editable {
        direction: rtl;
        text-align: right;
    }
</style>
@endsection
@endif

@section('content')
  <div class="page-header">
    <h4 class="page-title">Edit Job</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{route('admin.dashboard')}}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">Career Page</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">Edit Job</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">Edit Job</div>
          <a class="btn btn-info btn-sm float-right d-inline-block" href="{{route('admin.job.index') . '?language=' . request()->input('language')}}">
            <span class="btn-label">
              <i class="fas fa-backward" style="font-size: 12px;"></i>
            </span>
            Back
          </a>
        </div>
        <div id="edit_content" class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-12">
                @if (!empty($langs))
                    <ul class="nav nav-tabs">
                        @foreach ($langs as $lang)
                            <li class="nav-item">
                                <a class="nav-link {{$lang->code == request()->input('language') ? 'active' : ''}}" data-toggle="tab"
                                   href="#edit-lang-{{$lang->code}}">{{$lang->name}}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
                <form id="ajaxEditForm" class="" action="{{route('admin.job.update')}}" method="post">
                    @csrf
                    <div id="sliders"></div>
                    @if (!empty($langs))
                        <div class="tab-content">
                            @foreach ($langs as $lang)
                                <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}" id="edit-lang-{{$lang->code}}">
                                    @include('admin.sameContent')

                                    @if($job[$lang->code]->id==0)
                                        <div class="form-group">
                                            <label class="" for="">Choose association **</label>
                                            <select class="form-control select2" name="job_assoc_id_{{$lang->code}}">
                                                <option value="" selected>Select a blog</option>
                                                @foreach ($jcates[$lang->code] as $jcate)
                                                    <option value="{{$jcate->id}}">[{{$jcate->id}}-{{$jcate->assoc_id}}
                                                        ] {{$jcate->name}}</option>
                                                @endforeach
                                            </select>
                                            <p id="errjob_assoc_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                        </div>
                                    @else
                                        <input type="hidden" name="job_id_{{$lang->code}}" value="{{$job[$lang->code]->id}}">
                                    @endif

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">Title **</label>
                                            <input type="text" class="form-control" name="title_{{$lang->code}}" value="{{$job[$lang->code]->title}}"
                                                placeholder="Enter title">
                                            <p id="errtitle_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">Category **</label>
                                            <select class="form-control" name="jcategory_id_{{$lang->code}}">
                                                <option value="" selected disabled>Select a category</option>
                                                @foreach ($jcats[$lang->code] as $key => $jcat)
                                                <option value="{{$jcat->id}}" {{$job[$lang->code]->jcategory_id == $jcat->id ? 'selected' : ''}}>{{$jcat->name}}</option>
                                                @endforeach
                                            </select>
                                            <p id="errjcategory_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">Employment Status **</label>
                                            <input type="text" class="form-control" name="employment_status_{{$lang->code}}" value="{{$job[$lang->code]->employment_status}}"
                                                data-role="tagsinput">
                                            <p class="text-warning mb-0"><small>Use comma (,) to seperate statuses. eg: full-time, part-time, contractual</small></p>
                                            <p id="erremployment_status_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">Vacancy **</label>
                                            <input type="number" class="form-control" name="vacancy_{{$lang->code}}" value="{{$job[$lang->code]->vacancy}}"
                                                placeholder="Enter number of vacancy" min="1">
                                            <p id="errvacancy_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">Application Deadline **</label>
                                            <input id="deadline" type="text" class="form-control datepicker ltr" name="deadline_{{$lang->code}}" value="{{$job[$lang->code]->deadline}}" placeholder="Enter application deadline" autocomplete="off">
                                            <p id="errdeadline_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">Experience in Years **</label>
                                            <input type="text" class="form-control" name="experience_{{$lang->code}}" value="{{$job[$lang->code]->experience}}"
                                                placeholder="Enter years of experience">
                                            <p id="errexperience_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">Job Responsibilities **</label>
                                            <textarea class="form-control summernote" id="jobRes_{{$lang->code}}" name="job_responsibilities_{{$lang->code}}" data-height="150"
                                                placeholder="Enter job responsibilities">{{replaceBaseUrl($job[$lang->code]->job_responsibilities)}}</textarea>
                                            <p id="errjob_responsibilities_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">Educational Requirements **</label>
                                            <textarea class="form-control summernote" id="eduReq_{{$lang->code}}" name="educational_requirements_{{$lang->code}}" data-height="150"
                                                placeholder="Enter educational requirements">{{replaceBaseUrl($job[$lang->code]->educational_requirements)}}</textarea>
                                            <p id="erreducational_requirements_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">Experience Requirements **</label>
                                            <textarea class="form-control summernote" id="expReq_{{$lang->code}}" name="experience_requirements_{{$lang->code}}" data-height="150"
                                                placeholder="Enter experience requirements">{{replaceBaseUrl($job[$lang->code]->experience_requirements)}}</textarea>
                                            <p id="errexperience_requirements_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">Additional Requirements</label>
                                            <textarea class="form-control summernote" id="addReq_{{$lang->code}}" name="additional_requirements_{{$lang->code}}" data-height="150"
                                                placeholder="Enter additional requirements">{{replaceBaseUrl($job[$lang->code]->additional_requirements)}}</textarea>
                                            <p id="erradditional_requirements_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">Salary **</label>
                                            <textarea class="form-control summernote" id="salary_{{$lang->code}}" name="salary_{{$lang->code}}" data-height="150"
                                                placeholder="Enter salary">{{replaceBaseUrl($job[$lang->code]->salary)}}</textarea>
                                            <p id="errsalary_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">Benefits</label>
                                            <textarea class="form-control summernote" id="benefits_{{$lang->code}}" name="benefits_{{$lang->code}}" data-height="150"
                                                placeholder="Enter compensation & other benefits">{{replaceBaseUrl($job[$lang->code]->benefits)}}</textarea>
                                            <p id="errbenefits_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">Job Location **</label>
                                            <input type="text" class="form-control" name="job_location_{{$lang->code}}" value="{{$job[$lang->code]->job_location}}"
                                                placeholder="Enter job location">
                                            <p id="errjob_location_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">Email <span class="text-warning">(Where applicatints will send their CVs)</span> **</label>
                                            <input type="email" class="form-control ltr" name="email_{{$lang->code}}" value="{{$job[$lang->code]->email}}"
                                                placeholder="Enter email address">
                                            <p id="erremail_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">Read Before Apply</label>
                                            <textarea class="form-control summernote" id="read_before_apply_{{$lang->code}}" name="read_before_apply_{{$lang->code}}" data-height="150"
                                                placeholder="Enter read before apply">{{replaceBaseUrl($job[$lang->code]->read_before_apply)}}</textarea>
                                            <p id="errread_before_apply_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">Serial Number **</label>
                                            <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}" value="{{$job[$lang->code]->serial_number}}" placeholder="Enter Serial Number">
                                            <p id="errserial_number_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                            <p class="text-warning"><small>The higher the serial number is, the later the job will be shown.</small></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Meta Keywords</label>
                                            <input class="form-control" name="meta_keywords_{{$lang->code}}" value="{{$job[$lang->code]->meta_keywords}}" placeholder="Enter meta keywords" data-role="tagsinput">
                                         </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Meta Description</label>
                                            <textarea class="form-control" name="meta_description_{{$lang->code}}" rows="3" placeholder="Enter meta description">{{$job[$lang->code]->meta_description}}</textarea>
                                         </div>
                                    </div>
                                </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </form>
            </div>
          </div>

        </div>
        <div class="card-footer">
          <div class="form">
            <div class="form-group from-show-notify row">
              <div class="col-12 text-center">
                <button type="submit" id="updateBtn" class="btn btn-success">Update</button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

@endsection

@section('scripts')
    <script>

    $(document).ready(function() {
        var today = new Date();
        $("#deadline").datepicker({
            autoclose: true,
            endDate : today,
            todayHighlight: true
        });
    });

    </script>
@endsection
