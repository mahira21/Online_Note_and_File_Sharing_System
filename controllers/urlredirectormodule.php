<?php
    require_once dirname(__FILE__).'/../models/db/dbcon.php';


    //this section handles redirect to original url
    //from go/surl
    if(isset($_GET['id']))
    {
        //header("location: http://example.com",  true,  301 );  exit;
        $ul = "";
        if(empty($_GET['id']))
        {
            header("Location:$noteID");
        }
        else
        {
            $ul = htmlspecialchars($_GET['id']);
            $query = "SELECT longUrl FROM urls WHERE surl='$ul'";
            $result=get($query);
            if(mysqli_num_rows($result) > 0)
            {
                $ulres = mysqli_fetch_assoc($result);
                $lurl = $ulres['longUrl'];
                $parse = parse_url($lurl);
                if(!isset($parse['scheme']))
                {
                    $lurl = "http://".$lurl;
                }
                header("location: $lurl",  true,  301 );  exit;
            }

        }
    }
?>