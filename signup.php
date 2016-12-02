<?php

	$cxn = new mysqli( 
	  "warehouse.cims.nyu.edu",
	  "hh1316", 
	  "kt5uns2u", 
	  "hh1316_unbroke"
	);

	$user_id = -1;
	$username = $_GET["username"];
	$email = $_GET["email"];
	$password = $_GET["password"];
	$retype = $_GET["retypepassword"];
	$success = 'false';

	$q1 = "INSERT INTO sign_up ( username, email, password ) VALUES ( '{$username}', '{$email}', '{$password}')";
	$response = $cxn->query($q1);

	$cxn->close();
	header("Location:index.html");
?>

<!doctype html>
<html>
	<body>
		<br>
		Request: <?php echo var_dump($_GET); ?><br>
		User ID: <?php echo $user_id; ?><br>
	</body>
</html>