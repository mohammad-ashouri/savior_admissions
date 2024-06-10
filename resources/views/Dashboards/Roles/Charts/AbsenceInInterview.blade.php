@php
    $chart = $absenceInInterview['chart'];
    $labels = $absenceInInterview['labels'];
    $data = $absenceInInterview['data'];
    $colors = $absenceInInterview['colors'];
@endphp

<div class="flex mr-4 w-full">
    <div id="chart7"></div>
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
                name: 'Absence In Interview',
                data: @json($data)
            }],
            title: {
                text: 'Number of all absence in interview by academic year'
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart7"), options);
        chart.render();
    });
</script>
