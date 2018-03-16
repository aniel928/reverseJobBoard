<?php

//call this function to log a user in - need to add hash for password
function login($username,$password,$type){
	//start session
	session_start();
	//connect to db
	$connection = mysql_connect("localhost", "root", "");
	//takes input values and makes them safe
	$username = stripslashes($username);
	$password = stripslashes($password);
	$type = stripslashes($type);
	$username = mysql_real_escape_string($username);
	$password = mysql_real_escape_string($password);
	$type = mysql_real_escape_string($type);
	
	//choose db
	$db = mysql_select_db("mydb", $connection);
	//set up and execute query, then put into array, then check for # of rows
	$query = mysql_query("select * from `user` where password='$password' AND email='$username' AND accountType='$type'", $connection);
	$rows = mysql_num_rows($query);
	
	//assign all session variables if valid login
	if ($rows == 1) {
		$row = mysql_fetch_assoc($query);

		$_SESSION['user_id']=$row['userID'];
		$_SESSION['fname'] = $row['fname'];
		$_SESSION['lname'] = $row['lname'];
		$_SESSION['email'] = $row['email'];
		$_SESSION['paid'] = $row['paidAccount'];
		$_SESSION['accountType'] = $row['accountType'];
		$_SESSION['phone'] = $row['phone'];
		$_SESSION['login_user']= $row['userID']; //Initializes session		
		$_SESSION['resumeLink'] = $row['resumeLink'];
			
		if($_SESSION['resumeLink'] == "") {
			uploadResume("No Resume Yet.",$_SESSION['user_id']);		
			$_SESSION['resumeLink'] = 'No Resume Yet';
		
		}
		
		$user_check=$_SESSION['login_user'];
		$ses_sql=mysql_query("select email from `user` where email='$user_check'", $connection);
		$row = mysql_fetch_assoc($ses_sql);
		$login_session =$row['user_id'];

		if(!isset($login_session)){
		mysql_close($connection); // Closing Connection
		header('Location: index.php?status=failed'); // Redirecting To Home Page
		}//end if(!isset($login_session)
		mysql_close($connection); // Closing Connection
		header("location: dashboard.php"); // Redirecting To Other Page
	}//end if ($rows == 1)
 	else {//if rows !=1
	 	mysql_close($connection); // Closing Connection
		header("location: index.php?status=failed");
	}//end else
}//end function


//call this function to create a new account
function createAccount($fname,$lname,$phone,$email,$pass,$type){
	//connect and select db
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	//make sure account doesn't already exist
	$query = mysql_query("select * from `user` where password='$pass' AND email='$email' AND accountType='$type'",$connection);
	$rows = mysql_num_rows($query);
	if($rows >= 1){ //if email, password and type are the same, send back
		mysql_close($connection);
		header("location:register.php?status=failed");
		}//end if($rows >=1)
	else{//if it does not exist, check email and type (in case pw is wrong)
		$query2 = mysql_query("select * from `user` where email='$email' AND accountType='$type'",$connection);
		$rows2 = mysql_num_rows($query2);
	
		if($rows2>=1){	//try again
			header("location:register.php?status=failed");
		}//end if($rows2>=1)
		else{	//insert new user
		$query = "INSERT INTO `user` (`userID`, `fname`, `lname`, `email`, `phone`, `password`, `accountType`, `resumeLink`, `paidAccount`) VALUES (NULL, '$fname', '$lname', '$email', '$phone', '$pass', '$type', NULL, NULL)";
		mysql_query($query);
		mysql_close($connection);
		login($email,$pass,$type);	
		header("location: dashboard.php");
		}//end second else
	}//end first else
}

//preloads an array of skills for autocomplete JQuery
function loadSkills(){
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	//load skills
	$query = mysql_query("select * from `skills`",$connection);
	while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
    $skills[] =  $row['skills'];  
	}
	mysql_close($connection);
	return $skills;
	
}

//preloads an array of programs for autocomplete JQuery
function loadPrograms(){
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	//load programs
	$query = mysql_query("select * from `programs`",$connection);
	while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
    $programs[] =  $row['program'];  
	}
	mysql_close($connection);
	return $programs;
	
}

