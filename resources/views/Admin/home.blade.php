@extends('layouts.adminapp')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="row">
        <div class="col-md-4 grid-margin stretch-card">
            <div class="card charts-home">
                <div class="card-body">
                    <h6 class="card-title" style="text-align: center;">Daily lead volume</h6>

                    <div class="container mt-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div style="position: relative; width: 100%; height: 300px;">
                                    <canvas id="leadChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const totalLeads = {{ $totalLeads }};
            const sold = {{ $soldLeads }};
            const unsold = {{ $unsoldLeads }};

            let animatedValue = 0;

            const chart = new Chart(document.getElementById('leadChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Sold', 'Unsold'],
                    datasets: [{
                        data: [0, 0], // 👈 start from zero for fill animation
                        backgroundColor: ['#28a745', '#dc3545'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    cutout: '60%',

                    animation: {
                        duration: 1500,
                        easing: 'easeOutQuart'
                    },

                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                },
                plugins: [{
                    id: 'centerText',
                    beforeDraw(chart) {
                        const { ctx, width, height } = chart;

                        ctx.save();
                        ctx.textAlign = "center";
                        ctx.textBaseline = "middle";

                        ctx.fillStyle = "#000";
                        ctx.font = "normal 14px sans-serif";
                        ctx.fillText("Total Lead", width / 2, height / 2 - 15);

                        ctx.font = "bold 24px sans-serif";
                        ctx.fillText(Math.floor(animatedValue), width / 2, height / 2 + 15);

                        ctx.restore();
                    }
                }]
            });

            // ✅ REAL FILL ANIMATION (SAFE VERSION)
            function animateChart() {

                let progress = 0;
                const duration = 1200;
                const start = performance.now();

                function step(time) {
                    const elapsed = time - start;
                    const percent = Math.min(elapsed / duration, 1);

                    // smooth values
                    const currentSold = Math.round(sold * percent);
                    const currentUnsold = Math.round(unsold * percent);

                    chart.data.datasets[0].data = [currentSold, currentUnsold];
                    chart.update();

                    animatedValue = Math.round(totalLeads * percent);

                    if (percent < 1) {
                        requestAnimationFrame(step);
                    }
                }

                requestAnimationFrame(step);
            }

            animateChart();

        });
    </script>

@endsection
