<?php
include "connect.php";
// Starting the session
if (!isset($_SESSION["user_name"])) {
  header("location:emp_login.php");
}
?>
<?php
$num = 0;
$male = 0;
$female = 0;
$sql = "SELECT  count(*) AS num,
SUM(CASE WHEN Gender = 'Male' THEN 1 ELSE 0 END) AS male,
SUM(CASE WHEN Gender = 'Female' THEN 1 ELSE 0 END) AS female
FROM `login_credentials`";
$result = mysqli_query($conn, $sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $num = $row['num'];
    $male = $row['male'];
    $female = $row['female'];
}
$sql = "SELECT * FROM login_credentials
WHERE DATE(Createdat) = CURDATE()";
$result = mysqli_query($conn, $sql);
$cur_num = mysqli_num_rows($result);
?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Graphical Dashboard</title>

    <!-- Bootstrap -->
    <link href="css/client_dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include "header.php"; ?>

    <div class="clear"></div>
    <div class="clear"></div>
    <div class="content">
        <div class="wrapper">
            <div class="bedcram">
            </div>
            <?php include "left_sidebar.php"; ?>
            <div class="right_side_content">
                <h1>Dashboard</h1>
                <div style = " height: 680px; overflow-y: scroll;" class="list-contet">
                    <div class="text_data">
                        <div class="text_box">
                            <div class="text_head">Total No. of Users</div>
                            <div class="text_body">
                                <?php echo $num; ?>
                            </div>
                        </div>
                        <div class="text_box">
                            <div class="text_head">Total No. of Males</div>
                            <div class="text_body">
                                <?php echo $male; ?>
                            </div>
                        </div>
                        <div class="text_box">
                            <div class="text_head">Total No. of Females</div>
                            <div class="text_body">
                                <?php echo $female; ?>
                            </div>
                        </div>
                        <div class="text_box">
                            <div class="text_head">No.of Users Added Today</div>
                            <div class="text_body">
                                <?php echo $cur_num; ?>
                            </div>
                        </div>
                    </div>
                    <div class="graphs_data">

                        <script src="https://code.highcharts.com/highcharts.js"></script>
                        <script src="https://code.highcharts.com/modules/exporting.js"></script>
                        <script src="https://code.highcharts.com/modules/export-data.js"></script>
                        <script src="https://code.highcharts.com/modules/accessibility.js"></script>

                        <figure class="highcharts-figure">
                            <?php include "pie_chart.php"; ?>
                            <div class="clear1"></div>
                            <?php include "stacked_bar.php"; ?>
                            <div class="clear1"></div>
                            <?php include "linechart.php"; ?>
                        </figure>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/graphical_dashboard.js"></script>
</body>


</html>