@extends('frontend.layouts.app')

@section('content')


    <section class="py-5">
        <div class="container">
            <div class="d-flex align-items-start">
                @include('frontend.inc.user_side_nav')

                <div class="aiz-user-panel">

                    <div class="aiz-titlebar mt-2 mb-4">
                      <div class="row align-items-center">
                        <div class="col-md-6">
                            <h1 class="h3">Ürün Güncelleme Sayfası</h1>
                        </div>
                      </div>
                    </div>

                    <form class="" action="{{route('products.update', $product->id)}}" method="POST" enctype="multipart/form-data" id="choice_form">
                        <input name="_method" type="hidden" value="POST">
                        <input type="hidden" name="lang" value="{{ $lang }}">
                        <input type="hidden" name="id" value="{{ $product->id }}">
                        @csrf
                		<input type="hidden" name="added_by" value="seller">
                        <div class="card">
                            <ul class="nav nav-tabs nav-fill border-light">
                                @foreach (\App\Language::all() as $key => $language)
                                    <li class="nav-item d-none">
                                        <a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3" href="{{ route('seller.products.edit', ['id'=>$product->id, 'lang'=> $language->code] ) }}">
                                            <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
                                            <span>{{$language->name}}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{translate('Product Name')}}</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" name="name" placeholder="{{translate('Product Name')}}" value="{{$product->getTranslation('name')}}" required>
                                    </div>
                                </div>

                                <div class="form-group row" id="brand">
                                    <label class="col-lg-3 col-from-label">{{translate('Brand')}}</label>
                                    <div class="col-lg-8">
                                        <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id">
                                            <option value="">{{ ('Select Brand') }}</option>
                                            @foreach (\App\Brand::all() as $brand)
                                                <option value="{{ $brand->id }}" @if($product->brand_id == $brand->id) selected @endif>{{ $brand->getTranslation('name') }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{translate('Unit')}}</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" name="unit" placeholder="{{ translate('Unit (e.g. KG, Pc etc)') }}" value="{{$product->getTranslation('unit')}}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{translate('Minimum Qty')}}</label>
                                    <div class="col-lg-8">
                                        <input type="number" lang="en" class="form-control" name="min_qty" value="@if($product->min_qty <= 1){{1}}@else{{$product->min_qty}}@endif" min="1" required>
                                    </div>
                                </div>

                                <div class="form-group row" style="display: none;">
                                    <label class="col-lg-3 col-from-label">{{translate('Tags')}}</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control aiz-tag-input" name="tags[]" id="tags" value="{{ $product->tags }}" placeholder="{{ translate('Type to add a tag') }}" data-role="tagsinput">
                                    </div>
                                </div>
                                <div class="form-group row" id="category">
                                    <label class="col-lg-3 col-from-label">Kategori</label>
                                    <div class="col-md-8">
                                        @if ($product->subsubcategory_id != null)
                                            <div class="form-control mb-3 c-pointer" data-toggle="modal" data-target="#categorySelectModal" id="product_category">{{$bir .'>'. $iki .'>'.$uc }}</div>
                                        @else
                                            <div class="form-control mb-3 c-pointer" data-toggle="modal" data-target="#categorySelectModal" id="product_category">{{ $bir .'>'. $iki }}</div>
                                        @endif
                                        <input type="hidden" name="category_id" id="category_id" value="{{ $product->category_id }}" required>
                                        <input type="hidden" name="subcategory_id" id="subcategory_id" value="{{ $product->subcategory_id }}" required>
                                        <input type="hidden" name="subsubcategory_id" id="subsubcategory_id" value="{{ $product->subsubcategory_id }}">
                                    </div>


                                    <div class="modal fade" id="categorySelectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                         aria-hidden="true" data-backdrop="static">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h6 class="modal-title" id="exampleModalLabel">Kategori seç</h6>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="target-category heading-6">
                                                        <span class="mr-3">Hedef Kategori: <span id="hKategori"></span></span>
                                                    </div>
                                                    <div class="row no-gutters modal-categories mt-4 mb-2">
                                                        <div class="col-12" id="backButton">
                                                        </div>
                                                        <div class="col-4" id="categoriesBox">
                                                            <div class="modal-category-box c-scrollbar">
                                                                <div class="sort-by-box">
                                                                    <form role="form" class="search-widget">
                                                                        <input class="form-control input-lg" type="text" placeholder="Kategori Ara"
                                                                               onkeyup="filterListItems(this, 'categories')">
                                                                        <button type="button" class="btn-inner d-none">
                                                                            <i class="fa fa-search"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                                <style>
                                                                    #categories{
                                                                        padding: 0;
                                                                    }
                                                                </style>
                                                                <div class="modal-category-list has-right-arrow">
                                                                    <ul id="categories" class="list-unstyled" style="margin-top:10px">
                                                                        @if($bid==4)
                                                                            <li onclick="get_subcategories_by_category(this, 4)" class="secildi">KADIN</li>
                                                                        @else
                                                                            <li onclick="get_subcategories_by_category(this, 4)">KADIN</li>
                                                                        @endif

                                                                        @if($bid==5)
                                                                                <li onclick="get_subcategories_by_category(this, 5)" class="secildi">ERKEK</li>
                                                                        @else
                                                                            <li onclick="get_subcategories_by_category(this, 5)">ERKEK</li>
                                                                        @endif

                                                                            @if($bid==6)
                                                                                <li onclick="get_subcategories_by_category(this, 6)" class="secildi">ÇOCUK</li>
                                                                            @else
                                                                                <li onclick="get_subcategories_by_category(this, 6)">ÇOCUK</li>
                                                                            @endif

                                                                            @if($bid==8)
                                                                                <li onclick="get_subcategories_by_category(this, 8)" class="secildi">EV &amp; YAŞAM</li>
                                                                            @else
                                                                                <li onclick="get_subcategories_by_category(this, 8)">EV &amp; YAŞAM</li>
                                                                            @endif

                                                                            @if($bid==9)
                                                                                <li onclick="get_subcategories_by_category(this, 9)" class="secildi">KÖY PAZARIM</li>
                                                                            @else
                                                                                <li onclick="get_subcategories_by_category(this, 9)">KÖY PAZARIM</li>
                                                                            @endif

                                                                            @if($bid==10)
                                                                                <li onclick="get_subcategories_by_category(this, 10)" class="secildi">KOZMETİK</li>
                                                                            @else
                                                                                <li onclick="get_subcategories_by_category(this, 10)">KOZMETİK</li>
                                                                            @endif

                                                                            @if($bid==11)
                                                                                <li onclick="get_subcategories_by_category(this, 11)" class="secildi">AYAKKABI &amp; CANTA</li>
                                                                            @else
                                                                                <li onclick="get_subcategories_by_category(this, 11)">AYAKKABI &amp; CANTA</li>
                                                                            @endif

                                                                            @if($bid==12)
                                                                                <li onclick="get_subcategories_by_category(this, 12)" class="secildi">SAAT &amp; AKSESUAR</li>
                                                                            @else
                                                                                <li onclick="get_subcategories_by_category(this, 12)">SAAT &amp; AKSESUAR</li>
                                                                            @endif

                                                                            @if($bid==13)
                                                                                <li onclick="get_subcategories_by_category(this, 13)" class="secildi">ELEKTRONİK &amp;
                                                                                    AKSESUAR
                                                                                </li>
                                                                            @else
                                                                                <li onclick="get_subcategories_by_category(this, 13)">ELEKTRONİK &amp;
                                                                                    AKSESUAR
                                                                                </li>
                                                                            @endif
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-4" id="subCategoriesBox">
                                                            <div class="modal-category-box c-scrollbar" id="subcategory_list">
                                                                <div class="sort-by-box">
                                                                    <form role="form" class="search-widget">
                                                                            <span class='btn btn-primary mb-2 mr-1 backrow'
                                                                                  onclick="$('#categoriesBox').show();$('#subCategoriesBox').hide();$('#subcategory_list').hide()"><i
                                                                                        class="fa fa-arrow-left"></i> Geri</span>
                                                                                        <input class="form-control input-lg" type="text" placeholder="Alt Kategori Ara"
                                                                               onkeyup="filterListItems(this, 'subcategories')">
                                                                        <button type="button" class="btn-inner d-none">
                                                                            <i class="fa fa-search"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                                <div class="modal-category-list has-right-arrow">
                                                                    <ul id="subcategories" class="list-unstyled" style="margin-top:10px">
                                                                         @foreach($altKategoriParentList as $val)
                                                                             <li onclick="get_subsubcategories_by_subcategory(this, {{$val->id}})">{{$val->name}}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-4" id="subSubCategoriesBox">
                                                            <div class="modal-category-box c-scrollbar" id="subsubcategory_list">
                                                                <div class="sort-by-box">
                                                                    <form role="form" class="search-widget">
                                                                        <span class='btn btn-primary mb-2 mr-1 backrow'
                                                                            onclick="$('#subCategoriesBox').show();$('#subsubcategory_list').hide();$('#subSubCategoriesBox').hide()">
                                                                            <i class="fa fa-arrow-left"></i>Geri
                                                                        </span>
                                                                           <input class="form-control input-lg" type="text"
                                                                                  placeholder="Alt Alt Kategori Ara"
                                                                                  onkeyup="filterListItems(this, 'subsubcategories')">
                                                                           <button type="button" class="btn-inner d-none" >
                                                                               <i class="fa fa-search"></i>
                                                                           </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                                <div class="modal-category-list">
                                                                    <ul id="subsubcategories" class="list-unstyled" style="margin-top:10px">
                                                                        @foreach($altAltKategoriParentList as $val)
                                                                        <li onclick="confirm_subsubcategory(this, {{$val->id}})">{{$val->name}}</li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal et</button>
                                                    <button type="button" class="btn btn-primary" onclick="closeModal()">Onayla</button>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                        </div>
                            @php
                                   $pos_addon = \App\Addon::where('unique_identifier', 'pos_system')->first();
                                @endphp
                                @if ($pos_addon != null && $pos_addon->activated == 1)
                                    <div style="display: none;" class="form-group row">
                                        <label class="col-lg-3 col-from-label">{{translate('Barcode')}}</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" name="barcode" placeholder="{{ translate('Barcode') }}" value="{{ $product->barcode }}">
                                        </div>
                                    </div>
                                @endif
                            @php
                                    $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
                                @endphp
                                @if ($refund_request_addon != null && $refund_request_addon->activated == 1)
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-from-label">{{translate('Refundable')}}</label>
                                        <div class="col-lg-8">
                                            <label class="aiz-switch aiz-switch-success mb-0" style="margin-top:5px;">
                                                <input type="checkbox" name="refundable" @if ($product->refundable == 1) checked @endif>
                                                <span class="slider round"></span></label>
                                            </label>
                                        </div>
                                    </div>
                                @endif
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{translate('Product Images')}}</h5>
                            </div>
                            <div class="card-body">

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Gallery Images')}}</label>
                                    <div class="col-md-8">
                                        <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                            </div>
                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                            <input type="hidden" name="photos" value="{{ $product->photos }}" class="selected-files">
                                        </div>
                                        <div class="file-preview box sm">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Thumbnail Image')}} <small>(290x300)</small></label>
                                    <div class="col-md-8">
                                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                            </div>
                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                            <input type="hidden" name="thumbnail_img" value="{{ $product->thumbnail_img }}" class="selected-files">
                                        </div>
                                        <div class="file-preview box sm">
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{translate('Gallery Images')}}</label>
                                    <div class="col-lg-8">
                                        <div id="photos">
                                            @if(is_array(json_decode($product->photos)))
                                                @foreach (json_decode($product->photos) as $key => $photo)
                                                    <div class="col-md-4 col-sm-4 col-xs-6">
                                                        <div class="img-upload-preview">
                                                            <img loading="lazy"  src="{{ uploaded_asset($photo) }}" alt="" class="img-responsive">
                                                            <input type="hidden" name="previous_photos[]" value="{{ $photo }}">
                                                            <button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{translate('Thumbnail Image')}} <small>(290x300)</small></label>
                                    <div class="col-lg-8">
                                        <div id="thumbnail_img">
                                            @if ($product->thumbnail_img != null)
                                                <div class="col-md-4 col-sm-4 col-xs-6">
                                                    <div class="img-upload-preview">
                                                        <img loading="lazy"  src="{{ uploaded_asset($product->thumbnail_img) }}" alt="" class="img-responsive">
                                                        <input type="hidden" name="previous_thumbnail_img" value="{{ $product->thumbnail_img }}">
                                                        <button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                        <div class="card" style="display: none;">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{translate('Product Videos')}}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{translate('Video Provider')}}</label>
                                    <div class="col-lg-8">
                                        <select class="form-control aiz-selectpicker" name="video_provider" id="video_provider">
                                            <option value="youtube" <?php if($product->video_provider == 'youtube') echo "selected";?> >{{translate('Youtube')}}</option>
                                            <option value="dailymotion" <?php if($product->video_provider == 'dailymotion') echo "selected";?> >{{translate('Dailymotion')}}</option>
                                            <option value="vimeo" <?php if($product->video_provider == 'vimeo') echo "selected";?> >{{translate('Vimeo')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{translate('Video Link')}}</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" name="video_link" value="{{ $product->video_link }}" placeholder="{{ translate('Video Link') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{translate('Product Variation')}}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control" value="{{translate('Colors')}}" disabled>
                                    </div>
                                    <div class="col-lg-8">
                                        <select class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" name="colors[]" id="colors" multiple>
                                            @foreach (\App\Color::orderBy('name', 'asc')->get() as $key => $color)
                                                <option
                                                    value="{{ $color->code }}"
                                                    data-content="<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:{{ $color->code }}'></span><span>{{ $color->name }}</span></span>"
                                                    <?php if(in_array($color->code, json_decode($product->colors))) echo 'selected'?>
                                                ></option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-1">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input value="1" type="checkbox" name="colors_active" <?php if(count(json_decode($product->colors)) > 0) echo "checked";?> >
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control" value="{{translate('Attributes')}}" disabled>
                                    </div>
                                    <div class="col-lg-8">
                                        <select name="choice_attributes[]" data-live-search="true" data-selected-text-format="count" id="choice_attributes" class="form-control aiz-selectpicker" multiple data-placeholder="{{ translate('Choose Attributes') }}">
                                            @foreach (\App\Attribute::all() as $key => $attribute)
                                                <option value="{{ $attribute->id }}" @if($product->attributes != null && in_array($attribute->id, json_decode($product->attributes, true))) selected @endif>{{ $attribute->getTranslation('name') }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="">
                                    <p>{{ translate('Choose the attributes of this product and then input values of each attribute') }}</p>
                                    <br>
                                </div>

                                <div class="customer_choice_options" id="customer_choice_options">
                                    @foreach (json_decode($product->choice_options) as $key => $choice_option)
                                        <div class="form-group row">
                                            <div class="col-lg-3">
                                                <input type="hidden" name="choice_no[]" value="{{ $choice_option->attribute_id }}">
                                                <input type="text" class="form-control" name="choice[]" value="{{ \App\Attribute::find($choice_option->attribute_id)->getTranslation('name') }}" placeholder="{{ translate('Choice Title') }}" disabled>
                                            </div>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control aiz-tag-input" name="choice_options_{{ $choice_option->attribute_id }}[]" placeholder="{{ translate('Enter choice values') }}" value="{{ implode(',', $choice_option->values) }}" data-on-change="update_sku">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{translate('Product price + stock')}}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{translate('Unit price')}}</label>
                                    <div class="col-lg-6">
                                        <input type="text" placeholder="{{translate('Unit price')}}" name="unit_price" class="form-control" value="{{$product->unit_price}}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{translate('Purchase price')}}</label>
                                    <div class="col-lg-6">
                                        <input type="number" lang="en" min="0" step="0.01" placeholder="{{translate('Purchase price')}}" name="purchase_price" class="form-control" value="{{$product->purchase_price}}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{translate('Tax')}}</label>
                                    <div class="col-lg-6">
                                        <input type="number" lang="en" min="0" step="0.01" placeholder="{{translate('tax')}}" name="tax" class="form-control" value="{{$product->tax}}" required>
                                    </div>
                                    <div class="col-lg-3">
                                        <select class="form-control aiz-selectpicker" name="tax_type" required>
                                            <option value="amount" <?php if($product->tax_type == 'amount') echo "selected";?> >{{translate('Flat')}}</option>
                                            <option value="percent" <?php if($product->tax_type == 'percent') echo "selected";?> >{{translate('Percent')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{translate('Discount')}}</label>
                                    <div class="col-lg-6">
                                        <input type="number" lang="en" min="0" step="0.01" placeholder="{{translate('Discount')}}" name="discount" class="form-control" value="{{ $product->discount }}" required>
                                    </div>
                                    <div class="col-lg-3">
                                        <select class="form-control aiz-selectpicker" name="discount_type" required>
                                            <option value="amount" <?php if($product->discount_type == 'amount') echo "selected";?> >{{translate('Flat')}}</option>
                                            <option value="percent" <?php if($product->discount_type == 'percent') echo "selected";?> >{{translate('Percent')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" id="quantity">
                                    <label class="col-lg-3 col-from-label">{{translate('Quantity')}}</label>
                                    <div class="col-lg-6">
                                        <input type="number" lang="en" value="{{ $product->current_stock }}" step="1" placeholder="{{translate('Quantity')}}" name="current_stock" class="form-control" required>
                                    </div>
                                </div>
                                <br>
                                <div class="sku_combination" id="sku_combination">

                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{translate('Product Description')}}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{translate('Description')}}</label>
                                    <div class="col-lg-9">
                                        <textarea class="aiz-text-editor" name="description">{{$product->getTranslation('description')}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'product_wise_shipping')
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0 h6">{{translate('Product Shipping Cost')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="col-lg-3">
                                            <div class="card-heading">
                                                <h5 class="mb-0 h6">{{translate('Free Shipping')}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-lg-9">
                                            <div class="form-group row">
                                                <label class="col-lg-3 col-from-label">{{translate('Status')}}</label>
                                                <div class="col-lg-8">
                                                    <label class="aiz-switch aiz-switch-success mb-0">
                                                        <input type="radio" name="shipping_type" value="free" @if($product->shipping_type == 'free') checked @endif>
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-lg-3">
                                            <div class="card-heading">
                                                <h5 class="mb-0 h6">{{translate('Flat Rate')}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-lg-9">
                                            <div class="form-group row">
                                                <label class="col-lg-3 col-from-label">{{translate('Status')}}</label>
                                                <div class="col-lg-8">
                                                    <label class="aiz-switch aiz-switch-success mb-0">
                                                        <input type="radio" name="shipping_type" value="flat_rate" @if($product->shipping_type == 'flat_rate') checked @endif>
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-lg-3 col-from-label">{{translate('Shipping cost')}}</label>
                                                <div class="col-lg-8">
                                                    <input type="number" lang="en" min="0" value="{{ $product->shipping_cost }}" step="0.01" placeholder="{{ translate('Shipping cost') }}" name="flat_shipping_cost" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="card" style="display: none;">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{translate('PDF Specification')}}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('PDF Specification')}}</label>
                                    <div class="col-md-8">
                                        <div class="input-group" data-toggle="aizuploader">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                            </div>
                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                            <input type="hidden" name="pdf" value="{{ $product->pdf }}" class="selected-files">
                                        </div>
                                        <div class="file-preview box sm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card"style="display: none;" >
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{translate('SEO Meta Tags')}}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{translate('Meta Title')}}</label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" name="meta_title" value="{{ $product->meta_title }}" placeholder="{{translate('Meta Title')}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label">{{translate('Description')}}</label>
                                    <div class="col-lg-8">
                                        <textarea name="meta_description" rows="8" class="form-control">{{ $product->meta_description }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Meta Images')}}</label>
                                    <div class="col-md-8">
                                        <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                            </div>
                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                            <input type="hidden" name="meta_img" value="{{ $product->meta_img }}" class="selected-files">
                                        </div>
                                        <div class="file-preview box sm">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">{{translate('Slug')}}</label>
                                    <div class="col-lg-8">
                                        <input type="text" placeholder="{{translate('Slug')}}" id="slug" name="slug" value="{{ $product->slug }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mar-all text-right">
                            <button type="submit" name="button" class="btn btn-primary">{{ translate('Update Product') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script type="text/javascript">

var category_id = '{{$bid}}';
       var subcategory_id = '{{$iid}}';
       var subsubcategory_id = '{{$uid}}';
       var category_name='{{$bir}}';
       var subcategory_name='{{$iki}}';
       var subsubcategory_name='{{$uc}}';
       $(document).ready(function () {
           $("#hKategori").html(category_name+'>'+subcategory_name+'>'+subsubcategory_name);
           // $('#subcategory_list').hide();
           // $('#subsubcategory_list').hide();
           //alert(subcategory_id);
        });

        var u3ColumnsMode = false;
        if($(window).width() < 720){
            $("#categorySelectModal [role='document']").removeClass("modal-lg")
        }else{
            u3ColumnsMode = true;
            var boxes = $("#categoriesBox, #subCategoriesBox, #subSubCategoriesBox");
            boxes.removeClass("col-12").addClass("col-4");
            boxes.find(".backrow").remove();

        }
        function list_item_highlight(el){
            $(el).parent().children().each(function(){
                $(this).removeClass('selected');
            });
            $(el).addClass('selected');
        }
            function closeModal(){
                $("#filters").html("");
                addFilterInput();
                console.log();

                if(category_id > 0 && subcategory_id > 0){
                    $('#category_id').val(category_id);
                    $('#subcategory_id').val(subcategory_id);
                    $('#subsubcategory_id').val(subsubcategory_id);
                    $('#product_category').html(category_name+'>'+subcategory_name+'>'+subsubcategory_name);
                    $('#categorySelectModal').modal('hide');
                    // alert($('#category_id').val() +"-"+$('#subcategory_id').val()+"-"+$('#subsubcategory_id').val());
                }
                else{
                    alert('Lütfen Kategori Seçiniz...');
                    // console.log(category_id);
                    // console.log(subcategory_id);
                    // console.log(subsubcategory_id);
                }
            }
           
        function get_subcategories_by_category(el, cat_id){
            list_item_highlight(el);
            category_id = cat_id;
            subcategory_id = null;
            subsubcategory_id = null;
            category_name = $(el).html();
            $('#subcategories').html(null);
            $('#subsubcategory_list').hide();
            $.post('/subcategories/get_subcategories_by_category',{_token:'{{ csrf_token() }}', category_id:category_id}, function(data){
                console.log(data);
                for (var i = 0; i < data.length; i++) {
                    $('#subcategories').append('<li onclick="get_subsubcategories_by_subcategory(this, '+data[i].id+')">'+data[i].name+'</li>');
                }
                !u3ColumnsMode && $('#categoriesBox').hide();
                !u3ColumnsMode && $('#subCategoriesBox').show();
                $('#subcategory_list').show();
            });
            $("#hKategori").html(category_name);
        }

        function get_subsubcategories_by_subcategory(el, subcat_id){
            list_item_highlight(el);
            subcategory_id = subcat_id;
            subsubcategory_id = null;
            subcategory_name = $(el).html();
            $('#subsubcategories').html(null);
            $.post('/subsubcategories/get_subsubcategories_by_subcategory',{_token:'{{ csrf_token() }}', subcategory_id:subcategory_id}, function(data){

                for (var i = 0; i < data.length; i++) {
                    $('#subsubcategories').append('<li onclick="confirm_subsubcategory(this, '+data[i].id+')">'+data[i].name+'</li>');
                }
                !u3ColumnsMode && $('#subCategoriesBox').hide();
                $('#subsubcategory_list').show();
                !u3ColumnsMode && $('#subSubCategoriesBox').show();
            });
            $("#hKategori").html(category_name+'>'+subcategory_name);
        }

        function confirm_subsubcategory(el, subsubcat_id){
            list_item_highlight(el);
            subsubcategory_id = subsubcat_id;
            subsubcategory_name = $(el).html();
            $("#hKategori").html(category_name+'>'+subcategory_name+'>'+subsubcategory_name);
            !u3ColumnsMode && closeModal();
        }

    function add_more_customer_choice_option(i, name){
        $('#customer_choice_options').append('<div class="form-group row"><div class="col-md-3"><input type="hidden" name="choice_no[]" value="'+i+'"><input type="text" class="form-control" name="choice[]" value="'+name+'" placeholder="{{ translate('Choice Title') }}" readonly></div><div class="col-md-8"><input type="text" class="form-control aiz-tag-input" name="choice_options_'+i+'[]" placeholder="{{ translate('Enter choice values') }}" data-on-change="update_sku"></div></div>');

        AIZ.plugins.tagify();
    }

    $('input[name="colors_active"]').on('change', function() {
        if(!$('input[name="colors_active"]').is(':checked')){
            $('#colors').prop('disabled', true);
        }
        else{
            $('#colors').prop('disabled', false);
        }
        update_sku();
    });

    $('#colors').on('change', function() {
        update_sku();
    });

    function delete_row(em){
        $(em).closest('.form-group').remove();
        update_sku();
    }

    function delete_variant(em){
        $(em).closest('.variant').remove();
    }

    function update_sku(){
        $.ajax({
           type:"POST",
           url:'{{ route('products.sku_combination_edit') }}',
           data:$('#choice_form').serialize(),
           success: function(data){
               $('#sku_combination').html(data);
               if (data.length > 1) {
                   $('#quantity').hide();
               }
               else {
                    $('#quantity').show();
               }
           }
       });
    }

    AIZ.plugins.tagify();


    $(document).ready(function(){
        update_sku();

        $('.remove-files').on('click', function(){
            $(this).parents(".col-md-4").remove();
        });
    });

    $('#choice_attributes').on('change', function() {
        $.each($("#choice_attributes option:selected"), function(j, attribute){
            flag = false;
            $('input[name="choice_no[]"]').each(function(i, choice_no) {
                if($(attribute).val() == $(choice_no).val()){
                    flag = true;
                }
            });
            if(!flag){
                add_more_customer_choice_option($(attribute).val(), $(attribute).text());
            }
        });

        var str = @php echo $product->attributes @endphp;

        $.each(str, function(index, value){
            flag = false;
            $.each($("#choice_attributes option:selected"), function(j, attribute){
                if(value == $(attribute).val()){
                    flag = true;
                }
            });
            if(!flag){
                $('input[name="choice_no[]"][value="'+value+'"]').parent().parent().remove();
            }
        });

        update_sku();
    });


    </script>
@endsection
