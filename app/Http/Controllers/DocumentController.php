<?php

namespace App\Http\Controllers;

use App\Models\Catalogs\DocumentType;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    public function index()
    {
        $documentTypes = DocumentType::orderBy('name', 'asc')->get();
        $myDocuments = Document::with('documentType')->where('user_id', session('id'))->orderBy('id','desc')->get();
        $myDocumentTypes = Document::with('documentType')->where('user_id', session('id'))->pluck('document_type_id')->all();
        $myDocumentTypes = array_unique($myDocumentTypes);
        return view('Documents.index', compact('documentTypes', 'myDocuments', 'myDocumentTypes'));
    }

    public function createDocument(Request $request)
    {
        $this->validate($request, [
            'document_type' => 'exists:document_types,id',
            'document_file' => 'required|mimes:png,jpg,jpeg,pdf,bmp|max:2048',
        ]);
        $path = $request->file('document_file')->store('public/uploads/Documents/' . session('id'));
        $document = new Document();
        $document->user_id = session('id');
        $document->document_type_id = $request->document_type;
        $document->src = $path;
        $document->save();
        $this->logActivity(json_encode(['activity' => 'User document added', 'document id' => $document->id, 'user id' => $document->user_id]), request()->ip(), request()->userAgent(), session('id'));
        return response()->json(['success' => 'Document added!'], 200);
    }
    public function createDocumentForUser(Request $request, $user_id)
    {
        $this->validate($request, [
            'document_type' => 'exists:document_types,id',
            'document_file' => 'required|mimes:png,jpg,jpeg,pdf,bmp|max:2048',
            'user_id' => Rule::exists('users', 'id')->where(function ($query) use ($user_id) {
                $query->where('id', (int)$user_id);
            }),
        ]);
        $path = $request->file('document_file')->store('public/uploads/Documents/' . session('id'));
        $document = new Document();
        $document->user_id = $user_id;
        $document->document_type_id = $request->document_type;
        $document->src = $path;
        $document->save();
        $this->logActivity(json_encode(['activity' => 'User document added', 'document id' => $document->id, 'user id' => $document->user_id]), request()->ip(), request()->userAgent(), session('id'));
        return response()->json(['success' => 'Document added!'], 200);
    }

    public function showUserDocuments($user_id)
    {
        $documentTypes = DocumentType::orderBy('name', 'asc')->get();
        $myDocuments = Document::with('documentType')->where('user_id', $user_id)->orderBy('id','desc')->get();
        $documentOwner=Document::where('user_id', $user_id)->with('documentOwner')->first();
        $myDocumentTypes = Document::with('documentType')->where('user_id', $user_id)->pluck('document_type_id')->all();
        return view('Documents.index', compact('documentTypes', 'myDocuments', 'myDocumentTypes', 'documentOwner', 'user_id'));

    }
}
