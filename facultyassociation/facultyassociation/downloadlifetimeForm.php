<?php 
session_start();
require_once 'dompdf/autoload.inc.php';
// reference the Dompdf namespace
use Dompdf\Dompdf;
// instantiate and use the dompdf class
$dompdf = new Dompdf();

$member_id = $_SESSION['idnum'];
$member_name = 	$_SESSION['member_name'];
if($_SESSION['type']=='b'){
$primary = 	$_SESSION['primary'];
$secondary = 	$_SESSION['secondary'];

$datetime = date('Y/m/d');
unset($_SESSION["type"]);
unset($_SESSION["primary"]);
unset($_SESSION["secondary"]);
$dompdf->loadHtml('<html>
<body>

Current Date and Time: '.$datetime.'<br>
User: ' .$member_id. '<br> 
Name: '.$member_name.'<br>


Primary Beneficiary: '.$primary.'<br>
Secondary Beneficiary: '.$secondary.'<br>


</body>
</html>');
}
else{
	
	$org = $_SESSION['primary'];
	$add= $_SESSION['secondary'];
$dompdf->loadHtml('<html>
<body>


User: ' .$member_id. '<br>
Name:'.$member_name.'<br>


Organization of Choice: '.$org.'<br>


</body>
</html>');
}
// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream('Lifetime');


?>