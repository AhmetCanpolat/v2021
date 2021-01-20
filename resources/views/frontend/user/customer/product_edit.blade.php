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
                            <h1 class="h3">Ürününüzü Ekleyin</h1>
                        </div>
                      </div>
                    </div>
                    <ul class="nav nav-tabs nav-fill border-light">
          				@foreach (\App\Language::all() as $key => $language)
          					<li class="nav-item">
          						<a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3" href="{{ route('customer_products.edit', ['id'=>$product->id, 'lang'=> $language->code] ) }}">
          							<img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
          							<span>{{ $language->name }}</span>
          						</a>
          					</li>
        	            @endforeach
          			</ul>

                    <form class="" action="{{route('customer_products.update', $product->id)}}" method="POST" enctype="multipart/form-data" id="choice_form">
                        <input name="_method" type="hidden" value="PATCH">
                        <input type="hidden" name="lang" value="{{ $lang }}">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 h6">Genel</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-2 col-from-label">Ürün adı <span class="text-danger">* <i class="las la-language" title="Çevrilebilir"></i></span></label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="name" value="{{ $product->getTranslation('name') }}" placeholder="Ürün adı" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-from-label">Ürün Kategorisi <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <select class="form-control aiz-selectpicker" data-placeholder="Bir kategori seç" id="categories" name="category_id" data-live-search="true" data-selected={{ $product->category_id }} required>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->getTranslation('name') }}</option>
                                                @foreach ($category->childrenCategories as $childCategory)
                                                    @include('categories.child_category', ['child_category' => $childCategory])
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-from-label">Ürün markası<span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <select class="form-control selectpicker" data-placeholder="Marka yazın" data-live-search="true"  id="brands" name="brand_id">
                                            <option value=""></option>
                                            @foreach (\App\Brand::all() as $brand)
                                                <option value="{{ $brand->id }}" @if($brand->id == $product->brand_id) selected @endif>{{ $brand->getTranslation('name') }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-from-label">Ürün Birimi <span class="text-danger">* <i class="las la-language" title="Çevrilebilir"></i></span></label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="unit" value="{{ $product->getTranslation('unit') }}" placeholder="Ürün birimi}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-from-label">Durum <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <select class="form-control selectpicker" data-placeholder="Bir koşul seçin" id="conditon" name="conditon" required>
                                            <option value="new" @if ($product->conditon == 'new') selected @endif>Yeni</option>
                                            <option value="used" @if ($product->conditon == 'used') selected @endif>Kullanılmış</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-from-label">Lokasyon <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="location" value="{{ $product->location }}" placeholder="Lokasyon" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-from-label">Ürün etiketi <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control aiz-tag-input" name="tags[]" value="{{ $product->tags }}" placeholder="Yazın ve enter tuşuna basın">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 h6">Görüntüler</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-2 col-from-label">Ana Görseller <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">Göz at</div>
                                            </div>
                                            <div class="form-control file-amount">Dosya seçin</div>
                                            <input type="hidden" name="photos" class="selected-files" value="{{ $product->photos }}">
                                        </div>
                                        <div class="file-preview box sm">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-from-label">Küçük Resim <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">Göz at</div>
                                            </div>
                                            <div class="form-control file-amount">Choose File</div>
                                            <input type="hidden" name="thumbnail_img" class="selected-files" value="{{ $product->thumbnail_img }}">
                                        </div>
                                        <div class="file-preview box sm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 h6">Fiyat</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-2 col-from-label">Birim fiyat <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input type="number" value="{{ $product->unit_price }}"  min="0" step="0.01" class="form-control" name="unit_price" placeholder="Birim fiyat (Taban fiyat)" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 h6">Açıklama</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-2 col-from-label">Description <span class="text-danger">* <i class="las la-language" title="Çevrilebilir"></i></span></label>
                                    <div class="col-md-10">
                                        <div class="mb-3">
                                            <textarea class="aiz-text-editor" name="description" required>{{$product->getTranslation('description')}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mar-all text-right">
                            <button type="submit" name="button" class="btn btn-primary">Ürünü Güncelle</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>

@endsection
