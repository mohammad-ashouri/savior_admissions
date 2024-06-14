@php
    $chart = $allStudentsPendingForUploadDocument['chart'];
    $labels = $allStudentsPendingForUploadDocument['labels'];
    $data = $allStudentsPendingForUploadDocument['data'];
    $colors = $allStudentsPendingForUploadDocument['colors'];
@endphp

<div class="flex mr-4 w-full">
    <div id="chart4"></div>
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
                name: 'Approved Students',
                data: @json($data)
            }],
            title: {
                text: 'Number of All Students Waiting for Document Upload by Academic Year'
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart4"), options);
        chart.render();
    });
</script>
