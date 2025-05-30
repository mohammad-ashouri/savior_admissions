<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\TemporarySeeders\AddConfirmInterviewForPrincipal;
use Database\Seeders\TemporarySeeders\AddDropoutPermission;
use Database\Seeders\TemporarySeeders\AddPendingUserApprovalsPermission;
use Database\Seeders\TemporarySeeders\AddSearchTuitionStatusPermission;
use Database\Seeders\TemporarySeeders\AddStudentStatisticsReportPermission;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            //            PermissionSeeder::class,
            //            RoleSeeders\SuperAdmin::class,
            //            RoleSeeders\FinancialManager::class,
            //            RoleSeeders\Interviewer::class,
            //            RoleSeeders\Parents::class,
            //            RoleSeeders\Student::class,
            //            RoleSeeders\AdmissionsOfficer::class,
            //            RoleSeeders\Principal::class,
            //            DatabaseImportSql::class,
            //            RevokeDocumentPermissionFromParentRole::class,
            //            DeleteParentsAndStudentsEmail::class,
            //            ChangeUsersMobileFormatToInternational::class,
            //            RemoveAllStudentsMobiles::class,
            //            ChangeParentsPassword::class,
            //            RemoveMobileThatUserNotGuardian::class,
            //            AddSearchTuitionStatusPermission::class,
            //            AddImpersonatePermission::class,
            //            AllTuitionsPermission::class,
//            RevokeSomePermissions::class,
//            AddStudentStatisticsReportPermission::class,
//            AddLevelsToStudentApplianceStatuses::class,
//            AddPendingUserApprovalsPermission::class,
//            AddDropoutPermission::class,
            AddConfirmInterviewForPrincipal::class,
        ]);
    }
}
