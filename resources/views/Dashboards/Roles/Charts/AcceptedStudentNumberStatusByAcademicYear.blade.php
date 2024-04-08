@if($acceptedStudentNumberStatusByAcademicYear)
    <div class="flex mr-4 w-full">
        @if(!empty($data))
            {!! $acceptedStudentNumberStatusByAcademicYear->container() !!}
            <script src="{{ $acceptedStudentNumberStatusByAcademicYear->cdn() }}"></script>
            {{ $acceptedStudentNumberStatusByAcademicYear->script() }}
        @else
            There is no data chart to show about: The number of accepted students
        @endif
    </div>
@endif
