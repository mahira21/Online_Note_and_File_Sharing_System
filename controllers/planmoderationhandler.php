<?php
    require_once dirname(__FILE__).'/../models/db/dbcon.php';
    require_once dirname(__FILE__).'/userstatmodule.php';
    require_once dirname(__FILE__).'/planmodule.php';


    date_default_timezone_set("Asia/Dhaka");

    $resarr = array();
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
        return date('d/m/Y h:m A',$longDate);
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

    if(isset($_POST['action']))
    {
        $data = array();
        $action = (int)sanitizer($_POST['action']);
        if(!empty($action))
        {
            if(isset($_POST['username']))
            {
                $username = sanitizer($_POST['username']);
                if(!empty($username))
                {
                    if($action == 1)
                    {
                        $query = "SELECT plan FROM cpreq WHERE actions='0' and username='$username';";
                        $result=get($query);        
                        $res = mysqli_fetch_assoc($result);
                        $dplan = (int)$res['plan'];

                        $query = "UPDATE profiles SET plan='$dplan'  WHERE username='$username'";
                        execute($query);
                        
                        $query = "UPDATE cpreq SET actions='1' WHERE username='$username'";
                        execute($query);
                        
                        $data['success'] = "Request Approved";

                    }
                    elseif($action == 2)
                    {
                        $query = "UPDATE cpreq SET actions='2' WHERE username='$username'";
                        execute($query);

                        $data['success'] = "Request Declined!";

                    }
                    else
                    {
                        // echo "<script>alert('Something Went wrong');</script>";
                    }
                }
                else
                {
                    // echo "<script>alert('Something Went wrong');</script>";
                }

            }
            else
            {
                // echo "<script>alert('Something Went wrong');</script>";
            }
        }
        else
        {
            // echo "<script>alert('Something Went wrong');</script>";
        }

        echo json_encode($data);
    }

    if(isset($_POST['star']))
    {
        $data = array();
        $star = (int)sanitizer($_POST['star']);
        if(!empty($star) || $star == 0)
        {

            if(isset($_POST['username']))
            {
                $username = sanitizer($_POST['username']);
                if(!empty($username))
                {

                    if($star == 1)
                    {
                        
                        $query = "UPDATE cpreq SET star='1' WHERE username='$username'";
                        execute($query);
                        $data['success'] = "Request Starred";

                    }
                    elseif($star == 0)
                    {

                        $query = "UPDATE cpreq SET star='0' WHERE username='$username'";
                        execute($query);
                        $data['success'] = "Request Unstarred!";
                    }
                    else
                    {
                        // echo "<script>alert('Something Went wrong');</script>";
                    }
                }
                else
                {
                    // echo "<script>alert('Something Went wrong');</script>";

                }

            }
            else
            {
                // echo "<script>alert('Something Went wrong');</script>";
            }
        }
        else
        {
            // echo "<script>alert('Something Went wrong');</script>";
        }

        echo json_encode($data);
    }
    
    
    if(isset($_POST['searchByUser']))
    {
        $chars = $_POST['searchByUser'];
        $query = "SELECT * FROM cpreq WHERE username like '%".$chars."%' and actions='0' order by star DESC, reqDate";

        $result=get($query);        
        if($result === false)
        {
            echo "in";
        }
        elseif(mysqli_num_rows($result) > 0)
        {
            while($row = mysqli_fetch_assoc($result))
            {
                $resarr[] = $row;
            }
        }
        foreach($resarr as $key=>$res)
                        {
                            $username = $res['username'];
                            $cplan = planInString(getCurrentPlanDB($username));
                            $notecounts = getCounts('note',$username);
                            $urlcounts = getCounts('url',$username);
                            $filesize = getFileSize($username)."KB";
                            $reqDate = shortDate($res['reqDate']);
                            $star = $res['star'];
                            $dplan = planInString($res['plan']);

                            echo "<div class='user-plate'>
                                        <div class='row1'>
                                            <div class='date'>
                                                $reqDate
                                            </div>
                                            <div class='star-holder'>
                                                <button data-username='$username' data-val='$star'><i class='fas fa-star'></i></button>
                                            </div>
                                        </div>
                                        <div class='row2'>
                                            <div class='name-plan'>
                                                <div class='username'>
                                                    @$username
                                                </div>
                                                <div class='plan'>
                                                    <span>$cplan</span><span>to</span
                                                    ><span>$dplan</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class='row3'>
                                            <div class='summary'>
                                                <span>$notecounts Notes</span>
                                                <span>$filesize Files</span>
                                                <span>$urlcounts URLs</span>
                                            </div>
                                            <div class='action-buttons-wrapper'>
                                                <div class='action-buttons-holder'>
                                                    <button class='action-button decline-button' data-username='$username'>
                                                        Decline
                                                    </button>
                                                    <button class='action-button approve-button' data-username='$username'>
                                                        Approve
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>";
                        }
    }
?>