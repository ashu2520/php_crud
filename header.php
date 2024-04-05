<div class="header">
    <div class="wrapper">
        <div class="logo"><a href="#"><img src="images/logo.png"></a></div>
        <div class="right_side">
            <!-- <p style="margin-bottom: 10px;">Time: <?php echo time(); ?></p> -->
            <ul>
                <li>Welcome <strong> <?php echo $_SESSION['role_name']; ?></strong></li>
                <li><a onclick="logoutBC()">Log Out</a></li>
            </ul>
        </div>
        <div class="nav_top">
            <ul>
                <li><a href="graphical_dashboard.php" >Dashboard</a></li>
                <li><a href="client_dashboard.php" class="listusers">Users</a></li>
                <li><a href="emp_change_password.php">Change Password</a></li>
                <li><a href="request.php" class="managerequest">Contact Request</a></li>
                <?php
                if ($_SESSION['User_role_id'] == 1 ) {
                    echo'<li><a href="email_temp.php" class="template">Manage Templates</a></li>';
                    echo '<li><a href="settings.php" >Settings</a></li>';
                } ?>
            </ul>
        </div>
    </div>
</div>
<script>
    function logoutBC(){
        bc.postMessage("LOGOUT");
        window.location.href ="emp_logout.php";
    }
    // function chnageLocationBC(link){
    //     bc.postMessage(link);
    //     window.location.href = link;
    // }
    const li =document.getElementById('li');
    const bc = new BroadcastChannel("test_channel");
    bc.addEventListener('message', (event) => {
        if (event.data == "LOGOUT"){
            window.location.reload();
        }
        else {
            window.location.href = event.data
        }
    })
</script>