//preloads an array of titles for autocomplete JQuery
function loadJobTitles(){
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	//load titles
	$query = mysql_query("select * from `jobTitle`",$connection);
	while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
    $jobTitles[] =  $row['jobTitle'];  
	}
	mysql_close($connection);
	return $jobTitles;
	
}

//preloads an array of employers for autocomplete JQuery
function loadEmployers(){
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	//load employers
	$query = mysql_query("select * from `workplace`",$connection);
	while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
    $workplaces[] =  $row['workplace'];  
	}
	mysql_close($connection);
	return $workplaces;
	
}

//to delete account if user wishes to delete - deletes foreign key values first, then user
function deleteAccount($userID){
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	mysql_query("SET FOREIGN_KEY_CHECKS=0",$connection);
	mysql_query("DELETE FROM `UserJobTitles` WHERE `User_UserID`='$userID'",$connection);
	mysql_query("DELETE FROM `UserPrograms` WHERE `User_UserID`='$userID'",$connection);
	mysql_query("DELETE FROM `UserSkills` WHERE `User_UserID`='$userID'",$connection);
	mysql_query("DELETE FROM `UserWorkplace` WHERE `User_UserID`='$userID'",$connection);
	mysql_query("DELETE FROM `user` WHERE `userID`='$userID'",$connection);
	header("location: index.php?status=deleted");
}

//add skill to user profile
function addSkill($skill,$userID){
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	//load skills to skill table
	$query = mysql_query("select * from `skills` where `skills`='$skill'",$connection);
	$rows = mysql_num_rows($query);
	if($rows == 0){
		$query = "INSERT INTO `skills` (`skills`) VALUES ('$skill');";
		$result = mysql_query($query);
		if(!$result){
			echo "Failed";
		}
		
	}
	//add skills to userSkill table
	mysql_query("SET FOREIGN_KEY_CHECKS=0",$connection);
	$query = mysql_query("select * from `userSkills` where `skills_skills`='$skill' AND `User_UserID`='$userID'", $connection);
	$rows = mysql_num_rows($query);
	if($rows > 0){
	return "Error. Skill already exists for user.";
	}
	else{
		$query = "INSERT INTO `userSkills` (`skills_skills`,`User_UserID`) VALUES ('$skill','$userID')";
		mysql_query($query);
		return "Added succesfully. </br>";
	}
}

//delete skill from user profile
function deleteSkill($userID,$skill){
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	mysql_query("SET FOREIGN_KEY_CHECKS=0",$connection);
	$query=mysql_query("DELETE FROM `userSkills` WHERE `user_userID`='$userID' AND `skills_skills`='$skill'",$connection);

}

//add program to user profile
function addProgram($program,$userID){
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	//load programs to program table
	$query = mysql_query("select * from `programs` where `program`='$program'",$connection);
	$rows = mysql_num_rows($query);
	if($rows == 0){
		$query = "INSERT INTO `programs` (`program`) VALUES ('$program')";
		mysql_query($query);
	}
	//load programs to userProgram table
	mysql_query("SET FOREIGN_KEY_CHECKS=0",$connection);
	$query = mysql_query("select * from `userPrograms` where `programs_program`='$program' AND `user_userID`='$userID'", $connection);
	$rows = mysql_num_rows($query);
	if($rows > 0){
		return "Error. Program already exists for user.";
	}
	else{
		$query = "INSERT INTO `userPrograms` (`programs_program`,`user_userID`) VALUES ('$program','$userID')";
		mysql_query($query);
		return "Added succesfully.";
	}
}
//delete program from user profile
function deleteProgram($userID,$program){
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	mysql_query("SET FOREIGN_KEY_CHECKS=0",$connection);
	$query=mysql_query("DELETE FROM `userPrograms` WHERE `user_userID`='$userID' AND `programs_program`='$program'",$connection);
	}

