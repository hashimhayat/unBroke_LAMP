
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
		if ($status == 0) { return "<font color=\"Blue\">" . 'Considering.' . "</font>"; }
		elseif ($status == 1) {return "<font color=\"Green\">" . 'Accepted.' . "</font>";}
		elseif ($status == -1) {return "<font color=\"Red\">" . 'Rejected.' . "</font>";}
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

	$clicked_user = -1;

	// All Active users
	$all_users = "SELECT * FROM user_details, sign_up WHERE sign_up.status=1 AND user_details.user_id=sign_up.user_id ORDER BY sign_up.time DESC";
	$get_all_users = $cxn->query($all_users);

	// All Accepted/Rejected Jobs
	$all_accepted = "SELECT * FROM jobs, job_applications WHERE jobs.job_id=job_applications.job_id ORDER BY job_applications.accept DESC LIMIT 0,20";
	$get_jobs = $cxn->query($all_accepted);

	$category_selected = "Arts";

	$q2 = "SELECT category FROM `jobs` WHERE 1 GROUP BY category";
	$category = $cxn->query($q2);
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
		UnBroke - Reports
	</header>

		<body>
			<p>Welcome <font color="green"> <?php echo $name; ?></font> to Reports.</p>

			<div class="container">

			<!--  -->
				<div class = "active_users"> 
					<pre><strong><code>Report 1: Active Users</code></strong></pre>
					
					<div style="padding-left: 30px;"> <code>
						<?php while ($row = $get_all_users->fetch_assoc()): ?>
							
							<hr> 
							<strong>Name:</strong>
							<a href="reports.php?domain=active_users&amp;user_id=<?php echo $row["user_id"];?>"> 
								 <?php echo $row['fullname']; ?>
							</a>

							<?php
								if (isset($_GET['domain'])){
									if ($_GET['domain']=="active_users"){
										$clicked_user = $_GET['user_id'];
										$result = get_user_info($clicked_user, $cxn);
										if ($clicked_user == $row["user_id"]){
											echo "<br><strong>City: </strong>" . $result['city'];  
											echo "<br><strong>Occupation: </strong>" . $result['occupation'];  
										} 
									}
								}
							?>
							<br><a href="send_message.php?domain=active_users&amp;user_id=<?php echo $row["user_id"];?>"> Send Message </a>
						<?php endwhile; ?><hr>
					</code> </div>
				</div>
			<!--  -->

			<!--  -->
				<div class = "job_status"> 
					<pre><strong><code>Report 2: Posted Jobs by Status</code></strong></pre>
					
					<div style="padding-left: 30px;"> <code>
						<?php while ($row = $get_jobs->fetch_assoc()): ?>
							
							<hr> 
							<strong>Title:</strong>
							<a href="reports.php?domain=job_status&amp;app_id=<?php echo $row["app_id"];?>"> 
								 <?php echo $row['title']; ?>
							</a>

							<?php
								if (isset($_GET['domain'])){
									if ($_GET['domain']=="job_status"){
										$app_id = $_GET['app_id'];
										if ($app_id == $row["app_id"]){
											echo "<br><strong>Category: </strong>" . $row['category'];  
											echo "<br><strong>Status: </strong>" . check_status($row['accept']);  
										} 
									}
								}
							?>

							<!-- <br><a href="send_message.php?user_id=<?php echo $row["user_id"];?>"> Send Message </a><hr> -->
							<br>
						<?php endwhile; ?><hr>
					</code> </div>
				</div>
			<!--  -->

			<!--  -->
				<div class = "jobs_by_category"> 
					<pre><strong><code>Report 3: Jobs by Category</code></strong></pre>
					
					<hr>
					<form action="#" method="post">
						<select name="cats">
							<?php while ($row = $category->fetch_assoc()): ?>
					  			<?php echo "<option value='" . $row['category'] . "' selected>" . $row['category'] . "</option>"; ?>
					  		<?php endwhile; ?>
						</select>
						<input type="submit" name="select_category" value="Select" /> <code> press me after selecting an option.</code>
					</form>
					<code>
						<?php
							if (isset($_POST['cats'])){
								$category_selected = $_POST['cats'];
								$jobs_in_category = "SELECT * FROM jobs, job_offerings 
													WHERE jobs.job_id=job_offerings.job_id 
													AND jobs.category='{$category_selected}' 
													ORDER BY job_offerings.offer_time DESC";
								$jobs_category = $cxn->query($jobs_in_category);

							while ($row = $jobs_category->fetch_assoc()):
								echo "<br><strong>Job Title: </strong>" . $row['title'] . "<br>";
		
							endwhile; 
							}
						?>

					</code> </div>
				</div>
			<!--  -->

			</div>







	</body>
</html>

