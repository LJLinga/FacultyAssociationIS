<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../DataTables/media/css/jquery.dataTables.css">
	</head>
	<body>
		<script type="text/javascript" charset="utf8" src="../DataTables/media/js/jquery.js"></script>
		<script type="text/javascript" charset="utf8" src="../DataTables/media/js/jquery.dataTables.js"></script>
		
		<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
		<?php
		//Connect DB
		require_once('../mysql_connect_FA.php');
		
		//Variable
		//User Validation 
		session_start();
		$_SESSION["sBankID"] = 0; //Session variable to carry over the Bank ID
		
		If(isset($_POST['displayMPB'])){
			Switch ($_POST['displayMPB']){
				case "Edit":
					$_SESSION["sBankID"] = $_POST["bankID"];
					header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/editMPB.php");
					break;
				case "Add":
					header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/addMPB.php");
					break;
				case "Remove":
					$query="UPDATE BANK_LIST SET STATUS=2 WHERE BANK_ID = {$_POST['bankID']}";
					$result=mysqli_query($dbc,$query);
					break;
			}
		}
		

		//displayMPB Banks > Name:ASC > 
		echo '
		<table width="75%" border="1" align="center" id="table_id" class="displayMPB">
			<thead>
				<tr>
					<th></th>
					<th>BANK NAME</th>
					<th>BANK ABBV</th>
					<th>STATUS</th>
					<th>EMP ID ADDED</th>
					<th>DATE ADDED</th>
					<th>DATE REMOVE</th>
				</tr>
			</thead>
			<tbody>
		';
		//Retrieve Info
				$query="SELECT * FROM bank_list";
				$result=mysqli_query($dbc,$query);
				while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
				$refBankID = $row['BANK_ID'];
				$bankDateAdded = date('M d, Y', strtotime($row['DATE_ADDED']));
				//This code prevents a default value to be generated if the $row['DATE_REMOVED'] being formatted is null
				If (isset($row['DATE_REMOVED'])) $bankDateRemoved = date('M d, Y', strtotime($row['DATE_REMOVED']));
				else $bankDateRemoved = null;
				Switch($row['STATUS']){
					case 1:
						$bankStatus = "Active";
						break;
					case 2:
						$bankStatus = "Inactive";
						break;
				}
				echo "
					<tr>
						<td>
							<input type='radio' name='bankID' value='$refBankID'>
						</td>
						<td>{$row['BANK_NAME']}</td>
						<td>{$row['BANK_ABBV']}</td>
						<td>$bankStatus</td>
						<td>{$row['EMP_ID_ADDED']}</td>
						<td>$bankDateAdded</td>
						<td>$bankDateRemoved</td>
					</tr>
				";
				}
		echo '
			</tbody>
		</table>
		';
		//displayMPB options
		?>
			<input type="submit" name="displayMPB" value="Edit">
			<input type="submit" name="displayMPB" value="Add">
			<input type="submit" name="displayMPB" value="Remove">
		</form>
		
		<script>
				$('#table_id').DataTable();
		</script>
	</body>
</html>