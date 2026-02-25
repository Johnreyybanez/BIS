<?php

return [
    'name' => env('BARANGAY_NAME', 'Barangay System'),
    'version' => '1.0.0',
    
    'certificate_prefix' => 'CTRL-',
    'resident_prefix' => 'RES-',
    'or_prefix' => 'OR-',
    
    'payment_methods' => [
        'cash' => 'Cash',
        'gcash' => 'GCash',
        'maya' => 'Maya',
        'bank' => 'Bank Transfer'
    ],
    
    'civil_status' => [
        'single' => 'Single',
        'married' => 'Married',
        'widowed' => 'Widowed',
        'separated' => 'Separated'
    ],
    
    'blotter_status' => [
        'ongoing' => 'Ongoing',
        'settled' => 'Settled',
        'filed' => 'Filed',
        'dismissed' => 'Dismissed'
    ]
];