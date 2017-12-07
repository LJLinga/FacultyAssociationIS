<?php session_start();
	

require_once('../mysql_connect_FA.php');

IF(isset($_POST['submit'])){
	echo $_SESSION['type'];
IF($_SESSION['type'] == 'o'){
	$query="insert into death_beneficiaries(MEMBER_ID,ORGANIZATION,APP_STATUS_ID,DATE_ADDED)VALUES({$_SESSION['idnum']},'{$_SESSION['primary']}',0,DATE(NOW()))";
	$result=mysqli_query($dbc,$query);
	$query = "Insert into transactions(MEMBER_ID,AMOUNT,TXN_DATE,TXN_TYPE,TXN_STATUS) values ({$_SESSION['idnum']},0,date(now()),1,'Lifetime Application Submitted')";
	mysqli_query($dbc,$query);
	header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/downloadlifetimeForm.php");
}
else {
	$query="insert into death_beneficiaries(MEMBER_ID,PRIMARY_B,SECONDARY,APP_STATUS_ID,DATE_ADDED)VALUES({$_SESSION['idnum']},'{$_SESSION['primary']}','{$_SESSION['secondary']}',0,DATE(NOW()))";
	$result=mysqli_query($dbc,$query);
	$query = "Insert into transactions(MEMBER_ID,AMOUNT,TXN_DATE,TXN_TYPE,TXN_STATUS) values ({$_SESSION['idnum']},0,date(now()),1,'Lifetime Application Submitted')";
	mysqli_query($dbc,$query);
	header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/downloadlifetimeForm.php");
}


}


?>

<html>
<body>


User: <?php echo $_SESSION['idnum'];?> of <?php echo $_SESSION['member_name'];?>
<br>
<?php if($_SESSION['type']=='b'){?>
Primary Beneficiary: 
	<?php echo $_SESSION['primary'];?>
	<br>
Secondary Beneficiary: 
	<?php echo $_SESSION['secondary'];?>
	<br>
<?php };?>	

<?php if($_SESSION['type']=='o'){?>
Organization of Choice: 
	<?php echo $_SESSION['primary'];?>
	<br>

<?php };?>

<form action = "<?php echo $_SERVER['PHP_SELF']; ?>" method = "post">
<input type = "submit" value = "download" name = "submit">
</form>

</body>
</html>