<?php
    session_start();
    require_once dirname(__FILE__).'/../models/db/dbcon.php';

    //preventing access from view route
    if (strpos($_SERVER['REQUEST_URI'], '/views/') !== false) {
        exit();
    }
    


    date_default_timezone_set("Asia/Dhaka");

    $loggedUser = "";
    $resarr = array();
    $notecounts = 0;

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

    function shortDate($longDate)
    {
        return date('d/m/Y',$longDate);
    }
    
    function sliceText($text)
    {
        $newText = "";
        if(mb_strlen($text,'utf8') >= 15)
        {
             $newText .= mb_substr($text,0,15);
             $newText .= "...";
        }
        else if(mb_strlen($text,'utf8') == 0)
        {
            $newText = "---";
        }
        else
        {
            $newText = $text;
        }

        return $newText;
        
    }
    function sliceID($text)
    {
        
        $newText = "";
        $newText = wordwrap($text, 6, "\n", true);
        return $text;

    }
    
    if(isset($_SESSION['user'])) 
    {
        $user = $_SESSION['user'];
        if(isset($user['username']))
        {
            $loggedUser = $user['username'];
        }

        $query = "SELECT * FROM notes WHERE noteOwner='$loggedUser'";
        $result=get($query);
        
        

		while($row = mysqli_fetch_assoc($result))
		{
            $resarr[] = $row;
            $notecounts++;
        }

    }


    $results_per_page = 10;

    $query= "SELECT * FROM notes WHERE noteOwner='$loggedUser';";
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

    $query = "SELECT * FROM notes WHERE noteOwner='$loggedUser' order by lastEdited DESC LIMIT $this_page_first_result , $results_per_page";
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
        <title>NoteFused</title>

        <link rel="stylesheet" href="views/styles/all.css">
        <link rel="stylesheet" href="views/styles/throwlert.css">
        <link rel="stylesheet" href="views/styles/side2.css" />
        <link rel="stylesheet" href="views/styles/login.css" />
        <link rel="stylesheet" href="views/styles/form.css" />
        <link rel="stylesheet" href="views/styles/mynotes.css" />
        <link rel="stylesheet" href="views/styles/navbar.css">
        <link rel="stylesheet" href="views/styles/userdashcard.css">
        <link rel="stylesheet" href="views/styles/sidebar.css">
        <link rel="stylesheet" href="views/styles/pagination.css">
        <link rel="stylesheet" href="views/styles/semiloader.css">




        <script src="views/js/jquery341.js"></script>
        <script src="views/js/navbarfunctionality.js" defer></script>
        <script src="views/js/userdashcardfunctionality.js" defer></script>
        <script src="views/js/sidebar.js" defer></script>
        <script src="views/js/throwlert.js" defer></script>



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
                    <div class="search-row">
                        <input type="text" placeholder="search by note id or text..." value="" autocomplete="off"/>
                    </div>
                    <div class="result-table-plates">

                        <div class="note-lists">
                            
                            <table>
                                <thead>
                                <tr class='head'>
                                    <div class='trdiv'>
                                    <th id='noteid'>
                                        <div class='sub-unit'>
                                            Note ID
                                        </div>
                                    </th>
                                    <th id='lastEdit'>
                                        <div class='sub-unit'>
                                            Last Edited
                                        </div>
                                    </th>
                                    <th id='lastVisit'>
                                        <div class='sub-unit'>
                                            Last Visited
                                        </div>
                                    </th>
                                    <th id='expire'>
                                        <div class='sub-unit'>
                                            Expiration
                                        </div>
                                    </th>
                                    <th id='privacy'>
                                        <div class='sub-unit'>
                                            privacy
                                        </div>
                                    </th>
                                    <th id='textcontent'>
                                        <div class='sub-unit'>
                                            Content
                                        </div>
                                    </th>
                                    <th id='share'>
                                        <div class='sub-unit'>
                                            Edit
                                        </div>
                                    </th>
                                    <th id='download'>
                                        <div class='sub-unit'>
                                            Download
                                        </div>
                                    </th>
                                    <th id='delete'>
                                        <div class='sub-unit'>
                                            Delete
                                        </div>
                                    </th>
                                    </div>
                                </tr>
                                </thead>
                            </table>
                            <div class="row-plates">
                                <table>
                                    <?php
                                    foreach($resarr as $res)
                                    {
                                        $noteid = $res['noteID'];
                                        $lastVisit = $res['lastVisited'];
                                        $lastVisit = shortDate($lastVisit);     
                                        $lastEdit = $res['lastEdited'];
                                        $lastEdit = shortDate($lastEdit);        
                                        $expiration = $res['expiration'];
                                        $expiration = shortDate($expiration); 
                                        $xpire =  $res['xpire'];
                                        if($xpire == 3650)
                                        {
                                            $expiration = "NONE";
                                        }      
                                        $text = $res['text'];
                                        $text = sliceText($text);
                                        $privacy = $res['notePrivacy'];
                                        // $privacy = $privacy == 0 ? "Public" : "Private";   
                                        $privacy = $privacy == 0 ? "Public" : ($privacy == 2 ? "View Only" : "Private");   
                                    echo
                                    "<tr>
                                        <div class='trdiv'>
                                        <td id='noteid'>
                                            <div class='sub-unit'>
                                                 $noteid
                                            </div>
                                        </td>
                                        <td id='lastEdit'>
                                            <div class='sub-unit'>
                                                $lastEdit
                                            </div>
                                        </td>
                                        <td id='lastVisit'>
                                            <div class='sub-unit'>
                                                $lastVisit
                                            </div>
                                        </td>
                                        <td id='expire'>
                                            <div class='sub-unit'>
                                                $expiration
                                            </div>
                                        </td>
                                        <td id='privacy'>
                                            <div class='sub-unit'>
                                                $privacy
                                            </div>
                                        </td>
                                        <td id='textcontent'>
                                            <div class='sub-unit'>
                                                $text
                                            </div>
                                        </td>
                                        <td id='share'>
                                            <div class='sub-unit'>
                                                <a href='./$noteid'><i class='fas fa-pen-square'></i></a>
                                            </div>
                                        </td>
                                        <td id='download'>
                                            <div class='sub-unit'>
                                                <a href='controllers/mynoteshandler.php?id=$noteid'><i
                                                class='fa fa-download'
                                                style='font-size:20px'
                                            ></i></a>
                                            </div>
                                        </td>
                                        <td id='delete'>
                                            <div class='sub-unit'>
                                                <a href='zzz' id='$noteid'><i class='fa fa-trash' aria-hidden='true'></i></a>
                                            </div>
                                        </td>
                                        </div>
                                    </tr>";
                                    }
                                    ?>
                                </table>
                                <?php
                                    echo "<div class='pagination'>"; //start of pagination
                                    if($page > 1)
                                    {
                                        $prev_page = $page - 1;
                
                                        echo "<div class='paging-button-holder'>
                                                <a href='mynotes?p=$prev_page'>Newer</a>
                                            </div>";
                                    }
                                        
                                        echo "<div class='current-button-holder'>
                                                Page $page out of $number_of_pages Pages
                                            </div>";
                                    if($page < $number_of_pages)
                                    {
                                        $next_page = $page + 1;
                                        echo "<div class='paging-button-holder'>
                                                <a href='mynotes?p=$next_page'>Older</a>
                                            </div>";
                                    }
                
                                    echo "</div>"; //end of pagination
                                ?>
                            </div>  <!-- end of row-plates -->
                            
                        </div> <!-- end of notelist -->
                    </div> <!-- end of result-table-plates -->

                </div> <!-- end of fuse -->
            
            </div>
        </div>
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

            $('.row-plates').on('click', '#delete a', function (e) {
                e.preventDefault();
                $('.semiloader').fadeIn();
                let that = this;
                // console.log($(this).attr('id'));
                $.ajax({

                    url:'controllers/deletemodule.php',
                    method:'POST',
                    dataType: 'JSON',
                    data:{
                        delete:"note",
                        noteID: $(this).attr('id')
                    },
                    success:function(response){
                        $('.semiloader').fadeOut(function(){
                            if(response.success == 'true'){
                                $(that).parents('tr').fadeOut(500);
                                let ttn =  $('#totalNotes').text();
                                let tn = parseInt(ttn);
                                tn = tn-1;
                                $('#totalNotes').text(tn);
                                // console.log('done');
                            }
                        });
                    }
                });

            });

            $('.search-row input').keyup(function () {
                $.ajax({
                    url: 'controllers/mynoteshandler.php',
                    method: 'POST',
                    data: {
                        searchKeyword: $('.search-row input').val(),
                    },
                    success: function (data) {
                        $('.row-plates').html(data);
                    },
                });
            });

            $('.row-plates').on('click', '#newer', function (e) {
                e.preventDefault();
                $('.semiloader').fadeIn();

                let that = $(this);

                $.ajax({
                    url: 'controllers/mynoteshandler.php',
                    method: 'POST',
                    data: {
                        searchKeyword: $('.search-row input').val(),
                        p: $(this).data('p'),
                    },
                    success: function (data) {
                        $('.row-plates').html(data);
                        $('.semiloader').fadeOut();
                    },
                });
            });
            $('.row-plates').on('click', '#older', function (e) {
                e.preventDefault();
                $('.semiloader').fadeIn();

                let that = $(this);

                $.ajax({
                    url: 'controllers/mynoteshandler.php',
                    method: 'POST',
                    data: {
                        searchKeyword: $('.search-row input').val(),
                        p: $(this).data('p'),
                    },
                    success: function (data) {
                        $('.row-plates').html(data);
                        $('.semiloader').fadeOut();

                    },
                });
            });
        </script>
    </body>
</html>
