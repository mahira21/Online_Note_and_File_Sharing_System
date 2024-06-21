<?php
    session_start();

    require_once dirname(__FILE__).'/../models/db/dbcon.php';
    require_once dirname(__FILE__).'/uniqstringgeneratormodule.php';
    require_once dirname(__FILE__).'/planmodule.php';
    require_once dirname(__FILE__).'/userstatmodule.php';
    require_once dirname(__FILE__).'/variables.php';

    $loggedUser = "";
    $surl = "";
    $data = array();

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

    function insertUrl($url,$longUrl)
    {
        $query = "INSERT INTO urls (surl,longUrl) VALUES ('$url', '$longUrl')";
        execute($query);
    }
    function updateOwner($loggedUser,$surl,$longUrl)
    {
        $createDate = time();
        $query = "SELECT * FROM urlmap WHERE surl='$surl' and urlOwner='$loggedUser'";
        
        $result=get($query);
        if(mysqli_num_rows($result) > 0)
        {

        }
        else
        {
            $query = "INSERT INTO urlmap (urlOwner,surl,longUrl,createDate) VALUES ('$loggedUser', '$surl','$longUrl','$createDate')";
            execute($query);
            $query = "INSERT INTO stat (datestamp,username,urlshort) VALUES('$createDate','$loggedUser', '1');";
            execute($query);
        }
    }
    
    if(isset($_POST['longUrl']))
    {
        $data = array();

        $limit = getLimit('url');
        $urlcount = getCounts('url');

        if($urlcount < $limit)
        {
            if(!empty($_POST['longUrl']))
            {
    
                $longUrl = htmlspecialchars($_POST['longUrl']);
                $longUrl = strpos($longUrl, 'http') !== 0 ? "http://$longUrl" : $longUrl;
                if(filter_var($longUrl, FILTER_VALIDATE_URL))
                {
    
                    //check if already exists then return past short url
                    $query = "SELECT * FROM urls WHERE longUrl='$longUrl'";
                    $result=get($query);
                    if(mysqli_num_rows($result) > 0)
                    {
                        $res=mysqli_fetch_assoc($result);
                        $surl = $res['surl'];
    
                        //insert the new owner
                        updateOwner($loggedUser,$surl,$longUrl);
                    }
                    else
                    {
                        $surl = generateUniq('urls','surl');
                        insertUrl($surl,$longUrl);
                        updateOwner($loggedUser,$surl,$longUrl);
    
                    }
                    $surl= "http://".$siteurl."/go/".$surl;
                    $data['surl'] = $surl;
                }
                else
                {
                    //throw error ivalid url
                }
            }
            else
            {
                //throw empty url
            }

        }
        else
        {
            //error limit exceeded
            $data['limitError'] = "Limit Exceeded";
        }

        echo json_encode($data);
    }
    
?>