//add employer to user profile
function addEmployer($employer,$userID){
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	//load employers to employer table
	$query = mysql_query("select * from `workplace` where `workplace`='$employer'",$connection);
	$rows = mysql_num_rows($query);
	if($rows == 0){
		$query = "INSERT INTO `workplace` (`workplace`) VALUES ('$employer')";
		mysql_query($query);
	}
	//load employers to userEmployer table
	mysql_query("SET FOREIGN_KEY_CHECKS=0",$connection);
	$query = mysql_query("select * from `userWorkplace` where `workplace_workplace`='$employer' AND `user_userID`='$userID'", $connection);
	$rows = mysql_num_rows($query);
	
	if($rows > 0){
	return "Error. Employer already exists for user.";
	}
	else{
		$query = "INSERT INTO `userWorkplace` (`workplace_workplace`,`user_userID`) VALUES ('$employer','$userID')";
		mysql_query($query);
		return "Added succesfully.";
		}
}

//delete employer from user profile
function deleteEmployer($userID,$employer){
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	mysql_query("SET FOREIGN_KEY_CHECKS=0",$connection);
	$query=mysql_query("DELETE FROM `userWorkplace` WHERE `user_userID`='$userID' AND `workplace_workplace`='$employer'",$connection);
}

//add job title to user profile
function addTitle($title, $userID){
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	//load titles to title table
	$query = mysql_query("select * from `jobTitle` where `jobTitle`='$title'",$connection);
	$rows = mysql_num_rows($query);
	if($rows == 0){
		$query = "INSERT INTO `jobTitle` (`jobTitle`) VALUES ('$title')";
		mysql_query($query);
	}
	//load titles to userTitle table
	mysql_query("SET FOREIGN_KEY_CHECKS=0",$connection);
	$query = mysql_query("select * from `userJobTitles` where `jobTitle_jobTitle`='$title' AND `User_UserID`='$userID'", $connection);
	$rows = mysql_num_rows($query);
	if($rows > 0){
	return "Error. Title already exists for user.";
	}
	else{
		$query = "INSERT INTO `userJobTitles` (`jobTitle_jobTitle`,`User_UserID`) VALUES ('$title','$userID')";
		mysql_query($query);
		return "Added succesfully.";
	}
}
//delete job title from user profile
function deleteTitle($userID,$title){
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	mysql_query("SET FOREIGN_KEY_CHECKS=0",$connection);
	$query=mysql_query("DELETE FROM `userJobTitles` WHERE `user_userID`='$userID' AND `jobTitle_jobTitle`='$title'",$connection);
}

//bring back list of skills pertaining to user ID
function getSkills($userID){
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	//load skills
	$query = mysql_query("select * from `userSkills` where `User_UserID`='$userID'",$connection);
	while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
    	$userSkills[] =  $row['skills_skills'];  
	}
	$rows = mysql_num_rows($query);
	mysql_close($connection);
	if($rows == 0){
		$userSkills[] = "No Skills Yet";
	}
		return $userSkills;

}

//bring back list of programs pertaining to user ID
function getPrograms($userID){
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	//load programs
	$query = mysql_query("select * from `userPrograms` where `user_userID`='$userID'",$connection);
	while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
    	$userPrograms[] =  $row['programs_program'];  
	}
	$rows = mysql_num_rows($query);
	mysql_close($connection);
	if($rows == 0){
		$userPrograms[] = "No Programs Yet";
	}
		return $userPrograms;
}

//bring back list of titles pertaining to user ID
function getTitles($userID){
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	//load titles
	$query = mysql_query("select * from `userJobTitles` where `user_userID`='$userID'",$connection);
	while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
    	$userTitles[] =  $row['jobTitle_jobTitle'];  
	}
	$rows = mysql_num_rows($query);
	mysql_close($connection);
	if($rows == 0){
		$userTitles[] = "No Job Titles Yet";
	}
		return $userTitles;
}

//bring back list of employers pertaining to user ID
function getEmployers($userID){
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	//load employers
	$query = mysql_query("select * from `userWorkplace` where `user_userID`='$userID'",$connection);
	while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
    	$userEmployers[] =  $row['workplace_workplace'];  
	}
	$rows = mysql_num_rows($query);
	mysql_close($connection);
	if($rows == 0){
		$userEmployers[] = "No Employers Yet";
	}
		return $userEmployers;

}

