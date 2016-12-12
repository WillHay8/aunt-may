<!DOCTYPE html>
<html>
	<head>
		<title>Aunt May Mailer Results</title>
		<style>
			#email-count-table, th, td{
			border: 1px solid black;
			border-collapse: collapse;
			}
			td, th {
				text-align: left;
				padding: 0.2em;
			}
		</style>
	</head>
	<body>
<?php
logToFile('test log');
include 'sqlConfig.php';

$connection = new mysqli($servername, $username, $password, $database);
if($connection->connect_error){
	die("connection failed: " . $connection->connect_error);
}
//echo "connected successfully <br>";
$sqlOpenDatabase = "use auntMayMailer";
if($result = mysqli_query($connection, $sqlOpenDatabase)){
	//echo "connected to auntMayMailer db<br>";
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
				<th>video link clicks</th>
			</tr>
<?php		while($row=mysqli_fetch_array($result)){
				echo "<tr>";
				echo "<td>" . $row["name"] . "</td>";
				echo "<td>" . $row["email"] . "</td>";
				echo "<td>" . $row["loadCount"] . "</td>";
				echo "<td>" . $row["videoClicks"] . "</td>";
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