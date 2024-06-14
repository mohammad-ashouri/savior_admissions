@php
    $chart = $admittedInterviews['chart'];
    $labels = $admittedInterviews['labels'];
    $data = $admittedInterviews['data'];
    $colors = $admittedInterviews['colors'];
@endphp

<div class="flex mr-4 w-full">
    <div id="chart6"></div>
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
                name: 'Admitted Interviews',
                data: @json($data)
            }],
            title: {
                text: 'Total Accepted Interviews by Academic Year'
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart6"), options);
        chart.render();
    });
</script>
