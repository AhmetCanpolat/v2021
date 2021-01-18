<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\BusinessSetting;
use App\Seller;
use App\CustomerPackage;
use App\SellerPackage;
use Session;
use Redirect;

class IyzicoController extends Controller
{
    public function index(Request $iyzicoRequest){

    }

    public function pay(){
        $options = new \Iyzipay\Options();
        $options->setApiKey('sandbox-m766nTpGwDbSU5mAgVfSfUx7UaIL66QD');
        $options->setSecretKey('sandbox-Zzrk3Gwqe60axO77j1GfnepT717tNOaZ');

        if(BusinessSetting::where('type', 'iyzico_sandbox')->first()->value == 1) {
            $options->setBaseUrl("https://sandbox-api.iyzipay.com");
        } else {
            $options->setBaseUrl("https://sandbox-api.iyzipay.com"); //https://api.iyzipay.com
        }

        if(Session::has('payment_type')){


            $request = new \Iyzipay\Request\CreatePaymentRequest();
            $request->setLocale(\Iyzipay\Model\Locale::TR);
            $request->setConversationId("123456789");
            $request->setPrice("1");
            $request->setPaidPrice("1.2");
            $request->setCurrency(\Iyzipay\Model\Currency::TL);
            $request->setInstallment(1);
            $request->setBasketId("B67832");
            $request->setPaymentChannel(\Iyzipay\Model\PaymentChannel::WEB);
            $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
            $request->setCallbackUrl("https://www.merchant.com/callback");
            $paymentCard = new \Iyzipay\Model\PaymentCard();
            $paymentCard->setCardHolderName("John Doe");
            $paymentCard->setCardNumber("5528790000000008");
            $paymentCard->setExpireMonth("12");
            $paymentCard->setExpireYear("2030");
            $paymentCard->setCvc("123");
            $paymentCard->setRegisterCard(0);
            $request->setPaymentCard($paymentCard);
            $buyer = new \Iyzipay\Model\Buyer();
            $buyer->setId("BY789");
            $buyer->setName("John");
            $buyer->setSurname("Doe");
            $buyer->setGsmNumber("+905350000000");
            $buyer->setEmail("email@email.com");
            $buyer->setIdentityNumber("74300864791");
            $buyer->setLastLoginDate("2015-10-05 12:43:35");
            $buyer->setRegistrationDate("2013-04-21 15:12:09");
            $buyer->setRegistrationAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
            $buyer->setIp("85.34.78.112");
            $buyer->setCity("Istanbul");
            $buyer->setCountry("Turkey");
            $buyer->setZipCode("34732");
            $request->setBuyer($buyer);
            $shippingAddress = new \Iyzipay\Model\Address();
            $shippingAddress->setContactName("Jane Doe");
            $shippingAddress->setCity("Istanbul");
            $shippingAddress->setCountry("Turkey");
            $shippingAddress->setAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
            $shippingAddress->setZipCode("34742");
            $request->setShippingAddress($shippingAddress);
            $billingAddress = new \Iyzipay\Model\Address();
            $billingAddress->setContactName("Jane Doe");
            $billingAddress->setCity("Istanbul");
            $billingAddress->setCountry("Turkey");
            $billingAddress->setAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
            $billingAddress->setZipCode("34742");
            $request->setBillingAddress($billingAddress);
            $basketItems = array();
            $firstBasketItem = new \Iyzipay\Model\BasketItem();
            $firstBasketItem->setId("BI101");
            $firstBasketItem->setName("Binocular");
            $firstBasketItem->setCategory1("Collectibles");
            $firstBasketItem->setCategory2("Accessories");
            $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
            $firstBasketItem->setPrice("0.3");
            $basketItems[0] = $firstBasketItem;
            $secondBasketItem = new \Iyzipay\Model\BasketItem();
            $secondBasketItem->setId("BI102");
            $secondBasketItem->setName("Game code");
            $secondBasketItem->setCategory1("Game");
            $secondBasketItem->setCategory2("Online Game Items");
            $secondBasketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
            $secondBasketItem->setPrice("0.5");
            $basketItems[1] = $secondBasketItem;
            $thirdBasketItem = new \Iyzipay\Model\BasketItem();
            $thirdBasketItem->setId("BI103");
            $thirdBasketItem->setName("Usb");
            $thirdBasketItem->setCategory1("Electronics");
            $thirdBasketItem->setCategory2("Usb / Cable");
            $thirdBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
            $thirdBasketItem->setPrice("0.2");
            $basketItems[2] = $thirdBasketItem;
            $request->setBasketItems($basketItems);

            $threedsInitialize = \Iyzipay\Model\ThreedsInitialize::create($request, $options);
            dd("Burada1",$threedsInitialize);

//            $iyzicoRequest = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
//            $iyzicoRequest->setLocale(\Iyzipay\Model\Locale::TR);
//            $iyzicoRequest->setConversationId('123456789');
//
//            $buyer = new \Iyzipay\Model\Buyer();
//            $buyer->setId("BY789");
//            $buyer->setName("John");
//            $buyer->setSurname("Doe");
//            $buyer->setEmail("email@email.com");
//            $buyer->setIdentityNumber("74300864791");
//            $buyer->setRegistrationAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
//            $buyer->setCity("Istanbul");
//            $buyer->setCountry("Turkey");
//            $iyzicoRequest->setBuyer($buyer);
//
//            $shippingAddress = new \Iyzipay\Model\Address();
//            $shippingAddress->setContactName("Jane Doe");
//            $shippingAddress->setCity("Istanbul");
//            $shippingAddress->setCountry("Turkey");
//            $shippingAddress->setAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
//            $iyzicoRequest->setShippingAddress($shippingAddress);
//
//            $billingAddress = new \Iyzipay\Model\Address();
//            $billingAddress->setContactName("Jane Doe");
//            $billingAddress->setCity("Istanbul");
//            $billingAddress->setCountry("Turkey");
//            $billingAddress->setAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
//            $iyzicoRequest->setBillingAddress($billingAddress);
//
//
//            if(Session::get('payment_type') == 'cart_payment'){
//
//                $order = Order::findOrFail(Session::get('order_id'));
//
//
//                $iyzicoRequest->setPrice(round($order->grand_total));
//                $iyzicoRequest->setPaidPrice(round($order->grand_total));
//                $iyzicoRequest->setCurrency(\Iyzipay\Model\Currency::TL);
//                $iyzicoRequest->setBasketId(rand(000000,999999));
//                $iyzicoRequest->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
//                $iyzicoRequest->setCallbackUrl(route('iyzico.callback', ['payment_type' => Session::get('payment_type'), 'payment_data' => json_encode(Session::get('payment_data')), 'order_id' => Session::get('order_id')]));
//
//                $basketItems = array();
//                $firstBasketItem = new \Iyzipay\Model\BasketItem();
//                $firstBasketItem->setId(rand(1000,9999));
//                $firstBasketItem->setName("Cart Payment");
//                $firstBasketItem->setCategory1("Accessories");
//                $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
//                $firstBasketItem->setPrice(round($order->grand_total));
//                $basketItems[0] = $firstBasketItem;
//
//                $iyzicoRequest->setBasketItems($basketItems);
//
//            }

            # make request
            //$checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($iyzicoRequest, $options);

            # print result
            print_r($checkoutFormInitialize->getStatus());
            print_r($checkoutFormInitialize->getErrorMessage());
            print_r($checkoutFormInitialize->getCheckoutFormContent());

            ?>
            <html>
            <body>
            <div id="iyzipay-checkout-form" class="popup"></div>
            </body>
            </html>

        <?php

            return Redirect::to($checkoutFormInitialize->getPaymentPageUrl());
        }
        else {
            flash(translate('Hata oluştu!'))->warning();
            return redirect()->route('cart');
        }
    }

