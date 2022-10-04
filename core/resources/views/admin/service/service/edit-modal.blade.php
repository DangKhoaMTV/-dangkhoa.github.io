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
<form id="ajaxEditForm" class="" action="{{route('admin.service.update')}}" method="post">
    @csrf
    @if (!empty($langs))
        <div class="tab-content">
            @foreach ($langs as $lang)
                <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}"
                     id="edit-lang-{{$lang->code}}">
                    @include('admin.sameContent')

                @if($service[$lang->code]->id==0)
                        <div class="form-group">
                            <label class="" for="">Choose association **</label>
                            <select class="form-control select2" name="service_assoc_id_{{$lang->code}}">
                                <option value="" selected>Select a service</option>
                                @foreach ($scates[$lang->code] as $scate)
                                    <option value="{{$scate->id}}">[{{$scate->id}}-{{$scate->assoc_id}}
                                        ] {{$scate->title}}</option>
                                @endforeach
                            </select>
                            <p id="errservice_assoc_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                    @else
                        <input type="hidden" name="service_id_{{$lang->code}}" value="{{$service[$lang->code]->id}}">
                    @endif

                        <div class="form-group">
                            <label for="">Type **</label>
                            <select class="form-control" name="type_{{$lang->code}}" data-lang="{{$lang->code}}">
                                <option value="" disabled>Select a Type</option>
                                <option {{$service[$lang->code]->type == 'indoor' ? 'selected' : ''}} value="indoor">{{__('indoor-service')}}</option>
                                <option {{$service[$lang->code]->type == 'outdoor' ? 'selected' : ''}} value="outdoor">{{__('outdoor-service')}}</option>
                            </select>
                            <p id="eerrtype_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>

                    {{-- Image Part --}}
                    <div class="form-group">
                        <label for="">Image ** </label>
                        <br>
                        <div class="thumb-preview" id="thumbPreview{{$lang->id}}9">
                            <label for="chooseImage{{$lang->id}}9"><img
                                    src="{{ $service[$lang->code]->main_image!=''? asset('assets/front/img/services/' . $service[$lang->code]->main_image): asset('assets/admin/img/noimage.jpg') }}"
                                    alt="User Image"></label>
                        </div>
                        <br>
                        <br>


                        <input id="fileInput{{$lang->id}}9" type="hidden" name="image_{{$lang->code}}">
                        <button id="chooseImage{{$lang->id}}9" class="choose-image btn btn-primary" type="button"
                                data-multiple="false" data-toggle="modal" data-target="#lfmModal{{$lang->id}}9">Choose
                            Image
                        </button>


                        <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
                        <p class="em text-danger mb-0" id="eerrimage_{{$lang->code}}"></p>


                    </div>

                    {{-- START: slider Part --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="">Slider Images ** </label>
                                <br>
                                <div class="slider-thumbs" id="sliderThumbs{{$lang->id}}2">

                                </div>

                                <input id="fileInput{{$lang->id}}2" type="hidden" name="slider_{{$lang->code}}" value="" />
                                <button id="chooseImage{{$lang->id}}2" class="choose-image btn btn-primary" type="button" data-multiple="true" data-toggle="modal" data-target="#lfmModal{{$lang->id}}2">Choose Images</button>


                                <p class="text-warning mb-0">JPG, PNG, JPEG images are allowed</p>
                                <p id="eerrslider_{{$lang->code}}" class="mb-0 text-danger em"></p>

                                <!-- slider LFM Modal -->
                            </div>
                        </div>
                    </div>
                    {{-- END: slider Part --}}

                    <div class="form-group">
                        <label for="">Title **</label>
                        <input type="text" class="form-control" name="title_{{$lang->code}}"
                               value="{{$service[$lang->code]->title}}" placeholder="Enter title">
                        <p id="eerrtitle_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>

                    @if (serviceCategory())
                        <div class="form-group">
                            <label for="">Category **</label>
                            <select class="form-control" name="category_{{$lang->code}}">
                                <option value="" selected disabled>Select a category</option>
                                @foreach ($ascats[$lang->code] as $key => $ascat)
                                    <option
                                        value="{{$ascat->id}}" {{$ascat->id == $service[$lang->code]->scategory_id ? 'selected' : ''}}>{{$ascat->name}}</option>
                                @endforeach
                            </select>
                            <p id="eerrcategory_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="">Summary **</label>
                        <textarea class="form-control" name="summary_{{$lang->code}}" placeholder="Enter summary"
                                  rows="3">{{$service[$lang->code]->summary}}</textarea>
                        <p id="eerrsummary_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>

                        {{--Service Attribute--}}
                        <div class="form-group" id="attributes_input_tg_{{$lang->code}}">
                            <label>Service Attribute **</label>
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="radio" {{$service[$lang->code]->service_attribute_status == 1 ? 'checked' : ''}} name="service_attribute_status_{{$lang->code}}" data-lang="{{$lang->code}}" value="1" class="selectgroup-input" checked>
                                    <span class="selectgroup-button">Enable</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input {{$service[$lang->code]->service_attribute_status == 0 ? 'checked' : ''}} type="radio" name="service_attribute_status_{{$lang->code}}" data-lang="{{$lang->code}}" value="0" class="selectgroup-input">
                                    <span class="selectgroup-button">Disable</span>
                                </label>
                            </div>
                            <p id="eerrservice_attribute_status_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>

                        <div class="row" id="attributes_tg_{{$lang->code}}">
                            <div class="col-lg-12 my-2" id="eattribute_text_{{$lang->code}}">

                                @if(count($sattributes[$lang->code]) > 0)
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Attribute **</span>
                                        </div>
                                        <input type="text" class="form-control ltr" disabled placeholder="Add Service attribute">
                                        <span class="btn btn-xs">
                                            <a href="#" id="eaddAttribute_{{$lang->code}}" class="btn btn-xs btn-primary">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        </span>
                                    </div>

                                    @foreach($sattributes[$lang->code] as $key => $sattribute)
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Attribute **</span>
                                            </div>
                                            <select name="service_attribute_{{$lang->code}}[{{$key + 1}}][attribute_id]" data-index="{{$key + 1}}" class="form-control">
                                                @foreach($attributes[$lang->code] as $attribute)
                                                    <option {{$sattribute->attribute_id == $attribute->id ? 'selected' : ''}} value="{{$attribute->id}}" data-assoc_id="{{$attribute->assoc_id}}">{{$attribute->name}}</option>
                                                @endforeach
                                            </select>
                                            <textarea class="form-control ltr" name="service_attribute_{{$lang->code}}[{{$key + 1}}][text]" placeholder="Enter Service attribute text">{{$sattribute->text}}</textarea>
                                            <span class="btn btn-xs btn-danger" style="height: 25px" onclick="removeServiceAttr(this);">
                                                <i class="fas fa-minus"></i>
                                            </span>
                                        </div>
                                        <p id="eerrservice_attribute_{{$lang->code}}.{{$key + 1}}.text" class="mb-0 text-danger em"></p>
                                    @endforeach
                                @else
                                    <div class="input-group" id="attributes_tg_{{$lang->code}}">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Attribute **</span>
                                        </div>
{{--                                        <select name="service_attribute_{{$lang->code}}[0][attribute_id]" class="form-control">--}}
{{--                                            @foreach($attributes[$lang->code] as $attribute)--}}
{{--                                                <option value="{{$attribute->id}}">{{$attribute->name}}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                        <textarea class="form-control ltr" name="service_attribute_{{$lang->code}}[0][text]"--}}
{{--                                                  placeholder="Enter Service attribute text"></textarea>--}}
{{--                                        <span class="btn btn-xs btn-default" style="height: 25px"><i class="fas fa-minus"></i></span>--}}
                                        <input type="text" class="form-control ltr" disabled placeholder="Add Service attribute">
                                        <span class="btn btn-xs">
                                            <a href="#" id="eaddAttribute_{{$lang->code}}" class="btn btn-xs btn-primary">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        </span>
                                    </div>
                                    <p id="eerrservice_attribute_{{$lang->code}}.0.text" class="mb-0 text-danger em"></p>
                                @endif

                            </div>
{{--                            <div class="col-lg-12 my-2"><a href="#" id="eaddAttribute_{{$lang->code}}" class="btn btn-xs btn-primary pull-right">+</a></div>--}}
                        </div>
                        {{--End Service Attribute--}}

                    <div class="form-group">
                        <label>Details Page **</label>
                        <div class="selectgroup w-100">
                            <label class="selectgroup-item">
                                <input type="radio" name="details_page_status_{{$lang->code}}"
                                       data-lang="{{$lang->code}}" value="1"
                                       class="selectgroup-input" {{$service[$lang->code]->details_page_status == 1 ? 'checked' : ''}}>
                                <span class="selectgroup-button">Enable</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="radio" name="details_page_status_{{$lang->code}}"
                                       data-lang="{{$lang->code}}" value="0"
                                       class="selectgroup-input" {{$service[$lang->code]->details_page_status == 0 ? 'checked' : ''}}>
                                <span class="selectgroup-button">Disable</span>
                            </label>
                        </div>
                        <p id="eerrdetails_page_status_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group" id="contentFg_{{$lang->code}}">
                        <label for="">Content **</label>
                        <textarea id="serviceContent" class="form-control summernote" name="content_{{$lang->code}}"
                                  data-height="300"
                                  placeholder="Enter content">{{replaceBaseUrl($service[$lang->code]->content)}}</textarea>
                        <p id="eerrcontent_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Serial Number **</label>
                        <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}"
                               value="{{$service[$lang->code]->serial_number}}" placeholder="Enter Serial Number">
                        <p id="eerrserial_number_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        <p class="text-warning"><small>The higher the serial number is, the later the service will be
                                shown everywhere.</small></p>
                    </div>
                    <div class="form-group">
                        <label>Meta Keywords</label>
                        <input class="form-control" name="meta_keywords_{{$lang->code}}"
                               value="{{$service[$lang->code]->meta_keywords}}" placeholder="Enter meta keywords"
                               data-role="tagsinput">
                        @if ($errors->has('meta_keywords_'.$lang->code))
                            <p class="mb-0 text-danger">{{$errors->first('meta_keywords_'.$lang->code)}}</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Meta Description</label>
                        <textarea class="form-control" name="meta_description_{{$lang->code}}" rows="5"
                                  placeholder="Enter meta description">{{$service[$lang->code]->meta_description}}</textarea>
                        @if ($errors->has('meta_description_'.$lang->code))
                            <p class="mb-0 text-danger">{{$errors->first('meta_description_'.$lang->code)}}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</form>
<script>
    $("input[name^='details_page_status']").off('change').on('change', function () {
        toggleDetails();
    });

    $("input[name^='service_attribute_status']").on('change', function() {
        toggleAttribute();
    });

    $("select[name^='type']").on('change', function() {
        toggleType();
    });

    (function () {
        toggleDetails();

        toggleAttribute();

        toggleType();

        @foreach ($langs as $lang)
        //Add Attribute
        $("#edit-lang-{{$lang->code}} #eaddAttribute_{{$lang->code}}").on('click', function () {
            const count_attr = $('#edit-lang-{{$lang->code}} #eattribute_text_{{$lang->code}} .input-group').length;

            const clone = $('#attribute_temp_{{$lang->code}}').clone(true);
            const cloned = clone.html().replaceAll('_index', count_attr).replaceAll('errproduct_attribute_', 'eerrproduct_attribute_');

            $('#edit-lang-{{$lang->code}} #eattribute_text_{{$lang->code}}').append(cloned);
        });

        $('#lfmModal{{$lang->id}}2 iframe').attr('src', "{{url('laravel-filemanager')}}?serial={{$lang->id}}2&service={{$service[$lang->code]->id}}");
        @endforeach
    })();

    var attIndexRemove = [];
    function removeServiceAttr(element) {
        let index = $(element).closest('.input-group').children('select').data('index');
        attIndexRemove.push(index)
        $(element).closest('.input-group').remove();
    }

</script>
