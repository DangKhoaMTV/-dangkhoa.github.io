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
    <h4 class="page-title">Dinnings</h4>
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
            <a href="#">Dinning Page</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">Dinnings</a>
        </li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card-title d-inline-block">Dinnings</div>
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
                        <a href="#" class="btn btn-primary float-right btn-sm" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i> Add Dinning</a>
                        <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete" data-href="{{route('admin.dinning.bulk.delete')}}"><i class="flaticon-interface-5"></i> Delete</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        @if (count($dinnings) == 0)
                        <h3 class="text-center">NO SERVICE FOUND</h3>
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
                                        <th scope="col">Featured</th>
                                        <th scope="col">Serial Number</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>


                                <tbody>
                                    @foreach ($dinnings as $key => $dinning)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="bulk-check" data-val="{{$dinning->id}}">
                                        </td>
                                        <td><img src="{{$dinning->main_image!=''?asset('assets/front/img/dinnings/' . $dinning->main_image):asset('assets/admin/img/noimage.jpg')}}" alt="" width="70"></td>
                                        <td>{{strlen(convertUtf8($dinning->title)) > 100 ? convertUtf8(substr($dinning->title, 0, 100)) . '...' : convertUtf8($dinning->title)}}</td>

                                        <td>
                                            <form id="featureForm{{$dinning->id}}" class="d-inline-block" action="{{route('admin.dinning.feature')}}" method="post">
                                                @csrf
                                                <input type="hidden" name="dinning_id" value="{{$dinning->id}}">
                                                <select class="form-control {{$dinning->feature == 1 ? 'bg-success' : 'bg-danger'}}" name="feature" onchange="document.getElementById('featureForm{{$dinning->id}}').submit();">
                                                    <option value="1" {{$dinning->feature == 1 ? 'selected' : ''}}>Yes</option>
                                                    <option value="0" {{$dinning->feature == 0 ? 'selected' : ''}}>No</option>
                                                </select>
                                            </form>
                                        </td>

                                        <td>{{$dinning->serial_number}}</td>
                                    <td>
                                        <a class="btn btn-secondary btn-sm editbtn_url" data-url="{{route('admin.dinning.edit', $dinning->id) . '?language=' . request()->input('language')}}" href="{{route('admin.dinning.edit', $dinning->id) . '?language=' . request()->input('language')}}" data-toggle="modal" data-target="#editModal">
                                            <span class="btn-label">
                                                <i class="fas fa-edit"></i>
                                            </span>
                                            Edit
                                        </a>
                                        <form class="deleteform d-inline-block" action="{{route('admin.dinning.delete')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="dinning_id" value="{{$dinning->id}}">
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
<!-- Create Dinning Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Dinning</h5>
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
            <form id="ajaxForm" class="modal-form" action="{{route('admin.dinning.store')}}" method="POST">
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
                    <button id="chooseImage1{{$lang->id}}" class="choose-image btn btn-primary" type="button" data-multiple="false" data-toggle="modal" data-target="#lfmModal1{{$lang->id}}">Choose Image</button>

                    <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
                    <p class="em text-danger mb-0" id="errimage_{{$lang->code}}"></p>

                </div>
                <div class="form-group">
                    <label for="">Title **</label>
                    <input type="text" class="form-control" name="title_{{$lang->code}}" placeholder="Enter title" value="">
                    <p id="errtitle_{{$lang->code}}" class="mb-0 text-danger em"></p>
                </div>

                <div class="form-group">
                    <label for="">Summary **</label>
                    <textarea class="form-control" name="summary_{{$lang->code}}" placeholder="Enter summary" rows="3"></textarea>
                    <p id="errsummary_{{$lang->code}}" class="mb-0 text-danger em"></p>
                </div>

                <div class="form-group">
                    <label>Details Page **</label>
                    <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                            <input type="radio" name="details_page_status_{{$lang->code}}" data-lang="{{$lang->code}}" value="1" class="selectgroup-input" checked>
                            <span class="selectgroup-button">Enable</span>
                        </label>
                        <label class="selectgroup-item">
                            <input type="radio" name="details_page_status_{{$lang->code}}" data-lang="{{$lang->code}}" value="0" class="selectgroup-input">
                            <span class="selectgroup-button">Disable</span>
                        </label>
                    </div>
                    <p id="errdetails_page_status_{{$lang->code}}" class="mb-0 text-danger em"></p>
                </div>

                <div class="form-group" id="contentFg_{{$lang->code}}">
                    <label for="">Content **</label>
                    <textarea id="dinningContent_{{$lang->code}}" class="form-control summernote" name="content_{{$lang->code}}" data-height="300" placeholder="Enter content"></textarea>
                    <p id="errcontent_{{$lang->code}}" class="mb-0 text-danger em"></p>
                </div>

                    <div class="form-group">
                        <label for="">Menu PDF Link **</label>
                        <input type="text" class="form-control ltr" name="pdf_link_{{$lang->code}}" value="" placeholder="Enter Serial Number">
                        <p id="errpdf_link_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        <p class="text-warning"><small>Only Google Drive Link.</small></p>
                    </div>

                    <div class="form-group">
                        <label for="">Serial Number **</label>
                        <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}" value="" placeholder="Enter Serial Number">
                        <p id="errserial_number_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        <p class="text-warning"><small>The higher the serial number is, the later the dinning will be shown everywhere.</small></p>
                    </div>

                <div class="form-group">
                    <label>Meta Keywords</label>
                    <input class="form-control" name="meta_keywords_{{$lang->code}}" value="" placeholder="Enter meta keywords" data-role="tagsinput">
                    <p id="errmeta_keywords_{{$lang->code}}" class="mb-0 text-danger em"></p>
                </div>
                <div class="form-group">
                    <label>Meta Description</label>
                    <textarea class="form-control" name="meta_description_{{$lang->code}}" rows="5" placeholder="Enter meta description"></textarea>
                    <p id="errmeta_description_{{$lang->code}}" class="mb-0 text-danger em"></p>
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
<!-- Edit Dinning Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Dinning</h5>
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
    function toggleDetails() {
        $("input[name^='details_page_status']:checked").each(function (){
            let page = $(this);
            let form = page.closest('form');
            let lang = page.data('lang');
            let val = page.val();

            // if 'details page' is 'enable', then show 'content' & hide 'summary'
            if (val == 1) {
                $('div[id="contentFg_'+lang+'"]',form).show();
            }
            // if 'details page' is 'disable', then show 'summary' & hide 'content'
            else if (val == 0) {
                $('div[id="contentFg_'+lang+'"]',form).hide();
            }
        });
    }

    $("input[name^='details_page_status']").on('change', function() {
        toggleDetails();
    });
</script>

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
                    $("form.modal-form .summernote").each(function() {
                        $(this).siblings('.note-editor').find('.note-editable').addClass('rtl text-right');
                    });

                } else {
                    $("form.modal-form input, form.modal-form select, form.modal-form textarea").removeClass('rtl');
                    $("form.modal-form .summernote").siblings('.note-editor').find('.note-editable').removeClass('rtl text-right');
                }
            })
        });
    });
</script>
@endsection
