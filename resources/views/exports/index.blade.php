@extends('layouts.app')

@section('page', 'Exports and Reports')

@section('content')
<div class="container">
    <div class="tabs-wrap mt-5">
        <div class="tabs-header">
            @if (array_key_exists('general', $exports))
                <div class="tab-btn tab-selected" name="general">General</div>
            @endif

            @if (array_key_exists('finance_pricing', $exports))
                <div class="tab-btn" name="finance_pricing">Finance / Pricing</div>
            @endif

            @if (array_key_exists('marketing', $exports))
                <div class="tab-btn" name="marketing">Marketing</div>
            @endif
        </div>
        <div class="tabs-body">
            <div class="general-tab">
                <div class="row justify-content-center">
                    @foreach ($exports['general'] as $view => $export)
                        @if(in_array(App\Contracts\Http\Livewire\ExportComponent::class, class_implements($export)))
                            @livewire($export)
                        @else
                            @include("exports.forms.{$view}")<br />
                        @endif
                    @endforeach
                </div>
            </div>
            @if (array_key_exists('finance_pricing', $exports))
            <div class="finance_pricing-tab" style="display:none;">
                <div class="row justify-content-center">
                    @foreach ($exports['finance_pricing'] as $view => $export)
                        @if(in_array(App\Contracts\Http\Livewire\ExportComponent::class, class_implements($export)))
                            @livewire($export)
                        @else
                            @include("exports.forms.{$view}")<br />
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
            @if (array_key_exists('marketing', $exports))
            <div class="marketing-tab" style="display:none;">
                <div class="row justify-content-center">
                    @foreach ($exports['marketing'] as $view => $export)
                    @ray($export)
                        @if(in_array(App\Contracts\Http\Livewire\ExportComponent::class, class_implements($export)))
                            @livewire($export)
                        @else
                            @include("exports.forms.{$view}")<br />
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
{!! BladeHelper::initChosenSelect('searchable') !!}
<script type="text/javascript" src="{{ mix('js/view-tabs.js') }}"></script>
@endpush
