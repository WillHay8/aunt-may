<?php
include 'constants.php';
include $privateDirectory.'sqlConfig.php');
$connection = new mysqli($servername, $username, $password, $database);

if($connection->connect_error){
	die("connection failed: " . $connection->connect_error);
}

$sql = "select * from emailsOpened where email='".$_REQUEST['email']."'";
if($result = mysqli_query($connection, $sql)){
	if(mysqli_num_rows($result) > 0){
		while($row=mysqli_fetch_array($result)){
				$newCount = $row["loadCount"] + 1;
				$sqlUpdate = "update emailsOpened set loadCount=".$newCount." where id=".$row["id"];
				mysqli_query($connection, $sqlUpdate);
		}
		mysqli_free_result($result);
	}else{
		$unknownEmailSQL = "select * from emailsOpened where email='unknown email'";
		if($result2 = mysqli_query($connection, $unknownEmailSQL)){
			if(mysqli_num_rows($result2) == 1){
				while($unknownRow = mysqli_fetch_array($result2)){
					$newCount2 = $unknownRow['loadCount'] + 1;
					$sqlUpdate2 = "update emailsOpened set loadCount=".$newCount2." where id=".$unknownRow['id'];
					mysqli_query($connection, $sqlUpdate2);				
				}
			}
			mysqli_free_result($result2);
		}
	}
}
mysqli_close($connection);

$file = "footer-background.jpg";
$type = "image/jpeg";
header("Content-Type:".$type);
header('Content-Length: ' . filesize($file));
readfile($file);

?>
