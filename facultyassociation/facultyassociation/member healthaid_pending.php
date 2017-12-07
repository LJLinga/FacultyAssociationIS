<html>

	<?php 

		session_start();

		if ($_SESSION['usertype'] != 2) {

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
			
		}

	?>
	
	<body>

		<h1>You have already submitted an application. Wait for the admin to finish evaluating your application.</h1> <p>
		<a href="member homepage.php"> Go back to member dashboard </a>

	</body>

</html>