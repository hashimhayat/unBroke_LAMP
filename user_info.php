<?php

	$cxn = new mysqli( 
	  "warehouse.cims.nyu.edu",
	  "hh1316", 
	  "kt5uns2u", 
	  "hh1316_unbroke"
	);

	$cookie_name = 'unbroke_userID';
	if(!isset($_COOKIE[$cookie_name])) {
	     echo "Cookie named '" . $cookie_name . "' is not set!";
	} else {
	     echo "Cookie '" . $cookie_name . "' is set!<br>";
	     echo "Value is: " . $_COOKIE[$cookie_name];
	}

	$user_id = $_COOKIE[$cookie_name];
	$fullname = $_POST["fullname"];
	$age = $_POST["age"];
	$occupation = $_POST["occupation"];
	$bio = $_POST["bio"];
	$address = $_POST["address"];
	$city = $_POST["city"];
	$zip = $_POST["zip"];
	$longitude = $_POST["longitude"];
	$latitude = $_POST["latitude"];

	$success = 'false';

	$q1 = "INSERT INTO user_details (
										user_id,
										fullname,
										age,
										occupation,
										bio,
										address,
										city,
										zip,
										longitude,
										latitude,
										status
									) VALUES (
										'{$user_id}',
										'{$fullname}',  
										'{$age}',  
										'{$occupation}',  
										'{$bio}',  
										'{$address}',  
										'{$city}',  
										'{$zip}',  
										'{$longitude}',    
										'{$latitude}', 
										1
									);";
	
	$response = $cxn->query($q1);
	$cxn->close();
	header("Location:home.php");
?>

<!doctype html>
<html>
 	<head>
 		<link href="css/blog.css"  type="text/css" rel="stylesheet" />
 		<title>Unbroke</title >
 	</head>

	<header>
		Unbroke - User Information
	</header>
		<body>
			<br>
				Request: <?php echo var_dump($_POST); ?><br>
		</body>
</html>