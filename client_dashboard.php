﻿<?php
include "connect.php";

// Starting the session
if (!isset ($_SESSION["user_name"])) {
  header("location:emp_login.php");
}
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
$num_per_page = 8;
$sql_2 = "Select * from `login_credentials`";
$result_2 = mysqli_query($conn, $sql_2);
$total_records = mysqli_num_rows($result_2);
$total_pages = ceil($total_records / $num_per_page);
// echo $total_records;

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
  if ($column_name !== "Id" && $column_name !== "Name" && $column_name !== "Gender" && $column_name !== "Mobile" && $column_name !== "Email" && $column_name !== "User_type") {
    $column_name = "Createdat";
  }
} else {
  $column_name = "Createdat";
}

if (isset ($_GET["sort_order"])) {
  $sort_order = clean_search_input($_GET["sort_order"]);
  if ($sort_order !== "ASC" && $sort_order !== "DESC") {
    $sort_order = "DESC";
  }
} else {
  $sort_order = "DESC";
}

$curr_page = max(1, $curr_page);
$start_from = ($curr_page - 1) * $num_per_page;
// $start_form --> ye batata hai ki next page kaha se start hoga...  
$search = "";
if (isset($_POST["search_box"]) && $_POST["search_box"] !== "") {
  $search = stripslashes($_POST["search_box"]);
  $search = trim($search);
  $search = str_replace("'", '"', $search);
  $search = htmlspecialchars($search, ENT_QUOTES, 'UTF-8');
  // echo $search;
  $sql = "SELECT * FROM login_credentials 
          WHERE Id LIKE '%$search%' 
          OR Name LIKE '%$search%' 
          OR Mobile LIKE '%$search%' 
          OR Email LIKE '%$search%' 
          OR Gender LIKE '%$search%' 
          OR User_type LIKE '%$search%' 
          ORDER BY $column_name $sort_order 
          LIMIT $start_from, $num_per_page";
  $result = mysqli_query($conn, $sql);
  $total_records = mysqli_num_rows($result);
  // print_r($_POST);
  // unset($_POST["search_box"]);
  // print_r($_POST);
$total_pages = ceil($total_records / $num_per_page);
// echo $total_records;
} else {
  $sql = "SELECT * FROM `login_credentials` 
  ORDER BY $column_name $sort_order 
  LIMIT $start_from, $num_per_page";
  // die(mysqli_error($conn));
  $result = mysqli_query($conn, $sql);
}
?>

<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Client Data</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link href="css/client_dashboard.css" rel="stylesheet" />
  <style>
    .active-page{
  text-decoration: none; 
  
}
  </style>
</head>

