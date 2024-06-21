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


    $results_per_page = 10;

    $query= "SELECT * FROM permission;";
    $result = get($query);
    $number_of_results = mysqli_num_rows($result);

    $number_of_pages = ceil($number_of_results/$results_per_page);


    if (!isset($_GET['p']))
    {
        $page = 1;
    }
    else
    {
        $pGet = 1;
        try
        {
            $pGet = (int)$_GET['p'];
        }
        catch(Error $e)
        {

        }
        if($pGet < 1)
        {
            $page = 1;
        }
        elseif($pGet > $number_of_pages)
        {
            $page = $number_of_pages;
        }
        else
        {
            $page = $pGet;
        }
    }

    $this_page_first_result = ($page-1)*$results_per_page;


    $resarr = array();

    $query = "SELECT * FROM permission order by username LIMIT $this_page_first_result , $results_per_page";
    $result=get($query); 
    
    if($result !== false)
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
        <link rel="stylesheet" href="views/styles/pagination.css">
        <link rel="stylesheet" href="views/styles/permissionmoderation.css" />
        <link rel="stylesheet" href="views/styles/throwlert.css" />
        <link rel="stylesheet" href="views/styles/semiloader.css">



        <script src="views/js/jquery341.js"></script>
        <script src="views/js/throwlert.js" defer></script>
        <script
            type="text/javascript"
            src="https://www.gstatic.com/charts/loader.js"
        ></script>
        <script></script>
    </head>
    <body>
        <div class="semiloader">
            <div class="one"></div>
            <div class="two"></div>
            <div class="three"></div>
            <div class="four"></div>
        </div>
        <section class="ad-holder">
            <?php require "adsidebar.php"; ?>
            <section class="ad-container">
                <div class="mini-ad-container">
                    <div class="search-row">
                        <input type="text" placeholder="search for user" />
                    </div>
                    <div class="user-plate-row">
                    <?php

                        foreach($resarr as $res)
                        {
                            $username = $res['username'];
                            $note = $res['note'];
                            if($note == 1)
                            {
                                $note = "checked='true'";
                            }
                            else
                            {
                                $note = "";
                            }

                            $file = $res['file'];
                            if($file == 1)
                            {
                                $file = "checked='true'";
                            }
                            else
                            {
                                $file = "";
                            }

                            $url = $res['url'];
                            if($url == 1)
                            {
                                $url = "checked='true'";
                            }
                            else
                            {
                                $url = "";
                            }

                            $cplan = planInString(getCurrentPlanDB($username));

                            $notecounts = getCounts('note',$username);
                            $urlcounts = getCounts('url',$username);
                            $filesize = getFileSize($username)."KB";

                            echo "  <div class='user-plate'>
                                        <div class='row1'>
                                            <div class='name-plan'>
                                                <div class='username'>
                                                    $username
                                                </div>
                                                <div class='plan'>
                                                    <span>$cplan</span>
                                                </div>
                                            </div>
                                            <div class='star-holder'>
                                                <button data-username='$username' data-val=''>
                                                    <i class='fas fa-star'></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class='row2'>
                                            <div class='summary'>
                                                <div>$notecounts Notes</div>
                                                <div>$filesize Files</div>
                                                <div>$urlcounts URLs</div>
                                            </div>
                                            <div class='action-buttons-wrapper'>
                                                <div class='action-buttons-holder'>
                                                    <div>
                                                        <input type='checkbox' class='notecheck' name='' id='notec$username' data-username='$username' $note/>
                                                        <label for='notec$username'>Note</label>
                                                    </div>
                                                    <div>
                                                        <input type='checkbox' class='filecheck' name='' id='filec$username' data-username='$username' $file/>
                                                        <label for='filec$username'>File</label>
                                                    </div>
                                                    <div>
                                                        <input type='checkbox' class='urlcheck' name='' id='urlc$username' data-username='$username' $url/>
                                                        <label for='urlc$username'>URL</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- row3 -->
                                    </div>
                                    <!-- user-plate -->";
                        }

                                echo "<div class='pagination'>"; //start of pagination
                                if($page > 1)
                                {
                                    $prev_page = $page - 1;
            
                                    echo "<div class='paging-button-holder'>
                                            <a href='permissionmoderation?p=$prev_page'>Newer</a>
                                        </div>";
                                }
                                    
                                    echo "<div class='current-button-holder'>
                                            Page $page out of $number_of_pages Pages
                                        </div>";
                                if($page < $number_of_pages)
                                {
                                    $next_page = $page + 1;
                                    echo "<div class='paging-button-holder'>
                                            <a href='permissionmoderation?p=$next_page'>Older</a>
                                        </div>";
                                }
            
                                echo "</div>"; //end of pagination
                        ?>
                    </div>
                    <!-- user-plate-row -->
                </div>
                <!-- mini-ad-container -->
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
            $('.ad-side a').eq(5).css('background-color', '#555');


            $('.search-row input').keyup(function () {
                $.ajax({
                    url: 'controllers/permissionmoderationhandler.php',
                    method: 'POST',
                    data: {
                        searchKeyword: $('.search-row input').val(),
                    },
                    success: function (data) {
                        $('.user-plate-row').html(data);
                    },
                });
            });



            $('.user-plate-row').on('change', '.notecheck,.filecheck,.urlcheck', function (e) {
                let that = $(this);
                let prevstate,tostate;
                let fuse = this.id.slice(0,1);
                
                if(this.checked){
                    prevstate = 0;
                    tostate = 1;
                }else{
                    prevstate = 1;
                    tostate = 0;
                }

                if(fuse == 'n'){
                    fuse = 'note';
                }else if(fuse == 'f'){
                    fuse = 'file';
                }else if(fuse == 'u'){
                    fuse = 'url';
                }

                $.ajax({
                    url: 'controllers/permissionmoderationhandler.php',
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        fuse: fuse,
                        username: $(this).data('username'),
                        permit: tostate,
                    },
                    success: function (data) {
                        if ('success' in data) {
                            // throwlert(1, data.success);
                            //
                        } else {
                            throwlert(0, 'Something went wrong!');
                            if(prevstate == 1){
                                that.prop('checked', true);
                            }else{
                                that.prop('checked', false);
                            }
                        }
                    },
                });
            });

            $('.user-plate-row').on(
                'click',
                '.star-holder button',
                function () {
                    let that = $(this);
                    let cval = parseInt($(this).data('val'));
                    let toval;
                    if (cval == 1) {
                        toval = 0;
                    } else {
                        toval = 1;
                    }
                    $.ajax({
                        url: 'controllers/planmoderationhandler.php',
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            star: toval,
                            username: $(this).data('username'),
                        },
                        success: function (data) {
                            if ('success' in data) {
                                that.data('val', `${toval}`);
                                that.attr('data-val', `${toval}`);
                            } else {
                            }
                        },
                    });
                }
            );
        </script>
    </body>
</html>
