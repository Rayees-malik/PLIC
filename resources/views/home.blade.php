@extends('layouts.app')

@section('page', 'Home')

@section('content')
@unless(in_array(config('app.env'), ['production', 'test']))
<div class="tw-container tw-flex-col tw-flex">
    <h1 class="tw-p-4 tw-text-yellow-500 tw-self-center tw-font-black tw-uppercase tw-border-yellow-600 tw-border-2">IN DEVELOPMENT</h1>
    <div class="tw-flex tw-justify-around">
        @if(url('/') == 'https://dev.plic.puritylife.com')
            <a target="_blank" rel="noopener noreferrer" href="http://10.50.4.17:8025" class="tw-inline-flex tw-items-center tw-rounded-md tw-bg-blue-500 tw-h-10 tw-px-2 tw-py-1 tw-text-lg tw-font-medium tw-text-blue-50 tw-ring-1 tw-ring-inset tw-ring-blue-700/10">Mailpit</a>
        @elseif(url('/') == 'https://dev2.plic.puritylife.com')
            <a target="_blank" rel="noopener noreferrer" href="http://10.50.4.17:8026" class="tw-inline-flex tw-items-center tw-rounded-md tw-bg-blue-500 tw-h-10 tw-px-2 tw-py-1 tw-text-lg tw-font-medium tw-text-blue-50 tw-ring-1 tw-ring-inset tw-ring-blue-700/10">Mailpit</a>
        @elseif(url('/') == 'https://staging.plic.puritylife.com')
            <a target="_blank" rel="noopener noreferrer" href="http://10.50.4.17:8027" class="tw-inline-flex tw-items-center tw-rounded-md tw-bg-blue-500 tw-h-10 tw-px-2 tw-py-1 tw-text-lg tw-font-medium tw-text-blue-50 tw-ring-1 tw-ring-inset tw-ring-blue-700/10">Mailpit</a>
        @endif
    </div>
</div>
@endunless

<div class="container">
    <div class="row justify-content-center mt-5 align-center">
        <div class="col">
            <h1>Welcome to PLIC - Purity Life Information Centre</h1>
            <p>
                If you wish to provide feedback or report any issues, please send an email to <a href="mailto:plicfeedback@puritylife.com">plicfeedback@puritylife.com</a>.
            </p>
        </div>
    </div>
</div>

<div class="container">
    <div class="row justify-content-center mt-5 align-center">
        <div class="col">
            <h2>View our current magazine:</h2>
            <a href="https://issuu.com/puritylifehealthproducts/docs/julaugsep_purity_pulse.flip?fr=xPf9vPhtCa0M7li9TlywCbgpiITsGDwP0wf46CP46wQrB_lNYNsH_CEVVTEdXOFA08dcD_k8G_lYC_lUS8f8DX0c4Jv8CWUIED_8FazVQOF8s_wVLMUpaORL_AllRBjv-QQP_AlE_Av8EPUtENgP_AjcxSjVDQEBAbjuWL1OXLAL0wUA7yHVQYcEmOxYG4QrB_wQyMDIxwf8CMTLBJQomJv8CQktu_mo7OlAB" target="_blank">
                <img src="images/July2023.png" alt="Catalogue" style="max-width: 150px">
            </a>
        </div>
        <div class="col">
            <h2>Marketing Opportunities:</h2>
            <a href="https://issuu.com/puritylifehealthproducts/docs/pl_marketingopportunities2023forissuu?fr=sMTViMjUzOTA0NTU" target="_blank">
                <img src="images/PL_MarketingOpportunities2023Icon.png" alt="Catalogue" style="max-width: 150px">
            </a>
        </div>
        <div class="col">
            <h2>Feature Updates to PLIC</h2>
            <ul class="tw-space-y-4 md:tw-space-y-2">
                @foreach ($upcomingChanges as $change)
                <li class="tw-flex tw-flex-col md:tw-flex-row md:tw-space-x-6">
                    <span class="tw-flex-shrink-0">{{ $change->change_date }}</span>
                    <span class="tw-italic">{{ $change->title }}</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
