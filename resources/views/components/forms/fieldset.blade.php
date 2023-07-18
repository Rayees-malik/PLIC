@props(['legend' => ''])

<fieldset {{ $attributes->merge(['class' => 'input-wrap']) }}>
    @if ($legend != '')
      <legend>{{ $legend }}</legend>
    @endif

    {{ $slot }}
</fieldset>