//upload text area resume - want to change to file
function uploadResume($resume, $userID){
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	$query = mysql_query("UPDATE `user` SET `resumeLink`='$resume' where `userID`='$userID'",$connection);
	mysql_query($query);
	mysql_close($connection);
	return $resume . $userID;
}

//bring back resume pertaining to user ID
function getResume($userID){
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	//load skills
	$query = mysql_query("select * from `user` where `userID`='$userID'",$connection);
	while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
    	$resume[] =  $row['resumeLink'];  
	}
	$rows = mysql_num_rows($query);
	mysql_close($connection);
	if($rows == 0){
		$resume[] = "No Resume Yet";
	}
		return $resume;

}

//update contact information using update query	
function updateContact($userID,$fname,$lname,$email,$phone){
	echo $userID . " " . $fname . " " . $lname . " " . $email . " " . $phone;
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	$query = "UPDATE `user` SET `fname`='$fname', `lname`='$lname', `email`='$email', `phone`='$phone' WHERE `userID`='$userID';";
	mysql_query($query);
	mysql_close($connection);
	$_SESSION['fname'] = $fname;
	$_SESSION['lname'] = $lname;
	$_SESSION['email'] = $email;
	$_SESSION['phone'] = $phone;
	
	header("location: profile.php");
	
}

//search database based on criteria selection from search boxes
function search($skills,$programs,$titles){
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	$countSkill = count($skills);
	$countProgram = count($programs);
	$countTitle = count($titles);
	echo $countSkill;
	echo $countProgram;
	echo $countTitle;
	$skill_output = [];	
	//takes all users that match skills
	foreach($skills as $skill){
		if($skill == "*Not Important*"){
			$query = mysql_query("SELECT `User_UserID` from `userSkills`",$connection);
			$index3 = 0;
			while($row = mysql_fetch_array($query, MYSQL_ASSOC)){
				$skill_output[$index3]=$row['User_UserID'];
				$index3++;
				}
			break;	
		}
		else{
			$query = mysql_query("SELECT `User_UserID` from `userSkills` WHERE `skills_skills`='$skill'",$connection);		
			$rows = mysql_num_rows($query);
			if($rows > 0){
				$index3 = 0;
				while($row = mysql_fetch_array($query, MYSQL_ASSOC)){
					$skill_output[$index3]=$row['User_UserID'];
					$index3++;
				}//end while
			}//end if
		}//end else
		
	}//end for each

	$program_output = [];	
	//takes all users that match programs
	foreach($programs as $program){
		if($program == "*Not Important*"){
			$query = mysql_query("SELECT `User_UserID` from `userPrograms`",$connection);
			$index3=0;
			while($row = mysql_fetch_array($query, MYSQL_ASSOC)){
				$program_output[$index3]=$row['User_UserID'];
				$index3++;
			}//end while
			break;
		}//end if
		else{
			$query = mysql_query("SELECT `User_UserID` from `userPrograms` WHERE `programs_program`='$program'",$connection);
			$rows = mysql_num_rows($query);
			if($rows > 0){
				$index3=0;
				while($row = mysql_fetch_array($query, MYSQL_ASSOC)){
					$program_output[$index3]=$row['User_UserID'];
					$index3++;
				}//end while
			}//end if
		}//end else
	}//end for each
	$title_output = [];
	//takes all users that match job titles
	foreach($titles as $title){
		if($title == "*Not Important*"){
			$query = mysql_query("SELECT `User_UserID` from `userJobTitles`",$connection);
			$index3=0;
			while($row = mysql_fetch_array($query, MYSQL_ASSOC)){
				$title_output[$index3]=$row['User_UserID'];
				$index3++;
			}//end while
			break;
		}
		else{
			$query = mysql_query("SELECT `User_UserID` from `userJobTitles` WHERE `jobTitle_jobTitle`='$title'",$connection);
		
		$rows = mysql_num_rows($query);
		if($rows > 0){
			$index3=0;
			while($row = mysql_fetch_array($query, MYSQL_ASSOC)){
				$title_output[$index3]=$row['User_UserID'];
				$index3++;
			}//end while
		}//end if
		}//end else
	}//end for each
	
	$output = [];
	$index = 0;
	//runs through each index for each array and only keeps users that exist in all three arrays
	foreach($skill_output as $sk_out){
		foreach($program_output as $pr_out){
			foreach($title_output as $tt_out){
				if ($sk_out == $pr_out && $pr_out == $tt_out){
					$output[$index] = $tt_out;
						$index++;	
					}//end if
			}//end for each
		}//end for each
	}//end for each
	return $output;
}//end function


