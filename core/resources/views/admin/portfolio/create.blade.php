@extends('admin.layout')

@section('content')
<div class="page-header">
  <h4 class="page-title">Create Portfolio</h4>
  <ul class="breadcrumbs">
    <li class="nav-home">
      <a href="#">
        <i class="flaticon-home"></i>
      </a>
    </li>
    <li class="separator">
      <i class="flaticon-right-arrow"></i>
    </li>
    <li class="nav-item">
      <a href="#">Portfolio Page</a>
    </li>
    <li class="separator">
      <i class="flaticon-right-arrow"></i>
    </li>
    <li class="nav-item">
      <a href="#">Create Portfolio</a>
    </li>
  </ul>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <div class="card-title d-inline-block">Create Portfolio</div>
        <a class="btn btn-info btn-sm float-right d-inline-block"
          href="{{route('admin.portfolio.index') . '?language=' . request()->input('language')}}">
          <span class="btn-label">
            <i class="fas fa-backward" style="font-size: 12px;"></i>
          </span>
          Back
        </a>
      </div>
      <div class="card-body pt-5 pb-5">
        <div class="row">
          <div class="col-lg-8 offset-lg-2">
              @if (!empty($langs))
                  <ul class="nav nav-tabs">
                      @php $active = 1 @endphp
                      @foreach ($langs as $lang)
                          <li class="nav-item">
                              <a class="nav-link @if ($active == 1) active  @endif" data-toggle="tab" href="#create-lang-{{$lang->code}}">{{$lang->name}}</a>
                          </li>
                          @php $active++ @endphp
                      @endforeach
                  </ul>
              @endif
              <form id="ajaxForm" class="modal-form" action="{{route('admin.portfolio.store')}}" method="POST">
                  @csrf
                  @if (!empty($langs))
                      <div class="tab-content">
                          @php $active = 1 @endphp
                          @foreach ($langs as $lang)
                              <div class="tab-pane container @if ($active == 1) active  @endif"  id="create-lang-{{$lang->code}}">
                                  @if ($active !== 1)
                                      @include('admin.sameContent')
                                  @endif

                                  {{-- Image Part --}}
                                  <div class="form-group">
                                      <label for="">Image ** </label>
                                      <br>
                                      <div class="thumb-preview" id="thumbPreview1{{$lang->id}}">
                                          <label for="chooseImage1{{$lang->id}}"><img
                                              src="{{ asset('assets/admin/img/noimage.jpg') }}"
                                              alt="User Image"></label>
                                      </div>
                                      <br>
                                      <br>

                                      <input id="fileInput1{{$lang->id}}" type="hidden" name="image_{{$lang->code}}">
                                      <button id="chooseImage1{{$lang->id}}" class="choose-image btn btn-primary" type="button"
                                              data-multiple="false"
                                              data-toggle="modal" data-target="#lfmModal1{{$lang->id}}">Choose Image
                                      </button>

                                      <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
                                      <p class="em text-danger mb-0" id="errimage{{$lang->code}}"></p>

                                  </div>

                                  {{-- START: slider Part --}}
                                  <div class="row">
                                      <div class="col-12">
                                          <div class="form-group">
                                              <label for="">Slider Images ** </label>
                                              <br>
                                              <div class="slider-thumbs" id="sliderThumbs2{{$lang->id}}">

                                              </div>

                                              <input id="fileInput2{{$lang->id}}" type="hidden" name="slider_{{$lang->code}}"
                                                     value=""/>
                                              <button id="chooseImage2{{$lang->id}}" class="choose-image btn btn-primary"
                                                      type="button"
                                                      data-multiple="true"
                                                      data-toggle="modal" data-target="#lfmModal2{{$lang->id}}">Choose Images
                                              </button>


                                              <p class="text-warning mb-0">JPG, PNG, JPEG images are allowed</p>
                                              <p id="errslider{{$lang->code}}" class="mb-0 text-danger em"></p>

                                          </div>
                                      </div>
                                  </div>
                                  {{-- END: slider Part --}}

                                  <div class="row">
                                      <div class="col-lg-6">
                                          <div class="form-group">
                                              <label for="">Title **</label>
                                              <input type="text" class="form-control" name="title_{{$lang->code}}"
                                                     value=""
                                                     placeholder="Enter title">
                                              <p id="errtitle_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                          </div>
                                      </div>
                                      <div class="col-lg-6">
                                          <div class="form-group">
                                              <label for="">Client Name **</label>
                                              <input type="text" class="form-control" name="client_name_{{$lang->code}}"
                                                     value=""
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
                                                  <option value="">Select a service</option>
                                                  @foreach ($data['services'][$lang->code] as $key => $service)
                                                      <option
                                                          value="{{$service->id}}" data-assoc_id="{{$service->assoc_id}}">
                                                          {{$service->title}}
                                                      </option>
                                                  @endforeach
                                              </select>
                                              <p id="errservice_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                          </div>
                                      </div>
                                      <div class="col-lg-6">
                                          <div class="form-group">
                                              <label for="">Tags **</label>
                                              <input type="text" class="form-control" name="tags_{{$lang->code}}"
                                                     value=""
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
                                                     value="" placeholder="Enter start date"
                                                     autocomplete="off">
                                              <p id="errstart_date_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                          </div>
                                      </div>
                                      <div class="col-lg-6">
                                          <div class="form-group">
                                              <label for="">Submission Date </label>
                                              <input id="submissionDate_{{$lang->code}}" type="text" class="form-control datepicker"
                                                     name="submission_date_{{$lang->code}}"
                                                     value=""
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
                                                      value="In Progress">
                                                      In
                                                      Progress
                                                  </option>
                                                  <option
                                                      value="Completed">
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
                                                     value=""
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
                                                     value=""
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
                                                        placeholder="Enter content"></textarea>
                                              <p id="errcontent_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <label>Meta Keywords</label>
                                      <input class="form-control" name="meta_keywords_{{$lang->code}}"
                                             value=""
                                             placeholder="Enter meta keywords" data-role="tagsinput">
                                  </div>
                                  <div class="form-group">
                                      <label>Meta Description</label>
                                      <textarea class="form-control" name="meta_description_{{$lang->code}}" rows="5"
                                                placeholder="Enter meta description"></textarea>
                                  </div>
                              </div>
                              @php $active++ @endphp
                          @endforeach
                      </div>
                  @endif
              </form>
          </div>
        </div>
      </div>
      <div class="card-footer">
        <div class="form">
          <div class="form-group from-show-notify row">
            <div class="col-12 text-center">
              <button type="submit" id="submitBtn" class="btn btn-success">Submit</button>
            </div>
          </div>
        </div>
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
    <div class="modal fade lfm-modal" id="lfmModal2{{$lang->id}}" tabindex="-1" role="dialog" aria-labelledby="lfmModalTitle" aria-hidden="true">
        <i class="fas fa-times-circle"></i>
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <iframe src="{{url('laravel-filemanager')}}?serial=2{{$lang->id}}" style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    // {{url('laravel-filemanager')}}?serial=2

        $("input.note-image-input").on('change', function(e) {
            e.preventDefault();
            console.log('changed');
        });

       // services load according to language selection
       $("select[name='language_id']").on('change', function() {

           $("#services").removeAttr('disabled');

           let langid = $(this).val();
           let url = "{{url('/')}}/admin/portfolio/" + langid + "/getservices";
           // console.log(url);
           $.get(url, function(data) {
               // console.log(data);
               let options = `<option value="" disabled selected>Select a service</option>`;
               for (let i = 0; i < data.length; i++) {
                   options += `<option value="${data[i].id}">${data[i].title}</option>`;
               }
               $("#services").html(options);

           });
       });


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
                   $("form .summernote").each(function() {
                       $(this).siblings('.note-editor').find('.note-editable').addClass('rtl text-right');
                   });
               } else {
                   $("form input, form select, form textarea").removeClass('rtl');
                   $("form .summernote").siblings('.note-editor').find('.note-editable').removeClass('rtl text-right');
               }
           })
       });

       // translatable portfolios will be available if the selected language is not 'Default'
       $("#language").on('change', function() {
           let language = $(this).val();
           // console.log(language);
           if (language == 0) {
               $("#translatable").attr('disabled', true);
           } else {
               $("#translatable").removeAttr('disabled');
           }
       });
   });


   // myDropzone is the configuration for the element that has an id attribute
   // with the value my-dropzone (or myDropzone)
   Dropzone.options.myDropzone = {
     acceptedFiles: '.png, .jpg, .jpeg',
     url: "{{route('admin.portfolio.sliderstore')}}",
     maxFilesize: 2, // specify the number of MB you want to limit here
     success : function(file, response){
         console.log(response.file_id);
         $("#sliders").append(`<input type="hidden" name="slider_images[]" id="slider${response.file_id}" value="${response.file_id}">`);
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

         if(typeof response.error != 'undefined') {
           if (typeof response.file != 'undefined') {
             document.getElementById('errpreimg').innerHTML = response.file[0];
           }
         }
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
           success: function(data) {
             $("#slider"+fileid).remove();
           }
         });

   }

   var today = new Date();
   $("#submissionDate").datepicker({
     autoclose: true,
     endDate : today,
     todayHighlight: true
   });
   $("#startDate").datepicker({
     autoclose: true,
     endDate : today,
     todayHighlight: true
   });
</script>
@endsection
