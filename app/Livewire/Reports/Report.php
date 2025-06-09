<?php

namespace App\Livewire\Reports;

use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Report extends Component
{
    #[Url]
    public $report_type = null;

    public function render()
    {
        return view('livewire.reports.report');
    }
}
