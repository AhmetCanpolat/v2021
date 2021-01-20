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
                            <h1 class="h3">Toplu Ürün Yükleme</h1>
                        </div>
                      </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <table class="table aiz-table mb-0" style="font-size:14px; background-color: #cce5ff; border-color: #b8daff">
                                <tr>
                                    <td>1. İskelet dosyasını indirin ve verilerle doldurun. :</td>
                                </tr>
                                <tr >
                                    <td>2. Verilerin nasıl doldurulması gerektiğini anlamak için örnek dosyayı indirebilirsiniz. :</td>
                                </tr>
                                <tr>
                                    <td>3. İskelet dosyasını indirip doldurduktan sonra, aşağıdaki forma yükleyin ve gönderin. :</td>
                                </tr>
                                <tr>
                                    <td>4. Ürünleri yükledikten sonra onları düzenlemeniz ve ürün resimlerini ve seçeneklerini ayarlamanız gerekir.</td>
                                </tr>
                            </table>
                            <a href="{{ static_asset('download/product_bulk_demo.xlsx') }}" download><button class="btn btn-primary mt-2">PDF'yi indirin</button></a>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <table class="table aiz-table mb-0" style="font-size:14px;background-color: #cce5ff;border-color: #b8daff">
                                <tr>
                                    <td>1. Kategori ve Marka sayısal kimliğe sahip olmalıdır. :</td>
                                </tr>
                                <tr >
                                    <td>2. Kategori ve Marka kimliğini almak için pdf dosyasını indirebilirsiniz. :</td>
                                </tr>
                            </table>
                            <a href="{{ route('pdf.download_category') }}"><button class="btn btn-primary mt-2">Download Category</button></a>
                            <a href="{{ route('pdf.download_brand') }}"><button class="btn btn-primary mt-2">Markayı İndir</button></a>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div class="col text-center text-md-left">
                                <h5 class="mb-md-0 h6">PDF Dosyası Yükle</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <form class="form-horizontal" action="{{ route('bulk_product_upload') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">PDF</label>
                                    <div class="col-sm-10">
                                        <div class="custom-file">
                    						<label class="custom-file-label">
                    							<input type="file" name="bulk_file" class="custom-file-input" required>
                    							<span class="custom-file-name">Dosya seçin</span>
                    						</label>
                    					</div>
                                    </div>
                                </div>
                                <div class="form-group mb-0 text-right">
                                    <button type="submit" class="btn btn-primary">PDF Yükle</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

@endsection
