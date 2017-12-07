<html>
	<body>
		<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
		<?php
		//Go back to the main page
		If(isset($_POST['displayAMPO'])){
			If($_POST['displayAMPO'] == "Go Back") header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/Manage Product Offering.php");
		}
		//User Validation 
		session_start();
		//Connect DB
		require_once('../mysql_connect_FA.php');
		
		if (isset($_POST['submitAMPO'])){ //Start of Update Code
			$message=NULL;
			echo "Start submission";
			if (empty($_POST['addProdName'])){
			  $aProdName=NULL;
			  $message.='<p>You forgot to enter a valid Product name!';
			}else
			  $aProdName=$_POST['addProdName'];
			
			if (empty($_POST['addDesc'])){
			  $aDesc=NULL;
			  $message.='<p>You forgot to enter a valid Product Description!';
			}else
			  $aDesc=$_POST['addDesc'];

			if (empty($_POST['addQtyAvail'])){
			  $aQtyAvail=0;
			  $message.='<p>You forgot to enter a valid Quantity Available!';
			}else{
				if (!is_numeric($_POST['addQtyAvail'])){
				$message.='<p>The Quantity Available you entered is not numeric!';
				}else
				$aQtyAvail=$_POST['addQtyAvail'];
			}
			if (empty($_POST['addPrice'])){
				$aPrice=0;
				$message.='<p>You forgot to enter a valid Price!';
			}else{
				if (!is_numeric($_POST['addPrice'])){
				$message.='<p>The Price you entered is not numeric!';
				}else
				$aPrice=$_POST['addPrice'];
			}
			
			if (empty($_POST['addInterest'])){
				$aInterest=NULL;
				$message.='<p>You forgot to enter an Interest!';
			}else
				$aInterest=$_POST['addInterest'];
			
			if (empty($_POST['addPaymentTerms'])){
				$aPaymentTerms=NULL;
				$message.='<p>You forgot to enter the Payment Terms!';
			}else
				$aPaymentTerms=$_POST['addPaymentTerms'];
			
			if (empty($_POST['addNumPayments'])){
				$aNumPayments=NULL;
				$message.='<p>You forgot to enter the number of Payments!';
			}else
				$aNumPayments=$_POST['addNumPayments'];
			
			if (empty($_POST['addTotalDeduction'])){
				$aTotalDeductions=NULL;
				$message.='<p>You forgot to enter the total deductions!';
			}else
				$aTotalDeductions=$_POST['addTotalDeduction'];
			
			if (empty($_POST['addMonthlyDeduction'])){
				$aMonDeductions=NULL;
				$message.='<p>You forgot to enter the monthly Deduction!';
			}else
				$aMonDeductions=$_POST['addMonthlyDeduction'];
			
			if (empty($_POST['addPerPaymentDeduction'])){
				$aPerPaymentDeductions=NULL;
				$message.='<p>You forgot to enter the Per Payment Deductions!';
			}else
				$aPerPaymentDeductions=$_POST['addPerPaymentDeduction'];
			
			if(!isset($message)){
				$query="INSERT INTO PRODUCT_LIST (PRODUCT_NAME,DESCRIPTION,QTY_AVAIL,PRICE,INTEREST,PAYMENT_TERMS,NUM_PAYMENTS,TOTAL_DEDUCTION,MONTHLY_DEDUCTION,PER_PAYMENT_DEDUCTION,STATUS) 
				
				VALUES('{$aProdName}','{$aDesc}','{$aQtyAvail}','{$aPrice}','{$aInterest}','{$aPaymentTerms}', '{$aNumPayments}','{$aTotalDeductions}','{$aMonDeductions}','{$aPerPaymentDeductions}',1)";
				$resultAddMPO=mysqli_query($dbc,$query);
				$message="<b><p>Product Name: {$aProdName} updated! </b>";
			}
		}/*End of main Submit conditional*/
		
		if (isset($message)){
			echo '<font color="red">'.$message. '</font>';
		}
	
		//displayAMPO options
		?>
		<table width='50%' border='0' align='left'>
			<tr>
				<td>Product Name:</td>
				<td><input type="text" name="addProdName" placeholder="Product Name"></td>
			</tr>
			<tr>
				<td>Product Description:</td>
				<td><input type="text" name="addDesc" placeholder="Description"></td>
			</tr>
			<tr>
				<td>Qty Available:</td>
				<td><input type="text" name="addQtyAvail" placeholder="Qty Available"></td>
			</tr>
			<tr>
				<td>Price:</td>
				<td><input type="text" name="addPrice" placeholder="Php 00.00"></td>
			</tr>
			<tr>
				<td>Interest:</td>
				<td><input type="text" name="addInterest" placeholder="00.00%"></td>
			</tr>
			<tr>
				<td>Payment Terms:</td>
				<td><input type="text" name="addPaymentTerms" placeholder="Terms"></td>
			</tr>
			<tr>
				<td>Num of Payments:</td>
				<td><input type="text" name="addNumPayments" placeholder="Num Payments"></td>
			</tr>
			<tr>
				<td>Total Deductions:</td>
				<td><input type="text" name="addTotalDeduction" placeholder="Total Deduction"></td>
			</tr>
			<tr>
				<td>Monthly Deduction:</td>
				<td><input type="text" name="addMonthlyDeduction" placeholder="Monthly Deduction"></td>
			</tr>
			<tr>
				<td>Per Payment Deduction:</td>
				<td><input type="text" name="addPerPaymentDeduction" placeholder="Per Payment Deduction"></td>
			</tr>
		</table><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
			<input type="submit" name="submitAMPO" value="Add">
			<input type="submit" name="displayAMPO" value="Go Back">
		</form>
	</body>
</html>