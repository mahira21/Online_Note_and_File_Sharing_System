<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    require_once dirname(__FILE__).'/../models/db/dbcon.php';

    if(!isset($_SESSION['admin']))
    {
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

    date_default_timezone_set("Asia/Dhaka");
    function shortDate($longDate)
    {
        return date('d/m/Y',$longDate);
    }

    if(isset($_POST['deleteall']))
    {
        $data = array();
        try
        {
            $query = "DELETE from urls;";
            execute($query);
            $query = "DELETE from urlmap;";
            execute($query);
            $data['success'] = "All urls deleted";
        }
        catch(Error $e)
        {

        }
        echo json_encode($data);
    }


    if(isset($_POST['delete']) && isset($_POST['username']))
    {
        $data = array();

        $surl = sanitizer($_POST['delete']);
        $username = sanitizer($_POST['username']);

        if(!empty($surl) && !empty($username))
        {
            try
            {
                $query = "DELETE from urlmap where surl='$surl' and urlOwner='$username'";
                execute($query); //removing record from urlmap

                //check if any other record exists for another owner
                $query = "SELECT * FROM urlmap WHERE surl='$surl'";
                $result=get($query);

                if(mysqli_num_rows($result) > 0)
                {
                    //we do nothing lol
                }
                else
                {
                    $query = "DELETE from urls where surl='$surl'";
                    execute($query); //removing record from urls
                }

                $data['success'] = 'URL deleted';
            }
            catch(Error $e)
            {

            }
        }
        echo json_encode($data);
    }

    if(isset($_POST['searchKeyword']))
    {
        $results_per_page = 10;
        $key = sanitizer($_POST['searchKeyword']);


        $query= "SELECT * FROM urlmap WHERE CONCAT(longUrl,urlOwner) like '%".$key."%';";
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



        $query = "SELECT * FROM urlmap WHERE CONCAT(longUrl,urlOwner) like '%".$key."%' order by createDate DESC LIMIT $this_page_first_result , $results_per_page";
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
        foreach($resarr as $key=>$res)
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
    
                        echo "<div class='pagination'>"; //start of pagination
                        if($page > 1)
                        {
                            $prev_page = $page - 1;

                            echo "<div class='paging-button-holder'>
                                    <a href='urlsmoderation?p=$prev_page' id='newer' data-p='$prev_page'>Newer</a>
                                </div>";
                        }
                            
                            echo "<div class='current-button-holder'>
                                        Page $page out of $number_of_pages Pages
                            </div>";
                        if($page < $number_of_pages)
                        {
                            $next_page = $page + 1;
                            echo "<div class='paging-button-holder'>
                                    <a href='urlsmoderation?p=$next_page' id='older' data-p='$next_page'>Older</a>
                                </div>";
                        }

                        echo "</div>"; //end of pagination
    }


?>