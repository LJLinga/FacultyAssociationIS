
<a href = "admin.php"> Back to Admin </a> 
<?php
require_once('../mysql_connect_FA.php');
// loop through all pending approvals
// get the number of rows in the approvals, then go for a while loop
// for the  Member ID and Person who requested it 

session_Start();


if (isset($_POST['pickup'])) {
	
	
	if($_POST['pickup'] == "Ready for Pickup"){
		
			if(isset($_POST['loan_id'])){   // LOAN CLAIM
		
			$query="UPDATE member_loans SET PICKUP_STATUS = 3 WHERE '".$_POST['loan_id']."' = RECORD_ID && APP_STATUS = 2 && PICKUP_STATUS = 2";
			
			mysqli_query($dbc,$query);
		
			// insert into the transaction table, get the member id through loan id

			$query2 = "SELECT M.MEMBER_ID from member_loans AS M WHERE M.RECORD_ID = {$_POST['loan_id']}";
			
			$result= mysqli_query($dbc,$query2);
			
			$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
			
			$query = "INSERT into transactions(member_id,amount,txn_date,txn_type,txn_status,emp_id_involved) values('".$row['MEMBER_ID']."',0,DATE(NOW()),1,'Ready For Pickup','".$_SESSION['idnum']."');";
				
			mysqli_query($dbc,$query);

		}else if(isset($_POST['health_id'])){  //HEALTH AID CLAIM
			

			$query="UPDATE health_claim SET PICK_UP_STATUS = 3 WHERE '".$_POST["health_id"]."' = CLAIM_ID && APP_STATUS = 2 && PICK_UP_STATUS = 2";
			
			mysqli_query($dbc,$query);
			
			// insert in the transaction table get the member id through the health aid id
			
			$query2 = "SELECT MEMBER_ID from health_claim WHERE '".$_POST["health_id"]."' = CLAIM_ID";
			
			$result= mysqli_query($dbc,$query2);
			
			$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
			
			$query = "INSERT into transactions(member_id,amount,txn_date,txn_type,txn_status,emp_id_involved) values('".$row['MEMBER_ID']."',0,DATE(NOW()),1,'Ready For Pickup','".$_SESSION['idnum']."');";
				
			mysqli_query($dbc,$query);
			
		}
	}else if($_POST['pickup'] == "Picked Up"){
		
		if(isset($_POST['loan_id'])){   // LOAN CLAIM
	
			$query="UPDATE member_loans SET PICKUP_STATUS = 4 WHERE '".$_POST['loan_id']."' = RECORD_ID && APP_STATUS = 2 && PICKUP_STATUS = 3";
			
			mysqli_query($dbc,$query);
		
			// insert into the transaction table, get the member id through loan id

			$query2 = "SELECT MEMBER_ID from member_loans WHERE '".$_POST["loan_id"]."' = RECORD_ID";
			
			$result= mysqli_query($dbc,$query2);
			
			$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
			
			$query = "INSERT into transactions(member_id,amount,txn_date,txn_type,txn_status,emp_id_involved) values('".$row['MEMBER_ID']."',0,DATE(NOW()),1,'Picked up','".$_SESSION['idnum']."');";
				
			mysqli_query($dbc,$query);

		}else if(isset($_POST['health_id'])){  //HEALTH AID CLAIM
			
			$query="UPDATE health_claim SET PICK_UP_STATUS = 4 WHERE '".$_POST["health_id"]."' = CLAIM_ID && APP_STATUS = 2 && PICK_UP_STATUS = 3";
			
			mysqli_query($dbc,$query);
			
			// insert in the transaction table get the member id through the health aid id
			
			$query2 = "SELECT MEMBER_ID from health_claim WHERE '".$_POST['health_id']."' = CLAIM_ID";
			
			$result= mysqli_query($dbc,$query2);
			
			$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
			
			$query = "INSERT into transactions(member_id,amount,txn_date,txn_type,txn_status,emp_id_involved) values('".$row['MEMBER_ID']."',0,DATE(NOW()),1,'Picked up','".$_SESSION['idnum']."');";
				
			mysqli_query($dbc,$query);
		
		}
	}
	
}




