<html>
	<body>
		<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
		<?php
		//Go back to the main page
		If(isset($_POST['displayAMPB'])){
			If($_POST['displayAMPB'] == "Go Back") header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/Manage Partner Banks.php");
		}
		//User Validation 
		session_start();
		//Connect DB
		require_once('../mysql_connect_FA.php');
		
		if (isset($_POST['submitAMPB'])){ //Start of Update Code
			$message=NULL;
			if (empty($_POST['addBankName'])){
			  $aBankName=NULL;
			  $message.='<p>You forgot to enter a valid Bank name!';
			}else
			  $aBankName=$_POST['addBankName'];
			
			if (empty($_POST['addBankAbbv'])){
			  $aBankAbbv=NULL;
			  $message.='<p>You forgot to enter a valid Bank Abbreviation!';
			}else
			  $aBankAbbv=$_POST['addBankAbbv'];
			
			if (empty($_POST['addEmpID'])){
			  $aEmpID=NULL;
			  $message.='<p>You forgot to enter a valid Employee ID!';
			}else
			  $aEmpID=$_POST['addEmpID'];
			
			if(!isset($message)){
				$query="INSERT INTO BANK_LIST (BANK_NAME, BANK_ABBV,STATUS,EMP_ID_ADDED,DATE_ADDED) 
				
				VALUES('{$aBankName}','{$aBankAbbv}',1,'{$aEmpID}',DATE(NOW()))";
				$resultMPB=mysqli_query($dbc,$query);
				$message="<b><p>Product Name: {$aBankName} updated! </b>";
			}
		}/*End of main Submit conditional*/
		
		if (isset($message)){
			echo '<font color="red">'.$message. '</font>';
		}
	
		//displayAMPO options
		?>
		<table width='50%' border='0' align='left'>
			<tr>
				<td>Bank Name:</td>
				<td><input type="text" name="addBankName" placeholder="Bank Name"></td>
			</tr>
			<tr>
				<td>Bank Abbreviation:</td>
				<td><input type="text" name="addBankAbbv" placeholder="Bank Abbrevation"></td>
			</tr>
			<tr>
				<td>Employee ID:</td>
				<td><input type="text" name="addEmpID" placeholder="Employee ID of the one adding bank"></td>
			</tr>
			
		</table><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
			<input type="submit" name="submitAMPB" value="Add">
			<input type="submit" name="displayAMPB" value="Go Back">
		</form>
	</body>
</html>