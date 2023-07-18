@if ($message)
    <div class="container pb-2">
        <div class="row justify-content-center">
            <div class="col-7">
                <div class="alert-danger">
                    <i class="material-icons">error</i>
                    <p>{{ $message }}</p>
                </div>
            </div>
        </div>
    </div>
@endif
