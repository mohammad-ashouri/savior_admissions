@include('GeneralPages.errors.session.error')
@include('GeneralPages.errors.session.success')

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
    <div class="lg:col-span-2 col-span-1">
        <div class="bg-white dark:bg-gray-800 dark:text-white p-4 md:p-8 rounded-lg mb-4">
            <div class="mb-6">
                <h1 class="text-xl font-semibold text-black dark:text-white mb-4">Users Status</h1>
            </div>
            <div class="overflow-x-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 w-full">
                <x-charts.pie :data="$userRolesChart" />
            </div>
        </div>
    </div>
</div>

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

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
    <div class="lg:col-span-2 col-span-1">
        <div class="bg-white dark:bg-gray-800 dark:text-white p-4 md:p-8 rounded-lg mb-4">
            <div class="mb-6">
                <h1 class="text-xl font-semibold text-black dark:text-white mb-4">Applications Status</h1>
            </div>
            <div class="overflow-x-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 w-full">
                <x-charts.pie :data="$reservedApplicationsByAcademicYear" />
                <x-charts.bar :height="'300px'" :data="$levels" />
            </div>
        </div>
    </div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
    <div class="lg:col-span-2 col-span-1">
        <div class="bg-white dark:bg-gray-800 dark:text-white p-4 md:p-8 rounded-lg mb-4">
            <div class="mb-6">
                <h1 class="text-xl font-semibold text-black dark:text-white mb-4">Interviews Status</h1>
            </div>
            <div class="overflow-x-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 w-full">
                <x-charts.pie :data="$admittedInterviews" />
                <x-charts.pie :data="$rejectedInterviews" />
                <x-charts.pie :data="$absenceInInterview" />
                <x-charts.pie :data="$interviewTypes" />
            </div>
        </div>
    </div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
    <div class="lg:col-span-2 col-span-1">
        <div class="bg-white dark:bg-gray-800 dark:text-white p-4 md:p-8 rounded-lg mb-4">
            <div class="mb-6">
                <h1 class="text-xl font-semibold text-black dark:text-white mb-4">Tuition Status</h1>
            </div>
            <div class="overflow-x-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 w-full">
                <x-charts.pie :data="$tuitionPaidAcademicYear" />
                <x-charts.pie :data="$tuitionPaidPaymentType" />
            </div>
        </div>
    </div>
</div>
