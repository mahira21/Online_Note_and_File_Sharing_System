<?php
        session_start();
        require_once dirname(__FILE__).'/../models/db/dbcon.php';
        require_once dirname(__FILE__).'/../controllers/variables.php';
    
        if (strpos($_SERVER['REQUEST_URI'], '/views/') !== false) {
            exit();
        }

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
            header("Location:login");
        }

        date_default_timezone_set("Asia/Dhaka");
    
        
        
    
        $query = "SELECT * from messages where msg_id = '$loggedUser' order by id desc;";
    
        $result = get($query);
    
        $resarr2 = array();
    
        if($result !== false)
        {
            while($row = mysqli_fetch_assoc($result))
            {
                $resarr2[] = $row;
            }
        }
        $query = "SELECT * from messages where msg_id = '$loggedUser' and attach='1' order by id desc;";

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NoteFused</title>
    <link rel="stylesheet" href="views/styles/all.css">
    <link rel="stylesheet" href="views/styles/throwlert.css">
    <link rel="stylesheet" href="views/styles/side2.css">
    <link rel="stylesheet" href="views/styles/base.css">
    <link rel="stylesheet" href="views/styles/optiontoggler.css">
    <link rel="stylesheet" href="views/styles/form.css">
    <link rel="stylesheet" href="views/styles/navbar.css">
    <link rel="stylesheet" href="views/styles/userdashcard.css">
    <link rel="stylesheet" href="views/styles/messages.css">
    <link rel="stylesheet" href="views/styles/contact2.css">
    <link rel="stylesheet" href="views/styles/sidebar.css">
    <link rel="stylesheet" href="views/styles/semiloader.css">


    <script src="views/js/jquery341.js"></script>
    <script src="views/js/throwlert.js" defer></script>
    <script src="views/js/shortnerfunctionality.js" defer></script>
    <script src="views/js/optiontogglerfunctionality.js" defer></script>
    <script src="views/js/navbarfunctionality.js" defer></script>
    <script src="views/js/userdashcardfunctionality.js" defer></script>
    <script src="views/js/contactfunctionality.js" defer></script>
    <script src="views/js/sidebar.js" defer></script>


</head>
<body>
    <div class="semiloader">
        <div class="one"></div>
        <div class="two"></div>
        <div class="three"></div>
        <div class="four"></div>
    </div>
    <?php require "sidebar.php"; ?>
    <div class="holder">
        <?php require "navbar.php"; ?>

        <div class="container">
            
            <div class="sidebar">
                <?php require "userdashcard.php"; ?>
            </div>
            <div class="fuse">
                <div class="mini-container">
                <div class="chat-pan-holder">
                    <div class="chat-pan">
                        <div class="chat-side">
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
                                    <?php echo $loggedUser; ?>
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
                                            $sender = 'client';
                                        }
                                        else
                                        {
                                            $sender = 'server';
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
                </div> <!-- mini-container -->       
            </div>
            <div class="alter-options">
                <div class="option-toggler" id="optionToggler">
                    <div class="create-link">
                        <button id="createNewLink" title="Shorten a URL"><i class="fas fa-link"></i></button>
                    </div>
                    <div class="create-file">
                        <button id="createNewFile" title="Upload a File"><i class="fas fa-file-archive"></i></button>
                    </div>
                    <div class="create-note">
                        <button id="createNewNote" title="Create another Note"><i class="fas fa-file-alt"></i></button>
                    </div>
                </div>
                <div class="expand-option">
                    <button id="expandOptions"><i class="fas fa-plus"></i></button>
                </div>
    
            </div>
        </div>
            
            
        </div>
    </div>
    <script>
            $('.chat-pan').on('click','img',function () {
                window.open($(this)[0].src, '_blank');
            });
            let msg_id = '<?php echo "$loggedUser"; ?>';
            console.log(msg_id);
    </script>
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
</body>

</html>