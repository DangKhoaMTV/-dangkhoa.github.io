@extends('admin.layout')

@section('content')
<div class="page-header">
    <h4 class="page-title">Sliders</h4>
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
            <a href="#">Hero Section</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">Sliders</a>
        </li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card-title d-inline-block">Sliders</div>
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
                        <a href="#" class="btn btn-primary float-lg-right float-left" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i> Add Slider</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        @if (count($sliders) == 0)
                        <h3 class="text-center">NO SLIDER FOUND</h3>
                        @else
                        <div class="row">
                            @foreach ($sliders as $key => $slider)
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <a class="editbtn_url" data-url="{{route('admin.slider.edit-modal', $slider->id) . '?language=' . request()->input('language')}}" href="{{route('admin.slider.edit', $slider->id) . '?language=' . request()->input('language')}}" data-toggle="modal" data-target="#editModal">
                                            <img src="{{$slider->image!=''?asset('assets/front/img/sliders/' . $slider->image):asset('assets/admin/img/noimage.jpg')}}" alt="" style="width:100%;"></a>
                                    </div>
                                    <div class="card-footer text-center">
                                        <a class="btn btn-secondary btn-sm mr-2 editbtn_url" data-url="{{route('admin.slider.edit-modal', $slider->id) . '?language=' . request()->input('language')}}" href="{{route('admin.slider.edit', $slider->id) . '?language=' . request()->input('language')}}" data-toggle="modal" data-target="#editModal">
                                            <span class="btn-label">
                                                <i class="fas fa-edit"></i>
                                            </span>
                                            Edit
                                        </a>
                                        <form class="deleteform d-inline-block" action="{{route('admin.slider.delete')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="slider_id" value="{{$slider->id}}">
                                            <button type="submit" class="btn btn-danger btn-sm deletebtn">
                                                <span class="btn-label">
                                                    <i class="fas fa-trash"></i>
                                                </span>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Create Slider Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Slider</h5>
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
            <form class="modal-form" id="ajaxForm" action="{{route('admin.slider.store')}}" method="post">
                @csrf
                @if (!empty($langs))
                    <div class="tab-content">
                        @foreach ($langs as $lang)
                            <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}" id="create-lang-{{$lang->code}}">
                                @include('admin.sameContent')
                {{-- Image Part --}}
                <div class="form-group">
                    <label for="">Image ** </label>
                    <br>
                    <div class="thumb-preview" id="thumbPreview1{{$lang->id}}">
                        <label for="chooseImage1{{$lang->id}}"><img src="{{asset('assets/admin/img/noimage.jpg')}}" alt="Slider Image"></label>
                    </div>
                    <br>
                    <br>


                    <input id="fileInput1{{$lang->id}}" type="hidden" name="image_{{$lang->code}}">
                    <button id="chooseImage1{{$lang->id}}" class="choose-image btn btn-primary" type="button" data-multiple="false" data-toggle="modal" data-target="#lfmModal1{{$lang->id}}">Choose Image</button>


                    <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
                    <p class="em text-danger mb-0" id="errimage_{{$lang->code}}"></p>

                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Title </label>
                            <input type="text" class="form-control" name="title_{{$lang->code}}" value="" placeholder="Enter Title">
                            <p id="errtitle_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Title Font Size **</label>
                            <input type="number" class="form-control ltr" name="title_font_size_{{$lang->code}}" value="">
                            <p id="errtitle_font_size_{{$lang->code}}" class="em text-danger mb-0"></p>
                        </div>
                    </div>
                </div>


                @if ($be->theme_version == 'gym' || $be->theme_version == 'car' || $be->theme_version == 'cleaning')
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Bold Text </label>
                            <input type="text" class="form-control" name="bold_text_{{$lang->code}}" value="" placeholder="Enter Bold Text">
                            <p id="errbold_text_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Bold Text Font Size **</label>
                            <input type="number" class="form-control ltr" name="bold_text_font_size_{{$lang->code}}" value="">
                            <p id="errbold_text_font_size_{{$lang->code}}" class="em text-danger mb-0"></p>
                        </div>
                    </div>
                </div>
                @endif



                @if ($be->theme_version == 'cleaning')
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="">Bold Text Color **</label>
                            <input type="text" class="form-control jscolor" name="bold_text_color_{{$lang->code}}" value="#13287e">
                            <p id="errbold_text_color_{{$lang->code}}" class="em text-danger mb-0"></p>
                        </div>
                    </div>
                </div>
                @endif


                @if ($be->theme_version != 'cleaning')
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Text </label>
                            <input type="text" class="form-control" name="text_{{$lang->code}}" value="" placeholder="Enter Text">
                            <p id="errtext_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Text Font Size **</label>
                            <input type="number" class="form-control ltr" name="text_font_size_{{$lang->code}}" value="">
                            <p id="errtext_font_size_{{$lang->code}}" class="em text-danger mb-0"></p>
                        </div>
                    </div>
                </div>
                @endif


                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Button Text </label>
                            <input type="text" class="form-control" name="button_text_{{$lang->code}}" value="" placeholder="Enter Button Text">
                            <p id="errbutton_text_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Button Text Font Size **</label>
                            <input type="number" class="form-control ltr" name="button_text_font_size_{{$lang->code}}" value="">
                            <p id="errbutton_text_font_size_{{$lang->code}}" class="em text-danger mb-0"></p>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label for="">Button URL </label>
                    <input type="text" class="form-control ltr" name="button_url_{{$lang->code}}" value="" placeholder="Enter Button URL">
                    <p id="errbutton_url_{{$lang->code}}" class="mb-0 text-danger em"></p>
                </div>
                <div class="form-group">
                    <label for="">Serial Number **</label>
                    <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}" value="" placeholder="Enter Serial Number">
                    <p id="errserial_number_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    <p class="text-warning"><small>The higher the serial number is, the later the slider will be shown.</small></p>
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
<!-- Edit Slider Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Slider</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Loading...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="updateBtn" type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>
</div>
@foreach ($langs as $lang)
    <!-- Image LFM Modal -->
    <div class="modal fade lfm-modal" id="lfmModal1{{$lang->id}}" tabindex="-1" role="dialog" aria-labelledby="lfmModalTitle" aria-hidden="true">
        <i class="fas fa-times-circle"></i>
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <iframe src="{{url('laravel-filemanager')}}?serial=1{{$lang->id}}" style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
                </div>
            </div>
        </div>
    </div>
    <!-- Image LFM Modal -->
    <div class="modal fade lfm-modal" id="lfmModal3{{$lang->id}}" tabindex="-1" role="dialog" aria-labelledby="lfmModalTitle" aria-hidden="true">
        <i class="fas fa-times-circle"></i>
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <iframe src="{{url('laravel-filemanager')}}?serial=3{{$lang->id}}" style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
                </div>
            </div>
        </div>
    </div>
@endforeach
    @endsection

    @section('scripts')
    <script>
        $(document).ready(function() {

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
