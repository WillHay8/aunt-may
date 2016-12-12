<?php
function logToFile($message){
	file_put_contents("mailer-results.log", $message .  PHP_EOL, FILE_APPEND);
}?>
<!DOCTYPE html>
<html>
	<head>
		<title>Aunt May Mailer Results</title>
		<link rel="stylesheet" type="text/css" href="style-results.css">
	</head>
	<body>
<?php
logToFile('test log');
include 'sqlConfig.php';

$connection = new mysqli($servername, $username, $password, $database);
if($connection->connect_error){
	die("connection failed: " . $connection->connect_error);
}
echo "connected successfully <br>";
$sqlOpenDatabase = "use auntMayMailer";
if($result = mysqli_query($connection, $sqlOpenDatabase)){
	echo "connected to auntMayMailer db<br>";
}
else {
	echo "can't connect to db<br>";
}
$sql = "select * from emailsOpened";
if($result = mysqli_query($connection, $sql)){
	
	if(mysqli_num_rows($result) > 0){
		?>
		<table id="email-count-table">
			<tr>
				<th>name</th>
				<th>email</th>
				<th>load count</th>
			</tr>
<?php		while($row=mysqli_fetch_array($result)){
				echo "<tr>";
				echo "<td>" . $row["name"] . "</td>";
				echo "<td>" . $row["email"] . "</td>";
				echo "<td>" . $row["loadCount"] . "</td>";
				echo "</tr>";
			}
		echo "</table>";
		mysqli_free_result($result);
	}
	else {
		echo "0 results";
	}
}else{
	echo "database call failed";
}
$connection->close();
?>
</body>
</html>