<html>

	<header>

		<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>

	</header>

	<style>

		 	table, th, td{border: 1px solid black; text-align: center;}

	</style>

	<?php 

		session_start();

		if ($_SESSION['usertype'] != 1) {

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
			
		}

		$empid = $_SESSION['idnum'];

		require_once('../mysql_connect_FA.php');

		$queryMem = "SELECT ML.RECORD_ID, ML.DATE_APPLIED, ML.MEMBER_ID, M.LASTNAME, M.FIRSTNAME, D.DEPT_NAME, P.PAYROLL_NAME, L.AMOUNT
					 FROM MEMBER_LOANS AS ML
					 JOIN MEMBER AS M ON ML.MEMBER_ID = M.MEMBER_ID
					 JOIN LOAN_LIST AS L ON ML.LOAN_ID = L.LOAN_ID
                     JOIN REF_DEPARTMENT AS D ON M.DEPT_ID = D.DEPT_ID
                     JOIN REF_PAYROLL AS P ON M.PAYROLL_ID = P.PAYROLL_ID
                     WHERE L.BANK_ID = 1 AND ML.APP_STATUS = 1";

        $resultMem = mysqli_query($dbc, $queryMem);

        if (isset($_POST['approve']) && !empty($_POST['selected'])) {

			if (isset($_POST['selected'])) {

				foreach ($_POST['selected'] as $selected) {

					$queryAcceptMem = "UPDATE MEMBER_LOANS SET APP_STATUS = 2, LOAN_STATUS = 2, EMP_ID_APPROVED = '{$empid}', DATE_APPROVED = NOW(), PICKUP_STATUS = 2 WHERE RECORD_ID = '{$selected}'";

					$resultAcceptMem = mysqli_query($dbc, $queryAcceptMem);

					$queryRecord = "SELECT MEMBER_ID FROM MEMBER_LOANS WHERE RECORD_ID = '{$selected}'";
					$resultRecord = mysqli_query($dbc, $queryRecord);
					$rowRecord = mysqli_fetch_array($resultRecord);

					$queryTransaction = "INSERT INTO TRANSACTIONS (MEMBER_ID, AMOUNT, TXN_DATE, TXN_TYPE, TXN_STATUS, EMP_ID_INVOLVED) 
								 VALUES ({$rowRecord['MEMBER_ID']}, 0, NOW(), 1, 'FALP Application Approved', {$empid})";

					$resultTransaction = mysqli_query($dbc, $queryTransaction);

				}

			}

		}

		else if (isset($_POST['approve']) && empty($_POST['selected'])) {

			$errormsg = "<font color='red'>You didn't select an applicant to accept!</font>";

		}

		if (isset($_POST['reject']) && !empty($_POST['selected'])) {

			if (isset($_POST['selected'])) {

				foreach ($_POST['selected'] as $selected) {

					$queryRejectMem = "UPDATE MEMBER_LOANS SET APP_STATUS = 3 WHERE RECORD_ID = '{$selected}'";

					$resultRejectMem = mysqli_query($dbc, $queryRejectMem);

					$queryRecord = "SELECT MEMBER_ID FROM MEMBER_LOANS WHERE RECORD_ID = '{$selected}'";
					$resultRecord = mysqli_query($dbc, $queryRecord);
					$rowRecord = mysqli_fetch_array($resultRecord);

					$queryTransaction = "INSERT INTO TRANSACTIONS (MEMBER_ID, AMOUNT, TXN_DATE, TXN_TYPE, TXN_STATUS, EMP_ID_INVOLVED) 
								 VALUES ({$rowRecord['MEMBER_ID']}, 0, NOW(), 1, 'FALP Application Rejected', {$empid})";

					$resultTransaction = mysqli_query($dbc, $queryTransaction);

				}

			}

		}

		else if (isset($_POST['reject']) && empty($_POST['selected'])) {

			$errormsg = "<font color='red'>You didn't select an applicant to reject!</font>";

		}

		if (isset($_POST['viewdetails']) && !empty($_POST['viewdetailscheck'])) {

			$_SESSION['selectedrecord'] = $_POST['viewdetailscheck'];

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/admin falp_viewdetails.php");

		}

		else if (isset($_POST['viewdetails']) && empty($_POST['viewdetailscheck'])) {

			$errormsg = "<font color='red'>You didn't select an applicant to view details!</font>";

		}

	?>

	<body>

		<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

		<?php if (isset($errormsg)) echo $errormsg; ?><p>

		<table style="width: 100%;" id="table">

			<thead>

			<tr>

				<th>Select Row</th>
				<th>Date Applied</th>
				<th>ID Number</th>
				<th>Name</th>
				<th>Department</th>
				<th>Payroll</th>
				<th>Amount</th>
				<th>View Details</th>

			</tr>

			</thead>

			<tbody>

			<?php if ($resultMem) { ?>

			<?php foreach ($resultMem as $resultRow) { ?>

				<?php 

					$name = $resultRow['LASTNAME'] . ", " . $resultRow['FIRSTNAME'];

				?>

				<tr>

					<td><input type="checkbox" name="selected[]" value="<?php echo $resultRow['RECORD_ID']; ?>"></td>
					<td><?php echo $resultRow['DATE_APPLIED']; ?></td>
					<td><?php echo $resultRow['MEMBER_ID']; ?></td>
					<td><?php echo $name; ?></td>
					<td><?php echo $resultRow['DEPT_NAME']; ?></td>
					<td><?php echo $resultRow['PAYROLL_NAME']; ?></td>
					<td><?php echo $resultRow['AMOUNT']; ?></td>
					<td><input type="radio" name="viewdetailscheck" value="<?php echo $resultRow['RECORD_ID']; ?>"></td>

				</tr>

			<?php } ?>

			<?php } ?>

			</tbody>

		</table><p>

		<input type="submit" name="approve" value="Approve Selected Applications">
		<input type="submit" name="reject" value="Reject Selected Applications"><p>
		<input type="submit" name="viewdetails" value="View Details Selected">

		</form>

		<hr>

		<p>

		<a href="admin homepage.php">

			<button type="button">Back to Dashboard</button>

		</a>

	</body>

	<script type="text/javascript" src="jquery-3.1.1.min.js"></script>
		<script type="text/javascript" src="DataTables/datatables.min.js"></script>
		<script>

			$(document).ready(function(){
    
    			$('#table').DataTable();

			});

	</script>

</html>