<html>

	<header>

		<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>

	</header>

	<?php 

		session_start();

		if ($_SESSION['usertype'] != 1) {

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
			
		}

		require_once('../mysql_connect_FA.php');

		$queryStatus = "UPDATE MEMBER AS M SET ELIGIBLE_LIFETIME = 1 WHERE TIMESTAMPDIFF(YEAR, M.DATE_HIRED, (NOW())) >= 10 AND ELIGIBLE_LIFETIME <=> NULL";

		$resultStatus = mysqli_query($dbc, $queryStatus);

		$queryMem = "SELECT M.MEMBER_ID, M.LASTNAME, M.FIRSTNAME, M.MIDDLENAME, M.DATE_HIRED, D.DEPT_NAME, P.PAYROLL_NAME, U.STATUS FROM MEMBER AS M
					 JOIN REF_DEPARTMENT AS D
					 ON M.DEPT_ID = D.DEPT_ID
					 JOIN REF_PAYROLL AS P
					 ON M.PAYROLL_ID = P.PAYROLL_ID
					 JOIN USER_STATUS AS U
					 ON M.USER_STATUS_ID = U.STATUS_ID
					 WHERE APP_STATUS_ID = '2' AND USER_STATUS_ID = '1' AND ELIGIBLE_LIFETIME = '1' AND LIFETIME_APP_STATUS <=> NULL";

		$resultMem = mysqli_query($dbc, $queryMem);

		if (isset($_POST['viewdetails']) && !empty($_POST['viewdetailscheck'])) {

			$_SESSION['selectedmemid'] = $_POST['viewdetailscheck'];

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/admin lifetime_viewdetails.php");

		}

		else if (isset($_POST['viewdetails']) && empty($_POST['viewdetailscheck'])) {

			$errormsg = "<font color='red'>You didn't select an applicant to view details!</font>";

		}

	?>

	<style>

		table, th, td{border: 1px solid black; text-align: center;}

	</style>

	<body>

		<h1>Eligible Members for Lifetime</h1>

		<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

		<?php if (isset($errormsg)) echo $errormsg; ?><p>

		<table id="table" style="width: 100%;">

			<thead>

				<th>View Details</th>
				<th>Date Hired</th>
				<th>Member ID</th>
				<th>Lastname</th>
				<th>Firstname</th>
				<th>Middlename</th>
				<th>Department</th>
				<th>Payroll</th>

			</thead>

			<tbody>

				<?php foreach ($resultMem as $resultRow) { ?>

				<tr>

					<td><input type="radio" name="viewdetailscheck" value="<?php echo $resultRow['MEMBER_ID']; ?>"></td>
					<td><?php echo $resultRow['DATE_HIRED']?></td>
					<td><?php echo $resultRow['MEMBER_ID'] ?></td>
					<td><?php echo $resultRow['LASTNAME'] ?></td>
					<td><?php echo $resultRow['FIRSTNAME'] ?></td>
					<td><?php echo $resultRow['MIDDLENAME'] ?></td>
					<td><?php echo $resultRow['DEPT_NAME'] ?></td>
					<td><?php echo $resultRow['PAYROLL_NAME'] ?></td>

				</tr>

				<?php } ?>

			</tbody>

		</table>

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