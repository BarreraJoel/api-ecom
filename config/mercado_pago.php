<?php

use Laravel\Cashier\Console\WebhookCommand;
use Laravel\Cashier\Invoices\DompdfInvoiceRenderer;

return [

    'key' => env('MERCADO_PAGO_KEY'),
    'access_token' => env('MERCADO_PAGO_TOKEN'),

];
