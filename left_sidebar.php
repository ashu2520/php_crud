<div class="left_sidebr">
    <ul class="submenu">
        <li><a href="graphical_dashboard.php">Dashboard</a></li>
        <li><a href="client_dashboard.php" class="listusers">Manage Users</a></li>
        <li><a href="emp_change_password.php">Change Password</a></li>
        <li><a href="request.php" class="managerequest">Manage Contact Request</a></li>
        <?php 
        if($_SESSION['User_role_id'] == 1){
        echo'<li><a href="email_temp.php" class="template">Manage Email Templates</a></li>';
        echo '<li><a href="settings.php">Settings</a></li>';
        }
        ?>
    </ul>
</div>
<script>
    var navLinks = document.querySelectorAll('ul li a');
    const lastActiveLink = window.location.href.split('?')[0]
    console.log(lastActiveLink);


    navLinks.forEach(function (link) {
        if (link.href === lastActiveLink) {
            link.classList.add('active');
        }

        link.addEventListener('click', function () {
            navLinks.forEach(function (link) {
                link.classList.remove('active');
            });

            this.classList.add('active');
        });
    });
    if (lastActiveLink == "http://localhost/NewUI/client_create.php" || lastActiveLink == "http://localhost/NewUI/client_update.php") {
        var elements = document.getElementsByClassName('listusers');
        for (var i = 0; i < elements.length; i++) {
            elements[i].classList.add('active');
        }
    }
    if (lastActiveLink == "http://localhost/NewUI/message_display.php") {


        var elements = document.getElementsByClassName('managerequest');
        for (var i = 0; i < elements.length; i++) {
            elements[i].classList.add('active');
        }
    }
    if (lastActiveLink == "http://localhost/NewUI/edit_templates.php") {


        var elements = document.getElementsByClassName('template');
        for (var i = 0; i < elements.length; i++) {
            elements[i].classList.add('active');
        }
    }
</script>