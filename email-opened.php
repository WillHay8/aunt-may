<?php
include 'constants.php';
include $privateDirectory.'sqlConfig.php';
$connection = new mysqli($servername, $username, $password, $database);
//echo "email opened";
if($connection->connect_error){
	die("connection failed: " . $connection->connect_error);
}
//print_r($_REQUEST);
if(isset($_REQUEST['email'])){
	$email = $_REQUEST['email'];
}else{
	//echo "unknown email";
	$email = "unknown email";
}
$insertSuccess = false;
while(!$insertSuccess){
	$sql = "select * from emailsOpened where email='".$email."'";
	if($result = mysqli_query($connection, $sql)){ 
		if(mysqli_num_rows($result) > 0){
			while($row=mysqli_fetch_array($result)){
				$newCount = $row["loadCount"] + 1;
				$sqlUpdate = "update emailsOpened set loadCount=".$newCount." where id=".$row["id"];
				if(mysqli_query($connection, $sqlUpdate)){
					//echo "sql executed successfully ".$sqlUpdate;
				}
				else{
					//echo "sql failed ".$sqlUpdate;
				}				
			}
			mysqli_free_result($result);
			$insertSuccess = true;
		}else{
			$email = "unknown email";	
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
