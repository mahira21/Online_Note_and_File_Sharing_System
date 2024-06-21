<?php
    session_start();
    require_once dirname(__FILE__).'/../models/db/dbcon.php';

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
            header("Location:./");
        }
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration | NoteFused</title>
    <link rel="stylesheet" href="views/styles/reg.css">
    <link rel="stylesheet" href="views/styles/form.css">
    <link rel="stylesheet" href="views/styles/throwlert.css">

    <script src="views/js/jquery341.js"></script>
    <script src="views/js/throwlert.js"></script>
    

</head>

<body>
    <div class="blur-back">

    </div>
    <div class="form-wrap">
        <form action="" method="post" id="rform" autocomplete="off">
            <div class="headingz">
                <div class="title">
                    <a href="./" id="ttl">NoteFused</a>
                </div>
                <div><span>&nbsp>&nbsp</span></div>
                <div>
                    Registration
                </div>
            </div>


            <div class="input-sec">
                <input type="text" name="name" id="namebox" value="<?php //echo $name ;?>">
                <span data-placeholder="name"></span>
            </div>
            <div class="warn"><?php //echo $err_name; ?></div>
            <div class="input-sec">
                <input type="text" name="uname" id="unamebox" value="<?php //echo $uname ;?>">
                <span data-placeholder="username"></span>
            </div>
            <div class="warn"><?php //echo $err_uname; ?></div>
            <div class="input-sec">
                <input type="text" name="email" id="emailbox" value="<?php //echo $email ;?>">
                <span data-placeholder="email"></span>
            </div>
            <div class="warn"><?php //echo $err_email; ?></div>
            <div class="input-sec">
                <input type="password" name="pass" id="passbox" value="<?php //echo $pass ;?>">
                <span data-placeholder="password"></span>
            </div>
            <div class="warn"><?php //echo $err_pass; ?></div>
            <div class="input-sec">
                <input type="password" name="cpass" id="cpassbox" value="<?php //echo $cpass ?>">
                <span data-placeholder="confirm password"></span>
            </div>
            <div class="warn"><?php //echo $err_cpass; ?></div>
            <div class="button-holder">
                <input type="reset" value="Clear Form" class="resBtn" id="resetButton" onclick="resetForm()">
                <div class="gap"></div>
                <input type="submit" value="Register" class="subBtn" name="register">
            </div>
            <div class="bottomText">
                Already have an account? <a href="login">Login Instead</a>
            </div>


        </form>
    </div>

    <script>

        //JS validation
        function warn(that, msg) {
            $(`#${that}`).parent().next('.warn').text(msg);
        }
        let pinfarr;
        let validate = () => {
            pinfarr = [];
            let valid = true;
            function warn(that, msg) {
                $(`#${that}`).parent().next('.warn').text(msg);
            }

            const letters = /^[A-Za-z ]+$/;
            const name = $('#namebox').val();
            if (name == '') {
                warn('namebox', 'please enter your name above');
                valid = false;
            } else if (!name.match(letters)) {
                warn('namebox', 'please enter letters and Space only (e.g. Abcd Efgh)');
                valid = false;
            } else {
                warn('namebox', '');
                pinfarr.push(name);
            }

            const uletters = /^[a-z0-9]{6,32}$/;
            const uname = $('#unamebox').val();
            if (uname == '') {
                warn('unamebox', 'please enter your username above');
                valid = false;
            } else if (!uname.match(uletters)) {
                warn('unamebox', "Invalid username. Make sure it conatins lower case letters and number only and length between 6 to 32 characters");
                valid = false;
            } else {
                warn('unamebox', '');
                pinfarr.push(uname);
            }


            const mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            const email = $('#emailbox').val();
            if (email == '') {
                warn('emailbox', 'please enter your email above');
                valid = false;
            } else if (!email.match(mailformat)) {
                warn('emailbox', 'please enter valid email only');
                valid = false;
            } else {
                warn('emailbox', '');
                pinfarr.push(email);
            }

            const pass = $('#passbox').val();
            if (pass == '') {
                warn('passbox', 'please enter your password above');
                valid = false;
            } else {
                warn('passbox', '');
                pinfarr.push(pass);
            }
            const cpass = $('#cpassbox').val();
            if (cpass == '') {
                warn('cpassbox', 'please enter your password above');
                valid = false;
            } else if (cpass !== pass) {
                warn('cpassbox', "pleasword didn't match");
                valid = false;
            }else {
                warn('passbox', '');
                pinfarr.push(cpass);
            }

            return valid;
        };

        $('#rform').submit(function (e) {
            e.preventDefault();

            $('.warn').each(function () {
                $(this).text('');
            });
            if (validate()) {
                $.ajax({
                    url: 'controllers/reghandler.php',
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        infoArray: pinfarr,
                    },
                    success: function (data) {
                            if ('err_name' in data) {
                                warn('namebox', data.err_name);
                            }
                            if ('err_uname' in data) {
                                warn('unamebox', data.err_uname);
                            }
                            if ('err_email' in data) {
                                warn('emailbox', data.err_email);
                            }
                            if ('err_pass' in data) {
                                warn('passbox', data.err_pass);
                            }
                            if ('err_cpass' in data) {
                                warn('cpassbox', data.err_cpass);
                            }
                            if ('success' in data) {
                                if (data.success == 'true') {
                                    throwlert(1, 'Account created Successfully');
                                    window.location.href = './';
                                } else if (data.success == 'false') {
                                    throwlert(1, 'Something went wrong');
                                }
                            }
                    },
                });
            }
        });


        function resetForm() {
            $(".input-sec input").removeClass('focus');
            $('.warn').each(function () {
                $(this).text('');
            });
        }
        $(".input-sec input").on("focus", function () {
            $(this).addClass("focus");
        });
        $(".input-sec input").on("blur", function () {
            if ($(this).val() == "") {
                $(this).removeClass('focus');
            }
        });

        function addRemoveFocus() {
            $('.input-sec input').each(function () {
                if ($(this).val() != '') {
                    $(this).addClass('focus');
                } else {
                    $(this).removeClass('focus');
                }
            });
        }
        addRemoveFocus();

    </script>
</body>

</html>