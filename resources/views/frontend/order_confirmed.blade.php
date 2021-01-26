@extends('frontend.layouts.app')

@section('content')
    @php
        $status = $order->orderDetails->first()->delivery_status;
    @endphp
    <section class="pt-5 mb-4">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="row aiz-steps arrow-divider">
                        <div class="col done">
                            <div class="text-center text-success">
                                <i class="la-3x mb-2 las la-shopping-cart"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block text-capitalize">Sepetim</h3>
                            </div>
                        </div>
                        <div class="col done">
                            <div class="text-center text-success">
                                <i class="la-3x mb-2 las la-map"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block text-capitalize">Kargo Bilgileri</h3>
                            </div>
                        </div>
                        <div class="col done">
                            <div class="text-center text-success">
                                <i class="la-3x mb-2 las la-truck"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block text-capitalize">Teslimat Adres Bilgileri</h3>
                            </div>
                        </div>
                        <div class="col done">
                            <div class="text-center text-success">
                                <i class="la-3x mb-2 las la-credit-card"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block text-capitalize">Ödeme</h3>
                            </div>
                        </div>
                        <div class="col active">
                            <div class="text-center text-primary">
                                <i class="la-3x mb-2 las la-check-circle"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block text-capitalize">Tamamla</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-4">
        <div class="container text-left">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="card shadow-sm border-0 rounded">
                        <div class="card-body">
                            <div class="text-center py-4 mb-4">
                                <i class="la la-check-circle la-3x text-success mb-3"></i>
                                <h1 class="h3 mb-3 fw-600">Siparişiniz için teşekkür ederiz!</h1>
                                <h2 class="h5">Sipariş Kodu: <span class="fw-700 text-primary">{{ $order->code }}</span></h2>
                                <p class="opacity-70 font-italic">Sipariş özetiniz Epostanıza gönderildi {{ json_decode($order->shipping_address)->email }}</p>
                            </div>
                            <div class="mb-4">
                                <h5 class="fw-600 mb-3 fs-17 pb-2">sipariş özeti</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table">
                                            <tr>
                                                <td class="w-50 fw-600">Sipariş Kodu:</td>
                                                <td>{{ $order->code }}</td>
                                            </tr>
                                            <tr>
                                                <td class="w-50 fw-600">Ad Soyad:</td>
                                                <td>{{ json_decode($order->shipping_address)->name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="w-50 fw-600">Eposta:</td>
                                                <td>{{ json_decode($order->shipping_address)->email }}</td>
                                            </tr>
                                            <tr>
                                                <td class="w-50 fw-600">Teslimat Adresi:</td>
                                                <td>{{ json_decode($order->shipping_address)->address }}, {{ json_decode($order->shipping_address)->city }}, {{ json_decode($order->shipping_address)->country }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table">
                                            <tr>
                                                <td class="w-50 fw-600">Sipariş tarihi:</td>
                                                <td>{{ date('d-m-Y H:i A', $order->date) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="w-50 fw-600">Sipariş durumu:</td>
                                                <td>{{ translate(ucfirst(str_replace('_', ' ', $status))) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="w-50 fw-600">Toplam sipariş miktarı:</td>
                                                <td>{{ single_price($order->orderDetails->sum('price') + $order->orderDetails->sum('tax')) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="w-50 fw-600">Kargo:</td>
                                                <td>Sabit Kargo oranı</td>
                                            </tr>
                                            <tr>
                                                <td class="w-50 fw-600">Ödeme şekli:</td>
                                                <td>Kart ile</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h5 class="fw-600 mb-3 fs-17 pb-2">sipariş detayları</h5>
                                <div>
                                    <table class="table table-responsive-md">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th width="30%">Ürün</th>
                                                <th>varyasyon</th>
                                                <th>Miktar</th>
                                                <th>Teslimat Tipi</th>
                                                <th class="text-right">Fiyat</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($order->orderDetails as $key => $orderDetail)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>
                                                        @if ($orderDetail->product != null)
                                                            <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank" class="text-reset">
                                                                {{ $orderDetail->product->getTranslation('name') }}
                                                            </a>
                                                        @else
                                                            <strong>Ürün Kullanılamıyor</strong>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $orderDetail->variation }}
                                                    </td>
                                                    <td>
                                                        {{ $orderDetail->quantity }}
                                                    </td>
                                                    <td>
                                                        @if ($orderDetail->shipping_type != null && $orderDetail->shipping_type == 'home_delivery')
                                                            Adrese teslim
                                                        @elseif ($orderDetail->shipping_type == 'pickup_point')
                                                            @if ($orderDetail->pickup_point != null)
                                                                {{ $orderDetail->pickup_point->getTranslation('name') }} ({{ translate('Alış Noktası') }})
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td class="text-right">{{ single_price($orderDetail->price) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-xl-5 col-md-6 ml-auto mr-0">
                                        <table class="table ">
                                            <tbody>
                                                <tr>
                                                    <th>Ara Toplam</th>
                                                    <td class="text-right">
                                                        <span class="fw-600">{{ single_price($order->orderDetails->sum('price')) }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Kargo</th>
                                                    <td class="text-right">
                                                        <span class="font-italic">{{ single_price($order->orderDetails->sum('shipping_cost')) }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Kupon İndirimi</th>
                                                    <td class="text-right">
                                                        <span class="font-italic">{{ single_price($order->coupon_discount) }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><span class="fw-600">Toplam</span></th>
                                                    <td class="text-right">
                                                        <strong><span>{{ single_price($order->grand_total) }}</span></strong>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
