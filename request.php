<?php
include "connect.php";
// Starting the session
// if (!isset($_SESSION["user_name"])) {
//   header("location:emp_login.php");
// }
?>
<?php
function clean_search_input($fields)
{
  $fields = trim($fields);
  $fields = stripslashes($fields);
  $fields = str_replace("'", "", $fields);
  $fields = htmlspecialchars($fields);
  return $fields;
}
$is_delete = false;
$num_per_page = 9;
$sql_2 = "Select * from contact_us";
$result_2 = mysqli_query($conn, $sql_2);
$total_records = mysqli_num_rows($result_2);
$total_pages = ceil($total_records / $num_per_page);

if (isset ($_GET["page"])) {
  $curr_page = clean_search_input($_GET["page"]);
  if (is_int($curr_page) || $curr_page < 1 || $curr_page > $total_pages) {
    $curr_page = 1;
  }
} else {
  $curr_page = 1;
}

if (isset ($_GET["column_name"])) {
  $column_name = clean_search_input($_GET["column_name"]);
  if ($column_name !== "Id" && $column_name !== "Name" && $column_name !== "Email" && $column_name !== "Number" && $column_name !== "Subject") {
    $column_name = "Id";
  }
} else {
  $column_name = "Id";
}

if (isset ($_GET["sort_order"])) {
  $sort_order = clean_search_input($_GET["sort_order"]);
  if ($sort_order !== "ASC" && $sort_order !== "DESC") {
    $sort_order = "ASC";
  }
} else {
  $sort_order = "ASC";
}

