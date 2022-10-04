@if (!empty($langs))
    <ul class="nav nav-tabs">
        @foreach ($langs as $lang)
            <li class="nav-item">
                <a class="nav-link {{$lang->code == request()->input('language') ? 'active' : ''}}" data-toggle="tab" href="#edit-lang-{{$lang->code}}">{{$lang->name}}</a>
            </li>
        @endforeach
    </ul>
@endif
<form id="ajaxEditForm" class="" action="{{route('admin.member.update')}}" method="post">
@csrf
    @if (!empty($langs))
        <div class="tab-content">
            @foreach ($langs as $lang)
                <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}" id="edit-lang-{{$lang->code}}">
                    @include('admin.sameContent')

                    @if($member[$lang->code]->id==0)
                        <div class="form-group">
                            <label class="" for="">Choose association **</label>
                            <select class="form-control select2" name="member_assoc_id_{{$lang->code}}">
                                <option value="" selected disabled>Select a feature</option>
                                @foreach ($scates[$lang->code] as $scate)
                                    <option value="{{$scate->id}}">[{{$scate->id}}-{{$scate->assoc_id}}] {{$scate->name}}</option>
                                @endforeach
                            </select>
                            <p id="errmember_assoc_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                    @else
                        <input type="hidden" name="member_id_{{$lang->code}}" value="{{$member[$lang->code]->id}}">
                    @endif

{{-- Image Part --}}
<div class="form-group">
    <label for="">Image ** </label>
    <br>
    <div class="thumb-preview" id="thumbPreview3{{$lang->id}}">
        <label for="chooseImage3{{$lang->id}}"><img src="{{$member[$lang->code]->image!=''?asset('assets/front/img/members/' . $member[$lang->code]->image):asset('assets/admin/img/noimage.jpg')}}" alt="User Image"></label>
    </div>
    <br>
    <br>


    <input id="fileInput3{{$lang->id}}" type="hidden" name="image_{{$lang->code}}">
    <button id="chooseImage3{{$lang->id}}" class="choose-image btn btn-primary" type="button" data-multiple="false" data-toggle="modal" data-target="#lfmModal3{{$lang->id}}">Choose Image</button>


    <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
    <p class="em text-danger mb-0" id="errimage_{{$lang->code}}"></p>


</div>

<div class="form-group">
  <label for="">Name **</label>
  <input type="text" class="form-control" name="name_{{$lang->code}}" value="{{$member[$lang->code]->name}}" placeholder="Enter name">
  <p id="errname_{{$lang->code}}" class="mb-0 text-danger em"></p>
</div>
<div class="form-group">
  <label for="">Rank **</label>
  <input type="text" class="form-control" name="rank_{{$lang->code}}" value="{{$member[$lang->code]->rank}}" placeholder="Enter rank">
  <p id="errrank_{{$lang->code}}" class="mb-0 text-danger em"></p>
</div>
<div class="form-group">
  <label for="">Facebook</label>
  <input type="text" class="form-control ltr" name="facebook_{{$lang->code}}" value="{{$member[$lang->code]->facebook}}" placeholder="Enter facebook url">
  <p id="errfacebook_{{$lang->code}}" class="mb-0 text-danger em"></p>
</div>
<div class="form-group">
  <label for="">Twitter</label>
  <input type="text" class="form-control ltr" name="twitter_{{$lang->code}}" value="{{$member[$lang->code]->twitter}}" placeholder="Enter twitter url">
  <p id="errtwitter_{{$lang->code}}" class="mb-0 text-danger em"></p>
</div>
<div class="form-group">
  <label for="">Instagram</label>
  <input type="text" class="form-control ltr" name="instagram_{{$lang->code}}" value="{{$member[$lang->code]->instagram}}" placeholder="Enter instagram url">
  <p id="errinstagram_{{$lang->code}}" class="mb-0 text-danger em"></p>
</div>
<div class="form-group">
  <label for="">Linkedin</label>
  <input type="text" class="form-control ltr" name="linkedin_{{$lang->code}}" value="{{$member[$lang->code]->linkedin}}" placeholder="Enter linkedin url">
  <p id="errlinkedin_{{$lang->code}}" class="mb-0 text-danger em"></p>
</div>
                </div>
            @endforeach
        </div>
    @endif
</form>
