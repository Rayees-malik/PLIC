<?php

namespace App\Contracts\Notifications\Signoffs;

interface ResolveMailAttributesBehaviour
{
    public function execute(): array;
}
