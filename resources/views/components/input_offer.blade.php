<div class="form-group">
    <label for="{{ $name }}">{{ $label }}</label>
    <input
        class="form-control form-control-lg mb-3 @if(session($name)) is-invalid @endif"
        type="{{ $type }}"
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        value="{{ old($name, $value) }}"

        {{ $attributes->merge(['value' => old($name), 'id' => $name]) }}
    />

    @if(session($name))
        <div class="invalid-feedback">
            {{ session($name) }}
        </div>
    @endif

    {{ $slot }}
</div>
