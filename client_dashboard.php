<?php
include "connect.php";
// Starting the session
if (!isset($_SESSION["user_name"])) {
  header("location:emp_login.php");
}
$user_role_id = $_SESSION["User_role_id"];
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

$num_per_page = $_SESSION['num_per_page'];
$format_date = $_SESSION["date_format"];
if ($format_date == "YYYY-MM-DD") {
  $format_date = '%Y-%m-%d';
} else {
  $format_date = '%d-%m-%Y';
}

// To find number of rows
$sql_2 = "Select COUNT(user_id) as cnt from `users` WHERE user_role_id != 1 AND user_role_id != 2" ;
$result_2 = mysqli_query($conn, $sql_2);
$row = mysqli_fetch_array($result_2);

$total_records = (int) $row['cnt'] +1;
$total_pages = ceil($total_records / $num_per_page);

// To check curr_page
if (isset($_GET["page"])) {
  $curr_page = clean_search_input($_GET["page"]);
  if (is_int($curr_page) || $curr_page < 1 || $curr_page > $total_pages) {
    $curr_page = 1;
  }
} else {
  $curr_page = 1;
}

// To check Column Name
if (isset($_GET["column_name"])) {
  $column_name = clean_search_input($_GET["column_name"]);
  if ($column_name !== "user_id" && $column_name !== "user_name" && $column_name !== "user_gender" && $column_name !== "user_mobile" && $column_name !== "user_email" && $column_name !== "user_type") {
    $column_name = "user_created_at";
  }
} else {
  $column_name = "user_created_at";
}

// To check Sort order
if (isset($_GET["sort_order"])) {
  $sort_order = clean_search_input($_GET["sort_order"]);
  if ($sort_order !== "ASC" && $sort_order !== "DESC") {
    $sort_order = "DESC";
  }
} else {
  $sort_order = "DESC";
}

// To find the start index
$curr_page = max(1, $curr_page);
$start_from = ($curr_page - 1) * $num_per_page;
// $start_form --> ye batata hai ki next page kaha se start hoga...  
$search = "";

// Searching Query
if (isset($_GET["search_box"]) && $_GET["search_box"] !== "") {
  $search = stripslashes($_GET["search_box"]);
  $search = str_replace("'", '', $search);
  $search = htmlspecialchars($search, ENT_QUOTES, 'UTF-8');
 

  $sql = "SELECT *,  DATE_FORMAT(user_created_at, '" . $format_date . "') AS Createdat  FROM users 
          WHERE user_id LIKE '%$search%' 
          OR user_name LIKE '%$search%' 
          OR user_mobile LIKE '%$search%' 
          OR user_email LIKE '%$search%' 
          OR user_gender LIKE '%$search%' 
          OR user_type LIKE '%$search%' 
          ORDER BY $column_name $sort_order 
          LIMIT $start_from, $num_per_page";
  $result = mysqli_query($conn, $sql);

// Count the total number of records matching the search criteria
  $count_sql = "SELECT COUNT(*) AS total_count FROM users 
              WHERE user_id LIKE '%$search%' 
              OR user_name LIKE '%$search%' 
              OR user_mobile LIKE '%$search%' 
              OR user_email LIKE '%$search%' 
              OR user_gender LIKE '%$search%' 
              OR user_type LIKE '%$search%'";

$count_result = mysqli_query($conn, $count_sql);
$count_row = mysqli_fetch_assoc($count_result);
$total_records = $count_row['total_count'];

  $total_pages = ceil($total_records / $num_per_page);
} else {
  if ($user_role_id == 1) {
    $sql = "SELECT *, DATE_FORMAT(user_created_at, '" . $format_date . "') AS Createdat FROM `users` 
    WHERE user_role_id != 1 
    ORDER BY $column_name $sort_order 
    LIMIT $start_from, $num_per_page";
  } elseif ($user_role_id == 2) {
    $user_id = $_SESSION['Id'];
    $sql = "SELECT *, DATE_FORMAT(user_created_at, '" . $format_date . "') AS Createdat FROM `users` 
    WHERE 
    (user_role_id != 1) AND 
    (
        (user_role_id != 2) OR 
        (user_role_id = 2 AND $user_id = 2)
    )
    ORDER BY $column_name $sort_order 
    LIMIT $start_from, $num_per_page";

  } else {
    $sql = "SELECT *, DATE_FORMAT(user_created_at, '" . $format_date . "') AS Createdat FROM `users` 
    WHERE user_role_id != 1 AND user_role_id !=2
    ORDER BY $column_name $sort_order 
    LIMIT $start_from, $num_per_page";
  }
  $result = mysqli_query($conn, $sql);

}
?>

