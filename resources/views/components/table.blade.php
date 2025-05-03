@props([
    'headers',
     'rows',
      'showEditButton'=>false,
       'showDeleteButton'=>false,
       'route'
])

<div class="overflow-x-auto">
    <table
        class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
        <thead class="bg-gray-50 dark:bg-gray-700">
        <tr>
            @foreach($headers as $header)
                <th class="px-6 py-3  text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    {{ $header }}
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
        @foreach($rows as $row)
            <tr wire:key="{{ $row[1] }} - {{ $loop->iteration }}"
                class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                @foreach($row as $cell)
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900 dark:text-gray-200">
                        {{ $cell }}
                    </td>
                @endforeach
                @if(in_array('عملیات',$headers))
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900 dark:text-gray-200">
                        @if($showEditButton)
                            @if(isset($route))
                                <x-secondary-button
                                    wire:navigate
                                    href="{{ route($route, $row[0]) }}"
                                    title="ویرایش"
                                >ویرایش
                                </x-secondary-button>
                            @else
                                <x-secondary-button
                                    x-on:click="$dispatch('open-modal', 'edit'); $dispatch('get_data', { id: {{ $row[0] }} } );"
                                    title="ویرایش"
                                >ویرایش
                                </x-secondary-button>
                            @endif
                        @endif
                        @if($showDeleteButton)
                            <x-danger-button
                                x-on:click="$dispatch('open-modal', 'delete');"
                                title="حذف"
                            >حذف
                            </x-danger-button>
                        @endif
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
