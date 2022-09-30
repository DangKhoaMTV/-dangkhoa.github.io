@extends('admin.layout')

@if(!empty($event->language) && $event->language->rtl == 1)
@section('styles')
<style>
    form input,
    form textarea,
    form select {
        direction: rtl;
    }
    form .note-editor.note-frame .note-editing-area .note-editable {
        direction: rtl;
        text-align: right;
    }
</style>
@endsection
@endif

@section('content')
  <div class="page-header">
    <h4 class="page-title">Edit Event</h4>
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
        <a href="#">Event Page</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">Edit Event</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">Edit Event</div>
          <a class="btn btn-info btn-sm float-right d-inline-block" href="{{route('admin.event.index') . '?language=' . request()->input('language')}}">
            <span class="btn-label">
              <i class="fas fa-backward" style="font-size: 12px;"></i>
            </span>
            Back
          </a>
        </div>
        <div class="card-body pt-5 pb-5" id="edit_content">
          <div class="row">
            <div class="col-lg-10 offset-lg-1">
                {{-- Slider images upload start --}}
                {{-- <div class="px-2">
                    <label for="" class="mb-2"><strong>Slider Images **</strong></label>
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-striped" id="imgtable">
                                @if (!is_null($event->image))
                                    @foreach(json_decode($event->image) as $key => $img)
                                        <tr class="trdb" id="trdb{{$key}}">
                                            <td>
                                                <div class="thumbnail">
                                                    <img style="width:150px;" src="{{asset('assets/front/img/events/sliders/'.$img)}}" alt="Ad Image">
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger pull-right rmvbtndb" onclick="rmvdbimg({{$key}},{{$event->id}})">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </table>
                        </div>
                    </div>
                    <form action="" id="my-dropzone" enctype="multipart/formdata" class="dropzone create">
                        @csrf
                        <div class="fallback">
                        </div>
                    </form>
                    <p class="em text-danger mb-0" id="errimage"></p>
                </div> --}}
                {{-- Slider images upload end --}}
                {{-- <form class="mb-3 dm-uploader modal-form" enctype="multipart/form-data" action="{{route('admin.event.upload')}}" method="POST" id="video-frm">
                    <div class="form-row px-2">
                        <div class="col-12 mb-2">
                            <label for=""><strong>Video</strong></label>
                        </div>
                        <div class="col-sm-12">
                            <div class="from-group mb-2">
                               @if(!is_null($event->video))
                                    <video width="320" height="240" controls id="video_src">
                                        <source src="{{ asset("assets/front/img/events/videos/".$event->video)}}" type="video/mp4">
                                    </video>
                                @else
                                   No video uploaded yet
                                @endif
                            </div>
                            <div class="mt-4">
                                <div role="button" class="btn btn-primary mr-2">
                                    <i class="fa fa-folder-o fa-fw"></i> Browse Files
                                    <input type="file" title='Click to add Files' id="upload-video" name="upload-video" />
                                </div>
                                <small class="status text-muted">Select a file or drag it over this area..</small>
                                <p class="em text-danger mb-0" id="errblog"></p>
                            </div>
                        </div>
                    </div>
                </form> --}}

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
              <form id="ajaxEditForm" class="" action="{{route('admin.event.update')}}" method="post">
                @csrf
                  @if (!empty($langs))
                      <div class="tab-content">
                          @foreach ($langs as $lang)
                              <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}"
                                   id="edit-lang-{{$lang->code}}">
                                  @if($event[$lang->code]->id==0)
                                      <div class="form-group">
                                          <label class="" for="">Choose association **</label>
                                          <select class="form-control select2" name="event_assoc_id_{{$lang->code}}">
                                              <option value="" selected>Select a blog</option>
                                              @foreach ($ccates[$lang->code] as $ccate)
                                                  <option value="{{$ccate->id}}">[{{$ccate->id}}-{{$ccate->assoc_id}}
                                                      ] {{$ccate->title}}</option>
                                              @endforeach
                                          </select>
                                          <p id="errevent_assoc_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                      </div>
                                  @else
                                      <input type="hidden" name="event_id_{{$lang->code}}" value="{{$event[$lang->code]->id}}">
                                  @endif

                                <input type="hidden" name="lang_id_{{$lang->code}}" value="{{$event[$lang->code]->lang_id}}">
                                {{-- Video Part --}}
                                <div class="form-group">
                                    <label for="">Video ** </label>
                                    <br>
                                    <div class="video-preview" id="videoPreview{{$lang->id}}1">
                                        <video width="320" height="240" controls id="video_src{{$lang->id}}">
                                            <source src="{{ asset("assets/front/img/events/videos/".$event[$lang->code]->video)}}" type="video/mp4">
                                        </video>
                                    </div>
                                    <br>


                                    <input id="fileInput{{$lang->id}}1" type="hidden" name="video">
                                    <button id="chooseVideo{{$lang->id}}1" class="choose-video btn btn-primary" type="button" data-multiple="false" data-video="true" data-toggle="modal" data-target="#lfmModal{{$lang->id}}1">Choose Video</button>


                                    <p class="text-warning mb-0">MP4 video is allowed</p>
                                    <p class="em text-danger mb-0" id="errvideo_{{$lang->code}}"></p>

                                </div>
                                {{-- START: slider Part --}}

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
                                            <p id="errslider_{{$lang->code}}" class="mb-0 text-danger em"></p>

                                        </div>
                                    </div>
                                </div>
                                {{-- END: slider Part --}}
                                <div class="form-group">
                                  <label for="">Title **</label>
                                  <input type="text" class="form-control" name="title_{{$lang->code}}" value="{{$event[$lang->code]->title}}" placeholder="Enter title">
                                  <p id="errtitle_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                  <label for="">Category **</label>
                                  <select class="form-control" name="cat_id_{{$lang->code}}">
                                    <option value="" selected disabled>Select a category</option>
                                    @foreach ($event_categories[$lang->code] as $key => $event_category)
                                      <option value="{{$event_category->id}}" {{$event_category->id == $event[$lang->code]->cat_id ? 'selected' : ''}}>{{$event_category->name}}</option>
                                    @endforeach
                                  </select>
                                  <p id="errcat_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                  <label for="">Content **</label>
                                  <textarea class="form-control summernote" name="content_{{$lang->code}}" data-height="300" placeholder="Enter content">{{replaceBaseUrl($event[$lang->code]->content)}}</textarea>
                                  <p id="errcontent_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                </div>
                                  <div class="form-group">
                                      <label for="">Date</label>
                                      <input type="date" class="form-control ltr" name="date_{{$lang->code}}" value="{{$event[$lang->code]->date}}" placeholder="Enter Event Date">
                                      <p id="errdate_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                  </div>
                                  <div class="form-group">
                                      <label for="">Time</label>
                                      <input type="time" class="form-control ltr" name="time_{{$lang->code}}" value="{{\Carbon\Carbon::parse($event[$lang->code]->time)->format('H:i:s')}}" placeholder="Enter Event Time">
                                      <p id="errtime_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                  </div>
                                  <div class="form-group">
                                      <label for="">Cost (in {{$abx->base_currency_text}}) **</label>
                                      <input type="number" class="form-control ltr" name="cost_{{$lang->code}}" value="{{$event[$lang->code]->cost}}" placeholder="Enter Ticket Cost">
                                      <p id="errcost_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                  </div>
                                <div class="form-group">
                                  <label for="">Available Tickets **</label>
                                  <input type="number" class="form-control ltr" name="available_tickets_{{$lang->code}}" value="{{$event[$lang->code]->available_tickets}}" placeholder="Enter Number of available tickets">
                                  <p id="erravailable_tickets_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                </div>
                                  <div class="form-group">
                                      <label for="">Organizer</label>
                                      <input type="text" class="form-control ltr" name="organizer_{{$lang->code}}" value="{{$event[$lang->code]->organizer}}" placeholder="Event Organizer">
                                      <p id="errorganizer_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                  </div>
                                  <div class="form-group">
                                      <label for="">Organizer Email</label>
                                      <input type="text" class="form-control ltr" name="organizer_email_{{$lang->code}}" value="{{$event[$lang->code]->organizer_email}}" placeholder="Organizer Email">
                                      <p id="errorganizer_email_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                  </div>
                                  <div class="form-group">
                                      <label for="">Organizer Phone</label>
                                      <input type="text" class="form-control ltr" name="organizer_phone_{{$lang->code}}" value="{{$event[$lang->code]->organizer_phone}}" placeholder="Organizer Email">
                                      <p id="errorganizer_phone_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                  </div>
                                  <div class="form-group">
                                      <label for="">Organizer Website</label>
                                      <input type="text" class="form-control ltr" name="organizer_website_{{$lang->code}}" value="{{$event[$lang->code]->organizer_website}}" placeholder="Organizer Website">
                                      <p id="errserial_number_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                  </div>
                                  <div class="form-group">
                                      <label for="">Venue</label>
                                      <input type="text" class="form-control ltr" name="venue_{{$lang->code}}" value="{{$event[$lang->code]->venue}}" placeholder="Enter Venue">
                                      <p id="errvenue_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                  </div>
                                  <div class="form-group">
                                      <label for="">Venue Location</label>
                                      <input type="text" class="form-control ltr" name="venue_location_{{$lang->code}}" value="{{$event[$lang->code]->venue_location}}" placeholder="Venue Location">
                                      <p id="errvenue_location_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                  </div>
                                  <div class="form-group">
                                      <label for="">Venue Phone</label>
                                      <input type="text" class="form-control ltr" name="venue_phone_{{$lang->code}}" value="{{$event[$lang->code]->venue_phone}}" placeholder="Venue Phone">
                                      <p id="errvenue_phone_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                  </div>
                                <div class="form-group">
                                  <label for="">Meta Keywords</label>
                                  <input type="text" class="form-control" name="meta_tags_{{$lang->code}}" value="{{$event[$lang->code]->meta_tags}}" data-role="tagsinput">
                                  <p id="errmeta_keywords_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                  <label for="">Meta Description</label>
                                  <textarea type="text" class="form-control" name="meta_description_{{$lang->code}}" rows="5">{{$event[$lang->code]->meta_description}}</textarea>
                                  <p id="errmeta_description_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                </div>
                              </div>
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
                <button type="submit" id="submitBtn" class="btn btn-success">Update</button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection
