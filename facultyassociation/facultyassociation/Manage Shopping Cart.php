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
		$_SESSION["sSCProdID"] = 0; //Session variable to carry over the Product ID in Shopping Cart
		$_SESSION["sSCOrderID"] = 0; //Session variable to carry over the Order ID in Shopping Cart

		$querySOI="SELECT MAX(ORDER_ID) as ORDER_ID, CHECK_OUT FROM ORDERS LIMIT 1";
		$resultSOI=mysqli_query($dbc,$querySOI);
		$rowSOI=mysqli_fetch_array($resultSOI,MYSQLI_ASSOC);
		If($rowSOI['CHECK_OUT'] == 1) $_SESSION["sSCOrderID"] = $rowSOI['ORDER_ID'] + 1;
		else $_SESSION["sSCOrderID"] = $rowSOI['ORDER_ID'];
		
		If(isset($_POST['displayMSC'])){
			Switch ($_POST['displayMSC']){
				case "Modify Order":
					$_SESSION["sSCProdID"] = $_POST["scProdID"];
					header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/editMSC.php");
					break;
				case "Add another product":
					header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/addMSC.php");
					break;
				case "Remove An Order":
					$query="DELETE FROM ORDERDETAILS WHERE PRODUCT_ID = {$_POST['scProdID']}";
					$result=mysqli_query($dbc,$query);
					break;
				case "Order All Items":
					$query="UPDATE ORDERS SET CHECK_OUT = 1"; //Check out items
					$result=mysqli_query($dbc,$query);
					
					$query="INSERT INTO TRANSACTIONS ( MEMBER_ID, AMOUNT, TXN_DATE,TXN_TYPE, TXN_STATUS)
					VALUES('222222222','{$_SESSION["sGrandTotal"]}', DATE(NOW()), 1, 'Ordered Items')";
					$result=mysqli_query($dbc,$query);
					echo "Products Ordered!";
					break;
			}
		}
		//Display Shopping Cart Contents > Name:ASC > 
		echo '
		<table width="75%" border="1" align="center" id="table_id" class="display">
			<thead>
				<tr>
					<th></th>
					<th>PRODUCT NAME</th>
					<th>DESCRIPTION</th>
					<th>QTY</th>
					<th>PRICE</th>
					<th>TOTAL PRICE</th>
				</tr>
			</thead>
			<tbody>
		';
		//Retrieve Info
				$mscGrandTotal = 0;
				$query="SELECT O.ORDER_ID, O.PRODUCT_ID, PRODUCT_NAME, DESCRIPTION, PRICE, QTY_ORDERED  FROM ORDERDETAILS O JOIN PRODUCT_LIST PL ON O.PRODUCT_ID = PL.PRODUCT_ID WHERE O.ORDER_ID = {$_SESSION["sSCOrderID"]}";
				$result=mysqli_query($dbc,$query);
				while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
					$refSCProdID = $row['PRODUCT_ID']; 
					$mscQTYOrdered = $row['QTY_ORDERED'];
					$mscPrice = $row['PRICE'];
					$mscTotalPrice = number_format($mscPrice*$mscQTYOrdered,2);
					$mscGrandTotal+= $mscPrice*$mscQTYOrdered;
						echo "
							<tr>
								<td>
									<input type='radio' name='scProdID' value='$refSCProdID'>
								</td>
								<td>{$row['PRODUCT_NAME']}</td>
								<td>{$row['DESCRIPTION']}</td>
								<td>$mscQTYOrdered</td>
								<td>$mscPrice</td>
								<td>$mscTotalPrice</td>
							</tr>
						";
				}
		$mscGrandTotal = number_format($mscGrandTotal,2);
		$_SESSION["sGrandTotal"] = $mscGrandTotal;
		echo "
			</tbody>
		</table>
		<table width='50%' border='0' align='right'>
			<tr>
				<td>Total Price:</td>
				<td><input type='text' value='$mscGrandTotal' disabled></td>
			</tr>
		</table><br><br><br>
		";
		//Display options
		?>
			<input type="submit" name="displayMSC" value="Modify Order">
			<input type="submit" name="displayMSC" value="Add another product">
			<input type="submit" name="displayMSC" value="Remove An Order">
			<input type="submit" name="displayMSC" value="Order All Items">
		</form>
		<?php
		//Edit

		//Add

		//Remove - Change Status
		?>
		<script>
				$('#table_id').DataTable();
		</script>
	</body>
</html>