<?php
// Starting the session
include 'connect.php';
if (!isset($_SESSION["user_name"])){
    header("location:emp_login.php");
    exit();
}
if($_SESSION["User_role_id"] != 1 && $_SESSION["User_role_id"] != 2){
    header("location:emp_login.php");
    exit();
}
?>
<?php
function cleandelete_input($fields)
{
    $fields = trim($fields);
    $fields = stripslashes($fields);
    $fields = str_replace("'", "", $fields);
    $fields = htmlspecialchars($fields);
    return $fields;
}
// To check curr_page
if (isset($_GET["page"])) {
	$curr_page = (int)cleandelete_input($_GET["page"]);
	if (is_int($curr_page) || $curr_page < 1) {
		$curr_page = 1;
	}
} else {
	$curr_page = 1;
}

// To check Column Name
if (isset($_GET["column_name"])) {
	$column_name = cleandelete_input($_GET["column_name"]);
	if ($column_name !== "user_id" && $column_name !== "user_name" && $column_name !== "user_gender" && $column_name !== "user_mobile" && $column_name !== "user_email" && $column_name !== "user_type") {
		$column_name = "user_created_at";
	}
} else {
	$column_name = "user_created_at";
}

// To check Sort order
if (isset($_GET["sort_order"])) {
	$sort_order = cleandelete_input($_GET["sort_order"]);
	if ($sort_order !== "ASC" && $sort_order !== "DESC") {
		$sort_order = "DESC";
	}
} else {
	$sort_order = "DESC";
}
$id_check = false;
$id ="";
// die("asdfgh");
if (isset($_GET['deleteid'])) {
    $id = cleandelete_input($_GET['deleteid']);

    if (!empty($id) && is_numeric($id)) {
        // Proceed with the deletion
        $_SESSION['flash_message'] = "Deleted Sucessfully";
        $sql = "DELETE FROM `users` WHERE user_id = $id";
        $result = mysqli_query($conn, $sql);
    
        if ($result) {
            if (mysqli_affected_rows($conn) > 0) {
                header("location:client_dashboard.php?column_name=$column_name&sort_order=$sort_order&page=$curr_page");
                exit();
            } else {
                // No rows affected, ID not found
                $_SESSION['flash_message'] = "ID not found";
                header("location:client_dashboard.php?column_name=$column_name&sort_order=$sort_order&page=$curr_page");
                exit();
            }
        } else {
            // Error in query execution
            $_SESSION['flash_message'] = "Error: " . mysqli_error($conn);
            header("location:client_dashboard.php?column_name=$column_name&sort_order=$sort_order&page=$curr_page");
            exit();
        }
    } else {
        // Invalid or empty ID provided
        $_SESSION['flash_message'] = "Invalid ID provided";
        header("location:client_dashboard.php?column_name=$column_name&sort_order=$sort_order&page=$curr_page");
        exit();
    }
}
else {
    // When deleteid was not set....
    header("location:client_dashboard.php?column_name=$column_name&sort_order=$sort_order&page=$curr_page");
}
?>