$curr_page = max(1, $curr_page);
$start_from = ($curr_page - 1) * $num_per_page;
// $start_form --> ye batata hai ki next page kaha se start hoga...  
$search = "";
if (isset ($_POST["search_box"])) {
  $search = clean_search_input($_POST["search_box"]);
  $sql = "SELECT Id, Name, Email, Number, Subject, Message FROM contact_us 
          WHERE Id LIKE '%$search%' 
          OR Name LIKE '%$search%' 
          OR Email LIKE '%$search%' 
          OR Number LIKE '%$search%' 
          OR Subject LIKE '%$search%' 
          ORDER BY $column_name $sort_order 
          LIMIT $start_from, $num_per_page";
  $result = mysqli_query($conn, $sql);
} else {
  $sql = "SELECT * FROM contact_us 
  ORDER BY $column_name $sort_order 
  LIMIT $start_from, $num_per_page";
  // die(mysqli_error($conn));
  $result = mysqli_query($conn, $sql);
}
?>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Request Data</title>
  <!-- Bootstrap -->
  <link href="css/client_dashboard.css" rel="stylesheet">
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
        <h1>List Users</h1>
        <div class="list-contet">
          <div class="form-left">
            <div class="form">

              <!-- Message popup -->
              <!-- <div class='popup' id='popup' style="height: 400px;">
                <div style="position: absolute; background-color: #214139; width: 396px; height: 70px; top: 0.5px; left: 0px;">
                    <h2 style="position: relative; margin-top: 12px; margin-left: 120px; color: white;">Message</h2>
                </div>
                <p style="position: absolute; margin:0 8px 0 0; top: 80px;" id="message-display">  -->
              <!-- // Ab humko yaha pr Message print karwana hai... -->
              <!-- </p>
                <div class='btn'> -->
              <!-- <a class="del_btn" id='delete_a' href='#" . $id . "'>Delete</a> -->
              <!-- <a style ="margin-left: 110px; margin-bottom: 0px;" class="del_btn2" onclick='closepopup()'>Close</a>
                </div>
              </div> -->

              <!-- Search Box -->
              <form id="myForm" role="form" action="" method="POST">
                <input id="search_box" type="text" class="search-box search-upper" name="search_box"
                  placeholder="Search..." value=<?php echo "$search" ?>>
                <input type="submit" class="submit-btn" value="Search" />
                <!-- <div class="add_more_user_button">
                  <a class="submit-btn add-user" href="client_create.php">Add More Users</a>
                </div> -->
              </form>
            </div>
          </div>


          <!-- TABLE -->
          <!-- TABLE -->
          <!-- TABLE -->

          <table width="100%" cellspacing="0">
            <tbody>
              <tr>
                <?php
                echo '
                  <th width="10px"><a href="request.php?column_name=Id&sort_order=' . ($sort_order == "DESC" ? "ASC" : "DESC") . '&page=' . $curr_page . '">Id</a></th>
                  <th width="10px"><a href="request.php?column_name=Name&sort_order=' . ($sort_order == "DESC" ? "ASC" : "DESC") . '&page=' . $curr_page . '">Name</a></th>
                  <th width="10px"><a href="request.php?column_name=Email&sort_order=' . ($sort_order == "DESC" ? "ASC" : "DESC") . '&page=' . $curr_page . '">Email</a></th>
                  <th width="10px"><a href="request.php?column_name=Number&sort_order=' . ($sort_order == "DESC" ? "ASC" : "DESC") . '&page=' . $curr_page . '">Mobile</a></th>
                  <th width="10px"><a href="request.php?column_name=Subject&sort_order=' . ($sort_order == "DESC" ? "ASC" : "DESC") . '&page=' . $curr_page . '">Subject</a></th> ';
                ?>
                <th width="50px" class="Message" style="color: #ff651b;">Message</th>
              </tr>

              <?php
              if ($result) {
                while ($row = mysqli_fetch_array($result)) {
                  // Fetch the first row
                  $id = $row['Id'];
                  $Name = $row['Name'];
                  $Email = $row['Email'];
                  $Mobile = $row['Number'];
                  $Subject = $row['Subject'];

                  $Message = $row['Message'];   // Isko humko show karwana hai popup ke jariye...
              
                  echo " 
                  <tr>
                         <td>" . $id . "</td>
                         <td>" . $Name . "</td>
                         <td>" . $Email . "</td>
                         <td>" . $Mobile . "</td>
                         <td>" . $Subject . "</td>
                         <td> 
                         <a id='message-popup' href='message_display.php?Id=" . $id . "'><img style = 'margin-left: 20px;' src='images/message-solid.png' width='30' height='25'></a>
                         </td>
                         </tr>";
                }
              } else {
                echo "No rows found.";
              }
              ?>
          </table>

          <!-- Pagination -->
          <!-- Pagination -->
          <!-- Pagination -->
          <div class="paginaton-div">
            <?php
            if ($curr_page - 1 > 0) {
              echo "<a class='act_btn' href='request.php?column_name=" . $column_name . "&sort_order=" . ($sort_order == "DESC" ? "DESC" : "ASC") . "&page=" . ($curr_page - 1) . "'>Prev</a>";

            } else {
              echo "<a style='background-color: #b4b4b4; color: white; text-decoration: none; cursor: not-allowed;'>Prev</a>";
            }
            $start_page = max(1, min($curr_page, $total_pages - 2));
            $end_page = min($total_pages, $start_page + 2);

            // Display page numbers
            for ($i = $start_page; $i <= $end_page; $i++) {
              if($curr_page == $i) {
                echo "<a  style='background-color: #ff651b; color: #fff; cursor: not-allowed; text-decoration: none;'>" . $i . "</a>";
                }else{
                  echo "<a class='$class' href='request.php?column_name=" . $column_name . "&sort_order=" . ($sort_order == "DESC" ? "DESC" : "ASC") . "&page=" . $i . "'>" . $i . "</a>";
                }
            }

            if ($curr_page + 1 <= $total_pages) {
              echo "<a class='act_btn' href = 'request.php?column_name=" . $column_name . "&sort_order=" . ($sort_order == "DESC" ? "DESC" : "ASC") . "&page=" . ($curr_page + 1) . "'>Next</a>";
            } else {
              echo "<a style='background-color: #b4b4b4; color: white; text-decoration: none; cursor: not-allowed;'>Next</a>";
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    const input = document.getElementById('search_box');
    const form = document.getElementById('myForm');
    window.onload = function () {
      input.focus();
      input.setSelectionRange(input.value.length, input.value.length);
    };

    input.addEventListener('input', function (event) {
      let timer;
      clearTimeout(timer);
      timer = setTimeout(() => {
        form.action = "request.php?search_box=" + event.target.value;
        form.submit();
      }, 500);
    });
  </script>
  <!-- <script src="js/client_update.js"></script> -->
</body>

</html>