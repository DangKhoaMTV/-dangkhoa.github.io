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
                                <source src="{{ asset("assets/front/img/events/videos/".$event[$lang->code]->video)}}"
                                        type="video/mp4">
                            </video>
                        </div>
                        <br>


                        <input id="fileInput{{$lang->id}}1" type="hidden" name="video">
                        <button id="chooseVideo{{$lang->id}}1" class="choose-video btn btn-primary" type="button"
                                data-multiple="false" data-video="true" data-toggle="modal"
                                data-target="#lfmModal{{$lang->id}}1">Choose Video
                        </button>


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

                                <input id="fileInput{{$lang->id}}2" type="hidden" name="slider_{{$lang->code}}"
                                       value=""/>
                                <button id="chooseImage{{$lang->id}}2" class="choose-image btn btn-primary"
                                        type="button" data-multiple="true" data-toggle="modal"
                                        data-target="#lfmModal{{$lang->id}}2">Choose Images
                                </button>


                                <p class="text-warning mb-0">JPG, PNG, JPEG images are allowed</p>
                                <p id="errslider_{{$lang->code}}" class="mb-0 text-danger em"></p>

                            </div>
                        </div>
                    </div>
                    {{-- END: slider Part --}}
                    <div class="form-group">
                        <label for="">Title **</label>
                        <input type="text" class="form-control" name="title_{{$lang->code}}"
                               value="{{$event[$lang->code]->title}}" placeholder="Enter title">
                        <p id="errtitle_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Category **</label>
                        <select class="form-control" name="cat_id_{{$lang->code}}">
                            <option value="" selected disabled>Select a category</option>
                            @foreach ($event_categories[$lang->code] as $key => $event_category)
                                <option
                                    value="{{$event_category->id}}" {{$event_category->id == $event[$lang->code]->cat_id ? 'selected' : ''}}>{{$event_category->name}}</option>
                            @endforeach
                        </select>
                        <p id="errcat_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Content **</label>
                        <textarea class="form-control summernote" name="content_{{$lang->code}}" data-height="300"
                                  placeholder="Enter content">{{replaceBaseUrl($event[$lang->code]->content)}}</textarea>
                        <p id="errcontent_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Date</label>
                        <input type="date" class="form-control ltr" name="date_{{$lang->code}}"
                               value="{{$event[$lang->code]->date}}" placeholder="Enter Event Date">
                        <p id="errdate_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Time</label>
                        <input type="time" class="form-control ltr" name="time_{{$lang->code}}"
                               value="{{\Carbon\Carbon::parse($event[$lang->code]->time)->format('H:i:s')}}"
                               placeholder="Enter Event Time">
                        <p id="errtime_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Cost (in {{$abx->base_currency_text}}) **</label>
                        <input type="number" class="form-control ltr" name="cost_{{$lang->code}}"
                               value="{{$event[$lang->code]->cost}}" placeholder="Enter Ticket Cost">
                        <p id="errcost_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Available Tickets **</label>
                        <input type="number" class="form-control ltr" name="available_tickets_{{$lang->code}}"
                               value="{{$event[$lang->code]->available_tickets}}"
                               placeholder="Enter Number of available tickets">
                        <p id="erravailable_tickets_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Organizer</label>
                        <input type="text" class="form-control ltr" name="organizer_{{$lang->code}}"
                               value="{{$event[$lang->code]->organizer}}" placeholder="Event Organizer">
                        <p id="errorganizer_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Organizer Email</label>
                        <input type="text" class="form-control ltr" name="organizer_email_{{$lang->code}}"
                               value="{{$event[$lang->code]->organizer_email}}" placeholder="Organizer Email">
                        <p id="errorganizer_email_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Organizer Phone</label>
                        <input type="text" class="form-control ltr" name="organizer_phone_{{$lang->code}}"
                               value="{{$event[$lang->code]->organizer_phone}}" placeholder="Organizer Email">
                        <p id="errorganizer_phone_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Organizer Website</label>
                        <input type="text" class="form-control ltr" name="organizer_website_{{$lang->code}}"
                               value="{{$event[$lang->code]->organizer_website}}" placeholder="Organizer Website">
                        <p id="errserial_number_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Venue</label>
                        <input type="text" class="form-control ltr" name="venue_{{$lang->code}}"
                               value="{{$event[$lang->code]->venue}}" placeholder="Enter Venue">
                        <p id="errvenue_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Venue Location</label>
                        <input type="text" class="form-control ltr" name="venue_location_{{$lang->code}}"
                               value="{{$event[$lang->code]->venue_location}}" placeholder="Venue Location">
                        <p id="errvenue_location_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Venue Phone</label>
                        <input type="text" class="form-control ltr" name="venue_phone_{{$lang->code}}"
                               value="{{$event[$lang->code]->venue_phone}}" placeholder="Venue Phone">
                        <p id="errvenue_phone_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Meta Keywords</label>
                        <input type="text" class="form-control" name="meta_tags_{{$lang->code}}"
                               value="{{$event[$lang->code]->meta_tags}}" data-role="tagsinput">
                        <p id="errmeta_keywords_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Meta Description</label>
                        <textarea type="text" class="form-control" name="meta_description_{{$lang->code}}"
                                  rows="5">{{$event[$lang->code]->meta_description}}</textarea>
                        <p id="errmeta_description_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</form>
<script>
    (function () {
        @foreach ($langs as $lang)
        @if ($event[$lang->code]->id)
        $('#lfmModal{{$lang->id}}2 iframe').attr('src', "{{url('laravel-filemanager')}}?serial={{$lang->id}}2&event={{$event[$lang->code]->id}}");
        @endif
        @endforeach
    })();
</script>
