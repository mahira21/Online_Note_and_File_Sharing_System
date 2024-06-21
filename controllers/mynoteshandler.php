<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    require_once dirname(__FILE__).'/../models/db/dbcon.php';
    $txt = "";
    $noteID = "";

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

    if(isset($_GET['id']))
    {
        if(!empty($_GET['id']))
        {
            
            $noteID = $_GET['id'];

            $query = "SELECT * FROM notes WHERE noteID='$noteID'";
            
            $result=get($query);
            //stat update
            $datestamp = time();
            $query = "INSERT INTO stat (datestamp,username,notedownload) VALUES('$datestamp','$loggedUser', '1');";
            execute($query);        

            if(mysqli_num_rows($result) > 0)
            {
                $note=mysqli_fetch_assoc($result);
                $noteOwner = $note['noteOwner'];
                $noteText = $note['text'];
                $txt = $noteText."\n\n"."#notefused";
            }
        }
        header('Content-type: text/plain');
        header("Content-Disposition: attachment; filename=$noteID.txt");
    
        echo $txt;
    }

    if(isset($_POST['searchKeyword']))
    {
        $results_per_page = 10;
        $key = sanitizer($_POST['searchKeyword']);


        $query= "SELECT * FROM notes WHERE noteOwner='$loggedUser' and CONCAT(noteID,text) like '%".$key."%';";
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



        $query = "SELECT * FROM notes WHERE noteOwner='$loggedUser' and CONCAT(noteID,text) like '%".$key."%' order by lastEdited DESC LIMIT $this_page_first_result , $results_per_page";
        $result = get($query);

        $resarr = array();
    
        if($result !== false)
        {
            while($row = mysqli_fetch_assoc($result))
            {
                $resarr[] = $row;
            }
        }


        echo "<table>";
                                    
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
            $privacy = $privacy == 0 ? "Public" : "Private";   
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
                                    
        echo "</table>";
        echo "<div class='pagination'>"; //start of pagination
                if($page > 1)
                {
                    $prev_page = $page - 1;

                    echo "<div class='paging-button-holder'>
                            <a href='mynotes?p=$prev_page' id='newer' data-p='$prev_page'>Newer</a>
                        </div>";
                }
                    
                    echo "<div class='current-button-holder'>
                                Page $page out of $number_of_pages Pages
                        </div>";
                if($page < $number_of_pages)
                {
                    $next_page = $page + 1;
                    echo "<div class='paging-button-holder'>
                            <a href='mynotes?p=$next_page' id='older' data-p='$next_page'>Older</a>
                        </div>";
                }

            echo "</div>"; //end of pagination
    }
                                    
?>