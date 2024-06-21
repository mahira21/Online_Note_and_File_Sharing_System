<?php
    require_once dirname(__FILE__).'/../models/db/dbcon.php';
    require_once dirname(__FILE__).'/uniqstringgeneratormodule.php';    $loggedUser = "";
    $noteID = "";
    $noteText = "";
    $noteOwner = "guest";
    $notePrivacy = 0;
    $expiration = "";
    $lastEdited = "";
    $lastVisited = "";

    $privacypub = "";
    $privacypriv = "";
    $privacyview = "";
    $oldpvc = "";
    $exp = array("","","","");
    $xpire = 3;

    if(isset($_SESSION['user'])) 
    {
        $user = $_SESSION['user'];
        if(isset($user['username']))
        {
            $loggedUser = $user['username'];
        }
    }
    
    function prepareNote($note,$noteID,$owner)
    {
        global $noteText, $noteOwner, $notePrivacy, $expiration, $lastVisited, $lastVisited, $privacypub, $privacypriv,$privacyview,$oldpvc, $exp;
        
        $xpire = $note['xpire'];

        $lastVisited = time();

        $expiration = strtotime("+$xpire days", time());


        $query2 = "UPDATE notes SET lastVisited = '$lastVisited', expiration ='$expiration' WHERE noteID='$noteID'";
        execute($query2);

        if($notePrivacy == 0)
        {
            $privacypub = "checked";
            $oldpvc = 0;
        }
        elseif($notePrivacy == 2)
        {
            $privacyview = "checked";
            $oldpvc = 2;
        }
        else
        {
            $privacypriv = "checked";
            $oldpvc = 1;
        }
        
        $noteOwner = $owner;

        $noteText = $note['text'];
        if($xpire == 3)
        {
            $exp[1] = "selected";
        }
        if($xpire == 7)
        {
            $exp[2] = "selected";
        }
        if($xpire == 30)
        {
            $exp[3] = "selected";
        }
        if($xpire > 30)
        {
            $exp[0] = "selected";
        }
    }



    if(isset($_GET['id']))
    {
        if(empty($_GET['id']))
        {
            $noteID = generateUniq('notes','noteID',6);
            header("Location:./$noteID");
        }
        else
        {
            
            $noteID = htmlspecialchars($_GET['id']);

            $query = "SELECT * FROM notes WHERE noteID='$noteID'";
            
            $result=get($query);
            

            if(mysqli_num_rows($result) > 0)
            {
                $note=mysqli_fetch_assoc($result);
                $notePrivacy = $note['notePrivacy'];
                $owner = $note['noteOwner'];

                if($notePrivacy==0 || $notePrivacy == 2)
                {
                    prepareNote($note,$noteID,$owner);
                }
                else if($notePrivacy==1)
                {
                    if(empty($loggedUser))
                    {
                        //ask to login
                        header("Location:login");
                    }
                    else
                    {
                        if($owner == $loggedUser)
                        {
                            prepareNote($note,$noteID,$owner);
                        }
                        else
                        {
                            session_destroy();
                            header("Location:login");
                        }
                    }
                }
            }
            else
            {
                //create new note against the id

                $lastEdited = time();

                $lastVisited = time();

                $expiration = strtotime("+$xpire days", time());

                $privacy = 0;

                if(empty($loggedUser))
                {
                    //our default note template
                }
                else
                {
                    //users preferred template
                    $xpire = 3650;

                    $expiration = strtotime("+$xpire days", time());

                    $noteOwner = $loggedUser;

                }
                $query = "INSERT INTO notes (noteID, notePrivacy,noteOwner,lastEdited,lastVisited,xpire,expiration,text)
                VALUES ('$noteID', '$privacy', '$noteOwner','$lastEdited','$lastVisited','$xpire','$expiration','$noteText')";
                execute($query);

                //stat update
                $datestamp = time();
                $query = "INSERT INTO stat (datestamp,username,notecreate) VALUES('$datestamp','$loggedUser', '1');";
                execute($query);

                $query = "SELECT * FROM notes WHERE noteID='$noteID'";
                $result=get($query);
                if(mysqli_num_rows($result) > 0)
                {
                    $note=mysqli_fetch_assoc($result);
                    prepareNote($note,$noteID,$noteOwner);
                }
            }
             
        }
    }


?>