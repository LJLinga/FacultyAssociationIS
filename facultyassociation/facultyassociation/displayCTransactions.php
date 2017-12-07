<html>

<header>

<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>

</header>

<style>

 	table, th, td{border: 1px solid black; text-align: center;}

</style>

<body>
<?php
require_once('../mysql_connect_FA.php');

session_start();

if ($_SESSION['usertype'] != 2) {

header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
			
}

// Select * from transactions where username = member_id


$query="select * from transactions where '".$_SESSION["idnum"]."' = MEMBER_ID";
$result=mysqli_query($dbc,$query);

// for the document ID and File Location  select the document ID and Document location using the claim ID 

echo '<table id="table" width="100%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
<thead>
<tr>

<th> Amount </th>

<th> Transaction Date </th>

<th> Transaction Type </th>

<th> Transaction Description </th>

<th>  Employee Id Involved </th>

</tr>
</thead>

<tbody>';

while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
	$transtype ='';
	if($row['TXN_TYPE'] == 1){
		$transtype ='Application';
	}else{
		$transtype = 'Deduction';
	}

echo "

<tr>
<td> {$row['AMOUNT']}</td>
<td> {$row['TXN_DATE']}</td>
<td> {$transtype}</td>
<td> {$row['TXN_STATUS']}</td>
<td> {$row['EMP_ID_INVOLVED']}</td>
</tr> 
";


}

echo "</tbody></table>"

?>

<a href = "member homepage.php"> <button type="button">Back to Member</button> </a> 

</body>

<script type="text/javascript" src="jquery-3.1.1.min.js"></script>
		<script type="text/javascript" src="DataTables/datatables.min.js"></script>
		<script>

			$(document).ready(function(){
    
    			$('#table').DataTable();

			});

</script>

</html>




