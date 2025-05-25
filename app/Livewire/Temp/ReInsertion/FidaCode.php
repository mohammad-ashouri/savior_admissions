<?php

namespace App\Livewire\Temp\ReInsertion;

use App\Models\GeneralInformation;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Fida Code | Savior Schools')]
class FidaCode extends Component
{
    #[Validate('required|integer|digits:12|unique:general_informations,fida_code')]
    public $fida_code;

    public function save()
    {
        $this->validate();
        $general_information = GeneralInformation::where('user_id', auth()->user()->id)->first();
        $general_information->fida_code = $this->fida_code;
        $general_information->save();
        session()->flash('success', 'Fida Code Updated Successfully');
        return redirect()->to(route('dashboard'));
    }
}
