@if (!empty($langs))
    <ul class="nav nav-tabs">
        @foreach ($langs as $lang)
            <li class="nav-item">
                <a class="nav-link {{$lang->code == request()->input('language') ? 'active' : ''}}" data-toggle="tab" href="#edit-lang-{{$lang->code}}">{{$lang->name}}</a>
            </li>
        @endforeach
    </ul>
@endif
<form id="ajaxEditForm" class="" action="{{route('admin.testimonial.update')}}" method="post">
    @csrf
    @if (!empty($langs))
        <div class="tab-content">
            @foreach ($langs as $lang)
                <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}" id="edit-lang-{{$lang->code}}">
                    @include('admin.sameContent')

                    @if($testimonial[$lang->code]->id==0)
                        <div class="form-group">
                            <label class="" for="">Choose association **</label>
                            <select class="form-control select2" name="testimonial_assoc_id_{{$lang->code}}">
                                <option value="" selected disabled>Select a feature</option>
                                @foreach ($scates[$lang->code] as $scate)
                                    <option value="{{$scate->id}}">[{{$scate->id}}-{{$scate->assoc_id}}] {{$scate->name}}</option>
                                @endforeach
                            </select>
                            <p id="errtestimonial_assoc_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                    @else
                        <input type="hidden" name="testimonial_id_{{$lang->code}}" value="{{$testimonial[$lang->code]->id}}">
                    @endif

    {{-- Image Part --}}
    <div class="form-group">
        <label for="">Image ** </label>
        <br>
        <div class="thumb-preview" id="thumbPreview3{{$lang->id}}">
            <label for="chooseImage3{{$lang->id}}"><img src="{{$testimonial[$lang->code]->image!=''?asset('assets/front/img/testimonials/' . $testimonial[$lang->code]->image):asset('assets/admin/img/noimage.jpg')}}" alt="Client"></label>
        </div>
        <br>
        <br>


        <input id="fileInput3{{$lang->id}}" type="hidden" name="image_{{$lang->code}}">
        <button id="chooseImage3{{$lang->id}}" class="choose-image btn btn-primary" type="button" data-multiple="false" data-toggle="modal" data-target="#lfmModal3{{$lang->id}}">Choose Image</button>


        <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
        <p class="em text-danger mb-0" id="errimage_{{$lang->code}}"></p>


    </div>

    <div class="form-group">
      <label for="">Comment **</label>
      <textarea class="form-control" name="comment_{{$lang->code}}" rows="3" cols="80" placeholder="Enter comment">{{$testimonial[$lang->code]->comment}}</textarea>
      <p id="errcomment_{{$lang->code}}" class="mb-0 text-danger em"></p>
    </div>
    <div class="form-group">
      <label for="">Name **</label>
      <input type="text" class="form-control" name="name_{{$lang->code}}" value="{{$testimonial[$lang->code]->name}}" placeholder="Enter name">
      <p id="errname_{{$lang->code}}" class="mb-0 text-danger em"></p>
    </div>
    <div class="form-group">
      <label for="">Rank **</label>
      <input type="number" min="0" step=".1" max="5" class="form-control" name="rank_{{$lang->code}}" value="{{$testimonial[$lang->code]->rank}}" placeholder="Enter rank">
      <p id="errrank_{{$lang->code}}" class="mb-0 text-danger em"></p>
    </div>
    <div class="form-group">
      <label for="">Channel **</label>
        <select class="form-control select2" name="channel_{{$lang->code}}">
            <option value="" selected disabled>Select a channel</option>
            <option value="google" {{$testimonial[$lang->code]->channel == 'google'?'selected':''}}>Google</option>
            <option value="facebook" {{$testimonial[$lang->code]->channel == 'facebook'?'selected':''}}>Facebook</option>
            <option value="twitter" {{$testimonial[$lang->code]->channel == 'twitter'?'selected':''}}>Twitter</option>
        </select>
        <p id="errchannel_{{$lang->code}}" class="mb-0 text-danger em"></p>
    </div>
    <div class="form-group">
      <label for="">Serial Number **</label>
      <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}" value="{{$testimonial[$lang->code]->serial_number}}" placeholder="Enter Serial Number">
      <p id="errserial_number_{{$lang->code}}" class="mb-0 text-danger em"></p>
      <p class="text-warning"><small>The higher the serial number is, the later the testimonial will be shown.</small></p>
    </div>
                </div>
            @endforeach
        </div>
    @endif
  </form>
