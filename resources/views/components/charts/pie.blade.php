<div>
    <style>
        #pieChart-{{ $data['chart_label'] }} {
            height: {{ $height ?? 0 }};
        }
    </style>
    <div style="width: 100%; margin: auto;">
        <canvas id="pieChart-{{ $data['chart_label'] }}"></canvas>
    </div>

    <script>
        (function () {
            // Append '4d' to the colors (alpha channel), except for the hovered index
            function handleHover(evt, item, legend) {
                legend.chart.data.datasets[0].backgroundColor.forEach((color, index, colors) => {
                    if (index === item.index) {
                        colors[index] = color.replace(/rgba\((\d+), (\d+), (\d+), [^)]+\)/, 'rgba($1, $2, $3, 0.8)');
                    } else {
                        colors[index] = color.replace(/rgba\((\d+), (\d+), (\d+), [^)]+\)/, 'rgba($1, $2, $3, 0.2)');
                    }
                });
                legend.chart.update();
            }

            // Removes the alpha channel from background colors
            function handleLeave(evt, item, legend) {
                legend.chart.data.datasets[0].backgroundColor.forEach((color, index, colors) => {
                    colors[index] = color.replace(/rgba\((\d+), (\d+), (\d+), [^)]+\)/, 'rgba($1, $2, $3, 0.2)');
                });
                legend.chart.update();
            }

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

            const ctx = document.getElementById('pieChart-{{ $data['chart_label'] }}').getContext('2d');
            const myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: @json($data['labels']),
                    datasets: [{
                        data: @json($data['data']),
                        backgroundColor: backgroundColors,
                        borderColor: borderColors,
                        borderWidth: 1,
                    }],
                },
                options: {
                    animation: {
                        duration: 1500,
                        easing: 'easeOutElastic',
                        animateRotate: true,
                        animateScale: true,
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    rotation: Math.PI / 4,
                    plugins: {
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
                        title: {
                            display: true,
                            text: @json($data['chart_label']),
                            font: {
                                size: 16,
                            },
                        },
                        legend: {
                            labels: {
                                font: {
                                    size: 14,
                                    weight: 'bold',
                                },
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle',
                            },
                            position: 'right',
                            onHover: handleHover,
                            onLeave: handleLeave
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
                            },
                        },
                    },
                    layout: {
                        padding: 10,
                    },
                },
            });

            document.getElementById('pieChart-{{ $data['chart_label'] }}').addEventListener('click', function (event) {
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
