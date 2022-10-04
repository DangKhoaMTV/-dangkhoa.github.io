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
<form id="ajaxEditForm" class="" action="{{route('admin.dinning.update')}}" method="post">
    @csrf
    @if (!empty($langs))
        <div class="tab-content">
            @foreach ($langs as $lang)
                <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}"
                     id="edit-lang-{{$lang->code}}">
                    @include('admin.sameContent')

                    @if($dinning[$lang->code]->id==0)
                        <div class="form-group">
                            <label class="" for="">Choose association **</label>
                            <select class="form-control select2" name="dinning_assoc_id_{{$lang->code}}">
                                <option value="" selected>Select a dinning</option>
                                @foreach ($scates[$lang->code] as $scate)
                                    <option value="{{$scate->id}}">[{{$scate->id}}-{{$scate->assoc_id}}
                                        ] {{$scate->title}}</option>
                                @endforeach
                            </select>
                            <p id="errdinning_assoc_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                    @else
                        <input type="hidden" name="dinning_id_{{$lang->code}}" value="{{$dinning[$lang->code]->id}}">
                    @endif

                    {{-- Image Part --}}
                    <div class="form-group">
                        <label for="">Image ** </label>
                        <br>
                        <div class="thumb-preview" id="thumbPreview3{{$lang->id}}">
                            <label for="chooseImage3{{$lang->id}}"><img
                                    src="{{ $dinning[$lang->code]->main_image!=''? asset('assets/front/img/dinnings/' . $dinning[$lang->code]->main_image): asset('assets/admin/img/noimage.jpg') }}"
                                    alt="User Image"></label>
                        </div>
                        <br>
                        <br>


                        <input id="fileInput3{{$lang->id}}" type="hidden" name="image_{{$lang->code}}">
                        <button id="chooseImage3{{$lang->id}}" class="choose-image btn btn-primary" type="button"
                                data-multiple="false" data-toggle="modal" data-target="#lfmModal3{{$lang->id}}">Choose
                            Image
                        </button>


                        <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
                        <p class="em text-danger mb-0" id="errimage_{{$lang->code}}"></p>


                    </div>

                    <div class="form-group">
                        <label for="">Title **</label>
                        <input type="text" class="form-control" name="title_{{$lang->code}}"
                               value="{{$dinning[$lang->code]->title}}" placeholder="Enter title">
                        <p id="eerrtitle_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">Summary **</label>
                        <textarea class="form-control" name="summary_{{$lang->code}}" placeholder="Enter summary"
                                  rows="3">{{$dinning[$lang->code]->summary}}</textarea>
                        <p id="eerrsummary_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>


                    <div class="form-group">
                        <label>Details Page **</label>
                        <div class="selectgroup w-100">
                            <label class="selectgroup-item">
                                <input type="radio" name="details_page_status_{{$lang->code}}"
                                       data-lang="{{$lang->code}}" value="1"
                                       class="selectgroup-input" {{$dinning[$lang->code]->details_page_status == 1 ? 'checked' : ''}}>
                                <span class="selectgroup-button">Enable</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="radio" name="details_page_status_{{$lang->code}}"
                                       data-lang="{{$lang->code}}" value="0"
                                       class="selectgroup-input" {{$dinning[$lang->code]->details_page_status == 0 ? 'checked' : ''}}>
                                <span class="selectgroup-button">Disable</span>
                            </label>
                        </div>
                        <p id="eerrdetails_page_status_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group" id="contentFg_{{$lang->code}}">
                        <label for="">Content **</label>
                        <textarea id="dinningContent" class="form-control summernote" name="content_{{$lang->code}}"
                                  data-height="300"
                                  placeholder="Enter content">{{replaceBaseUrl($dinning[$lang->code]->content)}}</textarea>
                        <p id="eerrcontent_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>


                    <div class="form-group">
                        <label for="">Menu PDF Link **</label>
                        <input type="text" class="form-control ltr" name="pdf_link_{{$lang->code}}" value="{{$dinning[$lang->code]->pdf_link}}" placeholder="Enter Serial Number">
                        <p id="eerrpdf_link_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        <p class="text-warning"><small>Only Google Drive Link.</small></p>
                    </div>

                    <div class="form-group">
                        <label for="">Serial Number **</label>
                        <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}"
                               value="{{$dinning[$lang->code]->serial_number}}" placeholder="Enter Serial Number">
                        <p id="eerrserial_number_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        <p class="text-warning"><small>The higher the serial number is, the later the dinning will be
                                shown everywhere.</small></p>
                    </div>
                    <div class="form-group">
                        <label>Meta Keywords</label>
                        <input class="form-control" name="meta_keywords_{{$lang->code}}"
                               value="{{$dinning[$lang->code]->meta_keywords}}" placeholder="Enter meta keywords"
                               data-role="tagsinput">
                        @if ($errors->has('meta_keywords_'.$lang->code))
                            <p class="mb-0 text-danger">{{$errors->first('meta_keywords_'.$lang->code)}}</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Meta Description</label>
                        <textarea class="form-control" name="meta_description_{{$lang->code}}" rows="5"
                                  placeholder="Enter meta description">{{$dinning[$lang->code]->meta_description}}</textarea>
                        @if ($errors->has('meta_description_'.$lang->code))
                            <p class="mb-0 text-danger">{{$errors->first('meta_description_'.$lang->code)}}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</form>
<script>
    $("input[name^='details_page_status']").off('change').on('change', function () {
        toggleDetails();
    });
    (function () {
        toggleDetails();
    })();
</script>
