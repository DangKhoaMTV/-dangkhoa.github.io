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
<form id="ajaxEditForm" class="" action="{{route('admin.portfolio.update')}}" method="post">
    @csrf
    @if (!empty($langs))
        <div class="tab-content">
            @foreach ($langs as $lang)
                <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}"
                     id="edit-lang-{{$lang->code}}">
                    @include('admin.sameContent')

                    @if($portfolio[$lang->code]->id==0)
                        <div class="form-group">
                            <label class="" for="">Choose association **</label>
                            <select class="form-control select2" name="portfolio_assoc_id_{{$lang->code}}">
                                <option value="" selected>Select a portfolio</option>
                                @foreach ($scates[$lang->code] as $scate)
                                    <option value="{{$scate->id}}" data-assoc_id="{{$scate->assoc_id}}">[{{$scate->id}}-{{$scate->assoc_id}}] {{$scate->title}}</option>
                                @endforeach
                            </select>
                            <p id="errportfolio_assoc_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                    @else
                        <input type="hidden" name="portfolio_id_{{$lang->code}}"
                               value="{{$portfolio[$lang->code]->id}}">
                    @endif

                    {{-- Image Part --}}
                    <div class="form-group">
                        <label for="">Image ** </label>
                        <br>
                        <div class="thumb-preview" id="thumbPreview3{{$lang->id}}">
                            <label for="chooseImage3{{$lang->id}}"><img
                                src="{{ $portfolio[$lang->code]->featured_image != '' ? asset('assets/front/img/portfolios/featured/' . $portfolio[$lang->code]->featured_image) : asset('assets/admin/img/noimage.jpg') }}"
                                alt="User Image"></label>
                        </div>
                        <br>
                        <br>

                        <input id="fileInput3{{$lang->id}}" type="hidden" name="image_{{$lang->code}}">
                        <button id="chooseImage3{{$lang->id}}" class="choose-image btn btn-primary" type="button"
                                data-multiple="false"
                                data-toggle="modal" data-target="#lfmModal3{{$lang->id}}">Choose Image
                        </button>

                        <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
                        <p class="em text-danger mb-0" id="errimage_{{$lang->code}}"></p>

                    </div>

                    {{-- START: slider Part --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="">Slider Images ** </label>
                                <br>
                                <div class="slider-thumbs" id="sliderThumbs4{{$lang->id}}">

                                </div>

                                <input id="fileInput4{{$lang->id}}" type="hidden" name="slider_{{$lang->code}}"
                                       value=""/>
                                <button id="chooseImage4{{$lang->id}}" class="choose-image btn btn-primary"
                                        type="button"
                                        data-multiple="true"
                                        data-toggle="modal" data-target="#lfmModal4{{$lang->id}}">Choose Images
                                </button>


                                <p class="text-warning mb-0">JPG, PNG, JPEG images are allowed</p>
                                <p id="errslider_{{$lang->code}}" class="mb-0 text-danger em"></p>

                            </div>
                        </div>
                    </div>
                    {{-- END: slider Part --}}

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">Title **</label>
                                <input type="text" class="form-control" name="title_{{$lang->code}}"
                                       value="{{$portfolio[$lang->code]->title}}"
                                       placeholder="Enter title">
                                <p id="errtitle_{{$lang->code}}" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">Client Name **</label>
                                <input type="text" class="form-control" name="client_name_{{$lang->code}}"
                                       value="{{$portfolio[$lang->code]->client_name}}"
                                       placeholder="Enter client name">
                                <p id="errclient_name_{{$lang->code}}" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">Service **</label>
                                <select class="form-control" name="service_id_{{$lang->code}}">
                                    <option value="">Select a portfolio</option>
                                    @foreach ($services[$lang->code] as $key => $service)
                                        <option
                                            value="{{$service->id}}" {{$portfolio[$lang->code]->service_id == $service->id ? 'selected' : ''}}>
                                            {{$service->title}}</option>
                                    @endforeach
                                </select>
                                <p id="errservice_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">Tags **</label>
                                <input type="text" class="form-control" name="tags_{{$lang->code}}"
                                       value="{{$portfolio[$lang->code]->tags}}"
                                       data-role="tagsinput"
                                       placeholder="Enter tags">
                                <p id="errtags_{{$lang->code}}" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">Start Date </label>
                                <input id="startDate_{{$lang->code}}" type="text" class="form-control datepicker"
                                       name="start_date_{{$lang->code}}"
                                       value="{{$portfolio[$lang->code]->start_date}}" placeholder="Enter start date"
                                       autocomplete="off">
                                <p id="errstart_date_{{$lang->code}}" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">Submission Date </label>
                                <input id="submissionDate_{{$lang->code}}" type="text" class="form-control datepicker"
                                       name="submission_date_{{$lang->code}}"
                                       value="{{$portfolio[$lang->code]->submission_date}}"
                                       placeholder="Enter submission date"
                                       autocomplete="off">
                                <p id="errsubmission_date_{{$lang->code}}" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">Status **</label>
                                <select class="form-control ltr" name="status_{{$lang->code}}">
                                    <option value="" selected disabled>Select a status</option>
                                    <option
                                        value="In Progress" {{$portfolio[$lang->code]->status == 'In Progress' ? 'selected' : ''}}>
                                        In
                                        Progress
                                    </option>
                                    <option
                                        value="Completed" {{$portfolio[$lang->code]->status == 'Completed' ? 'selected' : ''}}>
                                        Completed
                                    </option>
                                </select>
                                <p id="errstatus_{{$lang->code}}" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">Serial Number **</label>
                                <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}"
                                       value="{{$portfolio[$lang->code]->serial_number}}"
                                       placeholder="Enter Serial Number">
                                <p id="errserial_number_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                <p class="text-warning mb-0"><small>The higher the serial number is, the later the
                                        portfolio will be
                                        shown.</small></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="">Website Link</label>
                                <input type="url" class="form-control" name="website_link_{{$lang->code}}"
                                       value="{{$portfolio[$lang->code]->website_link}}"
                                       placeholder="Enter website link">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="">Content **</label>
                                <textarea id="portContent_{{$lang->code}}" class="form-control summernote"
                                          name="content_{{$lang->code}}" rows="8"
                                          placeholder="Enter content"
                                          data-height="300">{{replaceBaseUrl($portfolio[$lang->code]->content)}}</textarea>
                                <p id="errcontent_{{$lang->code}}" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Meta Keywords</label>
                        <input class="form-control" name="meta_keywords_{{$lang->code}}"
                               value="{{$portfolio[$lang->code]->meta_keywords}}"
                               placeholder="Enter meta keywords" data-role="tagsinput">
                    </div>
                    <div class="form-group">
                        <label>Meta Description</label>
                        <textarea class="form-control" name="meta_description_{{$lang->code}}" rows="5"
                                  placeholder="Enter meta description">{{$portfolio[$lang->code]->meta_description}}</textarea>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</form>

{{-- dropzone --}}
<script>
    // myDropzone is the configuration for the element that has an id attribute
    // with the value my-dropzone (or myDropzone)
    Dropzone.options.myDropzone = {
        acceptedFiles: '.png, .jpg, .jpeg',
        url: "{{route('admin.portfolio.sliderstore')}}",
        success: function (file, response) {
            console.log(response.file_id);

            // Create the remove button
            var removeButton = Dropzone.createElement("<button class='rmv-btn'><i class='fa fa-times'></i></button>");


            // Capture the Dropzone instance as closure.
            var _this = this;

            // Listen to the click event
            removeButton.addEventListener("click", function (e) {
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

            $.notify(content, {
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
            url: "{{route('admin.portfolio.sliderrmv')}}",
            type: 'POST',
            data: {
                _token: "{{csrf_token()}}",
                fileid: fileid
            },
            success: function (data) {
                var content = {};

                content.message = 'Slider image deleted successfully!';
                content.title = 'Success';
                content.icon = 'fa fa-bell';

                $.notify(content, {
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


<script>
    var el = 0;

    (function () {
        @foreach ($langs as $lang)
        @if ($portfolio[$lang->code]->id)
        $.get("{{route('admin.portfolio.images', $portfolio[$lang->code]->id)}}", function (data) {
            for (var i = 0; i < data.length; i++) {
                $("#imgtable").append('<tr class="trdb" id="trdb' + data[i].id + '"><td><div class="thumbnail"><img style="width:150px;" src="{{asset('assets/front/img/portfolios/sliders/')}}/' + data[i].image + '" alt="Ad Image"></div></td><td><button type="button" class="btn btn-danger pull-right rmvbtndb" onclick="rmvdbimg(' + data[i].id + ')"><i class="fa fa-times"></i></button></td></tr>');
            }
        });
        $('#lfmModal92{{$lang->id}} iframe').attr('src', "{{url('laravel-filemanager')}}?serial=92{{$lang->id}}&portfolio={{$portfolio[$lang->code]->id}}");
            @endif
            @endforeach

        var today = new Date();
        $("input[id^='submissionDate']").datepicker({
            autoclose: true,
            endDate: today,
            todayHighlight: true
        });
        $("input[id^='startDate_']").datepicker({
            autoclose: true,
            endDate: today,
            todayHighlight: true
        });
    })();

    function rmvdbimg(indb) {
        $(".request-loader").addClass("show");
        $.ajax({
            url: "{{route('admin.portfolio.sliderrmv')}}",
            type: 'POST',
            data: {
                _token: "{{csrf_token()}}",
                fileid: indb
            },
            success: function (data) {
                $(".request-loader").removeClass("show");
                $("#trdb" + indb).remove();
                var content = {};

                content.message = 'Slider image deleted successfully!';
                content.title = 'Success';
                content.icon = 'fa fa-bell';

                $.notify(content, {
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
