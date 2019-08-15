<?php

return [

    'sanbox' => [
    	'ipnbusiness' => env('SANBOX_BUSINESS', 'sb-pzhh554963@business.example.com'),

	    //'ipnnotifyurl' => env('SANBOX_NOTIFY_URL', 'https://d926b265.ngrok.io/paypal/notify')

	    'ipnnotifyurl' => env('SANBOX_NOTIFY_URL', 'https://d926b265.ngrok.io/api/paypal-ipn')
    ],

    'live' => [
    	'ipnbusines' => env('LIVE_IPN_BUSINESS', 'example@example.com'),
    	'ipnnotifyurl' => env('LIVE_IPN_NOTIFY_URL', 'https://www.example.com/notify')
    ]

];