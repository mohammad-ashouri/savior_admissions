<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DocumentTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:document-type-list', ['only' => ['index']]);
        $this->middleware('permission:document-type-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:document-type-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:document-type-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $types = DocumentType::orderBy('name', 'asc')->paginate(10);
        $this->logActivity(json_encode(['activity' => 'Getting Document Types']), request()->ip(), request()->userAgent());

        return view('Catalogs.DocumentTypes.index', compact('types'));
    }

    public function create()
    {
        $catalog = DocumentType::get();

        return view('Catalogs.DocumentTypes.create', compact('catalog'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:document_types,name',
        ]);

        if ($validator->fails()) {
            $this->logActivity(json_encode(['activity' => 'Saving Document Type Failed', 'errors' => json_encode($validator)]), request()->ip(), request()->userAgent());

            return redirect()->back()->withErrors($validator)->withInput();
        }
        $documentType = DocumentType::create(['name' => $request->input('name')]);
        $this->logActivity(json_encode(['activity' => 'Document Type Saved', 'id' => $documentType->id]), request()->ip(), request()->userAgent());

        return redirect()->route('DocumentTypes.index')
            ->with('success', 'Document type created successfully');
    }

    public function show(Request $request)
    {
        $name = $request->name;
        $types = DocumentType::where('name', 'LIKE', "%$name%")->paginate(10);
        $types->appends(request()->query())->links();
        if ($types->isEmpty()) {
            $this->logActivity(json_encode(['activity' => 'Getting Document Type Informations', 'entered_name' => $request->name, 'status' => 'Not Found']), request()->ip(), request()->userAgent());

            return redirect()->route('DocumentTypes.index')->withErrors('Not Found!')->withInput();
        }
        $this->logActivity(json_encode(['activity' => 'Getting Document Type Informations', 'entered_name' => $request->name, 'status' => 'Founded']), request()->ip(), request()->userAgent());

        return view('Catalogs.DocumentTypes.index', compact('types', 'name'));
    }

    public function edit($id)
    {
        $catalog = DocumentType::find($id);
        $this->logActivity(json_encode(['activity' => 'Getting Document Type Information For Edit', 'id' => $catalog->id]), request()->ip(), request()->userAgent());

        return view('Catalogs.DocumentTypes.edit', compact('catalog'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:document_types,name',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            $this->logActivity(json_encode(['activity' => 'Saving Document Type Failed', 'errors' => json_encode($validator)]), request()->ip(), request()->userAgent());

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $catalog = DocumentType::find($id);
        $catalog->name = $request->input('name');
        $catalog->status = $request->input('status');
        $catalog->save();

        $this->logActivity(json_encode(['activity' => 'Document Type Updated', 'id' => $catalog->id]), request()->ip(), request()->userAgent());

        return redirect()->route('DocumentTypes.index')
            ->with('success', 'Document type updated successfully');
    }
}
