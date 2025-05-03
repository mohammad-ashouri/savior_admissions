@props(['messages', 'type' => 'info', 'title' => null])

@if ($messages)
    <div {{ $attributes->merge(['class' => 'p-4 rounded-md text-sm space-y-1 bg-white dark:bg-gray-800 shadow-sm']) }}>
        @if ($title)
            <h4 class="font-semibold {{
                $type === 'success' ? 'text-green-700 dark:text-green-400' :
                ($type === 'warning' ? 'text-yellow-700 dark:text-yellow-400' :
                ($type === 'error' ? 'text-red-700 dark:text-red-400' :
                'text-blue-700 dark:text-blue-400'))
            }}">
                {{ $title }}
            </h4>
        @endif

        @foreach ((array) $messages as $message)
            <div class="flex items-center">
                <!-- آیکون بر اساس نوع اطلاع‌رسانی -->
                @if ($type === 'success')
                    <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-2" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                @elseif ($type === 'warning')
                    <svg class="w-5 h-5 text-yellow-500 dark:text-yellow-400 mr-2" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                @elseif ($type === 'error')
                    <svg class="w-5 h-5 text-red-500 dark:text-red-400 mr-2" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                @else
                    <svg class="w-5 h-5 text-blue-500 dark:text-blue-400 mr-2" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                @endif

                <!-- متن اطلاع‌رسانی -->
                <span class="{{
                    $type === 'success' ? 'text-green-700 dark:text-green-400' :
                    ($type === 'warning' ? 'text-yellow-700 dark:text-yellow-400' :
                    ($type === 'error' ? 'text-red-700 dark:text-red-400' :
                    'text-blue-700 dark:text-blue-400'))
                }}">
                    {{ $message }}
                </span>
            </div>
        @endforeach
    </div>
@endif
