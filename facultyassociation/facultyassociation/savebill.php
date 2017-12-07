
<html>
<head>

<style type="text/css">
    .break { page-break-before: always; }
</style>

</head>
<body>


<?php session_start();

require_once('../mysql_connect_FA.php');
$flag=0;
$query="select m.LASTNAME AS 'LAST',m.FIRSTNAME AS 'FIRST',m.MIDDLENAME AS 'MIDDLE',SUM(nl.PER_PAYMENT_DEDUCTION) as 'Loan Deduction',f.PER_PAYMENT_DEDUCTION as 'FALP',m.HEALTH_AID_STATUS as 'Status',p.PER_PAYMENT_DEDUCTION as 'Products'
		from MEMBER m 
		left join (SELECT l.PER_PAYMENT_DEDUCTION,ml.LOAN_ID,ml.MEMBER_ID FROM MEMBER_LOANS ml JOIN loan_list l on l.LOAN_ID= ml.LOAN_ID WHERE l.LOAN_ID NOT LIKE '1%' AND APP_STATUS=2 AND LOAN_STATUS = 2) nl
		on m.member_id = nl.MEMBER_ID
		left join loan_list l
		on nl.loan_id = l.loan_id
		left join (SELECT l.PER_PAYMENT_DEDUCTION,ml.LOAN_ID,ml.MEMBER_ID FROM MEMBER_LOANS ml JOIN loan_list l on l.LOAN_ID= ml.LOAN_ID  WHERE l.LOAN_ID LIKE '1%' AND APP_STATUS = 2 and LOAN_STATUS = 2) f
		on f.MEMBER_ID = m.MEMBER_ID
        left join (SELECT p.PER_PAYMENT_DEDUCTION,o.MEMBER_ID FROM orders o JOIN orderdetails od on o.order_id= od.ORDER_ID join product_list p on p.PRODUCT_ID = od.PRODUCT_ID  WHERE od.DEDUCTION_STATUS = 2) p
        on p.member_ID = m.MEMBER_ID
		group by m.MEMBER_ID
		order by m.LASTNAME asc";
	
$result1=mysqli_query($dbc,$query);
	


	




if (isset($_POST['submit'])){
			if($_POST['submit'] == 'save')
			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/downloadBill.php");	
			
				
				
	
}
$result=mysqli_query($dbc,$query);

?>

<form id = "form1" runat = "server" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">


 
 <table border = "1">
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



echo " <tr>
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

<div align = "right">
<input type = "submit" name = "submit" value = "save" /></div>
</form>
</body>
</html>
