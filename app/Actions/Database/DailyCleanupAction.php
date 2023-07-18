<?php

namespace App\Actions\Database;

use YlsIdeas\FeatureFlags\Facades\Features;

class DailyCleanupAction
{
    /**
     * Class constructor.
     */
    public function __construct(
        private RemoveDiscontinuedBrandPromosAction $removeDiscontinuedBrandPromosAction,
        private RemoveDiscontinuedBrandCaseStackDealsAction $removeDiscontinuedBrandCaseStackDealsAction,
        private RemoveDiscontinuedProductPromosAction $removeDiscontinuedProductPromosAction,
        private DeleteDraftSignoffsAction $deleteDraftSignoffsAction,
        private DeleteArchivedSignoffsAction $deleteArchivedSignoffsAction,
    ) {
    }

    public function execute()
    {
        if (! Features::accessible('skip-daily-promo-cleanup-actions')) {
            $this->removeDiscontinuedBrandPromosAction->execute();
            $this->removeDiscontinuedBrandCaseStackDealsAction->execute();
            $this->removeDiscontinuedProductPromosAction->execute();
        }

        $this->deleteDraftSignoffsAction->execute();
        $this->deleteArchivedSignoffsAction->execute();
    }
}
