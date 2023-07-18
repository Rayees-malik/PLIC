<div class="col-xl-6 mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h2 class="mb-0">Customer Link Images</h2>
        </div>
        <div class="card-body">
            <div class="formContainer">
                    <div class="container">
                        @feature('customer-link-images-export-tweaks')
                        <div class="row">
                            <p class="tw-text-gray-500">You must provide either Added Since, one or more Stock IDs or only retrieve active products.</p>
                        </div>
                        @endfeature
                    <div class="row">
                            <div class="input-wrap">
                                <label>Added Since
                                    <div class="icon-input">
                                        <i class="material-icons pre-icon">calendar_today</i>
                                        <input wire:model="sinceDate" class="js-datepicker" value="" readonly>
                                    </div>
                                @error('sinceDate')<x-input.error :message="$message"/>@enderror
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-wrap">
                                <label>or by Stock Id
                                    <div class="input">
                                        <textarea type="text" wire:model="stockIds" autocomplete="off"></textarea>
                                    </div>
                                    @error('stockIds')<x-input.error :message="$message"/>@enderror
                                </label>
                            </div>
                        </div>
                    @feature('customer-link-images-export-tweaks')
                        <div class="row input-wrap">
                            <label>Product Status</label>
                            <div class="inline-radio-group">
                                <div class="radio-wrap">
                                    <label class="radio">
                                        <input type="radio" wire:model="onlyActive" value="0" checked>
                                        <span class="radio-checkmark"></span>
                                        <span class="radio-label">All</span>
                                    </label>
                                </div>
                                <div class="radio-wrap">
                                    <label class="radio">
                                        <input type="radio" wire:model="onlyActive" value="1">
                                        <span class="radio-checkmark"></span>
                                        <span class="radio-label">Active Only</span>
                                    </label>
                                </div>
                                @error('onlyActive')<x-input.error :message="$message"/>@enderror
                            </div>
                        </div>
                        <div class="row input-wrap">
                            <label>Include Original Image?</label>
                            <div class="inline-radio-group">
                                <div class="radio-wrap">
                                    <label class="radio">
                                        <input type="radio" wire:model="includeOriginalImage" value="1">
                                        <span class="radio-checkmark"></span>
                                        <span class="radio-label">Yes</span>
                                    </label>
                                </div>
                                <div class="radio-wrap">
                                    <label class="radio">
                                        <input type="radio" wire:model="includeOriginalImage" value="0" checked>
                                        <span class="radio-checkmark"></span>
                                        <span class="radio-label">No</span>
                                    </label>
                                </div>
                                @error('includeOriginalImage')<x-input.error :message="$message"/>@enderror
                            </div>
                        </div>
                        <div class="row input-wrap">
                            <label>Include Small Image?</label>
                            <div class="inline-radio-group">
                                <div class="radio-wrap">
                                    <label class="radio">
                                        <input type="radio" wire:model="includeSmallImage" value="1" checked>
                                        <span class="radio-checkmark"></span>
                                        <span class="radio-label">Yes</span>
                                    </label>
                                </div>
                                <div class="radio-wrap">
                                    <label class="radio">
                                        <input type="radio" wire:model="includeSmallImage" value="0">
                                        <span class="radio-checkmark"></span>
                                        <span class="radio-label">No</span>
                                    </label>
                                </div>
                                @error('includeSmallImage')<x-input.error :message="$message"/>@enderror
                            </div>
                        </div>
                    <div class="row input-wrap">
                        <label>Include Large Image?</label>
                        <div class="inline-radio-group">
                            <div class="radio-wrap">
                                <label class="radio">
                                    <input type="radio" wire:model="includeLargeImage" value="1" checked>
                                    <span class="radio-checkmark"></span>
                                    <span class="radio-label">Yes</span>
                                </label>
                            </div>
                            <div class="radio-wrap">
                                <label class="radio">
                                    <input type="radio" wire:model="includeLargeImage" value="0">
                                    <span class="radio-checkmark"></span>
                                    <span class="radio-label">No</span>
                                </label>
                            </div>
                            @error('includeLargeImage')<x-input.error :message="$message"/>@enderror
                        </div>
                    </div>
                    @endfeature
                </div>
                    <button type="submit" wire:click="run" class="primary-btn block-btn mt-3" title="Export">
                        <i class="material-icons">save_alt</i>
                        Export
                    </button>
            </div>
        </div>
    </div>
</div>
