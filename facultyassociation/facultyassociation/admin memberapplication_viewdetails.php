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

		require_once('../mysql_connect_FA.php');

		$queryMem = "SELECT M.MEMBER_ID, M.LASTNAME, M.FIRSTNAME, M.MIDDLENAME, M.SEX, C.STATUS, M.BIRTHDATE, M.HOME_ADDRESS, M.BUSINESS_ADDRESS, M.HOME_NUM, 
					 M.BUSINESS_NUM, M.DATE_APPLIED, D.DEPT_NAME, P.PAYROLL_NAME, M.DATE_HIRED
					 FROM MEMBER AS M
					 JOIN REF_DEPARTMENT AS D
					 ON M.DEPT_ID = D.DEPT_ID
					 JOIN REF_PAYROLL AS P
					 ON M.PAYROLL_ID = P.PAYROLL_ID
					 JOIN CIV_STATUS AS C
					 ON M.CIV_STATUS = C.STATUS_ID
					 WHERE M.MEMBER_ID = '{$selectedid}' AND USER_STATUS_ID = '1' AND APP_STATUS_ID = '1'";

		$resultMem = mysqli_query($dbc, $queryMem);
		$rowMem = mysqli_fetch_array($resultMem);

		if (isset($_POST['approve'])) {

			$queryAcceptMem = "UPDATE MEMBER SET APP_STATUS_ID = 2, EMP_ID_APPROVE = '{$empid}', DATE_ACCEPTED = NOW() WHERE MEMBER_ID = '{$selectedid}'";

			$resultAcceptMem = mysqli_query($dbc, $queryAcceptMem);

			$queryTransaction = "INSERT INTO TRANSACTIONS (MEMBER_ID, AMOUNT, TXN_DATE, TXN_TYPE, TXN_STATUS, EMP_ID_INVOLVED) 
								 VALUES ({$selectedid}, 0, NOW(), 1, 'Member Application Approved', {$empid})";

			$resultTransaction = mysqli_query($dbc, $queryTransaction);

		}

		else if (isset($_POST['reject'])) {

			$queryRejectMem = "UPDATE MEMBER SET APP_STATUS_ID = 3 WHERE MEMBER_ID = '{$selectedid}'";

			$resultRejectMem = mysqli_query($dbc, $queryRejectMem);

			$queryTransaction = "INSERT INTO TRANSACTIONS (MEMBER_ID, AMOUNT, TXN_DATE, TXN_TYPE, TXN_STATUS, EMP_ID_INVOLVED) 
								 VALUES ({$selectedid}, 0, NOW(), 1, 'Member Application Rejected', {$empid})";

			$resultTransaction = mysqli_query($dbc, $queryTransaction);

		}

	?>

	<body>

		<h1><?php echo $rowMem['FIRSTNAME'] . " " . $rowMem['LASTNAME'] . "'s "; ?>Membership Application Details</h1>

		<fieldset>

			<h2> Member Info </h2>

			Member ID: <?php echo $rowMem['MEMBER_ID']; ?><p>
			Name: <?php echo $rowMem['LASTNAME'] . ", " . $rowMem['FIRSTNAME'] . " " . $rowMem['MIDDLENAME']; ?><p>
			Department: <?php echo $rowMem['DEPT_NAME']; ?><p>
			Payroll: <?php echo $rowMem['PAYROLL_NAME']; ?><p>
			Date Hired: <?php echo $rowMem['DATE_HIRED']; ?><p>
			Date Applied: <?php echo $rowMem['DATE_APPLIED']; ?><p>

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

		<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

			<input type="submit" name="approve" value="Approve Application">
			<input type="submit" name="reject" value="Reject Application">

		</form>

		<hr>

		<a href="admin manage_member_applications.php">

			<button type="button">Go back to Member Applications</button>

		</a>

	</body>

</html>