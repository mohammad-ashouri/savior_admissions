<?php

namespace App\Livewire\Documents;

use App\Models\Catalogs\DocumentType;
use App\Models\Document;
use App\Models\StudentInformation;
use App\Models\User;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class Show extends Component
{
    use WithFileUploads;

    #[Locked]
    public User $user;

    public $documentTypes;
    public $myDocuments;
    public $documentOwner;
    public $myDocumentTypes;

    #[Validate('required|integer|exists:document_types,id')]
    public $document_type;

    #[Validate('required|file|mimes:png,jpg,jpeg,pdf,bmp|max:2048')]
    public $document_file;

    public $showModal = false;

    protected $listeners = [
        '$refresh',
    ];

    protected $rules = [
        'document_type' => 'required',
        'document_file' => 'required|file|mimes:png,jpg,jpeg,pdf,bmp|max:2048'
    ];

    public function createDocument()
    {
        $this->validate();
        $path = $this->document_file->store('public/uploads/Documents/' . $this->user->id);
        $document = new Document;
        $document->user_id = $this->user->id;
        $document->document_type_id = $this->document_type;
        $document->src = $path;
        $document->save();

        $this->reset(['document_type', 'document_file']);
        $this->mount($this->user->id);
        session()->flash('success', 'Document created successfully.');
        $this->dispatch('close-modal');
    }

    /**
     * Mount the component
     * @param $user_id
     * @return void
     */
    public function mount($user_id)
    {
        $this->user = User::findOrFail($user_id);

        if (!auth()->user()->can('document-list')) {
            abort(403, 'Unauthorized action.');
        }

        if (auth()->user()->hasExactRoles(['Parent'])) {
            $students = StudentInformation::where('guardian', auth()->id())->pluck('student_id')->toArray();
            if (!in_array($user_id, $students) and $user_id != auth()->user()->id) {
                abort(403, 'Unauthorized action.');
            }
        }

        $this->documentTypes = DocumentType::orderBy('name', 'asc')->get();
        $this->myDocuments = Document::with('documentType')->whereUserId($this->user->id)->orderBy('id', 'desc')->get();
        $this->documentOwner = User::find($this->user->id);
        $myDocumentTypes = Document::with('documentType')
            ->whereUserId($this->user->id)
            ->distinct('document_type_id')
            ->pluck('document_type_id')
            ->toArray();

        $this->myDocumentTypes = DocumentType::whereIn('id', $myDocumentTypes)->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
    }

    public function render()
    {

        return view('livewire.documents.show', [
            'documentTypes' => $this->documentTypes,
            'myDocuments' => $this->myDocuments,
            'documentOwner' => $this->documentOwner,
            'myDocumentTypes' => $this->myDocumentTypes,
        ]);
    }
}
