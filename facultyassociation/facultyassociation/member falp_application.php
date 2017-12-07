<html>

	<?php 

		session_start();

		if ($_SESSION['usertype'] != 2) {

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
			
		}

		$idnum = $_SESSION['idnum'];

		require_once('../mysql_connect_FA.php');

		$queryMem = "SELECT M.PAYROLL_ID FROM MEMBER AS M WHERE M.MEMBER_ID = '{$idnum}'";

		$resultMem = mysqli_query($dbc, $queryMem);
		$rowMem = mysqli_fetch_array($resultMem);

		$queryForm = "SELECT M.LOAN_ID FROM MEMBER_LOANS AS M
					  WHERE M.MEMBER_ID = '{$idnum}' AND M.APP_STATUS = 1";

		$resultForm = mysqli_query($dbc, $queryForm);
		$resultRowForm = mysqli_fetch_array($resultForm);

		if (!empty($resultRowForm)) {

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/member pending_falp.php");

		}

		if ($rowMem['PAYROLL_ID'] == 6 || $rowMem['PAYROLL_ID'] == 7) {

			$loanID = 2;

		}

		else {
		
			$loanID = 1;

		}

		$queryAmount = "SELECT L.AMOUNT, L.INTEREST, L.PAYMENT_TERMS, L.NUM_PAYMENTS, L.TOTAL_DEDUCTION, L.MONTHLY_DEDUCTION, 
					    L.PER_PAYMENT_DEDUCTION
						FROM LOAN_LIST AS L
						WHERE L.LOAN_ID = '{$loanID}'";

		$resultAmount = mysqli_query($dbc, $queryAmount);
		$resultRow = mysqli_fetch_array($resultAmount);

		if (isset($_POST['apply'])) {

			$queryApply = "INSERT INTO MEMBER_LOANS (MEMBER_ID, LOAN_ID, APP_STATUS, DATE_APPLIED, LOAN_STATUS, PICKUP_STATUS)

						   VALUES ('{$idnum}', '{$loanID}', '1', NOW(), '1', '1')";

			$resultApply = mysqli_query($dbc, $queryApply);

			$queryTransaction = "INSERT INTO TRANSACTIONS (MEMBER_ID, AMOUNT, TXN_DATE, TXN_TYPE, TXN_STATUS) 
								 VALUES ({$_SESSION['idnum']}, 0, NOW(), 1, 'FALP Application Submitted')";

			$resultTransaction = mysqli_query($dbc, $queryTransaction);

		}

	?>

	<style>

		 	table, th, td{border: 1px solid black; text-align: center;}

	</style>

	<body>

		<h1>FALP Loan Details</h1><p>

		<div align="center">

		The loan amount is fixed based on your payroll group. <p>

		</div>

		<table align="center" id="table" style="width: 40%;">

			<thead>

			<tr>

				<th>Description</th>
				<th>Value</th>

			</tr>

			</thead>

			<tbody>

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

		</form>

		<hr>

		<a href="member homepage.php">

			<button type="button">Go back to Member Dashboard</button>

		</a>

		</div>

	</body>

</html>