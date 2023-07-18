<?php

namespace App\Http\Requests\Promos;

class PromoPeriodFormRequest extends \App\Http\Requests\BaseFormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'type' => 'required|in:0,1,2',
            'active' => 'required|in:0,1',
            'order_form_header' => 'nullable|string|max:255',
            'base_period_id' => 'nullable|string|max:255',
        ];
    }

    public function filters()
    {
        return [
            'title' => 'trim|capitalize',
            'order_form_header' => 'trim|name_case',
        ];
    }
}
