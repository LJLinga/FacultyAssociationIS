<html>

	<header>

		<title>Login - Faculty Association Portal</title>

	</header>

	<?php

		session_start();

		if (isset($_POST['submit'])) {

			$message = NULL;

			if (empty($_POST['idnum'])){

				$_SESSION['idnum']=FALSE;
 				$message.='<p>You forgot to enter your username!';

			}

			else {

				$_SESSION['idnum'] = $_POST['idnum'];

			}

			if (empty($_POST['password'])) {

				$_SESSION['password']=FALSE;
 				$message.='<p>You forgot to enter your password!';

			}

			else {

				$_SESSION['password'] = $_POST['password'];

			}

			
			if (!isset($message)) {

				$idnum = $_SESSION['idnum'];
				$password = $_SESSION['password'];

				require_once('../mysql_connect_FA.php');
				$queryMem = "SELECT MEMBER_ID, PASSWORD, APP_STATUS_ID, FIRSTNAME, LASTNAME FROM MEMBER WHERE MEMBER_ID = '{$idnum}' AND PASSWORD = PASSWORD('{$password}')";
				$resultMem = mysqli_query($dbc, $queryMem);
				$rowMem = mysqli_fetch_array($resultMem);

				$name = $rowMem['FIRSTNAME'] . " " . $rowMem['MIDDLENAME'] . " " . $rowMem['LASTNAME'];
				$_SESSION['member_name'] = name;

				$queryEmp = "SELECT MEMBER_ID, PASSWORD, APP_STATUS_ID FROM EMPLOYEE WHERE MEMBER_ID = '{$idnum}' AND PASSWORD = PASSWORD('{$password}')";
				$resultEmp = mysqli_query($dbc, $queryEmp);
				$rowEmp = mysqli_fetch_array($resultEmp);

				if ($rowMem['MEMBER_ID'] == $_SESSION['idnum'] && $rowMem['APP_STATUS_ID'] == 2) {

					header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/member homepage.php");
					$_SESSION['usertype'] = "2";

				}

				else if ($rowEmp['MEMBER_ID'] == $_SESSION['idnum'] && $rowEmp['APP_STATUS_ID'] == 2) {

					header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/admin homepage.php");
					$_SESSION['usertype'] = "1";

				}

				else {

					$message .= "<p> The username and password is invalid, or the account is not recognized by the Faculty Association.";

				}

			}

		}

	?>

	<body>

		<center>

		<h1>FACULTY ASSOCIATION PORTAL</h1>

		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

		<fieldset><legend><b>Enter your login credentials</b></legend><p>

		<?php if (isset($message)){
		
			echo '<font color="red">'.$message. '</font><p>';
		
		} ?>

			ID Number: <input type="text" name="idnum" size="18" maxlength="8"><p>
			Password: <input type="password" name="password"><p>

			<input type="submit" name="submit" value="Login"><p>

		</form>

		</fieldset>


		Don't have an account yet? Click below!<p>

		<a href="registration.php">

			<button type="button">Register here!</button>

		</a>

		</center>

	</body>

</html>