<!DOCTYPE html>
<html>
	<head>
		<title>Generate Aunt May Email</title>
		<link rel="stylesheet" type="text/css" href="style-results.css">
	</head>
	<body>
		<h1>Generate Aunt May Pitch Email</h1>
			<p>To generate html to paste into email, input the name and email address of the person you want to email:</p>
			<form action="generate-email-html.php">
				Name: <input type="text" name="name"/><br/>
				Email: <input type="text" name="email"/><br/>
				<input type="submit" value="generate html">
			</form>

<?php
if(isset($_REQUEST['name'])):
	
	include 'sqlConfig.php';
	$connection = new mysqli($servername, $username, $password, $database);
	if($connection->connect_error){
		die("connection failed: " . $connection->connect_error);
	}
	echo 'connected successfully';
	$name = $_REQUEST['name'];
	$email = $_REQUEST['email'];
	$sqlInsert = "insert into emailsOpened (name,email,loadCount) values ('".$name."','".$email."',". 0 .")";
	if(mysqli_query($connection, $sqlInsert)){
		echo 'inserted '.$email.' successfully';
	}
	else{
		echo 'insertion of '.$email.' failed <br/>';
		echo $sqlInsert;
	}
	
	$htmlTemplate = "";
	$sqlSelectHtml = "select html from htmlTemplate where title='default html'";
	if($result = mysqli_query($connection, $sqlSelectHtml)){
		if(mysqli_num_rows($result) > 0){
			while($row=mysqli_fetch_array($result)){
				$htmlTemplate = $row['html'];
				$htmlTemplate = str_replace('toname',$name,$htmlTemplate);
				$name = str_replace(' ','%20',$name);
				$htmlTemplate = str_replace('defaultname',$name,$htmlTemplate);
				$htmlTemplate = str_replace('defaultemail',$email,$htmlTemplate);
			}
		?>

			<p>Copy and paste this html	into the email, see below for appearance</p>
				<div id="email-html">
					<?php echo htmlspecialchars($htmlTemplate) ?>
				</div>
				<div id="email-rendered">
				<?php echo $htmlTemplate ?>
				</div>
	<?php }else{
			echo 'no rows returned';
		}
	}else{
		echo 'db call failed';
	}
	
endif; ?>
	</body>
</html>


