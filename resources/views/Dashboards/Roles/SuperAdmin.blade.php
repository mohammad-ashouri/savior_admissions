@include('GeneralPages.errors.session.error')
@include('GeneralPages.errors.session.success')

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
    <div class="lg:col-span-2 col-span-1">
        <div class="bg-white dark:bg-gray-800 dark:text-white p-4 md:p-8 rounded-lg mb-4">
            <div class="mb-6">
                <h1 class="text-xl font-semibold text-black dark:text-white mb-4">Students Status</h1>
            </div>
            <div class="overflow-x-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 w-full">
                <x-charts.pie :data="$allRegisteredStudentsInLastAcademicYear" />
                <x-charts.pie :data="$acceptedStudentNumberStatusByAcademicYear" />
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-2 gap-4 mb-4">
    <div class="lg:col-span-2 col-span-3 ">
        <div class="bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
            <div class=" mb-6 md:grid-cols-2">
                <div class="relative overflow-x-auto">
                    <div class="grid grid-cols-1 gap-4 mb-4">
                        <h1 class="text-xl font-semibold text-black dark:text-white ">Applications Status </h1>
                    </div>
                </div>
                <div class="flex w-full overflow-x-auto">
{{--                    @include('Dashboards.Roles.Charts.AllReservedApplications')--}}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="grid grid-cols-2 gap-4 mb-4">
    <div class="lg:col-span-2 col-span-3 ">
        <div class="bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
            <div class=" mb-6 md:grid-cols-2">
                <div class="relative overflow-x-auto">
                    <div class="grid grid-cols-1 gap-4 mb-4">
                        <h1 class="text-xl font-semibold text-black dark:text-white ">Interviews Status </h1>
                    </div>
                </div>
                <div class="flex w-full overflow-x-auto">
{{--                    @include('Dashboards.Roles.Charts.AdmittedInterviews')--}}
{{--                    @include('Dashboards.Roles.Charts.RejectedInterviews')--}}
                </div>
                <div class="flex w-full mt-6 overflow-x-auto">
{{--                    @include('Dashboards.Roles.Charts.AbsenceInInterview')--}}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="grid grid-cols-2 gap-4 mb-4">
    <div class="lg:col-span-2 col-span-3 ">
        <div class="bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
            <div class=" mb-6 md:grid-cols-2">
                <div class="relative overflow-x-auto">
                    <div class="grid grid-cols-1 gap-4 mb-4">
                        <h1 class="text-xl font-semibold text-black dark:text-white ">Upload Documents Status </h1>
                    </div>
                </div>
                <div class="flex w-full">
{{--                    @include('Dashboards.Roles.Charts.AllStudentsPendingForUploadDocument')--}}
                </div>
            </div>
        </div>
    </div>
</div>
