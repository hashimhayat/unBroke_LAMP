<?php

	$cxn = new mysqli( 
	  "warehouse.cims.nyu.edu",
	  "hh1316", 
	  "kt5uns2u", 
	  "hh1316_unbroke"
	);

	$job_id = $_POST["job_id"];
	$user_id = $_POST["user_id"];
	$comments = $_POST["comment"];
	$salary = $_POST["salary"];
	$class = $_POST["class"];
	$status = $_POST["status"];

	$q1 = "INSERT INTO job_offerings (
										job_id,
										user_id,
										comments,
										salary,
										class,
										status
									)
									VALUES (
										'{$job_id}',
										'{$user_id}',  
										'{$comments}',  
										'{$salary}',  
										'{$class}',  
										'{$status}'  
									);";
	$response = $cxn->query($q1);
	$cxn->close();
	header("Location:home.php");
?>

<!doctype html>
<html>
	<body>
		<br>
		<!-- Request: <?php echo var_dump($_POST); ?><br> -->
	</body>
</html>