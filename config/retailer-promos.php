<?php

$defaultTemplate = [
    'lineItemFormRequest' => \App\Http\Requests\Promos\Retailers\DefaultPromoLineItemFormRequest::class,
    'lineItemFields' => [
        'ad_type' => [
            'display' => 'Ad Type',
            'type' => 'input',
        ],
        'ad_cost' => [
            'display' => 'Ad Cost',
            'type' => 'input',
        ],
        'demo' => [
            'display' => 'Demo',
            'type' => 'textarea',
            'width' => '300px',
        ],
        'notes' => [
            'display' => 'Notes',
            'type' => 'textarea',
            'width' => '300px',
        ],
    ],
];

return [
    // Nature's Fare
    9 => [
        'lineItemFormRequest' => \App\Http\Requests\Promos\Retailers\NaturesFarePromoLineItemFormRequest::class,
        'lineItemFields' => [
            'types' => [
                'display' => 'Promo Types',
                'type' => 'checkbox',
                'pivotType' => 'child_concat',
                'values' => [
                    'Flyer Ad',
                    'In-store Feature',
                    'In-store Demo',
                ],
            ],
            'stores' => [
                'display' => '# Stores',
                'type' => 'input',
            ],
            'notes' => [
                'display' => 'Notes',
                'type' => 'textarea',
                'width' => '300px',
            ],
        ],
    ],
    // Wholefoods East
    18 => [
        'formRequest' => \App\Http\Requests\Promos\Retailers\WholeFoodsPromoFormRequest::class,
        'lineItemFormRequest' => \App\Http\Requests\Promos\Retailers\WholeFoodsPromoLineItemFormRequest::class,
        'hidePLDiscount' => true,
        'hideBrandDiscount' => true,
        'hidePricing' => true,
        'onlyPercentDiscount' => true,
        'promoFields' => [
            'authorized_by' => [
                'display' => 'Authorized By',
                'type' => 'input',
            ],
            'phone' => [
                'display' => 'Phone Number',
                'type' => 'input',
            ],
            'email' => [
                'display' => 'Email',
                'type' => 'input',
            ],
            'promo_period' => [
                'display' => 'Promo Period',
                'type' => 'select',
                'values' => [
                    'A',
                    'B',
                    'BOTH',
                    'FLEX',
                ],
            ],
            'header_notes' => [
                'display' => 'Notes',
                'type' => 'textarea',
            ],
        ],
        'lineItemFields' => [
            'mcb' => [
                'display' => 'MCB %',
                'type' => 'input',
            ],
            'scanback_percent' => [
                'display' => 'Scanback %',
                'type' => 'input',
            ],
            'scanback_dollar' => [
                'display' => 'Scanback $',
                'type' => 'input',
            ],
            'scanback_period' => [
                'display' => 'Scanback Period',
                'saveIgnore' => true,
                'type' => 'select',
                'values' => [
                    'A',
                    'B',
                    'BOTH',
                    'FLEX',
                ],
            ],
            'flyer' => [
                'display' => 'Regional Flyer',
                'type' => 'checkbox',
                'values' => [
                    'Yes',
                ],
            ],
            'notes' => [
                'display' => 'Notes',
                'type' => 'textarea',
                'width' => '300px',
            ],
        ],
    ],
    // Wholefoods West
    19 => [
        'formRequest' => \App\Http\Requests\Promos\Retailers\WholeFoodsPromoFormRequest::class,
        'lineItemFormRequest' => \App\Http\Requests\Promos\Retailers\WholeFoodsPromoLineItemFormRequest::class,
        'hidePLDiscount' => true,
        'hideBrandDiscount' => true,
        'hidePricing' => true,
        'onlyPercentDiscount' => true,
        'promoFields' => [
            'authorized_by' => [
                'display' => 'Authorized By',
                'type' => 'input',
            ],
            'phone' => [
                'display' => 'Phone Number',
                'type' => 'input',
            ],
            'email' => [
                'display' => 'Email',
                'type' => 'input',
            ],
            'promo_period' => [
                'display' => 'Promo Period',
                'type' => 'select',
                'values' => [
                    'A',
                    'B',
                    'BOTH',
                    'FLEX',
                ],
            ],
            'header_notes' => [
                'display' => 'Notes',
                'type' => 'textarea',
            ],
        ],
        'lineItemFields' => [
            'mcb' => [
                'display' => 'MCB %',
                'type' => 'input',
            ],
            'scanback_percent' => [
                'display' => 'Scanback %',
                'type' => 'input',
            ],
            'scanback_dollar' => [
                'display' => 'Scanback $',
                'type' => 'input',
            ],
            'scanback_period' => [
                'display' => 'Scanback Period',
                'saveIgnore' => true,
                'type' => 'select',
                'values' => [
                    'A',
                    'B',
                    'BOTH',
                    'FLEX',
                ],
            ],
            'flyer' => [
                'display' => 'Regional Flyer',
                'type' => 'checkbox',
                'values' => [
                    'Yes',
                ],
            ],
            'notes' => [
                'display' => 'Notes',
                'type' => 'textarea',
                'width' => '300px',
            ],
        ],
    ],
    // Wholefoods Canada
    23 => [
        'formRequest' => \App\Http\Requests\Promos\Retailers\WholeFoodsPromoFormRequest::class,
        'lineItemFormRequest' => \App\Http\Requests\Promos\Retailers\WholeFoodsPromoLineItemFormRequest::class,
        'hidePLDiscount' => true,
        'hideBrandDiscount' => true,
        'hidePricing' => true,
        'onlyPercentDiscount' => true,
        'promoFields' => [
            'authorized_by' => [
                'display' => 'Authorized By',
                'type' => 'input',
            ],
            'phone' => [
                'display' => 'Phone Number',
                'type' => 'input',
            ],
            'email' => [
                'display' => 'Email',
                'type' => 'input',
            ],
            'promo_period' => [
                'display' => 'Promo Period',
                'type' => 'select',
                'values' => [
                    'A',
                    'B',
                    'BOTH',
                    'FLEX',
                ],
            ],
            'header_notes' => [
                'display' => 'Notes',
                'type' => 'textarea',
            ],
        ],
        'lineItemFields' => [
            'mcb' => [
                'display' => 'MCB %',
                'type' => 'input',
            ],
            'scanback_percent' => [
                'display' => 'Scanback %',
                'type' => 'input',
            ],
            'scanback_dollar' => [
                'display' => 'Scanback $',
                'type' => 'input',
            ],
            'scanback_period' => [
                'display' => 'Scanback Period',
                'saveIgnore' => true,
                'type' => 'select',
                'values' => [
                    'A',
                    'B',
                    'BOTH',
                    'FLEX',
                ],
            ],
            'flyer' => [
                'display' => 'Regional Flyer',
                'type' => 'checkbox',
                'values' => [
                    'Yes',
                ],
            ],
            'notes' => [
                'display' => 'Notes',
                'type' => 'textarea',
                'width' => '300px',
            ],
        ],
    ],
    // Vitasave
    32 => $defaultTemplate,
    // Finlandia
    27 => $defaultTemplate,
    // Organic Grocer
    36 => $defaultTemplate,
    // Donald's Market
    40 => $defaultTemplate,
    // City Avenue Market
    44 => $defaultTemplate,
];
