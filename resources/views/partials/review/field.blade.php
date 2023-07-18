<div class="review-field">
    {{ $prefix ?? '' }}
    @if (isset($format))
    @if ($format === 'boolean')
    {{ old($formField ?? $field, optional($model)->$field) ? 'Yes' : 'No' }}
    @elseif ($format === 'ucfirst')
    {{ ucfirst(old($formField ?? $field, optional($model)->$field)) ?? '-' }}
    @elseif ($format === 'in_array' && isset($array) && optional($model)->$field && Arr::has($array, optional($model)->$field))
    {{ $array[old($formField ?? $field, optional($model)->$field)] }}
    @elseif ($format === 'bitarray' && isset($bitarray))
    {{ App\Helpers\BitArrayHelper::toString(old($formField ?? $field, optional($model)->$field), $bitarray) }}
    @else
    {{ old($formField ?? $field, optional($model)->$field) ?? '-' }}
    @endif
    @else
    @if (isset($subfield))
    @if (isset($wrapper))
    {{ call_user_func($wrapper, optional(optional($model)->$field)->$subfield) ?? '-' }}
    @else
    {{ optional(optional($model)->$field)->$subfield ?? '-' }}
    @endif
    @else
    @if (isset($wrapper))
    {{ call_user_func($wrapper, old($formField ?? $field, optional($model)->$field)) ?? '-' }}
    @else
    {{ old($formField ?? $field, optional($model)->$field) ?? '-' }}
    @endif
    @endif
    @endif
    {{ $suffix ?? '' }}
    @if ($errors->{$key}->has($formField ?? $field))
    <div class="error-message">{{ $errors->{$key}->first($errorField ?? ($formField ?? $field)) == 'Required' ? 'Missing' : $errors->{$key}->first($errorField ?? ($formField ?? $field)) }}</div>
    @endif
</div>
