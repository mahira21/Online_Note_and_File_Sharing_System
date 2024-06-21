<?php
	 $serverName="localhost";
	 $userName="root";
	 $password="";
	 $dbName="notefused";

	 set_error_handler(function($errno, $errstr, $errfile, $errline, $errcontext) {
		// error was suppressed with the @-operator
		if (0 === error_reporting()) {
			return false;
		}
	
		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	});



	function execute($query)
	{
		global $serverName;
		global $userName;
		global $password;
		global $dbName;
		$conn = mysqli_connect($serverName, $userName,  $password, $dbName);

		if(mysqli_query($conn,$query))
		{
			//echo "succ";
		}
		else
		{
			//echo "no succ";
		}
		mysqli_close($conn);
	}
	
	function get($query)
	{
		global $serverName;
		global $userName;
		global $password;
		global $dbName;
		$conn = mysqli_connect( $serverName, $userName, $password, $dbName);
		$result=mysqli_query($conn,$query);
		mysqli_close($conn);
		return $result;
	}
	function getCon()
	{
		global $serverName;
		global $userName;
		global $password;
		global $dbName;
		$conn = mysqli_connect( $serverName, $userName, $password, $dbName);
		return $conn;
	}

	function operate($query1,$query2)
	{
		
		global $serverName;
		global $userName;
		global $password;
		global $dbName;
		
		try
		{
			$con = mysqli_connect( $serverName, $userName, $password, $dbName);
	
			if (mysqli_connect_errno()) {
				return false;
			// echo "Failed to connect to MySQL: " . mysqli_connect_error();
			// exit();
			}
	
			// Turn autocommit off
			mysqli_autocommit($con,FALSE);
	
			// Insert some values
			mysqli_query($con,$query1);
			mysqli_query($con,$query2);
			// mysqli_query($con,"INSERT INTO Persons (FirstName,LastName,Age)
			// VALUES ('Glenn','Quagmire',33)");
	
			// Commit transaction
			if (!mysqli_commit($con)) {
				return false;
				// echo "Commit transaction failed";
				// exit();
			}
			else
			{
				return true;
			}
	
			// Close connection
			mysqli_close($con);

		}
		catch(ErrorException $e)
		{
			// echo $e->errline;
			// echo "<script>alert('ha');</script>";
			return false;
		}
	}


	function getResult($query1,$query2)
	{
		
		global $serverName;
		global $userName;
		global $password;
		global $dbName;
		
		try
		{
			$con = mysqli_connect( $serverName, $userName, $password, $dbName);
	
			if (mysqli_connect_errno()) {
				return false;
			// echo "Failed to connect to MySQL: " . mysqli_connect_error();
			// exit();
			}
	
			// Turn autocommit off
			mysqli_autocommit($con,FALSE);
	
			// Insert some values
			mysqli_query($con,$query1);
			mysqli_query($con,$query2);
			// mysqli_query($con,"INSERT INTO Persons (FirstName,LastName,Age)
			// VALUES ('Glenn','Quagmire',33)");
	
			// Commit transaction
			$result = mysqli_commit($con);
			if ($result)
			{
				var_dump($result);
				return $result;
			}
			else
			{
				return false;
			}
	
			// Close connection
			mysqli_close($con);

		}
		catch(ErrorException $e)
		{
			// echo $e->errline;
			// echo "<script>alert('ha');</script>";
			return false;
		}
	}
?>