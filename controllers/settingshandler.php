<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once dirname(__FILE__).'/../models/db/dbcon.php';


    $loggedUser = "";

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
        header('Location:../login');
        exit();
    }

    function getInfo()
    {
        global $loggedUser;
        $personalInfoArray = array();
        $query = "SELECT * from profiles where username='$loggedUser';";
        try
        {
            $result = get($query);
            if($result == false) return false;
            if(mysqli_num_rows($result) > 0)
            {
                return mysqli_fetch_assoc($result);
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


    //// update personal info
    function updatePersonalInfo($via,$passchange = false)
    {
        global $loggedUser;
        $query = "UPDATE profiles set name='$via[0]' , email='$via[1]' where username='$loggedUser'";
        if($passchange)
        {
            $pass = md5($via[4]);
            $query = "UPDATE profiles set name='$via[0]' , email='$via[1]', pass='$pass' where username='$loggedUser'";
        }
        try
        {            
            execute($query);
            return true;
        }
        catch(Error $e)
        {
            return false;
        }
    }


    function matchPass($opass)
    {
        global $loggedUser;

        $opass = md5($opass);

        $query = "SELECT pass from profiles where username='$loggedUser';";
        try
        {
            $result = get($query);
            if($result == false) return false;
            if(mysqli_num_rows($result) > 0)
            {
                $res =  mysqli_fetch_row($result);

                if($opass != $res[0])
                {
                    return false;
                }
                else
                {
                    return true;
                }
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

    function emailAvailable($email)
    {
        global $loggedUser;
        $query = "SELECT * from profiles where email='$email'";
        $result = get($query);
        if(mysqli_num_rows($result)>0)
        {
            $row = mysqli_fetch_assoc($result);
            if($row['username'] === $loggedUser)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return true;
        }
    }

    function validate($irray, &$data, $passchange = false)
    {
        $con = getCon();
        $infoArray = array();
        for ($i=0; $i < count($irray); $i++)
        { 
            # code...
            $infoArray[$i] = mysqli_real_escape_string($con, trim(htmlspecialchars($irray[$i])));
        }
        $error = array();
        $name = preg_replace('/\s\s+/', ' ', $infoArray[0]);
        if(empty($name))
        {
            $error[0] = "please enter your name above";
        }
        elseif(!ctype_alpha(str_replace(' ', '', $name)))
        {
            $error[0] = "please enter letters and Space only (e.g. Abcd Efgh)";
        }
        else
        {

        }
        
        $email = $infoArray[1];
        if(empty($email))
        {
            $error[1] = "please enter your email above";
        }
        elseif(emailAvailable($email) === false)
        {
            $error[1] = "There is already an account with the email";
        }
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $error[1] = "please enter valid email only";
        }
        else
        {

        }

        $opass = $infoArray[2];

        if(empty($opass))
        {
            $error[2] = "please enter your password above";
        }
        else if(!matchPass($opass))
        {
            $error[2] = "please enter correct password";
        }
        else
        {

        }
        if($passchange === true)
        {
            $npass = $infoArray[4];
            if(empty($npass))
            {
                $error[3] = "please enter your new password above";
            }
            else if(strlen($npass) < 4)
            {
                $error[3] = "please make sure your password is minimum 4 digit";
            }
            else
            {
    
            }
            $cnpass = $infoArray[5];
            if(empty($cnpass))
            {
                $error[4] = "please re-enter your new password above";
            }
            else if($npass !== $cnpass)
            {
                $error[4] = "passwords didn't match";
            }
            else
            {
    
            }

        }
        $data['errors'] = $error;
        if(count($error)>0)
        {
            return false;
        }
        else
        {
            return $infoArray;
        }
    }


    if(isset($_POST['infoArray']))
    {
        $data = array();

        $infoArray = $_POST['infoArray'];
        if(is_array($infoArray))
        {
            if(count($infoArray)==4)
            {
                //sanitize, validate, update
                $validatedInfoArray = validate($infoArray,$data);
                if(is_array($validatedInfoArray))
                {
                    if(updatePersonalInfo($validatedInfoArray))
                    {
                        $data['success'] = 'true';
                    }
                    else
                    {
                        $data['success'] = 'false';
                        $data['message'] = 'Something went wrong here';
                    }
                }
                else
                {
                    //eror in the input fields
                }

            }
            elseif(count($infoArray)==6)
            {
                $validatedInfoArray = validate($infoArray,$data,true);
                if(is_array($validatedInfoArray))
                {
                    if(updatePersonalInfo($validatedInfoArray,true))
                    {
                        $data['success'] = 'true';
                    }
                    else
                    {
                        $data['success'] = 'false';
                        $data['message'] = 'Something went wrong here';
                    }
                }
                else
                {
                    //eror in the input fields
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

    function hasExistingCPReq($calledfor)
    {
        date_default_timezone_set("Asia/Dhaka");
        global $loggedUser;

        $query = "SELECT * from cpreq where username='$loggedUser';";
        try
        {
            $result = get($query);
            if($result == false) return false;
            if(mysqli_num_rows($result) > 0)
            {
                $res =  mysqli_fetch_assoc($result);
                $unlockUnixTime = $res['reqDate'] + 604800;
                
                
                if($unlockUnixTime <= time())
                {
                    //delete prev req
                    try
                    {
                        $dquery = "DELETE from cpreq where username='$loggedUser'";
                        execute($dquery);
                        return 'no';
                    }
                    catch(Error $e)
                    {
                        //db error while deleting
                        return false;
                    }
                }
                else
                {
                    if($calledfor === 'res') return $res;
                    elseif($calledfor === 'check') return 'yes';
                }
                
            }
            else
            {
                return "no";
            }
        }
        catch(Error $e)
        {
            return false;
        }
    }


    function cpReqCheck()
    {
        date_default_timezone_set("Asia/Dhaka");
        global $loggedUser;
        $cpinfo = array();
        
        $res = hasExistingCPReq('res');

        if($res!== false && $res!== 'no' && is_array($res))
        {
            $cpinfo['dp'] = $res['plan'];
            $requts =  $res['reqDate'];
            $unlockuts = $requts + 604800;
            $cpinfo['unlockDate'] = date('d/m/Y h:i:s a',$unlockuts);
            $cpinfo['actions'] = $res['actions'];
            return $cpinfo;
        }
        else
        {
            return false;
        }
    }

    if(isset($_POST['fetchPersonal']))
    {
        $data = array();

        $result = getInfo();

        if($result !== false)
        {
            $info = array();
            $info['name'] = $result['name'];
            $info['uname'] = $result['username'];
            $info['email'] = $result['email'];
            $info['plan'] = $result['plan'];
            $data['info'] = $info;
        }
        echo json_encode($data);
    }
    if(isset($_POST['fetchPlan']))
    {
        $data = array();
        $cpinfo = cpReqCheck();
        if($cpinfo !== false && is_array($cpinfo))
        {
            $data['cpinfo'] = $cpinfo;
        }
        echo json_encode($data);
    }



    function matchPlan($desiredPlan)
    {
        global $loggedUser;

        $query = "SELECT plan from profiles where username='$loggedUser';";
        try
        {
            $result = get($query);
            if($result == false) return false;
            if(mysqli_num_rows($result) > 0)
            {
                $res =  mysqli_fetch_row($result);

                if($desiredPlan == $res[0])
                {
                    return false;
                }
                else
                {
                    return true;
                }
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

    if(isset($_POST['requestPlanChange']))
    {
        date_default_timezone_set("Asia/Dhaka");

        global $loggedUser;
        $reqDate = time();

        if(!empty($_POST['requestPlanChange']))
        {
            $desiredPlan = htmlspecialchars(trim($_POST['requestPlanChange']));
            $dp;
            if($desiredPlan == 'basic') $dp = 0;
            elseif($desiredPlan == 'pro') $dp = 1;
            elseif($desiredPlan == 'ultra') $dp = 2;
            else
            {
                //tried to manipulate string
                $data['failure'] = "Something went wrong";
                echo json_encode($data);
                return;
            }
            if(matchPlan($dp))
            {
                
                $hasExistingReq = hasExistingCPReq('check');
                if($hasExistingReq === 'no')
                {
                    $query = "INSERT INTO cpreq (username,plan,reqDate,actions) VALUES ('$loggedUser','$dp','$reqDate',0)";
                    try
                    {
                        execute($query);
                        $data['success'] = "Your request has been Placed for Admin Aprooval";
                    }
                    catch(Error $e)
                    {
                        //db error match plan
                        $data['failure'] = "Something went wrong";
                    }
                }
                elseif($hasExistingReq === 'yes')
                {
                    $data['hasExistingReq'] = "You already have a request pending";
                }
                else
                {
                    //db error check existing req
                    $data['failure'] = "Something went wrong";
                }
            }
            else
            {
                //already in this plan
                $data['failure'] = "Something went wrong";
            }

        }
        else
        {
            //empty manipulated string
            $data['failure'] = "Something went wrong";

        }
        echo json_encode($data);
    }

?>