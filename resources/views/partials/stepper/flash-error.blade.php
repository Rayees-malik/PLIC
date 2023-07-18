@if ($errors->flash->has("{$key}-flash"))
@foreach ($errors->flash->get("{$key}-flash") as $message)
@include('partials.errors.error-flash', ['message' => $message])
@endforeach
@endif
