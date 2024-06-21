<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once dirname(__FILE__).'/../models/db/dbcon.php';
    require_once dirname(__FILE__).'/planmodule.php';
    require_once dirname(__FILE__).'/userstatmodule.php';
    require_once dirname(__FILE__).'/permissionmodule.php';



    $loggedUser = "";

    if(isset($_SESSION['user'])) 
    {
        $user = $_SESSION['user'];
        if(isset($user['username']))
        {
            $loggedUser = $user['username'];
        }
    }

    function alive()
    {
        if(isset($_SESSION['user'])) return true;
        else return false;
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

    function updateNoteText($noteText,$noteID,$xpire)
    {
        global $loggedUser;
        $noteOwner = empty($loggedUser)? "guest" : $loggedUser;
        $lastVisited = time();
        $lastEdited = time();
        $expiration = strtotime("+$xpire days", time());
        $query = "UPDATE notes SET noteOwner='$noteOwner', lastEdited='$lastEdited', xpire='$xpire', lastVisited = '$lastVisited', expiration ='$expiration', text='$noteText'  WHERE noteID='$noteID'";
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


    if(isset($_POST['updateNoteText']))
    {
        $data = array();

        $noteText = sanitizer($_POST['updateNoteText']);
        $noteID = sanitizer($_POST['noteID']);
        $xpire =  (int)sanitizer($_POST['xpire']);


        if(!empty($noteID) && !empty($xpire) )
        {
            if(updateNoteText($noteText,$noteID,$xpire) === true)
            {
                $data['success'] = 'true';
            }
            else
            {
                $data['error'] = "something went wrong";
            }
        }
        else
        {
            $data['error'] = "something went wrong";
        }
        echo json_encode($data);
    }

    function updatePrivacy($notePrivacy,$noteID,$xpire)
    {
        global $loggedUser;
        $noteOwner = empty($loggedUser)? "guest" : $loggedUser;
        $lastVisited = time();
        $lastEdited = time();
        $expiration = strtotime("+$xpire days", time());
        $query = "UPDATE notes SET notePrivacy='$notePrivacy', noteOwner='$noteOwner', lastEdited='$lastEdited', xpire='$xpire', lastVisited = '$lastVisited', expiration ='$expiration' WHERE noteID='$noteID'";
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


    if(isset($_POST['updateNotePrivacy']))
    {
        
        $data = array();
        if(!alive())
        {
            $data['loginfirst'] = "true";
            echo json_encode($data);
            return;
        }
        
        $con = getCon();
        $noteID = sanitizer($_POST['noteID']);
        $xpire =  (int)sanitizer($_POST['xpire']);
        
        if(/* !empty($_POST['updateNotePrivacy']) &&  */!empty($noteID))
        {
            if($_POST['updateNotePrivacy'] == '0')
            {
                if(updatePrivacy(0,$noteID,$xpire) === true)
                {
                    $data['success'] = 'true';
                }
                else
                {
                    $data['error'] = "something went wrong";
                }
            }
            elseif($_POST['updateNotePrivacy'] == '2')
            {
                if(updatePrivacy(2,$noteID,$xpire) === true)
                {
                    $data['success'] = 'true';
                }
                else
                {
                    $data['error'] = "something went wrong";
                }
            }
            elseif($_POST['updateNotePrivacy'] == '1')
            {
                $permit = getPermit('note');

                if($permit !== false && $permit == 1)
                {
                    $limit = getLimit('note');
                    $pnotecount = getCounts('pnote');
    
                    if($pnotecount < $limit)
                    {
                        if(updatePrivacy(1,$noteID,$xpire) === true)
                        {
                            $data['success'] = 'true';
                        }
                        else
                        {
                            $data['error'] = "something went wrong";
                        }
                    }
                    else
                    {
                        //limit exceeded
                        $data['limitError'] = "Private note limit exceeded";
                    }
                }
                else
                {
                    //no permission
                    $data['permitError'] = "You are not allowed to create private note";
                }

            }
            else
            {
                //privacy type error
                $data['error'] = "something went wrong";
            }
            echo json_encode($data);
        }
    }

    function updateExpiration($xpire,$noteID)
    {
        global $loggedUser;
        $noteOwner = empty($loggedUser)? "guest" : $loggedUser;
        $lastVisited = time();
        $lastEdited = time();
        $expiration = strtotime("+$xpire days", time());
        $query = "UPDATE notes SET noteOwner='$noteOwner', lastEdited='$lastEdited', xpire='$xpire', lastVisited = '$lastVisited', expiration ='$expiration' WHERE noteID='$noteID'";
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

    if(isset($_POST['updateNoteExpiration']))
    {
        $data = array();
        if(!alive())
        {
            $data['loginfirst'] = "true";
            echo json_encode($data);
            return;
        }

        $noteID = sanitizer($_POST['noteID']);
        $xpire = (int)sanitizer($_POST['updateNoteExpiration']);


        if(!empty($xpire) && !empty($noteID))
        {
            if($xpire == 3)
            {
                if(updateExpiration(3,$noteID) === true)
                {
                    $data['success'] = 'true';
                }
                else
                {
                    $data['error'] = "something went wrong";
                }
            }
            elseif($xpire == 7)
            {
                if(updateExpiration(7,$noteID) === true)
                {
                    $data['success'] = 'true';
                }
                else
                {
                    $data['error'] = "something went wrong";
                }
            }
            elseif($xpire == 30)
            {
                if(updateExpiration(30,$noteID) === true)
                {
                    $data['success'] = 'true';
                }
                else
                {
                    $data['error'] = "something went wrong";
                }
            }
            elseif($xpire >= 3650)
            {
                if(updateExpiration(3650,$noteID) === true)
                {
                    $data['success'] = 'true';
                }
                else
                {
                    $data['error'] = "something went wrong";
                }
            }
            
            else
            {
                //privacy type error
                $data['error'] = "something went wrong";
            }
            echo json_encode($data);
        }
    }
?>