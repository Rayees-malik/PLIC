@extends('layouts.app')

@section('page', 'Management Bulk Approvals')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
        <h1 class="mb-0 text-center">Management Bulk Price Approvals</h1>
        <h2 class="text-center">
            @if ($allBrands)
            Multiple Brands
            @else
            <a href="{{ route('brands.show', $signoffs->first()->proposed->brand->id) }}" class="text-link">{{ $signoffs->first()->proposed->brand->name }}</a>
            @endif
        </h2>

        <form id="signoff-form" method="POST" action="{{ route('signoffs.management.update') }}">
            @csrf
            @method('post')

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
                            @if ($allBrands)
                            <th>Brand</th>
                            @endif
                            <th>Product</th>
                            <th>Stock #</th>
                            <th>Current Cost</th>
                            <th>New Cost</th>
                            <th>Current Whls</th>
                            <th>New Whls</th>
                            <th>Duty</th>
                            <th>Freight</th>
                            <th>Extra Addon</th>
                            <th>Exch. Rate</th>
                            <th>Landed Cost</th>
                            <th>Margin</th>
                            <th>New / Update</th>
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
                        @if ($allBrands)
                        <td>{{ $signoff->proposed->brand->name }}</td>
                        @endif
                        <td><a href="{{ route('signoffs.edit', $signoff->id) }}">{{ $signoff->proposed->getName() }}</a></td>
                        <td>{{ $signoff->proposed->stock_id }}</td>
                        <td class="text-right">{{ $signoff->new_submission ? '-' : '$' . number_format(optional($signoff->initial->as400Pricing)->po_price, 2) }}</td>
                        <td class="text-right">${{ number_format($signoff->proposed->unit_cost, 2) }}</td>
                        <td class="text-right">{{ $signoff->new_submission || !$signoff->initial->as400Pricing ? '-' : '$' . number_format(optional($signoff->initial->as400Pricing)->wholesale_price, 2) }}</td>
                        <td class="text-right">${{ number_format($signoff->proposed->wholesale_price, 2) }}</td>
                        <td class="text-right">{{ number_format($signoff->new_submission ? $signoff->proposed->temp_duty : optional($signoff->initial->as400Pricing)->duty, 2) }}%</td>
                        <td class="text-right">{{ number_format(optional($signoff->proposed->brand->as400Freight)->freight, 2) . '%' }}</td>
                        <td class="text-right">{{ number_format($signoff->proposed->extra_addon_percent, 2) . '%' }}</td>
                        <td class="text-right">{{ number_format($signoff->proposed->brand->currency->exchange_rate ?? 1, 2) }}</td>
                        <td class="text-right">${{ number_format($signoff->proposed->landed_cost, 2) }}</td>
                        <td class="text-right">{{ $signoff->proposed->wholesale_price > 0 ? number_format((1 - ($signoff->proposed->landed_cost / $signoff->proposed->wholesale_price)) * 100, 2) . '%' : '-' }}</td>
                        <td class="text-center">{{ $signoff->new_submission ? 'New' : 'Update' }}</td>
                    </tr>
                    @endforeach
                </table>

                <div class="mt-3 mb-5 input-wrap">
                    <label for="signoff_comment">Comment</label>
                    <textarea type="text" name="signoff_comment"></textarea>
                </div>

                <div class="row justify-content-center mt-5 align-items-end">
                    <div class="col-md-2" style="vertical-align: bottom;">
                        <a class="secondary-btn block-btn" href="{{ route('signoffs.management') }}" title="Cancel">
                            <i class="material-icons">cancel</i>
                            Cancel
                        </a>
                    </div>
                    <div class="col-md-4 justify-content-between">
                        <div class="mb-2 text-center">
                            <p class="mb-0">Approve selected product(s)?</p>
                        </div>

                        <div class="row mt-1">
                            <div class="col-6">
                                <button class="warning-btn block-btn" type="button" data-toggle="modal" title="Reject Signoff" data-target="#rejectModal">No</button>
                            </div>
                            <div class="col-6">
                                <button class="success-btn block-btn" type="button" data-toggle="modal" title="Approve Signoff" data-target="#approveModal">Yes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('modals.approve')
            @include('modals.reject')
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="{{ mix('js/modules/management-signoff.js') }}"></script>
@endpush
