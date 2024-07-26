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

    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $types = DocumentType::orderBy('name', 'asc')->paginate(10);
        return view('Catalogs.DocumentTypes.index', compact('types'));
    }

    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $catalog = DocumentType::get();

        return view('Catalogs.DocumentTypes.create', compact('catalog'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:document_types,name',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $documentType = DocumentType::create(['name' => $request->input('name')]);

        return redirect()->route('DocumentTypes.index')
            ->with('success', 'Document type created successfully');
    }

    public function show(Request $request): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $name = $request->name;
        $types = DocumentType::whereName('LIKE', "%$name%")->paginate(10);
        $types->appends(request()->query())->links();
        if ($types->isEmpty()) {
            return redirect()->route('DocumentTypes.index')->withErrors('Not Found!')->withInput();
        }
        return view('Catalogs.DocumentTypes.index', compact('types', 'name'));
    }

    public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $catalog = DocumentType::find($id);
        return view('Catalogs.DocumentTypes.edit', compact('catalog'));
    }

    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:document_types,name',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $catalog = DocumentType::find($id);
        $catalog->name = $request->input('name');
        $catalog->status = $request->input('status');
        $catalog->save();

        return redirect()->route('DocumentTypes.index')
            ->with('success', 'Document type updated successfully');
    }
}
