<?php
	$cxn = new mysqli( 
	  "warehouse.cims.nyu.edu",
	  "hh1316", 
	  "kt5uns2u", 
	  "hh1316_unbroke"
	);

	$user_id = $_GET["user_id"];
	var_dump($_GET);

	// Log out status update
	$login_status = "UPDATE `sign_up` SET status=0 WHERE user_id='{$user_id}';";
	$perform_update = $cxn->query($login_status);

	// Delete cookie
	$cookie_name = "unbroke_userID";
	$cookie_value = $user_id;
	setcookie($cookie_name, $cookie_value, time() - 3600);

	$cxn->close();
	header("Location:index.html");
?>