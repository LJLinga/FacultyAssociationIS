
<html>
<body>




<?php
session_start();

require_once('../mysql_connect_FA.php');

if ($_SESSION['usertype'] != 2) {

	header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
			
}

$queryLifetime = "SELECT M.MEMBER_ID FROM MEMBER AS M WHERE M.MEMBER_ID = {$_SESSION['idnum']} AND LIFETIME_APP_STATUS <=> NULL AND ELIGIBLE_LIFETIME = '1'";

$resultLifetime = mysqli_query($dbc, $queryLifetime);
$rowLifetime = mysqli_fetch_array($resultLifetime);

if (empty($rowLifetime)) {

	header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/member not_lifetime.php");

}

if (isset($_POST['submit'])){

	$message=NULL;

	 

	 $primary = null;

		if(isset($_POST['type'])){
			if($_POST['type']=="b"){
				if(!empty($_POST['name1'])){

						$primary = $_POST['name1'];
				}
				else{
					$message.='You forgot to enter your primary!';
				}

				if(!empty($_POST['name2'])){
					$secondary = $_POST['name2'];
				}
				else{
					$secondary = 'none';
				
				}
				if($secondary == $primary)
					$message.='You entered the same person!';
				if(!isset($message)){
						$_SESSION['primary'] = $primary;
						$_SESSION['secondary'] = $secondary;
						$_SESSION['type'] = 'b';
						header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/complete.php");
				}
				else
					echo '<font color="red">'.$message. '</font>';
			}
			else{
				if(!empty($_POST['name3'])){
					    if (preg_match('/[^A-Za-z.]/', $_POST['name3']))
						$primary = $_POST['name3'];
					else
						$message.='You entered invalid character in org!';
					
				}
				else{
					$message.='You forgot to enter your ORG!';
				}
				
				
				if(!isset($message)){
						$_SESSION['primary'] = $primary;
						$_SESSION['secondary'] = $secondary;
						
						$_SESSION['type'] = 'o';
						header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/complete.php");
				}
				else
					echo '<font color="red">'.$message. '</font>';
								
				}
			}
			else
				echo '<font color="red">'.'You have not chosen a choice'. '</font>';
		}
	

?>

<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset>

Choose which to use:<br>
<input type="radio" name="type" value = "b" id="type1" onclick="toggleTextbox(this);"  />Beneficiary<br>
<input type="radio" name="type" value = "o" id="type2" onclick="toggleTextbox(this);"  />Organization<br>


Primary Beneficiary:<input type="text" name="name1" id="name1" disabled  /><br>
Secondary Beneficiary:<input type="text" name="name2"  id="name2" disabled /><br>
Organization:<input type="text" name="name3"  id="name3" disabled />

			
<script type="text/javascript">
function toggleTextbox(rdo) {
	
	if(rdo.id == 'type1'){
    document.getElementById("name1").disabled = false;
	document.getElementById("name2").disabled = false;
	document.getElementById("name3").disabled = true;
	
	document.getElementById("type2").checked = false;
	}
	else{
	 document.getElementById("name1").disabled = true;
		document.getElementById("name2").disabled = true;
		document.getElementById("name3").disabled = false;
		document.getElementById("type1").checked = false;
	}
}


</script>
<input type = "submit" name = "submit" value = "submit"/>
</form>
</body>
</html>
