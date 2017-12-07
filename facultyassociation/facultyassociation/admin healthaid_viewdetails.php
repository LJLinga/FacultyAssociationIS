<html>

	<body>

		<?php 

			session_start();

			if ($_SESSION['usertype'] != 1) {

				header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");

			}

			$idnum = $_SESSION['idnum'];
			$selectedid = $_SESSION['selectedmemid'];

			require_once('../mysql_connect_FA.php');

			$queryMem = "SELECT M.CIV_STATUS, M.FIRSTNAME, M.LASTNAME FROM MEMBER AS M WHERE MEMBER_ID = '{$selectedid}'";
			$resultMem = mysqli_query($dbc, $queryMem);
			$rowMem = mysqli_fetch_array($resultMem);

			$queryFather = "SELECT F.LASTNAME, F.FIRSTNAME, F.MIDDLENAME, F.BIRTHDATE, TIMESTAMPDIFF(YEAR, DATE(NOW()), F.BIRTHDATE) AS 'AGE' FROM FATHER AS F WHERE MEMBER_ID = '{$selectedid}'";
			$resultFather = mysqli_query($dbc, $queryFather);
			$rowFather = mysqli_fetch_array($resultFather);

			$queryMother = "SELECT M.LASTNAME, M.FIRSTNAME, M.MIDDLENAME, M.BIRTHDATE, TIMESTAMPDIFF(YEAR, DATE(NOW()), M.BIRTHDATE) AS 'AGE' FROM MOTHER AS M WHERE MEMBER_ID = '{$selectedid}'";
			$resultMother = mysqli_query($dbc, $queryMother);
			$rowMother = mysqli_fetch_array($resultMother);

			$querySibling = "SELECT S.LASTNAME, S.FIRSTNAME, S.MIDDLENAME, S.BIRTHDATE, S.SEX, TIMESTAMPDIFF(YEAR, DATE(NOW()), S.BIRTHDATE) AS 'AGE' FROM SIBLINGS AS S WHERE MEMBER_ID = '{$selectedid}'";
			$resultSibling = mysqli_query($dbc, $querySibling);

			if ($rowMem['CIV_STATUS'] != 1) {

				$queryChild = "SELECT C.LASTNAME, C.FIRSTNAME, C.MIDDLENAME, C.BIRTHDATE, C.SEX, TIMESTAMPDIFF(YEAR, DATE(NOW()), C.BIRTHDATE) AS 'AGE' FROM CHILDREN AS C WHERE MEMBER_ID = '{$selectedid}'";
				$resultChild = mysqli_query($dbc, $queryChild);

			}

			$selectedmemid = $_SESSION['selectedmemid'];
			$empid = $_SESSION['idnum'];

			if (isset($_POST['approve'])) {

				$queryAcceptMem = "UPDATE MEMBER SET HEALTH_AID_STATUS = 2, EMP_ID_APPROVE_HEALTHAID = '{$empid}', DATE_APPROVED_HEALTHAID = NOW() WHERE MEMBER_ID = '{$selectedmemid}'";

				$resultAcceptMem = mysqli_query($dbc, $queryAcceptMem);

				$queryTransaction = "INSERT INTO TRANSACTIONS (MEMBER_ID, AMOUNT, TXN_DATE, TXN_TYPE, TXN_STATUS, EMP_ID_INVOLVED) 
								 VALUES ({$selectedmemid}, 0, NOW(), 1, 'Health Aid Application Approved', {$empid})";

				$resultTransaction = mysqli_query($dbc, $queryTransaction);

			}

			else if (isset($_POST['reject'])) {

				$queryRejectMem = "UPDATE MEMBER SET HEALTH_AID_STATUS = 3 WHERE MEMBER_ID = '{$selectedmemid}'";
				$resultRejectMem = mysqli_query($dbc, $queryRejectMem);

				$queryTransaction = "INSERT INTO TRANSACTIONS (MEMBER_ID, AMOUNT, TXN_DATE, TXN_TYPE, TXN_STATUS, EMP_ID_INVOLVED) 
								 VALUES ({$selectedmemid}, 0, NOW(), 1, 'Health Application Rejected', {$empid})";

				$resultTransaction = mysqli_query($dbc, $queryTransaction);

			}

		?>

		<style>

		 	table, th, td{border: 1px solid black; text-align: center;}

		 	.hatable{width: 50%;}

		</style>

		<h1><?php echo $rowMem['FIRSTNAME'] . " " . $rowMem['LASTNAME'] . "'s Health Aid Application Details" ?></h1>

			<b>Father's Info</b><p>

			<table class="hatable">

				<thead>

					<tr>

						<th>Name</th>
						<th>Birthdate</th>
						<th>Age</th>

					</tr>

				</thead>

				<tbody>

					<tr>

						<td><?php echo $rowFather['LASTNAME'] . ", " . $rowFather['FIRSTNAME'] . " " . $rowFather['MIDDLENAME']; ?></td>
						<td><?php echo $rowFather['BIRTHDATE']; ?></td>
						<td><?php echo $rowFather['AGE']; ?></td>

					</tr>

				</tbody>

			</table>

			<p>

			<b>Mother's Info</b><p>

			<table class="hatable">

				<thead>

					<tr>

						<th>Name</th>
						<th>Birthdate</th>
						<th>Age</th>

					</tr>

				</thead>

				<tbody>

					<tr>

						<td><?php echo $rowMother['LASTNAME'] . ", " . $rowMother['FIRSTNAME'] . " " . $rowMother['MIDDLENAME']; ?></td>
						<td><?php echo $rowMother['BIRTHDATE']; ?></td>
						<td><?php echo $rowMother['AGE']; ?></td>

					</tr>

				</tbody>

			</table>

			<p>

			<b>Sibling's Info</b><p>

				<table class="hatable">

					<thead>

					<tr>

						<th>Name</th>
						<th>Birthdate</th>
						<th>Age</th>

					</tr>

				</thead>

				<tbody>

					<?php foreach ($resultSibling as $sibling) { ?>

					<tr>

						<td><?php echo $sibling['LASTNAME'] . ", " . $sibling['FIRSTNAME'] . " " . $sibling['MIDDLENAME']; ?></td>
						<td><?php echo $sibling['BIRTHDATE'] ?></td>
						<td><?php echo $sibling['AGE']; ?></td>

					</tr>

					<?php } ?>

				</tbody>

				</table>

			<?php if ($rowMem['CIV_STATUS'] != 1) { ?>

				<b>Children Info</b><p>

					<table class="hatable">

						<thead>

							<tr>

								<th>Name</th>
								<th>Birthdate</th>
								<th>Age</th>

							</tr>

						</thead>

						<tbody>

							<?php foreach ($resultChild as $child) { ?>

							<tr>

								<td><?php echo $child['LASTNAME'] . ", " . $child['FIRSTNAME'] . " " . $child['MIDDLENAME'];?></td>
								<td><?php echo $child['BIRTHDATE'] ?></td>
								<td><?php echo $child['AGE']; ?></td>

							</tr>

							<?php } ?>

						</tbody>

					</table>

			<?php } ?>

		<p>

		<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

			<input type="submit" name="approve" value="Approve Application">
			<input type="submit" name="reject" value="Reject Application">

		</form>

		<hr>

		<a href="admin manage_healthaid_applications.php">

			<button type="button">Go back to Health Aid Applications</button>

		</a>

	</body>

</html>