<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Employee Data</title>
  <link rel="icon" type="image/x-icon" href="images/arcs_logo.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link href="css/client_dashboard.css" rel="stylesheet" />

  <style>
    .active-page {
      text-decoration: none;
    }
  </style>

</head>

<body>
  <?php include "header.php"; ?>

  <div class="clear"></div>
  <div class="clear"></div>

  <?php
  if (isset($_SESSION['flash_message'])) {
    $message = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
    echo "<span id='flash-message' class='flash-message'> $message</span>";
  }
  ?>

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

              <!-- Delete popup -->
              <div class='popup' id='popup'>
                <h2>Warning</h2>
                <p>Data will be deleted permanently.</p>
                <div class='btn'>
                  <a class="del_btn" id='delete_a' href='#" . $id . "'>Delete</a>
                  <a style="background-color: #50704f;" class="del_btn" onclick='closepopup()'>Cancel</a>
                </div>
              </div>

              <!-- Search Box -->
              <form id="myForm" role="form" action="" method="GET">
                <input id="search_box" type="text" class="search-box search-upper" name="search_box"
                  placeholder="Search..." value="<?php echo $search; ?>">
                <input type="submit" class="submit-btn" value="Search" />
                <?php if ($user_role_id == 1 || $user_role_id == 2) { ?>
                  <div class="add_more_user_button">
                    <a class="submit-btn add-user" href="client_create.php">Add More Users</a>
                  </div>
                <?php } ?>
              </form>
            </div>
          </div>

          <!-- TABLE -->
          <!-- TABLE -->
          <!-- TABLE -->

          <table cellspacing="0">

            <tr>
              <?php

              // Id
              echo '<th width="10px"><a href="client_dashboard.php?column_name=user_id&sort_order=' . ($column_name == "user_id" && $sort_order == "ASC" ? "DESC" : "ASC") . '&page=' . $curr_page . '">S.no';
              if ($column_name == "user_id") {
                if ($sort_order == 'DESC') {
                  echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-down" aria-hidden="true"></i>';
                } else {
                  echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-up" aria-hidden="true"></i>';
                }
              }
              echo '</a></th>';

              // Name
              echo '<th width="98px"><a href="client_dashboard.php?column_name=user_name&sort_order=' . ($column_name == "user_name" && $sort_order == "ASC" ? "DESC" : "ASC") . '&page=' . $curr_page . '">Name ';
              if ($column_name == 'user_name') {
                if ($sort_order == 'DESC') {
                  echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-down" aria-hidden="true"></i>';
                } else {
                  echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-up" aria-hidden="true"></i>';
                }
              }
              echo '</a></th>';

              // Email
              echo '<th width="113px"><a href="client_dashboard.php?column_name=user_email&sort_order=' . ($column_name == "user_email" && $sort_order == "ASC" ? "DESC" : "ASC") . '&page=' . $curr_page . '">Email ';
              if ($column_name == 'user_email') {
                if ($sort_order == 'DESC') {
                  echo '<i class="fa fa-arrow-down" aria-hidden="true"></i>';
                } else {
                  echo '<i class="fa fa-arrow-up" aria-hidden="true"></i>';
                }
              }
              echo '</a></th>';

              // Mobile
              echo '<th width="70px"><a href="client_dashboard.php?column_name=user_mobile&sort_order=' . ($column_name == "user_mobile" && $sort_order == "ASC" ? "DESC" : "ASC") . '&page=' . $curr_page . '">Mobile ';
              if ($column_name == 'user_mobile') {
                if ($sort_order == 'DESC') {
                  echo '<i class="fa fa-arrow-down" aria-hidden="true"></i>';
                } else {
                  echo '<i class="fa fa-arrow-up" aria-hidden="true"></i>';
                }
              }
              echo '</a></th>';

              // Gender
              echo '<th width="100px"><a href="client_dashboard.php?column_name=user_gender&sort_order=' . ($column_name == "user_gender" && $sort_order == "ASC" ? "DESC" : "ASC") . '&page=' . $curr_page . '">Gender ';
              if ($column_name == 'user_gender') {
                if ($sort_order == 'DESC') {
                  echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-down" aria-hidden="true"></i>';
                } else {
                  echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-up" aria-hidden="true"></i>';
                }
              }
              echo '</a></th>';

              // User_type
              echo '<th width="97px"><a href="client_dashboard.php?column_name=user_type&sort_order=' . ($column_name == "user_type" && $sort_order == "ASC" ? "DESC" : "ASC") . '&page=' . $curr_page . '">User Type ';
              if ($column_name == 'user_type') {
                if ($sort_order == 'DESC') {
                  echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-down" aria-hidden="true"></i>';
                } else {
                  echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-up" aria-hidden="true"></i>';
                }
              }
              echo '</a></th>';

              // Createdat
              echo '<th width="135px"><a href="client_dashboard.php?column_name=user_created_at&sort_order=' . ($column_name == "user_created_at" && $sort_order == "ASC" ? "DESC" : "ASC") . '&page=' . $curr_page . '">Created At ';
              if ($column_name == 'user_created_at') {
                if ($sort_order == 'DESC') {
                  echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-down" aria-hidden="true"></i>';
                } else {
                  echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-up" aria-hidden="true"></i>';
                }
              }

              if ($user_role_id == 1 || $user_role_id == 2) {
                echo '<th width="50px" class="Action" style="color: #ff651b;">Action</th>';
              }
              ?>
            </tr>
            <?php
            if ($result) {
              while ($row = mysqli_fetch_array($result)) {
                // Fetch the first row
                if ($row['user_role_id'] >= 3 || ($row['user_role_id'] == 2 && $row['user_id'] == $_SESSION['Id']) || ($user_role_id == 1 && $row['user_role_id'] != 1)) {
                  $id = $row['user_id'];
                  $Name = $row['user_name'];
                  $Email = $row['user_email'];
                  $Mobile = $row['user_mobile'];
                  $Gender = $row['user_gender'];
                  $User_type = $row['user_type'];
                  $Created_at = $row['Createdat'];
                  echo "<tr>
                         <td>" . $id . "</td>
                         <td>" . $Name . "</td>
                         <td>" . $Email . "</td>
                         <td>" . $Mobile . "</td>
                         <td>" . $Gender . "</td>
                         <td>" . $User_type . "</td>
                         <td>" . $Created_at . "</td>";

                  if ($user_role_id == 1 || $user_role_id == 2) {

                    echo "<td> 
                         <a href='client_update.php?updateid=" . $id . "&column_name=" . $column_name . "&sort_order=" . $sort_order . "&page=" . $curr_page . "' id='update' style='margin-right:10px'><img src='images/edit-icon.png' onclick='myfunc()'></a>
                         <a id='delete_a' onclick='openpopup($id, \"$column_name\", \"$sort_order\", $curr_page)'><img src='images/cross.png'></a>
                         </td> 
                         </tr>";
                  }
                }
              }
            } else
              echo "No rows found.";
            ?>
          </table>

          <!-- Pagination -->
          <!-- Pagination -->
          <!-- Pagination -->

          <div class="paginaton-div">
            <?php
            // echo $total_records;
            if ($curr_page > 1) {
              echo "<a class='act_btn' href='client_dashboard.php?column_name=" . $column_name . "&sort_order=" . ($sort_order == "DESC" ? "DESC" : "ASC") . "&page=1&search_box=".$search."'>First</a>";
            } else {
              echo "<a style='background-color: #b4b4b4; color: white; text-decoration: none; cursor: not-allowed;' class='disabled-btn'>First</a>";
            }
            if ($curr_page - 1 > 0) {
              echo "<a class='act_btn' href='client_dashboard.php?column_name=" . $column_name . "&sort_order=" . ($sort_order == "DESC" ? "DESC" : "ASC") . "&page=" . ($curr_page - 1) . " &search_box=".$search."'>Prev</a>";

            } else {
              echo "<a style='background-color: #b4b4b4; color: white; text-decoration: none; cursor: not-allowed;'>Prev</a>";
            }
            $start_page = max(1, min($curr_page, $total_pages - 2));
            $end_page = min($total_pages, $start_page + 2);

            // Display page numbers
            for ($i = $start_page; $i <= $end_page; $i++) {
              if ($curr_page == $i) {
                echo "<a  style='background-color: #ff651b; color: #fff; cursor: not-allowed; text-decoration: none;'>" . $i . "</a>";
              } else {
                echo "<a href = 'client_dashboard.php?column_name=" . $column_name . "&sort_order=" . ($sort_order == "DESC" ? "DESC" : "ASC") . "&page=" . $i . "&search_box=".$search."'>" . $i . "</a>";
              }
              // ? --> query parameters, multiple page bhej sakte hai...
            }
            if ($curr_page + 1 <= $total_pages) {
              echo "<a class='act_btn' href = 'client_dashboard.php?column_name=" . $column_name . "&sort_order=" . ($sort_order == "DESC" ? "DESC" : "ASC") . "&page=" . ($curr_page + 1) . "&search_box=".$search."'>Next</a>";
            } else {
              echo "<a style='background-color: #b4b4b4; color: white; text-decoration: none; cursor: not-allowed;'>Next</a>";
            }
            if ($curr_page < $total_pages) {
              echo "<a class='act_btn' href='client_dashboard.php?column_name=" . $column_name . "&sort_order=" . ($sort_order == "DESC" ? "DESC" : "ASC") . "&page=" . $total_pages . "&search_box=".$search."'>Last</a>";
            } else {
              echo "<a style='background-color: #b4b4b4; color: white; text-decoration: none; cursor: not-allowed;' class='disabled-btn'>Last</a>";
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    let popup = document.getElementById("popup");

    function openpopup(id, column_name, sort_order, page) {
      console.log(id);
      popup.classList.add("open-popup");
      var myLink = document.getElementById("delete_a");
      url = "client_data_delete.php?deleteid=" + id + "&column_name=" + column_name + "&sort_order=" + sort_order + "&page=" + page;
      myLink.setAttribute("href", url);
    }

    function closepopup() {
      popup.classList.remove("open-popup");
    }

    // For Search box
    const input = document.getElementById('search_box');
    const form = document.getElementById('myForm');
    window.onload = function () {
      // window.onload = function() --> jaise hi window(page) load hoga waise hi ye function call hoga... 
      input.focus();
      // jaise hi page load hoga cursor(focus) input id wale element main chala jayega...
      input.setSelectionRange(input.value.length, input.value.length);
      // setSelectionRange(start, end) --> ye kya karta hai ye basically select kar ke rakhta hai...
      // input.value.lenght --> last element ke baad isko set kar dega. Taki humara cursor humesha end main hi aye...
    };

    input.addEventListener('input', function (event) {
      // input(search_box) ke ander koi bhi input denge to input event trigger ho jayega... 

      let timer;
      clearTimeout(timer);
      timer = setTimeout(() => {
        // Hum HTML ke kisi bhi element pr event uske id ke through hi laga sakte hai...
        form.action = "client_dashboard.php?search_box=" + event.target.value;  // iss query humein curr_page, bhi bhejna hoga
        // form.action --> form id wale element ka bhi jo action attribute hai usmain value set karne ke liye...
        form.submit();
        // form.submit --> form ko submit karwane ke liye...
      }, 1000);
      // jaise hi event ayega uske harek 1-second ke baad ye function chalega...
    });

    // For Flash Messages
    setTimeout(function () {
      document.getElementById("flash-message").style.display = 'none';
    }, 3000);
  </script>

</body>

</html>