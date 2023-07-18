<?php

namespace App\Traits;

use App\Helpers\RegulatoryFlagsHelper;

trait HasRegulatoryFlags
{
    public function getHasRegulationsAttribute()
    {
        return $this->flags & RegulatoryFlagsHelper::HAS_REGULATIONS;
    }

    public function getHasWarningsAttribute()
    {
        return $this->flags & RegulatoryFlagsHelper::HAS_WARNINGS;
    }

    public function getHasRecommendedDosageAttribute()
    {
        return $this->flags & RegulatoryFlagsHelper::HAS_RECOMMENDED_DOSAGE;
    }

    public function getHasRecommendedUseAttribute()
    {
        return $this->flags & RegulatoryFlagsHelper::HAS_RECOMMENDED_USE;
    }

    public function getRequiresNutritionalFactsAttribute()
    {
        return $this->flags & RegulatoryFlagsHelper::REQUIRES_NUTRITIONAL_FACTS;
    }

    public function getRequiresIngredientPanelAttribute()
    {
        return $this->flags & RegulatoryFlagsHelper::REQUIRES_INGREDIENT_PANEL;
    }

    public function getHasNPNAttribute()
    {
        return $this->flags & RegulatoryFlagsHelper::HAS_NPN;
    }

    public function getRequiresNPNAttribute()
    {
        return $this->flags & RegulatoryFlagsHelper::REQUIRES_NPN;
    }

    public function getRequiresCosmeticLicenseAttribute()
    {
        return $this->flags & RegulatoryFlagsHelper::REQUIRES_COSMETIC_LICENSE;
    }

    public function getRequiresImporterAttribute()
    {
        return $this->flags & RegulatoryFlagsHelper::REQUIRES_IMPORTER;
    }

    public function getRequiresCNNAttribute()
    {
        return $this->flags & RegulatoryFlagsHelper::REQUIRES_CNN;
    }

    public function getIsMedicalDeviceAttribute()
    {
        return $this->flags & RegulatoryFlagsHelper::IS_MEDICAL_DEVICE;
    }

    public function getHasNutritionalInfoAttribute()
    {
        return $this->flags & RegulatoryFlagsHelper::HAS_NUTRITIONAL_INFO;
    }

    public function getIsPesticideAttribute()
    {
        return $this->flags & RegulatoryFlagsHelper::IS_PESTICIDE;
    }

    public function getHasNetWeightAttribute()
    {
        return $this->flags & RegulatoryFlagsHelper::HAS_NET_WEIGHT;
    }
}
