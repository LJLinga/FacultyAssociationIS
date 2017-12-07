<html>
	<body>
		<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
		<?php
		//Go back to the main page
		If(isset($_POST['displayEMPO'])){
			If($_POST['displayEMPO'] == "Go Back") header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/Manage Product Offering.php");
		}
		//User Validation 
		session_start();
		
		//Connect DB
		require_once('../mysql_connect_FA.php');
		if (isset($_POST['submitEMPO'])){ //Start of Update Code
			$message=NULL;
			if (empty($_POST['updateProdName'])){
			  $uProdName=NULL;
			  $message.='<p>You forgot to enter a valid Product name!';
			}else
			  $uProdName=$_POST['updateProdName'];
			
			if (empty($_POST['updateDesc'])){
			  $uDesc=NULL;
			  $message.='<p>You forgot to enter a valid Product Description!';
			}else
			  $uDesc=$_POST['updateDesc'];

			if (empty($_POST['updateQtyAvail'])){
			  $uQtyAvail=0;
			  $message.='<p>You forgot to enter a valid Quantity Available!';
			}else{
				if (!is_numeric($_POST['updateQtyAvail'])){
				$message.='<p>The Quantity Available you entered is not numeric!';
				}else
				$uQtyAvail=$_POST['updateQtyAvail'];
			}
			if (empty($_POST['updatePrice'])){
				$uPrice=0;
				$message.='<p>You forgot to enter a valid Price!';
			}else{
				if (!is_numeric($_POST['updatePrice'])){
				$message.='<p>The Price you entered is not numeric!';
				}else
				$uPrice=$_POST['updatePrice'];
			}
			
			if (empty($_POST['updateInterest'])){
				$uInterest=NULL;
				$message.='<p>You forgot to enter an Interest!';
			}else
				$uInterest=$_POST['updateInterest'];
			
			if (empty($_POST['updatePaymentTerms'])){
				$uPaymentTerms=NULL;
				$message.='<p>You forgot to enter the Payment Terms!';
			}else
				$uPaymentTerms=$_POST['updatePaymentTerms'];
			
			if (empty($_POST['updateNumPayments'])){
				$uNumPayments=NULL;
				$message.='<p>You forgot to enter the number of Payments!';
			}else
				$uNumPayments=$_POST['updateNumPayments'];
			
			if (empty($_POST['updateTotalDeduction'])){
				$uTotalDeductions=NULL;
				$message.='<p>You forgot to enter the total deductions!';
			}else
				$uTotalDeductions=$_POST['updateTotalDeduction'];
			
			if (empty($_POST['updateMonthlyDeduction'])){
				$uMonDeductions=NULL;
				$message.='<p>You forgot to enter the monthly Deduction!';
			}else
				$uMonDeductions=$_POST['updateMonthlyDeduction'];
			
			if (empty($_POST['updatePerPaymentDeduction'])){
				$uPerPaymentDeductions=NULL;
				$message.='<p>You forgot to enter the Per Payment Deductions!';
			}else
				$uPerPaymentDeductions=$_POST['updatePerPaymentDeduction'];
			
			if (empty($_POST['updateStatus'])){
				$uStatus=NULL;
				$message.='<p>You forgot to enter the Status!';
			}else
				$uStatus=$_POST['updateStatus'];
			
			if(!isset($message)){
				$query="UPDATE PRODUCT_LIST SET PRODUCT_NAME='{$uProdName}', DESCRIPTION='{$uDesc}', QTY_AVAIL='{$uQtyAvail}', PRICE='{$uPrice}', INTEREST='{$uInterest}', PAYMENT_TERMS='{$uPaymentTerms}', NUM_PAYMENTS='{$uNumPayments}', TOTAL_DEDUCTION='{$uTotalDeductions}', MONTHLY_DEDUCTION='{$uMonDeductions}', PER_PAYMENT_DEDUCTION='{$uPerPaymentDeductions}', STATUS='{$uStatus}' WHERE PRODUCT_ID = '{$_SESSION["sProdID"]}'";
				$resultEditMPO=mysqli_query($dbc,$query);
				$message="<b><p>Product Name: {$uProdName} updated! </b>";
			}
		}/*End of main Submit conditional*/
		
		if (isset($message)){
			echo '<font color="red">'.$message. '</font>';
		}

		//Edit Product area
		//Retrieve Info
		$query="SELECT * FROM product_list WHERE PRODUCT_ID =".$_SESSION["sProdID"];
		$result=mysqli_query($dbc,$query);
		$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
		echo"
			<table width='50%' border='0' align='left'>
				<tr>
					<td>Product Name:</td>
					<td><input type='text' name='updateProdName' value='{$row['PRODUCT_NAME']}'></td>
				</tr>
				<tr>
					<td>Product Description:</td>
					<td><input type='text' name='updateDesc' value='{$row['DESCRIPTION']}'></td>
				</tr>
				<tr>
					<td>Qty Available:</td>
					<td><input type='text' name='updateQtyAvail' value='{$row['QTY_AVAIL']}'></td>
				</tr>
				<tr>
					<td>Price:</td>
					<td><input type='text' name='updatePrice' value='{$row['PRICE']}'></td>
				</tr>
				<tr>
					<td>Interest:</td>
					<td><input type='text' name='updateInterest' value='{$row['INTEREST']}'></td>
				</tr>
				<tr>
					<td>Payment Terms:</td>
					<td><input type='text' name='updatePaymentTerms' value='{$row['PAYMENT_TERMS']}'></td>
				</tr>
				<tr>
					<td>Num of Payments:</td>
					<td><input type='text' name='updateNumPayments' value='{$row['NUM_PAYMENTS']}'></td>
				</tr>
				<tr>
					<td>Total Deductions:</td>
					<td><input type='text' name='updateTotalDeduction' value='{$row['TOTAL_DEDUCTION']}'></td>
				</tr>
				<tr>
					<td>Monthly Deduction:</td>
					<td><input type='text' name='updateMonthlyDeduction' value='{$row['MONTHLY_DEDUCTION']}'></td>
				</tr>
				<tr>
					<td>Per Payment Deduction:</td>
					<td><input type='text' name='updatePerPaymentDeduction' value='{$row['PER_PAYMENT_DEDUCTION']}'></td>
				</tr>
				<tr>
					<td>Status:</td>
					<td>
						<select name='updateStatus'>
		";					Switch($row['STATUS']){
								Case 1:
									echo"
										<option value='1' selected> Available </option>
										<option value='2'> Unavailable </option>
										<option value='3'> Removed </option>
									";
									break;
								Case 2:
									echo"
										<option value='1'> Available </option>
										<option value='2' selected> Unavailable </option>
										<option value='3'> Removed </option>
									";
									break;
								Case 3:
									echo"
										<option value='1'> Available </option>
										<option value='2'> Unavailable </option>
										<option value='3' selected> Removed </option>
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
			<input type="submit" name="submitEMPO" value="Update">
			<input type="submit" name="displayEMPO" value="Go Back">	
		</form>

	</body>
</html>