<?php
    session_start();
    //preventing access from view route
    if (strpos($_SERVER['REQUEST_URI'], '/index.php') !== false) {
        exit();
    }
    require "controllers/indexhandler.php";
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $noteID." | NoteFused";?></title>
    <link rel="stylesheet" href="views/styles/all.css">
    <link rel="stylesheet" href="views/styles/throwlert.css">
    <link rel="stylesheet" href="views/styles/optiontoggler.css">
    <link rel="stylesheet" href="views/styles/main.css">
    <link rel="stylesheet" href="views/styles/side2.css">
    <link rel="stylesheet" href="views/styles/login.css">
    <link rel="stylesheet" href="views/styles/form.css">
    <link rel="stylesheet" href="views/styles/userdashcard.css">
    <link rel="stylesheet" href="views/styles/indexsidebar.css">

    <script src="views/js/jquery341.js"></script>
    <script src="views/js/throwlert.js" defer></script>
    <script src="views/js/editorfunctionality.js" defer></script>
    <script src="views/js/optiontogglerfunctionality.js" defer></script>
    <script src="views/js/loginvalidationfunctionality.js" defer></script>
    <script src="views/js/userdashcardfunctionality.js" defer></script>
    <script src="views/js/sidebarfunctionality.js" defer></script>


</head>
<body>
<?php require "views/sidebar.php"; ?>
    <div class="container">
        <div class="sidebar">
            <?php require "views/indexsidebar.php"; ?>
        </div>
        <div class="editor">
            <div class="bar" id="bar">
                <div class="head-bar">
                    <button id="ham">&#9776</button>
                    <div class="title">
                        <a href="./">NoteFused</a>
                    </div>
                    <button id="expand">
                        <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                        <g>
                            <title>background</title>
                            <rect fill="none" id="canvas_background" height="402" width="582" y="-1" x="-1"/>
                        </g>
                        <g>
                            <title>Layer 1</title>
                            <path id="svg_1" d="m256,298.3l0,0l0,0l174.2,-167.2c4.3,-4.2 11.4,-4.1 15.8,0.2l30.6,29.9c4.4,4.3 4.5,11.3 0.2,15.5l-212.7,204.2c-2.2,2.2 -5.2,3.2 -8.1,3c-3,0.1 -5.9,-0.9 -8.1,-3l-212.7,-204.2c-4.3,-4.2 -4.2,-11.2 0.2,-15.5l30.6,-29.9c4.4,-4.3 11.5,-4.4 15.8,-0.2l174.2,167.2z"/>
                        </g>
                        </svg>
                    </button>
                                                                                
                </div>
                <form action="" id="noteForm">
                <div class="edit-settings" id="editSettings">
                    <div class="privacy-radio-holder sub-settings">
                        <div class="sep-div"><input type="radio" name="privacy" id="privpub" value="0" <?php echo $privacypub; ?> ><span>Public Note</span></div>
                        <div class="sep-div"><input type="radio" name="privacy" id="privview" value="2" <?php echo $privacyview; ?> ><span>View Only Note</span></div>
                        <div class="sep-div"><input type="radio" name="privacy" id="privpri" value="1" <?php echo $privacypriv; ?> ><span>Private Note</span></div>
                    </div>
                    <div class="sub-settings">
                        <div class="sep-div">
                            <span>Expiration</span>
                            <select name="" id="expire">
                                <option value="3650" <?php echo $exp[0] ;?> >None</option>
                                <option value="3" <?php echo $exp[1] ;?> >3 days</option>
                                <option value="7" <?php echo $exp[2] ;?> >7 days</option>
                                <option value="30" <?php echo $exp[3] ;?> >30 days</option>
                            </select>
                        </div>
                    </div>
                    <div class="sub-settings">
                        <div class="sep-div">
                            <span>Author</span>
                            <input type="text" name="" id="author" value="<?php echo $noteOwner; ?>" readonly>
                        </div>
                    </div>
                    <div class="sub-settings zoom-buttons">
                        <button id="tzmin"><i class="fas fa-minus"></i></button><!--  <span>zoom</span> --><span id='tzpercent'>100%</span><button id="tzplus"><i class="fas fa-plus"></i></button>
                    </div>
                    
                </div>
            </div>
            
            <div class="fuse">
                <textarea name="" id="pad" spellcheck="false" placeholder="Start typing notes here..."><?php echo $noteText; ?></textarea>
            </div>
            </form>
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
    <div id="disableDiv"></div>

    <div class="loginform" id="loginForm">
        <button id="close" onclick="closeForm()">x</button>
        <form action="" id="login_form" method="post" autocomplete="off">
            <h1 class="form-heading">Login</h1>
            <div class="warn" id="errProfile"><?php //echo $err_profile; ?></div>
            <div class="input-sec">
                <input type="text" name="uname" id="unamebox" value="<?php //echo $uname; ?>">
                <span data-placeholder="username" ></span>
            </div>
            <div class="warn" id="errUname"><?php //echo $err_uname; ?></div>
            <div class="input-sec">
                <input type="password" name="pass" id="passbox" value="<?php //echo $pass;?>">
                <span data-placeholder="password"></span>
            </div>
            <div class="warn" id="errPass"><?php //echo $err_pass; ?></div>
            <div class="button-holder">
                <input type="submit" value="Login" class="subBtn" name="login" >
            </div>
            <div class="bottomText">
                Don't have an account? <a href="registration">Register Now</a>
            </div>
            <div class="bottomText">
                Forget Password? <a href="resetform">Recover</a>
            </div>

        </form>
    </div>
    <script>
        
        let loggedUser ="<?php echo empty($loggedUser)?'':$loggedUser?>";
        let noteid = '<?php echo $noteID; ?>';
        let oldpvc = '<?php echo $oldpvc; ?>';
        let noteOwner = '<?php echo $noteOwner; ?>';
        if(oldpvc == 2){
            if(loggedUser != noteOwner){
                $('#pad').attr('readonly','true');
            }
        }
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