
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

	function num_apps($offer_id, $cxn){
		$user = "SELECT * FROM `job_applications` WHERE offer_id='{$offer_id}'";

		$rep = $cxn->query($user);
		if ($rep->num_rows > 0){
			return $rep->num_rows;
		}
  		return 0;
	}

	function get_job_application($offer_id, $cxn) {
		$user = "SELECT * FROM job_applications WHERE offer_id='{$offer_id}'";

		$rep = $cxn->query($user);
		if ($rep->num_rows > 0){
			$row = $rep->fetch_assoc();
			return $row;
		}
  		return -1;
	}

	$app = "SELECT * FROM job_offerings,jobs WHERE job_offerings.user_id='{$user_id}' AND jobs.job_id=job_offerings.job_id";
	$applications = $cxn->query($app);

	$review = "SELECT * FROM job_applications, jobs, user_details 
				WHERE job_applications.provider='{$user_id}' 
				AND jobs.job_id=job_applications.job_id 
				AND user_details.user_id=job_applications.receiver";
	$review_apps = $cxn->query($review);
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
		unBroke - Review Jobs
	</header>

		<body>
			<p>Welcome <font color="green"> <?php echo $name; ?></font> to the Application Center.</p>

			<div class="container">

				<div class = "my_jobs"> 
					<p>Review Center</p>
					<pre><strong><code>Jobs posted:</code></strong></pre>
					
					<div style="padding-left: 30px;"> <code>
						<?php while ($row = $applications->fetch_assoc()): ?>

							<?php $job_info = get_job_info_from_id($row['job_id'], $cxn); ?>
							<?php $user_info = get_user_info($row['user_id'], $cxn); ?>
							<?php $num_app = num_apps($row['offer_id'], $cxn); ?>

							<strong>Offer ID:</strong> <?php echo $row['offer_id']; ?> 
							<strong>Title:</strong> <?php echo $job_info['title']; ?> <br>
							<strong>Salary Offered:</strong>  <?php echo $row['salary']; ?> <br>
							<strong>Description:</strong>  <?php echo $row['comments']; ?> <br>
							<strong>Applied by: </strong>  <?php echo $num_app; ?> 
							<br><br>

							<a href="delete_app.php?app_id=<?php echo $row['app_id'];?>"> 
								<img style="width:15px;height:15px;" class='delete' src='images/delete.png' /> 
							</a>
							
							<br><br>
						<?php endwhile; ?>
					</code> </div>
				</div>

				<div class = "review_jobs"> 
					<pre><strong><code>Review Jobs:</code></strong></pre>
					
					<div style="padding-left: 30px;"> <code>
						<?php while ($row = $review_apps->fetch_assoc()): ?>

							<strong>Offer ID:</strong> <?php echo $row['offer_id']; ?>
							<strong>Job Title:</strong> <?php echo $row['title']; ?> <br>
							<strong>Applied by:</strong> <?php echo $row['fullname']; ?> <br>
							<strong>Occupation:</strong> <?php echo $row['occupation']; ?> <br>
							<strong>Bio:</strong> <?php echo $row['bio']; ?> <br>
							<strong>City:</strong> <?php echo $row['city']; ?> <br>
							<strong>Accept:</strong> <?php echo $row['accept']; ?> <br>
							<br>	

							<a href="process_app.php?status=<?php echo "accept&app_id=$row[app_id]";?>"> 
								<img style="width:15px;height:15px;" class='accept' src='images/accept.png' /> 
							</a>
							<a href="process_app.php?status=<?php echo "reject&app_id=$row[app_id]";?>"> 
								<img style="width:15px;height:15px;" class='reject' src='images/reject.png' /> 
							</a>
							<a href="delete_app.php?app_id=<?php echo $row['app_id'];?>"> 
								<img style="width:15px;height:15px;" class='delete' src='images/delete.png' /> 
							</a>
							<br><br>

						<?php endwhile; ?>
					</code> </div><br><br><br><br><br>
				</div>

			</div>
	</body>
</html>

