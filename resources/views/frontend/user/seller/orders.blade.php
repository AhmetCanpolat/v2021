@extends('frontend.layouts.app')

@section('content')

    <section class="py-5">
        <div class="container">
            <div class="d-flex align-items-start">
                @include('frontend.inc.user_side_nav')
                <div class="aiz-user-panel">

                    <div class="card">
                        <form id="sort_orders" action="" method="GET">
                          <div class="card-header row gutters-5">
                            <div class="col text-center text-md-left">
                              <h5 class="mb-md-0 h6">Siparişler</h5>
                            </div>
                              <div class="col-md-3 ml-auto">
                                  <select class="form-control aiz-selectpicker" data-placeholder="Ödeme Durumuna Göre Filtrele" name="payment_status" onchange="sort_orders()">
                                      <option value="">Ödeme Durumuna Göre Filtrele</option>
                                      <option value="paid" @isset($payment_status) @if($payment_status == 'paid') selected @endif @endisset>Ödenmiş</option>
                                      <option value="unpaid" @isset($payment_status) @if($payment_status == 'unpaid') selected @endif @endisset>Ödenmemiş</option>
                                  </select>
                              </div>

                              <div class="col-md-3 ml-auto">
                                <select class="form-control aiz-selectpicker" data-placeholder="Ödeme Durumuna Göre Filtrele" name="delivery_status" onchange="sort_orders()">
                                    <option value="">Teslim Durumuna Göre Filtrele</option>
                                    <option value="pending" @isset($delivery_status) @if($delivery_status == 'pending') selected @endif @endisset>Bekliyor</option>
                                    <option value="confirmed" @isset($delivery_status) @if($delivery_status == 'confirmed') selected @endif @endisset>Onaylanmış</option>
                                    <option value="on_delivery" @isset($delivery_status) @if($delivery_status == 'on_delivery') selected @endif @endisset>Teslimatta</option>
                                    <option value="delivered" @isset($delivery_status) @if($delivery_status == 'delivered') selected @endif @endisset>Teslim edildi</option>
                                </select>
                              </div>
                              <div class="col-md-3">
                                <div class="from-group mb-0">
                                    <input type="text" class="form-control" id="search" name="search" @isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="Sipariş kodunu yazın ve Enter'a basın">
                                </div>
                              </div>
                          </div>
                        </form>

                        @if (count($orders) > 0)
                            <div class="card-body">
                                <table class="table aiz-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th width="20%">Sipariş Kodu</th>
                                            <th>Ürün Sayısı</th>
                                            <th>Müşteri</th>
                                            <th>Miktar</th>
                                            <th>Teslim durumu</th>
                                            <th>Ödeme Durumu</th>
                                            <th>Seçenekler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $key => $order_id)
                                            @php
                                                $order = \App\Order::find($order_id->id);
                                            @endphp
                                            @if($order != null)
                                                <tr>
                                                    <td>
                                                        {{ $key+1 }}
                                                    </td>
                                                    <td>
                                                        <a href="#{{ $order->code }}" onclick="show_order_details({{ $order->id }})">{{ $order->code }}</a>
                                                    </td>
                                                    <td>
                                                        {{ count($order->orderDetails->where('seller_id', Auth::user()->id)) }}
                                                    </td>
                                                    <td>
                                                        @if ($order->user_id != null)
                                                            {{ $order->user->name }}
                                                        @else
                                                            Guest ({{ $order->guest_id }})
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ single_price($order->orderDetails->where('seller_id', Auth::user()->id)->sum('price')) }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $status = $order->orderDetails->first()->delivery_status;
                                                        @endphp
                                                        {{ translate(ucfirst(str_replace('_', ' ', $status))) }}
                                                    </td>
                                                    <td>
                                                        @if ($order->orderDetails->where('seller_id', Auth::user()->id)->first()->payment_status == 'paid')
                                                            <span class="badge badge-inline badge-success">Ödenmiş</span>
                                                        @else
                                                            <span class="badge badge-inline badge-danger">Ödenmemiş</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-right">
                                                        <a href="javascript:void(0)" class="btn btn-soft-info btn-icon btn-circle btn-sm" onclick="show_order_details({{ $order->id }})" title="sipariş detayları">
                                                            <i class="las la-eye"></i>
                                                        </a>
                                                        <a href="{{ route('seller.invoice.download', $order->id) }}" class="btn btn-soft-warning btn-icon btn-circle btn-sm" title="Faturayı İndir">
                                                            <i class="las la-download"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="aiz-pagination">
                                	{{ $orders->links() }}
                              	</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('modal')
    <div class="modal fade" id="order_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div id="order-details-modal-body">

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function sort_orders(el){
            $('#sort_orders').submit();
        }
    </script>
@endsection
