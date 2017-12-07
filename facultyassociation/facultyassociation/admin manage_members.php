<html>

	<header>

		<title>Manage Members - Faculty Association</title>
		<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>

	</header>

	<?php 

		session_start();

		if ($_SESSION['usertype'] != 1) {

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
			
		}

		require_once('../mysql_connect_FA.php');
		$queryMem = "SELECT M.MEMBER_ID, M.LASTNAME, M.FIRSTNAME, M.MIDDLENAME, M.DATE_ACCEPTED, D.DEPT_NAME, P.PAYROLL_NAME, U.STATUS FROM MEMBER AS M
					 JOIN REF_DEPARTMENT AS D
					 ON M.DEPT_ID = D.DEPT_ID
					 JOIN REF_PAYROLL AS P
					 ON M.PAYROLL_ID = P.PAYROLL_ID
					 JOIN USER_STATUS AS U
					 ON M.USER_STATUS_ID = U.STATUS_ID
					 WHERE APP_STATUS_ID = '2'";

		$resultMem = mysqli_query($dbc, $queryMem);

		$empid = $_SESSION['idnum'];

		if (isset($_POST['viewdetails']) && !empty($_POST['viewdetailscheck'])) {

			$_SESSION['selectedmemid'] = $_POST['viewdetailscheck'];

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/admin member_viewdetails.php");

		}

		else if (isset($_POST['viewdetails']) && empty($_POST['viewdetailscheck'])) {

			$errormsg = "<font color='red'>You didn't select an applicant to view details!</font>";

		}

	?>

	<body>

		<h1>Active Member Management</h1><p>

		<style>

		 	table, th, td{border: 1px solid black; text-align: center;}

		</style>

		<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

		<?php if (isset($errormsg)) echo $errormsg; ?><p>

		<table style="width: 100%;" id="table">

			<thead>

			<tr>
				
				<th>View Details</th>
				<th>Date Accepted</th>
				<th>Member Status</th>
				<th>ID Number</th>
				<th>Lastname</th>
				<th>Firstname</th>
				<th>Middlename</th>
				<th>Department</th>
				<th>Payroll</th>

			</tr>

			</thead>

			<tbody>

			<?php foreach ($resultMem as $resultRow) { ?>

				<tr>

					<td><input type="radio" name="viewdetailscheck" value="<?php echo $resultRow['MEMBER_ID']; ?>"></td>
					<td><?php echo $resultRow['DATE_ACCEPTED']?></td>
					<td><?php echo $resultRow['STATUS'] ?></td>
					<td><?php echo $resultRow['MEMBER_ID'] ?></td>
					<td><?php echo $resultRow['LASTNAME'] ?></td>
					<td><?php echo $resultRow['FIRSTNAME'] ?></td>
					<td><?php echo $resultRow['MIDDLENAME'] ?></td>
					<td><?php echo $resultRow['DEPT_NAME'] ?></td>
					<td><?php echo $resultRow['PAYROLL_NAME'] ?></td>

				</tr>

			<?php } ?>

			</tbody>

		</table><p>

		<input type="submit" name="viewdetails" value="View Details Selected">

		</form>

		<hr>

		<p>

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