<?php

namespace App\ExcelExports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentStatuses implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public $students;

    public function __construct($students)
    {
        $this->students = $students;
    }

    public function collection()
    {
        return $this->students;
    }

    public function headings(): array
    {
        return [
            'appliance id',
            'guardian information',
            'guardian mobile',
            'academic year',
            'student id',
            'student information',
            'gender',
            'interview status',
            'document upload status',
            'document approval status',
            'document approval seconder name',
            'tuition payment status',
            'approval status',
        ];
    }

    public function map($row): array
    {
        $documentUploadStatus=null;
        switch($row->documents_uploaded) {
            case '0':
                $documentUploadStatus='Pending For Upload';
                break;
            case '1':
                $documentUploadStatus='Admitted';
                break;
            case '2':
                $documentUploadStatus='Pending For Review';
                break;
            case '3':
                $documentUploadStatus='Rejected';
                break;
        }
        return [
            $row->id,
            $row->studentInformations->guardianInfo->generalInformationInfo->first_name_en.' '.$row->studentInformations->guardianInfo->generalInformationInfo->last_name_en,
            $row->studentInformations->guardianInfo->mobile,
            $row->academicYearInfo->name,
            $row->student_id,
            $row->studentInformations->generalInformations->first_name_en.' '.$row->studentInformations->generalInformations->last_name_en,
            $row->studentInformations->generalInformations->gender,
            $row->interview_status,
            $documentUploadStatus,
            $row->document_approval_status,
            @$row->documentSeconder->generalInformationInfo->first_name_en.' '.@$row->documentSeconder->generalInformationInfo->last_name_en,
            $row->tuition_payment_status,
            $row->approval_status,
        ];
    }
}
