<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add Gallery Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
          @if (!empty($langs))
              <ul class="nav nav-tabs">
                  @foreach ($langs as $lang)
                      <li class="nav-item">
                          <a class="nav-link {{$lang->code == request()->input('language') ? 'active' : ''}}" data-toggle="tab"
                             href="#create-lang-{{$lang->code}}">{{$lang->name}}</a>
                      </li>
                  @endforeach
              </ul>
          @endif
        <form id="ajaxForm" class="modal-form" action="{{ route('admin.gallery.store_category', ['language' => request()->input('language')]) }}" method="post">
          @csrf
            @if (!empty($langs))
                <div class="tab-content">
                    @foreach ($langs as $lang)
                        <div class="tab-pane container {{$lang->code == request()->input('language') ? 'active' : ''}}"
                             id="create-lang-{{$lang->code}}">
                            @include('admin.sameContent')

                            <div class="form-group">
                            <label for="">Category Name*</label>
                            <input type="text" class="form-control" name="name_{{$lang->code}}" placeholder="Enter Category Name">
                            <p id="errname_{{$lang->code}}" class="mt-1 mb-0 text-danger em"></p>
                          </div>

                          <div class="form-group">
                            <label for="">Category Status*</label>
                            <select name="status_{{$lang->code}}" class="form-control ltr">
                              <option selected disabled>Select a Status</option>
                              <option value="1">Active</option>
                              <option value="0">Deactive</option>
                            </select>
                            <p id="errstatus_{{$lang->code}}" class="mt-1 mb-0 text-danger em"></p>
                          </div>

                          <div class="form-group">
                            <label for="">Category Serial Number*</label>
                            <input type="number" class="form-control ltr" name="serial_number_{{$lang->code}}" placeholder="Enter Category Serial Number">
                            <p id="errserial_number_{{$lang->code}}" class="mt-1 mb-0 text-danger em"></p>
                            <p class="text-warning mt-2">
                              <small>The higher the serial number is, the later the category will be shown.</small>
                            </p>
                          </div>
                        </div>
                    @endforeach
                </div>
                @endif
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          Close
        </button>
        <button id="submitBtn" type="button" class="btn btn-primary">
          Save
        </button>
      </div>
    </div>
  </div>
</div>
