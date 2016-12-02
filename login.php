<?php

	$cxn = new mysqli( 
	  "warehouse.cims.nyu.edu",
	  "hh1316", 
	  "kt5uns2u", 
	  "hh1316_unbroke"
	);

	$username = $_POST["username"];
	$password = $_POST["password"];
	var_dump($_POST);

	$success = false;

	$q1 = "SELECT user_id FROM `sign_up` WHERE username='{$username}' AND password='{$password}'";
	$response = $cxn->query($q1);

	if($response->num_rows > 0) { $success = true; } 

	if ($success == true){
		$row = $response->fetch_assoc();
		$user_id = $row['user_id'];
		echo "success";

		// User is Logged in
		$login_status = "UPDATE `sign_up` SET status=1 WHERE user_id='{$user_id}';";
		$perform_update = $cxn->query($login_status);
		
		// Setting up a Cookie
		$cookie_name = "unbroke_userID";
		$cookie_value = $user_id;
		setcookie($cookie_name, $cookie_value, time() - 3600);
		setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); 
		echo "cookie_id: ". $_COOKIE[$cookie_name];

		$q2 = "SELECT status FROM `user_details` WHERE user_id='{$user_id}'";
		$resp = $cxn->query($q2);

		if ($resp->num_rows > 0){
			echo "Row exists";
			$row = $resp->fetch_assoc();
			$status = $row['status'];
			if ($status == 1) { header("Location:home.php"); } else { header("Location:user_info.html"); }

		} else {header("Location:user_info.html");}

	} else {
		header("Location:index.html");
	}

	$cxn->close();
?>

<!doctype html>
<html>
	<body>
		<br>
		Welcome <?php echo $_POST["username"]; ?><br>
		Your Password is: <?php echo $_POST["password"]; ?><br>


	</body>
</html>