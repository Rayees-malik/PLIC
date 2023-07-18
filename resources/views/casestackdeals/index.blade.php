@extends('layouts.app')

@section('page', 'Case Stack Deals')

@section('content')
<div class="container">
    <form method="POST" action="/casestackdeals">
        @csrf
        @method('PATCH')
        <div class="row">
            <div class="input-wrap col-xl-4">
                <label>Brand
                    @if ($brands->count())
                    <div class="icon-input">
                        <select name="brand_id" class="searchable js-brand" data-placeholder="Select Brand">
                            @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ $brandId == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    {{ $brands->first()->name }}
                    @endif
                </label>
            </div>
        </div>

        <div class="top-controls">
            <a class="secondary-btn" href="{{ route('home') }}" title="Cancel">
                <i class="material-icons">cancel</i>
                Cancel
            </a>
            <button class="secondary-btn" type="submit" title="Save">
                Save
            </button>
        </div>

        @foreach ($promoPeriods as $period)
        <div class="card mt-2">
            <div class="card-header">
                <h2 class="mb-0 d-inline">{{ $period->name }}</h2>
                <button type="button" class="js-copy btn" style="float: right;" data-year="{{ $period->start_date->year }}" data-period="{{ $period->id }}">copy to full year</a>
            </div>
            <div class="card-body">
                <div class="row mt-3">
                    <div class="input-wrap col-xl-6">
                        <label>Deal (EN)
                            <div class="input">
                                <textarea type="text" class="js-deal" name="deal[{{ $period->id }}]" autocomplete="off" data-year="{{ $period->start_date->year }}" }>{{
                            old("deal.{$period->id}", optional($period->getCaseStackDeal($brandId))->deal)
                        }}</textarea>
                            </div>
                        </label>
                    </div>
                    <div class="input-wrap col-xl-6">
                        <label>Deal (FR)
                            <div class="input">
                                <textarea type="text" class="js-deal-fr" name="deal_fr[{{ $period->id }}]" autocomplete="off" data-year="{{ $period->start_date->year }}">{{
                            old("deal_fr.{$period->id}", optional($period->getCaseStackDeal($brandId))->deal_fr)
                        }}</textarea>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <div class="bottom-controls">
            <a class="secondary-btn" href="{{ route('home') }}" title="Cancel">
                <i class="material-icons">cancel</i>
                Cancel
            </a>
            <button class="secondary-btn" type="submit" title="Save">
                Save
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')

<script type="text/javascript">
    $('.js-brand').on('change', function () {
        window.location.href = `/casestackdeals/${$(this).val()}/`;
    });

    $('.js-copy').on('click', function () {
        const deal = $(`[name="deal[${$(this).data('period')}]"]`).val();
        const dealFR = $(`[name="deal_fr[${$(this).data('period')}]"]`).val();

        const year = $(this).data('year');

        $(`.js-deal[data-year="${year}"]`).val(deal);
        $(`.js-deal-fr[data-year="${year}"]`).val(dealFR);
    });

</script>
{!! BladeHelper::initChosenSelect('searchable') !!}
@endpush
