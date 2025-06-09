<div>
    @switch ($this->report_type)
        @case('TuitionCardFa')
            <livewire:reports.types.tuition-card-fa/>
            @break
        @default
            {{ abort(404) }}
    @endswitch
</div>
