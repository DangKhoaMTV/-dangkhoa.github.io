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
<form id="ajaxEditForm" class="" action="{{route('admin.special_offer.update')}}" method="post">
    @csrf
    @if (!empty($langs))
        <div class="tab-content">
            @foreach ($langs as $lang)
                <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}"
                     id="edit-lang-{{$lang->code}}">
                    @include('admin.sameContent')
                    <input type="hidden" name="special_offer_id_{{$lang->code}}" value="{{$special_offer[$lang->code]->id}}">

                    {{-- Image Part --}}
                    <div class="form-group">
                        <label for="">Image ** </label>
                        <br>
                        <div class="thumb-preview" id="thumbPreview3{{$lang->id}}">
                            <label for="chooseImage3{{$lang->id}}"><img
                                    src="{{ $special_offer[$lang->code]->image!=''? asset('assets/front/img/special_offers/' . $special_offer[$lang->code]->image): asset('assets/admin/img/noimage.jpg') }}"
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
                               value="{{$special_offer[$lang->code]->title}}" placeholder="Enter title">
                        <p id="errtitle_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group" id="contentFg_{{$lang->code}}">
                        <label for="">Content **</label>
                        <textarea class="form-control summernote" name="content_{{$lang->code}}"
                                  data-height="300"
                                  placeholder="Enter content">{{replaceBaseUrl($special_offer[$lang->code]->content)}}</textarea>
                        <p id="errcontent_{{$lang->code}}" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">Serial Number **</label>
                        <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}"
                               value="{{$special_offer[$lang->code]->serial_number}}" placeholder="Enter Serial Number">
                        <p id="errserial_number_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        <p class="text-warning"><small>The higher the serial number is, the later the special offer will be
                                shown later.</small></p>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Button Text**</label>
                                <input type="text" class="form-control" name="btn_text_{{$lang->code}}"
                                       value="{{$special_offer[$lang->code]->btn_text}}" placeholder="Enter Button text">
                                <p id="errbtn_text_{{$lang->code}}" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Button URL</label>
                                <input type="text" class="form-control" name="btn_url_{{$lang->code}}"
                                       value="{{$special_offer[$lang->code]->btn_url}}" placeholder="Enter URL">
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</form>
