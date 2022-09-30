
    @if (!empty($langs))
        <ul class="nav nav-tabs">
            @foreach ($langs as $lang)
                <li class="nav-item">
                    <a class="nav-link {{$lang->code == request()->input('language') ? 'active' : ''}}" data-toggle="tab" href="#edit-lang-{{$lang->code}}">{{$lang->name}}</a>
                </li>
            @endforeach
        </ul>
    @endif
<form id="ajaxEditForm" class="" action="{{route('admin.slider.update')}}" method="post">
    @csrf
    @if (!empty($langs))
        <div class="tab-content">
            @foreach ($langs as $lang)
                <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}" id="edit-lang-{{$lang->code}}">
                    @include('admin.sameContent')

                    @if($slider[$lang->code]->id==0)
                        <div class="form-group">
                            <label class="" for="">Choose association **</label>
                            <select class="form-control select2" name="slider_assoc_id_{{$lang->code}}">
                                <option value="" selected disabled>Select a category</option>
                                @foreach ($scates[$lang->code] as $scate)
                                    <option value="{{$scate->id}}">[{{$scate->id}}-{{$scate->assoc_id}}] {{$scate->title}}</option>
                                @endforeach
                            </select>
                            <p id="errslider_assoc_id_{{$lang->code}}" class="mb-0 text-danger em"></p>
                        </div>
                    @else
                        <input type="hidden" name="slider_id_{{$lang->code}}" value="{{$slider[$lang->code]->id}}">
                    @endif

    {{-- Image Part --}}
    <div class="form-group">
        <label for="">Image ** </label>
        <br>
        <div class="thumb-preview" id="thumbPreview3{{$lang->id}}">
            <label for="chooseImage3{{$lang->id}}"><img src="{{$slider[$lang->code]->image!=''?asset('assets/front/img/sliders/' . $slider[$lang->code]->image):asset('assets/admin/img/noimage.jpg')}}" alt="Slider Image"></label>
        </div>
        <br>
        <br>


        <input id="fileInput3{{$lang->id}}" type="hidden" name="image_{{$lang->code}}">
        <button id="chooseImage3{{$lang->id}}" class="choose-image btn btn-primary" type="button" data-multiple="false" data-toggle="modal" data-target="#lfmModal3{{$lang->id}}">Choose Image</button>


        <p class="text-warning mb-0">JPG, PNG, JPEG, SVG images are allowed</p>
        <p class="em text-danger mb-0" id="errimage_{{$lang->code}}"></p>

    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label for="">Title </label>
                <input type="text" class="form-control" name="title_{{$lang->code}}" value="{{$slider[$lang->code]->title}}" placeholder="Enter Title">
                <p id="errtitle_{{$lang->code}}" class="text-danger mb-0 em"></p>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="">Title Font Size **</label>
                <input type="number" class="form-control ltr" name="title_font_size_{{$lang->code}}" value="{{$slider[$lang->code]->title_font_size}}">
                <p id="errtitle_font_size_{{$lang->code}}" class="em text-danger mb-0"></p>
            </div>
        </div>
    </div>


    @if ($be->theme_version == 'gym' || $be->theme_version == 'car' || $be->theme_version == 'cleaning')
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label for="">Bold Text </label>
                <input type="text" class="form-control" name="bold_text_{{$lang->code}}" value="{{$slider[$lang->code]->bold_text}}" placeholder="Enter Bold Text">
                <p id="errbold_text_{{$lang->code}}" class="mb-0 text-danger em"></p>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="">Bold Text Font Size **</label>
                <input type="number" class="form-control ltr" name="bold_text_font_size_{{$lang->code}}" value="{{$slider[$lang->code]->bold_text_font_size}}">
                <p id="errbold_text_font_size_{{$lang->code}}" class="em text-danger mb-0"></p>
            </div>
        </div>
    </div>
    @endif



    @if ($be->theme_version == 'cleaning')
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label for="">Bold Text Color **</label>
                <input type="text" class="form-control jscolor" name="bold_text_color_{{$lang->code}}" value="{{$slider[$lang->code]->bold_text_color}}">
                <p id="errbold_text_color_{{$lang->code}}" class="em text-danger mb-0"></p>
            </div>
        </div>
    </div>
    @endif


    @if ($be->theme_version != 'cleaning')
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label for="">Text </label>
                <input type="text" class="form-control" name="text_{{$lang->code}}" value="{{$slider[$lang->code]->text}}" placeholder="Enter Text">
                <p id="errtext_{{$lang->code}}" class="text-danger mb-0 em"></p>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="">Text Font Size **</label>
                <input type="number" class="form-control ltr" name="text_font_size_{{$lang->code}}" value="{{$slider[$lang->code]->text_font_size}}">
                <p id="errtext_font_size_{{$lang->code}}" class="em text-danger mb-0"></p>
            </div>
        </div>
    </div>
    @endif


    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label for="">Button Text </label>
                <input type="text" class="form-control" name="button_text_{{$lang->code}}" value="{{$slider[$lang->code]->button_text}}" placeholder="Enter Button Text">
                <p id="errbutton_text_{{$lang->code}}" class="text-danger mb-0 em"></p>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="">Button Text Font Size **</label>
                <input type="number" class="form-control ltr" name="button_text_font_size_{{$lang->code}}" value="{{$slider[$lang->code]->button_text_font_size}}">
                <p id="errbutton_text_font_size_{{$lang->code}}" class="em text-danger mb-0"></p>
            </div>
        </div>
    </div>


    <div class="form-group">
        <label for="">Button URL **</label>
        <input type="text" class="form-control ltr" name="button_url_{{$lang->code}}" value="{{$slider[$lang->code]->button_url}}" placeholder="Enter Button URL">
        <p id="errbutton_url_{{$lang->code}}" class="text-danger mb-0 em"></p>
    </div>
    <div class="form-group">
        <label for="">Serial Number **</label>
        <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}" value="{{$slider[$lang->code]->serial_number}}" placeholder="Enter Serial Number">
        <p id="errserial_number_{{$lang->code}}" class="mb-0 text-danger em"></p>
        <p class="text-warning"><small>The higher the serial number is, the later the slider will be shown.</small></p>
    </div>
                </div>
            @endforeach
        </div>
    @endif
</form>
