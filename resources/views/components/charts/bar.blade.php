<div>
    <style>
        #barChart-{{ $data['chart_label'] }} {
            height: {{ $height ?? 0 }};
        }
    </style>
    <div style="width: 100%; margin: auto;">
        <canvas id="barChart-{{ $data['chart_label'] }}"></canvas>
    </div>

    <script>
        (function () {
            function generateRandomColorWithOpacity(opacity) {
                const r = Math.floor(Math.random() * 255);
                const g = Math.floor(Math.random() * 255);
                const b = Math.floor(Math.random() * 255);
                return `rgba(${r}, ${g}, ${b}, ${opacity})`;
            }

            function isColorTooClose(color1, color2, threshold = 50) {
                const rgb1 = color1.match(/\d+/g).map(Number);
                const rgb2 = color2.match(/\d+/g).map(Number);
                const distance = Math.sqrt(
                    Math.pow(rgb1[0] - rgb2[0], 2) +
                    Math.pow(rgb1[1] - rgb2[1], 2) +
                    Math.pow(rgb1[2] - rgb2[2], 2)
                );
                return distance < threshold;
            }

            function generateDistinctColorsWithOpacity(count, opacity) {
                const colors = [];
                while (colors.length < count) {
                    const newColor = generateRandomColorWithOpacity(opacity);
                    if (!colors.some(existingColor => isColorTooClose(newColor, existingColor))) {
                        colors.push(newColor);
                    }
                }
                return colors;
            }

            const backgroundColors = generateDistinctColorsWithOpacity(@json($data['labels']).length, 0.2);
            const borderColors = backgroundColors.map(color => color.replace(/0.2\)$/, "1)"));

            const unit = @json($data['unit']);

            const ctx = document.getElementById('barChart-{{ $data['chart_label'] }}').getContext('2d');
            const myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($data['labels']),
                    datasets: [{
                        data: @json($data['data']),
                        backgroundColor: backgroundColors,
                        borderColor: borderColors,
                        borderWidth: 1,
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    animation: {
                        duration: 1500,
                        easing: 'easeOutElastic'
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        title: {
                            display: true,
                            text: @json($data['chart_label']),
                            font: {
                                size: 16,
                            },
                        },
                        tooltip: {
                            enabled: true,
                            callbacks: {
                                label: function (context) {
                                    let label = context.label || '';
                                    let value = context.raw || 0;
                                    let total = context.dataset.data.reduce((sum, val) => sum + val, 0);
                                    if (unit === 'IRR') {
                                        value = parseFloat(value).toLocaleString('en-US');
                                    }
                                    const percentage = ((value / total) * 100).toFixed(2);
                                    return `${label}: ${value} ${unit} (${percentage}%)`;
                                },
                            }
                        },
                        zoom: {
                            zoom: {
                                wheel: {
                                    enabled: true,
                                },
                                mode: 'xy',
                            },
                            pan: {
                                enabled: true,
                                mode: 'xy',
                            },
                        },
                    }
                }
            });
            document.getElementById('barChart-{{ $data['chart_label'] }}').addEventListener('click', function (event) {
                const points = myChart.getElementsAtEventForMode(event, 'nearest', { intersect: true }, true);
                if (points.length) {
                    const datasetIndex = points[0].datasetIndex;
                    const index = points[0].index;
                    const label = myChart.data.labels[index];
                    const value = myChart.data.datasets[datasetIndex].data[index];
                    alert(`Label: ${label}\nValue: ${value}`);
                }
            });
        })();
    </script>
</div>
