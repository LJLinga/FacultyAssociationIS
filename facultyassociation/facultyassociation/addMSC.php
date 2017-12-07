<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../DataTables/media/css/jquery.dataTables.css">
	</head>
	<body>
		<script type="text/javascript" charset="utf8" src="../DataTables/media/js/jquery.js"></script>
		<script type="text/javascript" charset="utf8" src="../DataTables/media/js/jquery.dataTables.js"></script>
		<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
		<?php
		//Go back to the main page
		If(isset($_POST['displayAMSC'])){
			If($_POST['displayAMSC'] == "Go Back") header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/Manage Shopping Cart.php");
		}
		//User Validation 
		session_start();
		//Connect DB
		require_once('../mysql_connect_FA.php');
		
		if (isset($_POST['submitAMSC'])){ //Start of Update Code
			$message=NULL;
			$query="SELECT QTY_AVAIL FROM PRODUCT_LIST WHERE PRODUCT_ID =".$_POST['scProdID'];
			$result=mysqli_query($dbc,$query);
			$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
				echo"Available Products: {$row['QTY_AVAIL']}";
			
			if (empty($_POST['submitQTYORDERED'])){
			  $sQtyOrdered=0;
			  $message.='<p>You forgot to enter a value for Quantity Ordered!';
			}else{
				if (!is_numeric($_POST['submitQTYORDERED'])){
				$message.='<p>The Quantity Ordered you entered is not numeric!';
				}else{
					If ($_POST['submitQTYORDERED'] > $row['QTY_AVAIL']){
						$message.='<p>The Quantity you entered is not valid! Quantity exceeds the total number of available products!';
					}else{
						If ($_POST['submitQTYORDERED'] <= 0){
							$message.='<p>The Quantity you entered is not valid! Quantity is either zero or negative!';
						}else
							$sQtyOrdered=$_POST['submitQTYORDERED'];
					}
						
				}
			}
			
			if(!isset($message)){
				$query="INSERT INTO ORDERDETAILS (ORDER_ID, PRODUCT_ID, QTY_ORDERED, DEDUCTION_STATUS) 
				
				VALUES('{$_SESSION['sSCOrderID']}','{$_POST['scProdID']}','{$sQtyOrdered}', 1)";
				$resultMPB=mysqli_query($dbc,$query);
				$message="<b><p>Product added! </b>";
			}
		}/*End of main Submit conditional*/
		
		if (isset($message)){
			echo '<font color="red">'.$message. '</font>';
		}
	
		//displayAMPO options
		?>
		<table width="75%" border="1" align="center" id="table_id" class="displayMPO">
				<thead>
					<tr>
						<th></th>
						<th>PRODUCT NAME</th>
						<th>DESCRIPTION</th>
						<th>QTY AVAILABLE</th>
						<th>PRICE</th>
						<th>INTEREST</th>
						<th>PAYMENT TERMS</th>
						<th>NUM PAYMENTS</th>
						<th>TOTAL DEDUCTION</th>
						<th>MONTHLY DEDUCTION</th>
						<th>PER PAYMENT DEDUCTION</th>
					</tr>
				</thead>
				<tbody>
			<?php	//Retrieve Info
				$queryMSC="SELECT * FROM product_list WHERE STATUS = 1";
				$resultMSC=mysqli_query($dbc,$queryMSC);
				while($row=mysqli_fetch_array($resultMSC,MYSQLI_ASSOC)){
				$refProdID = $row['PRODUCT_ID'];
				echo "
					<tr>
						<td>
							<input type='radio' name='scProdID' value='$refProdID'>
						</td>
						<td>{$row['PRODUCT_NAME']}</td>
						<td>{$row['DESCRIPTION']}</td>
						<td>{$row['QTY_AVAIL']}</td>
						<td>{$row['PRICE']}</td>
						<td>{$row['INTEREST']}</td>
						<td>{$row['PAYMENT_TERMS']}</td>
						<td>{$row['NUM_PAYMENTS']}</td>
						<td>{$row['TOTAL_DEDUCTION']}</td>
						<td>{$row['MONTHLY_DEDUCTION']}</td>
						<td>{$row['PER_PAYMENT_DEDUCTION']}</td>
					</tr>
				";
				}
			?>
				</tbody>
		</table>
		<table width='50%' border='0' align='left'>
			<tr>
				<td>Quantity To Order:</td>
				<td><input type='text' name='submitQTYORDERED' placeholder='quantity'></td>
			</tr>
		</table><br><br><br>
			<input type="submit" name="submitAMSC" value="Add">
			<input type="submit" name="displayAMSC" value="Go Back">
		</form>
		<script>
				$('#table_id').DataTable();
		</script>
	</body>
</html>