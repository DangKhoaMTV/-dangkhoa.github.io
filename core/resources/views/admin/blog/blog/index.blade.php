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
        <h4 class="page-title">Blogs</h4>
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
                <a href="#">Blog Page</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">Blogs</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">Blogs</div>
                        </div>
                        <div class="col-lg-3">
                            @if (!empty($langs))
                                <select name="language" class="form-control"
                                        onchange="window.location='{{url()->current() . '?language='}}'+this.value">
                                    <option value="" selected disabled>Select a Language</option>
                                    @foreach ($langs as $lang)
                                        <option
                                            value="{{$lang->code}}" {{$lang->code == request()->input('language') ? 'selected' : ''}}>{{$lang->name}}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
                            <a href="#" class="btn btn-primary float-right btn-sm" data-toggle="modal"
                               data-target="#createModal"><i class="fas fa-plus"></i> Add Blog</a>
                            <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                                    data-href="{{route('admin.blog.bulk.delete')}}"><i class="flaticon-interface-5"></i>
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($blogs) == 0)
                                <h3 class="text-center">NO BLOG FOUND</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                        <tr>
                                            <th scope="col">
                                                <input type="checkbox" class="bulk-check" data-val="all">
                                            </th>
                                            <th scope="col">Image</th>
                                            <th scope="col">Category</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Publish Date</th>
                                            <th scope="col">Serial Number</th>
                                            <th scope="col">Sidebar</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($blogs as $key => $blog)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="bulk-check" data-val="{{$blog->id}}">
                                                </td>
                                                <td><img src="{{$blog->main_image!=''?asset('assets/front/img/blogs/' . $blog->main_image):asset('assets/admin/img/noimage.jpg')}}"
                                                         alt="" width="80"></td>
                                                <td>{{convertUtf8($blog->bcategory->name)}}</td>
                                                <td>{{convertUtf8(strlen($blog->title)) > 30 ? convertUtf8(substr($blog->title, 0, 30)) . '...' : convertUtf8($blog->title)}}</td>
                                                <td>
                                                    @php
                                                        $date = \Carbon\Carbon::parse($blog->created_at);
                                                    @endphp
                                                    {{$date->translatedFormat('jS F, Y')}}
                                                </td>
                                                <td>{{$blog->serial_number}}</td>
                                                <td>
                                                    <form id="statusForm{{$blog->id}}" class="d-inline-block"
                                                          action="{{route('admin.blog.sidebar')}}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="blog_id" value="{{$blog->id}}">
                                                        <select class="form-control form-control-sm @if ($blog->sidebar == 1) bg-success @elseif ($blog->sidebar == 0) bg-danger @endif " name="sidebar" onchange="document.getElementById('statusForm{{$blog->id}}').submit();">
                                                            <option value="1" {{$blog->sidebar == 1 ? 'selected' : ''}}>
                                                                Enabled
                                                            </option>
                                                            <option value="0" {{$blog->sidebar == 0 ? 'selected' : ''}}>
                                                                Disabled
                                                            </option>
                                                        </select>
                                                    </form>
                                                </td>
                                                <td>
                                                    <a class="btn btn-secondary btn-sm editbtn_url" href="{{route('admin.blog.edit', $blog->id) . '?language=' . request()->input('language')}}" data-url="{{route('admin.blog.edit-modal', $blog->id) . '?language=' . request()->input('language')}}" data-toggle="modal" data-target="#editModal"> <span class="btn-label"> <i class="fas fa-edit"></i> </span> Edit </a>
                                                            <form class="deleteform d-inline-block"
                                                                  action="{{route('admin.blog.delete')}}" method="post">
                                                                @csrf
                                                                <input type="hidden" name="blog_id" value="{{$blog->id}}">
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

    <!-- Create Blog Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Blog</h5>
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
                    <form id="ajaxForm" class="modal-form" action="{{route('admin.blog.store')}}" method="POST">
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
                                    <label for="chooseImage1{{$lang->id}}"><img src="{{asset('assets/admin/img/noimage.jpg')}}" alt="User Image"></label>
                                </div>
                                <br>
                                <br>

                                <input id="fileInput1{{$lang->id}}" type="hidden" name="image_{{$lang->code}}">
                                <button id="chooseImage1{{$lang->id}}" class="choose-image btn btn-primary" type="button"
                                        data-multiple="false" data-toggle="modal" data-target="#lfmModal1{{$lang->id}}">Choose Image
                                </button>

                                <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
                                <p class="em text-danger mb-0" id="errimage{{ $lang->code}}"></p>

                            </div>

                            <div class="form-group">
                                <label for="">Title **</label>
                                <input type="text" class="form-control" name="title_{{ $lang->code}}" placeholder="Enter title" value="">
                                <p id="errtitle{{ $lang->code}}" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="">Category **</label>
                                <select id="bcategory" class="form-control" name="category_{{ $lang->code}}">
                                    <option value="" selected disabled>Select a category</option>
                                    @foreach ($bcats[$lang->code] as $key => $bcat)
                                        <option value="{{$bcat->id}}" data-assoc_id="{{$bcat->assoc_id}}">{{$bcat->name}}</option>
                                    @endforeach
                                </select>
                                <p id="errcategory_{{ $lang->code}}" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="">Content **</label>
                                <textarea id="blogContent" class="form-control summernote" name="content_{{ $lang->code}}" rows="8" cols="80"
                                          laceholder="Enter content"></textarea>
                                <p id="errcontent_{{ $lang->code}}" class="mb-0 text-danger em"></p>
                            </div>
                            <div class="form-group">
                                <label for="">Serial Number **</label>
                                <input type="number" class="form-control ltr" name="serial_number_{{ $lang->code}}" value=""
                                       placeholder="Enter Serial Number">
                                <p id="errserial_number_{{ $lang->code}}" class="mb-0 text-danger em"></p>
                                <p class="text-warning mb-0"><small>The higher the serial number is, the later the blog will
                                        be shown.</small></p>
                            </div>
                            <div class="form-group">
                                <label for="">Meta Keywords</label>
                                <input type="text" class="form-control" name="meta_keywords_{{ $lang->code}}" value="" data-role="tagsinput">
                            </div>
                            <div class="form-group">
                                <label for="">Meta Description</label>
                                <textarea type="text" class="form-control" name="meta_description_{{ $lang->code}}" rows="5"></textarea>
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
    <!-- Edit Portfolio Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Blog</h5>
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
        $(document).ready(function () {
            $("select[name='language_id']").on('change', function () {

                $("#bcategory").removeAttr('disabled');

                let langid = $(this).val();
                let url = "{{url('/')}}/admin/blog/" + langid + "/getcats";
                console.log(url);
                $.get(url, function (data) {
                    console.log(data);
                    let options = `<option value="" disabled selected>Select a category</option>`;
                    for (let i = 0; i < data.length; i++) {
                        options += `<option value="${data[i].id}">${data[i].name}</option>`;
                    }
                    $("#bcategory").html(options);

                });
            });

            // make input fields RTL
            $("select[name='language_id']").on('change', function () {
                $(".request-loader").addClass("show");

                let url = "{{url('/')}}/admin/rtlcheck/" + $(this).val();
                console.log(url);
                $.get(url, function (data) {
                    $(".request-loader").removeClass("show");
                    if (data == 1) {
                        $("form input").each(function () {
                            if (!$(this).hasClass('ltr')) {
                                $(this).addClass('rtl');
                            }
                        });
                        $("form select").each(function () {
                            if (!$(this).hasClass('ltr')) {
                                $(this).addClass('rtl');
                            }
                        });
                        $("form textarea").each(function () {
                            if (!$(this).hasClass('ltr')) {
                                $(this).addClass('rtl');
                            }
                        });
                        $("form .summernote").each(function () {
                            $(this).siblings('.note-editor').find('.note-editable').addClass('rtl text-right');
                        });

                    } else {
                        $("form input, form select, form textarea").removeClass('rtl');
                        $("form.modal-form .summernote").siblings('.note-editor').find('.note-editable').removeClass('rtl text-right');
                    }
                })
            });

            // translatable portfolios will be available if the selected language is not 'Default'
            $("#language").on('change', function () {
                let language = $(this).val();
                // console.log(language);
                if (language == 0) {
                    $("#translatable").attr('disabled', true);
                } else {
                    $("#translatable").removeAttr('disabled');
                }
            });
        });
        // console.log('loaded');
    </script>
@endsection
