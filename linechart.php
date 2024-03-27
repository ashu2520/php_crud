<?php 
$join_date= array();
$sql_1 = "SELECT DATE(Createdat) AS join_date, 
COUNT(*) AS employee_count
FROM login_credentials GROUP BY DATE(Createdat)";
$result_1 = mysqli_query($conn, $sql_1);
while ($row_1 = mysqli_fetch_assoc($result_1)) {
    $join_date[(string)$row_1['join_date']] = (int)$row_1['employee_count'];
}
 ?>

<div id="container3"></div>

<script>

Highcharts.chart('container3', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'User Details'
    },
    xAxis: {
        categories: [
                <?php foreach ($join_date as $date => $y): ?>
                    '<?php echo $date; ?>',
                <?php endforeach; ?>
            ]
    },
    yAxis: {
        title: {
            text: 'No. of Employees'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [{
        name: 'Employees',
        data: [
                <?php foreach ($join_date as $date => $y): ?>
                    <?php echo $y; ?>,
                <?php endforeach; ?>
            ]
    }]
});

</script>