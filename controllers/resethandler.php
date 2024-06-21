<?php
    session_start();
    require_once dirname(__FILE__).'/../models/db/dbcon.php';
    require_once dirname(__FILE__).'/uniqstringgeneratormodule.php';

    require 'phpmailer/PHPMailer.php';
	require 'phpmailer/SMTP.php';
	require 'phpmailer/Exception.php';

    use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;


    function getPass()
    {
        $query = "SELECT p from ps where id='1'";
        $result=get($query);
        if(mysqli_num_rows($result) > 0)
        {
            $res =  mysqli_fetch_assoc($result);
            return $res['p'];
        }
        else
        {
            return false;
        }
    }


    function sendmail($email, $code)
    {
        $pass = getPass();
        if(!is_string($pass))
        {
            return;
        }

        $mail = new PHPMailer;
    
        $mail->isSMTP();
        
        $mail->Host = 'smtp.gmail.com';
    
        $mail->Port = 587;
        
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        
        $mail->SMTPAuth = true;
    
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
        $mail->Username = 'notefused@gmail.com';
        
        $mail->Password = $pass;
        
        $mail->setFrom('notefused@gmail.com');
        
        $mail->addAddress($email);
        
        $mail->Subject = 'NoteFused Password Reset';
        
        $mail->isHTML(true);

        $mail->Body = " <body>
        <div
            style='
                margin: 0 auto;
                max-width: 600px;
                margin-bottom: 20px;
                color: rgb(42, 78, 95);
                text-align: center;
                font-size: 1.5rem;
            '
        >
            NoteFused Password Reset
        </div>
        <hr />
        <div>
            It seems that you have forgotten your password for your notefused
            account
            <br /><br />
            Copy the below code to reset your password
        </div>
        <br />
        <div
            style='
                margin: 0 auto;
                max-width: 600px;
                margin-bottom: 20px;
                color: rgb(34, 101, 110);
                text-align: center;
                font-size: 1.5rem;
            '
        >
            $code
        </div>
        <br />
        <div>
            The code expires in 60 Min from now
        </div>
        <br />
        <div>
            Ignore if you haven't requested this code
        </div>
    </body>";
        
        if (!$mail->send()) {
            return false;
        } else {
            return true;
        }
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


    function checkRecord($uname,$email)
    {
        try
        {
            $query = "SELECT * FROM profiles WHERE username='$uname' AND email='$email'";
			$result=get($query);
			if(mysqli_num_rows($result) > 0)
			{
                return true;
			}
			else
			{
                return false;
			}
        }
        catch(Error $e)
        {
            return false;
        }
    }

    function prepareResetTable($uname)
    {
        $expire = time() + 3600;
        $code = generateUniq('passreset','code', 32);

        $query = "INSERT into passreset (username,expire,status,code) values('$uname','$expire',1,'$code');";
        try
        {
            execute($query);
            return $code;
        }
        catch(Error $e)
        {
            return false;
        }
    }


    if(isset($_POST['uname']) && isset($_POST['email']))
    {
        $data = array();
        $hasNoError = true;
        
        $uname = sanitizer($_POST['uname']);
        $email = sanitizer($_POST['email']);

        if(!empty($uname) && !empty($email))
        {
            if(checkRecord($uname,$email) === true)
            {
                $code = prepareResetTable($uname);

                if($code !== false)
                {
                    if(is_string($code))
                    {
                        if(sendmail($email,$code) === true )
                        {
                            $data['success'] = 'true';
                        }
                        else
                        {
                            //email send problem
                            $data['error'] = "Something went wrong";
                        }
                    }
                    else
                    {
                        $data['error'] = "Something went wrong";
                    }
                }
                else
                {
                    $data['error'] = "Something went wrong";
                }

            }
            else
            {
                $data['error_record'] = "No records found associated with the username and email";
            }

        }
        else
        {
            $data['error'] = "Something went wrong";
        }

        echo json_encode($data);

    }
    
?>