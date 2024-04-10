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
$sql_2 = "Select * from contact_request";
$result_2 = mysqli_query($conn, $sql_2);
$total_records = mysqli_num_rows($result_2);
$total_pages = ceil($total_records / $num_per_page);

if (isset($_GET["page"])) {
  $curr_page = clean_search_input($_GET["page"]);
  if (is_int($curr_page) || $curr_page < 1 || $curr_page > $total_pages) {
    $curr_page = 1;
  }
} else {
  $curr_page = 1;
}

if (isset($_GET["column_name"])) {
  $column_name = clean_search_input($_GET["column_name"]);
  if ($column_name !== "contact_id" && $column_name !== "contact_name" && $column_name !== "contact_email" && $column_name !== "contact_number" && $column_name !== "contact_subject") {
    $column_name = "contact_id";
  }
} else {
  $column_name = "contact_id";
}

if (isset($_GET["sort_order"])) {
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
if (isset($_GET["search_box"]) && $_GET["search_box"] !== "") {
  $search = stripslashes($_GET["search_box"]);
  $search = str_replace("'", '', $search);
  $search = htmlspecialchars($search, ENT_QUOTES, 'UTF-8');
  $sql = "SELECT contact_id, contact_name, contact_email, contact_number, contact_subject, contact_message FROM contact_request 
          WHERE contact_id LIKE '%".trim($search)."%' 
          OR contact_name LIKE '%".trim($search)."%' 
          OR contact_email LIKE '%".trim($search)."%' 
          OR contact_number LIKE '%".trim($search)."%' 
          OR contact_subject LIKE '%".trim($search)."%' 
          ORDER BY $column_name $sort_order 
          LIMIT $start_from, $num_per_page";
  $result = mysqli_query($conn, $sql);
} else {
  $sql = "SELECT * FROM contact_request 
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
  <link rel="icon" type="image/x-icon" href="images/arcs_logo.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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
              <form id="myForm" role="form" action="" method="GET">
                <input id="search_box" type="text" class="search-box search-upper" name="search_box"
                  placeholder="Search..." value="<?php echo $search ?>">
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
                echo '<th width="10px"><a href="request.php?column_name=contact_id&sort_order=' . ($sort_order == "DESC" ? "ASC" : "DESC") . '&page=' . $curr_page . '">Id';

                if ($column_name == "contact_id") {
                  if ($sort_order == 'DESC') {
                    echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-down" aria-hidden="true"></i>';
                  } else {
                    echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-up" aria-hidden="true"></i>';
                  }
                }

                echo '</a></th>';
                echo '<th width="10px"><a href="request.php?column_name=contact_name&sort_order=' . ($sort_order == "DESC" ? "ASC" : "DESC") . '&page=' . $curr_page . '">Name';

                if ($column_name == "contact_name") {
                  if ($sort_order == 'DESC') {
                    echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-down" aria-hidden="true"></i>';
                  } else {
                    echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-up" aria-hidden="true"></i>';
                  }
                }

                echo '</a></th>';
                echo '<th width="10px"><a href="request.php?column_name=contact_email&sort_order=' . ($sort_order == "DESC" ? "ASC" : "DESC") . '&page=' . $curr_page . '">Email';

                if ($column_name == "contact_email") {
                  if ($sort_order == 'DESC') {
                    echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-down" aria-hidden="true"></i>';
                  } else {
                    echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-up" aria-hidden="true"></i>';
                  }
                }

                echo '</a></th>';
                echo '<th width="10px"><a href="request.php?column_name=contact_number&sort_order=' . ($sort_order == "DESC" ? "ASC" : "DESC") . '&page=' . $curr_page . '">Mobile';

                if ($column_name == "contact_number") {
                  if ($sort_order == 'DESC') {
                    echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-down" aria-hidden="true"></i>';
                  } else {
                    echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-up" aria-hidden="true"></i>';
                  }
                }

                echo '</a></th>';

                echo '<th width="10px"><a href="request.php?column_name=contact_subject&sort_order=' . ($sort_order == "DESC" ? "ASC" : "DESC") . '&page=' . $curr_page . '">Subject';

                if ($column_name == "contact_subject") {
                  if ($sort_order == 'DESC') {
                    echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-down" aria-hidden="true"></i>';
                  } else {
                    echo '<i style="position: absolute; margin-top:3px; margin-left:2px;" class="fa fa-arrow-up" aria-hidden="true"></i>';
                  }
                }

                echo '</a></th>';

                ?>
                <th width="50px" class="Message" style="color: #ff651b; padding-left: 15px;">Message</th>
              </tr>

              <?php
              if ($result) {
                while ($row = mysqli_fetch_array($result)) {
                  // Fetch the first row
                  $id = $row['contact_id'];
                  $Name = $row['contact_name'];
                  $Email = $row['contact_email'];
                  $Mobile = $row['contact_number'];
                  $Subject = $row['contact_subject'];

                  $Message = $row['contact_message'];   // Isko humko show karwana hai popup ke jariye...
              
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
              if ($curr_page == $i) {
                echo "<a  style='background-color: #ff651b; color: #fff; cursor: not-allowed; text-decoration: none;'>" . $i . "</a>";
              } else {
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