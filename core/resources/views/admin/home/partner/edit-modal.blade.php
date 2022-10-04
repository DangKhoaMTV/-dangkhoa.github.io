@if (!empty($langs))
    <ul class="nav nav-tabs">
        @foreach ($langs as $lang)
            <li class="nav-item">
                <a class="nav-link {{$lang->code == request()->input('language') ? 'active' : ''}}" data-toggle="tab" href="#edit-lang-{{$lang->code}}">{{$lang->name}}</a>
            </li>
        @endforeach
    </ul>
@endif
<form id="ajaxEditForm" class="" action="{{route('admin.partner.update')}}" method="post">
@csrf
    @if (!empty($langs))
        <div class="tab-content">
            @foreach ($langs as $lang)
                <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}" id="edit-lang-{{$lang->code}}">
                    @include('admin.sameContent')
                    @if($partner[$lang->code]->id==0)
                        <div class="form-group">
                            <label class="" for="">Choose association **</label>
                            <select class="form-control select2" name="partner_assoc_id_{{$lang->code}}">
                                <option value="" selected disabled>Select a feature</option>
                                @foreach ($scates[$lang->code] as $scate)
                                    <option value="{{$scate->id}}">[{{$scate->id}}-{{$scate->assoc_id}}] {{$scate->url}}</option>
                                @endforeach
                            </select>
                            <p id="errmember_assoc_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                    @else
                        <input type="hidden" name="partner_id_{{$lang->code}}" value="{{$partner[$lang->code]->id}}">
                    @endif

{{-- Image Part --}}
<div class="form-group">
    <label for="">Image ** </label>
    <br>
    <div class="thumb-preview" id="thumbPreview3{{$lang->id}}">
        <label for="chooseImage3{{$lang->id}}"><img src="{{$partner[$lang->code]->image!=''?asset('assets/front/img/partners/' . $partner[$lang->code]->image):asset('assets/admin/img/noimage.jpg')}}" alt="User Image"></label>
    </div>
    <br>
    <br>
    <input id="fileInput3{{$lang->id}}" type="hidden" name="image_{{$lang->code}}">
    <button id="chooseImage3{{$lang->id}}" class="choose-image btn btn-primary" type="button" data-multiple="false" data-toggle="modal" data-target="#lfmModal3{{$lang->id}}">Choose Image</button>


    <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
    <p class="em text-danger mb-0" id="errimage_{{$lang->code}}"></p>
</div>
<div class="form-group">
  <label for="">URL **</label>
  <input type="text" class="form-control ltr" name="url_{{$lang->code}}" value="{{$partner[$lang->code]->url}}" placeholder="Enter URL of social media account">
  <p id="errurl_{{$lang->code}}" class="text-danger mb-0 em"></p>
</div>
<div class="form-group">
  <label for="">Serial Number **</label>
  <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}" value="{{$partner[$lang->code]->serial_number}}" placeholder="Enter Serial Number">
  <p id="errserial_number_{{$lang->code}}" class="mb-0 text-danger em"></p>
  <p class="text-warning"><small>The higher the serial number is, the later the partner will be shown.</small></p>
</div>
                </div>
            @endforeach
        </div>
    @endif
</form>
