<div class="modal-header">
    <h5 class="modal-title strong-600 heading-5">Sipariş numarası: {{ $order->code }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

@php
    $status = $order->orderDetails->where('seller_id', Auth::user()->id)->first()->delivery_status;
    $payment_status = $order->orderDetails->where('seller_id', Auth::user()->id)->first()->payment_status;
    $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
@endphp

<div class="modal-body gry-bg px-3 pt-0">
    <div class="py-4">
        <div class="row gutters-5 text-center aiz-steps">
            <div class="col @if($status == 'pending') active @else done @endif">
                <div class="icon">
                    <i class="las la-file-invoice"></i>
                </div>
                <div class="title fs-12">Sipariş verildi</div>
            </div>
            <div class="col @if($status == 'confirmed') active @elseif($status == 'on_delivery' || $status == 'delivered') done @endif">
                <div class="icon">
                    <i class="las la-newspaper"></i>
                </div>
              <div class="title fs-12">Onaylanmış</div>
            </div>
            <div class="col @if($status == 'on_delivery') active @elseif($status == 'delivered') done @endif">
                <div class="icon">
                    <i class="las la-truck"></i>
                </div>
                <div class="title fs-12">Teslimatta</div>
            </div>
            <div class="col @if($status == 'delivered') done @endif">
                <div class="icon">
                    <i class="las la-clipboard-check"></i>
                </div>
                <div class="title fs-12">Teslim edildi</div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="offset-lg-2 col-lg-4 col-sm-6">
            <div class="form-group">
                <select class="form-control aiz-selectpicker form-control-sm"  data-minimum-results-for-search="Infinity" id="update_payment_status">
                    <option value="unpaid" @if ($payment_status == 'unpaid') selected @endif>Unpaid</option>
                    <option value="paid" @if ($payment_status == 'paid') selected @endif>ödenmiş</option>
                </select>
                <label>Ödeme Durumu</label>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6">
            <div class="form-group">
                <select class="form-control aiz-selectpicker form-control-sm"  data-minimum-results-for-search="Infinity" id="update_delivery_status">
                    <option value="pending" @if ($status == 'pending') selected @endif>Bekliyor</option>
                    <option value="confirmed" @if ($status == 'confirmed') selected @endif>Onaylanmış</option>
                    <option value="on_delivery" @if ($status == 'on_delivery') selected @endif>Teslimatta</option>
                    <option value="delivered" @if ($status == 'delivered') selected @endif>Teslim edildi</option>
                </select>
                <label>Teslim durumu</label>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
          <b class="fs-15">sipariş özeti</b>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <table class="table table-borderless">
                        <tr>
                            <td class="w-50 fw-600">Sipariş Kodu:</td>
                            <td>{{ $order->code }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">Müşteri:</td>
                            <td>{{ json_decode($order->shipping_address)->name }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">E-posta adresi:</td>
                            @if ($order->user_id != null)
                                <td>{{ $order->user->email }}</td>
                            @endif
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">Teslimat adresi:</td>
                            <td>{{ json_decode($order->shipping_address)->address }}, {{ json_decode($order->shipping_address)->city }}, {{ json_decode($order->shipping_address)->postal_code }}, {{ json_decode($order->shipping_address)->country }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-lg-6">
                    <table class="table table-borderless">
                        <tr>
                            <td class="w-50 fw-600">Sipariş tarihi:</td>
                            <td>{{ date('d-m-Y H:i A', $order->date) }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">Sipariş durumu:</td>
                            <td>{{ translate($status) }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">Toplam sipariş miktarı:</td>
                            <td>{{ single_price($order->grand_total) }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">Telefon:</td>
                            <td>{{ json_decode($order->shipping_address)->phone }}</td>
                        </tr>
                        <tr>
                            <td class="w-50 fw-600">Ödeme şekli:</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9">
            <div class="card mt-4">
                <div class="card-header">
                  <b class="fs-15">sipariş detayları</b>
                </div>
                <div class="card-body pb-0">
                    <table class="table table-borderless table-responsive">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th width="40%">Ürün</th>
                                <th>varyasyon</th>
                                <th>Miktar</th>
                                <th>Teslimat Tipi</th>
                                <th>Fiyat</th>
                                @if ($refund_request_addon != null && $refund_request_addon->activated == 1)
                                    <th>İade</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>
                                        @if ($orderDetail->product != null)
                                            <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank">{{ $orderDetail->product->getTranslation('name') }}</a>
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
                                                {{ $orderDetail->pickup_point->getTranslation('name') }} (Alış Noktası)
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{ $orderDetail->price }}</td>
                                    @if ($refund_request_addon != null && $refund_request_addon->activated == 1)
                                        <td>
                                            @if ($orderDetail->product != null && $orderDetail->product->refundable != 0 && $orderDetail->refund_request == null)
                                                <button type="submit" class="btn btn-primary btn-sm" onclick="send_refund_request('{{ $orderDetail->id }}')">Gönder</button>
                                            @elseif ($orderDetail->refund_request != null && $orderDetail->refund_request->refund_status == 0)
                                                <b class="text-info">Bekliyor</b>
                                            @elseif ($orderDetail->refund_request != null && $orderDetail->refund_request->refund_status == 1)
                                                <b class="text-success">ödenmiş</b>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card mt-4">
                <div class="card-header">
                  <b class="fs-15">Sipariş miktarı</b>
                </div>
                <div class="card-body pb-0">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td class="w-50 fw-600">ara toplam</th>
                                <td class="text-right">
                                    <span class="strong-600">{{ single_price($order->orderDetails->where('seller_id', Auth::user()->id)->sum('price')) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-50 fw-600">Kargo</th>
                                <td class="text-right">
                                    <span class="text-italic">{{ single_price($order->orderDetails->where('seller_id', Auth::user()->id)->sum('shipping_cost')) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-50 fw-600">Kupon</th>
                                <td class="text-right">
                                    <span class="text-italic">{{ single_price($order->coupon_discount) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="w-50 fw-600">Toplam</th>
                                <td class="text-right">
                                    <strong>
                                        <span>{{ single_price($order->grand_total) }}
                                        </span>
                                    </strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#update_delivery_status').on('change', function(){
        var order_id = {{ $order->id }};
        var status = $('#update_delivery_status').val();
        $.post('{{ route('orders.update_delivery_status') }}', {_token:'{{ @csrf_token() }}',order_id:order_id,status:status}, function(data){
            $('#order_details').modal('hide');
            AIZ.plugins.notify('success', 'Sipariş durumu güncellendi');
            location.reload().setTimeOut(500);
        });
    });

    $('#update_payment_status').on('change', function(){
        var order_id = {{ $order->id }};
        var status = $('#update_payment_status').val();
        $.post('{{ route('orders.update_payment_status') }}', {_token:'{{ @csrf_token() }}',order_id:order_id,status:status}, function(data){
            $('#order_details').modal('hide');
            //console.log(data);
            AIZ.plugins.notify('success', 'Ödeme durumu güncellendi');
            location.reload().setTimeOut(500);
        });
    });
</script>
