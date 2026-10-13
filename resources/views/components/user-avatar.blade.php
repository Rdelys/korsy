@props([
    'user',
    'size' => 'w-9 h-9',
    'text' => 'text-label-lg font-label-lg',
    'bg'   => 'bg-primary-container',
])

@if ($user->avatarUrl())
    <img src="{{ $user->avatarUrl() }}" alt="Photo de {{ $user->name }}" loading="lazy" decoding="async"
         {{ $attributes->merge(['class' => $size.' rounded-[50%] object-cover flex-shrink-0 bg-surface-container']) }}>
@else
    <span aria-hidden="true"
          {{ $attributes->merge(['class' => $size.' rounded-[50%] '.$bg.' text-on-primary flex items-center justify-center flex-shrink-0 '.$text]) }}>{{ $user->initial() }}</span>
@endif