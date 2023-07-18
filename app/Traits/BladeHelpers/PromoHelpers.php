<?php

namespace App\Traits\BladeHelpers;

use Illuminate\Support\Arr;

trait PromoHelpers
{
    public static function promoHeaderField($model, $field, $fieldConfig, $show = false)
    {
        $type = Arr::get($fieldConfig, 'type');

        if ($show) {
            $view = 'partials.promos.header-fields.show';
        } else {
            $view = 'partials.promos.header-fields.input';
            switch ($type) {
                case 'checkbox':
                    $view = 'partials.promos.header-fields.checkbox';
                    break;
                case 'textarea':
                    $view = 'partials.promos.header-fields.textarea';
                    break;
                case 'select':
                    $view = 'partials.promos.header-fields.select';
                    break;
            }
        }

        return view($view)->with(compact('model', 'field', 'fieldConfig'))->render();
    }

    public static function promoField($model, $product, $field, $fieldConfig, $show = false)
    {
        $type = Arr::get($fieldConfig, 'type');

        if ($show) {
            $view = 'partials.promos.fields.show';
            switch ($type) {
                case 'checkbox':
                    $view = 'partials.promos.fields.show-checkbox';
                    break;
            }
        } else {
            $view = 'partials.promos.fields.input';
            switch ($type) {
                case 'checkbox':
                    $view = 'partials.promos.fields.checkbox';
                    break;
                case 'textarea':
                    $view = 'partials.promos.fields.textarea';
                    break;
                case 'select':
                    $view = 'partials.promos.fields.select';
                    break;
            }
        }

        return view($view)->with(compact('model', 'product', 'field', 'fieldConfig'))->render();
    }

    public static function quickPromoField($field, $fieldConfig)
    {
        $type = Arr::get($fieldConfig, 'type');

        $view = 'partials.promos.quick-fields.input';
        switch ($type) {
            case 'checkbox':
                $view = 'partials.promos.quick-fields.checkbox';
                break;
            case 'textarea':
                $view = 'partials.promos.quick-fields.textarea';
                break;
            case 'select':
                $view = 'partials.promos.quick-fields.select';
                break;
        }

        return view($view)->with(compact('field', 'fieldConfig'))->render();
    }
}
