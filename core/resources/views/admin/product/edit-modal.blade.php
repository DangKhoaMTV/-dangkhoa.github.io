@php
    $type = request()->input('type');
@endphp
@if (!empty($langs))
    <ul class="nav nav-tabs">
        @foreach ($langs as $lang)
            <li class="nav-item">
                <a class="nav-link {{$lang->code == request()->input('language') ? 'active' : ''}}"
                   data-toggle="tab"
                   href="#edit-lang-{{$lang->code}}">{{$lang->name}}</a>
            </li>
        @endforeach
    </ul>
@endif
{{-- Featured image upload end --}}
<form id="ajaxEditForm" class="" action="{{route('admin.product.update')}}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="type" value="{{request()->input('type')}}">
    @if (!empty($langs))
        <div class="tab-content">
            @foreach ($langs as $lang)
                <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}" id="edit-lang-{{$lang->code}}">
                    @if($product[$lang->code]->id==0)
                        <div class="form-group">
                            <label class="" for="">Choose association **</label>
                            <select class="form-control select2"
                                    name="product_assoc_id_{{$lang->code}}">
                                <option value="" selected>Select a blog</option>
                                @foreach ($pcates[$lang->code] as $pcate)
                                    <option value="{{$pcate->id}}">[{{$pcate->id}} - {{$pcate->assoc_id}}] {{$pcate->title}}</option>
                                @endforeach
                            </select>
                            <p id="errproduct_assoc_id_{{$lang->code}}"
                               class="mb-0 text-danger em"></p>
                        </div>
                    @else
                        <input type="hidden"  name="product_id_{{$lang->code}}" value="{{$product[$lang->code]->id}}">
                    @endif

                    @include('admin.sameContent')

                    {{-- START: Featured Image --}}
                    <div class="form-group">
                        <label for="">Featured Image ** </label>
                        <br>
                        <div class="thumb-preview" id="thumbPreview3{{$lang->id}}">
                            <label for="chooseImage3{{$lang->id}}"><img src="{{ $product[$lang->code]->feature_image != '' ? asset('assets/front/img/product/featured/' . $product[$lang->code]->feature_image) : asset('assets/admin/img/noimage.jpg') }}" alt="Feature Image"></label>
                        </div>
                        <br>
                        <br>

                        <input id="fileInput3{{$lang->id}}" type="hidden" name="featured_image_{{$lang->code}}">
                        <button id="chooseImage3{{$lang->id}}" class="choose-image btn btn-primary" type="button" data-multiple="false" data-toggle="modal" data-target="#lfmModal3{{$lang->id}}">Choose Image</button>

                        <p class="text-warning mb-0">JPG, PNG, JPEG images are allowed</p>
                        <p id="errfeatured_image_{{$lang->code}}" class="mb-0 text-danger em"></p>

                    </div>
                    {{-- END: Featured Image --}}

                    {{-- START: slider Part --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="">Slider Images ** </label>
                                <br>
                                <button id="clearAllImage4{{$lang->id}}" data-serial="4{{$lang->id}}" class="clear-image btn btn-danger"
                                        type="button">Clear Images
                                </button>
                                <div class="slider-thumbs" id="sliderThumbs4{{$lang->id}}">

                                </div>

                                <input id="fileInput4{{$lang->id}}" type="hidden" name="slider_{{$lang->code}}" value="" />
                                <button id="chooseImage4{{$lang->id}}" class="choose-image btn btn-primary" type="button" data-multiple="true" data-toggle="modal" data-target="#lfmModal4{{$lang->id}}">Choose Images</button>


                                <p class="text-warning mb-0">JPG, PNG, JPEG images are allowed</p>
                                <p id="errslider_{{$lang->code}}" class="mb-0 text-danger em"></p>

                                <!-- slider LFM Modal -->
                            </div>
                        </div>
                    </div>
                    {{-- END: slider Part --}}

                    <div class="row">

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">Status **</label>
                                <select class="form-control ltr" id="edit_status_{{$lang->code}}" name="status_{{$lang->code}}">
                                    <option value="" selected disabled>Select a status</option>
                                    <option value="1" {{$product[$lang->code]->status == 1 ? 'selected' : ''}}>Show</option>
                                    <option value="0" {{$product[$lang->code]->status == 0 ? 'selected' : ''}}>Hide</option>
                                </select>
                                <p id="errstatus_{{$lang->code}}" class="mb-0 text-danger em"></p>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="category">Category **</label>
                                <select  class="form-control categoryData" id="edit_category_id_{{$lang->code}}" name="category_id_{{$lang->code}}" id="category_{{$lang->code}}">
                                    <option value="" selected disabled>Select a category</option>
                                    @foreach ($categories[$lang->code] as $categroy)
                                        <option value="{{$categroy->id}}" {{$product[$lang->code]->category_id == $categroy->id ? 'selected' : ''}} data-assoc_id="{{$categroy->assoc_id}}">
                                            {{$categroy->name}}
                                        </option>
                                    @endforeach
                                </select>
                                <p id="errcategory_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <!--Them danh muc Home-->
                    

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="">Title **</label>
                                <input type="text" class="form-control" id="edit_title_{{$lang->code}}" name="title_{{$lang->code}}"  placeholder="Enter title" value="{{$product[$lang->code]->title}}">
                                <p id="errtitle_{{$lang->code}}" class="mb-0 text-danger em"></p>
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-lg-6">
                            @if ($product[$lang->code]->type == 'physical' || $type == 'physical')
                                <div class="form-group">
                                    <label for="">Stock Product **</label>
                                    <input type="number" class="form-control ltr" name="stock_{{$lang->code}}"  placeholder="Enter Product Stock" value="{{$product[$lang->code]->stock}}">
                                    <p id="errstock_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                </div>
                            @endif

                            @if ($product[$lang->code]->type == 'digital' || $type == 'digital')
                                <div class="form-group d-none">
                                    <label for="">Type **</label>
                                    <select name="file_type_{{$lang->code}}" class="form-control" id="fileType_{{$lang->code}}" onchange="toggleFileUpload();">
                                        <option value="upload" {{!empty($product[$lang->code]->download_file) ? 'selected' : ''}}>File Upload</option>
                                        <option value="link" {{!empty($product[$lang->code]->download_link) ? 'selected' : ''}}>File Download Link</option>
                                    </select>
                                    <p id="errfile_type_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($product[$lang->code]->type == 'digital' || $type == 'digital')
                        <div class="row">
                            <div class="col-12">
                                <div id="downloadFile_{{$lang->code}}" class="form-group d-none">
                                    <label for="">Downloadable File **</label>
                                    <br>
                                    <input disabled name="download_file_{{$lang->code}}" type="file">
                                    <p class="mb-0 text-warning">Only zip file is allowed.</p>
                                    <p id="errdownload_file_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                </div>
                                <div id="downloadLink" class="form-group">
                                    <label for="">Link **</label>
                                    <input id="edit_download_link_{{$lang->code}}" name="download_link_{{$lang->code}}" type="text" class="form-control" value="{{$product[$lang->code]->download_link}}">
                                    <p id="errdownload_link_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                        </div>
                    @endif

                            <div class="col-lg-12 my-2" id="eattribute_text_{{$lang->code}}">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Attribute **</span>
                                    </div>
                                    <input type="text" class="form-control ltr" disabled placeholder="Add Your Product attribute">
                                    <span class="btn btn-xs">
                                        <a href="#" id="eaddAttribute_{{$lang->code}}" class="btn btn-xs btn-primary">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </span>
                                </div>

                                @foreach($pattributes[$lang->code] as $key => $pattribute)
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Attribute **</span>
                                        </div>
                                        <select name="product_attribute_{{$lang->code}}[{{$key + 1}}][attribute_id]" data-index="{{$key + 1}}" class="form-control">
                                            @foreach($attributes[$lang->code] as $attribute)
                                                <option {{$pattribute->attribute_id == $attribute->id ? 'selected' : ''}} value="{{$attribute->id}}" data-assoc_id="{{$attribute->assoc_id}}" >
                                                    {{$attribute->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="text" class="form-control ltr" name="product_attribute_{{$lang->code}}[{{$key + 1}}][text]" value="{{$pattribute->text}}"
                                               placeholder="Enter Product attribute text">
                                        <span class="btn btn-xs btn-danger" onclick="removeProductAttr(this);" style="height: 25px">
                                            <i class="fas fa-minus"></i>
                                        </span>
                                    </div>
                                    <p id="eerrproduct_attribute_{{$lang->code}}.{{$key + 1}}.text" class="mb-0 text-danger em"></p>
                                @endforeach
                            </div>

                    <div class="row">
                        @if ($product[$lang->code]->type == 'physical' || $type == 'physical')
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for=""> Product Sku **</label>
                                    <input type="text" class="form-control ltr"   name="sku_{{$lang->code}}"  placeholder="Enter Product sku" value="{{$product[$lang->code]->sku}}">
                                    <p id="errsku_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                        @endif
                        <div class="{{($product[$lang->code]->type == ''  || $type == 'physical' ) ? 'col-lg-6' : 'col-lg-12'}}">
                            <div class="form-group">
                                <label for="">Tags </label>
                                <input type="text" class="form-control" id="edit_tags_{{$lang->code}}"  name="tags_{{$lang->code}}" value="{{$product[$lang->code]->tags}}" data-role="tagsinput" placeholder="Enter tags">
                                <p id="errtags_{{$lang->code}}" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for=""> Current Price (in {{$abx->base_currency_text}}) **</label>
                                    <input type="number" class="form-control ltr" id="current_price_{{$lang->code}}" name="current_price_{{$lang->code}}" value="{{$product[$lang->code]->current_price}}"  placeholder="Enter Current Price">
                                    <p id="errcurrent_price_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Previous Price (in {{$abx->base_currency_text}})</label>
                                    <input type="number" class="form-control ltr" id="previous_price_{{$lang->code}}" name="previous_price_{{$lang->code}}" value="{{$product[$lang->code]->previous_price}}" placeholder="Enter Previous Price">
                                    <p id="errprevious_price_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                        </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="summary">Summary</label>
                                <textarea name="summary_{{$lang->code}}" id="summary_{{$lang->code}}" class="form-control" rows="4" placeholder="Enter Product Summary">{{$product[$lang->code]->summary}}</textarea>
                                <p id="errsubmission_date_{{$lang->code}}" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="">Description</label>
                                <textarea class="form-control summernote" id="description_{{$lang->code}}" name="description_{{$lang->code}}" placeholder="Enter description" data-height="300">{{replaceBaseUrl($product[$lang->code]->description)}}</textarea>
                                <p id="errdescription_{{$lang->code}}" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Meta Keywords</label>
                                <input class="form-control" id="meta_keywords_{{$lang->code}}" name="meta_keywords_{{$lang->code}}" value="{{$product[$lang->code]->meta_keywords}}" placeholder="Enter meta keywords" data-role="tagsinput">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Meta Description</label>
                                <textarea class="form-control" id="meta_description_{{$lang->code}}" name="meta_description_{{$lang->code}}" rows="5" placeholder="Enter meta description">{{$product[$lang->code]->meta_description}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</form>

@if($product[$lang->code]->type == 'digital' || $type == 'digital')
<script>
    function toggleFileUpload() {
        @foreach ($langs as $lang)
            let type_{{$lang->code}} = $("select[name='file_type_{{$lang->code}}']").val();
            if (type_{{$lang->code}} == 'link') {
                $("#downloadFile_{{$lang->code}} input").attr('disabled', true);
                $("#downloadFile_{{$lang->code}}").hide();
                $("#downloadLink_{{$lang->code}}").show();
                $("#downloadLink_{{$lang->code}} input").removeAttr('disabled');
            } else {
                $("#downloadLink_{{$lang->code}} input").attr('disabled', true);
                $("#downloadLink_{{$lang->code}}").hide();
                $("#downloadFile_{{$lang->code}}").show();
                $("#downloadFile_{{$lang->code}} input").removeAttr('disabled');
            }
        @endforeach
    }

    (function () {
        toggleFileUpload();
    })();
</script>
@endif

{{-- dropzone --}}
<script>
    // myDropzone is the configuration for the element that has an id attribute
    // with the value my-dropzone (or myDropzone)
    Dropzone.options.myDropzone = {
        acceptedFiles: '.png, .jpg, .jpeg',
        url: "",
        success : function(file, response){
            console.log(response.file_id);

            // Create the remove button
            var removeButton = Dropzone.createElement("<button class='rmv-btn'><i class='fa fa-times'></i></button>");


            // Capture the Dropzone instance as closure.
            var _this = this;

            // Listen to the click event
            removeButton.addEventListener("click", function(e) {
                // Make sure the button click doesn't submit the form:
                e.preventDefault();
                e.stopPropagation();

                _this.removeFile(file);

                rmvimg(response.file_id);
            });

            // Add the button to the file preview element.
            file.previewElement.appendChild(removeButton);

            var content = {};

            content.message = 'Slider images added successfully!';
            content.title = 'Success';
            content.icon = 'fa fa-bell';

            $.notify(content,{
                type: 'success',
                placement: {
                    from: 'top',
                    align: 'right'
                },
                time: 1000,
                delay: 0,
            });
        }
    };

    function rmvimg(fileid) {
        // If you want to the delete the file on the server as well,
        // you can do the AJAX request here.

        $.ajax({
            url: "",
            type: 'POST',
            data: {
                _token: "{{csrf_token()}}",
                fileid: fileid
            },
            success: function(data) {
                var content = {};

                content.message = 'Slider image deleted successfully!';
                content.title = 'Success';
                content.icon = 'fa fa-bell';

                $.notify(content,{
                    type: 'success',
                    placement: {
                        from: 'top',
                        align: 'right'
                    },
                    time: 1000,
                    delay: 0,
                });
            }
        });

    }
    $('button[id^="clearAllImage"]').click(function (){
        var serial = $(this).data('serial');
        document.getElementById('lfmIframe'+serial).contentWindow.clearImg();
    });
    $( "#code" ).on('shown', function(){
        alert("I want this to appear after the modal has opened!");
    });
</script>


<script>
    var attIndexRemove = [];
    function removeProductAttr(element) {
        let index = $(element).closest('.input-group').children('select').data('index');
        attIndexRemove.push(index)
        $(element).closest('.input-group').remove();
    }

    (function () {
        @foreach ($langs as $lang)

        //Add Attribute
        $("#edit-lang-{{$lang->code}} #eaddAttribute_{{$lang->code}}").on('click', function () {
            const count_attr = $('#edit-lang-{{$lang->code}} #eattribute_text_{{$lang->code}} .input-group').length;

            const clone = $('#attribute_temp_{{$lang->code}}').clone(true);
            const cloned = clone.html().replaceAll('_index', count_attr).replaceAll('errproduct_attribute_', 'eerrproduct_attribute_');

            $('#edit-lang-{{$lang->code}} #eattribute_text_{{$lang->code}}').append(cloned);
        });


        @if ($product[$lang->code]->id)
        $.get("{{route('admin.product.images', $product[$lang->code]->id)}}", function(data){
            for (var i = 0; i < data.length; i++) {
                $("#imgtable").append('<tr class="trdb" id="trdb'+data[i].id+'"><td><div class="thumbnail"><img style="width:150px;" src="{{asset('assets/front/img/product/sliders/')}}/'+data[i].image+'" alt="Ad Image"></div></td><td><button type="button" class="btn btn-danger pull-right rmvbtndb" onclick="rmvdbimg('+data[i].id+')"><i class="fa fa-times"></i></button></td></tr>');
            }
        });
        $('#lfmModal4{{$lang->id}} iframe').attr('src', "{{url('laravel-filemanager')}}?serial=4{{$lang->id}}&product={{$product[$lang->code]->id}}");
        @endif
        @if(!$loop->first)

        @endif
        @endforeach
    })();

    function rmvdbimg(indb) {
        $(".request-loader").addClass("show");
        $.ajax({
            url: "",
            type: 'POST',
            data: {
                _token: "{{csrf_token()}}",
                fileid: indb
            },
            success: function(data) {
                $(".request-loader").removeClass("show");
                $("#trdb"+indb).remove();
                var content = {};

                content.message = 'Slider image deleted successfully!';
                content.title = 'Success';
                content.icon = 'fa fa-bell';

                $.notify(content,{
                    type: 'success',
                    placement: {
                        from: 'top',
                        align: 'right'
                    },
                    time: 1000,
                    delay: 0,
                });
            }
        });

    }


</script>

