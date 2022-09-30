@extends('admin.layout')

@if(!empty($abs->language) && $abs->language->rtl == 1)
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
    <h4 class="page-title">Testimonials</h4>
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
            <a href="#">Testimonials</a>
        </li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">

        @if ($bex->home_page_pagebuilder == 0)
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-10">
                        <div class="card-title">Title & Subtitle</div>
                    </div>
                    <div class="col-lg-2">
                        @if (!empty($langs))
                        <select name="language" class="form-control" onchange="window.location='{{url()->current() . '?language='}}'+this.value">
                            <option value="" selected disabled>Select a Language</option>
                            @foreach ($langs as $lang)
                            <option value="{{$lang->code}}" {{$lang->code == request()->input('language') ? 'selected' : ''}}>{{$lang->name}}</option>
                            @endforeach
                        </select>
                        @endif
                    </div>
                </div>
            </div>
            <form class="" action="{{route('admin.testimonialtext.update', $lang_id)}}" method="post">
                @csrf
                <div class="card-body">
                    @if (!empty($langs))
                        <ul class="nav nav-tabs">
                            @foreach ($langs as $lang)
                                <li class="nav-item">
                                    <a class="nav-link {{$lang->code == request()->input('language') ? 'active' : ''}}" data-toggle="tab" href="#section-lang-{{$lang->code}}">{{$lang->name}}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                        <div class="tab-content">
                            @foreach ($langs as $lang)
                                <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}" id="section-lang-{{$lang->code}}">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Title **</label>
                                <input class="form-control" name="testimonial_section_title_{{$lang->code}}" value="{{$abs[$lang->code]->testimonial_title}}" placeholder="Enter Title">
                                @if ($errors->has('testimonial_section_title_'.$lang->code))
                                <p class="mb-0 text-danger">{{$errors->first('testimonial_section_title_'.$lang->code)}}</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Subtitle **</label>
                                <input class="form-control" name="testimonial_section_subtitle_{{$lang->code}}" value="{{$abs[$lang->code]->testimonial_subtitle}}" placeholder="Enter Subtitle">
                                @if ($errors->has('testimonial_section_subtitle_'.$lang->code))
                                <p class="mb-0 text-danger">{{$errors->first('testimonial_section_subtitle_'.$lang->code)}}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                                </div>
                            @endforeach
                        </div>
                </div>
                <div class="card-footer">
                    <div class="form">
                        <div class="form-group from-show-notify row">
                            <div class="col-12 text-center">
                                <button type="submit" id="displayNotif" class="btn btn-success">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @endif

        <div class="card">
            <div class="card-header">
                <div class="card-title d-inline-block">Testimonials</div>
                <a href="#" class="btn btn-primary float-right btn-sm" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i> Add Testimonial</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        @if (count($testimonials) == 0)
                        <h3 class="text-center">NO TESTIMONIAL FOUND</h3>
                        @else
                        <div class="table-responsive">
                            <table class="table table-striped mt-3">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Rank</th>
                                        <th scope="col">Channel</th>
                                        <th scope="col">Serial Number</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($testimonials as $key => $testimonial)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td><img src="{{$testimonial->image!=''?asset('assets/front/img/testimonials/' . $testimonial->image):asset('assets/admin/img/noimage.jpg')}}" alt="" width="40"></td>
                                        <td>{{convertUtf8($testimonial->name)}}</td>
                                        <td>{{$testimonial->rank}}</td>
                                        <td>{{$testimonial->channel}}</td>
                                        <td>{{$testimonial->serial_number}}</td>
                                        <td>
                                            <a class="btn btn-secondary btn-sm editbtn_url" data-url="{{route('admin.testimonial.edit-modal', $testimonial->id) . '?language=' . request()->input('language')}}" href="{{route('admin.testimonial.edit', $testimonial->id) . '?language=' . request()->input('language')}}" data-toggle="modal" data-target="#editModal">
                                                <span class="btn-label">
                                                    <i class="fas fa-edit"></i>
                                                </span>
                                                Edit
                                            </a>
                                            <form class="deleteform d-inline-block" action="{{route('admin.testimonial.delete')}}" method="post">
                                                @csrf
                                                <input type="hidden" name="testimonial_id" value="{{$testimonial->id}}">
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


<!-- Create Testimonial Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Testimonial</h5>
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
                <form id="ajaxForm" class="modal-form" action="{{route('admin.testimonial.store')}}" method="POST">
                    @csrf
                    <div class="tab-content">
                        @foreach ($langs as $lang)
                            <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}" id="create-lang-{{$lang->code}}">
                                @include('admin.sameContent')
                    {{-- Image Part --}}
                    <div class="form-group">
                        <label for="">Image ** </label>
                        <br>
                        <div class="thumb-preview" id="thumbPreview1{{$lang->id}}">
                            <label for="chooseImage1{{$lang->id}}"><img src="{{asset('assets/admin/img/noimage.jpg')}}" alt="Client"></label>
                        </div>
                        <br>
                        <br>


                        <input id="fileInput1{{$lang->id}}" type="hidden" name="image_{{$lang->code}}">
                        <button id="chooseImage1{{$lang->id}}" class="choose-image btn btn-primary" type="button" data-multiple="false" data-toggle="modal" data-target="#lfmModal1{{$lang->id}}">Choose Image</button>


                        <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
                        <p class="em text-danger mb-0" id="errimage"></p>

                    </div>

                    <div class="form-group">
                        <label for="">Comment **</label>
                        <textarea class="form-control" name="comment_{{$lang->code}}" rows="3" cols="80" placeholder="Enter comment"></textarea>
                        <p id="errcomment" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Name **</label>
                        <input type="text" class="form-control" name="name_{{$lang->code}}" value="" placeholder="Enter name">
                        <p id="errname" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Rank **</label>
                        <input type="text" class="form-control" name="rank_{{$lang->code}}" value="" placeholder="Enter rank">
                        <p id="errrank" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Serial Number **</label>
                        <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}" value="" placeholder="Enter Serial Number">
                        <p id="errserial_number" class="mb-0 text-danger em"></p>
                        <p class="text-warning"><small>The higher the serial number is, the later the testimonial will be shown.</small></p>
                    </div>
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="submitBtn" type="button" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>
<!-- Edit Testimonial Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Testimonial</h5>
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
                    $("form.modal-form input").each(function() {
                        if (!$(this).hasClass('ltr')) {
                            $(this).addClass('rtl');
                        }
                    });
                    $("form.modal-form select").each(function() {
                        if (!$(this).hasClass('ltr')) {
                            $(this).addClass('rtl');
                        }
                    });
                    $("form.modal-form textarea").each(function() {
                        if (!$(this).hasClass('ltr')) {
                            $(this).addClass('rtl');
                        }
                    });
                    $("form.modal-form .nicEdit-main").each(function() {
                        $(this).addClass('rtl text-right');
                    });

                } else {
                    $("form.modal-form input, form.modal-form select, form.modal-form textarea").removeClass('rtl');
                    $("form.modal-form .nicEdit-main").removeClass('rtl text-right');
                }
            })
        });
    });
</script>
@endsection
