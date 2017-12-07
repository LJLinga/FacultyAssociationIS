<a href = "member.php"> Back </a>
<?php
require_once('../mysql_connect_FA.php');

session_start();


//gets the eligibility of the health aid of the user

$query="SELECT HEALTH_AID_STATUS from member WHERE MEMBER_ID = {$_SESSION['idnum']}";

$result=mysqli_query($dbc,$query);

$row=mysqli_fetch_array($result,MYSQLI_ASSOC);

//gets the latest application of the user in the database

$query2="select APP_STATUS, PICKUP_STATUS from HEALTH_CLAIM WHERE MEMBER_ID ='".$_SESSION['idnum']."' ORDER BY CLAIM_ID DESC LIMIT 1";

$result2=mysqli_query($dbc,$query2);

$row2=mysqli_fetch_array($result2,MYSQLI_ASSOC);





// Normalize the file path of the file
	function normalizePath($path)
	{
    $parts = array();// Array to build a new path from the good parts
    $path = str_replace('\\', '/', $path);// Replace backslashes with forwardslashes
    $path = preg_replace('/\/+/', '/', $path);// Combine multiple slashes into a single slash
    $segments = explode('/', $path);// Collect path segments
    $test = '';// Initialize testing variable
    foreach($segments as $segment)
    {
        if($segment != '.')
        {
            $test = array_pop($parts);
            if(is_null($test))
                $parts[] = $segment;
            else if($segment == '..')
            {
                if($test == '..')
                    $parts[] = $test;

                if($test == '..' || $test == '')
                    $parts[] = $segment;
            }
            else
            {
                $parts[] = $test;
                $parts[] = $segment;
            }
        }
    }
    return implode('/', $parts);
	}


//checks if the member is eligible for health aid
	if($row['HEALTH_AID_STATUS'] == 2 ){
	
		echo " <h3> you are eligible for  Health Aid! </h3> ";
		
		if($row2['APP_STATUS'] == 1){ // if the application is pending
		
			$_SESSION['message'] = 'Your application is still pending. Please wait until it is Approved or Rejected. ';
			
			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/member homepage.php");
			
		}else if($row2['APP_STATUS'] == 2 && $row2['PICKUP_STATUS'] == 2){ // if app is approved, but isnt released yet
		
			$_SESSION['message'] = 'Your application has been approved, and it is undergoing processing. Please wait until further notice. ';

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/member homepage.php");
			
		}else if($row2['APP_STATUS'] == 2 && $row2['PICKUP_STATUS'] == 3){ // if app is approved and ready for pickup
			
			$_SESSION['message'] = 'Your application has been approved, and is now ready for pickup. Please proceed to the office. ';

			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/member homepage.php");
			
		}
		
	}	else{
	
		echo $row['HEALTH_AID_STATUS'];
		
	}
		
		
	if(isset($_FILES["file"]["name"])){
			
		$name = $_FILES["file"]["name"];
			
		$tmp_name = $_FILES['file']['tmp_name'];
			
		if(!empty($name)){ 
				
			$location = 'docs/';
			
			if(move_uploaded_file($tmp_name,$location.$name)){ // If file move is sucessful, executes the data to be put in the data base
			
			$location.=$name;
			
			echo $location;
			
			$realest = realpath($location);
			
			echo $realest;
			
			$realest = normalizePath($realest);
			
			echo $realest;
			
			// inserting in health claim
			
			$query= "INSERT into health_claim(member_id, app_status, date_applied,pick_up_status) values({$_SESSION['idnum']},'1',DATE(NOW()),1);";
						
			mysqli_query($dbc,$query);
						
			// select the claim id to put into the claim docs
						
			$query3= " SELECT CLAIM_ID from health_claim WHERE '".$_SESSION['idnum']."' ORDER BY CLAIM_ID DESC LIMIT 1";
						
			$result=mysqli_query($dbc,$query3);
						
			$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
						
			// inserting to claim docs
			
			
			$query = "INSERT into claim_documents(CLAIM_ID, DOC_ADDRESS) values ( {$row['CLAIM_ID']} ,'{$realest}');";
			
			mysqli_query($dbc,$query);
			
						
			// inserting to the transactions table
			$query = "INSERT into transactions(member_id,amount,txn_date,txn_type,txn_status) values('".$_SESSION['idnum']."',0,DATE(NOW()),1,'Pending');";
			
			mysqli_query($dbc,$query);
			
			
			
				
			//redirects back to member dashboard and informs that they have sucessfully uploaded file
				
			$_SESSION['message'] = 'FIle sucessfully uploaded';
			
			header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/member homepage.php");

			}else{
						
				echo 'failed to upload :(';
						
			}
	
		}else{
				
			echo ' Please choose a file ';
				
		}
			
	}

?>

<form action="applyHAid.php" method="POST" enctype="multipart/form-data">
	<input type = "file" name="file"><br> <br>
	<input type = "submit" value = "Submit">
</form>

