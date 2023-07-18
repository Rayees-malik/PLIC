<?php

namespace App\Helpers;

use Exception;

class StatusHelper
{
    const UNSUBMITTED = 0;

    const PENDING = 10;

    const ACTIVE = 20;

    const DISCONTINUED = 30;

    const LEGACY = 40;

    const DELETED = 100;

    public static function toString($state)
    {
        switch ($state) {
            case StatusHelper::UNSUBMITTED:
                return 'Unsubmitted';
                break;
            case StatusHelper::PENDING:
                return 'Pending';
                break;
            case StatusHelper::ACTIVE:
                return 'Active';
                break;
            case StatusHelper::DISCONTINUED:
                return 'Discontinued';
                break;
            case StatusHelper::LEGACY:
                return 'Legacy';
                break;
            case StatusHelper::DELETED:
                return 'Deleted';
                break;
            default:
                throw new Exception('Invalid Status');
        }
    }
}
