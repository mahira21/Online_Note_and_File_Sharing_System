<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    require_once dirname(__FILE__).'/../models/db/dbcon.php';
    require_once dirname(__FILE__).'/variables.php';

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
        header('Location:../login');
        exit();
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

    if(isset($_POST['searchKeyword']))
    {
        $results_per_page = 10;
        $key = sanitizer($_POST['searchKeyword']);


        $query= "SELECT * from urlmap WHERE urlOwner='$loggedUser' and longUrl like '%".$key."%';";
        $result = get($query);
        $number_of_results = mysqli_num_rows($result);

        $number_of_pages = ceil($number_of_results/$results_per_page);


        if (!isset($_POST['p']))
        {
            $page = 1;
        }
        else
        {
            $pGet = 1;
            try
            {
                $pGet = (int)$_POST['p'];
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



        $query = "SELECT * FROM urlmap WHERE longUrl like '%".$key."%' and urlOwner='$loggedUser' order by createDate DESC LIMIT $this_page_first_result , $results_per_page";
        $result = get($query);

        $resarr = array();
    
        if($result !== false)
        {
            while($row = mysqli_fetch_assoc($result))
            {
                $resarr[] = $row;
            }
        }
        $htmlembed = "";
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
                                    <a href='myurls?p=$prev_page' id='newer' data-p='$prev_page'>Newer</a>
                                </div>";
                        }
                            
                            echo "<div class='current-button-holder'>
                                        Page $page out of $number_of_pages Pages
                                </div>";
                        if($page < $number_of_pages)
                        {
                            $next_page = $page + 1;
                            echo "<div class='paging-button-holder'>
                                    <a href='myurls?p=$next_page' id='older' data-p='$next_page'>Older</a>
                                </div>";
                        }

                        echo "</div>"; //end of pagination
    }

?>