$query="select m.MEMBER_ID, m.FIRSTNAME, m.LASTNAME, l.RECORD_ID, l.PICKUP_STATUS as 'LOAN_PICKUP_STATUS', l.APP_STATUS
from member m join member_loans l on m.member_id = l.member_id
where l.APP_STATUS = 2 && PICKUP_STATUS != 1
order by l.LOAN_ID asc";
$result=mysqli_query($dbc,$query);

// for the document ID and File Location  select the document ID and Document location using the claim ID 


echo '<form action="confirmApproval.php" method="post">';
echo '<h3> Loan Claim Pickups </h3>';
echo '<table width="75%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
<tr>
<td width="10%"><div align="center"><b>
</div></b></td>
<td width="10%"><div align="center"><b> MEMBER ID
</div></b></td>
<td width="50%"><div align="center"><b> MEMBER NAME 
</div></b></td>
<td width="10%"><div align="center"><b> APP_STATUS
</div></b></td>
<td width="10%"><div align="center"><b> RECORD ID
</div></b></td>
<td width="10%"><div align="center"><b>  PICKUP STATUS
</div></b></td>
</tr>';

while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
echo '
<tr>
<td width=\"5%\"><div align=\"center\"> <input type="radio" name="loan_id" value="';echo $row['RECORD_ID'].'">
</div></td>
<td width=\"10%\"><div align=\"center\">';echo $row['MEMBER_ID'].
'</div></td>
<td width=\"50%\"><div align=\"center\">';echo $row['FIRSTNAME'].$row['LASTNAME'].
'</div></td>
<td width=\"10%\"><div align=\"center\">';echo $row['APP_STATUS'].
'</div></td>
<td width=\"10%\"><div align=\"center\">';echo $row['RECORD_ID'].
'</div></td>
<td width=\"10%\"><div align=\"center\">';echo $row['LOAN_PICKUP_STATUS'].
'</div></td>
</tr>';
}

$query="select m.MEMBER_ID, m.FIRSTNAME, m.LASTNAME, h.CLAIM_ID, h.APP_STATUS, h.PICK_UP_STATUS
from member m join health_claim h on m.member_id = h.member_id
where h.APP_STATUS = 2 && PICK_UP_STATUS = 2 OR PICK_UP_STATUS = 3
order by h.CLAIM_ID asc";
$result=mysqli_query($dbc,$query);

echo '<table width="75%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
<tr>
<td width="10%"><div align="center"><b>
</div></b></td>
<td width="10%"><div align="center"><b> MEMBER ID
</div></b></td>
<td width="50%"><div align="center"><b> MEMBER NAME 
</div></b></td>
<td width="10%"><div align="center"><b> APP_STATUS
</div></b></td>
<td width="10%"><div align="center"><b> CLAIM_ID
</div></b></td>
<td width="10%"><div align="center"><b> PICKUP_STATUS
</div></b></td>
</tr>';
echo '<h3> Health Claim Pickups </h3>';

while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
echo '
<tr>
<td width=\"5%\"><div align=\"center\"> <input type="radio" name="health_id" value="';echo $row['CLAIM_ID'].'">
</div></td>
<td width=\"10%\"><div align=\"center\">';echo $row['MEMBER_ID'].
'</div></td>
<td width=\"50%\"><div align=\"center\">';echo $row['FIRSTNAME'].$row['LASTNAME'].
'</div></td>
<td width=\"10%\"><div align=\"center\">';echo $row['APP_STATUS'].
'</div></td>
<td width=\"10%\"><div align=\"center\">';echo $row['CLAIM_ID'].
'</div></td>
<td width=\"10%\"><div align=\"center\">';echo $row['PICK_UP_STATUS'].
'</div></td>
</tr>';

}

echo '</table>';
echo '<input type="submit" name="pickup" value= "Ready for Pickup" /> '; 
echo '<input type="submit" name="pickup" value= "Picked Up" /> '; 
echo '</form>';






?>




