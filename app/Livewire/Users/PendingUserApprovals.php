<?php

namespace App\Livewire\Users;

use App\Models\GeneralInformation;
use App\Models\User;
use App\Traits\SMS;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class PendingUserApprovals extends Component
{
    use WithPagination, SMS;

    #[Validate('nullable|integer|exists:users,id')]
    public $user_id;

    #[Validate('nullable|string|max:50')]
    public $first_name_en;

    #[Validate('nullable|string|max:50')]
    public $last_name_en;

    #[Validate('nullable|string|max:50')]
    public $first_name_fa;

    #[Validate('nullable|string|max:50')]
    public $last_name_fa;

    #[Validate('nullable|string|max:12')]
    public $mobile;

    #[Validate('nullable|string|max:60')]
    public $email;

    /**
     * Search in users
     * @return void
     */
    public function search(): void
    {
        $this->validate();
    }

    /**
     * Change users status (if spam or not)
     * @param $user_id
     * @return void
     */
    public function changeStatus($user_id): void
    {
        if (!auth()->user()->can('pending-user-approvals.approve')) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make(
            [
                'user_id' => $user_id,
            ],
            [
                'user_id' => 'required|integer|exists:users,id',
            ]
        );

        if ($validator->fails()) {
            abort(422, $validator->errors()->first());
        }
        LivewireAlert::title('Is this user approved?')
            ->withConfirmButton('Yes, Approve', 'green')
            ->asConfirm()
            ->withDenyButton('No, Delete', 'red')
            ->withCancelButton()
            ->onConfirm('approveUser', ['id' => $user_id])
            ->onDeny('deleteUser', ['id' => $user_id])
            ->show();
    }

    /**
     * Delete user if not approved
     * @param $id
     * @return void
     */
    public function deleteUser($id): void
    {
        $user = User::where('id', $id)->first();
        GeneralInformation::where('user_id', $this->user_id)->first()->delete();

        $this->sendSMS($user->mobile, 'Dear user, your account has been removed from Savior Schools system. If this was a mistake or you need assistance, please contact our admissions specialist.\nSavior Schools');
        $user->delete();
        LivewireAlert::title('User Deleted!')
            ->withCancelButton()
            ->success()
            ->show();
    }

    /**
     * Approve user
     * @param $id
     * @return void
     */
    public function approveUser($id): void
    {
        $user = User::where('id', $id)->first();
        $user->update([
            'status' => 1
        ]);
        GeneralInformation::where('user_id', $id)->first()->update([
            'status' => 1
        ]);
        $this->sendSMS($user->mobile, 'Dear user, your account has been approved. Please log in using your registered mobile number and password.\n portal.saviorschools.com \nSavior Schools');

        LivewireAlert::title('User Activated!')
            ->withCancelButton()
            ->success()
            ->show();
    }

    /**
     * Reset search form
     * @return void
     */
    public function resetSearchForm(): void
    {
        $this->reset();
        $this->resetErrorBag();
    }

    /**
     * Render the component
     * @return Application|Factory|View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function render()
    {
        if (!auth()->user()->can('pending-user-approvals.view')) {
            abort(403);
        }

        $data = User::query()->where('status', 2);

        $data->when(!empty($this->user_id), function ($query) {
            $query->where('id', $this->user_id);
        });

        $data->when(!empty($this->email), function ($query) {
            $query->where('email', 'like', '%' . $this->email . '%');
        });

        $data->when(!empty($this->mobile), function ($query) {
            $query->where('mobile', 'like', '%' . $this->mobile . '%');
        });

        $data->when(!empty($this->first_name_fa), function ($query) {
            $query->whereHas('generalInformationInfo', function ($query) {
                $query->where('first_name_fa', 'like', '%' . $this->first_name_fa . '%');
            });
        });

        $data->when(!empty($this->last_name_fa), function ($query) {
            $query->whereHas('generalInformationInfo', function ($query) {
                $query->where('last_name_fa', 'like', '%' . $this->last_name_fa . '%');
            });
        });

        $data->when(!empty($this->first_name_en), function ($query) {
            $query->whereHas('generalInformationInfo', function ($query) {
                $query->where('first_name_en', 'like', '%' . $this->first_name_en . '%');
            });
        });

        $data->when(!empty($this->last_name_en), function ($query) {
            $query->whereHas('generalInformationInfo', function ($query) {
                $query->where('last_name_en', 'like', '%' . $this->last_name_en . '%');
            });
        });

        return view('livewire.users.pending-user-approvals', [
            'data' => $data->orderByDesc('created_at')->paginate(50),
        ]);
    }
}
