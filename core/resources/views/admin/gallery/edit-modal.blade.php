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
<form id="ajaxEditForm" class="modal-form" action="{{ route('admin.gallery.update') }}" method="post">
    @csrf
    @if (!empty($langs))
        <div class="tab-content">
            @foreach ($langs as $lang)
                <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}"
                     id="edit-lang-{{$lang->code}}">
                    @include('admin.sameContent')

                    @if($gallery[$lang->code]->id==0)
                        <div class="form-group">
                            <label class="" for="">Choose association **</label>
                            <select class="form-control select2" name="gallery_assoc_id_{{$lang->code}}">
                                <option value="" selected>Select a blog</option>
                                @foreach ($gcates[$lang->code] as $gcate)
                                    <option value="{{$gcate->id}}">[{{$gcate->id}}-{{$gcate->assoc_id}}
                                        ] {{$gcate->title}}</option>
                                @endforeach
                            </select>
                            <p id="eerrgallery_assoc_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                    @else
                        <input type="hidden" name="gallery_id_{{$lang->code}}" value="{{$gallery[$lang->code]->id}}">
                    @endif

                    {{-- Image Part --}}
                    <div class="form-group">
                        <label for="">Image ** </label>
                        <br>
                        <div class="thumb-preview" id="thumbPreview3{{$lang->id}}">
                           <label for="chooseImage3{{$lang->id}}"><img src="{{$gallery[$lang->code]->image!=''?asset('assets/front/img/gallery/' . $gallery[$lang->code]->image):asset('assets/admin/img/noimage.jpg')}}" alt="User Image"></label>
                        </div>
                        <br>
                        <br>

                        <input id="fileInput3{{$lang->id}}" type="hidden" name="image_{{$lang->code}}">
                        <button id="chooseImage3{{$lang->id}}" class="choose-image btn btn-primary" type="button" data-multiple="false"
                                data-toggle="modal" data-target="#lfmModal3{{$lang->id}}">Choose Image</button>


                        <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
                        <p class="em text-danger mb-0" id="eerrimage_{{$lang->code}}"></p>
                    </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">Type **</label>
                                    <select name="type_{{$lang->code}}" class="form-control" id="egalleryType_{{$lang->code}}">
                                        <option value="image" @if($gallery[$lang->code]->type == 'image') selected @endif>Image</option>
                                        <option value="video"@if($gallery[$lang->code]->type == 'video') selected @endif>Video</option>
                                    </select>
                                    <p id="eerrtype_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group" id="edit_video_url_{{$lang->code}}" @if($gallery[$lang->code]->type == 'image') style="display: none" @endif>
                                    <label for="">Video URL **</label>
                                    <input  @if($gallery[$lang->code]->type == 'image') disabled @endif type="text" class="form-control" name="video_url_{{$lang->code}}" placeholder="Enter video url" value="{{$gallery[$lang->code]->video_url}}">
                                    <p id="eerrvideo_url_{{$lang->code}}" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                        </div>

                    <div class="form-group {{ $categoryInfo->gallery_category_status == 0 ? 'd-none' : '' }}">
                        <label for="">Category **</label>
                        <select name="category_id_{{$lang->code}}" class="form-control">
                            <option disabled selected>Select a category</option>
                            @foreach ($categories[$lang->code] as $category)
                                <option value="{{ $category->id }}" {{ $category->id == $gallery[$lang->code]->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <p id="eerrcategory_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Title **</label>
                        <input type="text" class="form-control" name="title_{{$lang->code}}" value="{{$gallery[$lang->code]->title}}" placeholder="Enter title">
                        <p id="eerrtitle_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Serial Number **</label>
                        <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}" value="{{$gallery[$lang->code]->serial_number}}"
                               placeholder="Enter Serial Number">
                        <p id="eerrserial_number_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        <p class="text-warning"><small>The higher the serial number is, the later the image will be
                                shown.</small></p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</form>
<script>
    (function () {
        @foreach ($langs as $lang)
        $("#egalleryType_{{$lang->code}}").on('change', function () {
            let type = $(this).val();
            console.log(type);
            if (type == 'video') {
                $("#edit_video_url_{{$lang->code}}").show();
                $("#edit_video_url_{{$lang->code}} input").removeAttr('disabled');
            } else {
                $("#edit_video_url_{{$lang->code}} input").attr('disabled', true);
                $("#edit_video_url_{{$lang->code}}").hide();
            }
        });

        $( "input[name='video_url_{{$lang->code}}']" ).change(function() {
            var video_id = $(this).val().split('v=')[1];
            var ampersandPosition = video_id.indexOf('&');
            if(ampersandPosition != -1) {
                video_id = video_id.substring(0, ampersandPosition);
            }
            $('#thumbPreview3{{$lang->id}} img').attr('src','http://i.ytimg.com/vi/'+video_id+'/maxresdefault.jpg');
            $('#fileInput3{{$lang->id}}').val('http://i.ytimg.com/vi/'+video_id+'/maxresdefault.jpg');
        });
        @endforeach
    })();
</script>
