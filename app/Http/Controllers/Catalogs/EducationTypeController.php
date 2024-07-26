<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\EducationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EducationTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:education-type-list', ['only' => ['index']]);
        $this->middleware('permission:education-type-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:education-type-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:education-type-delete', ['only' => ['destroy']]);
    }

    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $types = EducationType::orderBy('name', 'asc')->paginate(10);
        return view('Catalogs.EducationTypes.index', compact('types'));
    }

    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $catalog = EducationType::get();

        return view('Catalogs.EducationTypes.create', compact('catalog'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:document_types,name',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $educationType = EducationType::create(['name' => $request->input('name'), 'description' => $request->input('description')]);
        return redirect()->route('EducationTypes.index')
            ->with('success', 'Document type created successfully');
    }

    public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $catalog = EducationType::find($id);
        return view('Catalogs.EducationTypes.edit', compact('catalog'));
    }

    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $catalog = EducationType::find($id);
        $catalog->name = $request->input('name');
        $catalog->description = $request->input('description');
        $catalog->status = $request->input('status');
        $catalog->save();

        return redirect()->route('EducationTypes.index')
            ->with('success', 'Document type updated successfully');
    }

    public function show(Request $request): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $name = $request->name;
        $types = EducationType::whereName('LIKE', "%$name%")->paginate(10);
        $types->appends(request()->query())->links();
        if ($types->isEmpty()) {
            return redirect()->route('EducationTypes.index')->withErrors('Not Found!')->withInput();
        }
        return view('Catalogs.EducationTypes.index', compact('types', 'name'));
    }
}
