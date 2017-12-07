<html>

	<header>

		<title>ADMIN Homepage - Faculty Association</title>

	</header>

	<?php 

		session_start();

		if ($_SESSION['usertype'] != 2) {

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
			
		}

	?>

	<body>

		<h1>WELCOME MEMBER</h1><br>
		These are the features of the member<p>
		<a href="logout.php">Logout Account</a><p>

		<hr>

		<a href="member healthaid_application.php">Apply for Health-Aid Benefits</a><p>
		<a href="member falp_application.php">Apply for a FALP Loan</a><p>
		<a href="member bankloan_application.php">Apply for a Bank Loan</a><p>
		<a href="lifetime-application.php">Apply for Lifetime Membership</a><p>
		<a href="Manage Shopping Cart.php">Buy Products</a><p>
		<a href="applyHAid.php">Apply for Health Aid Claim</a><p>
		<a href="displayCTransactions.php">Your Transactions</a><p>

	</body>

</html>