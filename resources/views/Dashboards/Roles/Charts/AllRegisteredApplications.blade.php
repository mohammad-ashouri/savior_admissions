@if($allRegisteredApplicationsInLastAcademicYear)
    <div class="flex mr-4 w-full">
        @if(!empty($data))
            {!! $allRegisteredApplicationsInLastAcademicYear->container() !!}
            <script src="{{ $allRegisteredApplicationsInLastAcademicYear->cdn() }}"></script>
            {{ $allRegisteredApplicationsInLastAcademicYear->script() }}
        @else
            There is no data chart to show about: Number of all registered applications by academic year
        @endif
    </div>
@endif
