<?php

return [

    'to_contact_email' => env('MAIL_TO_CONTACTFORM', 'info@cowboysonline.nl'),

    'disable_unique_checks' => env('COWBOYS_DISABLE_UNIQUE_CHECKS', false),

    'invoices_path' => env('COWBOYS_INVOICES_PATH', ''),

    'ip_whitelist' => [
        '::1',
        '84.35.57.42',
        '83.98.234.190',
        '80.60.244.124',
        '213.136.28.134',
        '213.125.84.218',
        '213.127.4.234',
        '84.243.240.204',
        '127.0.0.1',
    ],

];
