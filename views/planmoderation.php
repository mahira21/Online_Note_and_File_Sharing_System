<?php
    session_start();

    require_once dirname(__FILE__).'/../models/db/dbcon.php';
    require_once dirname(__FILE__).'/../controllers/userstatmodule.php';
    require_once dirname(__FILE__).'/../controllers/planmodule.php';

    //preventing access from view route
    if (strpos($_SERVER['REQUEST_URI'], '/views/') !== false) {
        exit();
    }
    if(!isset($_SESSION['admin']))
    {
        header("Location:login");
    }

    date_default_timezone_set("Asia/Dhaka");

    $resarr = array();

    function shortDate($longDate)
    {
        return date('d/m/Y h:m A',$longDate);
    }

    function planInString($p)
    {
        switch ($p) {
            case '0':
                return 'basic';
                break;
            case '1':
                return 'pro';
                break;
            case '2':
                return 'ultra';
                break;
            
            default:
                # code...
                break;
        }
    }

    $query = "SELECT * FROM cpreq WHERE actions='0' order by star DESC, reqDate";
    $result=get($query);        

    if($result === false)
    {

    }
    elseif(mysqli_num_rows($result) > 0)
    {
        while($row = mysqli_fetch_assoc($result))
        {
            $resarr[] = $row;
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
        <link rel="stylesheet" href="views/styles/planmoderation.css" />
        <link rel="stylesheet" href="views/styles/throwlert.css" />


        <script src="views/js/jquery341.js"></script>
        <script src="views/js/throwlert.js" defer></script>
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
                    <div class="search-row">
                        <input type="text" placeholder="search for user" />
                    </div>
                    <div class="user-plate-row">
                    <?php
                        foreach($resarr as $key=>$res)
                        {
                            $username = $res['username'];
                            $cplan = planInString(getCurrentPlanDB($username));
                            $notecounts = getCounts('note',$username);
                            $urlcounts = getCounts('url',$username);
                            $filesize = getFileSize($username)."KB";
                            $reqDate = shortDate($res['reqDate']);
                            $star = $res['star'];
                            $dplan = planInString($res['plan']);

                            echo "<div class='user-plate'>
                                        <div class='row1'>
                                            <div class='date'>
                                                $reqDate
                                            </div>
                                            <div class='star-holder'>
                                                <button data-username='$username' data-val='$star'><i class='fas fa-star'></i></button>
                                            </div>
                                        </div>
                                        <div class='row2'>
                                            <div class='name-plan'>
                                                <div class='username'>
                                                    @$username
                                                </div>
                                                <div class='plan'>
                                                    <span>$cplan</span><span>to</span
                                                    ><span>$dplan</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='row3'>
                                            <div class='summary'>
                                                <span>$notecounts Notes</span>
                                                <span>$filesize Files</span>
                                                <span>$urlcounts URLs</span>
                                            </div>
                                            <div class='action-buttons-wrapper'>
                                                <div class='action-buttons-holder'>
                                                    <button class='action-button decline-button' data-username='$username'>
                                                        Decline
                                                    </button>
                                                    <button class='action-button approve-button' data-username='$username'>
                                                        Approve
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>";
                        }
                    
                    ?>    
                        
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
            $('.ad-side a').eq(1).css('background-color', '#555');
            $('.search-row input').keyup(function(){
                $.ajax({
                    url:'controllers/planmoderationhandler.php',
                    method:'POST',
                    data:{
                        searchByUser: $('.search-row input').val(),
                    },
                    success:function(data){
                        $('.user-plate-row').html(data);
                    },
                });
            });

            $('.user-plate-row').on('click', '.approve-button', function (){
                let that = $(this);
                $.ajax({
                    url:'controllers/planmoderationhandler.php',
                    method:'POST',
                    dataType:'JSON',
                    data:{
                        action: 1,
                        username:$(this).data('username'),
                    },
                    success:function(data){
                        if('success' in data){
                            throwlert(1,data.success);
                            that.closest('.user-plate').fadeOut();
                        }
                        else{
                            throwlert(0,'Something went wrong!');

                        }
                    },
                });
            });
            $('.user-plate-row').on('click', '.decline-button', function (){
                let that = $(this);
                $.ajax({
                    url:'controllers/planmoderationhandler.php',
                    method:'POST',
                    dataType:'JSON',
                    data:{
                        action: 2,
                        username:$(this).data('username'),
                    },
                    success:function(data){
                        if('success' in data){
                            throwlert(1,data.success);
                            that.closest('.user-plate').fadeOut();
                        }
                        else{
                            throwlert(0,'Something went wrong!');
                        }
                    },
                });
            });
            $('.user-plate-row').on('click', '.star-holder button', function (){
                
                let that = $(this);
                let cval = parseInt($(this).data('val'));
                let toval;
                if(cval == 1){
                    toval = 0;
                }else{
                    toval = 1;
                }
                $.ajax({
                    url:'controllers/planmoderationhandler.php',
                    method:'POST',
                    dataType:'JSON',
                    data:{
                        star: toval,
                        username:$(this).data('username'),
                    },
                    success:function(data){
                        if('success' in data){
                            that.data('val',`${toval}`);
                            that.attr('data-val',`${toval}`);
                        }
                        else{

                        }
                    },
                });
            });

        </script>
    </body>
</html>
