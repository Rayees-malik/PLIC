<?php

namespace App\Contracts\Exports;

interface HasCriteria
{
    public function setCriteria(...$criteria): self;
}
