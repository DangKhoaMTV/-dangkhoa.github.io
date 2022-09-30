@extends('admin.layout')

@if(!empty($course->language) && $course->language->rtl == 1)
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
  <h4 class="page-title">Edit Course</h4>
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
      <a href="#">Course Page</a>
    </li>
    <li class="separator">
      <i class="flaticon-right-arrow"></i>
    </li>
    <li class="nav-item">
      <a href="#">Edit Course</a>
    </li>
  </ul>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <div class="card-title d-inline-block">Edit Course</div>
        <a
          class="btn btn-info btn-sm float-right d-inline-block"
          href="{{route('admin.course.index') . '?language=' . request()->input('language')}}"
        >
          <span class="btn-label">
            <i
              class="fas fa-backward"
              style="font-size: 12px;"
            ></i>
          </span>
          Back
        </a>
      </div>

      <div class="card-body pt-5 pb-5" id="edit_content">
        <div class="row">
          <div class="col-lg-10 offset-lg-1">
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
            <form
              id="ajaxEditForm"
              action="{{route('admin.course.update')}}"
              method="POST"
              enctype="multipart/form-data"
            >
              @csrf
                @if (!empty($langs))
                    <div class="tab-content">
                        @foreach ($langs as $lang)
                            <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}"
                                 id="edit-lang-{{$lang->code}}">
                                @if($course[$lang->code]->id==0)
                                    <div class="form-group">
                                        <label class="" for="">Choose association **</label>
                                        <select class="form-control select2" name="course_assoc_id_{{$lang->code}}">
                                            <option value="" selected>Select a blog</option>
                                            @foreach ($ccates[$lang->code] as $ccate)
                                                <option value="{{$ccate->id}}">[{{$ccate->id}}-{{$ccate->assoc_id}}
                                                    ] {{$ccate->name}}</option>
                                            @endforeach
                                        </select>
                                        <p id="errcourse_assoc_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                    </div>
                                @else
                                    <input type="hidden" name="course_id_{{$lang->code}}" value="{{$course[$lang->code]->id}}">
                                @endif


                              {{-- Image Part --}}
                              <div class="form-group">
                                  <label for="">Image ** </label>
                                  <br>
                                  <div class="thumb-preview" id="thumbPreview{{$lang->id}}1">
                                      <img src="{{$course[$lang->code]->course_image!=''?asset('assets/front/img/courses/' . $course[$lang->code]->course_image):asset('assets/admin/img/noimage.jpg')}}" alt="Course Image">
                                  </div>
                                  <br>
                                  <br>


                                  <input id="fileInput{{$lang->id}}1" type="hidden" name="image_{{$lang->code}}">
                                  <button id="chooseImage{{$lang->id}}1" class="choose-image btn btn-primary" type="button" data-multiple="false" data-toggle="modal" data-target="#lfmModal{{$lang->id}}1">Choose Image</button>


                                  <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
                                  <p class="em text-danger mb-0" id="errimage_{{$lang->code}}"></p>

                              </div>

                              <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="">Course Category **</label>
                                    <select
                                      id="course_category_id"
                                      class="form-control"
                                      name="course_category_id_{{$lang->code}}"
                                    >
                                      <option
                                        value=""
                                        selected
                                        disabled
                                      >Select a Category</option>
                                      @foreach ($course_categories[$lang->code] as $course_category)
                                      <option
                                        value={{ $course_category->id }}
                                        {{ $course_category->id == $course[$lang->code]->course_category_id ? 'selected' : '' }}
                                      >{{ $course_category->name }}</option>
                                      @endforeach
                                    </select>
                                    <p
                                      id="errcourse_category_id_{{$lang->code}}"
                                      class="mb-0 text-danger em"
                                    ></p>
                                  </div>
                                </div>

                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="">Course Title **</label>
                                    <input
                                      type="text"
                                      class="form-control"
                                      name="title_{{$lang->code}}"
                                      placeholder="Enter Title"
                                      value="{{ $course[$lang->code]->title }}"
                                    >
                                    <p
                                      id="errtitle_{{$lang->code}}"
                                      class="mb-0 text-danger em"
                                    ></p>
                                  </div>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="">Current Price ({{$bex->base_currency_text}})</label>
                                    <input
                                      type="number"
                                      class="form-control ltr"
                                      name="current_price_{{$lang->code}}_{{$lang->code}}"
                                      placeholder="Enter Current Price"
                                      value="{{ $course[$lang->code]->current_price }}"
                                    >
                                    <p class="mb-0 text-danger em"></p>
                                    <p class="mb-0 text-warning">Leave it blank if it's a free course</p>
                                  </div>
                                </div>

                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="">Previous Price ({{$bex->base_currency_text}})</label>
                                    <input
                                      type="number"
                                      class="form-control ltr"
                                      name="previous_price_{{$lang->code}}"
                                      placeholder="Enter Previous Price"
                                      value="{{ $course[$lang->code]->previous_price }}"
                                    >
                                    <p class="mb-0 text-danger em"></p>
                                  </div>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="">Course Duration **</label>
                                    <input
                                      type="text"
                                      class="form-control ltr"
                                      name="duration_{{$lang->code}}"
                                      placeholder="eg: 10h 15m"
                                      value="{{ $course[$lang->code]->duration }}"
                                    >
                                    <p
                                      id="errduration_{{$lang->code}}"
                                      class="mb-0 text-danger em"
                                    ></p>
                                  </div>
                                </div>
                              </div>

                              <div class="form-group">
                                <label for="">Course Summary</label>
                                <textarea
                                  class="form-control"
                                  name="summary_{{$lang->code}}"
                                  rows="6"
                                  cols="80"
                                  placeholder="Enter Course Summary"
                                >{{ $course[$lang->code]->summary }}</textarea>
                                <p class="mb-0 text-danger em"></p>
                              </div>

                              <div class="form-group mb-1">
                                <label for="">Course Video **</label>
                                <input
                                  type="text"
                                  class="form-control ltr"
                                  name="video_link_{{$lang->code}}"
                                  placeholder="Enter YouTube Video Link"
                                  value="{{ $course[$lang->code]->video_link }}"
                                >
                                <p
                                  id="errvideo_link"
                                  class="mb-0 text-danger em"
                                ></p>
                              </div>

                              <div class="form-group">
                                <label for="">Course Overview **</label>
                                <textarea
                                  class="form-control summernote"
                                  name="overview_{{$lang->code}}"
                                  rows="8"
                                  cols="80"
                                  placeholder="Enter Overview"
                                >{{ $course[$lang->code]->overview }}</textarea>
                                <p
                                  id="erroverview"
                                  class="mb-0 text-danger em"
                                ></p>
                              </div>

                              <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="">Instructor Name **</label>
                                    <input
                                      type="text"
                                      class="form-control"
                                      name="instructor_name_{{$lang->code}}"
                                      placeholder="Enter Instructor Name"
                                      value="{{ $course[$lang->code]->instructor_name }}"
                                    >
                                    <p
                                      id="errinstructor_name_{{$lang->code}}"
                                      class="mb-0 text-danger em"
                                    ></p>
                                  </div>
                                </div>

                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="">Instructor Occupation **</label>
                                    <input
                                      type="text"
                                      class="form-control"
                                      name="instructor_occupation_{{$lang->code}}"
                                      placeholder="Enter Instructor Occupation"
                                      value="{{ $course[$lang->code]->instructor_occupation }}"
                                    >
                                    <p
                                      id="errinstructor_occupation_{{$lang->code}}"
                                      class="mb-0 text-danger em"
                                    ></p>
                                  </div>
                                </div>
                              </div>

                              <div class="form-group">
                                <label for="">Instructor Details **</label>
                                <textarea
                                  class="form-control"
                                  name="instructor_details_{{$lang->code}}"
                                  rows="6"
                                  cols="80"
                                  placeholder="Enter Instructor Details"
                                >{{ $course[$lang->code]->instructor_details }}</textarea>
                                <p
                                  id="errinstructor_details_{{$lang->code}}"
                                  class="mb-0 text-danger em"
                                ></p>
                              </div>

                              <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="">Instructor Facebook</label>
                                    <input
                                      type="text"
                                      class="form-control"
                                      name="instructor_facebook_{{$lang->code}}"
                                      placeholder="Enter Faecbook ID"
                                      value="{{ $course[$lang->code]->instructor_facebook }}"
                                    >
                                    <p class="mb-0 text-danger em"></p>
                                  </div>
                                </div>

                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="">Instructor Instagram</label>
                                    <input
                                      type="text"
                                      class="form-control"
                                      name="instructor_instagram_{{$lang->code}}"
                                      placeholder="Enter Instagram ID"
                                      value="{{ $course[$lang->code]->instructor_instagram }}"
                                    >
                                    <p class="mb-0 text-danger em"></p>
                                  </div>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="">Instructor Twitter</label>
                                    <input
                                      type="text"
                                      class="form-control"
                                      name="instructor_twitter_{{$lang->code}}"
                                      placeholder="Enter Twitter ID"
                                      value="{{ $course[$lang->code]->instructor_twitter }}"
                                    >
                                    <p class="mb-0 text-danger em"></p>
                                  </div>
                                </div>

                                <div class="col-md-6">
                                  <div class="form-group">
                                    <label for="">Instructor LinkedIn</label>
                                    <input
                                      type="text"
                                      class="form-control"
                                      name="instructor_linkedin_{{$lang->code}}"
                                      placeholder="Enter LinkedIn ID"
                                      value="{{ $course[$lang->code]->instructor_linkedin }}"
                                    >
                                    <p class="mb-0 text-danger em"></p>
                                  </div>
                                </div>

                                {{-- Instructor Image Part --}}
                                <div class="col-12">

                                    <div class="form-group">
                                        <label for="">Instructor Image ** </label>
                                        <br>
                                        <div class="thumb-preview" id="thumbPreview{{$lang->id}}2">
                                            <img src="{{$course[$lang->code]->instructor_image!=''?asset('assets/front/img/instructors/' . $course[$lang->code]->instructor_image):asset('assets/admin/img/noimage.jpg')}}" alt="Instructor Image">
                                        </div>
                                        <br>
                                        <br>


                                        <input id="fileInput{{$lang->id}}2" type="hidden" name="instructor_image_{{$lang->code}}">
                                        <button id="chooseImage{{$lang->id}}2" class="choose-image btn btn-primary" type="button" data-multiple="false" data-toggle="modal" data-target="#lfmModal{{$lang->id}}2">Choose Image</button>


                                        <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
                                        <p class="em text-danger mb-0" id="errinstructor_image_{{$lang->code}}"></p>
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
              <button
                type="submit"
                id="submitBtn"
                class="btn btn-success"
              >Update</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
