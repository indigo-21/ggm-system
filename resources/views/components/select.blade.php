@props([
    'name',
    'value' => '',
    'label' => '',
    'class' => '',
    'required' => false,
    'disabled' => false,
    'multiple' => false,
    'search' => false,
    'hasinput' => false,
    'placeholder' => '',
    'timestamp' => false,
    'has_timestamp' => false,
    'labelstyle' => '',
    'error' => null,
])

<div class="form-group form-float">
    @if($label)
        <label for="{{ $name }}" style="{{ $labelstyle }}">{{ $label }}</label>
    @endif

    @php
        $datetime = '';
        if ($timestamp) {
            $datetime = \Carbon\Carbon::parse($timestamp)->format('F d, Y h:i A');
        }
    @endphp

    @if (!$hasinput)
        <select
            class="form-control {{ $class }} {{ $has_timestamp ? 'select-timestamp' : '' }}"
            name="{{ $name }}"
            id="{{ $name }}"
            data-dropup-auto="false"
            aria-label="{{ $label ?: $name }}"
            @if($required) required aria-required="true" @endif
            @if($disabled) disabled aria-disabled="true" @endif
            @if($multiple) multiple @endif
            @if($search) data-live-search="true" @endif
        >
            {{ $slot }}
        </select>
    @else
        <input
            type="text"
            class="form-control datalist-input"
            list="{{ $name }}_list"
            name="{{ $name }}"
            id="{{ $name }}"
            placeholder="{{ $placeholder }}"
            aria-label="{{ $label ?: $name }}"
            @if($required) required aria-required="true" @endif
            @if($disabled) disabled aria-disabled="true" @endif
            autocomplete="off"
        >
        <datalist id="{{ $name }}_list">{{ $slot }}</datalist>
    @endif

    <label id="{{ $name }}-error" class="error" for="{{ $name }}" role="alert" aria-live="polite">{{ $error }}</label>

    @if($timestamp || $has_timestamp)
        <span class="span-timestamp text-muted small">{{ $timestamp ? $datetime : '' }}</span>
        <input type="hidden" name="{{ $name }}_timestamp" value="{{ $timestamp ? $datetime : '' }}">
    @endif
</div>