    public function callback(Request $request, $payment_type, $payment_data, $order_id){
        $options = new \Iyzipay\Options();
        $options->setApiKey('sandbox-m766nTpGwDbSU5mAgVfSfUx7UaIL66QD');
        $options->setSecretKey('sandbox-Zzrk3Gwqe60axO77j1GfnepT717tNOaZ');

        if(BusinessSetting::where('type', 'iyzico_sandbox')->first()->value == 1) {
            $options->setBaseUrl("https://sandbox-api.iyzipay.com");
        } else {
            $options->setBaseUrl("https://api.iyzipay.com");
        }

        $iyzicoRequest = new \Iyzipay\Request\RetrievePayWithIyzicoRequest();
        $iyzicoRequest->setLocale(\Iyzipay\Model\Locale::TR);
        $iyzicoRequest->setConversationId('123456789');
        $iyzicoRequest->setToken($request->token);
        # make request
        $checkoutform = \Iyzipay\Model\CheckoutForm::retrieve($iyzicoRequest, $options);

        if ($checkoutform->getStatus() == 'success') {
            if($payment_type == 'cart_payment'){
                $payment = $checkoutform->getRawResult();

                $checkoutController = new CheckoutController;
                return $checkoutController->checkout_done($order_id, $payment);
            }
            else {
                dd($payment_type);
            }
        }
    }
}
