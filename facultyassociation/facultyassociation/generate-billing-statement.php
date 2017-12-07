
<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">

    <link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>

</head>
<body><?php session_start();

if ($_SESSION['usertype'] != 1) {

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
			
}


require_once('../mysql_connect_FA.php');
$flag=0;
$query="SELECT m.LASTNAME AS 'LAST',m.FIRSTNAME AS 'FIRST',m.MIDDLENAME AS 'MIDDLE',SUM(nl.PER_PAYMENT_DEDUCTION) as 'Loan Deduction',f.PER_PAYMENT_DEDUCTION as 'FALP',m.HEALTH_AID_STATUS as 'Status',p.PER_PAYMENT_DEDUCTION as 'Products'
		from MEMBER m 
		left join (SELECT l.PER_PAYMENT_DEDUCTION,ml.LOAN_ID,ml.MEMBER_ID FROM MEMBER_LOANS ml JOIN loan_list l on l.LOAN_ID= ml.LOAN_ID WHERE l.LOAN_ID NOT LIKE '1%' AND APP_STATUS=2 AND LOAN_STATUS = 2) nl
		on m.member_id = nl.MEMBER_ID
		left join loan_list l
		on nl.loan_id = l.loan_id
		left join (SELECT l.PER_PAYMENT_DEDUCTION,ml.LOAN_ID,ml.MEMBER_ID FROM MEMBER_LOANS ml JOIN loan_list l on l.LOAN_ID= ml.LOAN_ID  WHERE l.LOAN_ID LIKE '1%' AND APP_STATUS =2 and LOAN_STATUS = 2) f
		on f.MEMBER_ID = m.MEMBER_ID
        left join (SELECT p.PER_PAYMENT_DEDUCTION,o.MEMBER_ID FROM orders o JOIN orderdetails od on o.order_id= od.ORDER_ID join product_list p on p.PRODUCT_ID = od.PRODUCT_ID  WHERE od.DEDUCTION_STATUS = 2) p
        on p.member_ID = m.MEMBER_ID
		group by m.MEMBER_ID";
	
$result1=mysqli_query($dbc,$query);
	


	




if (isset($_POST['submit'])){
			if($_POST['submit'] == 'Print Billing Statement')
			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/saveBill.php");	
			else if($_POST['submit'] == 'Apply Deductions'){
				$query2 ="SELECT l.PER_PAYMENT_DEDUCTION as 'PER_PAYMENT_DEDUCTION',ml.LOAN_ID,ml.MEMBER_ID,ml.TOTAL_PAID,ml.NUM_PAY_COMPLETED,ml.PICKUP_STATUS,l.NUM_PAYMENTS 
				FROM MEMBER_LOANS ml 
				JOIN loan_list l 
				on l.loan_id = ml.loan_id 
				where APP_STATUS=2";
				$result2 = mysqli_query($dbc,$query2);
				if(isset($result2))
				while($row = mysqli_fetch_assoc($result2)){
					$query2= "UPDATE member_loans set TOTAL_PAID = {$row['TOTAL_PAID']}+{$row['PER_PAYMENT_DEDUCTION']},NUM_PAY_COMPLETED = {$row['NUM_PAY_COMPLETED']}+1 where MEMBER_ID = {$row['MEMBER_ID']} AND {$row['LOAN_ID']} = LOAN_ID";
					
					mysqli_query($dbc,$query2);
					
					$query3 = "INSERT into transactions(MEMBER_ID,AMOUNT,TXN_DATE,TXN_TYPE,TXN_STATUS, EMP_ID_INVOLVED) values ({$row['MEMBER_ID']},{$row['PER_PAYMENT_DEDUCTION']},NOW(),2,'Deduction Completed', {$_SESSION['idnum']})";
					mysqli_query($dbc,$query3);
					if($row['NUM_PAY_COMPLETED'] + 1 == $row['NUM_PAYMENTS']){
						$query2 = "Update member_loans set LOAN_STATUS = 3 where member_ID ='{$row['MEMBER_ID']}' AND '{$row['LOAN_ID']}'= LOAN_ID";
						mysqli_query($dbc,$query2);	
					}
				}
				
				$query2 = "SELECT p.PRODUCT_ID,p.PER_PAYMENT_DEDUCTION as 'PER_PAYMENT_DEDUCTION',o.order_ID,o.MEMBER_ID,o.TOTAL_PAID,o.NUM_PAY_COMPLETED,o.PICKUP_STATUS,p.NUM_PAYMENTS 
							FROM orders o 
							JOIN orderdetails od 
							on o.order_id= od.ORDER_ID 
							join product_list p on p.PRODUCT_ID = od.PRODUCT_ID  
							WHERE od.DEDUCTION_STATUS = 1";
				$result3 = mysqli_query($dbc,$query2);
				if(isset($result2))
				while($row = mysqli_fetch_assoc($result3)){
					$query2= "Update ORDERS 
						set TOTAL_PAID = TOTAL_PAID+{$row['PER_PAYMENT_DEDUCTION']},NUM_PAY_COMPLETED = NUM_PAY_COMPLETED+1 
						where MEMBER_ID = {$row['MEMBER_ID']} AND {$row['ORDER_ID']} = ORDER_ID";
					$query3 = "Insert into transactions(MEMBER_ID,AMOUNT,TXN_DATE,TXN_TYPE,TXN_STATUS) values ({$_SESSION['member_id']},{$row['PER_PAYMENT_DEDUCTION']},NOW(),2,'Deduction Completed')";
					mysqli_query($dbc,$query3);
					mysqli_query($dbc,$query2);
					if($row['NUM_PAY_COMPLETED'] + 1 == $row['NUM_PAYMENTS']){
						$query2 = "Update ORDERDETAILS 
						set DEDUCTION_STATUS = 3 
						where '{$row['ORDER_ID']}'= ORDER_ID AND PRODUCT_ID = '{$row['PRODUCT_ID']}";
						mysqli_query($dbc,$query2);	
					}
				}
				
			}
			
				
				
	
}
$result=mysqli_query($dbc,$query);

