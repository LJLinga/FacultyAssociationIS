<html>
	<head>
		<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
	</head>
	<body>
		
		<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
		<?php
		//Connect DB
		require_once('../mysql_connect_FA.php');
		
		//Variable
		//User Validation 
		session_start();
		$_SESSION["sProdID"] = 0; //Session variable to carry over the Product ID
		
		If(isset($_POST['displayMPO'])){
			Switch ($_POST['displayMPO']){
				case "Edit":
					$_SESSION["sProdID"] = $_POST["prodID"];
					header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/editMPO.php");
					break;
				case "Add":
					header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/addMPO.php");
					break;
				case "Remove":
					$query="UPDATE PRODUCT_LIST SET STATUS=3 WHERE PRODUCT_ID = {$_POST['prodID']}";
					$result=mysqli_query($dbc,$query);
					break;
			}
		}

		//displayMPO products > Name:ASC >
		echo'
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
						<th>STATUS</th>
					</tr>
				</thead>
				<tbody>
			';
			//Retrieve Info
			$query="SELECT * FROM product_list";
			$result=mysqli_query($dbc,$query);
			while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
				$refProdID = $row['PRODUCT_ID'];
				Switch($row['STATUS']){
					case 1:
						$prodStatus = "Available";
						break;
					case 2:
						$prodStatus = "Unavailable";
						break;
					case 3:
						$prodStatus = "Removed";
						break;	
				}
				
				echo "
					<tr>
						<td>
							<input type='radio' name='prodID' value='$refProdID'>
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
						<td>{$prodStatus}</td>
					</tr>
				";
			}
		echo '
			</tbody>
		</table>
			
		';
		//displayMPO options
		?>
			<input type="submit" name="displayMPO" value="Edit">
			<input type="submit" name="displayMPO" value="Remove">
			<input type="submit" name="displayMPO" value="Add">
		</form>
		
		<script type="text/javascript" src="jquery-3.1.1.min.js"></script>
		<script type="text/javascript" src="DataTables/datatables.min.js"></script>
		<script>

			$(document).ready(function(){
    
    			$('.displayMPO').DataTable();

			});

		</script>
	</body>
</html>