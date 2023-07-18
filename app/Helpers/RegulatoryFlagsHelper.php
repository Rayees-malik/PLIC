<?php

namespace App\Helpers;

class RegulatoryFlagsHelper
{
    public const HAS_REGULATIONS = 1;

    public const HAS_WARNINGS = 2;

    public const HAS_RECOMMENDED_DOSAGE = 4;

    public const HAS_RECOMMENDED_USE = 8;

    //public const AVAILABLE = 16;
    public const REQUIRES_NUTRITIONAL_FACTS = 32;

    public const REQUIRES_INGREDIENT_PANEL = 64;

    public const HAS_NPN = 128;

    public const REQUIRES_NPN = 256;

    public const REQUIRES_COSMETIC_LICENSE = 512;

    public const REQUIRES_IMPORTER = 1024;

    public const REQUIRES_CNN = 2048;

    public const IS_MEDICAL_DEVICE = 4096;

    public const HAS_NUTRITIONAL_INFO = 8192;

    public const IS_PESTICIDE = 16384;

    public const HAS_NET_WEIGHT = 32768;
}