@section('scripts')
    <script>
        (function () {
            @foreach ($langs as $lang)
            @if ($event[$lang->code]->id)
            $('#lfmModal{{$lang->id}}2 iframe').attr('src', "{{url('laravel-filemanager')}}?serial={{$lang->id}}2&event={{$event[$lang->code]->id}}");
            @endif
            @endforeach
        })();
        $(document).ready(function() {
            $("select[name='lang_id']").on('change', function() {
                $("#bcategory").removeAttr('disabled');
                let langid = $(this).val();
                let url = "{{url('/')}}/admin/event/" + langid + "/get-categories";
                $.get(url, function(data) {
                    console.log(data);
                    let options = `<option value="" disabled selected>Select a category</option>`;
                    for (let i = 0; i < data.length; i++) {
                        options += `<option value="${data[i].id}">${data[i].name}</option>`;
                    }
                    $("#bcategory").html(options);

                });
            });

            // make input fields RTL
            $("select[name='lang_id']").on('change', function() {
                $(".request-loader").addClass("show");
                let url = "{{url('/')}}/admin/rtlcheck/" + $(this).val();
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
                        $("form.modal-form .summernote").siblings('.note-editor').find('.note-editable').removeClass('rtl text-right');
                    }
                })
            });

            // translatable events will be available if the selected language is not 'Default'
            $("#language").on('change', function() {
                let language = $(this).val();
                if (language == 0) {
                    $("#translatable").attr('disabled', true);
                } else {
                    $("#translatable").removeAttr('disabled');
                }
            });

            $("#upload-video").on('change',function (event){
                let formData = new FormData($('#video-frm')[0]);
                let file = $('input[type=file]')[0].files[0];
                // formData.append('upload_video', file, file.name);
                formData.append('upload_video', file);
                $.ajax({
                    url: '{{route('admin.event.upload')}}',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    cache: false,
                    data: formData,
                    success: function(data) {
                        console.log(data.filename,"edit");
                        $("#my_video").val(data.filename);
                        var url = '{{ asset("assets/front/img/events/videos/filename") }}';
                        url = url.replace('filename', data.filename);
                        $("#video_src").attr('src',url);
                    },
                    error: function(data) {
                        console.log(data);
                    }
                })
            })
        });
    </script>
@endsection
