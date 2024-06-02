<?php

namespace App\ExcelExports;

use App\Models\GeneralInformation;
use App\Models\StudentInformation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AllGuardiansWithStudents implements FromCollection, ShouldAutoSize, WithHeadings
{
    protected $data;

    protected $maxStudents = 0;

    public function __construct()
    {
        $this->data = $this->prepareData();
    }

    public function prepareData(): Collection
    {
        $guardians = StudentInformation::pluck('guardian')->unique();
        $data = collect();

        foreach ($guardians as $guardianId) {
            $guardianInfo = GeneralInformation::with('user')->where('user_id', $guardianId)->first();

            if ($guardianInfo) {
                $students = StudentInformation::where('guardian', $guardianId)->get();

                $studentData = [];
                foreach ($students as $student) {
                    $studentData[] = $student->student_id.' - '.$student->generalInformations->first_name_en.' - '.$student->generalInformations->last_name_en;
                }

                // به روزرسانی تعداد حداکثری دانش‌آموزان برای تنظیم عنوان‌ها
                if (count($studentData) > $this->maxStudents) {
                    $this->maxStudents = count($studentData);
                }

                $data->push(array_merge([
                    'guardian_id' => $guardianInfo->user_id ?? 'نامشخص',
                    'guardian_first_name' => $guardianInfo->first_name_en ?? 'نامشخص',
                    'guardian_last_name' => $guardianInfo->last_name_en ?? 'نامشخص',
                    'guardian_mobile' => $guardianInfo->user->mobile ?? 'نامشخص',
                ], $studentData));
            }
        }

        return $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        $headings = [
            'Guardian ID',
            'Guardian First Name',
            'Guardian Last Name',
            'Guardian Mobile',
        ];

        // اضافه کردن عناوین دانش‌آموزان
        for ($i = 1; $i <= $this->maxStudents; $i++) {
            $headings[] = 'Student '.$i;
        }

        return $headings;
    }

    public function map($row): array
    {
        $guardianData = [
            $row['guardian_id'],
            $row['guardian_first_name'],
            $row['guardian_last_name'],
            $row['guardian_mobile'],
        ];

        $studentData = array_slice($row, 4); // دانش‌آموزان از ستون ۵ به بعد شروع می‌شوند

        return array_merge($guardianData, $studentData);
    }
}
