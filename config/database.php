<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
     */

    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'plic'),
            'username' => env('DB_USERNAME', 'plic'),
            'password' => env('DB_PASSWORD', 'plic'),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'kyolic' => [
            'driver' => 'mysql',
            'host' => env('KYOLIC_DB_HOST', '127.0.0.1'),
            'port' => env('KYOLIC_DB_PORT', '3306'),
            'database' => env('KYOLIC_DB_DATABASE', 'kyolic'),
            'username' => env('KYOLIC_DB_USERNAME', 'kyolic'),
            'password' => env('KYOLIC_DB_PASSWORD', 'kyolic'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'as400' => [
            'driver' => 'db2_ibmi_odbc',
            // or 'db2_ibmi_ibm' / 'db2_zos_odbc' / 'db2_expressc_odbc
            'driverName' => '{IBM i Access ODBC Driver 64-bit}',
            // or '{iSeries Access ODBC Driver}' / '{IBM i Access ODBC Driver 64-bit}'
            'host' => env('AS400_HOST', '127.0.0.1'),
            'username' => env('AS400_USERNAME', 'as400'),
            'password' => env('AS400_PASSWORD', 'as400'),
            'database' => env('AS400_DATABASE', 'as400'),
            'prefix' => '',
            'schema' => env('AS400_SCHEMA', 'as400'),
            'port' => env('AS400_PORT', 50000),
            'date_format' => 'Y-m-d H:i:s',
            // or 'Y-m-d H:i:s.u' / 'Y-m-d-H.i.s.u'...
            'odbc_keywords' => [
                'SIGNON' => 3,
                'SSL' => 0,
                'CommitMode' => 2,
                'ConnectionType' => 0,
                'DefaultLibraries' => '',
                'Naming' => 0,
                'UNICODESQL' => 0,
                'DateFormat' => 5,
                'DateSeperator' => 0,
                'Decimal' => 0,
                'TimeFormat' => 0,
                'TimeSeparator' => 0,
                'TimestampFormat' => 0,
                'ConvertDateTimeToChar' => 0,
                'BLOCKFETCH' => 1,
                'BlockSizeKB' => 32,
                'AllowDataCompression' => 1,
                'CONCURRENCY' => 0,
                'LAZYCLOSE' => 0,
                'MaxFieldLength' => 15360,
                'PREFETCH' => 0,
                'QUERYTIMEOUT' => 1,
                'DefaultPkgLibrary' => 'QGPL',
                'DefaultPackage' => 'A /DEFAULT(IBM),2,0,1,0',
                'ExtendedDynamic' => 0,
                'QAQQINILibrary' => '',
                'SQDIAGCODE' => '',
                'LANGUAGEID' => 'ENU',
                'SORTTABLE' => '',
                'SortSequence' => 0,
                'SORTWEIGHT' => 0,
                'AllowUnsupportedChar' => 0,
                'CCSID' => 819,
                'GRAPHIC' => 0,
                'ForceTranslation' => 0,
                'ALLOWPROCCALLS' => 0,
                'DB2SQLSTATES' => 0,
                'DEBUG' => 0,
                'TRUEAUTOCOMMIT' => 0,
                'CATALOGOPTIONS' => 3,
                'LibraryView' => 0,
                'ODBCRemarks' => 0,
                'SEARCHPATTERN' => 1,
                'TranslationDLL' => '',
                'TranslationOption' => 0,
                'MAXTRACESIZE' => 0,
                'MultipleTraceFiles' => 1,
                'TRACE' => 0,
                'TRACEFILENAME' => '',
                'ExtendedColInfo' => 0,
            ],
            'options' => [
                PDO::ATTR_CASE => PDO::CASE_LOWER,
                PDO::ATTR_PERSISTENT => false,
            ]
                + (defined('PDO::I5_ATTR_DBC_SYS_NAMING') ? [PDO::I5_ATTI5_ATTR_DBC_SYS_NAMINGR_COMMIT => false] : [])
                + (defined('PDO::I5_ATTR_COMMIT') ? [PDO::I5_ATTR_COMMIT => PDO::I5_TXN_NO_COMMIT] : [])
                + (defined('PDO::I5_ATTR_JOB_SORT') ? [PDO::I5_ATTR_JOB_SORT => false] : [])
                + (defined('PDO::I5_ATTR_DBC_LIBL') ? [PDO::I5_ATTR_DBC_LIBL => ''] : [])
                + (defined('PDO::I5_ATTR_DBC_CURLIB') ? [PDO::I5_ATTR_DBC_CURLIB => ''] : []),
        ],
    ],

    'migrations' => 'migrations',

    'redis' => [

        'client' => env('REDIS_CLIENT', 'predis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'predis'),
        ],

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_DB', 0),
        ],

        'cache' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_CACHE_DB', 1),
        ],

    ],

];
