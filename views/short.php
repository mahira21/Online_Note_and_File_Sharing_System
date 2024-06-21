<?php
    session_start();

    require_once dirname(__FILE__).'/../controllers/permissionmodule.php';
    
    //preventing access from view route
    if (strpos($_SERVER['REQUEST_URI'], '/views/') !== false) {
        exit();
    }


    if(isset($_SESSION['user'])) 
    {
        $user = $_SESSION['user'];
        if(isset($user['username']))
        {
            $loggedUser = $user['username'];
        }
    }
    else
    {
        header("Location:login");
    }
    $permit = getPermit('url');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NoteFused</title>
    <link rel="stylesheet" href="views/styles/all.css">
    <link rel="stylesheet" href="views/styles/throwlert.css">
    <link rel="stylesheet" href="views/styles/side2.css">
    <link rel="stylesheet" href="views/styles/base.css">
    <link rel="stylesheet" href="views/styles/short.css">
    <link rel="stylesheet" href="views/styles/optiontoggler.css">
    <link rel="stylesheet" href="views/styles/login.css">
    <link rel="stylesheet" href="views/styles/form.css">
    <link rel="stylesheet" href="views/styles/navbar.css">
    <link rel="stylesheet" href="views/styles/userdashcard.css">
    <link rel="stylesheet" href="views/styles/sidebar.css">
    <link rel="stylesheet" href="views/styles/semiloader.css">
    <link rel="stylesheet" href="views/styles/nopermit.css">


    <script src="views/js/jquery341.js"></script>
    <script src="views/js/throwlert.js" defer></script>
    <script src="views/js/shortnerfunctionality.js" defer></script>
    <script src="views/js/optiontogglerfunctionality.js" defer></script>
    <script src="views/js/navbarfunctionality.js" defer></script>
    <script src="views/js/userdashcardfunctionality.js" defer></script>
    <script src="views/js/sidebar.js" defer></script>


</head>
<body>
    <div class="semiloader">
        <div class="one"></div>
        <div class="two"></div>
        <div class="three"></div>
        <div class="four"></div>
    </div>
    <?php require "sidebar.php"; ?>
    <div class="holder">
        <?php require "navbar.php"; ?>

        <div class="container">
            
            <div class="sidebar">
                <?php require "userdashcard.php"; ?>
            </div>
            <div class="fuse">
                <div class="mini-container">
<?php
    if($permit !== false && $permit === 1)
    {
?>                 
                    <div class="row2">
                        <div class="example-span">
                            <span id="exampleSpan">Enter your URL below</span>
                        </div>
                        <div class="row1">
                            <div class="url-box">
                                <input type="text" class="urlBox" name="" id="urlBox" placeholder="http://www.example.com" spellcheck="false">
                            </div>
                            <div class="button-box">
                                <button id="shortenButton">Shorten</button>
                            </div>
                        </div>
                    </div>
                    <div class="row2 result-row">
                        <div class="example-span">
                            <span id="exampleSpan">Here is your short URL</span>
                        </div>
                        <div class="row1">
                            <div class="url-box result-url-box">
                                <input type="text" class="urlBox" name="" id="resultUrlBox"spellcheck="false">
                            </div>
                            <div class="button-box copy-button-box">
                                <button id="copyButton"><i class="fas fa-copy"></i></button>
                            </div>
                        </div>
                        <div class="alert">
                                <span>link copied to your clipboard</span>
                        </div>
                    </div>
<?php
    }
    else
    {
?>
                    <div class="no-permit">
                        <div>
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            You are not allowed to shorten any new link but you can still access old short links from <a href="myurls">myurls</a>
                        </div>
                    </div>
<?php
    }
?>
                </div> <!-- mini-container -->
                
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