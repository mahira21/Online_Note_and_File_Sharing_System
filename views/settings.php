<?php
    session_start();
    //preventing access from view route
    if (strpos($_SERVER['REQUEST_URI'], '/views/') !== false) {
        exit();
    }

    if(!isset($_SESSION['user']))
    {
        header("Location:login");
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NoteFused</title>

    <link rel="stylesheet" href="views/styles/all.css">
    <link rel="stylesheet" href="views/styles/throwlert.css">
    <link rel="stylesheet" href="views/styles/loader.css">
    <link rel="stylesheet" href="views/styles/base.css">
    <link rel="stylesheet" href="views/styles/form.css">
    <link rel="stylesheet" href="views/styles/profilesettings.css">
    <link rel="stylesheet" href="views/styles/optiontoggler.css">
    <link rel="stylesheet" href="views/styles/navbar.css">
    <link rel="stylesheet" href="views/styles/userdashcard.css">
    <link rel="stylesheet" href="views/styles/sidebar.css">


    <script src="views/js/jquery341.js"></script>
    <script src="views/js/throwlert.js" defer></script>
    <script src="views/js/optiontogglerfunctionality.js" defer></script>
    <script src="views/js/navbarfunctionality.js" defer></script>
    <script src="views/js/userdashcardfunctionality.js" defer></script>
    <script src="views/js/settingsfunctionality.js" defer></script>
    <script src="views/js/sidebar.js" defer></script>


</head>
<body>
    <?php require "sidebar.php"; ?>
    <div class="loader">
        <div class="one"></div>
        <div class="two"></div>
        <div class="three"></div>
        <div class="four"></div>
    </div>
    <div class="holder">
        <?php require "navbar.php"; ?>
        <div class="container">
            
            <div class="sidebar">
                <?php require "userdashcard.php";?>
            </div>
            <div class="fuse">
                <div class="mini-container">
                    <section id="personalInfo">
                        <div class="section-title">
                            <span>Personal Info</span>
                        </div>
                        <div class="section-content">
                            <div class="input-container-sec">
                                <div class="primary-sec">
                                    <div class="input-sec">
                                        <input type="text" name="name" id="namebox">
                                        <span data-placeholder="name"></span>
                                    </div>
                                    <div class="warn"></div>
                                    <div class="input-sec">
                                        <input type="text" name="uname" id="unamebox" disabled>
                                        <span data-placeholder="username"></span>
                                    </div>
                                    <div class="warn"></div>
                                    <div class="input-sec">
                                        <input type="text" name="email" id="emailbox">
                                        <span data-placeholder="email"></span>
                                    </div>
                                    <div class="warn"></div>
                                    <div class="input-sec">
                                        <input type="password" name="oldpass" id="oldpassbox">
                                        <span data-placeholder="current password"></span>
                                    </div>
                                    <div class="warn"></div>
                                </div>
                                <div class="secondary-sec">
                                    <div class="checkbox-holder"><input type="checkbox" name="" id="passchange"><label for="passchange">Change Password</label></div>
                                    <div class="change-pass-sec">
                                        <div class="input-sec">
                                            <input type="password" name="pass" id="newpassbox">
                                            <span data-placeholder="new password"></span>
                                        </div>
                                        <div class="warn"></div>
                                        <div class="input-sec">
                                            <input type="password" name="cpass" id="cnewpassbox">
                                            <span data-placeholder="confirm new password"></span>
                                        </div>
                                        <div class="warn"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="button-sec">
                                <div class="button-holder">
                                    <input type="reset" value="Clear Changes" class="resBtn" id="resetButton">
                                    <div class="gap"></div>
                                    <input type="submit" value="Update" class="subBtn" name="register">
                                </div>
                            </div>
                        </div>
                    </section>
                    <div class="secdiv"></div>
                    <section id="plans">
                        <div class="section-title">
                            <span class="title">Upgrade Plan</span>
                        </div>
                        <div class="section-content">
                            <div class="compare-table">
                                <div class="basic-plan card">
                                    <div>basic</div>
                                    <!-- <div>$0.00/Month</div> -->
                                    <div>Unlimited Public Note</div>
                                    <div>50 Private Note Limit</div>
                                    <div>
                                        10MB File Limit
                                    </div>
                                    <div>50 Short URL Limit</div>
                                    <div>-</div>
                                    <div>-</div>
                                    <div><button>Select</button></div>
                                </div>
                                <div class="pro-plan card">
                                    <div>pro</div>
                                    <!-- <div>$3.00/Month</div> -->
                                    <div>Unlimited Public Note</div>
                                    <div>200 Private Note Limit</div>
                                    <div>
                                        20MB File Limit
                                    </div>
                                    <div>200 Short URL Limit</div>
                                    <div>Pinned Profile</div>
                                    <div>-</div>
                                    <div><button>Select</button></div>
                                </div>
                                <div class="ultra-plan card">
                                    <div>ultra</div>
                                    <!-- <div>$40.00/Month</div> -->
                                    <div>Unlimited Public Note</div>
                                    <div>Unlimited Private Note</div>
                                    <div>
                                        1GB File Limit
                                    </div>
                                    <div>Unlimited Short URL</div>
                                    <div>Pinned Profile</div>
                                    <div>Profile Verifiation</div>
                                    <div><button>Select</button></div>
                                </div>
                            </div>
                            <div class="request-status">
                                You have not requested any plan change
                            </div>
                        </div>
                    </section>
                    
                </div>
            </div>
            <div class="alter-options">
                <div class="option-toggler" id="optionToggler">
                    <div class="create-link">
                        <button id="createNewLink" title="Shorten a URL"><i class="fas fa-link"></i></button>
                    </div>
                    <div class="create-file">
                        <button id="createNewFile" title="Upload a File"><i class="fas fa-file-archive"></i></button>
                    </div>
                    <div class="create-note">
                        <button id="createNewNote" title="Create another Note"><i class="fas fa-file-alt"></i></button>
                    </div>
                </div>
                <div class="expand-option">
                    <button id="expandOptions"><i class="fas fa-plus"></i></button>
                </div>
    
            </div>
        </div>
            
            
        </div>
    </div>
    <script>
    </script>
        <div class="throwlert">
            <div class="alert-box">
                <div class="alert-close-button">
                    <button><i class="fas fa-times"></i></button>
                </div>
                <div class="alert-type type-success">
                    <i class="far fa-check-circle"></i>
                </div>
                <div class="alert-type type-error">
                    <i class="far fa-times-circle"></i>
                </div>
                <div class="alert-dialog"></div>
            </div>
        </div>
</body>

</html>