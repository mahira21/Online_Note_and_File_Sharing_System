<?php
    session_start();
    require_once dirname(__FILE__).'/../models/db/dbcon.php';
    require_once dirname(__FILE__).'/uniqstringgeneratormodule.php';

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

    function checkRecord($uname,$code)
    {
        try
        {
            $query = "SELECT * FROM passreset WHERE username='$uname' and code='$code' and UNIX_TIMESTAMP() <= expire  and status='1'";
            $result=get($query);
            
			if(mysqli_num_rows($result) > 0)
			{
                return true;
			}
			else
			{
                return false;
			}
        }
        catch(Error $e)
        {
            return false;
        }
    }

    function updatePass($uname,$pass,$code)
    {

        $pass = md5($pass);

        $query = "Update profiles set pass='$pass' where username='$uname';";
        $query2 = "Update passreset set status='0' where username='$uname' and code='$code';";
        try
        {
            execute($query);
            execute($query2);
            return true;
        }
        catch(Error $e)
        {
            return false;
        }
    }


    if(isset($_POST['uname']) && isset($_POST['code']) && isset($_POST['pass']) && isset($_POST['cpass']))
    {
        $data = array();
        $hasNoError = true;
        
        $uname = sanitizer($_POST['uname']);
        $code = sanitizer($_POST['code']);
        $pass = $_POST['pass'];
        $cpass = $_POST['cpass'];

        if(!empty($uname) && !empty($code) && !empty($pass) && !empty($cpass) )
        {
            if(checkRecord($uname,$code) === true)
            {
                
                if(strlen($pass) >= 6 && strlen($cpass) >= 6 )
                {
                    if($pass === $cpass)
                    {
                        //update pass
                        if(updatePass($uname,$pass,$code) === true )
                        {
                            $data['success'] = 'true';
                        }
                    }
                    else
                    {
                        //pass did not match
                        $data['err_pass'] = "Passwords didn't match";
                    }
                }
                else
                {
                    //too small pass
                    $data['err_pass'] = 'Password length must be in between 6 to 32 characters';
                }
            }
            else
            {
                //code is not working
                $data['err_code'] = 'Your code is not valid';
            }
        }
        else
        {
            //empty fields
            $data['err_field'] = 'Fields can not be empty';
        }
        echo json_encode($data);
    }
    
?>