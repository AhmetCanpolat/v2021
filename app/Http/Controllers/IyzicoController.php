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
        $options->setApiKey('sandbox-IL3YqFQk1vpCzDjHZiuR7KtWXjfxrZCS');
        $options->setSecretKey('sandbox-ilVKAaMRVhXdp6DfAR0WYu7W0wfuFCFm');
        $options->setBaseUrl("https://sandbox-api.iyzipay.com"); //https://api.iyzipay.com

        foreach (Session::get('cart') as $key => $cartItem){

            $product = \App\Product::find($cartItem['id']);

           $iyzicoRequest = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
           $iyzicoRequest->setLocale(\Iyzipay\Model\Locale::TR);
           $iyzicoRequest->setConversationId(rand(000000,999999));
           $buyer = new \Iyzipay\Model\Buyer();
           $buyer->setId(Session::get('owner_id'));
           $buyer->setName(Session::get('shipping_info')["name"]);
           $buyer->setSurname("soy isim sutunu yok tek sutunda");
           $buyer->setEmail(Session::get('shipping_info')["email"]);
           $buyer->setIdentityNumber("74300864791");
           $buyer->setRegistrationAddress(Session::get('shipping_info')["address"]);
           $buyer->setCity(Session::get('shipping_info')["city"]);
           $buyer->setCountry(Session::get('shipping_info')["country"]);
           $iyzicoRequest->setBuyer($buyer);
           $shippingAddress = new \Iyzipay\Model\Address();
           $shippingAddress->setContactName(Session::get('shipping_info')["name"]);
           $shippingAddress->setCity(Session::get('shipping_info')["city"]);
           $shippingAddress->setCountry(Session::get('shipping_info')["country"]);
           $shippingAddress->setAddress(Session::get('shipping_info')["address"]);
           $iyzicoRequest->setShippingAddress($shippingAddress);

            $billingAddress = new \Iyzipay\Model\Address();
            $billingAddress->setContactName(Session::get('shipping_info')["name"]);
            $billingAddress->setCity(Session::get('shipping_info')["city"]);
            $billingAddress->setCountry(Session::get('shipping_info')["country"]);
            $billingAddress->setAddress(Session::get('shipping_info')["address"]);
            $billingAddress->setZipCode(Session::get('shipping_info')["postal_code"]);
            $iyzicoRequest->setBillingAddress($billingAddress);

               $order = Order::findOrFail(Session::get('order_id'));
               $iyzicoRequest->setPrice(round($order->grand_total));
               $iyzicoRequest->setPaidPrice(round($order->grand_total));
               $iyzicoRequest->setCurrency(\Iyzipay\Model\Currency::TL);
               $iyzicoRequest->setBasketId(rand(000000,999999));
               $iyzicoRequest->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
               $iyzicoRequest->setCallbackUrl(route('iyzico.callback', ['payment_type' => Session::get('payment_type'), 'payment_data' => json_encode(Session::get('payment_data')), 'order_id' => Session::get('order_id')]));
               $basketItems = array();
               $firstBasketItem = new \Iyzipay\Model\BasketItem();
               $firstBasketItem->setId(rand(1000,9999));
               $firstBasketItem->setName($product->name);
               $firstBasketItem->setCategory1($product->category_id);
               $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
               $firstBasketItem->setPrice(round($order->grand_total));
               $basketItems[0] = $firstBasketItem;
               $iyzicoRequest->setBasketItems($basketItems);

            # make request
            $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($iyzicoRequest, $options);

            $paymentinput = $checkoutFormInitialize->getCheckoutFormContent();
            return view('frontend.payment.iyzico',compact('paymentinput'));

            # print result
            print_r($checkoutFormInitialize->getStatus());
            print_r($checkoutFormInitialize->getErrorMessage());
            print_r($checkoutFormInitialize->getCheckoutFormContent());
        }
    }

    public function callback(Request $request, $payment_type, $payment_data, $order_id){
        $options = new \Iyzipay\Options();
        $options->setApiKey('sandbox-IL3YqFQk1vpCzDjHZiuR7KtWXjfxrZCS');
        $options->setSecretKey('sandbox-ilVKAaMRVhXdp6DfAR0WYu7W0wfuFCFm');
        $options->setBaseUrl("https://sandbox-api.iyzipay.com");

        $iyzicoRequest = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
        $iyzicoRequest->setLocale(\Iyzipay\Model\Locale::TR);
        $iyzicoRequest->setConversationId(Session::get('id'));
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
                flash(translate('Üzgünüz bir hata oluştu : Hata Kodu 4542'))->warning();
                return back();
            }
        }
    }
}
