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
        $this->validate([
            'report_type' => 'in:TuitionCardFa,TuitionCardEn'
        ]);
        return view('livewire.reports.report');
    }
}
