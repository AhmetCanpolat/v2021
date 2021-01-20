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
                    <form class="" action="{{route('customer_products.store')}}" method="POST" enctype="multipart/form-data" id="choice_form">
                        @csrf
                        <input type="hidden" name="added_by" value="{{ Auth::user()->user_type }}">
                        <input type="hidden" name="status" value="available">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 h6">Genel</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-2 col-from-label">Ürün adı<span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="name" placeholder="Ürün adı" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-from-label">Ürün Kategorisi <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <select class="form-control aiz-selectpicker" data-placeholder="Kategori Seçin" id="categories" name="category_id" data-live-search="true" required>
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
                                    <label class="col-md-2 col-from-label">Ürün markası <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <select class="form-control aiz-selectpicker" data-placeholder="Markayı yazın" data-live-search="true"  id="brands" name="brand_id">
                                            <option value=""></option>
                                            @foreach (\App\Brand::all() as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->getTranslation('name') }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-from-label">Ürün Birimi <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="unit" placeholder="Ürün birimi Adet, kilo vb" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-from-label">{{translate('Condition')}} <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <select class="form-control selectpicker" data-placeholder="Select a condition" id="conditon" name="conditon" required>
                                            <option value="new">Yeni</option>
                                            <option value="used">Kullanımda</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-from-label">Lokasyon <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="location" placeholder="Lokasyon" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-2 col-from-label">Ürün etiketi <span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control aiz-tag-input" name="tags[]" placeholder="Yazın ve enter tuşuna basın">
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
                                    <label class="col-md-2 col-from-label">Galeri Görselleri<span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">Göz at</div>
                                            </div>
                                            <div class="form-control file-amount">Dosya seçin</div>
                                            <input type="hidden" name="photos" class="selected-files">
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
                                            <div class="form-control file-amount">Dosya seçin</div>
                                            <input type="hidden" name="thumbnail_img" class="selected-files">
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
                                        <input type="number" min="0" step="0.01" class="form-control" name="unit_price" placeholder="Birim fiyat (Taban fiyat)" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 h6">Description <span class="text-danger">*</span></h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-md-2 col-from-label">Açıklama</label>
                                    <div class="col-md-10">
                                        <div class="mb-3">
                                            <textarea class="aiz-text-editor" name="description" required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mar-all text-right">
                            <button type="submit" name="button" class="btn btn-primary">Ürünü Yükle</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection
