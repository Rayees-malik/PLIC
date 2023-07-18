<?php

namespace Database\Factories\Traits;

use App\Helpers\SignoffStateHelper;

trait HasState
{
    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => SignoffStateHelper::PENDING,
            ];
        });
    }

    public function initial()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => SignoffStateHelper::INITIAL,
            ];
        });
    }

    public function approved()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => SignoffStateHelper::APPROVED,
            ];
        });
    }

    public function rejected()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => SignoffStateHelper::REJECTED,
            ];
        });
    }

    public function archived()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => SignoffStateHelper::ARCHIVED,
            ];
        });
    }

    public function inProgress()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => SignoffStateHelper::IN_PROGRESS,
            ];
        });
    }

    public function unsubmitted()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => SignoffStateHelper::UNSUBMITTED,
            ];
        });
    }
}
