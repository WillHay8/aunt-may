<?php 
include 'constants.php';
include $privateDirectory.'sqlConfig.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Generate Aunt May Email</title>
		<style>
			#wrapper {
				width: 100%;
				max-width: 800px;
				margin: auto;
				border: 1px solid black;
			}
			h1 {
				text-align: center;
			}
			
			.explanation {
				margin: 1em;
			}
			
			#newRecipient, #existingRecipient {
				display: table-cell;
				padding: 2.5%;
			}
			
			#newRecipient {
				width: 35%;
			}
			#existingRecipient {
				width: 55%;
			}
			
			tbody {
				height: 7em;
				overflow: scroll;
			}
			
			table, th, td {
				border: 1px solid black;
				border-collapse: collapse;
			}
			
			th, td {
				padding: 0.2em;
			}
			
			#email-html {
				padding: 2em;
			}
			</style>
	</head>
	<body>
		<div id="wrapper">
			<h1>Generate Aunt May Pitch Email</h1>
			<span class="explanation">To generate html, you can:</span>
			<div id="recipientContainer">
			<div id="newRecipient">
				<span>input name and email</span>
				<form action="generate-email-html.php">
					Name: <input type="text" name="name"/><br/>
					Email: <input type="text" name="email"/><br/>
					<input type="submit" value="generate html">
				</form>
			</div>
			<div id="existingRecipient">
			<?php 
				$connection = new mysqli($servername, $username, $password, $database);
				if($connection->connect_error){
					die("connection failed: " . $connection->connect_error);
				}
				//echo 'connected successfully';
				$sqlGetAllNames = "select * from emailsOpened";
				
				if($allNames = mysqli_query($connection, $sqlGetAllNames)){ ?>
					<span>or select name and email</span>
					<table>
						<tr>
							<th>select</th>
							<th>name</th>
							<th>email</th>
						</tr>
					<?php while($nameRow=mysqli_fetch_array($allNames)){
						echo "<tr>
							<td>
							<form action='generate-email-html.php'>
							<input type='hidden' name='name' value='".$nameRow["name"] . "'/>
							<input type='hidden' name='email' value='".$nameRow["email"]."'/>
							<input type='submit' value='select'/></form> </td>
							<td>" . $nameRow["name"] . "</td>
							<td>" . $nameRow["email"] . "</td>
							</tr>";
						}
					echo '</table>';
						
				}else{ echo 'name database call failed';}
				
				echo '</div></div>';		

if(isset($_REQUEST['email'])):	
	$name = $_REQUEST['name'];
	$email = $_REQUEST['email'];
	//check if record exists
	$sqlCheckIfEmailExists = "select id from emailsOpened where email='".$email."'";
	if($existingIds = mysqli_query($connection, $sqlCheckIfEmailExists)){
		if(mysqli_num_rows($existingIds) == 0){
			//if not exists insert
			$sqlInsert = "insert into emailsOpened (name,email,loadCount,videoClicks) values ('".$name."','".$email."',". 0 .",". 0 .")";
			if(mysqli_query($connection, $sqlInsert)){
				//echo 'inserted '.$email.' successfully';
			}
			else{
				echo "insertion failed";
			}
		}
	}

	
	$htmlTemplate = "";
	$sqlSelectHtml = "select html from htmlTemplate where title='".$htmlTitle."'";
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

			<span class="explanation">Copy and paste this html	into the email, see below for appearance</span><br/>
			<strong><span class="explanation">Recipient: <?=str_replace('%20',' ',$name)?> <?=$email?></span></strong>
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
	</div>
	</body>
</html>


