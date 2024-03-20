<?php
$data = array();
$sql = "SELECT User_Type,
        SUM(CASE WHEN Gender = 'Male' THEN 1 ELSE 0 END) AS male,
        SUM(CASE WHEN Gender = 'Female' THEN 1 ELSE 0 END) AS female
        FROM `users_list` GROUP BY User_Type";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = array((string) $row['User_Type'], (int) $row['male'], (int) $row['female']);
    }
}
?>

<div id="stacked_bar"></div>

<script>
    Highcharts.chart('stacked_bar', {
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Diversity Ratio'
        },
        xAxis: {
            categories: [
                <?php foreach ($data as $department): ?>
                    '<?php echo $department[0]; ?>',
                <?php endforeach; ?>
            ]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Number of Employees'
            }
        },
        legend: {
            reversed: true
        },
        plotOptions: {
            series: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true
                }
            }
        },
        series: [{
            name: 'Males',
            data: [
                <?php foreach ($data as $males): ?>
                    <?php echo $males[1]; ?>,
                <?php endforeach; ?>
            ]
        }, {
            name: 'Females',
            data: [
                <?php foreach ($data as $females): ?>
                    <?php echo $females[2]; ?>,
                <?php endforeach; ?>
            ]
        }]
    });
</script>