?>

<form id = "form1" runat = "server" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">


 
 <table id="datatable" class = "cell-border">
<thead>

<th><div align="center"><b>Member
</div></b></th>
<th><div align="center"><b>MEMBERSHIP FEE
</div></b></th>
<th><div align="center"><b>FALP
</div></b></th>
<th><div align="center"><b>BANK
</div></b></th>

<th><div align="center"><b>HEALTH AID BENEFIT
</div></b></th>
<th><div align="center"><b>PRODUCTS ORDERED
</div></b></th>

<th><div align="center"><b>TOTAL
</div></b></th>

</thead>
<?php
$total1=0.00;

while($row=mysqli_fetch_assoc($result)){

echo "<tr>
<td ><div align=\"left\">{$row['LAST']}, {$row['FIRST']} {$row['MIDDLE']}
</div></td>
<td ><div align=\"right\">";
echo '100.00';
echo "</div></td>
<td ><div align=\"right\">";

if(!empty($row['FALP']))
	echo $row['FALP'];

echo "</div></td>
<td ><div align=\"right\">";
if(!empty($row['Loan Deduction']))
	echo $row['Loan Deduction'];


echo "</div></td>
<td ><div align=\"right\">";
if($row['Status']==2)
	echo '100.00';
echo "</div></td>
";
echo "<td ><div align=\"right\">";
if(!empty($row['Products']))
	echo $row['Products'];


echo "</div></td>";
echo "
<td ><div align=\"right\">";
$total= 0.00;	
$total = 100.00+(float)$row['Loan Deduction']+(float)$row['FALP']+(float)$row['Products'];
if($row['Status'] == 2){
	$total = $total + 100.00;
}
echo sprintf("%.2f",$total);
$total1 = $total1 + $total;
echo "</div></td></tr>";



}
/*echo '<td ><div align=\"left\"><b> TOTAL:
</div></td>';
$i = 0;
while($i<5){
echo '
<td ><div align=\"left\">
</div></td>';
$i=$i+1;	
}
echo "
<td ><div align=\"right\">";

echo "</div></td></tr>";
echo '</table>';*/

?>
</table>
<b><div align = "right">Total: P<?php echo sprintf("%.2f",$total1);?> </div><br>

<div align = "left"><input type = "submit" name = "submit" value = "Apply Deductions" />
<input type = "submit" name = "submit" value = "Print Billing Statement" /></div><p>
</form>

<a href="admin homepage.php">

	<button type="button">Go back to Admin Dashboard</button>

</a>

 <script src="jquery-3.1.1.min.js"></script>
    <script src="DataTables/datatables.min.js">
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#datatable').dataTable();
        });
   </script>

</body>
</html>
