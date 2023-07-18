<?php

namespace App\Helpers;

use Exception;

class SignoffStateHelper
{
    const PENDING = 0;

    const INITIAL = 1;

    const APPROVED = 2;

    const REJECTED = 3;

    const ARCHIVED = 4;

    const IN_PROGRESS = 5;

    const UNSUBMITTED = 6;

    public static function toString($state)
    {
        switch ($state) {
            case SignoffStateHelper::PENDING:
                return 'Pending';
                break;
            case SignoffStateHelper::INITIAL:
                return 'Initial';
                break;
            case SignoffStateHelper::APPROVED:
                return 'Approved';
                break;
            case SignoffStateHelper::REJECTED:
                return 'Rejected';
                break;
            case SignoffStateHelper::ARCHIVED:
                return 'Archived';
                break;
            case SignoffStateHelper::IN_PROGRESS:
                return 'In Progress';
                break;
            case SignoffStateHelper::UNSUBMITTED:
                return 'Unsubmitted';
                break;
            default:
                throw new Exception('Invalid SignoffState');
        }
    }
}
