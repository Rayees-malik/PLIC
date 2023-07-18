<div class="review-field">
    {{ optional($model->$relation)->count() ? implode(', ', Arr::wrap(optional($model->$relation->pluck($field))->toArray())) : '-' }}
    @if ($errors->{$key}->has($errorField ?? $relation))
    <div class="error-message">{{ $errors->{$key}->first($errorField ?? $relation) == 'Required' ? 'Missing' : $errors->{$key}->first($errorField ?? $relation) }}</div>
    @endif
</div>
