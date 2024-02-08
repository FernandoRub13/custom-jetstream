@props(['id'])
@if (config('services.recaptcha.enable'))
    <div class="flex flex-col items-center justify-center gap-4 mt-6">
        <div id="{{ $id }}" class="g-recaptcha" wire:ignore></div>
        @error('g-recaptcha-response') <span class="error">{{ $message }}</span> @enderror
    </div>
@endif
