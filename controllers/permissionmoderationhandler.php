<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    require_once dirname(__FILE__).'/../models/db/dbcon.php';
    require_once dirname(__FILE__).'/../controllers/userstatmodule.php';
    require_once dirname(__FILE__).'/../controllers/planmodule.php';

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



    if(isset($_POST['fuse']) && isset($_POST['username']) && isset($_POST['permit']))
    {
        $data = array();
        $user = sanitizer($_POST['username']);
        $fuse = sanitizer($_POST['fuse']);
        $permit = sanitizer($_POST['permit']);
        
        if(!empty($user) && !empty($fuse))
        {
            try
            {
                $query = "UPDATE permission set $fuse='$permit' where username='$user'";
                execute($query);
                $data['success'] = 'true';
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


        $query= "SELECT * FROM permission WHERE username like '%".$key."%';";
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



        $query = "SELECT * FROM permission WHERE username like '%".$key."%' order by username LIMIT $this_page_first_result , $results_per_page";
        $result = get($query);

        $resarr = array();
    
        if($result !== false)
        {
            while($row = mysqli_fetch_assoc($result))
            {
                $resarr[] = $row;
            }
        }
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
                                <a href='permissionmoderation?p=$prev_page' id='newer' data-p='$prev_page'>Newer</a>
                            </div>";
                    }
                        
                        echo "<div class='current-button-holder'>
                                    Page $page out of $number_of_pages Pages
                        </div>";
                    if($page < $number_of_pages)
                    {
                        $next_page = $page + 1;
                        echo "<div class='paging-button-holder'>
                                <a href='permissionmoderation?p=$next_page' id='older' data-p='$next_page'>Older</a>
                            </div>";
                    }

                    echo "</div>"; //end of pagination
}


?>