
<!DOCTYPE html>
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

	$q2 = "SELECT * FROM `jobs` WHERE 1";
	$resp = $cxn->query($q2);

	function get_job_id($selected_val, $cxn) {
		$q4 = "SELECT job_id FROM `jobs` WHERE title='{$selected_val}'";

		$resp = $cxn->query($q4);
		if ($resp->num_rows > 0){
			$row = $resp->fetch_assoc();
			return $row['job_id'];
		}
  		return "not found";
	}

	function get_job_category($job_id, $cxn) {
		$q3 = "SELECT category FROM `jobs` WHERE job_id='{$job_id}'";

		$resp = $cxn->query($q3);
		if ($resp->num_rows > 0){
			$row = $resp->fetch_assoc();
			return $row['category'];
		}
  		return "not found";
	}

	function get_job_info($job_id, $cxn) {
		$q3 = "SELECT * FROM `jobs` WHERE job_id='{$job_id}'";

		$r = $cxn->query($q3);
		if ($r->num_rows > 0){
			$val = $r->fetch_assoc();
			return $val;
		}
  		return "not found";
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

	$q3 = "SELECT * FROM `job_offerings` WHERE class=0 AND status=0 AND user_id !='{$user_id}' ORDER BY offer_time";
	$jobs_available = $cxn->query($q3);
?>


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
		Unbroke - Home
	</header>
		<body>
			<p>Welcome "<font color="green"> <?php echo $name; ?></font> " to unBroke.</p>

			<p></p>

			<div class="container">

				<p>Post a Job.</p>
				<div class = "select_job">
					<pre><strong><code>Select a Job:&nbsp;</code></strong></pre>

					<form action="#" method="post">
						<select name="job_title">
							<?php while ($row = $resp->fetch_assoc()): ?>
					  			<?php echo "<option value='" . $row['title'] . "' selected>" . $row['title'] . "</option>"; ?>
					  		<?php endwhile; ?>
						</select>
						<input type="submit" name="select_category" value="Select" /> <code> press me after selecting a job.</code>
					</form>

						<div style="padding-left: 30px;"> <code>
						<?php
							$success = false;
							if(isset($_POST['job_title'])){
								$job_selected = $_POST['job_title'];  
								$job_id = get_job_id($job_selected, $cxn);
								$job_category = get_job_category($job_id, $cxn); 

								echo "<br> Job Code : " . "<font color=\"purple\">" . $job_id . "</font>" ; 
								echo "<br> Job Selected : " . "<font color=\"purple\">" . $job_selected . "</font>"; 
								echo "<br> Job Category : " . "<font color=\"purple\">" . $job_category . "</font>";  
								$success = true;
							}
						?>
						</code> </div>
				</div>

				<div class = "post_job"> 

					<form action="post_job.php" method="post" id="posting">
						<input type="hidden" name="job_id" value= <?php echo "'" . $job_id . "'"; ?> >
						<input type="hidden" name="user_id" value= <?php echo "'" . $user_id . "'"; ?> >
						<input type="hidden" name="class" value="0">
						<input type="hidden" name="status" value="0">

					  	<pre><strong><code>Comments: </code></strong></pre> <input type="text" name="comment" placeholder="comments"> 
					  	<pre><strong><code>Salary:</code></strong></pre> <input type="number" name="salary" placeholder="salary e.g 15"> 
			  			<?php if ($success == true){ echo "<input type=\"submit\" value=\"Post\">"; } ?>
					</form> 
				</div>
				
				<hr>

				<div class = "post_job"> 
					<p>Find a Job.</p>
					<pre><strong><code>Available Jobs:</code></strong></pre>
					<code>Select a job by clicking  :  </code>
					<img style="width:15px;height:15px;" class='delete' src='images/select.png' /> <br><br>
					
					<div style="padding-left: 30px;"> <code>
						<?php while ($row = $jobs_available->fetch_assoc()): ?>
							
							<?php $job_info = get_job_info($row['job_id'], $cxn); ?>
							<?php $user_info = get_user_info($row['user_id'], $cxn); ?>

							<a href="select_job.php?offer_id=<?php echo $row['offer_id'];?>"> 
								<img style="width:15px;height:15px;" class='delete' src='images/select.png' /> 
							</a>

							<strong>Code:</strong> <?php echo $row['offer_id']; ?> 
							<strong>Title:</strong> <?php echo $job_info['title']; ?> 
							<strong>Salary:</strong>  <?php echo $row['salary']; ?> 
							<br>
							<strong>Description:</strong>  <?php echo $row['comments']; ?> 
							<strong>Posted by:</strong> <?php echo $user_info['fullname']; ?> 
							<br><br>
						<?php endwhile; ?>
					</code> </div>

				</div>

			</div>
				
		</body>
</html>















