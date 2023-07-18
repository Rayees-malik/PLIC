@can('imports.pricing-update')
<div class="row justify-content-center">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">Pricing Update</h2>
            </div>
            <div class="card-body">
                <div class="formContainer">
                    <form method="POST" action="{{ route('imports.pricing-update') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="container">
                            <div class="row">
                                <div class="dropdown-wrap">
                                    <label>Vendor Relations Specialist</label>
                                    <div class="dropdown-icon">
                                        <select name="vrs">
                                            <option value="">Select ...</option>
                                            @foreach ($users as $user)
                                            <option value="{{ $user->id }}">
                                                {{ $user->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <input type="file" name="data"
                                    accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                    <button type="submit" class="primary-btn block-btn mt-3" title="Upload">
                                        <i class="material-icons">save_alt</i>
                                        Upload Pricing Update File
                                    </button>
                                </div>
                            <small><em>This will add signoffs for finance to approve the pricing update.</em></small>
                        </div>
                    </form>
                </div </div>
            </div>
        </div>
    </div>
    @endcan
