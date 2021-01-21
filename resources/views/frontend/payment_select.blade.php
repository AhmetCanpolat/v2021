@extends('frontend.layouts.app')

@section('content')
<section class="pt-5 mb-4">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 mx-auto">
                <div class="row aiz-steps arrow-divider">
                    <div class="col done">
                        <div class="text-center text-success">
                            <i class="la-3x mb-2 las la-shopping-cart"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block text-capitalize">{{ translate('1. My Cart')}}</h3>
                        </div>
                    </div>
                    <div class="col done">
                        <div class="text-center text-success">
                            <i class="la-3x mb-2 las la-map"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block text-capitalize">{{ translate('2. Shipping info')}}</h3>
                        </div>
                    </div>
                    <div class="col done">
                        <div class="text-center text-success">
                            <i class="la-3x mb-2 las la-truck"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block text-capitalize">{{ translate('3. Delivery info')}}</h3>
                        </div>
                    </div>
                    <div class="col active">
                        <div class="text-center text-primary">
                            <i class="la-3x mb-2 las la-credit-card"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block text-capitalize">{{ translate('4. Payment')}}</h3>
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center">
                            <i class="la-3x mb-2 opacity-50 las la-check-circle"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50 text-capitalize">{{ translate('5. Confirmation')}}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="mb-4">
    <div class="container text-left">
        <div class="row">
            <div class="col-lg-8">
                <form action="{{ route('payment.iyzico') }}" class="form-default" role="form" method="POST" id="checkout-form">
                    @csrf
                    {{method_field('post')}}
                    <div class="card shadow-sm border-0 rounded">
                        <div class="card-header p-3">
                            <h3 class="fs-16 fw-600 mb-0">
                                Ödeme yapın
                            </h3>
                        </div>
                        <div class="card-body text-center">

                            <div class="col-12 col-md-4">
                                <div class="form-group"> <label for="holdername">
                                        <h6>Ad Soyad</h6>
                                    </label> <input type="text" name="holdername" placeholder="Kart sahibi adı soyadı" required class="form-control "> </div>
                                <div class="form-group"> <label for="cardnumber">
                                            <h6>Kart Numarası</h6>
                                    </label>
                                    <div class="input-group"> <input type="text" name="cardnumber" placeholder="Kart numarası" class="form-control " required>
                                        <div class="input-group-append"> <span class="input-group-text text-muted"> <i class="fab fa-cc-visa mx-1"></i> <i class="fab fa-cc-mastercard mx-1"></i> <i class="fab fa-cc-amex mx-1"></i> </span> </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="form-group"> <label><span class="hidden-xs">
                                                    <h6>Ay/Yıl</h6>
                                                </span></label>
                                            <div class="input-group"> <input type="number" placeholder="AY" name="month" class="form-control" required> <input type="number" placeholder="YIL" name="year" class="form-control" required> </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group mb-4"> <label data-toggle="tooltip" title="Three digit CV code on the back of your card">
                                                <h6>CVC<i class="fa fa-question-circle d-inline"></i></h6>
                                            </label> <input type="text" placeholder="CVC" name="cvc" required class="form-control"> </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="pt-3">
                        <label class="aiz-checkbox">
                            <input type="checkbox" required id="agree_checkbox">
                            <span class="aiz-square-check"></span>
                            <span>{{ translate('I agree to the')}}</span>
                        </label>
                        <a href="{{ route('terms') }}">{{ translate('terms and conditions')}}</a>,
                        <a href="{{ route('returnpolicy') }}">{{ translate('return policy')}}</a> &
                        <a href="{{ route('privacypolicy') }}">{{ translate('privacy policy')}}</a>
                    </div>

                    <div class="row align-items-center pt-3">
                        <div class="col-6">
                            <a href="{{ route('home') }}" class="link link--style-3">
                                <i class="las la-arrow-left"></i>
                                {{ translate('Return to shop')}}
                            </a>
                        </div>
                        <div class="col-6 text-right">
                            <button type="button" onclick="submitOrder(this)" class="btn btn-primary fw-600">{{ translate('Complete Order')}}</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-4 mt-4 mt-lg-0">
                @include('frontend.partials.cart_summary')
            </div>
        </div>
    </div>
</section>


    <style>
        .rounded {
            border-radius: 1rem
        }

        .nav-pills .nav-link {
            color: #555
        }

        .nav-pills .nav-link.active {
            color: white
        }

        input[type="radio"] {
            margin-right: 5px
        }

        .bold {
            font-weight: bold
        }
    </style>
@endsection

@section('script')
    <script type="text/javascript">

        $(document).ready(function(){
            $(".online_payment").click(function(){

                $('#manual_payment_description').parent().addClass('d-none');

            });
            toggleManualPaymentData($('input[name=payment_option]:checked').data('id'));
        });

        function use_wallet(){
            $('input[name=payment_option]').val('wallet');
            if($('#agree_checkbox').is(":checked")){
                $('#checkout-form').submit();
            }else{
                AIZ.plugins.notify('danger','{{ translate('You need to agree with our policies') }}');
            }
        }
        function submitOrder(el){
            $(el).prop('disabled', true);
            if($('#agree_checkbox').is(":checked")){
                $('#checkout-form').submit();
            }else{
                AIZ.plugins.notify('danger','{{ translate('You need to agree with our policies') }}');
                $(el).prop('disabled', false);
            }
        }

        function toggleManualPaymentData(id){
            $('#manual_payment_description').parent().removeClass('d-none');

             $('#manual_payment_description').html($('#manual_payment_info_'+id).html());
        }
    </script>
@endsection
