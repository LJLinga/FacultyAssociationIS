<html>

	<header>

		<title>Registration - Faculty Association Portal</title>

	</header>

	<?php 

		require_once('../mysql_connect_FA.php');
		
		$queryPayroll = "SELECT * FROM REF_PAYROLL";
		$resultPayroll = mysqli_query($dbc, $queryPayroll);

		$queryDept = "SELECT * FROM REF_DEPARTMENT";
		$resultDept = mysqli_query($dbc, $queryDept);

		$queryCiv = "SELECT * FROM CIV_STATUS";
		$resultCiv = mysqli_query($dbc, $queryCiv);

		/* inserts and checking of ID number & password if empty */

		if (isset($_POST['submit'])) {

			$flag = 0;
			$message = NULL;
			$idnum = NULL;
			$password = NULL;
			$firstname = NULL;
			$lastname = NULL;
			$middlename = NULL;
			$payroll = NULL;
			$department = NULL;
			$month = NULL;
			$day = NULL;
			$year = NULL;
			$homeAddress = NULL;
			$homeNumber = NULL;
			$birthdate = NULL;
			$datehired = NULL;
			$businessAddress = $_POST['businessAddress'];
			$businessNumber = NULL;
			$userstatus = 1;
			$appstatus = 1;


			if (empty($_POST['idnum']) || !is_numeric($_POST['idnum'])){

 				$message.='<p>You forgot to enter your username or inputed an invalid value!';
 				$idnum = 0;
 				$flag++;

			}

			else {

				$idnum = $_POST['idnum'];

			}

			if (empty($_POST['password'])){

 				$message.='<p>You forgot to enter your password!';
 				$flag++;

			}

			else {

				$password = $_POST['password'];

			}

			/* if same ID has been entered */

			$queryMem = "SELECT MEMBER_ID, APP_STATUS_ID FROM MEMBER WHERE MEMBER_ID = {$idnum}";
			$resultMem = mysqli_query($dbc, $queryMem);
			$rowMem = mysqli_fetch_array($resultMem);

			if ($rowMem['MEMBER_ID'] == $idnum && ($rowMem['APP_STATUS_ID'] == 2 || $rowMem['APP_STATUS_ID'] == 1)) {

				$message.='<p>This Member ID already registered!';
				$flag++;

			}

			/* checking member info */

			if (empty($_POST['firstname']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['firstname'])) {

 				$message.='<p>You forgot to enter your first name or inputed an invalid value!';
 				$flag++;

			}

			else {

				$firstname = $_POST['firstname'];

			}

			if (empty($_POST['lastname']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['lastname'])){

 				$message.='<p>You forgot to enter your last name or inputed an invalid value!';
 				$flag++;

			}

			else {

				$lastname = $_POST['lastname'];

			}

			if (empty($_POST['middlename']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['middlename'])){

 				$message.='<p>You forgot to enter your middle name or inputed an invalid value!';
 				$flag++;

			}

			else {

				$middlename = $_POST['middlename'];

			}

			if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

				$message.='<p>You forgot to enter your DLSU email or inputed an invalid value!';
				$flag++;

			}

			else {

				$email = $_POST['email'];

			}

			if (empty($_POST['payroll'])){

 				$message.='<p>You forgot to select your payroll group!';
 				$flag++;

			}

			else {

				$payroll = $_POST['payroll'];

			}

			if (empty($_POST['department'])){

 				$message.='<p>You forgot to select your department!';
 				$flag++;

			}

			else {

				$department = $_POST['department'];

			}

			if (empty($_POST['month'])){

 				$message.='<p>You forgot to enter your birth month!';
 				$flag++;

			}

			else {

				$month = $_POST['month'];

			}

			if (empty($_POST['day'])){

 				$message.='<p>You forgot to enter your birth day!';
 				$flag++;

			}

			else {

				$day = $_POST['day'];

			}

			if (empty($_POST['year'])){

 				$message.='<p>You forgot to enter your birth year!';
 				$flag++;

			}

			else {

				$year = $_POST['year'];

			}

			if (empty($_POST['hiredmonth'])){

 				$message.='<p>You forgot to enter your hired month!';
 				$flag++;

			}

			else {

				$hiredmonth = $_POST['hiredmonth'];

			}

			if (empty($_POST['hiredday'])){

 				$message.='<p>You forgot to enter your hired day!';
 				$flag++;

			}

			else {

				$hiredday = $_POST['hiredday'];

			}

			if (empty($_POST['hiredyear'])){

 				$message.='<p>You forgot to enter your hired year!';
 				$flag++;

			}

			else {

				$hiredyear = $_POST['hiredyear'];

			}

			if (empty($_POST['homeAddress'])){

 				$message.='<p>You forgot to enter your home address!';
 				$flag++;

			}

			else {

				$homeAddress = $_POST['homeAddress'];

			}

			if (empty($_POST['homeNumber']) || !is_numeric($_POST['homeNumber'])){

 				$message.='<p>You forgot to enter your home number or inputed an invalid value!';
 				$flag++;

			}

			else {

				$homeNumber = $_POST['homeNumber'];

			}

			if (!empty($_POST['businessNumber'])) {

				if (!is_numeric($_POST['businessNumber'])) {

					$message.='<p>You inputed an invalid value for your Business Number!';
					$flag++;

				}

				else {

					$businessNumber = $_POST['businessNumber'];

				}

			}

			if (empty($_POST['civilstatus'])) {

				$message.='<p>You forgot to enter your civil status!';

			}

			else {

				$civilstatus = $_POST['civilstatus'];

			}

			/* inserts */

			if ($flag == 0) {

				$birthdate = $year . "-" . $month . "-" . $day;
				$datehired = $hiredyear . "-" . $hiredmonth . "-" . $hiredday;
				$sex = $_POST['sex'];

				$query = "INSERT INTO MEMBER (MEMBER_ID, PASSWORD, LASTNAME, FIRSTNAME, MIDDLENAME, EMAIL ,SEX, CIV_STATUS, DEPT_ID, PAYROLL_ID, BIRTHDATE, 
						  DATE_HIRED, HOME_ADDRESS, BUSINESS_ADDRESS, HOME_NUM, BUSINESS_NUM, USER_STATUS_ID, APP_STATUS_ID, DATE_APPLIED) 
					
					VALUES ('{$idnum}', PASSWORD('{$password}'), '{$lastname}', '{$firstname}', '{$middlename}', '{$email}', '{$sex}', '{$civilstatus}', '{$department}', '{$payroll}', CAST('{$birthdate}' AS DATE), CAST('{$datehired}' AS DATE), '{$homeAddress}', '{$businessAddress}', '{$homeNumber}', '{$businessNumber}', '{$userstatus}', '{$appstatus}', NOW())";

				$result = mysqli_query($dbc, $query);

				$queryTransaction = "INSERT INTO TRANSACTIONS (MEMBER_ID, AMOUNT, TXN_DATE, TXN_TYPE, TXN_STATUS) 
								 VALUES ({$idnum}, 0, NOW(), 1, 'Member Application Submitted')";

				$resultTransaction = mysqli_query($dbc, $queryTransaction);

			}

		}

	?>

	<body>

		<h2>Register an Account</h2>
		Accomplish the account registration form below. <p>

		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

		<fieldset><p>

		<?php if (isset($message)){
		
			echo '<font color="red">'.$message. '</font><p>';
		
		} ?>

			<b>Enter desired login credentials.</b><p>

			ID Number: <input type="text" name="idnum" size="18" maxlength="8"><p>
			Password: <input type="password" name="password"><p>

			<hr>

			<b>Enter your personal information.</b><p>

			Firstname: <input type="text" name="firstname"><p>
			Lastname: <input type="text" name="lastname"><p>
			Middlename: <input type="text" name="middlename"><p>
			Email: <input type="text" name="email"><p>

			Birthdate: 

			<select name="month">

				<option value="">Select Month</option>
				<option value=""> ----- </option>
				<option value="1">January</option>
				<option value="2">February</option>
				<option value="3">March</option>
				<option value="4">April</option>
				<option value="5">May</option>
				<option value="6">June</option>
				<option value="7">July</option>
				<option value="8">August</option>
				<option value="9">September</option>
				<option value="10">October</option>
				<option value="11">November</option>
				<option value="12">December</option>

			</select>

			<select name="day">

				<option value="">Select Day</option>
				<option value=""> ----- </option>

				<?php for($x = 1; $x <= 31; $x++) { ?>

					<option value="<?php echo $x; ?>"><?php echo $x; ?></option>

				<?php } ?>

			</select>

			<select name="year">

				<option value="">Select Year</option>
				<option value=""> ----- </option>

				<?php for($y = 2017; $y >= 1900; $y--) { ?>

					<option value="<?php echo $y; ?>"><?php echo $y; ?></option>

				<?php } ?>

			</select>

			<p>

			Sex:
			<select name="sex">

					<option value="0">Male</option>
					<option value="1">Female</option>

			</select><p>

			Civil Status:
			<select name="civilstatus">

					<option value="">Civil Status</option>
					<option value=""> ----- </option>

					<?php foreach($resultCiv as $civstatusArray) { ?>

						<option value="<?php echo $civstatusArray['STATUS_ID'] ?>"><?php echo $civstatusArray['STATUS'] ?></option>

					<?php } ?>

			</select><p>

			Date hired at DLSU: 

			<select name="hiredmonth">

				<option value="">Select Month</option>
				<option value=""> ----- </option>
				<option value="1">January</option>
				<option value="2">February</option>
				<option value="3">March</option>
				<option value="4">April</option>
				<option value="5">May</option>
				<option value="6">June</option>
				<option value="7">July</option>
				<option value="8">August</option>
				<option value="9">September</option>
				<option value="10">October</option>
				<option value="11">November</option>
				<option value="12">December</option>

			</select>

			<select name="hiredday">

				<option value="">Select Day</option>
				<option value=""> ----- </option>

				<?php for($x = 1; $x <= 31; $x++) { ?>

					<option value="<?php echo $x; ?>"><?php echo $x; ?></option>

				<?php } ?>

			</select>

			<select name="hiredyear">

				<option value="">Select Year</option>
				<option value=""> ----- </option>

				<?php for($y = 2017; $y >= 1900; $y--) { ?>

					<option value="<?php echo $y; ?>"><?php echo $y; ?></option>

				<?php } ?>

			</select> <p>

			Payroll Group: 
			<select name="payroll">

				<option value="">Payroll Group</option>
				<option value=""> ----- </option>

				<?php foreach($resultPayroll as $payrollArray) { ?>

					<option value="<?php echo $payrollArray['PAYROLL_ID']; ?>"> <?php echo $payrollArray['PAYROLL_NAME']; ?> </option>

				<?php } ?>

			</select><p>

			Department:
			<select name="department">

				<option value="">Department Name</option>
				<option value=""> ----- </option>

				<?php foreach($resultDept as $deptArray) { ?>

					<option value="<?php echo $deptArray['DEPT_ID']; ?>"><?php echo $deptArray['DEPT_NAME']; ?></option>

				<?php } ?>

			</select><p>

			<p>

			Home Address:<br> <textarea name="homeAddress" rows="4" cols="50"></textarea> <p>

			Business Address:<br> <textarea name="businessAddress" rows="4" cols="50"></textarea> <p>

			Home Phone number: <input type="text" name="homeNumber"><p>

			Business Phone number: <input type="text" name="businessNumber"><p>

			<p><input type="submit" name="submit" value="Register!"><p>

		</form>

		</fieldset>

		<a href="index.php">

			<button type="button">Go back to Login</button><p>

		</a>

	</body>

</html>