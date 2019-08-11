<?php

return [

    'sanbox' => [
    	'ipnbusiness' => env('SANBOX_BUSINESS', 'sb-pzhh554963@business.example.com'),

	    'ipnnotifynurl' => env('SANBOX_NOTIFY_URL', 'https://ea93c183.ngrok.io/paypal/notify')
    ],

    'live' => [
    	'ipnbusines' => env('LIVE_IPN_BUSINESS', 'example@example.com'),
    	'ipnnotifyurl' => env('LIVE_IPN_NOTIFY_URL', 'https://www.example.com/notify')
    ]

];