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
                            <h1 class="h3">{{ translate('Add Your Product') }}</h1>
                        </div>
                      </div>
                    </div>

                    <form class="" action="{{route('products.store')}}" method="POST" enctype="multipart/form-data" id="choice_form">
                        @csrf
                        <input type="hidden" name="added_by" value="seller">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{translate('Product Information')}}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{translate('Product Name')}}</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="name" placeholder="{{ translate('Product Name') }}" onchange="update_sku()" required>
                                    </div>
                                </div>

                                <div class="form-group row" id="category">
                                    <label class="col-md-3 col-from-label">Kategori</label>
                                    <div class="col-md-8">
                                        <div style="overflow-x: auto; white-space: nowrap;"
                                             class="form-control mb-3 c-pointer" data-toggle="modal"
                                             data-target="#categorySelectModal" id="product_category">Bir kategori seç
                                        </div>
                                        <input type="hidden" name="category_id" id="category_id" value="">
                                        <input type="hidden" name="subcategory_id" id="subcategory_id" value="">
                                        <input type="hidden" name="subsubcategory_id" id="subsubcategory_id" value="">
                                    </div>
                                </div>


                                <div class="form-group row" id="brand">
                                    <label class="col-md-3 col-from-label">{{translate('Brand')}}</label>
                                    <div class="col-md-8">
                                    <input type="text" class="form-control" name="brand_id" placeholder="Marka Yazınız!" required>
                                        <!--<select class="form-control aiz-selectpicker" name="brand_id" id="brand_id"  data-live-search="true">
                                            <option value="">{{ ('Select Brand') }}</option>
                                            @foreach (\App\Brand::all() as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->getTranslation('name') }}</option>
                                            @endforeach
                                        </select>-->
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{translate('Unit')}}</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="unit" placeholder="{{ translate('Unit (e.g. KG, Pc etc)') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{translate('Minimum Qty')}}</label>
                                    <div class="col-md-8">
                                        <input type="number" lang="en" class="form-control" name="min_qty" value="1" min="1" required>
                                    </div>
                                </div>
                                <div class="form-group row d-none">
                                    <label class="col-md-3 col-from-label">{{translate('Tags')}}</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control aiz-tag-input" name="tags[]" placeholder="{{ translate('Type and hit enter to add a tag') }}">
                                    </div>
                                </div>

                                @php
                                    $pos_addon = \App\Addon::where('unique_identifier', 'pos_system')->first();
                                @endphp
                                @if ($pos_addon != null && $pos_addon->activated == 1)
                                    <div class="form-group row d-none">
                                        <label class="col-md-3 col-from-label">{{translate('Barcode')}}</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="barcode" placeholder="{{ translate('Barcode') }}">
                                        </div>
                                    </div>
                                @endif

                                @php
                                    $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
                                @endphp
                                @if ($refund_request_addon != null && $refund_request_addon->activated == 1)
                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label">{{translate('Refundable')}}</label>
                                        <div class="col-md-8">
                                          <label class="aiz-switch aiz-switch-success mb-0">
                                              <input type="checkbox" name="refundable" checked>
                                              <span></span>
                                          </label>
                                        </div>
                                    </div>
                                @endif
                            </div>
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
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">Seçin</div>
                                    </div>
                                    <div class="form-control file-amount">Dosya seçin</div>
                                    <input type="hidden" name="photos" class="selected-files">
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
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">Seçin</div>
                                    </div>
                                    <div class="form-control file-amount">Dosya seçin</div>
                                    <input type="hidden" name="thumbnail_img" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                            </div>
                        </div>
                        <div class="card d-none">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{translate('Product Videos')}}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{translate('Video Provider')}}</label>
                                    <div class="col-md-8">
                                        <select class="form-control aiz-selectpicker" name="video_provider" id="video_provider">
                                            <option value="youtube">{{translate('Youtube')}}</option>
                                            <option value="dailymotion">{{translate('Dailymotion')}}</option>
                                            <option value="vimeo">{{translate('Vimeo')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{translate('Video Link')}}</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="video_link" placeholder="{{ translate('Video Link') }}">
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
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" value="{{translate('Colors')}}" disabled>
                                    </div>
                                    <div class="col-md-8">
                                        <select class="form-control aiz-selectpicker" data-live-search="true" name="colors[]" data-selected-text-format="count" id="colors" multiple disabled>
                                            @foreach (\App\Color::orderBy('name', 'asc')->get() as $key => $color)
                                                <option  value="{{ $color->code }}" data-content="<span><span class='size-15px d-inline-block mr-2 rounded border' style='background:{{ $color->code }}'></span><span>{{ $color->name }}</span></span>"></option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input value="1" type="checkbox" name="colors_active">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" value="{{translate('Attributes')}}" disabled>
                                    </div>
                                    <div class="col-md-8">
                                        <select name="choice_attributes[]" id="choice_attributes" class="form-control aiz-selectpicker" data-live-search="true" data-selected-text-format="count" multiple data-placeholder="Özellikleri Seçin">
                                            @foreach (\App\Attribute::all() as $key => $attribute)
                                            <option value="{{ $attribute->id }}">{{ $attribute->getTranslation('name') }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <p>{{ translate('Choose the attributes of this product and then input values of each attribute') }}</p>
                                    <p>Örnek doldurma</p><img src="{{ static_asset('assets/img/ornek-doldurma.png') }}" alt="Örnek Özellik Doldurma">
                                    <br>
                                </div>

                                <div class="customer_choice_options" id="customer_choice_options">

                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{translate('Product price + stock')}}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{translate('Unit price')}}</label>
                                    <div class="col-md-6">
                                        <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ translate('Unit price') }}" name="unit_price" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group row d-none">
                                    <label class="col-md-3 col-from-label">{{translate('Purchase price')}}</label>
                                    <div class="col-md-6">
                                        <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ translate('Purchase price') }}" name="purchase_price" class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group row d-none">
                                    <label class="col-md-3 col-from-label">{{translate('Tax')}}</label>
                                    <div class="col-md-6">
                                        <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ translate('Tax') }}" name="tax" class="form-control" required>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control aiz-selectpicker" name="tax_type">
                                            <option value="amount">{{translate('Flat')}}</option>
                                            <option value="percent">{{translate('Percent')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{translate('Discount')}}</label>
                                    <div class="col-md-6">
                                        <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ translate('Discount') }}" name="discount" class="form-control" required>
                                    </div>
                                    <div class="col-md-3">
                                        <select class="form-control aiz-selectpicker" name="discount_type">
                                            <option value="amount">{{translate('Flat')}}</option>
                                            <option value="percent">{{translate('Percent')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" id="quantity">
                                    <label class="col-md-3 col-from-label">Stok sayısı</label>
                                    <div class="col-md-6">
                                        <input type="number" lang="en" min="0" value="0" step="1" placeholder="{{ translate('Quantity') }}" name="current_stock" class="form-control" required>
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
                                    <label class="col-md-3 col-from-label">{{translate('Description')}}</label>
                                    <div class="col-md-8">
                                        <textarea class="aiz-text-editor" name="description"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (\App\BusinessSetting::where('type', 'shipping_type')->first()->value == 'product_wise_shipping')
                            <div class="card d-none">
                                <div class="card-header">
                                    <h5 class="mb-0 h6">{{translate('Product Shipping Cost')}}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <div class="col-md-3">
                                            <div class="card-heading">
                                                <h5 class="mb-0 h6">{{translate('Free Shipping')}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group row">
                                                <label class="col-md-3 col-from-label">{{translate('Status')}}</label>
                                                <div class="col-md-8">
                                                    <label class="aiz-switch aiz-switch-success mb-0">
                                                        <input type="radio" name="shipping_type" value="free" checked>
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-3">
                                            <div class="card-heading">
                                                <h5 class="mb-0 h6">{{translate('Flat Rate')}}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="form-group row">
                                                <label class="col-md-3 col-from-label">{{translate('Status')}}</label>
                                                <div class="col-md-8">
                                                    <label class="aiz-switch aiz-switch-success mb-0">
                                                        <input type="radio" name="shipping_type" value="flat_rate" checked>
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-3 col-from-label">{{translate('Shipping cost')}}</label>
                                                <div class="col-md-8">
                                                    <input type="number" lang="en" min="0" value="0" step="0.01" placeholder="{{ translate('Shipping cost') }}" name="flat_shipping_cost" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="card d-none">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{translate('PDF Specification')}}</h5>
                            </div>
                            <div class="card-body">
                              <div class="form-group row">
                                  <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('PDF Specification')}}</label>
                                  <div class="col-md-8">
                                      <div class="input-group" data-toggle="aizuploader" data-type="document">
                                          <div class="input-group-prepend">
                                              <div class="input-group-text bg-soft-secondary font-weight-medium">Seçin</div>
                                          </div>
                                          <div class="form-control file-amount">Dosya seçin</div>
                                          <input type="hidden" name="pdf" class="selected-files">
                                      </div>
                                      <div class="file-preview box sm">
                                      </div>
                                  </div>
                              </div>
                            </div>
                        </div>
                        <div class="card d-none">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{translate('SEO Meta Tags')}}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{translate('Meta Title')}}</label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" name="meta_title" placeholder="{{ translate('Meta Title') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-from-label">{{translate('Description')}}</label>
                                    <div class="col-md-8">
                                        <textarea name="meta_description" rows="8" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('Meta Image') }}</label>
                                    <div class="col-md-8">
                                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">Seçin</div>
                                            </div>
                                            <div class="form-control file-amount">Dosya seçin</div>
                                            <input type="hidden" name="meta_img" class="selected-files">
                                        </div>
                                        <div class="file-preview box sm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mar-all text-right">
                            <button type="submit" id="yla" name="button" class="btn btn-primary" style="display:none">{{ translate('Save Product') }}</button>
                            <div class="btn btn-primary" onclick="yolla()">Ürün Kaydet</div>
                        </div>
                        <script>
                            function yolla(){
                                if($(".file-preview").html()=="")
                                {
                                    AIZ.plugins.notify('danger','Ürün Görselleri Eklemediniz!');
                                }
                               else{
                                   $("#yla").click();
                               }
                            }
                        </script>

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
                                <div class="modal-category-list has-right-arrow">
                                    <ul id="categories" class="list-unstyled" style="margin-top: 10px;">
                                        <li onclick="get_subcategories_by_category(this, 4)">KADIN</li>
                                        <li onclick="get_subcategories_by_category(this, 5)">ERKEK</li>
                                        <li onclick="get_subcategories_by_category(this, 6)">ÇOCUK</li>
                                        <li onclick="get_subcategories_by_category(this, 8)">EV &amp; YAŞAM</li>
                                        <li onclick="get_subcategories_by_category(this, 9)">KÖY PAZARIM</li>
                                        <li onclick="get_subcategories_by_category(this, 10)">KOZMETİK</li>
                                        <li onclick="get_subcategories_by_category(this, 11)">AYAKKABI &amp; CANTA</li>
                                        <li onclick="get_subcategories_by_category(this, 12)">SAAT &amp; AKSESUAR</li>
                                        <li onclick="get_subcategories_by_category(this, 13)">ELEKTRONİK &amp;
                                            AKSESUAR
                                        </li>
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

                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-4" id="subSubCategoriesBox">
                            <div class="modal-category-box c-scrollbar" id="subsubcategory_list">
                                <div class="sort-by-box">
                                    <form role="form" class="search-widget">
                                        <span class='btn btn-primary mb-2 mr-1 backrow'
                                              onclick="$('#subCategoriesBox').show();$('#subsubcategory_list').hide();$('#subSubCategoriesBox').hide()"><i
                                                class="fa fa-arrow-left"></i> Geri</span>
                                        <input class="form-control input-lg" type="text"
                                               placeholder="Alt Alt Kategori Ara"
                                               onkeyup="filterListItems(this, 'subsubcategories')">
                                        <button type="button" class="btn-inner d-none">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="modal-category-list">
                                    <ul id="subsubcategories" class="list-unstyled" style="margin-top:10px">

                                    </ul>
                                </div>
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

                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script type="text/javascript">
        var subsubcategory_name = "";

        var u3ColumnsMode = false;
        if($(window).width() < 720){
            $("#categorySelectModal [role='document']").removeClass("modal-lg")
        }else{
            u3ColumnsMode = true;
            var boxes = $("#categoriesBox, #subCategoriesBox, #subSubCategoriesBox");
            boxes.removeClass("col-12").addClass("col-4");
            boxes.find(".backrow").remove();
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

        $('input[name="unit_price"]').on('keyup', function() {
            update_sku();
        });

        $('input[name="name"]').on('keyup', function() {
            update_sku();
        });

        function delete_row(em){
            $(em).closest('.form-group row').remove();
            update_sku();
        }

        function delete_variant(em){
            $(em).closest('.variant').remove();
        }

        function update_sku(){
            $.ajax({
               type:"POST",
               url:'{{ route('products.sku_combination') }}',
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

        $('#choice_attributes').on('change', function() {
            $('#customer_choice_options').html(null);
            $.each($("#choice_attributes option:selected"), function(){
                add_more_customer_choice_option($(this).val(), $(this).text());
            });
            update_sku();
        });

    </script>

<script>
        $(document).ready(function () {
            $('#subcategory_list').hide();
            $('#subsubcategory_list').hide();
        });
    </script>

<script>
        function get_subcategories_by_category(el, cat_id){
            list_item_highlight(el);
            category_id = cat_id;
            subcategory_id = null;
            subsubcategory_id = null;
            subcategory_name=null;
            subsubcategory_name="";
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
            subsubcategory_name="";
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
            !u3ColumnsMode && closeModal();
            $("#hKategori").html(category_name+'>'+subcategory_name+'>'+subsubcategory_name);
        }
        function get_attributes_by_subsubcategory(subsubcategory_id){
            $.post('/subsubcategories/get_attributes_by_subsubcategory',{_token:'{{ csrf_token() }}', subsubcategory_id:subsubcategory_id}, function(data){
                $('#choice_attributes').html(null);
                for (var i = 0; i < data.length; i++) {
                    $('#choice_attributes').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
            });
        }
        function list_item_highlight(el){
            $(el).parent().children().each(function(){
                $(this).removeClass('selected');
            });
            $(el).addClass('selected');
        }
        //  function get_subcategories_by_category(el, cat_id){
        //    list_item_highlight(el);
        //  category_id = cat_id;
        //    subcategory_id = null;
        //    subsubcategory_id = null;
        //    category_name = $(el).html();
        //    $('#subcategories').html(null);
        //    $('#subsubcategory_list').hide();
        //    $.post('/subcategories/get_subcategories_by_category',{_token:'{{ csrf_token() }}', category_id:category_id}, function(data){
        //   console.log(data);
        //        for (var i = 0; i < data.length; i++) {
        //           $('#subcategories').append('<li onclick="get_subsubcategories_by_subcategory(this, '+data[i].id+')">'+data[i].name+'</li>');
        //       }
        //       !u3ColumnsMode && $('#categoriesBox').hide();
        //       !u3ColumnsMode && $('#subCategoriesBox').show();
        //       $('#subcategory_list').show();
        //   });
        // }
        function closeModal(){
            $("#filters").html("");
            addFilterInput();
            if(category_id > 0 && subcategory_id > 0){
                $('#category_id').val(category_id);
                $('#subcategory_id').val(subcategory_id);
                $('#subsubcategory_id').val(subsubcategory_id);
                $('#product_category').html(category_name+'>'+subcategory_name+'>'+subsubcategory_name);
                $('#categorySelectModal').modal('hide');
                // alert($('#category_id').val() +"-"+$('#subcategory_id').val()+"-"+$('#subsubcategory_id').val());
            }
            else{
                alert('Lütfen kategorileri seçin ...');
                // console.log(category_id);
                // console.log(subcategory_id);
                // console.log(subsubcategory_id);
            }
        }
        function addFilterInput()
        {
            /*$("#filters").append(`
                <div class="row mb-4">
                    <div class="col-md-5 col-sm-12">
                        <select class="form-control filtername marka" onchange="getFilterValues(this)" readonly>
                            <option value="Marka">Marka</option>
                        </select>
                    </div>
                    <div class="col-md-5 col-sm-12">
                        <input class="form-control var-select filtervalue">
                    </div>
                    <div class="col-md-1 col-sm-12 text-center">
                    </div>
                </div>
            `);*/
            updateSelect2();
            try{$(".var-select").select2()}catch(i){}
        }
        function updateSelect2()
        {
            $("select.no-selection").each(function(){
                $(this).select2({});
                $(this).removeClass("no-selection")
            })
        }
        $(document).ready(function(){
            addFilterInput()
        })
    </script>
@endsection
