<?php
    require_once dirname(__FILE__).'/../models/db/dbcon.php';


    function generate($length)
    {
        $str = substr(md5(microtime()), 0, $length);
        return $str;
    }
    function uniq($shortString,$table,$idToCheck)
    {
        //here we will check if a record
        //already exists in database or not
        $query = "SELECT * FROM $table WHERE $idToCheck = '$shortString'";
        $result=get($query);
        return mysqli_num_rows($result) > 0 ? false: true;
        
    }
    function generateUniq($table,$idToCheck, $length = 14)
    {
        $shortString ="";
        while(true)
        {
            $shortString = generate($length);
            if(uniq($shortString,$table,$idToCheck))
            {
                break;
            }
        }
        return $shortString;
    }


?>