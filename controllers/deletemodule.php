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

    if(isset($_POST['delete']))
    {
        if(!empty($_POST['delete']))
        {
            $delete = sanitizer($_POST['delete']);
            if($delete == 'note')
            {
                try
                {
                    $noteID = sanitizer($_POST['noteID']);
                    $query = "DELETE from notes where noteID='$noteID' and noteOwner='$loggedUser'";
                    execute($query);
                    //stat update
                    $datestamp = time();
                    $query = "INSERT INTO stat (datestamp,username,notedelete) VALUES('$datestamp','$loggedUser', '1');";
                    execute($query);

                    $jsn['success'] = 'true';
                }
                catch(Exception $e)
                {
                    $jsn['success'] = 'false';
                }
            }
            elseif($delete == 'file')
            {
                try
                {
                    $fileID = sanitizer($_POST['fileID']);
                    $query = "DELETE from files where fileID='$fileID' and fileOwner='$loggedUser'";
                    execute($query);
                    //fwork
                    
                    if(file_exists(dirname(__FILE__).'/../models/upload/'.$fileID))
                    {
                        unlink(dirname(__FILE__).'/../models/upload/'.$fileID); //deleting file from server
                    }
                    //stat update
                    $datestamp = time();
                    $query = "INSERT INTO stat (datestamp,username,filedelete) VALUES('$datestamp','$loggedUser', '1');";
                    execute($query);
                    $jsn['success'] = 'true';
                }
                catch(Exception $e)
                {
                    $jsn['success'] = 'false';
                }
            }
            elseif($delete == 'url')
            {
                try
                {
                    $surl = sanitizer($_POST['surl']);
                    //it will delete record for logged user
                    $query = "DELETE from urlmap where surl='$surl' and urlOwner='$loggedUser'";
                    execute($query); //removing record from urlmap
                    //stat update
                    $datestamp = time();
                    $query = "INSERT INTO stat (datestamp,username,urldelete) VALUES('$datestamp','$loggedUser', '1');";
                    execute($query);

                    //check if any other record exists for another owner
                    $query = "SELECT * FROM urlmap WHERE surl='$surl'";
                    $result=get($query);

                    if(mysqli_num_rows($result) > 0)
                    {
                       //we do nothing lol
                    }
                    else
                    {
                        $query = "DELETE from urls where surl='$surl'";
                        execute($query); //removing record from urls
                    }
                    $jsn['success'] = 'true';
                }
                catch(Exception $e)
                {
                    $jsn['success'] = 'false';
                }
            }
        }

        echo json_encode($jsn);
    }
?>