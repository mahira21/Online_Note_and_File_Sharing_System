<?php
    session_start();
    require_once dirname(__FILE__).'/../models/db/dbcon.php';
    require_once dirname(__FILE__).'/uniqstringgeneratormodule.php';
    require_once dirname(__FILE__).'/filenamesanitizermodule.php';
    require_once dirname(__FILE__).'/planmodule.php';
    require_once dirname(__FILE__).'/userstatmodule.php';
    require_once dirname(__FILE__).'/variables.php';

    $loggedUser = "";
    $resarr = array();

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

    function dbOperation($filename,$fileID,$fileSize)
    {
        global $loggedUser;
        $uploadDate = time();
        $privacy = 0;

        if(empty($loggedUser))
        {
            $loggedUser = "test-user";
        }
        else
        {
            //users preferred template
        }

        $query1 = "INSERT INTO files (fileID,fileOwner, filePrivacy,fName,uploadDate,filesize)
          VALUES('$fileID','$loggedUser', '$privacy', '$filename','$uploadDate','$fileSize');";

        $query2 = "INSERT INTO stat (datestamp,username,fileupload) 
          VALUES('$uploadDate','$loggedUser', '1');";

        if(operate($query1,$query2) === false)
        {
            return false;
        }
        else
        {
            return true;
        }

    }


    

    $count = count($_FILES['file']['name']);
    $fileNames = array();
    $fileSizeArray = array();
    $noProb = true; 
    $fsinKB = 0;
    $errMsg = '';

    for($i=0;$i<$count;$i++)
    {
        $name = filter_filename($_FILES['file']['name'][$i]);
        $fileNames[] = $name;
        
        $fs = $_FILES['file']['size'][$i];
        
        $fileSizeArray[] = ceil($fs/1024);
        
        $fsinKB += ceil($fs/1024);

        $usedSpace = getFileSize();
        $filelimit = getLimit('file');

        $remainingSapce = 0;
        if(is_int($filelimit) && is_int($usedSpace))
        {
            if($filelimit > $usedSpace)
            {
                $remainingSapce = $filelimit - $usedSpace;
            }
        }
        if($fsinKB > $remainingSapce)
        {
            $noProb = false;
            $errMsg = 'You don\'t have enough space. Upgrade your plan or delete old files'; 
            break;

        }

        if($fsinKB > 102410)
        {
            $noProb =false;
            $errMsg = 'You can not upload more than 10MB at a time'; 
            break;
        }
    }

    $output = "";

    if($noProb)
    {
        for($i=0;$i<$count;$i++)
        {
            
            $filename = $fileNames[$i];
            $fileSize = $fileSizeArray[$i];
            try
            {
                $uniqfilename =  generateUniq('files','fileID');

                if(dbOperation($filename,$uniqfilename,$fileSize) === true)
                {
                    if(move_uploaded_file($_FILES['file']['tmp_name'][$i],dirname(__FILE__).'/../models/upload/'.$uniqfilename))
                    {
                        $outputurl = 'http://'.$siteurl.'/file/'.$uniqfilename;
                        $a = '<a href="'.$outputurl.'">'.$outputurl.'</a>';

                        $output .= '<div class="res-child">
                                        <div class="res-child-name">'.$filename.'</div>
                                        <div class="res-child-link">'.$a.'
                                        </div>
                                    </div>';
                    }
                    else
                    {
                        dbOperationDelete($uniqfilename);
                    }
                }
                else
                {
                    //db operation error
                    echo "<script>throwlert(0, 'Something Went wrong');</script>";
                }
                
            }
            catch(Exception $e)
            {
                echo "<script>throwlert(0, 'Something Went wrong');</script>";
            }
        }
        echo $output;
    }
    elseif($noProb == false)
    {
        ?>
        <script>
            throwlert(0, '<?php echo $errMsg; ?>');
        </script>
        <?php
    }
?>