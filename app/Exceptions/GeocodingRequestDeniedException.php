<?php

namespace App\Exceptions;

use Exception;

class GeocodingRequestDeniedException extends Exception
{
    /**
     * Class constructor.
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }
}
