@props(['disabled' => false, 'wireModel' => null, 'placeholder' => '', 'rows' => 3])

<textarea
    @if($disabled) disabled @endif
@if($wireModel) wire:model="{{ $wireModel }}" @endif
    placeholder="{{ $placeholder }}"
    rows="{{ $rows }}"
    {!! $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm']) !!}
>{{ $slot }}</textarea>
