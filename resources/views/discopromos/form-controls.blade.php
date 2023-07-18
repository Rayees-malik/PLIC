<div class="js-promo-form">
    <div class="row justify-content-between">
        @can('promo.update.discos')
            <div class="col-xl-4">
                <button type="button" class="secondary-btn mt-4" data-toggle="modal" title="Quick Update" data-target="#updateModal">
                    <i class="material-icons">wifi_protected_setup</i>
                    Quick Update
                </button>
            </div>
        @endcan
        <div class="input-wrap col-xl-4 ">
            <label>
                Search
                <div class="icon-input">
                    <i class="material-icons pre-icon">search</i>
                    <input type="text" class="js-search" placeholder="">
                </div>
            </label>
        </div>
    </div>
    <div class="js-promo-container">
        @include('discopromos.product-promo-table')
    </div>
</div>

@can('promo.update.discos')
    @include('discopromos.quick-update')
@endcan

@push('scripts')
<script type="text/javascript" src="{{ mix('js/modules/discopromos.js') }}"></script>
@endpush
