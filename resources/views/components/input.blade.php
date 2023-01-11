<div class="form-group">
    <label for="{{ $name }}">{{ $label }}</label>
    <input
        class="form-control form-control-lg mb-3 @error($name) is-invalid @enderror"
        type="{{ $type }}"
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"

        {{ $attributes->merge(['value' => old($name), 'id' => $name]) }}
    />

    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror

    {{ $slot }}
</div>
