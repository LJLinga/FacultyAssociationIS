<html>

	<header>

		<title>ADMIN Homepage - Faculty Association</title>

	</header>

	<?php 

		session_start();

		if ($_SESSION['usertype'] != 1) {

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
			
		}

	?>

	<body>

		<h1>WELCOME ADMIN</h1><br>
		These are the features of the admin role<p>
		<a href="logout.php">Logout Account</a><p>

		<hr>

		<a href="admin check_lifetime_status.php">Check Members Eligible for Lifetime Membership</a><p>
		<a href="admin manage_members.php">Manage Active Members</a><p>
		<a href="admin manage_member_applications.php">Manage Membership Applications</a><p>
		<a href="admin manage_healthaid_applications.php">Manage Health Aid Applications</a><p>
		<a href="admin manage_bankloan_applications.php">Manage Bank Loan Applications</a><p>
		<a href="admin manage_falp_applications.php">Manage FALP Applications</a><p>
		<a href="Manage Product Offering.php">Manage Product List</a><p>
		<a href="Manage Partner Banks.php">Manage Partner Banks</a><p>
		<a href="confirmApproval.php">Confirm Approval</a><p>
		<a href="hClaimApproval.php">Health Claim Approval</a><p>
		<a href="generate-billing-statement.php">Generate Billing Statement</a><p>

	</body>

</html>