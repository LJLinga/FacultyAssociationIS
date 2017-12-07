<html>

	<header>

		<title>Health Aid Application - Faculty Association</title>

	</header>

	<?php 

		session_start();

		if ($_SESSION['usertype'] != 2) {

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
			
		}

		$message = NULL;
		$idnum = $_SESSION['idnum'];
		$siblingfirstarray = array();
		$siblinglastarray = array();
		$siblingmiddlearray = array();
		$siblingdayarray = array();
		$siblingmontharray = array();
		$siblingyeararray = array();
		$siblingbirthdatearray = array();
		$siblingsexarray = array();
		$siblingstatusarray = array();
		$childdayarray = array();
		$childmontharray = array();
		$childyeararray = array();
		$childfirstarray = array();
		$childlastarray = array();
		$childmiddlearray = array();
		$childbirthdatearray = array();
		$childsexarray = array();
		$childstatusarray = array();
		$appstatusid = 1;

		require_once('../mysql_connect_FA.php');
		$queryCiv = "SELECT CIV_STATUS FROM MEMBER WHERE MEMBER_ID = '{$idnum}'";
		$resultCiv = mysqli_query($dbc, $queryCiv);
		$rowCiv = mysqli_fetch_array($resultCiv);

		$queryForm = "SELECT HEALTH_AID_STATUS FROM MEMBER WHERE MEMBER_ID = '{$idnum}'";
		$resultForm = mysqli_query($dbc, $queryForm);
		$rowForm = mysqli_fetch_array($resultForm);

		if ($rowForm['HEALTH_AID_STATUS'] == 1) { /* PENDING */

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/member healthaid_pending.php");

		}

		else if ($rowForm['HEALTH_AID_STATUS'] == 2) { /* ACCEPTED */

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/member healthaid_approved.php");

		}

		else if ($rowForm['HEALTH_AID_STATUS'] == 3) { /* REJECTED */

			echo "<font color=\"red\"> Your last application for Health Aid Benefits was rejected. You can apply for another one.</font>";

		}
 
		/* check if empty fields */

		if (isset($_POST['submit'])) {

			$flag = 0;

			if (empty($_POST['fatherfirst']) || empty($_POST['fathermiddle']) || empty($_POST['fatherlast']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['fatherfirst']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['fathermiddle']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['fatherlast'])) {

				$message.='<p>You forgot to fill up all name fields or inputed an invalid input on Father\'s info!';
				$flag++;

			}

			else {

				$fatherfirst = $_POST['fatherfirst'];
				$fathermiddle = $_POST['fathermiddle'];
				$fatherlast = $_POST['fatherlast'];

			}

			if (empty($_POST['fathermonth']) || empty($_POST['fatherday']) || empty($_POST['fatheryear'])) {

				$message.='<p>You forgot to fill up all birth date fields on Father\'s info!';
				$flag++;

			}

			else {

				$fatherbirthdate = $_POST['fatheryear'] . "-" . $_POST['fathermonth'] . "-" . $_POST['fatherday'];

			}

			if (empty($_POST['motherfirst']) || empty($_POST['mothermiddle']) || empty($_POST['motherlast']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['motherfirst']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['mothermiddle']) || !preg_match("/^[a-zA-Z ]*$/", $_POST['motherlast'])) {

				$message.='<p>You forgot to fill up all name fields or inputed an invalid input on Mother\'s info!';
				$flag++;

			}

			else {

				$motherfirst = $_POST['motherfirst'];
				$mothermiddle = $_POST['mothermiddle'];
				$motherlast = $_POST['motherlast'];

			}

			if (empty($_POST['mothermonth']) || empty($_POST['motherday']) || empty($_POST['motheryear'])) {

				$message.='<p>You forgot to fill up all birth date fields on Mother\'s info!';
				$flag++;

			}

			else {

				$motherbirthdate = $_POST['motheryear'] . "-" . $_POST['mothermonth'] . "-" . $_POST['motherday'];

			}

			if (!isset($_POST['hasSibling'])) {

				if (isset($_POST['siblingstatus'])) {

					foreach ($_POST['siblingstatus'] as $siblingstatus) {

							array_push($siblingstatusarray, $siblingstatus);

					}

				}

				else {

					$message.='<p>You forgot to choose the status on child\'s info!';
					$flag++;

				}

				foreach ($_POST['siblingfirst'] as $siblingfirst) {

					if (empty($siblingfirst) || !preg_match("/^[a-zA-Z ]*$/", $siblingfirst)) {

						echo $siblingfirst + " first ";
						$message.='<p>You forgot to fill up a first name field or inputed an invalid input on siblings\'s info!';
						$flag++;
						break;

					}

					else {

						array_push($siblingfirstarray, $siblingfirst);

					}

				}

				foreach ($_POST['siblinglast'] as $siblinglast) {

					if (empty($siblinglast) || !preg_match("/^[a-zA-Z ]*$/", $siblinglast)) {

						$message.='<p>You forgot to fill up a last name field or inputed an invalid input on siblings\'s info!';
						$flag++;
						break;

					}

					else {

						array_push($siblinglastarray, $siblinglast);

					}

				}

				foreach ($_POST['siblingmiddle'] as $siblingmiddle) {

					if (empty($siblingmiddle) || !preg_match("/^[a-zA-Z ]*$/", $siblingmiddle)) {

						$message.='<p>You forgot to fill up a middle name field or inputed an invalid input on siblings\'s info!';
						$flag++;
						break;

					}

					else {

						array_push($siblingmiddlearray, $siblingmiddle);

					}

				}

				foreach ($_POST['siblingmonth'] as $siblingmonth) {

					if (empty($siblingmonth)) {

						$message.='<p>You forgot to fill up a month field on siblings\'s info!';
						$flag++;
						break;

					}

					else {

						array_push($siblingmontharray, $siblingmonth);

					}

				}

				foreach ($_POST['siblingday'] as $siblingday) {

					if (empty($siblingday)) {

						$message.='<p>You forgot to fill up a day field on siblings\'s info!';
						$flag++;
						break;

					}

					else {

						array_push($siblingdayarray, $siblingday);

					}

				}

				foreach ($_POST['siblingyear'] as $siblingyear) {

					if (empty($siblingyear)) {

						$message.='<p>You forgot to fill up a year field on siblings\'s info!';
						$flag++;
						break;

					}

					else {

						array_push($siblingyeararray, $siblingyear);

					}

				}

				if (isset($_POST['siblingsex'])) {

					foreach ($_POST['siblingsex'] as $siblingsex) {

							array_push($siblingsexarray, $siblingsex);

					}

				}

				else {

					$message.='<p>You forgot to choose the sex on sibling\'s info!';
					$flag++;

				}

			}

		if ($rowCiv['CIV_STATUS'] != 1) {

			if (!isset($_POST['hasChild'])) {

				if (isset($_POST['childstatus'])) {

					foreach ($_POST['childstatus'] as $childstatus) {

							array_push($childstatusarray, $childstatus);

					}

				}

				else {

					$message.='<p>You forgot to choose the status on child\'s info!';
					$flag++;

				}

				foreach ($_POST['childfirst'] as $childfirst) {

					if (empty($childfirst) || !preg_match("/^[a-zA-Z ]*$/", $childfirst)) {

						$message.='<p>You forgot to fill up a first name field or inputed an invalid input on child\'s info!';
						$flag++;
						break;

					}

					else {

						array_push($childfirstarray, $childfirst);

					}

				}

				foreach ($_POST['childlast'] as $childlast) {

					if (empty($childlast) || !preg_match("/^[a-zA-Z ]*$/", $childlast)) {

						$message.='<p>You forgot to fill up a last name field or inputed an invalid input on child\'s info!';
						$flag++;
						break;

					}

					else {

						array_push($childlastarray, $childlast);

					}

				}

				foreach ($_POST['childmiddle'] as $childmiddle) {

					if (empty($childmiddle) || !preg_match("/^[a-zA-Z ]*$/", $childmiddle)) {

						$message.='<p>You forgot to fill up a middle name field or inputed an invalid input on child\'s info!';
						$flag++;
						break;

					}

					else {

						array_push($childmiddlearray, $childmiddle);

					}

				}

				foreach ($_POST['childmonth'] as $childmonth) {

					if (empty($childmonth)) {

						$message.='<p>You forgot to fill up a month field on child\'s info!';
						$flag++;
						break;

					}

					else {

						array_push($childmontharray, $childmonth);

					}

				}

				foreach ($_POST['childday'] as $childday) {

					if (empty($childday)) {

						$message.='<p>You forgot to fill up a day field on child\'s info!';
						$flag++;
						break;

					}

					else {

						array_push($childdayarray, $childday);

					}

				}

				foreach ($_POST['childyear'] as $childyear) {

					if (empty($childyear)) {

						$message.='<p>You forgot to fill up a year field on child\'s info!';
						$flag++;
						break;

					}

					else {

						array_push($childyeararray, $childyear);

					}

				}

				if (isset($_POST['childsex'])) {

					foreach ($_POST['childsex'] as $childsex) {

							array_push($childsexarray, $childsex);

					}

				}

				else {

					$message.='<p>You forgot to choose the sex on child\'s info!';
					$flag++;

				}

			}

		}

			if ($flag == 0) {

				$idnum = $_SESSION['idnum'];

				for ($x = 0; $x < count($siblingmontharray); $x++) {

					$siblingbirthday = $siblingyeararray[$x] . "-" . $siblingmontharray[$x] . "-" . $siblingdayarray[$x];

					array_push($siblingbirthdatearray, $siblingbirthday);

				}

				for ($y = 0; $y < count($childmontharray); $y++) {

					$childbirthday = $childyeararray[$y] . "-" . $childmontharray[$y] . "-" . $childdayarray[$y];

					array_push($childbirthdatearray, $childbirthday);

				}

					$queryMem = "UPDATE MEMBER SET HEALTH_AID_STATUS = '{$appstatusid}' WHERE MEMBER_ID = '{$idnum}';";
					$resultMem = mysqli_query($dbc, $queryMem);

				/* father insert */

					$fatherstatus = $_POST['fatherstatus'];

					$queryFather = "INSERT INTO FATHER (MEMBER_ID, LASTNAME, FIRSTNAME, MIDDLENAME, BIRTHDATE, STATUS_ID) 

					VALUES ('{$idnum}', '{$fatherlast}', '{$fatherfirst}', '{$fathermiddle}', CAST('{$fatherbirthdate}' AS DATE), '{$fatherstatus}');";

					$resultFather = mysqli_query($dbc, $queryFather);

				/* mother insert */

					$motherstatus = $_POST['motherstatus'];

					$queryMother = "INSERT INTO MOTHER (MEMBER_ID, LASTNAME, FIRSTNAME, MIDDLENAME, BIRTHDATE, STATUS_ID) 

					VALUES ('{$idnum}', '{$motherlast}', '{$motherfirst}', '{$mothermiddle}', CAST('{$motherbirthdate}' AS DATE), '{$motherstatus}');";

					$resultMother = mysqli_query($dbc, $queryMother);

				/* sibling insert */

					for ($s = 0; $s < count($siblinglastarray); $s++) {

						$siblingf = $siblingfirstarray[$s];
						$siblingl = $siblinglastarray[$s];
						$siblingm = $siblingmiddlearray[$s];
						$siblings = $siblingsexarray[$s];
						$siblingb = $siblingbirthdatearray[$s];
						$siblingst = $siblingstatusarray[$s];

						echo $siblingf . " " . $siblingl . " " . $siblingm . " " . $siblings . " " . $siblingb . " " . $siblingst;

						$querySibling = "INSERT INTO SIBLINGS (MEMBER_ID, LASTNAME, FIRSTNAME, MIDDLENAME, SEX, BIRTHDATE, STATUS_ID)

						VALUES ('{$idnum}', '{$siblingl}', '{$siblingf}', '{$siblingm}', '{$siblings}', CAST('{$siblingb}' AS DATE), '{$siblingst}');";

						$resultSibling = mysqli_query($dbc, $querySibling);

					}

				/* children insert */

				if ($rowCiv['CIV_STATUS'] != 1) {

					for ($c = 0; $c < count($childlastarray); $c++) {

						$childf = $childfirstarray[$c];
						$childl = $childlastarray[$c];
						$childm = $childmiddlearray[$c];
						$childs = $childsexarray[$c];
						$childb = $childbirthdatearray[$c];
						$childst = $childstatusarray[$c];

						$queryChild = "INSERT INTO CHILDREN (MEMBER_ID, LASTNAME, FIRSTNAME, MIDDLENAME, SEX, BIRTHDATE, STATUS_ID)

						VALUES ('{$idnum}', '{$childl}', '{$childf}', '{$childm}', '{$childs}', CAST('{$childb}' AS DATE), '{$childst}');";

						$resultChild = mysqli_query($dbc, $queryChild);


					}

				}

				$queryTransaction = "INSERT INTO TRANSACTIONS (MEMBER_ID, AMOUNT, TXN_DATE, TXN_TYPE, TXN_STATUS) 
								 VALUES ({$_SESSION['idnum']}, 0, NOW(), 1, 'Health Aid Application Submitted')";

				$resultTransaction = mysqli_query($dbc, $queryTransaction);

			}

		}

	?>

	<body>

		<?php if (isset($message)){
		
			echo '<font color="red">'.$message. '</font><p>';
		
		} ?>

		<h2>Health Aid Application</h2>
		Accomplish the health aid application form below. <p>

		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

		<fieldset><p>

			<b>Enter parents information</b><p>

			<b>Father's information</b><p>

			Status of Father: <br>
			<select name="fatherstatus">

				<option value="1">Normal</option>
				<option value="0">Deceased</option>

			</select> <p>

			First name: <input type="text" name="fatherfirst">
			&nbsp; Last name: <input type="text" name="fatherlast">
			&nbsp; Middle name: <input type="text" name="fathermiddle"><p>

			--- Enter your father's birthdate below --- <p>

			Month: 
			<select name="fathermonth">

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

			&nbsp; Day: 
			<select name="fatherday">

				<option value="">Select Day</option>
				<option value=""> ----- </option>

				<?php for($x = 1; $x <= 31; $x++) { ?>

					<option value="<?php echo $x; ?>"><?php echo $x; ?></option>

				<?php } ?>

			</select>

			&nbsp; Year: 
			<select name="fatheryear">

				<option value="">Select Year</option>
				<option value=""> ----- </option>

				<?php for($y = 2017; $y >= 1900; $y--) { ?>

					<option value="<?php echo $y; ?>"><?php echo $y; ?></option>

				<?php } ?>

			</select>

			<p>

			<hr>

			<b>Mother's information</b><p>

			Status of Mother: <br>
			<select name="motherstatus">

				<option value="1">Normal</option>
				<option value="0">Deceased</option>

			</select> <p>

			First name: <input type="text" name="motherfirst">
			&nbsp; Last name: <input type="text" name="motherlast">
			&nbsp; Middle name: <input type="text" name="mothermiddle"><p>

			--- Enter your mother's birthdate below --- <p>

			Month: 
			<select name="mothermonth">

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

			&nbsp; Day: 
			<select name="motherday">

				<option value="">Select Day</option>
				<option value=""> ----- </option>

				<?php for($x = 1; $x <= 31; $x++) { ?>

					<option value="<?php echo $x; ?>"><?php echo $x; ?></option>

				<?php } ?>

			</select>

			&nbsp; Year: 
			<select name="motheryear">

				<option value="">Select Year</option>
				<option value=""> ----- </option>

				<?php for($y = 2017; $y >= 1900; $y--) { ?>

					<option value="<?php echo $y; ?>"><?php echo $y; ?></option>

				<?php } ?>

			</select>

			<p>

			<hr>

			<font color="red">Note: Please check the checkbox below if you do not have siblings. Excluding deceased siblings.</font><p>

			<input type="checkbox" name="hasSibling" value="1"> I don't have siblings<p>

			<div id="siblingform">
				<div class="siblingfields">

				<b>Siblings's information</b><p>

				Status of Sibling:
				<select name="siblingstatus[]">

					<option value="1">Normal</option>
					<option value="0">Deceased</option>

				</select> <p>

				First name: <input type="text" name="siblingfirst[]">
				&nbsp; Last name: <input type="text" name="siblinglast[]">
				&nbsp; Middle name: <input type="text" name="siblingmiddle[]"> <p>

				Sex:
				<select name="siblingsex[]">

					<option value="0">Male</option>
					<option value="1">Female</option>

				</select><p>

				--- Enter your sibling's birthdate below --- <p>

				Month: 
				<select name="siblingmonth[]">

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

				&nbsp; Day: 
				<select name="siblingday[]">

					<option value="">Select Day</option>
					<option value=""> ----- </option>

					<?php for($x = 1; $x <= 31; $x++) { ?>

						<option value="<?php echo $x; ?>"><?php echo $x; ?></option>

					<?php } ?>

				</select>

				&nbsp; Year: 
				<select name="siblingyear[]">

					<option value="">Select Year</option>
					<option value=""> ----- </option>

					<?php for($y = 2017; $y >= 1900; $y--) { ?>

						<option value="<?php echo $y; ?>"><?php echo $y; ?></option>

					<?php } ?>

				</select>

				<p>

				</div>
			</div>

				<button name="addsibling" class="addsibling">Add sibling</button> &nbsp;
				<button name="removesibling" class="removesibling">Remove previously added field</button>

			<hr>

		<?php if ($rowCiv['CIV_STATUS'] != 1) { ?>

			<font color="red">Note: Please check the checkbox below if you do not have children. Excluding deceased children.</font><p>

			<input type="checkbox" name="hasChild" value="1"> I don't have children<p>

			<div id="childform">
				<div class="childfields">

				<b>Childrens's information</b><p>

				Status of Child:
				<select name="childstatus[]">

					<option value="1">Normal</option>
					<option value="0">Deceased</option>

				</select> <p>

				First name: <input type="text" name="childfirst[]">
				&nbsp; Last name: <input type="text" name="childlast[]">
				&nbsp; Middle name: <input type="text" name="childmiddle[]"> <p>

				Sex:
				<select name="childsex[]">

					<option value="0">Male</option>
					<option value="1">Female</option>

				</select><p>

				--- Enter your child's birthdate below --- <p>

				Month: 
				<select name="childmonth[]">

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

				&nbsp; Day: 
				<select name="childday[]">

					<option value="">Select Day</option>
					<option value=""> ----- </option>

					<?php for($x = 1; $x <= 31; $x++) { ?>

						<option value="<?php echo $x; ?>"><?php echo $x; ?></option>

					<?php } ?>

				</select>

				&nbsp; Year: 
				<select name="childyear[]">

					<option value="">Select Year</option>
					<option value=""> ----- </option>

					<?php for($y = 2017; $y >= 1900; $y--) { ?>

						<option value="<?php echo $y; ?>"><?php echo $y; ?></option>

					<?php } ?>

				</select>

				<p>

				</div>
			</div>

				<button name="addchild" class="addchild">Add child</button> &nbsp;
				<button name="removechild" class="removechild">Remove previously added field</button>

		<?php } ?>

		<hr>

		<input type="submit" name="submit" value="Submit Application">

		</fieldset>

		</form><p>

		<a href="member homepage.php">

			<button type="button">Go back to Member Dashboard</button>

		</a>

		<script type="text/javascript" src="jquery-3.1.1.min.js"></script>
		<script>

			$(document).ready(function(){

				var iS = 1;
				var iC = 1;
				var sibfields = $('.siblingfields:first');
				var childfields = $('.childfields:first');

				/* adding child and sibling fields */

				$('body').on('click', '.addsibling', function() {

					iS++;

					var section = sibfields.clone().find(':input').each(function() {

						var newId = this.id + iS;

						this.id = newId;

					}).end().appendTo('#siblingform');

					return false;

				});

				$('body').on('click', '.addchild', function() {

					iC++;

					var section2 = childfields.clone().find(':input').each(function() {

						var newId2 = this.id + iC;

						this.id = newId2;

					}).end().appendTo('#childform');

					return false;

				});

				/* removing child and sibling fields */

				$('#siblingform').on('click', '.removesibling', function() {

					$this.parent().fadeOut(300, function() {

						$(this).parent().parent().empty();
						return false;

					});

					return false;

				});

				$('#childform').on('click', '.removechild', function() {

					$this.parent().fadeOut(300, function() {

						$(this).parent().parent().empty();
						return false;

					});

					return false;

				});

			});

		</script>

	</body>

</html>