<?php

namespace App\Services;

use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Resources\Preference\Item;

class MercadoPagoService
{
    public function __construct()
    {
        MercadoPagoConfig::setAccessToken(config('mercado_pago.access_token'));
    }

    function createPreferenceRequest($items, $payer): array
    {
        $paymentMethods = [
            "excluded_payment_methods" => [],
            "installments" => 12,
            "default_installments" => 1
        ];

        $backUrls = array(
            'success' => route('mercadopago.success'),
            'failure' => route('mercadopago.failed')
        );

        $request = [
            "items" => $items,
            "payer" => $payer,
            "payment_methods" => $paymentMethods,
            "back_urls" => $backUrls,
            "statement_descriptor" => "NAME_DISPLAYED_IN_USER_BILLING",
            "external_reference" => "1234567890",
            "expires" => false,
            "auto_return" => 'approved',
        ];

        return $request;
    }

    public function pay($products, $orderId)
    {
        $items = array();

        foreach ($products as $product) {
            $item = new Item();
            $item->title = $product->name;
            $item->description = $product->description;
            $item->currency_id = "ARS";
            $item->quantity = $product->pivot->quantity;
            $item->unit_price = $product->price;
            array_push($items, $item);
        }

        $user = AuthService::getCurrentUser();

        $payer = array(
            "name" => $user->name,
            "surname" => 'velez',
            "email" => $user->email,
        );

        $request = $this->createPreferenceRequest($items, $payer);
        $client = new PreferenceClient();

        try {
            $preference = $client->create(
                [
                    "items" => $items,
                    "external_reference" => $orderId,
                    "notification_url" => config('app.host_url') . '/receive-pay'
                ]
            );

            return $preference;
        } catch (MPApiException $error) {
            return null;
        }
    }

    public function getPayment($id)
    {
        $auxPayment = new PaymentClient;
        $payment = $auxPayment->get($id);

        return $payment;
    }
}
