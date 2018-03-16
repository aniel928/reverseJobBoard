<?php $title = "The Reverse Job Board - Profile"; ?>
 
<?php 
//start session and pull user information into variables
session_start();
include("functions.php");
$userID = $_SESSION['user_id'];
$fname = $_SESSION['fname'];
$lname = $_SESSION['lname'];
$email = $_SESSION['email'];
$paid = $_SESSION['paid'];
$type = $_SESSION['accountType'];
$phone = $_SESSION['phone'];
$resume = $_SESSION['resumeLink'];


//changes display for employers viewing job seeker by changing userID variable temporarily (changed back at bottom of page)
if(isset($_GET["user"])){
	$userProfile = $_GET["user"];
	$userID = $userProfile;	
	$type = "JS";
	$fname = getFName($userProfile);
	$lname = getLName($userProfile);
	$email = getEmail($userProfile);
	$phone = getPhone($userProfile);
}

include("header.php");
?>

<!--Displays "back to dashboard" for personal profile or "back to search" for employer viewing other profile -->	
<?php if(!isset($_GET["user"]) ){echo "<button style='float:right; margin: 15px 100px; width:200px;'><a style='text-decoration:none; color:black' href='dashboard.php'>Back to Dashboard</a></button>";}else{echo 	"<button><a style='text-decoration: none; color:black;' href='search.php'>Start Over</a></button>";}
	 if($userID != $_SESSION['user_id']){
	 	$userID = $_GET['user'];
		echo'<button style="float: left"> <a style="text-decoration: none; color: black;" href="javascript:history.go(-1)">Back to Results</a></button>';
		}
				
	//checks for whether should be viewing resume or profile
	if(isset($_GET["resume"]) && ($_GET["resume"]=="true")){?>
		<button style="width: 200px; margin: 10px 100px"><a style="text-decoration: none; color: black;" href='profile.php'> Back to Profile </a></button>
		<h2 style='margin: 50px 100px 10px 100px;'> Resume <?php if(!isset($_GET["user"])){ echo"<a style='font-family: Calibri, sans-serif; color: blue; text-decoration: underline; font-size: 12px;' href='profile_edit.php?edit=resume'>Edit</a>";} ?></h2>
		<table id="profileResume">
			<tr>
				<th id="resume"><?php echo "</br>";
				
				foreach(getResume($userID) as $resume){echo $resume . "</br>";}?> </br> </th>
			</tr>
		</table></br></br></br>
	<?php } elseif(isset($_GET["resume"])){ //checks to see if should be viewing a different user's resume
		
		echo'<button style="float: left"> <a style="text-decoration: none; color: black;" href="javascript:history.go(-1)">Back to Results</a></button>';
		echo'<button style="float: left"> <a style="text-decoration: none; color: black;" href="search.php">Start Over</a></button>';
		
		$userID=$_GET["resume"];?>
		</br></br></br></br>
		<h1 style="text-align:center"> Resume</h1>
		
		<table id="profileResume">
			<tr>
				<th id="resume" ><?php echo "</br>";
				foreach(getResume($userID) as $resume){echo $resume . "</br>";}?> </br> </th>
			</tr>
		</table></br></br></br>
		<?php } else {?>
		
	 <section id="profileSection">
		</br><h1 style="text-align: center"> Profile </h1>
		
		<?php if(($type == "JS") && ((getSkills($userID)[0] == 'No Skills Yet') || (getPrograms($userID)[0] == 'No Programs Yet') || (getTitles($userID)[0] == 'No Job Titles Yet'))){ ?>  <p style='color:red; text-align:center; font-size: 16px;'>You must fill out at least one skill, program, and job title to show up in employer searches!</p> <?php } ?>
		
		<h2> Contact Information <?php if(!isset($_GET["user"])){ echo"<a href='profile_edit.php?edit=contact'>Edit</a>"; } ?></h2>
		
		<table id="profileContact">
			<tr> <th> &nbsp </th> </tr>
			<tr>
				<th><?php
				echo "Name:  &nbsp &nbsp &nbsp &nbsp &nbsp   " . $fname . " " . $lname; ?> </br></br> </th>
			</tr>
			<tr>
				<th><?php
				echo "Email:  &nbsp &nbsp &nbsp &nbsp &nbsp   " . $email; ?> </br></br></th>
			</tr>
			<tr>
				<th><?php
				echo "Phone: &nbsp &nbsp &nbsp &nbsp &nbsp    " . $phone; ?> </br></br></th>
			</tr>
		</table>	
	<?php if($type == "JS"){  // only write out the rest if job seeker?>
		<h2> Skills <?php if(!isset($_GET["user"])){ echo"<a href='profile_edit.php?edit=skills'>Edit</a>";} ?></h2>
		<table id="profileSkills">
			<tr>
				<th><?php echo "</br>";
				foreach(getSkills($userID) as $skill){echo $skill . "</br>";}?> </br> </th>
			</tr>
		</table>	
	
		<h2> Programs <?php if(!isset($_GET["user"])){ echo"<a href='profile_edit.php?edit=programs'>Edit</a>";} ?></h2>
		<table id="profilePrograms">
			<tr>
				<th><?php echo "</br>";
				foreach(getPrograms($userID) as $program){echo $program . "</br>";}?> </br> </th>
			</tr>
		</table>	
		
		<h2> Job Titles <?php if(!isset($_GET["user"])){ echo"<a href='profile_edit.php?edit=titles'>Edit</a>";} ?></h2>
		<table id="profileTitles">
			<tr>
				<th><?php echo "</br>";
				foreach(getTitles($userID) as $title){echo $title . "</br>";}?> </br> </th>
			</tr>
		</table>	
	
	
		<h2> Employers <?php if(!isset($_GET["user"])){ echo"<a href='profile_edit.php?edit=employers'>Edit</a>";} ?></h2>
		<table id="profileEmployers">
			<tr>
				<th><?php echo "</br>";
				foreach(getEmployers($userID) as $employer){echo $employer . "</br>";}?> </br> </th>
			</tr>
		</table>
		<?php if(!isset($_GET["user"])){ ?>
		<a href="profile.php?resume=true"><h2 style="text-align: center; color: navy; text-decoration: none;">View / Edit Resume</h2></a>
		
		<?php if(!isset($_GET["user"])){echo "<button style='margin: 0px 100px; float: right; margin: right; width:200px;'><a style='text-decoration:none; color:black; font-size: 16px;' href='dashboard.php'>Back to Dashboard</a></button>";} else{  echo "<button><a style='text-decoration: none; color:black;'href='search.php'>Start Over</a></button>";}
		
		 }}} ?>
		
	</section>
	
	</br></br></br></br></br>
	<?php 
		//set userID var back to session var in case needed
		$userID = $_SESSION['user_id'];	
		$type = $_SESSION['accountType'];
	
	include("footer.php") ?>
	