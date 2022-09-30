@extends('admin.layout')

@php
$selLang = \App\Language::where('code', request()->input('language'))->first();
  $default = \App\Language::where('is_default', 1)->first();
@endphp
@if(!empty($selLang) && $selLang->rtl == 1)
@section('styles')
<style>
    form:not(.modal-form) input,
    form:not(.modal-form) textarea,
    form:not(.modal-form) select,
    select[name='language'] {
        direction: rtl;
    }
    form:not(.modal-form) .note-editor.note-frame .note-editing-area .note-editable {
        direction: rtl;
        text-align: right;
    }
</style>
@endsection
@endif

@section('content')
  <div class="page-header">
    <h4 class="page-title">Products</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{route('admin.dashboard')}}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">Shop Management</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">Manage Products</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">Products</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card-title d-inline-block">Products</div>
                </div>
                <div class="col-lg-3">
                    @if (!empty($langs))
                        <select name="language" class="form-control" onchange="window.location='{{url()->current() . '?language='}}'+this.value">
                            <option value="" selected disabled>Select a Language</option>
                            @foreach ($langs as $lang)
                                <option value="{{$lang->code}}" {{$lang->code == request()->input('language') ? 'selected' : ''}}>{{$lang->name}}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
                <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
                    <a href="{{route('admin.product.create') . '?language=' . $default->code . '&type=digital'}}" data-url="{{route('admin.product.create_modal') . '?language=' . $default->code . '&type=digital'}}" data-toggle="modal" data-target="#createModal" class="btn btn-primary float-right btn-sm createbtn_url"><i class="fas fa-plus"></i> Add Product</a>
                    <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete" data-href="{{route('admin.product.bulk.delete')}}"><i class="flaticon-interface-5"></i> Delete</button>
                </div>
            </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($products) == 0)
                <h3 class="text-center">NO Products FOUND</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                            <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">Title</th>
                            <th>Price ({{$bex->base_currency_text}})</th>
                        <th scope="col">Category</th>
                        @if ($be->theme_version == 'ecommerce')
                            <th>Featured</th>
                        @endif
                        <th scope="col">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($products as $key => $product)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{$product->id}}">
                          </td>
                          <td>
                              {{strlen($product->title) > 30 ? mb_substr($product->title,0,30,'utf-8') . '...' : $product->title}}
                          </td>
                                <td>{{$product->current_price}}</td>
                          <td>
                            @if (!empty($product->category))
                            {{convertUtf8($product->category ? $product->category->name : '')}}
                            @endif
                          </td>

                          @if ($be->theme_version == 'ecommerce')
                          <td>
                            <form class="d-inline-block" action="{{route('admin.product.feature')}}" id="featureForm{{$product->id}}" method="POST">
                              @csrf
                              <input type="hidden" name="product_id" value="{{$product->id}}">
                              <select name="is_feature" id="" class="form-control form-control-sm
                              @if($product->is_feature == 1)
                              bg-success
                              @else
                              bg-danger
                              @endif
                              " onchange="document.getElementById('featureForm{{$product->id}}').submit();">
                                <option value="1" {{$product->is_feature == 1 ? 'selected' : ''}}>Yes</option>
                                <option value="0"  {{$product->is_feature == 0 ? 'selected' : ''}}>No</option>
                              </select>
                            </form>
                          </td>
                          @endif

                          <td>
                            <a class="btn btn-secondary btn-sm editbtn_url" href="{{route('admin.product.edit', $product->id) . '?language=' . request()->input('language')}}" data-url="{{route('admin.product.edit_modal', $product->id) . '?language=' . request()->input('language'). '&type=' . $product->type}}" data-toggle="modal" data-target="#editModal">
                            <span class="btn-label">
                              <i class="fas fa-edit"></i>
                            </span>
                            </a>
                            <form class="deleteform d-inline-block" action="{{route('admin.product.delete')}}" method="post">
                              @csrf
                              <input type="hidden" name="product_id" value="{{$product->id}}">
                              <button type="submit" class="btn btn-danger btn-sm deletebtn">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
                              </button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Type Product Modal -->
  <div class="modal fade" id="typeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Add Product</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <div class="product-type">
                      <div class="row">
                          <div class="col-lg-6">
                              <a href="{{route('admin.product.create') . '?language=' . $default->code . '&type=digital'}}" data-url="{{route('admin.product.create_modal') . '?language=' . $default->code . '&type=digital'}}" data-toggle="modal" data-target="#createModal" class="d-block createbtn_url">
                                  <div class="card card-stats card-round">
                                      <div class="card-body ">
                                          <div class="row align-items-center">
                                              <div class="col-12">
                                                  <div class="col-icon mx-auto">
                                                      <div class="icon-big text-center icon-success bubble-shadow-small">
                                                          <i class="icon-screen-desktop"></i>
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="col col-stats ml-3 ml-sm-0">
                                                  <div class="numbers mx-auto text-center">
                                                      <h2 class="card-title mt-2 mb-4 text-uppercase">Digital Product</h2>
                                                      <p class="card-category"><strong>Total:</strong> {{$digitalCount}} Items</p>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </a>
                          </div>
                          <div class="col-lg-6">
                              <a href="{{route('admin.product.create') . '?language=' . $default->code . '&type=physical'}}" data-url="{{route('admin.product.create_modal') . '?language=' . $default->code . '&type=physical'}}" data-toggle="modal" data-target="#createModal" class="d-block createbtn_url">
                                  <div class="card card-stats card-round">
                                      <div class="card-body ">
                                          <div class="row align-items-center">
                                              <div class="col-12">
                                                  <div class="col-icon mx-auto">
                                                      <div class="icon-big text-center icon-warning bubble-shadow-small">
                                                          <i class="icon-present"></i>
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="col col-stats ml-3 ml-sm-0">
                                                  <div class="numbers mx-auto text-center">
                                                      <h2 class="card-title mt-2 mb-4 text-uppercase">Physical Product</h2>
                                                      <p class="card-category"><strong>Total:</strong> {{$physicalCount}} Items</p>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </a>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <!-- Create Product Category Modal -->
  <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Add Product</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                <p>Loading...</p>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button id="submitBtn" type="button" class="btn btn-primary">Submit</button>
              </div>
          </div>
      </div>
  </div>

  <!-- Edit Product Category Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Edit Product</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <p>Loading...</p>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button id="updateBtn" type="button" class="btn btn-primary">Save Changes</button>
              </div>
          </div>
      </div>
  </div>

  @foreach ($langs as $lang)
      <!-- Image LFM Modal -->
      <div class="modal fade lfm-modal" id="lfmModal1{{$lang->id}}" tabindex="-1" role="dialog" aria-labelledby="lfmModalTitle"
           aria-hidden="true">
          <i class="fas fa-times-circle"></i>
          <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
              <div class="modal-content">
                  <div class="modal-body p-0">
                      <iframe id="lfmIframe1{{$lang->id}}" src="{{url('laravel-filemanager')}}?serial=1{{$lang->id}}"
                              style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
                  </div>
              </div>
          </div>
      </div>
      <!-- Image LFM Modal -->
      <div class="modal fade lfm-modal" id="lfmModal3{{$lang->id}}" tabindex="-1" role="dialog" aria-labelledby="lfmModalTitle"
           aria-hidden="true">
          <i class="fas fa-times-circle"></i>
          <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
              <div class="modal-content">
                  <div class="modal-body p-0">
                      <iframe id="lfmIframe3{{$lang->id}}" src="{{url('laravel-filemanager')}}?serial=3{{$lang->id}}"
                              style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
                  </div>
              </div>
          </div>
      </div>
      <!-- Image LFM Modal -->
      <div class="modal fade lfm-modal" id="lfmModal2{{$lang->id}}" tabindex="-1" role="dialog" aria-labelledby="lfmModalTitle"
           aria-hidden="true">
          <i class="fas fa-times-circle"></i>
          <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
              <div class="modal-content">
                  <div class="modal-body p-0">
                      <iframe id="lfmIframe2{{$lang->id}}" src="{{url('laravel-filemanager')}}?serial=2{{$lang->id}}"
                              style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
                  </div>
              </div>
          </div>
      </div>

      <!-- Image LFM Modal -->
      <div class="modal fade lfm-modal" id="lfmModal4{{$lang->id}}" tabindex="-1" role="dialog" aria-labelledby="lfmModalTitle"
           aria-hidden="true">
          <i class="fas fa-times-circle"></i>
          <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
              <div class="modal-content">
                  <div class="modal-body p-0">
                      <iframe id="lfmIframe4{{$lang->id}}" src="{{url('laravel-filemanager')}}?serial=4{{$lang->id}}"
                              style="width: 100%; height: 500px; overflow: hidden; border: none;"></iframe>
                  </div>
              </div>
          </div>
      </div>


    <template id="attribute_temp_{{$lang->code}}">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Attribute **</span>
            </div>
            <select name="product_attribute_{{$lang->code}}[_index][attribute_id]" data-index="_index" class="form-control">
                @foreach($attributes[$lang->code] as $attribute)
                      <option value="{{$attribute->id}}" data-assoc_id="{{$attribute->assoc_id}}" >{{$attribute->name}}</option>
                @endforeach
            </select>
            <input type="text" class="form-control ltr" name="product_attribute_{{$lang->code}}[_index][text]" value="" placeholder="Enter Product attribute text">
            <span onclick="removeProductAttr(this);" style="height: 25px" class="btn btn-xs btn-danger"><i class="fas fa-minus"></i></span>
        </div>
        <p id="errproduct_attribute_{{$lang->code}}._index.text" class="mb-0 text-danger em"></p>
    </template>
  @endforeach

@endsection

