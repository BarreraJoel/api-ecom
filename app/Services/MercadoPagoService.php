<?php

namespace App\Services;

use App\Classes\ApiResponse;
use App\Models\Product;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Resources\Preference\Item;
use stdClass;
use Symfony\Component\HttpFoundation\Response;

class MercadoPagoService
{
    private AuthService $authService;

    public function __construct()
    {
        MercadoPagoConfig::setAccessToken(config('mercado_pago.access_token'));
        $this->authService = new AuthService();
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

    private function generateArrayItems($products)
    {
        $items = array();

        foreach ($products as $product) {
            $modelProduct = Product::find($product->product_id);
            $item = new Item;
            $item->id = $modelProduct->id;
            $item->title = $modelProduct->name;
            $item->description = $modelProduct->description;
            $item->currency_id = "ARS";
            $item->quantity = $product->quantity;
            $item->unit_price = $product->unit_price;
            array_push($items, $item);
        }

        return $items;
    }

    public function createPreference($products, $orderId)
    {
        try {
            $items = $this->generateArrayItems($products);
            $user = $this->authService->getCurrentUser();

            $payer = array(
                "name" => $user->name,
                "surname" => $user->lastname,
                "email" => $user->email,
            );

            // $request = $this->createPreferenceRequest($items, $payer);
            $client = new PreferenceClient();

            $preference = $client->create(
                [
                    "items" => $items,
                    "payer" => $payer,
                    "external_reference" => $orderId,
                    "notification_url" => config('app.host_url') . '/api/receive-pay'
                ]
            );

            return $preference;
        } catch (MPApiException $error) {
            return ApiResponse::response(
                false,
                'Hubo un error con Mercado Pago',
                null,
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function getPayment($id)
    {
        $auxPayment = new PaymentClient;
        $payment = $auxPayment->get($id);

        return $payment;
    }
}
