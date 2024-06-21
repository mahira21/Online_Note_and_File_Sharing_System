<?php
    //preventing access from view route
    if (strpos($_SERVER['REQUEST_URI'], '/views/') !== false) {
        exit();
    }
    
    $logged = "";
    $notlogged = "";
    if(isset($_SESSION['user'])) 
    {
        $notlogged = "style='display:none'";
    }
    else
    {
        $logged = "style='display:none'";
    }
?>
<div class="sidebar-holder">
    <div class="sidebar-contents guest-sidebar-contents" <?php echo $notlogged; ?>>
        <div class="primary-sidebar-content">
            <a href="" class="primary-sidebar-a">Profile</a>
            <div class="secondary-sidebar-content">
                <a href="" class="secondary-sidebar-a" id="sideLoginBtn">Login</a>
                <a href="registration" class="secondary-sidebar-a">Register</a>
            </div>
        </div>
        <div class="primary-sidebar-content">
            <a href="" class="primary-sidebar-a">Note</a>
            <div class="secondary-sidebar-content">
                <a href="./" class="secondary-sidebar-a">New Note</a>
                <a href="mynotes" class="secondary-sidebar-a guest-secondary-a">My Notes</a>
            </div>
        </div>
        <div class="primary-sidebar-content">
            <a href="" class="primary-sidebar-a">File</a>
            <div class="secondary-sidebar-content">
                <a href="file" class="secondary-sidebar-a guest-secondary-a">Upload File</a>
                <a href="myfiles" class="secondary-sidebar-a guest-secondary-a">My Files</a>
            </div>
        </div>
        <div class="primary-sidebar-content">
            <a href="" class="primary-sidebar-a">URL</a>
            <div class="secondary-sidebar-content">
                <a href="go" class="secondary-sidebar-a guest-secondary-a">Shorten URL</a>
                <a href="myurls" class="secondary-sidebar-a guest-secondary-a">My URLs</a>
            </div>
        </div>
    </div>
    <div class="sidebar-contents user-sidebar-contents" <?php echo $logged; ?>>
        <div class="primary-sidebar-content">
            <a href="" class="primary-sidebar-a">Profile</a>
            <div class="secondary-sidebar-content">
                <?php 
                    require 'userdashcard.php';
                 ?>
                <a href="settings#personalInfo" class="secondary-sidebar-a">Profile Settings</a>
                <a href="settings#plans" class="secondary-sidebar-a">Plan Settings</a>
                <a href="controllers/destroysessionmodule.php" class="secondary-sidebar-a">Logout</a>
            </div>
        </div>
        <div class="primary-sidebar-content">
            <a href="" class="primary-sidebar-a">Note</a>
            <div class="secondary-sidebar-content">
                <a href="./" class="secondary-sidebar-a">New Note</a>
                <a href="mynotes" class="secondary-sidebar-a">My Notes</a>
            </div>
        </div>
        <div class="primary-sidebar-content">
            <a href="" class="primary-sidebar-a">File</a>
            <div class="secondary-sidebar-content">
                <a href="file" class="secondary-sidebar-a">Upload File</a>
                <a href="myfiles" class="secondary-sidebar-a">My Files</a>
            </div>
        </div>
        <div class="primary-sidebar-content">
            <a href="" class="primary-sidebar-a">URL</a>
            <div class="secondary-sidebar-content">
                <a href="go" class="secondary-sidebar-a">Shorten URL</a>
                <a href="myurls" class="secondary-sidebar-a">My URLs</a>
            </div>
        </div>
    </div>
</div>
