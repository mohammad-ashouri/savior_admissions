<form action="{{ route('excel.import') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="excel_file" required>
    <button type="submit">آپلود فایل</button>
</form>
