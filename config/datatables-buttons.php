<?php

return [
    /*
     * Namespaces used by the generator.
     */
    'namespace' => [
        /*
         * Base namespace/directory to create the new file.
         * This is appended on default Laravel namespace.
         * Usage: php artisan datatables:make User
         * Output: App\DataTables\UserDataTable
         * With Model: App\User (default model)
         * Export filename: users_timestamp
         */
        'base' => 'DataTables',

        /*
         * Base namespace/directory where your model's are located.
         * This is appended on default Laravel namespace.
         * Usage: php artisan datatables:make Post --model
         * Output: App\DataTables\PostDataTable
         * With Model: App\Post
         * Export filename: posts_timestamp
         */
        'model' => '',
    ],

    /*
     * Set Custom stub folder
     */
    //'stub' => '/resources/custom_stub',

    /*
     * PDF generator to be used when converting the table to pdf.
     * Available generators: excel, snappy
     * Snappy package: barryvdh/laravel-snappy
     * Excel package: maatwebsite/excel
     */
    'pdf_generator' => 'snappy',

    /*
     * Snappy PDF options.
     */
    'snappy' => [
        'options' => [
            'no-outline' => true,
            'margin-left' => '0',
            'margin-right' => '0',
            'margin-top' => '10mm',
            'margin-bottom' => '10mm',
        ],
        'orientation' => 'landscape',
    ],

    /*
     * Default html builder parameters.
     */
    'parameters' => [
        'dom' => '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>B',
        'order' => [[0, 'desc']],
        'processing' => true,
        'serverSide' => true,
        'stateSave' => true,
        'language' => [
            'processing' => '<div class="spinner-container"><div class="spinner-item spinner-moon"></div></div>',
        ],
        'buttons' => [
            // [
            //     'extend' => 'excel',
            //     'text' => '<i class="material-icons">table_chart</i>Excel',
            // ],
            // [
            //     'extend' => 'csv',
            //     'text' => '<i class="material-icons">view_comfy</i>CSV',
            // ],
            // [
            //     'extend' => 'pdf',
            //     'text' => '<i class="material-icons">picture_as_pdf</i>PDF',
            // ],
            // [
            //     'extend' => 'print',
            //     'text' => '<i class="material-icons">print</i>Print',
            // ],
        ],

    ],
];
