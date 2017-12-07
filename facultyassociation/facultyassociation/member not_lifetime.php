<html>

	<?php 

		session_start();

		if ($_SESSION['usertype'] != 2) {

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
			
		}

	?>
	
	<body>

		<h1>You cannot access this page.</h1> <p>
		You might have already submitted an application, a lifetime member already, or not yet eligible to be a lifetime member. <p>
		<a href="member homepage.php"> Go back to member dashboard </a>

	</body>

</html>