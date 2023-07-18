@extends('layouts.app')
@section('page', 'Payments & Deductions')

@section('content')
<div class="container">
    <h1 class="text-center">Payments & Deductions</h1>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="dropdown-wrap col-xl-6">
                    <label>Brand</label>
                    @if ($brands->count() > 1)
                    <div class="dropdown-icon">
                        <select name="brand_id" class="js-finance-brand searchable" data-placeholder="Select Brand">
                            <option value disabled selected></option>
                            @foreach ($brands as $selectBrand)
                            <option value="{{ $selectBrand->id }}" {{ optional($brand)->id == $selectBrand->id ? 'selected' : '' }}>
                                {{ $selectBrand->name }} ({{ $selectBrand->finance_brand_number ?? $selectBrand->brand_number }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <h2>{{ $brand->name }}</h2>
                    @endif
                </div>
            </div>

            <div class="js-finance-container">
                <div class="tabs-wrap" style="border: none;">
                    <div class="tabs-header">
                        @if ($brand)
                        <a class="tab-btn {{ $activeTab == 'payments' ? 'tab-selected' : '' }}" href="{{ route('brand-finance.index', ['brand_id' => $brand, 'tab' => 'payments']) }}" style="text-decoration: none">Payment Details</a>
                        <a class="tab-btn {{ $activeTab == 'invoices' ? 'tab-selected' : '' }}" href="{{ route('brand-finance.index', ['brand_id' => $brand, 'tab' => 'invoices']) }}" style="text-decoration: none">Invoices</a>
                        <a class="tab-btn {{ $activeTab == 'debitmemos' ? 'tab-selected' : '' }}" href="{{ route('brand-finance.index', ['brand_id' => $brand, 'tab' => 'debitmemos']) }}" style="text-decoration: none">Debit Memos</a>
                        <a class="tab-btn {{ $activeTab == 'rebates' ? 'tab-selected' : '' }}" href="{{ route('brand-finance.index', ['brand_id' => $brand, 'tab' => 'rebates']) }}" style="text-decoration: none">Weekly Rebate Details</a>
                        <a class="tab-btn {{ $activeTab == 'openap' ? 'tab-selected' : '' }}" href="{{ route('brand-finance.index', ['brand_id' => $brand, 'tab' => 'openap']) }}" style="text-decoration: none">Open AP</a>
                        <a class="tab-btn {{ $activeTab == 'poreceived' ? 'tab-selected' : '' }}" href="{{ route('brand-finance.index', ['brand_id' => $brand, 'tab' => 'poreceived']) }}" style="text-decoration: none">PO Received</a>
                        @can('finance.force-upload')<a class="tab-btn {{ $activeTab == 'admin' ? 'tab-selected' : '' }}" href="{{ route('brand-finance.index', ['brand_id' => $brand, 'tab' => 'admin']) }}" style="text-decoration: none">Admin</a>@endcan
                        @endif
                    </div>
                    <div class="tabs-body">
                        @if ($activeTab)
                        @if ($requiresLoad)
                        <div class="spinner-container ajax-loader">
                            <div class="spinner-moon spinner-item"></div>
                        </div>
                        @endif
                        <div class="js-tab-body" @if ($requiresLoad) style="display:none;" @endif>
                            @if (in_array($activeTab, ['payments', 'invoices', 'debitmemos', 'rebates']))
                            <form action="{{ Request::url() }}" method="POST">
                                @csrf
                                <div class="row justify-content-end mb-4">
                                    <div class="input-wrap mb-0 col-xl-4">
                                        <div class="icon-input">
                                            <i class="material-icons pre-icon">search</i>
                                            <input type="text" name="search" value="{{ request()->search }}">
                                        </div>
                                    </div>
                                    <button type="submit" class="primary-btn">Search</button>
                                </div>
                            </form>
                            @endif
                            @include("brand-finance.tabs.{$activeTab}")
                        </div>
                    </div>
                    @else
                    <em>Please select a brand to continue.</em>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<blockquote class="row justify-content-center mt-2 mb-1">
    If you have questions regarding accounts payable, please contact us at&nbsp;<a href="mailto:accountspayable@puritylife.com">accountspayable@puritylife.com</a>&nbsp;within 3 months of the deduction.
</blockquote>
<blockquote class="row justify-content-center">
    <strong>May take up to 24 hours for documents to appear.</strong>
</blockquote>
</div>
@can('finance.delete-media')
@include('modals.delete')
@endcan
@endsection

@push('scripts')
<script type="text/javascript">
    @if ($requiresLoad)
    $(function () {
        $('.ajax-loader').fadeOut(250);
        $('.js-tab-body').fadeIn(250);
    });
    @endif
    $('.js-finance-brand').on('change', function () {
        window.location.href = $(this).val() ? `/payments-deductions/${$(this).val()}/{{ $activeTab == 'admin' ? '' : $activeTab }}` : '/payments-deductions/';
    });

</script>
{!! BladeHelper::initChosenSelect('searchable') !!}
@endpush
