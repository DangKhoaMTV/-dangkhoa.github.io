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
<form id="ajaxEditForm" class="" action="{{route('admin.blog.update')}}" method="post">
    @csrf

    @if (!empty($langs))
        <div class="tab-content">
            @foreach ($langs as $lang)
                <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}"
                     id="edit-lang-{{$lang->code}}">
                    @include('admin.sameContent')

                    @if($blog[$lang->code]->id==0)
                        <div class="form-group">
                            <label class="" for="">Choose association **</label>
                            <select class="form-control select2" name="blog_assoc_id_{{$lang->code}}">
                                <option value="" selected>Select a blog</option>
                                @foreach ($scates[$lang->code] as $scate)
                                    <option value="{{$scate->id}}">[{{$scate->id}}-{{$scate->assoc_id}}
                                        ] {{$scate->title}}</option>
                                @endforeach
                            </select>
                            <p id="errblog_assoc_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                    @else
                        <input type="hidden" name="blog_id_{{$lang->code}}"
                               value="{{$blog[$lang->code]->id}}">
                    @endif

                    {{-- Image Part --}}
                    <div class="form-group">
                        <label for="">Image ** </label>
                        <br>
                        <div class="thumb-preview" id="thumbPreview3{{$lang->id}}">
                            <label for="chooseImage3{{$lang->id}}"><img src="{{ $blog[$lang->code]->main_image != '' ? asset('assets/front/img/blogs/' . $blog[$lang->code]->main_image) : asset('assets/admin/img/noimage.jpg') }}" alt="User Image"></label>
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
                    <div class="form-group">
                        <label for="">Title **</label>
                        <input type="text" class="form-control" name="title_{{$lang->code}}" value="{{$blog[$lang->code]->title}}"
                               placeholder="Enter title">
                        <p id="errtitle_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Category **</label>
                        <select class="form-control" name="category_{{$lang->code}}">
                            <option value="" selected disabled>Select a category</option>
                            @foreach ($bcats[$lang->code] as $key => $bcat)
                                <option
                                    value="{{$bcat->id}}" {{$bcat->id == $blog[$lang->code]->bcategory_id ? 'selected' : ''}}>{{$bcat->name}}</option>
                            @endforeach
                        </select>
                        <p id="errcategory_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Content **</label>
                        <textarea id="blogContent" class="form-control summernote" name="content_{{$lang->code}}" data-height="300"
                                  placeholder="Enter content">{{replaceBaseUrl($blog[$lang->code]->content)}}</textarea>
                        <p id="errcontent_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Serial Number **</label>
                        <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}"
                               value="{{$blog[$lang->code]->serial_number}}"
                               placeholder="Enter Serial Number">
                        <p id="errserial_number_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        <p class="text-warning"><small>The higher the serial number is, the later the blog will be
                                shown.</small></p>
                    </div>
                    <div class="form-group">
                        <label for="">Meta Keywords</label>
                        <input type="text" class="form-control" name="meta_keywords_{{$lang->code}}" value="{{$blog[$lang->code]->meta_keywords}}"
                               data-role="tagsinput">
                        <p id="errmeta_keywords_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Meta Description</label>
                        <textarea type="text" class="form-control" name="meta_description_{{$lang->code}}"
                                  rows="5">{{$blog[$lang->code]->meta_description}}</textarea>
                        <p id="errmeta_description_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</form>