<body>
  <?php include "header.php"; ?>

  <div class="clear"></div>
  <div class="clear"></div>

  <?php
  if (isset ($_SESSION['flash_message'])) {
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
                  <a class="del_btn" onclick='closepopup()'>Cancel</a>
                </div>
              </div>

              <!-- Search Box -->
              <form id="myForm" role="form" action="" method="POST">
                <input id="search_box" type="text" class="search-box search-upper" name="search_box" placeholder="Search..." value="<?php echo $search; ?>">
                <input type="submit" class="submit-btn" value="Search" />
                <div class="add_more_user_button">
                  <a class="submit-btn add-user" href="client_create.php">Add More Users</a>
                </div>
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

              // Id
                echo '<th width="10px"><a href="client_dashboard.php?column_name=Id&sort_order=' . ($sort_order == "DESC" ? "ASC" : "DESC") . '&page=' . $curr_page . '">S.no';
                if ($column_name == "Id") {
                    if ($sort_order == 'DESC') {
                        echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-down" aria-hidden="true"></i>';
                    } else {
                        echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-up" aria-hidden="true"></i>';
                    }
                }
                echo '</a></th>';

              // Name
                echo '<th width="98px"><a href="client_dashboard.php?column_name=Name&sort_order=' . ($sort_order == "DESC" ? "ASC" : "DESC") . '&page=' . $curr_page . '">Name ';
                if ($column_name == 'Name') {
                  if ($sort_order == 'DESC')
                    echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-down" aria-hidden="true"></i>';
                  else
                    echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-up" aria-hidden="true"></i>';
                }
                echo '</a></th>';
                
              // Email
                echo '<th width="113px"><a href="client_dashboard.php?column_name=Email&sort_order=' . ($sort_order == "DESC" ? "ASC" : "DESC") . '&page=' . $curr_page . '">Email ';
                if ($column_name == 'Email') {
                  if ($sort_order == 'DESC')
                    echo '<i class="fa fa-arrow-down" aria-hidden="true"></i>';
                  else
                    echo '<i class="fa fa-arrow-up" aria-hidden="true"></i>';
                }
                echo '</a></th>';
                
              // Mobile
                echo '<th width="113px"><a href="client_dashboard.php?column_name=Mobile&sort_order=' . ($sort_order == "DESC" ? "ASC" : "DESC") . '&page=' . $curr_page . '">Mobile ';
                if ($column_name == 'Mobile') {
                  if ($sort_order == 'DESC')
                    echo '<i class="fa fa-arrow-down" aria-hidden="true"></i>';
                  else
                    echo '<i class="fa fa-arrow-up" aria-hidden="true"></i>';
                }
                echo '</a></th>';

              // Gender
                echo '<th width="100px"><a href="client_dashboard.php?column_name=Gender&sort_order=' . ($sort_order == "DESC" ? "ASC" : "DESC") . '&page=' . $curr_page . '">Gender ';
                if ($column_name == 'Gender') {
                  if ($sort_order == 'DESC')
                    echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-down" aria-hidden="true"></i>';
                  else
                    echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-up" aria-hidden="true"></i>';
                }
                echo '</a></th>';

              // Country
                // echo '<th width="15px"><a href="client_dashboard.php?column_name=Country&sort_order=' . ($sort_order == "DESC" ? "ASC" : "DESC") . '&page=' . $curr_page . '">Location ';
                // if ($column_name == 'Country') {
                //   if ($sort_order == 'DESC')
                //     echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-down" aria-hidden="true"></i>';
                //   else
                //     echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-up" aria-hidden="true"></i>';
                // }
                // echo '</a></th>';
                
              // State
                // echo '<th width="15px"><a href="client_dashboard.php?column_name=State&sort_order=' . ($sort_order == "DESC" ? "ASC" : "DESC") . '&page=' . $curr_page . '">State ';
                // if ($column_name == 'State') {
                //   if ($sort_order == 'DESC')
                //     echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-down" aria-hidden="true"></i>';
                //   else
                //     echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-up" aria-hidden="true"></i>';
                // }
                // echo '</a></th>';

              // User_type
                echo '<th width="97px"><a href="client_dashboard.php?column_name=User_type&sort_order=' . ($sort_order == "DESC" ? "ASC" : "DESC") . '&page=' . $curr_page . '">User Type ';
                if ($column_name == 'User_type') {
                  if ($sort_order == 'DESC')
                    echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-down" aria-hidden="true"></i>';
                  else
                    echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-up" aria-hidden="true"></i>';
                }
                echo '</a></th>';

              
                echo '<th width="135px"><a href="client_dashboard.php?column_name=Createdat&sort_order=' . ($sort_order == "DESC" ? "ASC" : "DESC") . '&page=' . $curr_page . '">Created At ';
                if ($column_name == 'Createdat') {
                  if ($sort_order == 'DESC')
                    echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-down" aria-hidden="true"></i>';
                  else
                    echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-up" aria-hidden="true"></i>';
                }
                echo '</a></th>';
                ?>

                <th width="50px" class="Action" style="color: #ff651b;">Action</th>
              </tr>

              <?php
              if ($result) {
                while ($row = mysqli_fetch_array($result)) {
                  // Fetch the first row
                  $id = $row['Id'];
                  $Name = $row['Name'];
                  $Email = $row['Email'];
                  $Mobile = $row['Mobile'];
                  $Gender = $row['Gender'];
                  // $Country = $row['Country'];
                  // $State = $row['State'];
                  $User_type = $row['User_type'];
                  $Created_at = $row['Createdat'];
                  echo "<tr>
                         <td>" . $id . "</td>
                         <td>" . $Name . "</td>
                         <td>" . $Email . "</td>
                         <td>" . $Mobile . "</td>
                         <td>" . $Gender . "</td>
                         <td>" . $User_type . "</td>
                         <td>" . $Created_at . "</td>
                         <td> 
                         <a href='client_update.php?updateid=" . $id . "&column_name=" . $column_name . "&sort_order=" . $sort_order . "&page=" . $curr_page . "' id='update' style='margin-right:10px'><img src='images/edit-icon.png' onclick='myfunc()'></a>
                         <a id='delete_a' onclick='openpopup($id, \"$column_name\", \"$sort_order\", $curr_page)'><img src='images/cross.png'></a>
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
            // echo $total_records;
            if ($curr_page - 1 > 0) {
              echo "<a class='act_btn' href='client_dashboard.php?column_name=" . $column_name . "&sort_order=" . ($sort_order == "DESC" ? "DESC" : "ASC") . "&page=" . ($curr_page - 1) . "'>Prev</a>";

            } else {
              echo "<a style='background-color: #b4b4b4; color: white; text-decoration: none; cursor: not-allowed;'>Prev</a>";
            }
            $start_page = max(1, min($curr_page, $total_pages - 2));
            $end_page = min($total_pages, $start_page + 2);

            // Display page numbers
            for ($i = $start_page; $i <= $end_page; $i++) {
              if($curr_page == $i) {
              echo "<a  style='background-color: #ff651b; color: #fff; cursor: not-allowed; text-decoration: none;'>" . $i . "</a>";
              } else{
                echo "<a href = 'client_dashboard.php?column_name=" . $column_name . "&sort_order=" . ($sort_order == "DESC" ? "DESC" : "ASC") . "&page=" . $i . "'>" . $i . "</a>";
              }
              // ? --> query parameters, multiple page sakte hai...
            }
            if ($curr_page + 1 <= $total_pages) {
              echo "<a class='act_btn' href = 'client_dashboard.php?column_name=" . $column_name . "&sort_order=" . ($sort_order == "DESC" ? "DESC" : "ASC") . "&page=" . ($curr_page + 1) . "'>Next</a>";
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
      input.focus();
      input.setSelectionRange(input.value.length, input.value.length);
    };

    input.addEventListener('input', function (event) {
      let timer;
      clearTimeout(timer);
      timer = setTimeout(() => {
        form.action = "client_dashboard.php?search_box=" + event.target.value;
        form.submit();
      }, 1000);
    });

    // For Flash Messages
    setTimeout(function () {
      document.getElementById("flash-message").style.display = 'none';
    }, 3000);
  </script>

</body>

</html>