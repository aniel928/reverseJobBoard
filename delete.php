<?php $title = "The Reverse Job Board - Delete"; ?>
 
<?php
include("header.php");
include("functions.php");
session_start();

//if the "delete account" is confirmed below.
if(isset($_GET["status"]) && $_GET["status"] == "yes"){ 
  	$userID = $_SESSION['user_id'];
  	deleteAccount($userID);
  }
  
//if "delete account" is pressed from dashboard  
if(isset($_GET["status"]) && $_GET["status"] == "account"){ ?>
	
	<p style=" padding: 20px 0px; font-size: 20px; text-align: center; color: navy;font-family: helvetica">Are you sure you want to delete your account?  This will permanently delete all of your data and your account.
	<div style="text-align: center;">
	<a href="delete.php?status=yes"><button style="width:300px;text-align: center">Yes, I want to delete my account</button></a>
	<a href="dashboard.php"><button style="width:300px; text-align: center;">No, back to safety.</button></a>
	</div>
<?php

}

//if the "delete" button is pressed next to a skill
if(isset($_GET["skill"])){ 
	$skill = $_GET["skill"];
	$userID = $_SESSION['user_id'];
	deleteSkill($userID,$skill);
	echo "Skill '" . $skill . "' deleted successfully";
	echo "<button><a style='color:black; text-decoration: none;' href='profile.php'> Back to Profile </a></button>";
	echo "<button><a style='color:black; text-decoration: none;' href='profile_edit.php?edit=skills'> Back to Skills </a></button>";

}

//if the "delete" button is pressed next to a program
if(isset($_GET["program"])){ 
	$program = $_GET["program"];
	$userID = $_SESSION['user_id'];
	deleteProgram($userID,$program);
	echo "Program '" . $program . "'deleted Successfully";
	echo "<button><a style='color:black; text-decoration: none;' href='profile.php'> Back to Profile </a></button>";
	echo "<button><a style='color:black; text-decoration: none;' href='profile_edit.php?edit=programs'> Back to Programs </a></button>";

}

//if the "delete" button is pressed next to a job title
if(isset($_GET["title"])){ 
	$title = $_GET["title"];
	$userID = $_SESSION['user_id'];
	deleteTitle($userID,$title);
	echo "Job Title '" . $title . "' deleted Successfully";
	echo "<button><a style='color:black; text-decoration: none;' href='profile.php'> Back to Profile </a></button>";
	echo "<button><a style='color:black; text-decoration: none;' href='profile_edit.php?edit=titles'> Back to Job Titles </a></button>";

}

//if the "delete" button is pressed next to an employer
if(isset($_GET["employer"])){ 
	$employer = $_GET["employer"];
	$userID = $_SESSION['user_id'];
	deleteEmployer($userID,$employer);
	echo "Employer '" . $employer . "' deleted Successfully";
	echo "<button><a style='color:black; text-decoration: none;' href='profile.php'> Back to Profile </a></button>";
	echo "<button><a style='color:black; text-decoration: none;' href='profile_edit.php?edit=employers'> Back to Employers </a></button>";

}


include("footer.php");
?>