@extends('layouts.app')

@section('page', 'Management Bulk Approvals')

@section('content')
<h1 class="mb-0 text-center">Management Bulk Price Approvals</h1>
<div class="text-center">
    <small>Below are all brands with products requiring approval</small>
</div>

<div class="container mt-4">
    @if ($brands->count())
    <div class="dataTables_wrapper">
        <table class="table datatable">
            @if ($brands->count() > 1)
            <tr>
                <td style="vertical-align: middle;">
                    <h2 class="mb-1">All Brands</h2>
                </td>
                <td></td>
                <td style="vertical-align: middle;">
                    <a href="{{ route('signoffs.management.review', 'all') }}" class="link-btn table-btn"><i class="material-icons">fact_check</i>Review</a>
                </td>
            </tr>
            @endif
            @foreach ($brands as $brand => $signoffs)
            <tr>
                <td style="vertical-align: middle;">
                    <h3 class="mb-1">{{ $brand }}</h3>
                </td>
                <td style="vertical-align: middle;">
                    {{ count($signoffs) }} {{ count($signoffs) == 1 ? 'product requires' : 'products require' }} approval
                </td>
                <td style="vertical-align: middle;">
                    <a href="{{ route('signoffs.management.review', $signoffs->first()->proposed->brand_id) }}" class="link-btn table-btn"><i class="material-icons">fact_check</i>Review</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    @else
    <h2>No products are currently requiring approval.</h2>
    @endif
</div>
@endsection
