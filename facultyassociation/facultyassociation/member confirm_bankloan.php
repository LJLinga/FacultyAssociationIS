<html>

	<?php

		session_start();

		if ($_SESSION['usertype'] != 2) {

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
			
		}

		require_once('../mysql_connect_FA.php');

		$selectedloan = $_SESSION['selectedloan'];

		$queryLoan = "SELECT B.BANK_NAME, B.BANK_ABBV, L.AMOUNT, L.INTEREST, L.PAYMENT_TERMS, L.NUM_PAYMENTS, L.TOTAL_DEDUCTION, L.MONTHLY_DEDUCTION, 
					  L.PER_PAYMENT_DEDUCTION
					  FROM LOAN_LIST AS L
					  JOIN BANK_LIST AS B ON L.BANK_ID = B.BANK_ID
					  WHERE L.LOAN_ID = '{$selectedloan}'";

		$resultLoan = mysqli_query($dbc, $queryLoan);
		$resultRow = mysqli_fetch_array($resultLoan);

		if (isset($_POST['apply'])) {

			$idnum = $_SESSION['idnum'];

			$queryApply = "INSERT INTO MEMBER_LOANS (MEMBER_ID, LOAN_ID, APP_STATUS, DATE_APPLIED, LOAN_STATUS, PICKUP_STATUS)

						   VALUES ('{$idnum}', '{$selectedloan}', '1', NOW(), '1', '1')";

			$resultApply = mysqli_query($dbc, $queryApply);

			$queryTransaction = "INSERT INTO TRANSACTIONS (MEMBER_ID, AMOUNT, TXN_DATE, TXN_TYPE, TXN_STATUS) 
								 VALUES ({$_SESSION['idnum']}, 0, NOW(), 1, 'Bank Loan Application Submitted')";

			$resultTransaction = mysqli_query($dbc, $queryTransaction);

		}

	?>

	<style>

		 	table, th, td{border: 1px solid black; text-align: center;}

	</style>

	<body>

	<h1>Selected Loan Details</h1>

		<table align="center" id="table" style="width: 40%;">

			<thead>

			<tr>

				<th>Description</th>
				<th>Value</th>

			</tr>

			</thead>

			<tbody>

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

		<div align="center">

		<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

			<input type="submit" name="apply" value="Comfirm Application Submission">

		</form><p>

		<hr>

		<a href="member bankloan_application.php">

			<button type="button">Go back to Bank Loan List</button>

		</a>

		</div>

	</body>

</html>