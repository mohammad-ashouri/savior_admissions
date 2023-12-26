<?php

namespace App\Http\Controllers;

use App\Models\Catalogs\DocumentType;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        $documentTypes = DocumentType::orderBy('name', 'asc')->get();
        $myDocuments=Document::with('documentType')->where('user_id',session('id'))->get();
        $myDocumentTypes=Document::with('documentType')->where('user_id',session('id'))->pluck('document_type_id')->all();
        $myDocumentTypes = array_unique($myDocumentTypes);
        return view('Documents.index', compact('documentTypes','myDocuments','myDocumentTypes'));
    }

    public function createDocument(Request $request)
    {
        $this->validate($request, [
            'document_type' => 'exists:document_types,id',
            'document_file' => 'required|mimes:png,jpg,jpeg,pdf,bmp|max:2048',
        ]);
        $path = $request->file('document_file')->store('public/uploads/Documents/'.session('id'));
        $document = new Document();
        $document->user_id = session('id');
        $document->document_type_id = $request->input('document_type');
        $document->src = $path;
        $document->save();
        $this->logActivity('Document added by id => ' . $document->id, request()->ip(), request()->userAgent(), session('id'));
        return response()->json(['success' => 'Document added!'], 200);

    }

    public function showUserDocuments($user_id)
    {
        $documentTypes = DocumentType::orderBy('name', 'asc')->get();
        $myDocuments=Document::with('documentType')->where('user_id',$user_id)->get();
        $myDocumentTypes=Document::with('documentType')->where('user_id',$user_id)->pluck('document_type_id')->all();
        $documentForUser=true;
        return view('Documents.index', compact('documentTypes','myDocuments','myDocumentTypes','user_id'));

    }
}
