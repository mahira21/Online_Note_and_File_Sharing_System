<?php
    session_start();
    require_once dirname(__FILE__).'/../models/db/dbcon.php';

    //preventing access from view route
    if (strpos($_SERVER['REQUEST_URI'], '/views/') !== false) {
        exit();
    }
    if(!isset($_SESSION['admin']))
    {
        header("Location:login");
    }
    
    date_default_timezone_set("Asia/Dhaka");
    function shortDate($longDate)
    {
        return date('d/m/Y',$longDate);
    }


    $results_per_page = 10;

    $query= "SELECT * FROM urlmap;";
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

    $query = "SELECT * FROM urlmap order by createDate DESC LIMIT $this_page_first_result , $results_per_page";
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
        <link rel="stylesheet" href="views/styles/pagination.css" />
        <link rel="stylesheet" href="views/styles/notesmoderation.css" />
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
                        <input type="text" placeholder="search by user or long url" />
                    </div>
                    <div class="sitewide-action">
                        <div class="r1">
                            <div>Delete All URLs</div>
                            <div>
                                <button id="deleteall">Confirm <i class="fas fa-arrow-alt-circle-right"></i></button>
                            </div>
                        </div>
                        <div class="r2">
                            <div>
                                Delete All URLs Older than <input type="number" name="" id="" placeholder="ex: 3"> days
                            </div>
                            <div>
                                <button id="olderthanbtn">Confirm <i class="fas fa-arrow-alt-circle-right"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="note-plate-row">

                    <?php

                        foreach($resarr as $res)
                        {
                            $surl = $res['surl'];
                            $longUrl = $res['longUrl'];

                            $username = $res['urlOwner'];

                            $createDate = $res['createDate'];
                            $createDate = shortDate($createDate);

                                   echo "<div class='note-plate'>
                                            <div class='row1'>
                                                <div class='noteID'><a href='$longUrl'>$longUrl</a></div>
                                                <div class='username'>$username</div>
                                            </div>
                                            <div class='row2'>
                                                <div class='dates-holder'>
                                                    <div>Upload Date : $createDate</div>
                                                </div>
                                                <div class='action-button-holder'>
                                                    <a href='' class='delete-button' data-surl='$surl' data-username='$username'>Delete</a>
                                                </div>
                                            </div>
                                        </div>";
                        }

                    ?>
                                <?php
                                    echo "<div class='pagination'>"; //start of pagination
                                    if($page > 1)
                                    {
                                        $prev_page = $page - 1;
                
                                        echo "<div class='paging-button-holder'>
                                                <a href='urlsmoderation?p=$prev_page'>Newer</a>
                                            </div>";
                                    }
                                        
                                        echo "<div class='current-button-holder'>
                                                Page $page out of $number_of_pages Pages
                                            </div>";
                                    if($page < $number_of_pages)
                                    {
                                        $next_page = $page + 1;
                                        echo "<div class='paging-button-holder'>
                                                <a href='urlsmoderation?p=$next_page'>Older</a>
                                            </div>";
                                    }
                
                                    echo "</div>"; //end of pagination
                                ?>

                    </div>
                    <!-- end of note-plate-row -->
                </div>
                <!-- end of mini-ad-container -->
            </section>
            <!-- end of ad-container -->
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
            $('.ad-side a').eq(4).css('background-color', '#555');

            // $('.search-row input').keyup(function () {
            //     $.ajax({
            //         url: 'controllers/planmoderationhandler.php',
            //         method: 'POST',
            //         data: {
            //             searchByUser: $('.search-row input').val(),
            //         },
            //         success: function (data) {
            //             $('.user-plate-row').html(data);
            //         },
            //     });
            // });

            $('.note-plate-row').on('click', '.delete-button', function (e) {
                e.preventDefault();
                let that = $(this);
                $.ajax({
                    url: 'controllers/urlsmoderationhandler.php',
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        delete: $(this).data('surl'),
                        username: $(this).data('username'),
                    },
                    success: function (data) {
                        if ('success' in data) {
                            throwlert(1, data.success);
                            that.closest('.note-plate').fadeOut();
                        } else {
                            throwlert(0, 'Something went wrong!');
                        }
                    },
                });
            });

            $('.search-row input').keyup(function () {
                $.ajax({
                    url: 'controllers/urlsmoderationhandler.php',
                    method: 'POST',
                    data: {
                        searchKeyword: $('.search-row input').val(),
                    },
                    success: function (data) {
                        $('.note-plate-row').html(data);
                    },
                });
            });
            $('.note-plate-row').on('click', '#newer', function (e) {
                e.preventDefault();
                $('.semiloader').fadeIn();

                let that = $(this);

                $.ajax({
                    url: 'controllers/urlsmoderationhandler.php',
                    method: 'POST',
                    data: {
                        searchKeyword: $('.search-row input').val(),
                        p: $(this).data('p'),
                    },
                    success: function (data) {
                        $('.note-plate-row').html(data);
                        $('.semiloader').fadeOut();
                    },
                });
            });
            $('.note-plate-row').on('click', '#older', function (e) {
                e.preventDefault();
                $('.semiloader').fadeIn();

                let that = $(this);

                $.ajax({
                    url: 'controllers/urlsmoderationhandler.php',
                    method: 'POST',
                    data: {
                        searchKeyword: $('.search-row input').val(),
                        p: $(this).data('p'),
                    },
                    success: function (data) {
                        $('.note-plate-row').html(data);
                        $('.semiloader').fadeOut();
                    },
                });
            });

            $('#deleteall').click(function(){
                $('.semiloader').fadeIn();
                let that = $(this);

                $.ajax({
                    url: 'controllers/urlsmoderationhandler.php',
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        deleteall: 1,
                    },
                    success: function (data) {
                        $('.semiloader').fadeOut(function(){
                            if('success' in data){
                                $('.note-plate').remove();
                                throwlert(1,data.success);

                            }
                        });
                    },
                });
            });
            
        </script>
    </body>
</html>
