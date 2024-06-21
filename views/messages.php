<?php
    session_start();
    require_once dirname(__FILE__).'/../models/db/dbcon.php';
    require_once dirname(__FILE__).'/../controllers/variables.php';

    if (strpos($_SERVER['REQUEST_URI'], '/views/') !== false) {
        exit();
    }
    date_default_timezone_set("Asia/Dhaka");

    
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
    $res = $resarr[0];

    $msg_id_def = $res['msg_id'];

    $query = "SELECT * from messages where msg_id = '$msg_id_def' order by id desc;";

    $result = get($query);

    $resarr2 = array();

    if($result !== false)
    {
        while($row = mysqli_fetch_assoc($result))
        {
            $resarr2[] = $row;
        }
    }
    $query = "SELECT * from messages where msg_id = '$msg_id_def' and attach='1' order by id desc;";

    $result = get($query);

    $resarr3 = array();

    if($result !== false)
    {
        while($row = mysqli_fetch_assoc($result))
        {
            $resarr3[] = $row;
        }
    }


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Document</title>
        <link rel="stylesheet" href="views/styles/all.css" />
        <link rel="stylesheet" href="views/styles/adbase.css" />
        <link rel="stylesheet" href="views/styles/messages.css" />
        <link rel="stylesheet" href="views/styles/throwlert.css" />


        <script src="views/js/jquery341.js"></script>
        <script src="views/js/throwlert.js" defer></script>
        <script src="views/js/messagefunctionality.js" defer></script>
        <script
            type="text/javascript"
            src="https://www.gstatic.com/charts/loader.js"
        ></script>
        <script></script>
    </head>
    <body>
        <section class="ad-holder">
            <?php require "adsidebar.php"; ?>
            <section class="ad-container">
                <div class="mini-ad-container">
                <div class="chat-pan-holder">
                    <div class="chat-pan">
                        <div class="chat-side">
                            <div class="chat-side-content">
                            <?php
                                foreach($resarr as $res)
                                {
                                    $msg_id = $res['msg_id'];
                                    $lastmsg = $res['msg'];
                                    if($res['attach'] == 1)
                                    {
                                        $lastmsg = "ATTACHMENT";
                                    }
            
                                    echo    "<div class='msg-thumb' data-msgid='$msg_id'>
                                                <div class='thumb-username'>
                                                    $msg_id
                                                </div>
                                                <div class='thumb-lastmsg'>
                                                    $lastmsg
                                                </div>
                                            </div>";
                                }
                            ?>
                            </div>
                            <div class="chat-side-attach-wrapper">
                                <div class="side-title">Attachments</div>
                                <div class="chat-side-attach">
                                    <?php
                                foreach($resarr3 as $res)
                                {
                                    $msg = $res['msg'];
                                    echo    "<div class='img-thumb-wrapper'>
                                                <div class='img-thumb'>
                                                    <img src='models/images/$msg' alt=''/>
                                                </div>
                                            </div>";
                                }
                                ?>
                                </div>
                            </div>
                        </div>
                        <div class="chat-body">
                            <div class="chat-heading">
                                <div class="chat-heading-username">
                                    <?php echo $msg_id_def; ?>
                                </div>
                            </div>
                            <div class="chat-room">
                                <?php
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
            
                                        echo "<div class='msg-block-wrapper'>
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
            
                                ?>    
                            </div>
                            <div class="chat-attachment">
                                <button id="clearattach"><i class="fas fa-times"></i></button>
                                <div class="chat-attach-wrapper">
                                
                                </div>
                            </div>
                            
                            <div class="chat-input-sec-wrapper">
                                <div class="chat-input-sec">
                                    <!-- <button><i class="fas fa-file-image"></i></button> -->
                                    <input type="file" name="file[]" id="choose"  accept="image/x-png,image/gif,image/jpeg">
                                    <label for="choose" class="choose-label"><i class="fas fa-file-image"></i></label>
                                    <form action="" id="chatform"  autocomplete="off">
                                        <input type="text" placeholder="type your message" id="msg-txt-field"/>
                                        <button id="sendBtn"><i class="fas fa-paper-plane"></i></button>
                                    </form>
                                </div> <!-- chat-input-sec -->
                            </div> <!-- chat-input-sec-wrapper -->
                        </div>
                    </div>
                </div>
                </div>
            </section>
        </section>
        <div class="throwlert">
            <div class="alert-box">
                <div class="alert-close-button">
                    <button><i class="fas fa-times"></i></button>
                </div>
                <div class="alert-type type-success">
                    <i class="far fa-check-circle"></i>
                </div>
                <div class="alert-type type-error">
                    <i class="far fa-times-circle"></i>
                </div>
                <div class="alert-dialog"></div>
            </div>
        </div>
        <script>
            $('.sidebar-toggler').click(function () {
                $('.ad-sidebar').toggleClass('ad-sidebar-active');
            });

            $('.ad-side a').eq(6).css('background-color', '#555');

            $('.chat-pan').on('click','img',function () {
                window.open($(this)[0].src, '_blank');
            });
            let msg_id = '<?php echo $msg_id; ?>';
            console.log(msg_id);
        </script>
    </body>
</html>
