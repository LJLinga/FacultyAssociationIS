<html>

	<header>


	</header>

	<?php

		session_start();

		if ($_SESSION['usertype'] != 1) {

				header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");

		}

		$empid = $_SESSION['idnum'];
		$selectedid = $_SESSION['selectedmemid'];
		$message = NULL;

		require_once('../mysql_connect_FA.php');

		$queryMem = "SELECT M.MEMBER_ID, M.LASTNAME, M.FIRSTNAME, M.MIDDLENAME, M.SEX, C.STATUS, M.BIRTHDATE, M.HOME_ADDRESS, M.BUSINESS_ADDRESS, M.HOME_NUM, 
					 M.BUSINESS_NUM, M.DATE_ACCEPTED, D.DEPT_NAME, P.PAYROLL_NAME, M.DATE_HIRED, U.STATUS AS MSTATUS
					 FROM MEMBER AS M
					 JOIN REF_DEPARTMENT AS D
					 ON M.DEPT_ID = D.DEPT_ID
					 JOIN REF_PAYROLL AS P
					 ON M.PAYROLL_ID = P.PAYROLL_ID
					 JOIN CIV_STATUS AS C
					 ON M.CIV_STATUS = C.STATUS_ID
					 JOIN USER_STATUS AS U
					 ON M.USER_STATUS_ID = U.STATUS_ID
					 WHERE M.MEMBER_ID = '{$selectedid}'";

		$resultMem = mysqli_query($dbc, $queryMem);
		$rowMem = mysqli_fetch_array($resultMem);

	?>

	<body>

		<h1><?php echo $rowMem['FIRSTNAME'] . " " . $rowMem['LASTNAME'] . "'s "; ?>Membership Application Details</h1>

		<?php if (isset($message)) echo $message . "<p>"; ?>

		<fieldset>

			<h2> Member Info </h2>

			Member Status: <?php echo $rowMem['MSTATUS'] ?><p>
			Member ID: <?php echo $rowMem['MEMBER_ID']; ?><p>
			Name: <?php echo $rowMem['LASTNAME'] . ", " . $rowMem['FIRSTNAME'] . " " . $rowMem['MIDDLENAME']; ?><p>
			Department: <?php echo $rowMem['DEPT_NAME']; ?><p>
			Payroll: <?php echo $rowMem['PAYROLL_NAME']; ?><p>
			Date Hired: <?php echo $rowMem['DATE_HIRED']; ?><p>
			Date Accepted: <?php echo $rowMem['DATE_ACCEPTED']; ?><p>

			<hr>

			<h2> Personal Info </h2>

			Sex: <?php

				if ($rowMem['SEX'] == 0) {

					echo "Male";

				}

				else {

					echo "Female";

				}

			?><p>
			Birthdate: <?php echo $rowMem['BIRTHDATE']; ?><p>
			Civil Status: <?php echo $rowMem['STATUS']; ?><p>
			Home Address: <?php echo $rowMem['HOME_ADDRESS']; ?><p>
			Business Address: <?php echo $rowMem['BUSINESS_ADDRESS']; ?><p>
			Home Phone Number: <?php echo $rowMem['HOME_NUM']; ?><p>
			Business Phone Number: <?php echo $rowMem['BUSINESS_NUM']; ?><p>

		</fieldset><p>

		<hr>

		<a href="admin check_lifetime_status.php">

			<button type="button">Go back to Eligible Lifetime Members</button>

		</a>

	</body>

</html>