<?php
    $name = "";
    $err_name = "";
    $email = "";
    $err_email = "";
    $message = "";
    $err_message = "";

    if(isset($_POST['submit']))
    {
        if(empty($_POST['name']))
        {
            $err_name = "Name can not be empty";
        }
        else
        {
            $name = htmlspecialchars($_POST['name']);
        }

        if(empty($_POST['email']))
        {
            $err_email = "Email can not be empty";
        }
        else
        {
            $email = htmlspecialchars($_POST['email']);
        }
        if(empty($_POST['message']))
        {
            $err_message = "Your message can not be empty";
        }
        else
        {
            $message = htmlspecialchars($_POST['message']);
        }

    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact | NoteFused</title>
    <link rel="stylesheet" href="styles/form.css">
    <link rel="stylesheet" href="styles/contact.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

</head>

<body>
    <div class="form-wrap">

        <form action="" method="post">
            <div class="headingz">
                <div class="title">
                    <a href="index.php">NoteFused</a>
                </div>
                <div><span>&nbsp>&nbsp</span></div>
                <div>
                    Contact
                </div>
            </div>
            <div class="user-info">
                <div class="placediv">
                    <div class="input-sec">
                        <input type="text" name="name" id="namebox" value="<?php echo $name ;?>">
                        <span data-placeholder="name"></span>
                    </div>
                    <div class="warn"><?php echo $err_name; ?></div>
                </div>
                <div class="placediv">
                    <div class="input-sec">
                        <input type="text" name="email" id="emailbox" value="<?php echo $email ;?>">
                        <span data-placeholder="email"></span>
                    </div>
                    <div class="warn"><?php echo $err_email; ?></div>
                </div>
            </div>

            <div class="pad">
                <textarea name="message" id="messagebox" placeholder="Write here your concern.." ><?php echo $message ;?></textarea>
                <span></span>
            </div>
            
            <div class="warn"><?php echo $err_message; ?></div>
            <div class="button-holder">
                <input type="reset" value="Clear Form" class="resBtn" id="resetButton" onclick="resetForm()">
                <div class="gap"></div>
                <input type="submit" value="Submit" class="subBtn" name="submit">
            </div>

            <div class="bottomText">
                Want to know what other people concerns Frequently?
                <br>
                visit <a href="#">FAQ</a> section
            </div>

            <script>
                function resetForm() {
                    $(".input-sec input").removeClass('focus');
                }
                $(".input-sec input").on("focus", function () {
                    $(this).addClass("focus");
                });
                $(".pad textarea").on("focus", function () {
                    $(this).addClass("focus");
                    $(".pad").css({ 'border-bottom': 'none' });
                });

                $(".input-sec input").on("blur", function () {
                    if ($(this).val() == "") {
                        $(this).removeClass('focus');
                    }
                });
                $(".pad textarea").on("blur", function () {
                    if ($(this).val() == "") {
                        $(this).removeClass('focus');
                        $(".pad").css({ 'border-bottom': '2px solid #adadad' });
                    }
                });
                

            </script>

        </form>
    </div>

    <?php
            if($name != "")
            {
                echo "<script>
                $('#namebox').addClass('focus');
                </script>";
            }

            if($email != "")
            {
                echo "<script>
                $('#emailbox').addClass('focus');
                </script>";
            }
            if($message != "")
            {
                echo "<script>
                $('#messagebox').addClass('focus');
                </script>";
            }
            
        ?>
</body>

</html>