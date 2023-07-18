<?php

use Illuminate\Contracts\Foundation\Application;

/**
 * @returns array<string, bool>
 */
return static function (Application $app): array {
    return [
        'skip-daily-promo-cleanup-actions' => true,
        'remove-session-dependency' => true,
    ];
};
