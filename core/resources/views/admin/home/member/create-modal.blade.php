<div class="modal fade" id="createMemberModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Member</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if (!empty($langs))
                    <ul class="nav nav-tabs">
                        @foreach ($langs as $lang)
                            <li class="nav-item">
                                <a class="nav-link {{$lang->code == request()->input('language') ? 'active' : ''}}" data-toggle="tab" href="#create-lang-{{$lang->code}}">{{$lang->name}}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
                <form id="ajaxForm" class="" action="{{route('admin.member.store')}}" method="POST">
                    @csrf
                    @if (!empty($langs))
                        <div class="tab-content">
                            @foreach ($langs as $lang)
                                <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}" id="create-lang-{{$lang->code}}">
                                    @include('admin.sameContent')
                    {{-- Image Part --}}
                    <div class="form-group">
                        <label for="">Image ** </label>
                        <br>
                        <div class="thumb-preview" id="thumbPreview1{{$lang->id}}">
                            <label for="chooseImage1{{$lang->id}}"><img src="{{asset('assets/admin/img/noimage.jpg')}}" alt="User Image"></label>
                        </div>
                        <br>
                        <br>


                        <input id="fileInput1{{$lang->id}}" type="hidden" name="image_{{$lang->code}}">
                        <button id="chooseImage1{{$lang->id}}" class="choose-image btn btn-primary" type="button" data-multiple="false" data-toggle="modal" data-target="#lfmModal1{{$lang->id}}">Choose Image</button>


                        <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
                        <p class="em text-danger mb-0" id="errimage_{{$lang->code}}"></p>

                    </div>

                    <div class="form-group">
                        <label for="">Name **</label>
                        <input type="text" class="form-control" name="name_{{$lang->code}}" value="" placeholder="Enter name">
                        <p id="errname_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Rank **</label>
                        <input type="text" class="form-control" name="rank_{{$lang->code}}" value="" placeholder="Enter rank">
                        <p id="errrank_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Facebook</label>
                        <input type="text" class="form-control ltr" name="facebook_{{$lang->code}}" value="" placeholder="Enter facebook url">
                        <p id="errfacebook_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Twitter</label>
                        <input type="text" class="form-control ltr" name="twitter_{{$lang->code}}" value="" placeholder="Enter twitter url">
                        <p id="errtwitter_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Instagram</label>
                        <input type="text" class="form-control ltr" name="instagram_{{$lang->code}}" value="" placeholder="Enter instagram url">
                        <p id="errinstagram_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Linkedin</label>
                        <input type="text" class="form-control ltr" name="linkedin_{{$lang->code}}" value="" placeholder="Enter linkedin url">
                        <p id="errlinkedin_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="submitBtn" type="submit" class="btn btn-success">Submit</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Member</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Loading...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="updateBtn" type="submit" class="btn btn-success">Update</button>
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
<div class="modal fade lfm-modal" id="lfmModal3{{$lang->id}}" tabindex="-1" role="dialog" aria-labelledby="lfmModalTitle" aria-hidden="true">
    <i class="fas fa-times-circle"></i>
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <iframe src="{{url('laravel-filemanager')}}?serial=3{{$lang->id}}" style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>
@endforeach
