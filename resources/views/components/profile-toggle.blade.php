{{-- Interrupteur de préférence (statique pour l'instant) — <x-profile-toggle title="…" desc="…" :checked="true" /> --}}
@props(['title', 'desc', 'checked' => true])

<div class="flex items-center justify-between gap-space-md py-space-md first:pt-0 last:pb-0">
    <div class="min-w-0">
        <p class="text-label-lg font-label-lg text-primary font-bold">{{ $title }}</p>
        <p class="text-body-sm font-body-sm text-on-surface-variant">{{ $desc }}</p>
    </div>
    <label class="relative inline-flex items-center cursor-pointer shrink-0">
        <input type="checkbox" class="sr-only peer" @checked($checked) aria-label="{{ $title }}">
        <div class="w-11 h-6 bg-outline-variant peer-focus-visible:ring-2 peer-focus-visible:ring-primary-container/40 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-container"></div>
    </label>
</div>