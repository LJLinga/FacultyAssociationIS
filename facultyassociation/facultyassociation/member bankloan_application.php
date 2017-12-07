<html>
	
	<header>

		<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>

	</header>

	<?php 

		session_start();

		if ($_SESSION['usertype'] != 2) {

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
			
		}

		$idnum = $_SESSION['idnum'];

		require_once('../mysql_connect_FA.php');

		$queryLoan = "SELECT L.LOAN_ID, B.BANK_NAME, L.AMOUNT, L.INTEREST, L.PAYMENT_TERMS, L.TOTAL_DEDUCTION, L.MONTHLY_DEDUCTION 
					  FROM LOAN_LIST AS L
					  JOIN BANK_LIST AS B ON L.BANK_ID = B.BANK_ID
					  WHERE L.STATUS = '1' AND L.BANK_ID != '1'";

		$resultLoan = mysqli_query($dbc, $queryLoan);

		$queryForm = "SELECT M.LOAN_ID FROM MEMBER_LOANS AS M
					  WHERE M.MEMBER_ID = '{$idnum}' AND M.APP_STATUS = 1";

		$resultForm = mysqli_query($dbc, $queryForm);
		$resultRowForm = mysqli_fetch_array($resultForm);

		if (!empty($resultRowForm)) {

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/member pending_bankloan.php");

		}

		if (isset($_POST['continue']) && !empty($_POST['selected'])) {

			$_SESSION['selectedloan'] = $_POST['selected'];

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/member confirm_bankloan.php");

		}

		else if (isset($_POST['continue']) && empty($_POST['selected'])) {

			$errormsg = "<font color='red'>You didn't select a loan plan to continue!</font>";

		}

	?>

	<body>

		<style>

		 	table, th, td{border: 1px solid black; text-align: center;}

		</style>

		<?php if (isset($errormsg)) echo $errormsg; ?><p>

		<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

		<table style="width: 100%;" id="table">

			<thead>

			<tr>

				<th>Select Loan</th>
				<th>Bank From</th>
				<th>Amount</th>
				<th>Interest %</th>
				<th>Total Deduction</th>
				<th>Payment Terms</th>
				<th>Monthly Deduction</th>

			</tr>

			</thead>

			<tbody>

			<?php foreach ($resultLoan as $resultRow) { ?>

				<tr>

					<td><input type="radio" name="selected" value="<?php echo $resultRow['LOAN_ID']; ?>"></td>
					<td><?php echo $resultRow['BANK_NAME']; ?></td>
					<td><?php echo $resultRow['AMOUNT']; ?></td>
					<td><?php echo $resultRow['INTEREST']; ?></td>
					<td><?php echo $resultRow['TOTAL_DEDUCTION']; ?></td>
					<td><?php echo $resultRow['PAYMENT_TERMS']; ?></td>
					<td><?php echo $resultRow['MONTHLY_DEDUCTION']; ?></td>

				</tr>

			<?php } ?>

			</tbody>

		</table>

		<p>

		<input type="submit" name="continue" value="Apply for Selected Loan Plan">

		</form>

		<hr>

		<a href="member homepage.php">

			<button type="button">Go back to Member Dashboard</button>

		</a>

		<script type="text/javascript" src="jquery-3.1.1.min.js"></script>
		<script type="text/javascript" src="DataTables/datatables.min.js"></script>
		<script>

			$(document).ready(function(){
    
    			$('#table').DataTable();

			});

		</script>

	</body>

</html>