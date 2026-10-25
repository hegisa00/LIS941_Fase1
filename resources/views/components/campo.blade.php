@props(['name', 'label', 'type' => 'text', 'ayuda' => null])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <input
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        @if($type !== 'password') value="{{ old($name) }}" @endif
        {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($name)]) }}
    >
    @if($ayuda)
        <div class="form-text">{{ $ayuda }}</div>
    @endif
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
