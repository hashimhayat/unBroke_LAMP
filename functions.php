<?php
	
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
  		return -1;
	}

	function get_job_info_from_offer($offer_id, $cxn) {
		$user = "SELECT * FROM `job_offerings` WHERE offer_id='{$offer_id}'";

		$rep = $cxn->query($user);
		if ($rep->num_rows > 0){
			$row = $rep->fetch_assoc();
			return $row;
		}
  		return -1;
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
?>