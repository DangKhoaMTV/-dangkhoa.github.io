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
    <h4 class="page-title">Services</h4>
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
            <a href="#">Service Page</a>
        </li>
        <li class="separator">
            <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
            <a href="#">Services</a>
        </li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-2">
                        <div class="card-title d-inline-block">Services</div>
                    </div>
                    <div class="col-lg-3">
                        @if (!empty($langs))
                        <select name="language" class="form-control" onchange="window.location='{{url()->current() . '?language='}}'+this.value">
                            <option value="" disabled>Select a Language</option>
                            @foreach ($langs as $lang)
                            <option value="{{$lang->code}}" {{$lang->code == request()->input('language') ? 'selected' : ''}}>{{$lang->name}}</option>
                            @endforeach
                        </select>
                        @endif
                    </div>
                    <div class="col-lg-3">
                        <select name="type" class="form-control"
                                onchange="window.location= this.value">
                            <option value="" disabled>Select a Type</option>
                            <option selected value="{{url()->current() . '?language=' . request()->input('language') . '&type=all'}}" {{'all' == request()->input('type') ? 'selected' : ''}}>All</option>
                            <option value="{{url()->current() . '?language=' . request()->input('language') . '&type=indoor'}}" {{'indoor' == request()->input('type') ? 'selected' : ''}}>Indoor</option>
                            <option value="{{url()->current() . '?language=' . request()->input('language') . '&type=outdoor'}}" {{'outdoor' == request()->input('type') ? 'selected' : ''}}>Outdoor</option>
                        </select>
                    </div>
                    <div class="col-lg-3 offset-lg-1 mt-2 mt-lg-0">
                        <a href="#" class="btn btn-primary float-right btn-sm" data-toggle="modal" data-target="#createModal"><i class="fas fa-plus"></i> Add Service</a>
                        <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete" data-href="{{route('admin.service.bulk.delete')}}"><i class="flaticon-interface-5"></i> Delete</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        @if (count($services) == 0)
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
                                        <th scope="col">Type</th>
                                        @if (serviceCategory())
                                        <th scope="col">Category</th>
                                        @endif
                                        <th scope="col">Featured</th>
                                        <th scope="col">Serial Number</th>
                                        <th class="d-none" scope="col">Sidebar</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>


                                <tbody>
                                    @foreach ($services as $key => $service)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="bulk-check" data-val="{{$service->id}}">
                                        </td>
                                        <td><img class="bg-white" src="{{$service->main_image!=''?asset('assets/front/img/services/' . $service->main_image):asset('assets/admin/img/noimage.jpg')}}" alt="" width="70"></td>
                                        <td>{{strlen(convertUtf8($service->title)) > 100 ? convertUtf8(substr($service->title, 0, 100)) . '...' : convertUtf8($service->title)}}</td>
                                        <td>{{ucfirst($service->type)}}</td>
                                        @if (serviceCategory())
                                        <td>
                                            @if (!empty($service->scategory))
                                            {{convertUtf8($service->scategory->name)}}
                                            @endif
                                        </td>
                                        @endif

                                        <td>
                                            <form id="featureForm{{$service->id}}" class="d-inline-block" action="{{route('admin.service.feature')}}" method="post">
                                                @csrf
                                                <input type="hidden" name="service_id" value="{{$service->id}}">
                                                <select class="form-control {{$service->feature == 1 ? 'bg-success' : 'bg-danger'}}" name="feature" onchange="document.getElementById('featureForm{{$service->id}}').submit();">
                                                    <option value="1" {{$service->feature == 1 ? 'selected' : ''}}>Yes</option>
                                                    <option value="0" {{$service->feature == 0 ? 'selected' : ''}}>No</option>
                                                </select>
                                            </form>
                                        </td>

                                        <td>{{$service->serial_number}}</td>
                                        <td class="d-none">
                                            <form id="statusForm{{$service->id}}" class="d-inline-block" action="{{route('admin.service.sidebar')}}" method="post">
                                                @csrf
                                                <input type="hidden" name="service_id" value="{{$service->id}}">
                                                <select class="form-control form-control-sm
                                                @if ($service->sidebar == 1)
                                                bg-success
                                                @elseif ($service->sidebar == 0)
                                                bg-danger
                                                @endif
                                                " name="sidebar" onchange="document.getElementById('statusForm{{$service->id}}').submit();">
                                                <option value="1" {{$service->sidebar == 1 ? 'selected' : ''}}>Enabled</option>
                                                <option value="0" {{$service->sidebar == 0 ? 'selected' : ''}}>Disabled</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        <a class="btn btn-secondary btn-sm editbtn_url" data-url="{{route('admin.service.edit-modal', $service->id) . '?language=' . request()->input('language')}}" href="{{route('admin.service.edit', $service->id) . '?language=' . request()->input('language')}}" data-toggle="modal" data-target="#editModal">
                                            <span class="btn-label">
                                                <i class="fas fa-edit"></i>
                                            </span>
                                            Edit
                                        </a>
                                        <form class="deleteform d-inline-block" action="{{route('admin.service.delete')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="service_id" value="{{$service->id}}">
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
<!-- Create Service Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Service</h5>
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
            <form id="ajaxForm" class="modal-form" action="{{route('admin.service.store')}}" method="POST">
                @csrf
                @if (!empty($langs))
                    <div class="tab-content">
                        @foreach ($langs as $lang)
                            <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}" id="create-lang-{{$lang->code}}">

                @include('admin.sameContent')

                    <div class="form-group">
                        <label for="">Type **</label>
                        <select class="form-control" name="type_{{$lang->code}}" data-lang="{{$lang->code}}">
                            <option value="" selected disabled>Select a Type</option>
                            <option value="indoor">{{__('indoor-service')}}</option>
                            <option value="outdoor">{{__('outdoor-service')}}</option>
                        </select>
                        <p id="errtype_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>

                {{-- Image Part --}}
                <div class="form-group">
                    <label for="">Image ** </label>
                    <br>
                    <div class="thumb-preview" id="thumbPreview{{$lang->id}}">
                        <label for="chooseImage{{$lang->id}}"><img src="{{asset('assets/admin/img/noimage.jpg')}}" alt="User Image"></label>
                    </div>
                    <br>
                    <br>


                    <input id="fileInput{{$lang->id}}" type="hidden" name="image_{{$lang->code}}">
                    <button id="chooseImage{{$lang->id}}" class="choose-image btn btn-primary" type="button" data-multiple="false" data-toggle="modal" data-target="#lfmModal{{$lang->id}}">Choose Image</button>


                    <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
                    <p class="em text-danger mb-0" id="errimage_{{$lang->code}}"></p>

                </div>

                {{-- START: slider Part --}}
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="">Slider Images ** </label>
                            <br>
                            <div class="slider-thumbs" id="sliderThumbs2{{$lang->id}}">

                            </div>

                            <input id="fileInput2{{$lang->id}}" type="hidden" name="slider_{{$lang->code}}" value=""/>
                            <button id="chooseImage2{{$lang->id}}" class="choose-image btn btn-primary" type="button" data-multiple="true"
                                    data-toggle="modal" data-target="#lfmModal2{{$lang->id}}">Choose Images
                            </button>


                            <p class="text-warning mb-0">JPG, PNG, JPEG images are allowed</p>
                            <p id="errslider_{{$lang->code}}" class="mb-0 text-danger em"></p>

                        </div>
                    </div>
                </div>
                {{-- END: slider Part --}}


                <div class="form-group">
                    <label for="">Title **</label>
                    <input type="text" class="form-control" name="title_{{$lang->code}}" placeholder="Enter title" value="">
                    <p id="errtitle_{{$lang->code}}" class="mb-0 text-danger em"></p>
                </div>

                @if (serviceCategory())
                <div class="form-group">
                    <label for="">Category **</label>
                    <select id="scategory_{{$lang->code}}" data-langid="{{$lang->id}}" class="form-control" name="category_{{$lang->code}}" disabled>
                        <option value="" selected disabled>Select a category</option>
                    </select>
                    <p id="errcategory_{{$lang->code}}" class="mb-0 text-danger em"></p>
                </div>
                @endif

                <div class="form-group">
                    <label for="">Summary **</label>
                    <textarea class="form-control" name="summary_{{$lang->code}}" placeholder="Enter summary" rows="3"></textarea>
                    <p id="errsummary_{{$lang->code}}" class="mb-0 text-danger em"></p>
                </div>

                {{--Service Attribute--}}
                <div class="form-group" id="attributes_input_tg_{{$lang->code}}">
                    <label>Service Attribute **</label>
                    <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                            <input type="radio" name="service_attribute_status_{{$lang->code}}" data-lang="{{$lang->code}}" value="1" class="selectgroup-input" checked>
                            <span class="selectgroup-button">Enable</span>
                        </label>
                        <label class="selectgroup-item">
                            <input type="radio" name="service_attribute_status_{{$lang->code}}" data-lang="{{$lang->code}}" value="0" class="selectgroup-input">
                            <span class="selectgroup-button">Disable</span>
                        </label>
                    </div>
                    <p id="errservice_attribute_status_{{$lang->code}}" class="mb-0 text-danger em"></p>
                </div>

                <div class="row" id="attributes_tg_{{$lang->code}}">
                    <div class="col-lg-12 my-2" id="attribute_text_{{$lang->code}}">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Attribute **</span>
                            </div>
{{--                            <select name="service_attribute_{{$lang->code}}[0][attribute_id]" class="form-control">--}}
{{--                                @foreach($attributes[$lang->code] as $attribute)--}}
{{--                                    <option value="{{$attribute->id}}">{{$attribute->name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                            <textarea class="form-control ltr"--}}
{{--                                      name="service_attribute_{{$lang->code}}[0][text]"--}}
{{--                                      placeholder="Enter Service attribute text">--}}
{{--                            </textarea>--}}
{{--                            <span class="btn btn-xs btn-default" disabled="disabled" style="height: 25px"><i class="fas fa-minus"></i></span>--}}
                            <input type="text" class="form-control ltr" disabled placeholder="Add Service attribute">
                            <span class="btn btn-xs">
                                <a href="#" id="addAttribute_{{$lang->code}}" class="btn btn-xs btn-primary">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </span>
                        </div>
                        <p id="errservice_attribute_{{$lang->code}}.0.text" class="mb-0 text-danger em"></p>
                    </div>
{{--                    <div class="col-lg-12 my-2"><a href="#" id="addAttribute_{{$lang->code}}" class="btn btn-xs btn-primary pull-right">+</a></div>--}}
                </div>
                {{--End Service Attribute--}}

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
                    <textarea id="serviceContent_{{$lang->code}}" class="form-control summernote" name="content_{{$lang->code}}" data-height="300" placeholder="Enter content"></textarea>
                    <p id="errcontent_{{$lang->code}}" class="mb-0 text-danger em"></p>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="">Serial Number **</label>
                            <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}" value="" placeholder="Enter Serial Number">
                            <p id="errserial_number_{{$lang->code}}" class="mb-0 text-danger em"></p>
                            <p class="text-warning"><small>The higher the serial number is, the later the service will be shown everywhere.</small></p>
                        </div>
                    </div>
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
<!-- Edit Service Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Service</h5>
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
<div class="modal fade lfm-modal" id="lfmModal{{$lang->id}}" tabindex="-1" role="dialog" aria-labelledby="lfmModalTitle" aria-hidden="true">
    <i class="fas fa-times-circle"></i>
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <iframe src="{{url('laravel-filemanager')}}?serial={{$lang->id}}" style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>
<!-- Image LFM Modal -->
<div class="modal fade lfm-modal" id="lfmModal{{$lang->id}}9" tabindex="-1" role="dialog" aria-labelledby="lfmModalTitle" aria-hidden="true">
    <i class="fas fa-times-circle"></i>
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <iframe src="{{url('laravel-filemanager')}}?serial={{$lang->id}}9" style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Image LFM Modal -->
<div class="modal fade lfm-modal" id="lfmModal2{{$lang->id}}" tabindex="-1" role="dialog" aria-labelledby="lfmModalTitle"
     aria-hidden="true">
    <i class="fas fa-times-circle"></i>
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <iframe id="lfmIframe2{{$lang->id}}" src="{{url('laravel-filemanager')}}?serial=2{{$lang->id}}"
                        style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Image LFM Modal -->
<div class="modal fade lfm-modal" id="lfmModal{{$lang->id}}2" tabindex="-1" role="dialog" aria-labelledby="lfmModalTitle"
     aria-hidden="true">
    <i class="fas fa-times-circle"></i>
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <iframe id="lfmIframe{{$lang->id}}2" src="{{url('laravel-filemanager')}}?serial={{$lang->id}}2"
                        style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

<template id="attribute_temp_{{$lang->code}}">
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text">Attribute **</span>
        </div>
        <select name="service_attribute_{{$lang->code}}[_index][attribute_id]" data-index="_index" class="form-control">
            @foreach($attributes[$lang->code] as $attribute)
                <option value="{{$attribute->id}}" data-assoc_id="{{$attribute->assoc_id}}">{{$attribute->name}}</option>
            @endforeach
        </select>
        <textarea class="form-control ltr"
                  name="service_attribute_{{$lang->code}}[_index][text]"
                  placeholder="Enter Service attribute text"></textarea>
        <span onclick="removeServiceAttr(this);" style="height: 25px" class="btn btn-xs btn-danger"><i class="fas fa-minus"></i></span>
    </div>
    <p id="eerrservice_attribute_{{$lang->code}}._index.text" class="mb-0 text-danger em"></p>
</template>
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

    function toggleAttribute() {
        $("input[name^='service_attribute_status']:checked").each(function (){
            let page = $(this);
            let form = page.closest('form');
            let lang = page.data('lang');
            let val = page.val();

            // if 'details page' is 'enable', then show 'content' & hide 'summary'
            if (val == 1) {
                $('div[id="attributes_tg_'+lang+'"]',form).show();
            }
            // if 'details page' is 'disable', then show 'summary' & hide 'content'
            else if (val == 0) {
                $('div[id="attributes_tg_'+lang+'"]',form).hide();
            }
        });
    }

    $("input[name^='service_attribute_status']").on('change', function() {
        toggleAttribute();
    });

    function toggleType() {
        $("select[name^='type']").each(function (){
            let page = $(this);
            let form = page.closest('form');
            let lang = page.data('lang');
            let val = page.val();
            // if 'details page' is 'enable', then show 'content' & hide 'summary'
            if (val == 'indoor') {
                $('div[id="attributes_input_tg_'+lang+'"]',form).show();
            }
            // if 'details page' is 'disable', then show 'summary' & hide 'content'
            else if (val == 'outdoor') {
                $('input[name="service_attribute_status_'+lang+'"]').removeAttr("checked");
                $('input[name="service_attribute_status_'+lang+'"][value="0"]').prop("checked", true);
                $('div[id="attributes_tg_'+lang+'"]',form).hide();
                $('div[id="attributes_input_tg_'+lang+'"]',form).hide();
            }
        });
    }

    $("select[name^='type']").on('change', function() {
        toggleType();
    });

    var attIndexRemove = [];
    function removeServiceAttr(element) {
        let index = $(element).closest('.input-group').children('select').data('index');
        attIndexRemove.push(index)
        $(element).closest('.input-group').remove();
    }

    (function () {

        @foreach ($langs as $lang)

        //Add Attribute
        $("#addAttribute_{{$lang->code}}").on('click', function () {
            const count_attr = $('#create-lang-{{$lang->code}} #attribute_text_{{$lang->code}} .input-group').length;

            const clone = $('#attribute_temp_{{$lang->code}}').clone(true);
            const cloned = clone.html().replaceAll('_index', count_attr);

            $('#create-lang-{{$lang->code}} #attribute_text_{{$lang->code}}').append(cloned);
        });
        @endforeach
    })();

</script>

@if(serviceCategory())
<script>
    function loadCategories() {
        $("select[id^='scategory_']").removeAttr('disabled');
        $("select[id^='scategory_']").each(function (){
            let that = $(this);
            let langid = that.data('langid');
            let url = "{{url('/')}}/admin/service/" + langid + "/getcats";
            // console.log(url);
            $.get(url, function(data) {
                console.log(data);
                let options = `<option value="" disabled selected>Select a category</option>`;
                for (let i = 0; i < data.length; i++) {
                    options += `<option data-assoc_id="${data[i].assoc_id}" value="${data[i].id}">${data[i].name}</option>`;
                }
                that.html(options);

            });

        });

    }

    $(document).ready(function() {

        loadCategories();

        $("select[name='language_id']").on('change', function() {
            loadCategories();
        });

    });
</script>
@endif

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
