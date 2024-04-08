
<?php
$department = array();
$sql = "SELECT user_type, count(*) AS num, (COUNT(*) * 100 /(SELECT COUNT(*) FROM `users`)) AS num_percent FROM `users` GROUP BY user_type ";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Stored in key-value pair
        // Aara yaha pr sirf num bhi provide karte ho tab bhi %age calculate ho jayega(automatically)...
        $department[(string) $row['user_type']] = round(floatval($row['num_percent']), 3);
    }
}
?>
<div id="container"></div>

<script>
    (function (H) {
        H.seriesTypes.pie.prototype.animate = function (init) {
            const series = this,
                chart = series.chart,
                points = series.points,
                {
                    animation
                } = series.options,
                {
                    startAngleRad
                } = series;

            function fanAnimate(point, startAngleRad) {
                const graphic = point.graphic,
                    args = point.shapeArgs;

                if (graphic && args) {

                    graphic
                        // Set inital animation values
                        .attr({
                            start: startAngleRad,
                            end: startAngleRad,
                            opacity: 1
                        })
                        // Animate to the final position
                        .animate({
                            start: args.start,
                            end: args.end
                        }, {
                            duration: animation.duration / points.length
                        }, function () {
                            // On complete, start animating the next point
                            if (points[point.index + 1]) {
                                fanAnimate(points[point.index + 1], args.end);
                            }
                            // On the last point, fade in the data labels, then
                            // apply the inner size
                            if (point.index === series.points.length - 1) {
                                series.dataLabelsGroup.animate({
                                    opacity: 1
                                },
                                    void 0,
                                    function () {
                                        points.forEach(point => {
                                            point.opacity = 1;
                                        });
                                        series.update({
                                            enableMouseTracking: true
                                        }, false);
                                        chart.update({
                                            plotOptions: {
                                                pie: {
                                                    innerSize: '40%',
                                                    borderRadius: 8
                                                }
                                            }
                                        });
                                    });
                            }
                        });
                }
            }

            if (init) {
                // Hide points on init
                points.forEach(point => {
                    point.opacity = 0;
                });
            } else {
                fanAnimate(points[0], startAngleRad);
            }
        };
    }(Highcharts));
    Highcharts.chart('container', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Number of Employees in Each Department',
            useHTML: true,
        style: {
            marginBottom: '120px' // Adjust the value to create desired space
        }
        },
        subtitle: {
            text: '       ',
            // align: 'left'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                borderWidth: 2,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b><br>{point.percentage}%',
                    distance: 20
                }
            }
        },
        series: [{
            // Disable mouse tracking on load, enable after custom animation
            enableMouseTracking: false,
            animation: {
                duration: 2000
            },
            colorByPoint: true,
            data: [<?php foreach ($department as $name => $y): ?>{
                    name: '<?PHP echo $name ?>',
                    y: <?php echo $y; ?>
                }, <?php endforeach; ?>]
        }]
    });
</script>