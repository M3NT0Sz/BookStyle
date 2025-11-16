<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mercado Pago Access Token
    |--------------------------------------------------------------------------
    |
    | Seu token de acesso do Mercado Pago. Você pode obter este token
    | em: https://www.mercadopago.com.br/developers/panel/credentials
    |
    */
    'access_token' => env('MERCADOPAGO_ACCESS_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Mercado Pago Public Key
    |--------------------------------------------------------------------------
    |
    | Sua chave pública do Mercado Pago para uso no frontend.
    |
    */
    'public_key' => env('MERCADOPAGO_PUBLIC_KEY'),

    /*
    |--------------------------------------------------------------------------
    | URLs de Retorno
    |--------------------------------------------------------------------------
    |
    | URLs para onde o cliente será redirecionado após o pagamento.
    |
    */
    'success_url' => env('APP_URL') . '/payment/success',
    'failure_url' => env('APP_URL') . '/payment/failure',
    'pending_url' => env('APP_URL') . '/payment/pending',
];
