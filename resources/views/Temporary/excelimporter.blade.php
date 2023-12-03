users
<form action="{{ route('excel.importUsers') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="excel_file" required>
    <button type="submit">آپلود فایل</button>
</form>
document types
<form action="{{ route('excel.importDocumentTypes') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="excel_file" required>
    <button type="submit">آپلود فایل</button>
</form>
documents
<form action="{{ route('excel.importDocuments') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="excel_file" required>
    <button type="submit">آپلود فایل</button>
</form>
