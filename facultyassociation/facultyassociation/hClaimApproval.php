
<a href = "admin.php"> Back to Admin </a> 
<?php
require_once('../mysql_connect_FA.php');
// loop through all pending approvals
// get the number of rows in the approvals, then go for a while loop
// for the  Member ID and Person who requested it 

session_start();


if (isset($_POST['submit'])) {
	
	
	if($_POST['submit'] == "Approve"){
		
		if(isset($_POST['selected'])){
	
		$query="UPDATE health_claim SET APP_STATUS = 2, PICK_UP_STATUS = 2 WHERE '".$_POST["selected"]."' = CLAIM_ID && APP_STATUS = 1";
		
		mysqli_query($dbc,$query);

		$query="UPDATE health_claim SET EMP_ID_APPROVED = '".$_SESSION['idnum']."', DATE_APPROVED = DATE(NOW()) WHERE '".$_POST["selected"]."' = CLAIM_ID";
		
		mysqli_query($dbc,$query);
		
		//insert into the transaction table
		
		//gets member id of the loan id
		
		$query2 = "SELECT MEMBER_ID from health_claim WHERE '".$_POST["selected"]."' = CLAIM_ID";
		
		$result= mysqli_query($dbc,$query2);
		
		$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
		
		$query = "INSERT into transactions(member_id,amount,txn_date,txn_type,txn_status,emp_id_involved) values('".$row['MEMBER_ID']."',0,DATE(NOW()),1,'Approved','".$_SESSION['idnum']."');";
			
		mysqli_query($dbc,$query);
	 
		}
	}else if($_POST['submit'] == "Reject"){
		
		$query="UPDATE health_claim SET APP_STATUS = 3 WHERE '".$_POST["selected"]."' = CLAIM_ID)";
		
		mysqli_query($dbc,$query);

		$query="UPDATE health_claim SET EMP_ID_APPROVED = '".$_SESSION["username"]."', DATE_APPROVED = DATE(NOW()) WHERE '".$_POST['selected']."' = CLAIM_ID";
		
		mysqli_query($dbc,$query);
		
		//insert into the transaction table
		
		//gets member id of the claim id
		$query2 = "SELECT MEMBER_ID from health_claim WHERE '".$_POST["selected"]."' = CLAIM_ID";
		
		$result= mysqli_query($dbc,$query2);
		
		$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
		
		// inserts it into the transaction table
		
		$query = "INSERT into transactions(member_id,amount,txn_date,txn_type,txn_status,emp_id_involved) values('".$row['MEMBER_ID']."',0,DATE(NOW()),1,'Rejected','".$_SESSION["username"]."');";
			
		mysqli_query($dbc,$query);
		
		
	}
	
}




$query="select m.MEMBER_ID, m.FIRSTNAME, m.LASTNAME, h.CLAIM_ID, h.APP_STATUS, c.DOCUMENT_ID, c.DOC_ADDRESS
from member m join health_claim h on m.member_id = h.member_id
join claim_documents c on h.claim_id = c.claim_id
where h.APP_STATUS = 1
order by c.DOCUMENT_ID asc";

$result=mysqli_query($dbc,$query);

// for the document ID and File Location  select the document ID and Document location using the claim ID 


echo '<form action="hClaimApproval.php" method="post">';
echo '<table width="75%" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
<tr>
<td width="10%"><div align="center"><b>
</div></b></td>
<td width="10%"><div align="center"><b> MEMBER ID
</div></b></td>
<td width="50%"><div align="center"><b> MEMBER NAME 
</div></b></td>
<td width="10%"><div align="center"><b> CLAIM ID
</div></b></td>
<td width="10%"><div align="center"><b> DOCUMENT ID 
</div></b></td>
<td width="10%"><div align="center"><b>  DOCUMENT PATH
</div></b></td>
</tr>';
while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
echo '
<tr>
<td width=\"5%\"><div align=\"center\"> <input type="radio" name="selected" value="';echo $row['CLAIM_ID'].'">
</div></td>
<td width=\"10%\"><div align=\"center\">';echo $row['MEMBER_ID'].
'</div></td>
<td width=\"50%\"><div align=\"center\">';echo $row['FIRSTNAME'].$row['LASTNAME'].
'</div></td>
<td width=\"10%\"><div align=\"center\">';echo $row['CLAIM_ID'].
'</div></td>
<td width=\"10%\"><div align=\"center\">';echo $row['DOCUMENT_ID'].
'</div></td>
<td width=\"10%\"><div align=\"center\">';echo "<a href='download.php?nama=".$row['DOC_ADDRESS']."'>Download</a> "; echo '
</div></td>
</tr>';


}






echo '</table>';
echo '<input type="submit" name="submit" value="Approve" /> '; 
echo '<input type="submit" name="submit" value="Reject" /> '; 
echo '</form>';






?>




