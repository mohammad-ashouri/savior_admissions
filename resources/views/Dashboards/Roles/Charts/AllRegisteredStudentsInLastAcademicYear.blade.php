@if($allRegisteredStudentsInLastAcademicYear)
    <div class="flex mr-4 w-full">
        @if(!empty($data))
            {!! $allRegisteredStudentsInLastAcademicYear->container() !!}
            <script src="{{ $allRegisteredStudentsInLastAcademicYear->cdn() }}"></script>
            {{ $allRegisteredStudentsInLastAcademicYear->script() }}
        @else
            There is no data chart to show about: Number of all registered students by academic year
        @endif
    </div>
@endif
