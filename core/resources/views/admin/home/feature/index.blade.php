@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">Features</h4>
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
        <a href="#">Home Page</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">Features</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card-title d-inline-block">Features</div>
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
                    <a href="#" class="btn btn-primary float-lg-right float-left" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i> Add Feature</a>
                </div>
            </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($features) == 0)
                <h3 class="text-center">NO FEATURE FOUND</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Icon</th>
                        <th scope="col">Title</th>
                        <th scope="col">Serial Number</th>
                        <th scope="col">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($features as $key => $feature)
                        <tr>
                          <td>{{$loop->iteration}}</td>
                          <td> @if($feature->type == 'icon') <i class="{{ $feature->icon }}"></i> @else <img width="50" src="{{asset('assets/front/img/featured/' . $feature->image)}}" /> @endif</td>
                          <td>{{convertUtf8($feature->title)}}</td>
                          <td>{{$feature->serial_number}}</td>
                          <td>
                            <a class="btn btn-secondary btn-sm editbtn_url" data-url="{{route('admin.feature.edit-modal', $feature->id) . '?language=' . request()->input('language')}}" href="{{route('admin.feature.edit', $feature->id) . '?language=' . request()->input('language')}}" data-toggle="modal" data-target="#editModal">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                              Edit
                            </a>
                            <form class="deleteform d-inline-block" action="{{route('admin.feature.delete')}}" method="post">
                              @csrf
                              <input type="hidden" name="feature_id" value="{{$feature->id}}">
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


  <!-- Create Feature Modal -->
  <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Add Feature</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            @if (!empty($langs))
                <ul class="nav nav-tabs">
                    @foreach ($langs as $lang)
                        <li class="nav-item">
                            <a class="nav-link {{$lang->code == request()->input('language') ? 'active' : ''}}" data-toggle="tab" href="#create-lang-{{$lang->code}}">{{$lang->name}}</a>
                        </li>
                    @endforeach
                </ul>
            @endif
          <form id="ajaxForm" class="modal-form" action="{{route('admin.feature.store')}}" method="post">
            @csrf
              @if (!empty($langs))
                  <div class="tab-content">
                      @foreach ($langs as $lang)
                          <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}" id="create-lang-{{$lang->code}}">
                              @include('admin.sameContent')

              <div class="form-group">
                  <label for="">Type **</label>
                  <select name="type_{{$lang->code}}" class="form-control">
                      <option value="icon" selected>Icon</option>
                      <option value="image">Image</option>
                  </select>
                  <p id="errtype_{{$lang->code}}" class="mb-0 text-danger em"></p>
              </div>

              {{-- Image Part --}}
              <div class="form-group" id="feature_image_{{$lang->code}}" style="display: none;">
                  <label for="">Image ** </label>
                  <br>
                  <div class="thumb-preview" id="thumbPreview1{{$lang->id}}">
                      <label for="chooseImage1{{$lang->id}}"><img src="{{asset('assets/admin/img/noimage.jpg')}}" alt="User Image"></label>
                  </div>
                  <br>
                  <br>

                  <input id="fileInput1{{$lang->id}}" disabled type="hidden" name="image_{{$lang->code}}">
                  <button id="chooseImage1{{$lang->id}}" class="choose-image btn btn-primary" type="button" data-multiple="false"
                          data-toggle="modal" data-target="#lfmModal1{{$lang->id}}">Choose Image</button>
                  <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
                  <p class="em text-danger mb-0" id="errimage_{{$lang->code}}"></p>
              </div>

            <div class="form-group" id="feature_icon_{{$lang->code}}">
              <label for="">Icon **</label>
              <div class="btn-group d-block">
                  <button type="button" class="btn btn-primary iconpicker-component"><i
                          class="fa fa-fw fa-heart"></i></button>
                  <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle"
                          data-selected="fa-car" data-toggle="dropdown">
                  </button>
                  <div class="dropdown-menu"></div>
              </div>
              <input id="inputIcon1{{$lang->code}}" type="hidden" name="icon_{{$lang->code}}" value="fas fa-heart">
              @if ($errors->has('icon_'.$lang->code))
                <p class="mb-0 text-danger">{{$errors->first('icon_'.$lang->code)}}</p>
              @endif
              <div class="mt-2">
                <small>NB: click on the dropdown sign to select a icon.</small>
              </div>
              <p id="erricon_{{$lang->code}}" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">Title **</label>
              <input type="text" class="form-control" name="title_{{$lang->code}}" placeholder="Enter title">
              <p id="errtitle_{{$lang->code}}" class="mb-0 text-danger em"></p>
            </div>
            @if ($be->theme_version != 'car')
                <div class="form-group">
                    <label>Color **</label>
                    <input type="text" class="jscolor form-control ltr" name="color_{{$lang->code}}" value="">
                    <p id="errcolor_{{$lang->code}}" class="mb-0 text-danger em"></p>
                </div>
            @endif
            <div class="form-group">
              <label for="">Serial Number **</label>
              <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}" value="" placeholder="Enter Serial Number">
              <p id="errserial_number_{{$lang->code}}" class="mb-0 text-danger em"></p>
              <p class="text-warning"><small>The higher the serial number is, the later the feature will be shown.</small></p>
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

  <!-- Edit Feature Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Edit Feature</h5>
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

      (function () {

          @foreach ($langs as $lang)
          $("select[name='type_{{$lang->code}}']").on('change', function () {
              let type = $(this).val();
              const curForm = $('#create-lang-{{$lang->code}}')
              if (type == 'image') {
                  $("#feature_icon_{{$lang->code}} input", curForm).attr('disabled', true);
                  $("#feature_icon_{{$lang->code}}", curForm).hide();
                  $("#feature_image_{{$lang->code}}", curForm).show();
                  $("#feature_image_{{$lang->code}} input", curForm).removeAttr('disabled');
              } else {
                  $("#feature_image_{{$lang->code}} input", curForm).attr('disabled', true);
                  $("#feature_image_{{$lang->code}}", curForm).hide();
                  $("#feature_icon_{{$lang->code}}", curForm).show();
                  $("#feature_icon_{{$lang->code}} input", curForm).removeAttr('disabled');
              }
          });

          @endforeach
          $("select[name^='type_']").trigger('change');
      })();

    $(document).ready(function() {
        @foreach ($langs as $lang)
        $('.icp').on('iconpickerSelected', function(event){
            $("#inputIcon1{{$lang->code}}").val($(".iconpicker-component").find('i').attr('class'));
        });
        @endforeach

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
            })
        });

    });
  </script>
@endsection
