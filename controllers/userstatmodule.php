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

    function getCounts($thing, $username="")
    {
        global $loggedUser;
        if($username!="")
        {
            $loggedUser = $username;
        }
        // echo $loggedUser;
        $extended = "";
        switch ($thing) {
            case 'note':
                # code...
                $thingID = 'noteID';
                $thingTable  = 'notes';
                $thingOwner = 'noteOwner';
            break;
            case 'pnote':
                # code...
                $thingID = 'noteID';
                $thingTable  = 'notes';
                $thingOwner = 'noteOwner';
                $extended = "and notePrivacy='1'";
            break;
            case 'file':
                # code...
                $thingID = 'fileID';
                $thingTable  = 'files';
                $thingOwner = 'fileOwner';
            break;
            case 'pfile':
                # code...
                $thingID = 'fileID';
                $thingTable  = 'files';
                $thingOwner = 'fileOwner';
                $extended = "and filePrivacy='1'";
            break;
            case 'url':
                # code...
                $thingID = 'surl';
                $thingTable  = 'urlMap';
                $thingOwner = 'urlOwner';
            break;
            
            default:
                # code...
                break;
            }
        $query = "SELECT $thingID from $thingTable where $thingOwner='$loggedUser' $extended;";
        try
        {
            return mysqli_num_rows(get($query));
        }
        catch(Error $e)
        {
            return false;
        }
    }

    function getFileSIze($username = "")
    {
        global $loggedUser;
        if($username!="")
        {
            $loggedUser = $username;
        }

        $query = "SELECT SUM(filesize) AS totalsize FROM files where fileOwner='$loggedUser';";
        try
        {
            $res = get($query);
            if($res === false )
            {
                return false;
            }
            else
            {
                $result = mysqli_fetch_assoc($res);
                return (int) $result['totalsize'];
            }
        }
        catch(Error $e)
        {
            return false;
        }
    }
?>