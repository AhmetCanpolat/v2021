<?php

namespace App\Http\Controllers;

use App\BusinessSetting;
use Illuminate\Http\Request;
use App\Order;
use IyzipayBootstrap;
use Session;
use Redirect;
use Spatie\DbDumper\DbDumper;

class IyzicoController extends Controller
{
    public function pay(Request $request){

        $CardHolderName = request('holdername');
        $CardNumber = request('cardnumber');
        $ExpireMonth = request('month');
        $ExpireYear = request('year');
        $Cvc = request('cvc');
        $RegisterCard = request('0');
        $clientIP = request()->ip();

        $options = new \Iyzipay\Options();
        $options->setApiKey('sandbox-IL3YqFQk1vpCzDjHZiuR7KtWXjfxrZCS');
        $options->setSecretKey('sandbox-ilVKAaMRVhXdp6DfAR0WYu7W0wfuFCFm');
        $options->setBaseUrl("https://sandbox-api.iyzipay.com"); //https://api.iyzipay.com

        foreach (Session::get('cart') as $key => $cartItem) {
            $product = \App\Product::find($cartItem['id']);
            $order = Order::findOrFail(Session::get('order_id'));

            if(Session::get('payment_type') == 'cart_payment') {
                $request = new \Iyzipay\Request\CreatePaymentRequest();
                $request->setPrice(round($order->grand_total));
                $request->setPaidPrice(round($order->grand_total));
                $request->setBasketId(rand(000000, 999999));
                $request->setLocale(\Iyzipay\Model\Locale::TR);
                $request->setConversationId(Session::get('id'));
                $request->setCurrency(\Iyzipay\Model\Currency::TL);
                $request->setInstallment(1);
                $request->setPaymentChannel(\Iyzipay\Model\PaymentChannel::WEB);
                $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
                $request->setCallbackUrl(route('iyzico.callback', ['payment_type' => Session::get('payment_type'), 'payment_data' => json_encode(Session::get('payment_data')), 'order_id' => Session::get('order_id')]));


                $paymentCard = new \Iyzipay\Model\PaymentCard();
                $paymentCard->setCardHolderName($CardHolderName);
                $paymentCard->setCardNumber($CardNumber);
                $paymentCard->setExpireMonth($ExpireMonth);
                $paymentCard->setExpireYear($ExpireYear);
                $paymentCard->setCvc($Cvc);
                $paymentCard->setRegisterCard($RegisterCard);
                $request->setPaymentCard($paymentCard);


                $buyer = new \Iyzipay\Model\Buyer();
                $buyer->setId(Session::get('cart')["id"]);
                $buyer->setName(Session::get('shipping_info')["name"]);
                $buyer->setSurname("soy isim sutunu yok tek sutunda");
                $buyer->setEmail(Session::get('shipping_info')["email"]);
                $buyer->setIdentityNumber("74300864791");
                $buyer->setRegistrationAddress(Session::get('shipping_info')["address"]);
                $buyer->setIp($clientIP);
                $buyer->setCity(Session::get('shipping_info')["city"]);
                $buyer->setCountry(Session::get('shipping_info')["country"]);
                $buyer->setZipCode(Session::get('shipping_info')["postal_code"]);
                $request->setBuyer($buyer);


                $shippingAddress = new \Iyzipay\Model\Address();
                $shippingAddress->setContactName(Session::get('shipping_info')["name"]);
                $shippingAddress->setCity(Session::get('shipping_info')["city"]);
                $shippingAddress->setCountry(Session::get('shipping_info')["country"]);
                $shippingAddress->setAddress(Session::get('shipping_info')["address"]);
                $shippingAddress->setZipCode(Session::get('shipping_info')["postal_code"]);
                $request->setShippingAddress($shippingAddress);


                $billingAddress = new \Iyzipay\Model\Address();
                $billingAddress->setContactName(Session::get('shipping_info')["name"]);
                $billingAddress->setCity(Session::get('shipping_info')["city"]);
                $billingAddress->setCountry(Session::get('shipping_info')["country"]);
                $billingAddress->setAddress(Session::get('shipping_info')["address"]);
                $billingAddress->setZipCode(Session::get('shipping_info')["postal_code"]);
                $request->setBillingAddress($billingAddress);


                $basketItems = array();


                $firstBasketItem = new \Iyzipay\Model\BasketItem();
                $firstBasketItem->setId(rand(1000, 9999));
                $firstBasketItem->setName(Session::get('shipping_info')["name"]);
                $firstBasketItem->setCategory1($product->category_id);
                $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
                $firstBasketItem->setPrice(round($order->grand_total));
                $basketItems[0] = $firstBasketItem;

                $basketItems[0] = $firstBasketItem;

                $request->setBasketItems($basketItems);

            }

            $threedsInitialize = \Iyzipay\Model\ThreedsInitialize::create($request, $options);
            echo "<pre>";
            print_r($threedsInitialize);

        }


    }

    public function callback(Request $request, $payment_type, $payment_data, $order_id){
        $options = new \Iyzipay\Options();
        $options->setApiKey('sandbox-IL3YqFQk1vpCzDjHZiuR7KtWXjfxrZCS');
        $options->setSecretKey('sandbox-ilVKAaMRVhXdp6DfAR0WYu7W0wfuFCFm');
        $options->setBaseUrl("https://sandbox-api.iyzipay.com");
        $options->setBaseUrl("https://sandbox-api.iyzipay.com"); //https://api.iyzipay.com

        $iyzicoRequest = new \Iyzipay\Request\RetrievePaymentRequest();
        $iyzicoRequest->setLocale(\Iyzipay\Model\Locale::TR);
        $iyzicoRequest->setConversationId(Session::get('id'));
        $iyzicoRequest->setToken($request->token);
        # make request
        $payIyzico = \Iyzipay\Model\Payment::retrieve($iyzicoRequest, $options);

            if ($payIyzico->getStatus() == 'success') {
                if($payment_type == 'cart_payment'){
                    $payment = $payIyzico->getRawResult();

                    $checkoutController = new CheckoutController;
                    return $checkoutController->checkout_done($order_id, $payment);
                }
                else {
                    flash(translate('Üzgünüz bir hata oluştu : Hata Kodu 4542'))->warning();
                    return back();
                }
            }
        }

}
