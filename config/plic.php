<?php

return [
    'outdated_signoff_cleanup' => [
        'draft_delay_days' => env('DRAFT_SIGNOFF_CLEANUP_DELAY_DAYS', 30),
        'archived_delay_days' => env('ARCHIVED_SIGNOFF_CLEANUP_DELAY_DAYS', 30),
    ],

    'checkins' => [
        'daily_cleanup_command' => env('CHECKIN_DAILY_CLEANUP_COMMAND', ''),
    ],

    'notifications' => [
        'email' => [
            'max_per_minute' => env('NOTIFICATIONS_EMAIL_MAX_PER_MINUTE', 30),
        ],
    ],

    'brand_deductions' => [
        'import_path' => env('DEDUCTIONS_PATH', '/mnt/plicshare/Deductions/'),
    ],

    'listing_forms' => [
        'Blush Lane/SPUD' => \App\Exports\ListingForms\BlushSpudListingForm::class,
        'BuyWell' => \App\Exports\ListingForms\BuyWellListingForm::class,
        'Calgary Co-op' => \App\Exports\ListingForms\CalgaryCoopListingForm::class,
        'Choices Market' => \App\Exports\ListingForms\ChoicesMarketListingForm::class,
        'Coleman\'s' => \App\Exports\ListingForms\ColemansListingForm::class,
        'Fiddleheads' => \App\Exports\ListingForms\FiddleheadsListingForm::class,
        'GoodnessMe' => \App\Exports\ListingForms\GoodnessMeListingForm::class,
        'Healthy Planet' => \App\Exports\ListingForms\HealthyPlanetListingForm::class,
        'Kardish' => \App\Exports\ListingForms\KardishListingForm::class,
        'Longo\'s' => \App\Exports\ListingForms\LongosListingForm::class,
        'Natures Fare' => \App\Exports\ListingForms\NaturesFareListingForm::class,
        'Natures Emporium' => \App\Exports\ListingForms\NaturesEmporiumListingForm::class,
        'Natures Source' => \App\Exports\ListingForms\NaturesSourceListingForm::class,
        'Nesters' => \App\Exports\ListingForms\NestersListingForm::class,
        'Organic Garage' => \App\Exports\ListingForms\OrganicGarageListingForm::class,
        'Pomme Grocery' => \App\Exports\ListingForms\PommeGroceryListingForm::class,
        'Pomme HABA' => \App\Exports\ListingForms\PommeHabaListingForm::class,
        'Pusateri\'s' => \App\Exports\ListingForms\PusaterisListingForm::class,
        'Red River Coop' => \App\Exports\ListingForms\RedRiverListingForm::class,
        'Sobeys' => \App\Exports\ListingForms\SobeysListingForm::class,
        'Vince\'s' => \App\Exports\ListingForms\VincesListingForm::class,
        'Well.ca' => \App\Exports\ListingForms\WellCaListingForm::class,
        'Wholefoods Canada' => \App\Exports\ListingForms\WholeFoodsCanadaListingForm::class,
    ],

    'exports' => [
        'finance_pricing' => [
            'mcbpricing' => App\Exports\MCBPricingExport::class,
            'as400pricingdata' => App\Exports\AS400PricingDataExport::class,
            'cataloguechangesummary' => App\Exports\CatalogueChangeSummaryExport::class,
            'custompricing' => App\Exports\CustomPricingExport::class,
            'mafrejection' => App\Exports\MafRejectionExport::class,
        ],
        'general' => [
            'orderform' => [
                'class' => App\Exports\OrderFormExport::class,
                'vendor_accessible' => true,
            ],
            'promocalendar' => \App\Exports\PromoCalendarExport::class,
            'productdata' => App\Exports\ProductDataExport::class,
            'newproducts' => App\Exports\NewProductsExport::class,
            'productupdates' => App\Exports\ProductUpdatesExport::class,
            'promospecials' => App\Exports\PromoSpecialsExport::class,
            'productimages' => App\Exports\ProductImagesExport::class,
            'bulkorderform' => App\Exports\BulkOrderFormExport::class,
            'brandcontacts' => App\Exports\BrandContactsExport::class,
            'inventoryremovals' => App\Exports\InventoryRemovalExport::class,
        ],
        'marketing' => [
            'catalogue' => App\Exports\CatalogueExport::class,
            'catalogueold' => App\Exports\CatalogueExportOldFormat::class,
            'cataloguecasestackdeals' => App\Exports\CatalogueCaseStackDealsExport::class,
            'cataloguedealssummary' => App\Exports\CatalogueDealsSummaryExport::class,
            'customerlink' => App\Exports\CustomerLinkExport::class,
            'customerlinkimages' => App\Http\Livewire\Exports\CustomerLinkImagesExportComponent::class,
            'brandlogos' => App\Exports\BrandLogosExport::class,
        ],
    ],
];
