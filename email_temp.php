<?php
include "connect.php";
// Starting the session
if (!isset($_SESSION["user_name"])) {
    header("location:emp_login.php");
}
if ($_SESSION["User_role_id"] != 1) {
    header("location:emp_login.php");
    exit();
}
?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email Templates</title>

    <!-- Bootstrap -->
    <link href="css/client_dashboard.css" rel="stylesheet">
    <style>
        table {
            width: 100%;
        }

        tr,
        th,
        td {
            height: 40px;
        }

        th {
            font-size: 14px;
        }
    </style>
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
                <h1>Email Template</h1>
                <div class="list-contet">

                    <table>
                        <tr>
                            <th>Template Name</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Actions</th>
                        </tr>
                        <?php
                        $sql = "Select * from `email_templates`";
                        $result = mysqli_query($conn, $sql);
                        if ($result) {
                            while ($row = mysqli_fetch_array($result)) {
                                $template_name = $row['temp_title'];
                                $created_date = $row['temp_created_at'];
                                $updated_date = $row['temp_updated_at'];
                                $temp_slug = $row['temp_slug'];
                                echo " <tr>
                        <td>" . $template_name . "</td>
                        <td>" . $created_date . "</td>
                        <td>" . $updated_date . "</td>
                        <td> 
                        <a href='edit_templates.php?temp_slug=" . $temp_slug . "' id='update' style='margin-right:10px'><img src='images/edit-icon.png'></a> 
                        </td> 
                        </tr>";
                                // <a href='#'><img src='images/cross.png'></a>
                            }
                        }
                        ?>
                    </table>
                        <?php
                        if (isset($_SESSION['flash_message'])) {
                            $message = $_SESSION['flash_message'];
                            unset($_SESSION['flash_message']);
                            echo "<span id='flash-message' class='template-flash-message'> $message</span>";
                        }
                        ?>
                </div>
            </div>
        </div>
    </div>

    <!-- <script src="js/client_update.js"></script> -->
    <script>
        // For Flash Messages
        setTimeout(function () {
            document.getElementById("flash-message").style.display = 'none';
        }, 3000);
    </script>
    </script>
</body>

</html>