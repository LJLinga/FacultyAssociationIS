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
					 M.BUSINESS_NUM, M.DATE_ACCEPTED, D.DEPT_NAME, P.PAYROLL_NAME, M.DATE_HIRED, U.STATUS AS MSTATUS, M.HEALTH_AID_STATUS, M.LIFETIME_APP_STATUS,
					 M.ELIGIBLE_LIFETIME
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

		if (isset($_POST['changestatus'])) {

			$queryLifetime = "SELECT M.LIFETIME_APP_STATUS FROM MEMBER AS M 
							  WHERE M.MEMBER_ID = '{$selectedid}'";

			$resultLifetime = mysqli_query($dbc, $queryLifetime);
			$rowLifetime = mysqli_fetch_array($resultLifetime);

			$status = $_POST['changestatus'];

			if ($status == 2 || $status == 4 || $status == 5 || ($status == 3 && $rowLifetime['LIFETIME_APP_STATUS'] != 2)) {

				$message .= "You changed this member's status from " . $rowMem['MSTATUS'] . " to ";

				$queryStatus = "UPDATE MEMBER SET USER_STATUS_ID = '{$status}', APP_STATUS_ID = 3, HEALTH_AID_STATUS = 3, DATE_REMOVED = NOW() WHERE MEMBER_ID = '{$selectedid}'";

				$resultStatus = mysqli_query($dbc, $queryStatus);

				$queryStatus2 = "SELECT U.STATUS FROM MEMBER AS M
								 JOIN USER_STATUS AS U
								 ON M.USER_STATUS_ID = U.STATUS_ID
								 WHERE M.MEMBER_ID = '{$selectedid}'";

				$resultStatus2 = mysqli_query($dbc, $queryStatus2);
				$rowStatus = mysqli_fetch_array($resultStatus2);

				$message .= $rowStatus['STATUS'];

			}

			else {

				$message .= "You changed this member's status from " . $rowMem['MSTATUS'] . " to ";

				$queryStatus = "UPDATE MEMBER SET USER_STATUS_ID = '{$status}' WHERE MEMBER_ID = '{$selectedid}'";

				$resultStatus = mysqli_query($dbc, $queryStatus);

				$queryStatus2 = "SELECT U.STATUS FROM MEMBER AS M
								 JOIN USER_STATUS AS U
								 ON M.USER_STATUS_ID = U.STATUS_ID
								 WHERE M.MEMBER_ID = '{$selectedid}'";

				$resultStatus2 = mysqli_query($dbc, $queryStatus2);
				$rowStatus = mysqli_fetch_array($resultStatus2);

				$message .= $rowStatus['STATUS'];

			}

		}

	?>

	<body>

		<h1><?php echo $rowMem['FIRSTNAME'] . " " . $rowMem['LASTNAME'] . "'s "; ?>Membership Application Details</h1>

		<?php if (isset($message)) echo $message . "<p>"; ?>

		<?php if (isset($_POST['edit'])) { ?>

			<b>Change Member Status to: </b><p>

			<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

				<select name="changestatus">

					<option value="2">DECEASED</option>
					<option value="3">RETIRED</option>
					<option value="4">RESIGNED</option>
					<option value="5">TERMINATED</option>

				</select>

				<input type="submit" name="change" value="Confirm Changes">

			</form>

		<?php } ?>

		<fieldset>

			<h2> Member Info </h2>

			Member Status: <?php echo $rowMem['MSTATUS'] ?><p>
			Member ID: <?php echo $rowMem['MEMBER_ID']; ?><p>
			Name: <?php echo $rowMem['LASTNAME'] . ", " . $rowMem['FIRSTNAME'] . " " . $rowMem['MIDDLENAME']; ?><p>
			Department: <?php echo $rowMem['DEPT_NAME']; ?><p>
			Payroll: <?php echo $rowMem['PAYROLL_NAME']; ?><p>
			Date Hired: <?php echo $rowMem['DATE_HIRED']; ?><p>
			Date Accepted: <?php echo $rowMem['DATE_ACCEPTED']; ?><p>
			Has Health Aid: <?php if ($rowMem['LIFETIME_APP_STATUS'] == 2) echo "Yes";
								  else echo "No"; ?><p>
			Eligible for Lifetime: <?php if ($rowMem['ELIGIBLE_LIFETIME'] == 1) echo "Yes";
										 else echo "No"; ?><p>
			Is Lifetime Member: <?php if ($rowMem['LIFETIME_APP_STATUS'] == 2) echo "Yes";
									  else echo "No"; ?><p>

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

			<input type="submit" name="edit" value="Edit Member Status">

		</form><p>

		<hr>

		<a href="admin manage_members.php">

			<button type="button">Go back to Member Applications</button>

		</a>

	</body>

</html>