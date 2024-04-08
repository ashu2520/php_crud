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
$id_check = false;
$id ="";
// die("asdfgh");
if (isset($_GET['deleteid'])) {
    $id = cleandelete_input($_GET['deleteid']);
    // echo "ashm,nm,nn<br>" .$id;
    
    $column_name = cleandelete_input($_GET['column_name']);
	$sort_order = cleandelete_input($_GET['sort_order']);
	$curr_page = (int)cleandelete_input($_GET['page']);
    $id_check = ($id !== "") ? true : false;

    if($id_check){
        $sql = "delete from `users` where Id = $id";
        $result = mysqli_query($conn, $sql);
        header("location:client_dashboard.php?column_name=$column_name&sort_order=$sort_order&page=$curr_page");
        exit();
    }
    else{
        echo "<H1 style='color:red;'>INVALID</H1>";
        header("location:client_dashboard.php?column_name=$column_name&sort_order=$sort_order&page=$curr_page");
        die(mysqli_error($conn));
    }
}
else {
    header("location:client_dashboard.php?column_name=$column_name&sort_order=$sort_order&page=$curr_page");
}
?>