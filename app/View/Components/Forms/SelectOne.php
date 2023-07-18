<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class SelectOne extends Component
{
    public $options = [];

    public function render()
    {
        return view('components.forms.select-one');
    }
}
