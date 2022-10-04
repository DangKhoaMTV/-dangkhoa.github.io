@extends('admin.layout')

@php
$selLang = \App\Language::where('code', request()->input('language'))->first();
@endphp
@if(!empty($selLang) && $selLang->rtl == 1)
@section('styles')
<style>
    form:not(.modal-form) input,
    form:not(.modal-form) textarea,
    form:not(.modal-form) select,
    select[name='language'] {
        direction: rtl;
    }
    form:not(.modal-form) .note-editor.note-frame .note-editing-area .note-editable {
        direction: rtl;
        text-align: right;
    }
</style>
@endsection
@endif

@section('content')
  <div class="page-header">
    <h4 class="page-title">Jobs</h4>
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
        <a href="#">Jobs</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card-title d-inline-block">Jobs</div>
                </div>
                <div class="col-lg-3">
                    @if (!empty($langs))
                        <select name="language" class="form-control" onchange="window.location='{{url()->current() . '?language='}}'+this.value">
                            <option value="" selected disabled>Select a Language</option>
                            @foreach ($langs as $lang)
                                <option value="{{$lang->code}}" {{$lang->code == request()->input('language') ? 'selected' : ''}}>{{$lang->name}}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
                <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
                    <a href="{{route('admin.job.create') . '?language=' . request()->input('language')}}" data-toggle="modal" data-target="#createModal" class="btn btn-primary float-lg-right float-left btn-sm"><i class="fas fa-plus"></i> Post Job</a>
                    <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete" data-href="{{route('admin.job.bulk.delete')}}"><i class="flaticon-interface-5"></i> Delete</button>
                </div>
            </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($jobs) == 0)
                <h3 class="text-center">NO JOB FOUND</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                            <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">Title</th>
                        <th scope="col">Category</th>
                        <th scope="col">Vacancy</th>
                        <th scope="col">Serial Number</th>
                        <th scope="col" width="17%">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($jobs as $key => $job)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{$job->id}}">
                          </td>
                          <td>{{strlen(convertUtf8($job->title)) > 70 ? convertUtf8(substr($job->title, 0, 70)) . '...' : convertUtf8($job->title)}}</td>
                          <td>
                              @if (!empty($job->jcategory))
                              {{convertUtf8($job->jcategory->name)}}
                              @endif
                          </td>
                          <td>{{$job->vacancy}}</td>
                          <td>{{$job->serial_number}}</td>
                          <td width="17%">
                            <a class="btn btn-secondary btn-sm editbtn_url" href="{{route('admin.job.edit', $job->id) . '?language=' . request()->input('language')}}" data-url="{{route('admin.job.edit', $job->id) . '?language=' . request()->input('language') . ' #edit_content'}}" data-toggle="modal" data-target="#editModal">
                              <i class="fas fa-edit"></i>
                            </a>
                            <form class="deleteform d-inline-block" action="{{route('admin.job.delete')}}" method="post">
                              @csrf
                              <input type="hidden" name="job_id" value="{{$job->id}}">
                              <button type="submit" class="btn btn-danger btn-sm deletebtn">
                                  <i class="fas fa-trash"></i>
                              </button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Create Job Modal -->
  <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
       aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width: 90%;">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Post Job</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body job-create">
                  <div class="row">
                      <div class="col-lg-12">
                          @if (!empty($langs))
                              <ul class="nav nav-tabs">
                                  @foreach ($langs as $lang)
                                      <li class="nav-item">
                                          <a class="nav-link {{$lang->code == request()->input('language') ? 'active' : ''}}" data-toggle="tab"
                                             href="#create-lang-{{$lang->code}}">{{$lang->name}}</a>
                                      </li>
                                  @endforeach
                              </ul>
                          @endif
                          <form id="ajaxForm" class="" action="{{route('admin.job.store')}}" method="post">
                              @csrf
                              <div id="sliders"></div>
                              @if (!empty($langs))
                                  <div class="tab-content">
                                      @foreach ($langs as $lang)
                                          <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}" id="create-lang-{{$lang->code}}">
                                              @include('admin.sameContent')

                                              <div class="row">
                                                  <div class="col-lg-6">
                                                      <div class="form-group">
                                                          <label for="">Title **</label>
                                                          <input type="text" class="form-control" name="title_{{$lang->code}}" value=""
                                                                 placeholder="Enter title">
                                                          <p id="errtitle_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                                      </div>
                                                  </div>
                                                  <div class="col-lg-6">
                                                      <div class="form-group">
                                                          <label for="">Category **</label>
                                                          <select id="jcategory_{{$lang->code}}" class="form-control" name="jcategory_id_{{$lang->code}}">
                                                              <option value="" selected disabled>Select a category</option>
                                                              @foreach ($jcats[$lang->code] as $key => $jcat)
                                                                  <option value="{{$jcat->id}}" data-assoc_id="{{$jcat->assoc_id}}">{{$jcat->name}}</option>
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
                                                          <input type="text" class="form-control" name="employment_status_{{$lang->code}}" value=""
                                                                 data-role="tagsinput">
                                                          <p class="text-warning mb-0"><small>Use comma (,) to seperate statuses. eg: full-time, part-time, contractual</small></p>
                                                          <p id="erremployment_status_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                                      </div>
                                                  </div>
                                                  <div class="col-lg-6">
                                                      <div class="form-group">
                                                          <label for="">Vacancy **</label>
                                                          <input type="number" class="form-control" name="vacancy_{{$lang->code}}" value=""
                                                                 placeholder="Enter number of vacancy" min="1">
                                                          <p id="errvacancy_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-lg-6">
                                                      <div class="form-group">
                                                          <label for="">Application Deadline **</label>
                                                          <input id="deadline" type="text" class="form-control datepicker ltr" name="deadline_{{$lang->code}}" value="" placeholder="Enter application deadline" autocomplete="off">
                                                          <p id="errdeadline_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                                      </div>
                                                  </div>
                                                  <div class="col-lg-6">
                                                      <div class="form-group">
                                                          <label for="">Experience in Years **</label>
                                                          <input type="text" class="form-control" name="experience_{{$lang->code}}" value=""
                                                                 placeholder="Enter years of experience">
                                                          <p id="errexperience_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-lg-6">
                                                      <div class="form-group">
                                                          <label for="">Job Responsibilities **</label>
                                                          <textarea class="form-control summernote" id="jobRes_{{$lang->code}}" name="job_responsibilities_{{$lang->code}}"
                                                                    placeholder="Enter job responsibilities" data-height="150"></textarea>
                                                          <p id="errjob_responsibilities_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                                      </div>
                                                  </div>
                                                  <div class="col-lg-6">
                                                      <div class="form-group">
                                                          <label for="">Educational Requirements **</label>
                                                          <textarea class="form-control summernote" id="eduReq_{{$lang->code}}" name="educational_requirements_{{$lang->code}}"
                                                                    placeholder="Enter educational requirements" data-height="150"></textarea>
                                                          <p id="erreducational_requirements_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-lg-6">
                                                      <div class="form-group">
                                                          <label for="">Experience Requirements **</label>
                                                          <textarea class="form-control summernote" id="expReq_{{$lang->code}}" name="experience_requirements_{{$lang->code}}"
                                                                    placeholder="Enter experience requirements" data-height="150"></textarea>
                                                          <p id="errexperience_requirements_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                                      </div>
                                                  </div>
                                                  <div class="col-lg-6">
                                                      <div class="form-group">
                                                          <label for="">Additional Requirements</label>
                                                          <textarea class="form-control summernote" id="addReq_{{$lang->code}}" name="additional_requirements_{{$lang->code}}"
                                                                    placeholder="Enter additional requirements" data-height="150"></textarea>
                                                          <p id="erradditional_requirements_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-lg-6">
                                                      <div class="form-group">
                                                          <label for="">Salary **</label>
                                                          <textarea class="form-control summernote" id="salary_{{$lang->code}}" name="salary_{{$lang->code}}"
                                                                    placeholder="Enter salary" data-height="150"></textarea>
                                                          <p id="errsalary_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                                      </div>
                                                  </div>
                                                  <div class="col-lg-6">
                                                      <div class="form-group">
                                                          <label for="">Benefits</label>
                                                          <textarea class="form-control summernote" id="benefits_{{$lang->code}}" name="benefits_{{$lang->code}}"
                                                                    placeholder="Enter compensation & other benefits" data-height="150"></textarea>
                                                          <p id="errbenefits_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-lg-6">
                                                      <div class="form-group">
                                                          <label for="">Job Location **</label>
                                                          <input type="text" class="form-control" name="job_location_{{$lang->code}}" value=""
                                                                 placeholder="Enter job location">
                                                          <p id="errjob_location_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                                      </div>
                                                  </div>
                                                  <div class="col-lg-6">
                                                      <div class="form-group">
                                                          <label for="">Email <span class="text-warning">(Where applicatints will send their CVs)</span> **</label>
                                                          <input type="email" class="form-control ltr" name="email_{{$lang->code}}" value=""
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
                                                                    placeholder="Enter read before apply"></textarea>
                                                          <p id="errread_before_apply_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                                      </div>
                                                  </div>
                                                  <div class="col-lg-6">
                                                      <div class="form-group">
                                                          <label for="">Serial Number **</label>
                                                          <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}" value="" placeholder="Enter Serial Number">
                                                          <p id="errserial_number_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                                          <p class="text-warning"><small>The higher the serial number is, the later the job will be shown.</small></p>
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-lg-6">
                                                      <div class="form-group">
                                                          <label>Meta Keywords</label>
                                                          <input class="form-control" name="meta_keywords_{{$lang->code}}" value="" placeholder="Enter meta keywords" data-role="tagsinput">
                                                      </div>
                                                  </div>
                                                  <div class="col-lg-6">
                                                      <div class="form-group">
                                                          <label>Meta Description</label>
                                                          <textarea class="form-control" name="meta_description_{{$lang->code}}" placeholder="Enter meta description" rows="4"></textarea>
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
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button id="submitBtn" type="button" class="btn btn-primary">Create</button>
              </div>
          </div>
      </div>
  </div>

  <!-- Edit Job Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
       aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="max-width: 90%;">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Edit Job</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body job-edit">
                  <p>loading...</p>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button id="updateBtn" type="button" class="btn btn-primary">Save Changes</button>
              </div>
          </div>
      </div>
  </div>
@endsection
