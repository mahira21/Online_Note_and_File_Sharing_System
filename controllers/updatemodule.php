<?php
    session_start();
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
    
    $jsn = array();
    $jsn['success'] = 'false';

    if(isset($_POST['update']))
    {
        if(!empty($_POST['update']))
        {
            $update = sanitizer($_POST['update']);
            if($update == 'filePrivacy')
            {
                try
                {
                    $fileID = sanitizer($_POST['fileID']);
                    $filePrivacy = sanitizer($_POST['filePrivacy']);
                    $query = "UPDATE files set filePrivacy='$filePrivacy' where fileID='$fileID' and fileOwner='$loggedUser'";
                    execute($query);
                    $jsn['success'] = 'true';
                }
                catch(Error $e)
                {
                    $jsn['success'] = 'false';
                }
            }
        }

        echo json_encode($jsn);
    }
?>