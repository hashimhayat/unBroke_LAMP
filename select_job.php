
<?php
	$cxn = new mysqli( 
	  "warehouse.cims.nyu.edu",
	  "hh1316", 
	  "kt5uns2u", 
	  "hh1316_unbroke"
	);

	$cookie_name = 'unbroke_userID';
	if(!isset($_COOKIE[$cookie_name])) {
	     //echo "Cookie named '" . $cookie_name . "' is not set!";
	} else {
	     $user_id = $_COOKIE[$cookie_name];
	}

	$name = 'guest';
	$q1 = "SELECT * FROM `user_details` WHERE user_id='{$user_id}'";
	$resp = $cxn->query($q1);

	if ($resp->num_rows > 0){
		$row = $resp->fetch_assoc();
		$name = $row['fullname'];
	}

	function check_status($status){
		if ($status == 0) { echo "<font color=\"Blue\">" . 'Considering.' . "</font>"; }
		elseif ($status == 1) {echo "<font color=\"Green\">" . 'Accepted.' . "</font>";}
		elseif ($status == -1) {echo "<font color=\"Red\">" . 'Rejected.' . "</font>";}
	}

	function get_user_info($user_id, $cxn) {
		$user = "SELECT * FROM `user_details` WHERE user_id='{$user_id}'";

		$rep = $cxn->query($user);
		if ($rep->num_rows > 0){
			$row = $rep->fetch_assoc();
			return $row;
		}
  		return "not found";
	}

	function get_job_info_from_id($job_id, $cxn) {
		$q3 = "SELECT * FROM `jobs` WHERE job_id='{$job_id}'";

		$r = $cxn->query($q3);
		if ($r->num_rows > 0){
			$val = $r->fetch_assoc();
			return $val;
		}
  		return "not found";
	}

	function get_job_info($offer_id, $cxn) {
		$user = "SELECT * FROM `job_offerings` WHERE offer_id='{$offer_id}'";

		$rep = $cxn->query($user);
		if ($rep->num_rows > 0){
			$row = $rep->fetch_assoc();
			return $row;
		}
  		return -1;
	}

	if(isset($_GET["offer_id"])) {

		$present = true;
		$offer_id = $_GET["offer_id"];
		$info = get_job_info($offer_id, $cxn);

		if ($info != -1){
			$provider = $info['user_id'];
			$receiver = $user_id;
			$job_id = $info['job_id'];
			$accept = 0;
		}

		$check = "SELECT * FROM `job_applications` WHERE (receiver='{$user_id}' AND offer_id='{$offer_id}')";
		$response = $cxn->query($check);
			if ($response->num_rows == 0){
				$present = false;
			}

		$q1 = "INSERT INTO job_applications (
											offer_id,
											provider,
											receiver,
											job_id,
											accept
										)
										VALUES (
											'{$offer_id}',
											'{$provider}',  
											'{$receiver}',  
											'{$job_id}',  
											'{$accept}'  
										);";
		if ($present == false){
			$response = $cxn->query($q1);
		} else {
			echo "You have already applied for this job.";
		}
	}

	$app = "SELECT * FROM `job_applications` WHERE receiver='{$user_id}'";
	$applications = $cxn->query($app);

?>


<!doctype html>
<html>
	<head>
 		<link href="css/blog.css"  type="text/css" rel="stylesheet" />
 		<title>Unbroke</title >
 	</head>

 	<ul>
  		<li><a href="home.php">Home</a></li>
  		<li><a href="provider.php">Review Center</a></li>
  		<li><a href="select_job.php">My Application</a></li>
  		<li><a href="reports.php">Reports</a></li>
  		<li><a href="about.html">About</a></li>
  		<li style="float:right"><a class="active" href="logout.php?user_id=<?php echo $user_id;?>">Logout</a></li>
	</ul>

	<header>
		UnBroke - My Applications
	</header>

		<body>
			<p>Welcome <font color="green"> <?php echo $name; ?></font> to the Application Center.</p>

			<div class="container">

				<div class = "my_jobs"> 
					<p>Manage your application.</p>
					<pre><strong><code>Jobs applied:</code></strong></pre>
					
					<div style="padding-left: 30px;"> <code>
						<?php while ($row = $applications->fetch_assoc()): ?>

							<?php $job_information = get_job_info_from_id($row['job_id'], $cxn);?>
							<?php $job_detail = get_job_info($row['offer_id'], $cxn);?> 
							<?php $user_detail = get_user_info($row['provider'], $cxn);?>

							<strong>ID:</strong> <?php echo $row['app_id']; ?> 
							<strong>Title:</strong> <?php echo $job_information['title']; ?> <br>
							<strong>Salary:</strong> $<?php echo $job_detail['salary']; ?> <br>
							<strong>Provider:</strong> <?php echo $user_detail['fullname']; ?> <br>
							<strong>City:</strong> <?php echo $user_detail['city']; ?> 
							<strong>Zipcode:</strong> <?php echo $user_detail['zip']; ?> <br>
							<strong>Status:</strong> <?php check_status($row['accept']); ?> <br><br>

							<a href="delete_app.php?app_id=<?php echo $row['app_id'];?>"> 
								<img style="width:15px;height:15px;" class='delete' src='images/delete.png' /> 
							</a>


							<br><br>
						<?php endwhile; ?>
					</code> </div>

				</div>
			</div>
	</body>
</html>

