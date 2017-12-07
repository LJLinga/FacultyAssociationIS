<html>

	<?php

		session_start();

		if ($_SESSION['usertype'] != 1) {

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
			
		}

		require_once('../mysql_connect_FA.php');

		$selectedrecord = $_SESSION['selectedrecord'];
		$empid = $_SESSION['idnum'];

		$queryLoan = "SELECT ML.DATE_APPLIED, ML.MEMBER_ID, M.LASTNAME, M.FIRSTNAME, D.DEPT_NAME, P.PAYROLL_NAME, B.BANK_ABBV, B.BANK_NAME, L.AMOUNT, L.INTEREST, 
					  L.PAYMENT_TERMS, L.NUM_PAYMENTS, L.TOTAL_DEDUCTION, L.MONTHLY_DEDUCTION, L.PER_PAYMENT_DEDUCTION
					  FROM MEMBER_LOANS AS ML
					  JOIN MEMBER AS M ON ML.MEMBER_ID = M.MEMBER_ID
					  JOIN LOAN_LIST AS L ON ML.LOAN_ID = L.LOAN_ID
                      JOIN REF_DEPARTMENT AS D ON M.DEPT_ID = D.DEPT_ID
                      JOIN REF_PAYROLL AS P ON M.PAYROLL_ID = P.PAYROLL_ID
                      JOIN BANK_LIST AS B ON L.BANK_ID = B.BANK_ID
                      WHERE ML.RECORD_ID = '{$selectedrecord}'";

		$resultLoan = mysqli_query($dbc, $queryLoan);
		$resultRow = mysqli_fetch_array($resultLoan);

		$name = $resultRow['LASTNAME'] . ", " . $resultRow['FIRSTNAME'];

		if (isset($_POST['approve'])) {

			$queryAcceptMem = "UPDATE MEMBER_LOANS SET APP_STATUS = 2, LOAN_STATUS = 2, EMP_ID_APPROVED = '{$empid}', DATE_APPROVED = NOW(), PICKUP_STATUS = 2 WHERE RECORD_ID = '{$selectedrecord}'";

			$resultAcceptMem = mysqli_query($dbc, $queryAcceptMem);

			$queryTransaction = "INSERT INTO TRANSACTIONS (MEMBER_ID, AMOUNT, TXN_DATE, TXN_TYPE, TXN_STATUS, EMP_ID_INVOLVED) 
								 VALUES ($resultRow['MEMBER_ID'], 0, NOW(), 1, 'Bank Loan Application Approved', {$empid})";

			$resultTransaction = mysqli_query($dbc, $queryTransaction);

		}

		else if (isset($_POST['reject'])) {

			$queryRejectMem = "UPDATE MEMBER_LOANS SET APP_STATUS = 3 WHERE RECORD_ID = '{$selectedrecord}'";

			$resultRejectMem = mysqli_query($dbc, $queryRejectMem);

			$queryTransaction = "INSERT INTO TRANSACTIONS (MEMBER_ID, AMOUNT, TXN_DATE, TXN_TYPE, TXN_STATUS, EMP_ID_INVOLVED) 
								 VALUES ($resultRow['MEMBER_ID'], 0, NOW(), 1, 'Bank Loan Application Rejected', {$empid})";

			$resultTransaction = mysqli_query($dbc, $queryTransaction);

		}

	?>

	<style>

		 	table, th, td{border: 1px solid black; text-align: center;}

	</style>

	<body>

		<table align="center" id="table" style="width: 40%;">

			<thead>

			<tr>

				<th>Description</th>
				<th>Value</th>

			</tr>

			</thead>

			<tbody>

				<tr>

					<td>Date Applied</td>
					<td><?php echo $resultRow['DATE_APPLIED']; ?></td>

				</tr>

				<tr>

					<td>Member ID</td>
					<td><?php echo $resultRow['MEMBER_ID']; ?></td>

				</tr>

				<tr>

					<td>Member Name</td>
					<td><?php echo $name; ?></td>

				</tr>

				<tr>

					<td>Department</td>
					<td><?php echo $resultRow['DEPT_NAME']; ?></td>

				</tr>

				<tr>

					<td>Payroll Group</td>
					<td><?php echo $resultRow['PAYROLL_NAME']; ?></td>

				</tr>

				<tr>

					<td>Instutition From</td>
					<td><?php echo $resultRow['BANK_NAME']; ?></td>

				</tr>

				<tr>

					<td>Instutition Abbv.</td>
					<td><?php echo $resultRow['BANK_ABBV']; ?></td>

				</tr>

				<tr>

					<td>Loan Amount</td>
					<td><?php echo $resultRow['AMOUNT']; ?> Pesos</td>

				</tr>

				<tr>

					<td>Interest %</td>
					<td><?php echo $resultRow['INTEREST']; ?>%</td>

				</tr>

				<tr>

					<td>Total Amount to Pay</td>
					<td><?php echo $resultRow['TOTAL_DEDUCTION']; ?> Pesos</td>

				</tr>

				<tr>

					<td>Payment Terms</td>
					<td><?php echo $resultRow['PAYMENT_TERMS']; ?> Months</td>

				</tr>

				<tr>

					<td>Number of Payments</td>
					<td><?php echo $resultRow['NUM_PAYMENTS']; ?> Payments</td>

				</tr>

				<tr>

					<td>Monthly Deduction</td>
					<td><?php echo $resultRow['MONTHLY_DEDUCTION']; ?> Pesos</td>

				</tr>

				<tr>

					<td>Per Pay Period Deduction</td>
					<td><?php echo $resultRow['PER_PAYMENT_DEDUCTION']; ?> Pesos</td>

				</tr>

			</tbody>

		</table><p>

		<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

		<input type="submit" name="approve" value="Approve Selected Application">
		<input type="submit" name="reject" value="Reject Selected Application"><p>

		</form>

		<hr>

		<a href="admin manage_healthaid_applications.php">

			<button type="button">Go back to Health Aid Applications</button>

		</a>

	</body>

</html>