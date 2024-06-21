<?php
    session_start();
    require_once dirname(__FILE__).'/../models/db/dbcon.php';


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

    function validateName($n)
    {
        
        $name = preg_replace('/\s\s+/', ' ', $n);
        if(empty($name))
        {
            return "please enter your name above";
        }
        elseif(!ctype_alpha(str_replace(' ', '', $name)))
        {
            return  "please enter letters and Space only (e.g. Abcd Efgh)";
        }
        else
        {
            return true;
        }
    }

    function validateUsername($un)
    {
        if(preg_match('/^[a-z0-9]{6,32}$/', $un))
        {
            return true;
        }
        else
        {
            return "Invalid username. Make sure it conatins lower case letters and number only and length between 6 to 32 characterss";
        }
    }

    $name = "";
    $err_name = "";
    $uname = "";
    $err_uname = "";
    $pass = "";
    $err_pass = "";
    $email = "";
    $err_email = "";
    $pass ="";
    $err_pass = "";
    $cpass = "";
    $err_cpass = "";
    $mpass ="";

    //below function ensures unique email from user
    function emailAvailable($email)
    {
        $query = "SELECT * from profiles where email='$email'";
        $result = get($query);
        if(mysqli_num_rows($result)>0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    //below function ensures unique username from user
    function isAvailable($uname)
    {
        $query = "SELECT * from profiles where username='$uname'";
        $result = get($query);
        if(mysqli_num_rows($result)>0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    //below function automatically logs in the user
    function autoLogin($uname,$pass)
    {
        $pass = md5($pass);
        $query = "SELECT * FROM profiles WHERE username='$uname' AND pass='$pass'";
        $result=get($query);
        if(mysqli_num_rows($result) > 0)
        {
            $user=mysqli_fetch_assoc($result);
            $_SESSION['user'] = $user;
            return true;            
        }
        else
        {
            return false;
        }
    }


    if(isset($_POST['infoArray']))
    {
        $data = array();
        $hasNoError = true;
        $infoArray = $_POST['infoArray'];
        if(is_array($infoArray))
        {
            if(count($infoArray)==5)
            {
                if(empty($infoArray[0]))
                {
                    $data['err_name'] = "Name can not be empty";
                    $hasNoError = false;
                }
                else
                {
                    $name = sanitizer($infoArray[0]);
                    $n = validateName($name);
                    if($n !== true)
                    {
                        $data['err_name'] = $n;
                        $hasNoError = false;
                    }
                }
                if(empty($infoArray[1]))
                {
                    $data['err_uname'] = "Username can not be empty";
                    $hasNoError = false;
                }
                else
                {
                    $uname = sanitizer($infoArray[1]);
        
                    $un = validateUsername($uname);
                    if($un !== true)
                    {
                        $data['err_uname'] = $un;
                        $hasNoError = false;
                    }
                    else
                    {
                        if(!isAvailable($uname))
                        {
                            $data['err_uname'] = "Opps! username is already taken...";
                            $hasNoError = false;
                        }
                    }
                }
                if(empty($infoArray[2]))
                {
                    $data['err_email'] = "Email can not be empty";
                    $hasNoError = false;
                }
                else if(!filter_var($infoArray[2], FILTER_VALIDATE_EMAIL))
                {			
                    
                    $data['err_email'] = "Email ID is not valid";
                    $hasNoError = false;
                }
                else
                {
                    $email = sanitizer($infoArray[2]);
                    if(!emailAvailable($email))
                    {
                        $data['err_email'] = "Opps! There is already an account associated with this email...";
                        $hasNoError = false;
                    }
                }
        
                if(empty($infoArray[3]))
                {
                    $data['err_pass'] = "Password can not be empty";
                    $hasNoError = false;
                }
                else
                {
                    $pass = sanitizer($infoArray[3]);
                    if(strlen($pass) < 6 || strlen($pass) > 32)
                    {
                        $data['err_pass'] = "Password must be in between 6 and 32 characters long";
                        $hasNoError = false;
                    }
                }
                if(empty($infoArray[4]))
                {
                    $data['err_cpass'] = "Password can not be empty";
                    $hasNoError = false;
                }
                else
                {
                    $cpass = sanitizer($infoArray[4]);
                }
                if(!empty($cpass) and !empty($pass))
                {
                    if($cpass !== $pass)
                    {
                        $data['err_cpass'] = "Passwords didn't match";
                        $hasNoError = false;
                    }
                    else
                    {
                        $mpass = md5($pass);
                    }
                }
        
                if($hasNoError)
                {
                    $query = "INSERT INTO profiles (username, name, email, pass, plan, `level`,`status`)
                        VALUES ('$uname', '$name', '$email','$mpass','0','0','0')";
                    execute($query);
                    $query = "INSERT INTO permission (username) values('$uname');";
                    execute($query);

        
                    if(autoLogin($uname,$pass) === true )
                    {
                        $data['success'] = 'true';
                    }
                }

            }
            else
            {
                $data['success'] = 'false';
                $data['message'] = 'Something went wrong here';
            }

        }
        else
        {
            //not array
            $data['success'] = 'false';
            $data['message'] = 'Something went wrong';
        }

        echo json_encode($data);

    }
    
?>