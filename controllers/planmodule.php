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

    function getCurrentPlanDB($username = "")
    {
        global $loggedUser;
        if($username!= "")
        {
            $loggedUser = $username;
        }
        $query = "SELECT plan from profiles where username='$loggedUser';";
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
    function getCurrentPlan()
    {
        $currentPlan = getCurrentPlanDB();
        if($currentPlan !== false) return $currentPlan;
        else return -1;
    }

    function getLimit($thing)
    {
        $currentPlan = getCurrentPlan();
        if($currentPlan === 0)
        {
            switch ($thing) {
                case 'note':
                    return 50;
                    break;
                case 'file':
                    return 10240;
                    break;
                case 'url':
                    return 50;
                    break;
                default:
                    break;
            }
        }
        elseif($currentPlan === 1)
        {
            switch ($thing) {
                case 'note':
                    return 200;
                    break;
                case 'file':
                    return 20480;
                    break;
                case 'url':
                    return 200;
                    break;
                default:
                    break;
            }
        }
        elseif($currentPlan === 2)
        {
            switch ($thing) {
                case 'note':
                    return 5000;
                    break;
                case 'file':
                    return 102400;
                    break;
                case 'url':
                    return 5000;
                    break;
                default:
                    break;
            }
        }
        else
        {
            return 0;
        }
    }
?>


