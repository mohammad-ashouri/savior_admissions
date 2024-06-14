@php
    $chart = $rejectedInterviews['chart'];
    $labels = $rejectedInterviews['labels'];
    $data = $rejectedInterviews['data'];
    $colors = $rejectedInterviews['colors'];
@endphp

<div class="flex mr-4 w-full">
    <div id="chart5"></div>
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
                name: 'Rejected Interviews',
                data: @json($data)
            }],
            title: {
                text: 'Total Number of Rejected Interviews by Academic Year'
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart5"), options);
        chart.render();
    });
</script>
