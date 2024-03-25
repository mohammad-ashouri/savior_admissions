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

    public function index()
    {
        $types = EducationType::orderBy('name', 'asc')->paginate(10);
        $this->logActivity(json_encode(['activity' => 'Getting Education Type']), request()->ip(), request()->userAgent());

        return view('Catalogs.EducationTypes.index', compact('types'));
    }

    public function create()
    {
        $catalog = EducationType::get();

        return view('Catalogs.EducationTypes.create', compact('catalog'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:document_types,name',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            $this->logActivity(json_encode(['activity' => 'Saving Education Type Failed', 'errors' => json_encode($validator)]), request()->ip(), request()->userAgent());

            return redirect()->back()->withErrors($validator)->withInput();
        }
        $educationType = EducationType::create(['name' => $request->input('name'), 'description' => $request->input('description')]);
        $this->logActivity(json_encode(['activity' => 'Education Type Saved', 'id' => $educationType->id]), request()->ip(), request()->userAgent());

        return redirect()->route('EducationTypes.index')
            ->with('success', 'Document type created successfully');
    }

    public function edit($id)
    {
        $catalog = EducationType::find($id);
        $this->logActivity(json_encode(['activity' => 'Getting Education Type Information For Edit', 'id' => $catalog->id]), request()->ip(), request()->userAgent());

        return view('Catalogs.EducationTypes.edit', compact('catalog'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            $this->logActivity(json_encode(['activity' => 'Saving Education Type Failed', 'errors' => json_encode($validator)]), request()->ip(), request()->userAgent());

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $catalog = EducationType::find($id);
        $catalog->name = $request->input('name');
        $catalog->description = $request->input('description');
        $catalog->status = $request->input('status');
        $catalog->save();
        $this->logActivity(json_encode(['activity' => 'Education Type Updated', 'id' => $catalog->id]), request()->ip(), request()->userAgent());

        return redirect()->route('EducationTypes.index')
            ->with('success', 'Document type updated successfully');
    }

    public function show(Request $request)
    {
        $name = $request->name;
        $types = EducationType::where('name', 'LIKE', "%$name%")->paginate(10);
        $types->appends(request()->query())->links();
        if ($types->isEmpty()) {
            $this->logActivity(json_encode(['activity' => 'Getting Education Type Informations', 'entered_name' => $request->name, 'status' => 'Not Found']), request()->ip(), request()->userAgent());

            return redirect()->route('EducationTypes.index')->withErrors('Not Found!')->withInput();
        }
        $this->logActivity(json_encode(['activity' => 'Getting Education Type Informations', 'entered_name' => $request->name, 'status' => 'Founded']), request()->ip(), request()->userAgent());

        return view('Catalogs.EducationTypes.index', compact('types', 'name'));
    }
}
