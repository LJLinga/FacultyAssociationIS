<html>

	<?php 

		session_start();

		if ($_SESSION['usertype'] != 2) {

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
			
		}

	?>
	
	<body>

		<h1>You have already submitted an application. Wait for the admin to finish evaluating your application.</h1> <br>
		After the admin has approved your loan application, you can apply for another loan again. <p>
		<a href="member homepage.php"> Go back to member dashboard </a>

	</body>

</html>