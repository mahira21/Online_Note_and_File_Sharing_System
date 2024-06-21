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
<div class="index-sidebar-holder">
    <div class="index-sidebar-contents guest-index-sidebar-contents" <?php echo $notlogged; ?>>
        <div class="primary-index-sidebar-content">
            <a href="" class="primary-index-sidebar-a">Profile</a>
            <div class="secondary-index-sidebar-content">
                <a href="" class="secondary-index-sidebar-a" id="sideLoginBtn">Login</a>
                <a href="registration" class="secondary-index-sidebar-a">Register</a>
            </div>
        </div>
        <div class="primary-index-sidebar-content">
            <a href="" class="primary-index-sidebar-a">Note</a>
            <div class="secondary-index-sidebar-content">
                <a href="./" class="secondary-index-sidebar-a">New Note</a>
                <a href="mynotes" class="secondary-index-sidebar-a guest-secondary-index-a">My Notes</a>
            </div>
        </div>
        <div class="primary-index-sidebar-content">
            <a href="" class="primary-index-sidebar-a">File</a>
            <div class="secondary-index-sidebar-content">
                <a href="file" class="secondary-index-sidebar-a guest-secondary-index-a">Upload File</a>
                <a href="myfiles" class="secondary-index-sidebar-a guest-secondary-index-a">My Files</a>
            </div>
        </div>
        <div class="primary-index-sidebar-content">
            <a href="" class="primary-index-sidebar-a">URL</a>
            <div class="secondary-index-sidebar-content">
                <a href="go" class="secondary-index-sidebar-a guest-secondary-index-a">Shorten URL</a>
                <a href="myurls" class="secondary-index-sidebar-a guest-secondary-index-a">My URLs</a>
            </div>
        </div>
    </div>
    <div class="index-sidebar-contents user-index-sidebar-contents" <?php echo $logged; ?>>
        <div class="primary-index-sidebar-content">
            <a href="" class="primary-index-sidebar-a">Profile</a>
            <div class="secondary-index-sidebar-content">
                <?php require "userdashcard.php"; ?>
                <a href="settings#personalInfo" class="secondary-index-sidebar-a">Profile Settings</a>
                <a href="settings#plans" class="secondary-index-sidebar-a">Plan Settings</a>
                <a href="controllers/destroysessionmodule.php" class="secondary-index-sidebar-a">Logout</a>
            </div>
        </div>
        <div class="primary-index-sidebar-content">
            <a href="" class="primary-index-sidebar-a">Note</a>
            <div class="secondary-index-sidebar-content">
                <a href="./" class="secondary-index-sidebar-a">New Note</a>
                <a href="mynotes" class="secondary-index-sidebar-a">My Notes</a>
            </div>
        </div>
        <div class="primary-index-sidebar-content">
            <a href="" class="primary-index-sidebar-a">File</a>
            <div class="secondary-index-sidebar-content">
                <a href="file" class="secondary-index-sidebar-a">Upload File</a>
                <a href="myfiles" class="secondary-index-sidebar-a">My Files</a>
            </div>
        </div>
        <div class="primary-index-sidebar-content">
            <a href="" class="primary-index-sidebar-a">URL</a>
            <div class="secondary-index-sidebar-content">
                <a href="go" class="secondary-index-sidebar-a">Shorten URL</a>
                <a href="myurls" class="secondary-index-sidebar-a">My URLs</a>
            </div>
        </div>
    </div>
</div>
