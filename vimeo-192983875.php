<?php
//update record by id video link clicks ++
include 'constants.php';
include $privateDirectory.'sqlConfig.php';
$connection = new mysqli($servername, $username, $password, $database);
if($connection->connect_error){
	die("connection failed: " . $connection->connect_error);
}
$email = $_REQUEST['email'];
$sqlGetVideoClicks = "select videoClicks from emailsOpened where email=".$email;
if($result = mysqli_query($connection, $sqGetVideoClicksl)){
	if(mysqli_num_rows($result) > 0){
		while($row=mysqli_fetch_array($result)){
			$newVideoClicks = $row['videoClicks'] + 1;
		}
		$sqlUpdateVideoClicks = "update emailsOpened set videoClicks=".$newVideoClicks."where email=".$email;
		if(mysqli_query($connection, $sqlUpdateVideoClicks)){
			//update success
		}
	}else{
		//no results for that id
	}
}else{
	//select query failed
}

//redirect to vimeo
header("Location: https://vimeo.com/192983875");