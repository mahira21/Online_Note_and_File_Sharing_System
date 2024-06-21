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

    function getPermit($fuse)
    {
        global $loggedUser;
        
        $query = "SELECT $fuse from permission where username='$loggedUser';";
        try
        {
            $result = get($query);
            if($result == false) return false;
            if(mysqli_num_rows($result) > 0)
            {
                $res =  mysqli_fetch_row($result);
                return (int)$res[0];
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

?>


