<html>

	<?php 

		session_start();

		if ($_SESSION['usertype'] != 2) {

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
			
		}

	?>
	
	<body>

		<h1>You are already part of the Health Aid Beneficiaries. You cannot submit another application.</h1> <p>
		<a href="member homepage.php"> Go back to member dashboard </a>

	</body>

</html>