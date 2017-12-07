<?php 
session_start();
require_once 'dompdf/autoload.inc.php';
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
		group by m.MEMBER_ID";
	
$result=mysqli_query($dbc,$query);
// reference the Dompdf namespace
use Dompdf\Dompdf;
// instantiate and use the dompdf class

 //be sure this file exists, and works outside of web context etc.)


$dompdf = new Dompdf();


$html = '
<html>
<head>


</head>
<body>

<table border="1">

<tr>
<td><b>MEMBER
</b></td>
<td><b>MEMBERSHIP FEE
</b></td>
<td><b>FALP
</b></td>
<td><b>BANK
</b></td>

<td><b>HEALTH AID BENEFIT
</b></td>
<td><b>PRODUCTS ORDERED
</b></td>

<td><div align="center"><b>TOTAL
</div></b></td>

</tr>';
$total1 = 0.0;

while($row=mysqli_fetch_assoc($result)){
$last = $row['LAST'];
$first = $row['FIRST'];
$middle = $row['MIDDLE'];

$html .= "<tr>
<td ><div align=\"left\">" .$last.  $first . $middle ."
</div></td>
<td ><div align=\"right\">
100.00
</div></td>
<td ><div align=\"right\">";
if(!empty($row['Loan Deduction']))
	$html.= $row['Loan Deduction'];
$html.="</div></td>
<td ><div align=\"right\">";
if(!empty($row['FALP'])){
$falp =	$row['FALP'];
 $html.= $falp;
}
 $html.= "</div></td>
<td ><div align=\"right\">";
if($row['Status']==2)
	$html.= '100.00';
$html.= "</div></td>
";
$html.= "<td ><div align=\"right\">";
if(!empty($row['Products']))
	$html.= $row['Products'];


$html.= "</div></td>";
$html.= "
<td ><div align=\"right\">";
$total= 0.00;	
$total = 100.00+(float)$row['Loan Deduction']+(float)$row['FALP']+(float)$row['Products'];
if($row['Status'] == 2){
	$total = $total + 100.00;
}
$total1 = $total1 + $total;
$total = sprintf("%.2f",$total);
$html.= $total;

$html.= "</div></td></tr>";


}



$html.= '</table>';
$html.= sprintf("%.2f",$total1).'
</body>
</html>';

$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream('Bill');


?>