@extends('layouts.app')

@section('page', 'Webseries Bulk Approvals')

@section('content')
<div class="container container-xxl">
  <div class="row justify-content-center">
    <div class="col-12">
      <h1 class="mb-0 text-center">Webseries Bulk Price Approvals</h1>

      <form id="signoff-form" method="POST" action="{{ route('signoffs.webseries.update') }}">
        @csrf

        <input type="hidden" name="signoff_form" value="1">
        <div class="dataTables_wrapper">
          <table class="table datatable">
            <thead>
              <tr>
                <th style="width: 60px; vertical-align: middle">
                  <div class="checkbox-wrap mb-0">
                    <label class="checkbox">
                      <input type="checkbox" class="js-selected-header" checked>
                      <span class="checkbox-checkmark"></span>
                    </label>
                  </div>
                </th>
                <th>Brand</th>
                <th>Product</th>
                <th>Stock #</th>
                <th>Effective Date / Reason</th>
                <th>Current Cost</th>
                <th>New Cost</th>
                <th>Current Whls</th>
                <th>New Whls</th>
                <th>Duty</th>
                <th>Freight</th>
                <th>EDLP</th>
                <th>Extra Addon</th>
                <th>Exch. Rate</th>
                <th>Landed Cost / FLC</th>
                <th>Margin</th>
              </tr>
            </thead>
            @foreach ($signoffs as $signoff)
            <tr>
              <td style="vertical-align: middle;">
                <input type="hidden" name="signoff_id[]" value="{{ $signoff->id }}">
                <div class="checkbox-wrap mb-0">
                  <label class="checkbox">
                    <input type="checkbox" name="selected[{{ $signoff->id }}]" class="js-selected-row" value="1" checked>
                    <span class="checkbox-checkmark"></span>
                  </label>
                </div>
              </td>
              <td>{{ $signoff->proposed->brand->name }}</td>
              <td><a href="{{ route('signoffs.edit', $signoff->id) }}">{{ $signoff->proposed->getName() }}</a></td>
              <td>{{ $signoff->proposed->stock_id }}</td>
              <td>
                <div class="block">
                  <div>{{ optional($signoff->proposed->price_change_date)->format('Y-m-d') }}</div>
                  <small class="d-flex flex-column">
                    <div>{{ $signoff->proposed->price_change_reason }}</div>
                  </small>
                </div>
              </td>
              <td class="text-right">${{ number_format(optional($signoff->initial->as400Pricing)->po_price, 2) }}</td>
              <td class="text-right">${{ number_format($signoff->proposed->unit_cost, 2) }}</td>
              <td class="text-right">
                {{ !$signoff->initial->as400Pricing ? '-' : '$' . number_format(optional($signoff->initial->as400Pricing)->wholesale_price, 2) }}
              </td>
              <td class="text-right">${{ number_format($signoff->proposed->wholesale_price, 2) }}</td>
              <td class="text-right">{{ number_format(optional($signoff->initial->as400Pricing)->duty, 2) }}%</td>
              <td class="text-right">{{ number_format(optional($signoff->proposed->brand->as400Freight)->freight, 2) }}%</td>
              <td class="text-right">{{ $signoff->proposed->edlp ? number_format($signoff->proposed->edlp, 2) : number_format(0, 2) }}%</td>
              <td class="text-right">{{ $signoff->proposed->extra_addon_percent ? number_format($signoff->proposed->extra_addon_percent, 2) : number_format(2, 2) }}%</td>
              <td class="text-right">{{ number_format($signoff->proposed->brand->currency->exchange_rate ?? 1, 2) }}</td>
              <td class="text-right">
                <div class="block">
                  <div>${{ number_format($signoff->proposed->landed_cost, 2) }}</div>
                  @if ($signoff->initial->futureLandedCosts->count() > 0)
                  <small class="d-flex flex-column">
                    <div>${{ number_format(optional($signoff->initial->futureLandedCosts()->first())->landed_cost, 2) }}</div>
                    <div>
                      ({{ optional(optional($signoff->initial->futureLandedCosts()->first())->change_date)->format('Y-m-d') }})</div>
                  </small>
                  @else
                  <small>(None)</small>
                  @endif
                </div>
              </td>
              <td class="text-right">
                {{ $signoff->proposed->wholesale_price > 0 ? number_format((1 -($signoff->proposed->landed_cost / $signoff->proposed->wholesale_price)) * 100, 2) . '%' : '-' }}
              </td>
            </tr>
            @endforeach
          </table>

          <div class="mt-3 mb-5 input-wrap">
            <label for="signoff_comment">Comment</label>
            <textarea type="text" name="signoff_comment"></textarea>
          </div>
            <div class="col-md-4 justify-content-between">
              <div class="mb-2 text-center">
                <p class="mb-0">Approve selected product(s)?</p>
              </div>

              <div class="row mt-1">
                <div class="col-6">
                  <button class="warning-btn block-btn" type="button" data-toggle="modal" title="Reject Signoff"
                    data-target="#rejectModal">No</button>
                </div>
                <div class="col-6">
                  <button class="success-btn block-btn" type="button" data-toggle="modal" title="Approve Signoff"
                    data-target="#approveModal">Yes</button>
                </div>
              </div>
            </div>
            <div class="col-md-4 justify-content-between">
              <div class="row mt-2">
                <div class="col-12">
                  <button class="secondary-btn block-btn" name="action" type="submit" value="export" title="Webseries Export"
                    >Webseries Bulk Export</button>
                </div>
              </div>
          </div>
        </div>

        @include('modals.approve')
        @include('modals.reject')
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="{{ mix('js/modules/finance-signoff.js') }}"></script>
@endpush