//prints out results from db back to search screen
function print_results($output,$paid){
	
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	$rowCount = count($output);
	$index = 0;

	//takes array of userIDs and gets all information from all necessary tables, creates additional arrays for each column in table
	foreach($output as $userID){
		$query = mysql_query("SELECT * from `user` where `userID` = $userID",$connection);
		while($row = mysql_fetch_array($query, MYSQL_ASSOC)){
				$fname[$index]=$row['fname'];
				$lname[$index]=$row['lname'];
				$resume[$index]="<a href=profile.php?resume=".$userID.">View Resume</a>";
				$profile[$index]="<a href=profile.php?user=".$userID.">View Profile</a>";
			}
		$query = mysql_query("SELECT * from `userSkills` where `User_UserID` = $userID",$connection);
		$skills[$index]="";
		while($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
            	$skills[$index] .= $row['skills_skills'] .'</br>';
	  	 }
		$query = mysql_query("SELECT * from `userPrograms` where `User_UserID` = $userID",$connection);
		$programs[$index]="";
		while($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
            	$programs[$index] .= $row['programs_program'] .'</br>';
	  	 }
		$query = mysql_query("SELECT * from `userJobTitles` where `User_UserID` = $userID",$connection);
		$titles[$index]="";
		while($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
            	$titles[$index] .= $row['jobTitle_jobTitle'] .'</br>';
	  	 }
		$query = mysql_query("SELECT * from `userWorkplace` where `User_UserID` = $userID",$connection);
		$workplace[$index]="";
		while($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
            	$workplace[$index] .= $row['workplace_workplace'] .'</br>';
	  	 }
		$index++;
	}
	
	//loops through arrays created above to write out information in each array.
	
	if($paid == 1 ){
		for($index2=0;$index2<$rowCount;$index2++){
			echo "<tr> <td>" . $fname[$index2] . " " . $lname[$index2] . " </td><td> " . $skills[$index2] . "</td><td>" . $programs[$index2] . "</td><td>" . $titles[$index2]. "</td><td>" . $workplace[$index2] . "</td><td>" . $profile[$index2] .  " </td><td> " . $resume[$index2] . "</td></tr>";
		
		}
	}
	else{	
		for($index2=0;$index2<$rowCount;$index2++){
			echo "<tr> <td>" . $fname[$index2] . " " . $lname[$index2] . " </td><td> " . $skills[$index2] . "</td><td>" . $programs[$index2] . "</td><td>" . $titles[$index2]. "</td><td>" . $workplace[$index2] . "</td><td> Upgrade Your Account For This Option </td><td> Upgrade Your Account For This Option </td></tr>";
		}
	}
	
	echo "</table>";
	
}

function upgradeAccount($userID){

	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	$query = mysql_query("UPDATE `user` SET `paidAccount`='1' where `userID`='$userID'",$connection);
	$_SESSION['paid'] = 1;
	mysql_query($query);
	mysql_close($connection);
}

function getFName($userID){
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	$query = mysql_query("select `fname` from `user` where userID='$userID'", $connection);
	$row = mysql_fetch_assoc($query);

	return($row['fname']);
}

function getLName($userID){
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	$query = mysql_query("select `lname` from `user` where userID='$userID'", $connection);
	$row = mysql_fetch_assoc($query);

	return($row['lname']);
}

function getEmail($userID){
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	$query = mysql_query("select `email` from `user` where userID='$userID'", $connection);
	$row = mysql_fetch_assoc($query);

	return($row['email']);
}

function getPhone($userID){
	$connection = mysql_connect("localhost", "root", "");
	$db = mysql_select_db("mydb", $connection);
	$query = mysql_query("select `phone` from `user` where userID='$userID'", $connection);
	$row = mysql_fetch_assoc($query);

	return($row['phone']);
}

?>