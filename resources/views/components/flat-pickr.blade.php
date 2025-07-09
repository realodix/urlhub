@props([
    'name',
    'id' => null,
    'value' => '',
    'format' => 'Y-m-d H:i',
    'placeholder' => null,
    'options' => [],
])

@php
    $id = $id ?? $name;
    $value = old($name, $value ?? '');
    $placeholder = $placeholder ?? $format;
    $componentOptions = array_merge([
        'dateFormat' => $format,
        'altInput' => true,
        'enableTime' => true,
    ], (array) $options);
@endphp

<div wire:ignore>
    <input
        x-data="{ picker: null, }"
        x-init="$nextTick(() => {
            if (picker) return;

            picker = flatpickr($root, {{ json_encode((object) $componentOptions) }});
        })"
        name="{{ $name }}"
        type="text"
        id="{{ $id }}"
        placeholder="{{ $placeholder }}"

        @if($value) value="{{ $value }}" @endif
        {{ $attributes }}
    />
</div>
