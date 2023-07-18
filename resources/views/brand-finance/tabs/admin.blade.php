<div class="container admin-body">
    <div class="col-xl-12 mb-3">
        <form method="POST" action="{{ route('brand-finance.force-upload') }}">
            @csrf
            <h2>Force File Upload Process</h2>
            <button class="primary-btn" type="submit">Start Process</button>
        </form>
    </div>
</div>
