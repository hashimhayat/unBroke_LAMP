<?php
	$cxn = new mysqli( 
	  "warehouse.cims.nyu.edu",
	  "hh1316", 
	  "kt5uns2u", 
	  "hh1316_unbroke"
	);

	$value = $_GET["status"];
	$app_id = $_GET["app_id"];
	var_dump($_GET);

	if ($value == "accept"){
		echo "accepted";
		$q = "UPDATE `job_applications` SET accept=1 WHERE app_id='{$app_id}';";
	} else {
		$q = "UPDATE `job_applications` SET accept=-1 WHERE app_id='{$app_id}';";
	}

	$response = $cxn->query($q);
	$cxn->close();
	header("Location:provider.php");
?>