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
            $query = "DELETE from files;";
            execute($query);
            //fwork
            $files = glob(dirname(__FILE__).'/../models/upload/*');
            $count = count($files);
            foreach($files as $file)
            { 
                if(is_file($file))
                {
                    unlink($file);
                }
            }

            $data['success'] = "$count file(s) deleted";
        }
        catch(Error $e)
        {

        }
        echo json_encode($data);
    }

    if(isset($_POST['delete']))
    {
        $data = array();
        $fileid = sanitizer($_POST['delete']);
        if(!empty($fileid))
        {
            try
            {
                $query = "DELETE from files where fileID='$fileid';";
                execute($query);
                //fwork
                if(file_exists(dirname(__FILE__).'/../models/upload/'.$fileid))
                {
                    unlink(dirname(__FILE__).'/../models/upload/'.$fileid); //deleting file from server
                }

                $data['success'] = 'File deleted';
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


        $query= "SELECT * FROM files WHERE fName like '%".$key."%' OR fileOwner like '%".$key."%';";
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



        $query = "SELECT * FROM files WHERE fName like '%".$key."%' OR fileOwner like '%".$key."%' order by uploadDate DESC LIMIT $this_page_first_result , $results_per_page";
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
            $fileid = $res['fileID'];
            $filename = $res['fName'];

            $username = $res['fileOwner'];

            $uploadDate = $res['uploadDate'];
            $uploadDate = shortDate($uploadDate);

            $size = $res['filesize'];
            $size = (double)$size;
            $size = $size/1024;

            $size = number_format((float)$size, 2, '.', '');
            $size = $size.' MB';
                   echo "<div class='note-plate'>
                            <div class='row1'>
                                <div class='noteID'><a href='./$fileid'>$filename</a></div>
                                <div class='username'>$username</div>
                            </div>
                            <div class='row2'>
                                <div class='dates-holder'>
                                    <div>Upload Date : $uploadDate</div> <div>File Size : $size</div>
                                </div>
                                <div class='action-button-holder'>
                                    <a href='' class='delete-button' data-fileid='$fileid'>Delete</a>
                                </div>
                            </div>
                        </div>";
        }

    
                        echo "<div class='pagination'>"; //start of pagination
                        if($page > 1)
                        {
                            $prev_page = $page - 1;

                            echo "<div class='paging-button-holder'>
                                    <a href='filesmoderation?p=$prev_page' id='newer' data-p='$prev_page'>Newer</a>
                                </div>";
                        }
                            
                            echo "<div class='current-button-holder'>
                                        Page $page out of $number_of_pages Pages
                            </div>";
                        if($page < $number_of_pages)
                        {
                            $next_page = $page + 1;
                            echo "<div class='paging-button-holder'>
                                    <a href='filesmoderation?p=$next_page' id='older' data-p='$next_page'>Older</a>
                                </div>";
                        }

                        echo "</div>"; //end of pagination
    }


?>