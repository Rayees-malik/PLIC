<?php

namespace App\Http\Livewire\Input;

use App\Models\Product;
use Livewire\Component;

class ProductSelect extends Component
{
    public $options = [];

    public $selections = [];

    public $model = '';

    public function search($stockId)
    {
        $this->options = Product::query()
            ->withPending()
            ->catalogueActive(true)
            ->without('as400Pricing')
            ->select('id as value')
            ->selectRaw("CONCAT(stock_id, ' : ', name) as label")
            ->where('stock_id', 'like', $stockId . '%')
            ->orderBy('stock_id')
            ->take(20)
            ->get()
            ->toArray();

        $this->emit('select-options-updated', $this->options, $stockId);
    }

    public function mount($model, $initialValue = null)
    {
        $this->model = $model;

        if ($initialValue) {
            $this->selections = $initialValue;

            $this->options = Product::query()->withPending()
                ->catalogueActive()
                ->without('as400Pricing')
                ->select('id as value')
                ->selectRaw("CONCAT(stock_id, ' : ', name) as label")
                ->where('id', $initialValue)
                ->get()
                ->toArray();
        } else {
            $this->options = Product::query()
                ->withPending()
                ->catalogueActive()
                ->without('as400Pricing')
                ->select('id as value')
                ->selectRaw("CONCAT(stock_id, ' : ', name) as label")
                ->orderBy('stock_id')
                ->take(5)
                ->get()
                ->toArray();
        }
    }

    public function render()
    {
        return view('livewire.input.product-select');
    }
}
