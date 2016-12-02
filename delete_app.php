<?php
	$cxn = new mysqli( 
	  "warehouse.cims.nyu.edu",
	  "hh1316", 
	  "kt5uns2u", 
	  "hh1316_unbroke"
	);

	$app_id = $_GET["app_id"];;

	$q1 = "DELETE FROM `job_applications` WHERE app_id='{$app_id}'";
	$response = $cxn->query($q1);

	$cxn->close();
	header("Location:select_job.php");
?>