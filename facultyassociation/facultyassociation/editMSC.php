<html>
	<body>
		<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
		<?php
		//Go back to the main page
		If(isset($_POST['displayEMSC'])){
			If($_POST['displayEMSC'] == "Go Back") header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/Manage Shopping Cart.php");
		}
		//User Validation 
		session_start();
		
		//Connect DB
		require_once('../mysql_connect.php');
		if (isset($_POST['submitEMSC'])){ //Start of Update Code
			$message=NULL;
			$query="SELECT QTY_AVAIL FROM PRODUCT_LIST WHERE PRODUCT_ID =".$_SESSION["sSCProdID"];
			$result=mysqli_query($dbc,$query);
			$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
				
			if (empty($_POST['updateQTYORDERED'])){
			  $uQtyOrdered=0;
			  $message.='<p>You forgot to enter a value for Quantity Ordered!';
			}else{
				if (!is_numeric($_POST['updateQTYORDERED'])){
				$message.='<p>The Quantity Ordered you entered is not numeric!';
				}else{
					If ($_POST['updateQTYORDERED'] > $row['QTY_AVAIL']){
						$message.='<p>The Quantity you entered is not valid! Quantity exceeds the total number of available products!';
					}else{
						If ($_POST['updateQTYORDERED'] <= 0){
							$message.='<p>The Quantity you entered is not valid! Quantity is either zero or negative!';
						}else
							$uQtyOrdered=$_POST['updateQTYORDERED'];
					}
						
				}
			}
			
			if(!isset($message)){
				$query="UPDATE ORDERDETAILS SET QTY_ORDERED='{$uQtyOrdered}' WHERE PRODUCT_ID = '{$_SESSION["sSCProdID"]}'";
				$resultEditMPO=mysqli_query($dbc,$query);
				$message="<b><p>Quantity updated! </b>";
			}
		}/*End of main Submit conditional*/
		
		if (isset($message)){
			echo '<font color="red">'.$message. '</font>';
		}

		//Edit Product area
		//Retrieve Info
		$query="SELECT PRODUCT_NAME, DESCRIPTION, PRICE, QTY_ORDERED  FROM ORDERDETAILS O JOIN PRODUCT_LIST PL ON O.PRODUCT_ID = PL.PRODUCT_ID WHERE O.PRODUCT_ID =".$_SESSION["sSCProdID"];
		$result=mysqli_query($dbc,$query);
		$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
		echo"
			<table width='50%' border='0' align='left'>
				<tr>
					<td>Product Name:</td>
					<td><input type='text' value='{$row['PRODUCT_NAME']}' disabled></td>
				</tr>
				<tr>
					<td>Description:</td>
					<td><input type='text' value='{$row['DESCRIPTION']}' disabled></td>
				</tr>
				<tr>
					<td>Price:</td>
					<td><input type='text' value='{$row['PRICE']}' disabled></td>
				</tr>
				<tr>
					<td>Quantity:</td>
					<td><input type='text' name='updateQTYORDERED'value='{$row['QTY_ORDERED']}' ></td>
				</tr>
			</table>
		";
	
		//Display options
		?><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
			<input type="submit" name="submitEMSC" value="Update">
			<input type="submit" name="displayEMSC" value="Go Back">	
		</form>

	</body>
</html>