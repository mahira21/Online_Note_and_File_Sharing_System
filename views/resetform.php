<?php
    if (strpos($_SERVER['REQUEST_URI'], '/views/') !== false) {
        exit();
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
                <input
                    type="text"
                    id="unamebox"
                    placeholder="username"
                    value=""
                    autocomplete="off"
                />
                <input
                    type="text"
                    id="emailbox"
                    placeholder="email"
                    value=""
                    autocomplete="off"
                />
                <input type="submit" value="Request" />
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

            if (/* validate() fwork*/ true) {
                $('.semiloader').fadeIn();
                $.ajax({
                    url: 'controllers/resethandler.php',
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        uname: $('#unamebox').val(),
                        email: $('#emailbox').val(),
                    },
                    success: function (data) {
                        $('.semiloader').fadeOut(function(){
                            if('error' in data){
                                    throwlert(0,data.error);
                            }else if('err_record' in data){
                                throwlert(0,data.err_record);
                            }else if('success' in data){
                                throwlert(1,'An email with code has been sent to you');
                                setTimeout(function(){
                                    window.location.href = 'resetconfirm';
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
