@props(['name', 'defer' => true, 'showError' => 'true'])

<div
    x-data="{ isUploading: false, progress: 0 }"
    x-on:livewire-upload-start="isUploading = true"
    x-on:livewire-upload-finish="isUploading = false; progress = 0"
    x-on:livewire-upload-cancel="isUploading = false; progress = 0"
    x-on:livewire-upload-error="isUploading = false; progress = 0"
    x-on:livewire-upload-progress="progress = $event.detail.progress"
>
    <input
        type="file"
        name="{{ $name }}"
        {{ $attributes->merge(['class' => $errors->has($name) ? ' is-invalid' : '']) }}
        @if ($defer == 'true')
            wire:model="{{ $name }}"
        @else
            wire:model.live="{{ $name }}"
        @endif
    />

    <template x-if="isUploading">
        <div class="progress mt-2">
            <div class="progress-bar" role="progressbar" :style="`width: ${progress}%`">
                <span x-text="progress + '%'"></span>
            </div>
        </div>
    </template>
</div>

@if ($showError == 'true')
    @error($name)
        <span class="invalid-feedback" style="display: block">
            {{ $message }}
        </span>
    @enderror
@endif
