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
  <h4 class="page-title">Gallery Images</h4>
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
      <a href="#">Gallery Image Management</a>
    </li>
  </ul>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col-lg-2">
            <div class="card-title d-inline-block">Gallery Images</div>
          </div>
          <div class="col-lg-3">
            @if (!empty($langs))
            <select name="language" class="form-control"
              onchange="window.location = this.value">
              <option value="" selected disabled>Select a Language</option>
              @foreach ($langs as $lang)
              <option value="{{url()->current() . '?language='. $lang->code }}{{ request()->input('type') ? '&type='.request()->input('type') : '' }}" {{$lang->code == request()->input('language') ? 'selected' : ''}}>
                {{$lang->name}}</option>
              @endforeach
            </select>
            @endif
          </div>
          <div class="col-lg-3">
            <select name="type" class="form-control"
              onchange="window.location= this.value">
              <option value="" disabled>Select a Type</option>
                <option selected value="{{url()->current() . '?language=' . request()->input('language') . '&type=all'}}" {{'all' == request()->input('type') ? 'selected' : ''}}>All</option>
                <option value="{{url()->current() . '?language=' . request()->input('language') . '&type=image'}}" {{'image' == request()->input('type') ? 'selected' : ''}}>Image</option>
                <option value="{{url()->current() . '?language=' . request()->input('language') . '&type=video'}}" {{'video' == request()->input('type') ? 'selected' : ''}}>Video</option>
            </select>
          </div>
          <div class="col-lg-3 offset-lg-1 mt-2 mt-lg-0">
            <a href="#" class="btn btn-primary float-right btn-sm" data-toggle="modal" data-target="#createModal"><i
                class="fas fa-plus"></i> Add Image</a>
            <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
              data-href="{{route('admin.gallery.bulk.delete')}}"><i class="flaticon-interface-5"></i> Delete</button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-lg-12">
            @if (count($galleries) == 0)
            <h3 class="text-center">NO IMAGE FOUND</h3>
            @else
            <div class="table-responsive">
              <table class="table table-striped mt-3" id="basic-datatables">
                <thead>
                  <tr>
                    <th scope="col">
                      <input type="checkbox" class="bulk-check" data-val="all">
                    </th>
                    <th scope="col">Image</th>
                    <th scope="col">Title</th>
                    <th scope="col">Type</th>
                    <th scope="col">Category</th>
                    <th scope="col">Serial Number</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($galleries as $key => $gallery)
                  <tr>
                    <td>
                      <input type="checkbox" class="bulk-check" data-val="{{$gallery->id}}">
                    </td>
                    <td><img src="{{$gallery->image!=''?asset('assets/front/img/gallery/' . $gallery->image):asset('assets/admin/img/noimage.jpg')}}" alt="" width="80"></td>
                    <td>
                      {{strlen($gallery->title) > 70 ? mb_substr($gallery->title, 0, 70, 'UTF-8') . '...' : $gallery->title}}
                    </td>
                    <td>{{$gallery->type}}</td>
                    <td>{{$gallery->galleryImgCategory->name}}</td>
                    <td>{{$gallery->serial_number}}</td>
                    <td>
                      <a class="btn btn-secondary btn-sm editbtn_url"
                        href="{{route('admin.gallery.edit', $gallery->id) . '?language=' . request()->input('language')}}" data-url="{{route('admin.gallery.edit_modal', $gallery->id) . '?language=' . request()->input('language')}}" data-toggle="modal" data-target="#editModal">
                        <span class="btn-label">
                          <i class="fas fa-edit"></i>
                        </span>
                        Edit
                      </a>
                      <form class="deleteform d-inline-block" action="{{route('admin.gallery.delete')}}" method="post">
                        @csrf
                        <input type="hidden" name="gallery_id" value="{{$gallery->id}}">
                        <button type="submit" class="btn btn-danger btn-sm deletebtn">
                          <span class="btn-label">
                            <i class="fas fa-trash"></i>
                          </span>
                          Delete
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


<!-- Create Gallery Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add Image</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
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
        <form id="ajaxForm" class="modal-form" action="{{route('admin.gallery.store')}}" method="POST">
          @csrf
            @if (!empty($langs))
                <div class="tab-content">
                    @foreach ($langs as $lang)
                        <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}"
                             id="create-lang-{{$lang->code}}">
                            @include('admin.sameContent')

                          {{-- Image Part --}}
                          <div class="form-group">
                            <label for="">Image ** </label>
                            <br>
                            <div class="thumb-preview" id="thumbPreview1{{$lang->id}}">
                              <label for="chooseImage1{{$lang->id}}"><img src="{{asset('assets/admin/img/noimage.jpg')}}" alt="User Image"></label>
                            </div>
                            <br>
                            <br>

                            <input id="fileInput1{{$lang->id}}" type="hidden" name="image_{{$lang->code}}">
                            <button id="chooseImage1{{$lang->id}}" class="choose-image btn btn-primary" type="button" data-multiple="false"
                              data-toggle="modal" data-target="#lfmModal1{{$lang->id}}">Choose Image</button>
                            <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
                            <p class="em text-danger mb-0" id="errimage_{{$lang->code}}"></p>
                          </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Type **</label>
                                        <select name="type_{{$lang->code}}" class="form-control" id="galleryType_{{$lang->code}}">
                                            <option value="image">Image</option>
                                            <option value="video">Video</option>
                                        </select>
                                        <p id="errtype_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group" id="create_video_url_{{$lang->code}}" style="display: none">
                                        <label for="">Video URL **</label>
                                        <input disabled type="text" class="form-control" name="video_url_{{$lang->code}}" placeholder="Enter video url" value="">
                                        <p id="errvideo_url_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                    </div>
                                </div>
                            </div>

                          <div class="form-group {{ $categoryInfo->gallery_category_status == 0 ? 'd-none' : '' }}">
                            <label for="">Category **</label>
                            <select name="category_id_{{$lang->code}}" id="gallery_category_id_{{$lang->code}}" class="form-control">
                              <option selected disabled>Select a category</option>
                                @foreach ($categories[$lang->code] as $category)
                                    <option data-assoc_id="{{ $category->assoc_id }}" value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <p id="errcategory_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                          </div>
                          <div class="form-group">
                            <label for="">Title **</label>
                            <input type="text" class="form-control" name="title_{{$lang->code}}" placeholder="Enter title" value="">
                            <p id="errtitle_{{$lang->code}}" class="mb-0 text-danger em"></p>
                          </div>
                          <div class="form-group">
                            <label for="">Serial Number **</label>
                            <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}" value=""
                              placeholder="Enter Serial Number">
                            <p id="errserial_number_{{$lang->code}}" class="mb-0 text-danger em"></p>
                            <p class="text-warning"><small>The higher the serial number is, the later the image will be shown.</small>
                            </p>
                          </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="submitBtn" type="button" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </div>
