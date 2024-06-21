<?php
    session_start();

    require_once dirname(__FILE__).'/../models/db/dbcon.php';
    require_once dirname(__FILE__).'/variables.php';

    $loggedUser = "";
    $surl = "";
    $data = array();

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

    function loadAttach($msgId)
    {
        $resarr = array();

        $query = "SELECT * from messages where msg_id = '$msgId' and attach='1' order by id desc;";

        $result = get($query);

        $resarr3 = array();

        if($result !== false)
        {
            while($row = mysqli_fetch_assoc($result))
            {
                $resarr3[] = $row;
            }
        }

        $response = "";

        foreach($resarr3 as $res)
        {
            $msg = $res['msg'];
            $response .= "<div class='img-thumb-wrapper'>
                        <div class='img-thumb'>
                            <img src='models/images/$msg' alt=''/>
                        </div>
                    </div>";
        }

        return $response;
    }

    if(isset($_POST['refreshAttach']))
    {
        $data = array();
        $msgId = sanitizer($_POST['refreshAttach']);
        if(!empty($msgId))
        {
            $response = loadAttach($msgId);
            $data['response'] = $response;
            // if(!empty($response))
            // {

            // }
        }

        echo json_encode($data);
    }
    function loadThumbs()
    {
        $resarr = array();

        $query = "SELECT 
                    M.msg_id, M.msg, M.attach
                    FROM messages M
                    INNER JOIN 
                    (
                        SELECT 
                        MAX(id) AS last_id_of_conversation,
                        msg_id, msg, attach
                        FROM messages
                        GROUP BY msg_id
                    ) AS t
                    ON M.id = last_id_of_conversation order by M.id DESC";
        $result=get($query); 
        
        if($result !== false)
        {
            while($row = mysqli_fetch_assoc($result))
            {
                $resarr[] = $row;
            }
        }
        $response = "";

        foreach($resarr as $res)
        {
            $msg_id = $res['msg_id'];
            $lastmsg = $res['msg'];
            if($res['attach'] == 1)
            {
                $lastmsg = "ATTACHMENT";
            }

            $response .= "<div class='msg-thumb' data-msgid='$msg_id'>
                        <div class='thumb-username'>
                            $msg_id
                        </div>
                        <div class='thumb-lastmsg'>
                            $lastmsg
                        </div>
                    </div>";
        }
        return $response;
    }

    if(isset($_POST['refreshThumbs']))
    {
        $data = array();
        $msgId = sanitizer($_POST['refreshThumbs']);
        if(!empty($msgId))
        {
            $response = loadThumbs();
            if(!empty($response))
            {
                $data['response'] = $response;

            }
        }

        echo json_encode($data);
    }

    function loadConversations($msgId)
    {
        $query = "SELECT * from messages where msg_id = '$msgId' order by id desc;";

        $result = get($query);

        $resarr2 = array();

        if($result !== false)
        {
            while($row = mysqli_fetch_assoc($result))
            {
                $resarr2[] = $row;
            }
        }
        $response = "";

        foreach($resarr2 as $res)
        {
            $id = $res['id'];
            $msg_id = $res['msg_id'];
            $sender = $res['sender'];
            if($sender == 'admin')
            {
                $sender = 'server';
            }
            else
            {
                $sender = 'client';
            }
            $time = date('d/m/y h:m:s a',$res['sendtime']);
            $msg = $res['msg'];
            if($res['attach'] == 1){
                $msgI = "<img src='models/images/$msg' alt=''/>";
                $disp = "style='display:none'";
            }
            else
            {
                $msgI="";
                $disp = "";
            }

            $response .= "<div class='msg-block-wrapper'>
                        <div class='$sender msg-block'>
                                $msgI
                            <div class='msg-text-block'  $disp>
                                <div class='msg-action'>
                                    $id
                                </div>
                                <div class='msg-text'>
                                    $msg
                                </div>
                            </div>
                            <div class='msg-time'>
                                $time
                            </div>
                        </div>    
                    </div>";
            
        }
        return $response;
    }

    if(isset($_POST['refresh']))
    {
        $data = array();
        $msgId = sanitizer($_POST['refresh']);
        if(!empty($msgId))
        {
            $response = loadConversations($msgId);
            if(!empty($response))
            {
                $data['response'] = $response;

            }
        }

        echo json_encode($data);
    }

    if(isset($_POST['msgTxt']) && isset($_POST['msgId']))
    {
        $data = array();
        $msgTxt = sanitizer($_POST['msgTxt']);
        $msgId = sanitizer($_POST['msgId']);
        if(!empty($msgTxt) && !empty($msgId))
        {
            $time = time();
            $query = "INSERT INTO `messages`(`msg_id`, `sendtime`, `sender`, `attach`, `deleted`, `msg`)
             VALUES ('$msgId','$time','admin',0,0,'$msgTxt');";
            try
            {
                execute($query);
                $response = loadConversations($msgId);
                if(!empty($response))
                {
                    $data['response'] = $response;

                }
            }
            catch(Error $e)
            {

            }
        }

        echo json_encode($data);
    }
    if(isset($_FILES['file']['name'][0]) && isset($_GET['msgId']))  
    {  
         //echo 'OK';  
         $msgId = sanitizer($_GET['msgId']);

         foreach($_FILES['file']['name'] as $keys => $values)  
         {  
            $uniqfilename =  substr(md5(microtime()), 0, 31);
            if(move_uploaded_file($_FILES['file']['tmp_name'][$keys], dirname(__FILE__).'/../models/images/' . $uniqfilename))  
            {  
                $time = time();
                $query = "INSERT INTO `messages`(`msg_id`, `sendtime`, `sender`, `attach`, `deleted`, `msg`)
                VALUES ('$msgId','$time','admin',1,0,'$uniqfilename');";
                try
                {
                    execute($query);
                    $response = loadConversations($msgId);
                    if(!empty($response))
                    {
                        echo $response;

                    }
                }
                catch(Error $e)
                {

                }
            }
         }  
    }
    
    
?>