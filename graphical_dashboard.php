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
SUM(CASE WHEN user_gender = 'Male' THEN 1 ELSE 0 END) AS male,
SUM(CASE WHEN user_gender = 'Female' THEN 1 ELSE 0 END) AS female
FROM `users` WHERE user_role_id != 1";
$result = mysqli_query($conn, $sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $num = $row['num'];
    $male = $row['male'];
    $female = $row['female'];
}
$sql = "SELECT COUNT(*) AS cnt FROM `users`
WHERE DATE(user_created_at) = CURDATE() AND user_role_id !=1";
$result = mysqli_query($conn, $sql);
// echo $result;
// print($result);
// print_r($result);
// die();
$row = mysqli_fetch_assoc($result);
// print_r($row);
// die();
$cur_num = (int)$row['cnt'];
?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Graphical Dashboard</title>
    <link rel="icon" type="image/x-icon" href="images/arcs_logo.png">

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