</div>
<!-- Edit Gallery Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Edit Gallery</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Loading...</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="updateBtn" type="button" class="btn btn-primary">Update</button>
      </div>
    </div>
  </div>
</div>

@foreach ($langs as $lang)
    <!-- Image LFM Modal -->
    <div class="modal fade lfm-modal" id="lfmModal1{{$lang->id}}" tabindex="-1" role="dialog" aria-labelledby="lfmModalTitle"
      aria-hidden="true">
      <i class="fas fa-times-circle"></i>
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-body p-0">
            <iframe src="{{url('laravel-filemanager')}}?serial=1{{$lang->id}}"
              style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
          </div>
        </div>
      </div>
    </div>
   <!-- Image LFM Modal -->
    <div class="modal fade lfm-modal" id="lfmModal3{{$lang->id}}" tabindex="-1" role="dialog" aria-labelledby="lfmModalTitle"
      aria-hidden="true">
      <i class="fas fa-times-circle"></i>
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-body p-0">
            <iframe src="{{url('laravel-filemanager')}}?serial=3{{$lang->id}}"
              style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
          </div>
        </div>
      </div>
    </div>
@endforeach

@endsection

@section('scripts')
<script>
  $(document).ready(function() {
      @foreach ($langs as $lang)
      $("#galleryType_{{$lang->code}}").on('change', function () {
          let type = $(this).val();
          if (type == 'video') {
              $("#create_video_url_{{$lang->code}}").show();
              $("#create_video_url_{{$lang->code}} input").removeAttr('disabled');
          } else {
              $("#create_video_url_{{$lang->code}} input").attr('disabled', true);
              $("#create_video_url_{{$lang->code}}").hide();
          }
      });

          $( "input[name='video_url_{{$lang->code}}']" ).change(function() {
              if($(this).val().indexOf('v=') > 0){
                  var video_id = $(this).val().split('v=')[1];
                  var ampersandPosition = video_id.indexOf('&');
                  if(ampersandPosition != -1) {
                      video_id = video_id.substring(0, ampersandPosition);
                  }
                  $('#thumbPreview1{{$lang->id}} img').attr('src','http://i.ytimg.com/vi/'+video_id+'/maxresdefault.jpg');
                  $('#fileInput1{{$lang->id}}').val('http://i.ytimg.com/vi/'+video_id+'/maxresdefault.jpg');
                  $('#errvideo_url_{{$lang->code}}').text('');
              }else {
                    $('#errvideo_url_{{$lang->code}}').text('Please correct your video url!');
              }
          });

      @endforeach

    $("select[name='language_id']").on('change', function() {
      $("#gallery_category_id").removeAttr('disabled');

      let langId = $(this).val();
      let url = "{{url('/')}}/admin/gallery/" + langId + "/get_categories";

      $.get(url, function(data) {
        let options = `<option value="" disabled selected>Select a category</option>`;

        if (data.length == 0) {
          options += `<option value="" disabled>${'No Category Exists'}</option>`;
        } else {
          for (let i = 0; i < data.length; i++) {
            options +=`<option value="${data[i].id}">${data[i].name}</option>`;
          }
        }

        $("#gallery_category_id").html(options);
      });
    });

    // make input fields RTL
    $("select[name='language_id']").on('change', function() {
      $(".request-loader").addClass("show");
      let url = "{{url('/')}}/admin/rtlcheck/" + $(this).val();
      console.log(url);
      $.get(url, function(data) {
        $(".request-loader").removeClass("show");
        if (data == 1) {
          $("form input").each(function() {
            if (!$(this).hasClass('ltr')) {
              $(this).addClass('rtl');
            }
          });
          $("form select").each(function() {
            if (!$(this).hasClass('ltr')) {
              $(this).addClass('rtl');
            }
          });
          $("form textarea").each(function() {
            if (!$(this).hasClass('ltr')) {
              $(this).addClass('rtl');
            }
          });
          $("form .nicEdit-main").each(function() {
            $(this).addClass('rtl text-right');
          });
        } else {
          $("form input, form select, form textarea").removeClass('rtl');
          $("form .nicEdit-main").removeClass('rtl text-right');
        }
      });
    });
  });
</script>
@endsection
