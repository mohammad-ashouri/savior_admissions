@php
    $chart = $allRegisteredStudentsInLastAcademicYear['chart'];
    $labels = $allRegisteredStudentsInLastAcademicYear['labels'];
    $data = $allRegisteredStudentsInLastAcademicYear['data'];
    $colors = $allRegisteredStudentsInLastAcademicYear['colors'];
@endphp

<div class="flex mr-4 w-full">
    <div id="chart1"></div>
</div>

<script src="{{ $chart->cdn() }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var options = {
            chart: {
                type: 'bar',
                height: 250,
                width: 600,
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    distributed: true,
                }
            },
            colors: @json($colors),
            xaxis: {
                categories: @json($labels),
            },
            series: [{
                name: 'Registered Students',
                data: @json($data)
            }],
            title: {
                text: 'Total Number of Enrolled Students by Academic Year'
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart1"), options);
        chart.render();
    });
</script>
