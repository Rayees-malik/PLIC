<div class="review-field">
    @forelse ($model->getMedia($collection) as $media)
    {{ $media('thumb') }}
    @if (isset($customProperty))
    <h5>{{ $media->getCustomProperty($customProperty) }}</h5>
    @endif
    @empty
    -
    @endforelse
    @if ($errors->{$key}->has($errorField ?? $collection))
    <div class="error-message">{{ $errors->{$key}->first($errorField ?? $collection) == 'Required' ? 'Missing' : $errors->{$key}->first($errorField ?? $collection) }}</div>
    @endif
</div>
