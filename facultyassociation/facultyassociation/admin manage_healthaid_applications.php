<html>

	<header>

		<title>Manage Health Aid Applications - Faculty Association</title>
		<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>

	</header>

	<?php 

		session_start();

		if ($_SESSION['usertype'] != 1) {

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
			
		}

		require_once('../mysql_connect_FA.php');
		$queryMem = "SELECT M.DATE_APPLIED, M.MEMBER_ID, M.LASTNAME, M.FIRSTNAME, M.MIDDLENAME, D.DEPT_NAME, P.PAYROLL_NAME FROM MEMBER AS M 
					 JOIN REF_DEPARTMENT AS D ON M.DEPT_ID = D.DEPT_ID
					 JOIN REF_PAYROLL AS P ON M.PAYROLL_ID = P.PAYROLL_ID
					 WHERE M.HEALTH_AID_STATUS = '1'";

		$resultMem = mysqli_query($dbc, $queryMem);

		$empid = $_SESSION['idnum'];

		if (isset($_POST['approve']) && !empty($_POST['selected'])) {

			if (isset($_POST['selected'])) {

				foreach ($_POST['selected'] as $selected) {

					$queryAcceptMem = "UPDATE MEMBER SET HEALTH_AID_STATUS = 2, EMP_ID_APPROVE_HEALTHAID = '{$empid}', DATE_APPROVED_HEALTHAID = NOW() WHERE MEMBER_ID = '{$selected}'";

					$resultAcceptMem = mysqli_query($dbc, $queryAcceptMem);

					$queryTransaction = "INSERT INTO TRANSACTIONS (MEMBER_ID, AMOUNT, TXN_DATE, TXN_TYPE, TXN_STATUS, EMP_ID_INVOLVED) 
								 VALUES ({$selected}, 0, NOW(), 1, 'Health Aid Application Approved', {$empid})";

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

					$queryRejectMem = "UPDATE MEMBER SET HEALTH_AID_STATUS = 3 WHERE MEMBER_ID = '{$selected}'";
					$resultRejectMem = mysqli_query($dbc, $queryRejectMem);

					$queryTransaction = "INSERT INTO TRANSACTIONS (MEMBER_ID, AMOUNT, TXN_DATE, TXN_TYPE, TXN_STATUS, EMP_ID_INVOLVED) 
								 VALUES ({$selected}, 0, NOW(), 1, 'Health Aid Application Rejected', {$empid})";

					$resultTransaction = mysqli_query($dbc, $queryTransaction);

				}

			}

		}

		else if (isset($_POST['reject']) && empty($_POST['selected'])) {

			$errormsg = "<font color='red'>You didn't select an applicant to reject!</font>";

		}

		if (isset($_POST['viewdetails']) && !empty($_POST['viewdetailscheck'])) {

			$_SESSION['selectedmemid'] = $_POST['viewdetailscheck'];

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/admin healthaid_viewdetails.php");

		}

		else if (isset($_POST['viewdetails']) && empty($_POST['viewdetailscheck'])) {

			$errormsg = "<font color='red'>You didn't select an applicant to view details!</font>";

		}

	?>

	<body>

		<h1>Pending Health Aid Applications</h1><p>

		<style>

		 	table, th, td{border: 1px solid black; text-align: center;}

		</style>

		<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

		<?php if (isset($errormsg)) echo $errormsg; ?><p>

		<table style="width: 100%;" id="table">

			<thead>

			<tr>

				<th>Select Row</th>
				<th>Date Applied</th>
				<th>Member ID</th>
				<th>Lastname</th>
				<th>Firstname</th>
				<th>Middlename</th>
				<th>Department</th>
				<th>Payroll</th>
				<th>View Details</th>

			</tr>

			</thead>

			<tbody>

			<?php foreach ($resultMem as $resultRow) { ?>

				<tr>

					<td><input type="checkbox" name="selected[]" value="<?php echo $resultRow['MEMBER_ID']; ?>"></td>
					<td><?php echo $resultRow['DATE_APPLIED']; ?></td>
					<td><?php echo $resultRow['MEMBER_ID']; ?></td>
					<td><?php echo $resultRow['LASTNAME']; ?></td>
					<td><?php echo $resultRow['FIRSTNAME']; ?></td>
					<td><?php echo $resultRow['MIDDLENAME']; ?></td>
					<td><?php echo $resultRow['DEPT_NAME']; ?></td>
					<td><?php echo $resultRow['PAYROLL_NAME']; ?></td>
					<td><input type="radio" name="viewdetailscheck" value="<?php echo $resultRow['MEMBER_ID']; ?>"></td>

				</tr>

			<?php } ?>

			</tbody>

		</table><p>

			<input type="submit" name="approve" value="Approve Selected Applications">
			<input type="submit" name="reject" value="Reject Selected Applications"><p>
			<input type="submit" name="viewdetails" value="View Details Selected"><p>

			<hr>

		</form>

		<a href="admin homepage.php">

			<button type="button">Back to Dashboard</button>

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