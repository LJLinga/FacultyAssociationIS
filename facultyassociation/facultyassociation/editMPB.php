<html>
	<body>
		<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
		<?php
		//Go back to the main page
		If(isset($_POST['displayEMPB'])){
			If($_POST['displayEMPB'] == "Go Back") header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/Manage Partner Banks.php");
		}
		//User Validation 
		session_start();
		
		//Connect DB
		require_once('../mysql_connect_FA.php');
		if (isset($_POST['submitEMPB'])){ //Start of Update Code
			$message=NULL;
			if (empty($_POST['updateBankName'])){
			  $uBankName=NULL;
			  $message.='<p>You forgot to enter a valid Bank name!';
			}else
			  $uBankName=$_POST['updateBankName'];
			
			if (empty($_POST['updateBankAbbv'])){
			  $uBankAbbv=NULL;
			  $message.='<p>You forgot to enter a valid Bank Abbreviation!';
			}else
			  $uBankAbbv=$_POST['updateBankAbbv'];
			
			if (empty($_POST['updateStatus'])){
			  $uStatus=NULL;
			  $message.='<p>You forgot to enter a valid Bank Status!';
			}else
			  $uStatus=$_POST['updateStatus'];
			
			
			if(!isset($message)){
				$query="UPDATE BANK_LIST SET BANK_NAME='{$uBankName}', BANK_ABBV='{$uBankAbbv}', STATUS='{$uStatus}' WHERE BANK_ID = '{$_SESSION["sBankID"]}'";
				$resultEditMPO=mysqli_query($dbc,$query);
				$message="<b><p>Product Name: {$uBankName} updated! </b>";
			}
		}/*End of main Submit conditional*/
		
		if (isset($message)){
			echo '<font color="red">'.$message. '</font>';
		}

		//Edit Product area
		//Retrieve Info
		$query="SELECT * FROM BANK_LIST WHERE BANK_ID =".$_SESSION["sBankID"];
		$result=mysqli_query($dbc,$query);
		$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
		echo"
			<table width='50%' border='0' align='left'>
				<tr>
					<td>Bank Name:</td>
					<td><input type='text' name='updateBankName' value='{$row['BANK_NAME']}'></td>
				</tr>
				<tr>
					<td>Bank Abbreviation:</td>
					<td><input type='text' name='updateBankAbbv' value='{$row['BANK_ABBV']}'></td>
				</tr>
				<tr>
					<td>Status:</td>
					<td>
						<select name='updateStatus'>
		";					Switch($row['STATUS']){
								Case 1:
									echo"
										<option value='1' selected> Active </option>
										<option value='2'> Inactive </option>
									";
									break;
								Case 2:
									echo"
										<option value='1'> Active </option>
										<option value='2' selected> Inactive </option>
									";
									break;
							}
		echo"
						</select>
					</td>
				</tr>
			</table>
		";
	
		//Display options
		?><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
			<input type="submit" name="submitEMPB" value="Update">
			<input type="submit" name="displayEMPB" value="Go Back">	
		</form>

	</body>
</html>