<?php

namespace App\Exceptions;

use Exception;

class GeocodingNoResultsException extends Exception
{
    /**
     * Class constructor.
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }
}
