<?php
    $code = "";
    $uname = "";

    if (strpos($_SERVER['REQUEST_URI'], '/views/') !== false) {
        exit();
    }

    function sanitizer($string)
    {
        $con = getCon();
        if(!empty($string))
        {
            return  mysqli_real_escape_string($con, trim(htmlspecialchars($string)));
        }
        else
        {
            return "";
        }
    }



    if(isset($_GET['un']) && isset($_GET['c']))
    {
        $code = sanitizer($_GET['c']);
        $uname = sanitizer($_GET['un']);
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Document</title>
        <link rel="stylesheet" href="views/styles/all.css" />
        <link rel="stylesheet" href="views/styles/resetpass.css" />
        <link rel="stylesheet" href="views/styles/throwlert.css" />
        <link rel="stylesheet" href="views/styles/semiloader.css" />


        <script src="views/js/jquery341.js"></script>
        <script src="views/js/throwlert.js" defer></script>
        <script src="views/js/throwlert.js" defer></script>

    </head>
    <body>
        <div class="semiloader">
            <div class="one"></div>
            <div class="two"></div>
            <div class="three"></div>
            <div class="four"></div>
        </div>
        <form id="resetform">
            <a href="./" id="title">Notefused</a>
            <span>Password Reset Form</span>
            <div class="input-boxes">
                <span class="msg">
                    A code was sent to your email check in spam folder if not
                    found
                </span>
                <input
                    type="text"
                    id="unamebox"
                    placeholder="username"
                    value="<?php echo $uname; ?>"
                    autocomplete="off"
                />
                <input
                    type="text"
                    id="codebox"
                    placeholder="code from email"
                    value="<?php echo $code; ?>"
                    autocomplete="off"
                />
                <input
                    type="password"
                    id="passbox"
                    placeholder="enter new paswword"
                    value=""
                    autocomplete="off"
                />
                <input
                    type="password"
                    id="cpassbox"
                    placeholder="retype new password"
                    value=""
                    autocomplete="off"
                />
                <input type="submit" value="Confirm" />
            </div>
            <span> Remember password? <a href="login">Login Instead</a> </span>
        </form>
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
    <script>
        $('#resetform').submit(function (e) {
            e.preventDefault();
            if (/* validate() fwork */ true) {
                $('.semiloader').fadeIn();
                $.ajax({
                    url: 'controllers/resetconfirmhandler.php',
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        uname: $('#unamebox').val(),
                        code: $('#codebox').val(),
                        pass: $('#passbox').val(),
                        cpass: $('#cpassbox').val(),
                    },
                    success: function (data) {
                        $('.semiloader').fadeOut(function(){

                            if('err_field' in data){
                                throwlert(0,data.err_field);
                            }else if('err_code' in data){
                                throwlert(0,data.err_code);
                            }else if('err_pass' in data){
                                throwlert(0,data.err_pass);
                            }else if('success' in data){
                                throwlert(1,'Password changed successfully');
    
                                setTimeout(function(){
                                    window.location.href = 'login';
                                }, 3000);
    
                            }
                            else{
                                throwlert(0,'Something went wrong');
                            }

                        });

                    },
                });
            }
        });
    </script>
</html>
