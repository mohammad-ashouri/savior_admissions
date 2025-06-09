<?php

namespace App\Livewire\Reports\Types;

use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;

class TuitionCardFa extends Component
{
    #[Url]
    public $appliance_id;

    public function mount()
    {
        if (!property_exists($this, 'appliance_id') || $this->appliance_id === null) {
            abort(404);
        }
    }

    public function render()
    {
        return view('livewire.reports.types.tuition-card-fa');
    }
}
