<?php
    require_once dirname(__FILE__).'/userstatmodule.php';
    require_once dirname(__FILE__).'/planmodule.php';

    function getName(){
        if(isset($_SESSION['user'])) 
        {
            $user = $_SESSION['user'];
            if(isset($user['name']))
            {
                return  $user['name'];
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
        
    }

    if(isset($_POST['ustats']))
    {
        $data = array();
        $pnotecount = $pfilecount = $urlcount = $notecount = $filecount = -1;

        $notecount = getCounts('note');
        $pnotecount = getCounts('pnote');
        $filecount = getCounts('file');
        $pfilecount = getCounts('pfile');
        $urlcount = getCounts('url');

        $totalfilesize  = getFileSize();

        $nameofuser = getName();

        $data['nameofuser'] = is_string($nameofuser) ? $nameofuser : "";

        $data['currentplan'] = getCurrentPlan();

        $data['notecount'] = is_int($notecount)? $notecount : -1;
        $data['pnotecount'] = is_int($pnotecount)? $pnotecount : -1;
        $data['filecount'] = is_int($filecount)? $filecount : -1;
        $data['pfilecount'] = is_int($pfilecount)? $pfilecount : -1;
        $data['urlcount'] = is_int($urlcount)? $urlcount : -1;
        $data['totalfilesize'] = is_int($totalfilesize)? $totalfilesize : -1;

        echo json_encode($data);

    }

?>