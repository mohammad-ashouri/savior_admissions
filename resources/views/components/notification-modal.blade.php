@props(['name'])

<div
    x-data="{ show: false }"
    x-show="show"
    x-on:show-notification.window="($event.detail == '{{ $name }}') ? show = true : null"
    x-transition.opacity
    class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50"
    style="display: none;"
>
    <div x-on:click.away="show = false"
         class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-xl w-full max-w-sm sm:max-w-md">
        <div class="flex items-center justify-between">
            <p class="text-gray-900 dark:text-gray-200 text-sm font-semibold">
                {{ $slot }}
            </p>
            <button x-on:click="show = false" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                ✕
            </button>
        </div>
    </div>
</div>
