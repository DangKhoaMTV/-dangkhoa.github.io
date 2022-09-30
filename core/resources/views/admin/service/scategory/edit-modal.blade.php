
@if (!empty($langs))
    <ul class="nav nav-tabs">
        @foreach ($langs as $lang)
            <li class="nav-item">
                <a class="nav-link {{$lang->code == request()->input('language') ? 'active' : ''}}" data-toggle="tab" href="#edit-lang-{{$lang->code}}">{{$lang->name}}</a>
            </li>
        @endforeach
    </ul>
@endif
<form id="ajaxEditForm" class="" action="{{route('admin.scategory.update')}}" method="post">
@csrf
  @if (!empty($langs))
      <div class="tab-content">
          @foreach ($langs as $lang)
              <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}" id="edit-lang-{{$lang->code}}">
                  @if($scategory[$lang->code]->id==0)
                      <div class="form-group">
                          <label class="" for="">Choose association **</label>
                          <select class="form-control select2" name="scategory_assoc_id_{{$lang->code}}">
                              <option value="" selected disabled>Select a category</option>
                              @foreach ($scates[$lang->code] as $scate)
                              <option value="{{$scate->id}}">{{$scate->name}}</option>
                              @endforeach
                          </select>
                          <p id="errassoc_{{$lang->code}}" class="mb-0 text-danger em"></p>
                      </div>
                  @else
<input type="hidden" name="scategory_id_{{$lang->code}}" value="{{$scategory[$lang->code]->id}}">
                  @endif
{{-- Image Part --}}
<div class="form-group">
    <label for="">Image ** </label>
    <br>
    <div class="thumb-preview" id="thumbPreview{{$lang->id}}9">
        <label for="chooseImage{{$lang->id}}9"><img src="{{$scategory[$lang->code]->image!=''?asset('assets/front/img/service_category_icons/' . $scategory[$lang->code]->image):asset('assets/admin/img/noimage.jpg')}}" alt="User Image"></label>
    </div>
    <br>
    <br>


    <input id="fileInput{{$lang->id}}9" type="hidden" name="image_{{$lang->code}}">
    <button id="chooseImage{{$lang->id}}9" class="choose-image btn btn-primary" type="button" data-multiple="false" data-toggle="modal" data-target="#lfmModal{{$lang->id}}9">Choose Image</button>


    <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
    <p class="em text-danger mb-0" id="errimage_{{$lang->code}}"></p>

</div>
<div class="form-group">
  <label for="">Name **</label>
  <input type="text" class="form-control" name="name_{{$lang->code}}" value="{{$scategory[$lang->code]->name}}" placeholder="Enter name">
  <p id="errname_{{$lang->code}}" class="mb-0 text-danger em"></p>
</div>
<div class="form-group">
  <label for="">Sort Text **</label>
  <input type="text" class="form-control" name="short_text_{{$lang->code}}" value="{{$scategory[$lang->code]->short_text}}" placeholder="Enter short text">
  <p id="errshort_text_{{$lang->code}}" class="mb-0 text-danger em"></p>
</div>
<div class="form-group">
  <label for="">Status **</label>
  <select class="form-control ltr" name="status_{{$lang->code}}">
    <option value="" selected disabled>Select a status</option>
    <option value="1" {{$scategory[$lang->code]->status == 1 ? 'selected' : ''}}>Active</option>
    <option value="0" {{$scategory[$lang->code]->status == 0 ? 'selected' : ''}}>Deactive</option>
  </select>
  <p id="errstatus_{{$lang->code}}" class="mb-0 text-danger em"></p>
</div>
<div class="form-group">
  <label for="">Serial Number **</label>
  <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}" value="{{$scategory[$lang->code]->serial_number}}" placeholder="Enter Serial Number">
  <p id="errserial_number_{{$lang->code}}" class="mb-0 text-danger em"></p>
  <p class="text-warning"><small>The higher the serial number is, the later the service category will be shown everywhere.</small></p>
</div>

              </div>
          @endforeach
      </div>
  @endif
</form>
