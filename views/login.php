<?php
    session_start();

    //preventing access from view route
    if (strpos($_SERVER['REQUEST_URI'], '/views/') !== false) {
        exit();
    }

    if(isset($_SESSION['user'])) 
    {
        header("Location:./");
    }
    elseif(isset($_SESSION['admin']))
    {
        header("Location:dashboard");
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | NoteFused</title>
    <link rel="stylesheet" href="views/styles/loginpage.css">
    <link rel="stylesheet" href="views/styles/form.css">
    <script src="views/js/jquery341.js"></script>
    <script src="views/js/loginvalidation.js" defer></script>


</head>

<body>

    </div>
    <div class="form-wrap" id="login_form">
        <form action="" id="" method="post" autocomplete="off">
            <div class="wrap">
                <div class="headingz">
                    <div class="title">
                        <a href="./">NoteFused</a>
                    </div>
                    <div><span>&nbsp>&nbsp</span></div>
                    <div>
                        Login
                    </div>
                </div>
            </div>
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
        function resetForm() {
            $(".input-sec input").removeClass('focus');
        }
        $(".input-sec input").on("focus", function () {
            $(this).addClass("focus");
        });
        $(".input-sec input").on("blur", function () {
            if ($(this).val() == "") {
                $(this).removeClass('focus');
            }
        });
                
    </script>
</body>

</html>