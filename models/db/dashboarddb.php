<?php
    require_once dirname(__FILE__).'/dbcon.php';

    function getFileSize()
    {
        $query = "SELECT SUM(filesize) AS totalsize FROM files;";
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
                return (double) $result['totalsize'];
            }
        }
        catch(Error $e)
        {
            return false;
        }
    }
    function getCounts($thing)
    {
        global $loggedUser;
        // echo $loggedUser;
        $extended = "";
        switch ($thing) {
            case 'note':
                # code...
                $thingID = 'noteID';
                $thingTable  = 'notes';
            break;
            case 'pnote':
                # code...
                $thingID = 'noteID';
                $thingTable  = 'notes';
                $extended = "where notePrivacy='1'";
            break;
            case 'gnote':
                # code...
                $thingID = 'noteID';
                $thingTable  = 'notes';
                $extended = "where noteOwner='guest'";
            break;
            case 'file':
                # code...
                $thingID = 'fileID';
                $thingTable  = 'files';
            break;
            case 'pfile':
                # code...
                $thingID = 'fileID';
                $thingTable  = 'files';
                $extended = "where filePrivacy='1'";
            break;
            case 'url':
                # code...
                $thingID = 'surl';
                $thingTable  = 'urls';
            break;
            
            default:
                # code...
                break;
            }
        $query = "SELECT $thingID from $thingTable $extended;";
        try
        {
            return (int) mysqli_num_rows(get($query));
        }
        catch(Error $e)
        {
            return false;
        }
    }
?>