<?php

namespace App\Services;

use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Resources\Preference\Item;

class MercadoPagoService
{
    public function __construct()
    {
        MercadoPagoConfig::setAccessToken(config('mercado_pago.access_token'));
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::SERVER);
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

    public function pay($products)
    {
        // Fill the data about the product(s) being purchased

        $items = array();

        foreach ($products as $product) {
            // Mount the array of products that will integrate the purchase amount
            $item = new Item();
            // $item->id = $product->id;
            $item->title = $product->name;
            $item->description = $product->description;
            $item->currency_id = "ARS";
            $item->quantity = $product->pivot->quantity;
            $item->unit_price = $product->price;
            array_push($items, $item);
        }

        // Retrieve information about the user (use your own function)
        $user = AuthService::getCurrentUser();

        $payer = array(
            "name" => $user->name,
            "surname" => 'velez',
            "email" => $user->email,
        );

        // Create the request object to be sent to the API when the preference is created
        $request = $this->createPreferenceRequest($items, $payer);
        // Instantiate a new Preference Client
        $client = new PreferenceClient();


        // dd($items);


        try {
            // Send the request that will create the new preference for user's checkout flow
            // $preference = $client->create($request);
            $preference = $client->create(
                // $request
                ["items" => $items]
            );

            // Useful props you could use from this object is 'init_point' (URL to Checkout Pro) or the 'id'
            return $preference;
        } catch (MPApiException $error) {
            // Here you might return whatever your app needs.
            // We are returning null here as an example.
            return null;
        }
    }

    public function getPayment() {
        
    }
}
