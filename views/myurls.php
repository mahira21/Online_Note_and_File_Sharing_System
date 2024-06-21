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

    $results_per_page = 10;

    $query= "SELECT * from urlmap WHERE urlOwner='$loggedUser';";
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

    $query = "SELECT * from urlmap WHERE urlOwner='$loggedUser' order by createDate DESC LIMIT $this_page_first_result , $results_per_page";
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NoteFused</title>

    <link rel="stylesheet" href="views/styles/all.css">
    <link rel="stylesheet" href="views/styles/throwlert.css">
    <link rel="stylesheet" href="views/styles/side2.css">
    <link rel="stylesheet" href="views/styles/base.css">
    <link rel="stylesheet" href="views/styles/myurl2.css">
    <link rel="stylesheet" href="views/styles/optiontoggler.css">
    <link rel="stylesheet" href="views/styles/login.css">
    <link rel="stylesheet" href="views/styles/form.css">
    <link rel="stylesheet" href="views/styles/navbar.css">
    <link rel="stylesheet" href="views/styles/userdashcard.css">
    <link rel="stylesheet" href="views/styles/sidebar.css">
    <link rel="stylesheet" href="views/styles/pagination.css">
    <link rel="stylesheet" href="views/styles/semiloader.css">


    <script src="views/js/jquery341.js"></script>
    <script src="views/js/throwlert.js" defer></script>
    <script src="views/js/myurlsfunctionality.js" defer></script>
    <script src="views/js/optiontogglerfunctionality.js" defer></script>
    <script src="views/js/navbarfunctionality.js" defer></script>
    <script src="views/js/userdashcardfunctionality.js" defer></script>
    <script src="views/js/sidebar.js" defer></script>
    <style>
        .inp-but{
            display:none;
        }
    </style>

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
                    <div class="search-row">
                        <input type="text" placeholder="search for url" value="" autocomplete="off"/>
                    </div>
                    <div class="row-head">
                        <div class='row1'>
                                <div class='col1'>
                                    SL
                                </div>
                            </div>
                            <div class='row2'>
                                <div class='col2'>
                                    Full URL
                                </div>
                            </div>
                            <div class='row3'>
                                <div class='col3'>
                                    Short URL
                                </div>
                            </div>
                            <div class='row4'>
                                <div class='col4'>
                                    Copy Link
                                </div>
                                <div class='col5'>
                                    Delete
                                </div>
                            </div>
                        </div>
                    <div class="result-row-plate-container">
                    
<?php
                    echo "<div class='row-plates'>"; //start of row-plates
    $urls = array();
    $c = $this_page_first_result+1;
    foreach($resarr as $res)
    {
        $surl =  $res['surl'];

        $query = "SELECT * from urls WHERE surl='$surl'";
        $result=get($query);
		while($row = mysqli_fetch_assoc($result))
		{
            $fullUrl = $row['longUrl'];
            $srl = $shortUrl = $row['surl'];
            $shortUrl = 'http://'.$siteurl.'/go/'.$shortUrl;
            echo    "<div class='row-plate'>
                        <div class='row1'>
                            <div class='col1'>
                                $c
                            </div>
                        </div>
                        <div class='row2'>
                            <div class='col2'>
                                <a href='$fullUrl'>$fullUrl</a>
                            </div>
                        </div>
                        <div class='row3'>
                            <div class='col3'>
                                <a href='$shortUrl'>$shortUrl</a>
                            </div>
                        </div>
                        <div class='row4'>
                            <div class='col4'>
                                <a href=''><i class='fas fa-copy'></i></a>
                            </div>
                            <div class='col5'>
                                <a href='' id='$srl'><i class='fa fa-trash' aria-hidden='true'></i></a>
                            </div>
                        </div>
                    </div>";
                    $c++;
        }

    }
                    echo "</div>"; //end of row-plates
                    echo "<div class='pagination'>"; //start of pagination
                    if($page > 1)
                    {
                        $prev_page = $page - 1;

                        echo "<div class='paging-button-holder'>
                                <a href='myurls?p=$prev_page'>Newer</a>
                            </div>";
                    }
                        
                        echo "<div class='current-button-holder'>
                                Page $page out of $number_of_pages Pages
                            </div>";
                    if($page < $number_of_pages)
                    {
                        $next_page = $page + 1;
                        echo "<div class='paging-button-holder'>
                                <a href='myurls?p=$next_page'>Older</a>
                            </div>";
                    }

                    echo "</div>"; //end of pagination
?>
                    </div> <!-- end of result-row-plate-container  -->           
                </div> <!-- end of mini-container  -->
            </div>  <!-- end of fuse  -->
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