<?php
    session_start();
    require_once dirname(__FILE__).'/../models/db/dbcon.php';
    $uname = "";
    $err_uname = "";
    $pass = "";
    $err_pass = "";
    $err_profile = "";
    $data = array();

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

    if(isset($_POST['login']))
    {
        $hasNoError = true;
        $un = sanitizer($_POST['uname']);
        if(empty($un))
        {
            $err_uname = "Username can not be empty";
            $hasNoError = false;
        }
        else
        {
            $uname = $un;
        }

        if(empty($_POST['pass']))
        {
            $err_pass = "Password can not be empty";
            $hasNoError = false;
        }
        else
        {
            $pass = md5($_POST['pass']);
        }
        if($hasNoError)
        {
            $query = "SELECT * FROM profiles WHERE username='$uname' AND pass='$pass'";
			$result=get($query);
			if(mysqli_num_rows($result) > 0)
			{
                $res=mysqli_fetch_assoc($result);
                if($res['level'] == 1)
                {
                    $_SESSION['admin'] = $res;
                    $loggedAdmin = $res['username'];
                    $data['loggedAdmin'] = $loggedAdmin;
                }
                else
                {
                    $_SESSION['user'] = $res;
                    $loggedUser = $res['username'];
                    $data['loggedUser'] = $loggedUser;
                }
                unset($_POST['login']);
                
			}
			else
			{
                $err_profile = "No user found associated with the username and password";
			}
        }
        $data['errUname'] = $err_uname;
        $data['errPass'] = $err_pass;
        $data['errProfile'] = $err_profile;
    
        echo json_encode($data);
    }

?>



