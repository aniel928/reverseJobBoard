<!--links needed for JQuery Autocomplete functionality-->
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">

<?php $title = "The Reverse Job Board - Edit Profile"; ?>
 
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

//loads arrays with information from database necessary for JQuery autocomplete.
$skills = loadSkills();
array_shift($skills);
$programs = loadPrograms();
array_shift($programs);
$jobTitles = loadJobTitles();
array_shift($jobTitles);
$employers = loadEmployers();

include("header.php");

//After updating information from contact (below), redirects here (needs to be before JQUERY stuff b/c that "writes out" and this needs to redirect.)
if($_GET["edit"] == "updateContact"){
		updateContact($_SESSION['user_id'],$_POST["fname"],$_POST["lname"],$_POST["email"],$_POST["phone"]);		
	}
?>

<!--preload arrays for autocomplete-->
<script>
var skillArray = [<?php echo '"' . implode ('","', ($skills)) . '"'; ?>];
var programArray = [<?php echo '"' . implode ('","', ($programs)) . '"'; ?>];
var titleArray = [<?php echo '"' . implode ('","', ($jobTitles)) . '"'; ?>];
var employerArray = [<?php echo '"' . implode ('","', ($employers)) . '"'; ?>];
</script>


<?php

//if "edit" is clicked next to contact information on profile	
	if($_GET["edit"] == "contact"){
		
	 echo 
	 	"<form id=info method='post' action='profile_edit.php?edit=updateContact'><table>
	 		<tr>
	 			<label>First Name:&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp </label>
	 			<input type='text' name='fname' id='fname' value=" . $fname . ">
	 		</tr></br></br>
	 		<tr>
	 			<label>Last Name:&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp </label>
	 			<input type='text' name='lname' id='lname' value=" . $lname . ">
	 		</tr></br></br>
	 		<tr>
	 			<label>Email: &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</label>
	 			<input type='text' id='email' name='email' value=" . $email . ">
	 		</tr></br></br>
	 		<tr>
	 			<label>Phone Number: &nbsp &nbsp	</label>
	 			<input type='text' id='phone' name='phone' value=" . $phone . ">
	 		</tr></br></br>
	 		<tr>
	 			<input type='submit' name='submit'/>
			 	<button ><a style='text-decoration: none; color: black;' href='profile.php'>Back to Profile</a></button>

	 		</tr>
	 	</table>
	 	</form>
	 	";
	 	
	}//end get edit contact

	//if "edit" is clicked next to skills on profile
	if($_GET["edit"] == "skills"){
		//adds new skill to database
		if(isset($_POST["skill"])){
			$skill = $_POST["skill"];
			$message = addSkill($skill,$userID);
			echo "<p style='color: red; margin: 0px 150px 0px 150px	;'>" . $message . "</p></br></br>";
		}//end isset POST skills  ?>
		 <button style="margin: 0px 10%; float:right;"><a style="text-decoration: none; color:black" href="profile.php">Back to Profile</a></button>
		<?php
		//displays all skills currently in database
		echo "<div class='profileEdit'>";
		$userSkills = getSkills($userID); ?>
		<table>
		<tr><th style='text-align: left;'> <?php echo $fname . "'s Skills"?> </th></tr>
		<?php foreach($userSkills as $skill){
			if($skill == 'No Skills Yet'){
				echo "<tr><td>" . $skill . '</td></tr>';					
			}
			else{
				echo "<tr><td>" . $skill . ' <a href="delete.php?skill='. $skill . '">Delete</a></td></tr>';	
			}
		} ?>
	 	</table>
	 	</div>
	  </br></br></br>
	  <div class="form">
		 <form method="post" action="profile_edit.php?edit=skills">
		 <table>
		 <tr>
		 <label for="skill">New skill:</label>
		 <input type="text" id="skill" name="skill"/> 
		 <script> $("#skill").autocomplete({
		 	source: skillArray
		 	});</script>
		 <input type="submit" value="Add"/>
		 </tr>
		 </table>
		 </br></br></br		
		</form>
	 </div>
	<?php
	}//end get edit skills

//if "edit" is clicked next to programs on profile
	if($_GET["edit"] == "programs"){
		//adds new program to database
			if(isset($_POST["program"])){
			$program = $_POST["program"];
			$message = addProgram($program,$userID);
			echo "<p style='color: red; margin: 0px 150px 0px 150px;'>" . $message . "</p></br></br>";
		} //end isset POST program ?>
		 <button style="margin: 0px 10%; float:right;"><a style="text-decoration: none; color:black" href="profile.php">Back to Profile</a></button>
		<?php
		//displays all programs currently in database
		echo "<div class='profileEdit'>";		
		$userPrograms = getPrograms($userID); ?>
		<table>
			<tr><th style='text-align: left;'> <?php echo $fname . "'s Programs"?> </th></tr>
		<?php foreach($userPrograms as $program){
			if($program == 'No Programs Yet'){
				echo "<tr><td>" . $program . '</td></tr>';					
			}
			else{
				echo "<tr><td>" . $program . ' <a href="delete.php?program='. $program . '">Delete</a></td></tr>';	
			}
		}	 ?>
	 	</table>
	 	</div>
	 </br></br></br>
	 <div class="form">
	  <form method="post" action="profile_edit.php?edit=programs">
		 <label for="program">New program:</label>
		 <input type="text" name="program" id="program"/> 
		 <script> $("#program").autocomplete({
		 	source: programArray
		 	});</script> 
		 <input type="submit" value="Add"/>
		 </br></br></br>
		</div>
	</form>
		
	<?php
	}//end get edit programs

	//if "edit" is clicked next to titles on profile
	if($_GET["edit"] == "titles"){
		//adds new title to database
		if(isset($_POST["title"])){
			$title = $_POST["title"];
			$message = addTitle($title,$userID);
			echo "<p style='color: red; margin: 0px 150px 0px 150px	;'>" . $message . "</p></br></br>";
		}//end isset POST title
 		?>
 		 <button style="margin: 0px 10%; float:right;"><a style="text-decoration: none; color:black" href="profile.php">Back to Profile</a></button>
 		<?php

		//displays all titles currently in database
		echo "<div class=profileEdit>";
		$userTitles = getTitles($userID); ?>
		<table>
			<tr><th style='text-align: left;'> <?php echo $fname . "'s Job Titles"?> </th></tr>
		<?php foreach($userTitles as $title){
			if($title == 'No Job Titles Yet'){
				echo "<tr><td>" . $title . '</td></tr>';					
			}
			else{
				echo "<tr><td>" . $title . ' <a href="delete.php?title='.$title.'">Delete</a></td></tr>';
			}
		}
	?>	
	 	</table>
	 	</div>	
	 </br></br></br>
	 <div class="form">
	  <form method="post" action="profile_edit.php?edit=titles">
		 <label for="title">New Job Title:</label>
		 <input type="text" name="title" id="title"/> 
		 <script> $("#title").autocomplete({
		 	source: titleArray
		 	});</script> 
		 <input type="submit" value="Add"/>
		 </br></br></br>
      </div>
	</form> 
	 <?php
	}//end get edit title

	//if "edit" is clicked next to employers on profile
	if($_GET["edit"] == "employers"){
		//adds new employer to database
		if(isset($_POST["employer"])){
			$employer = $_POST["employer"];
			$message = addEmployer($employer,$userID);
			echo "<p style='color: red; margin: 30px 150px 0px 150px	;'>" . $message . "</p></br></br>";
			
		
		}//end isset POST employer
		?>
		 <button style="margin: 0px 10%; float:right;"><a style="text-decoration: none; color:black" href="profile.php">Back to Profile</a></button>
		<?php
		
		//displays all titles currently in database
		echo "<div class=profileEdit>";
		$userEmployers = getEmployers($userID);?>
		<table>
			<tr><th style="text-align: left"> <?php echo $fname . "'s Employers"?> </th></tr>
		<?php
		foreach($userEmployers as $employer){
			if($employer == 'No Employers Yet'){
				echo "<tr><td>" . $employer . '</td></tr>';					
			}
			else{
				echo "<tr><td>" . $employer . ' <a href="delete.php?employer='.$employer.'">Delete</a></td></tr>';	
			}
		
		}
		
	 ?>
	 
	 	</table>
	 	</div>
	 </br></br></br>
	 <div class="form">
	 <form method="post" action="profile_edit.php?edit=employers">
		 <label for="employer">New employer:</label>
		 <input type="text" name="employer"/> 
		 <script> $("#employer").autocomplete({
		 	source: employerArray
		 	});</script> 
		 <input type="submit" value="Add"/>
		</br></br></br>
      </div>
	</form>

	 <?php
	}//end get edit employers
	
	//if resume info is submitted
	if($_GET["edit"] == "resume_upload"){
		//adds new resume to database
		$resume = nl2br($_POST["resume"]);
		uploadResume($resume,$userID);	
		$_SESSION['resumeLink'] = $resume;
		echo "Resume updated.";
		echo "<a href='profile.php'><button>Back to Profile</button></a>";
	}
	
	//if "edit" is clicked next to resume on profile

	if($_GET["edit"] == "resume"){
		echo '<div style="width: 1000px">
			<form method="post" action="profile_edit.php?edit=resume_upload">	
			<h3 style="text-decoration: none; color: navy; margin: 30px 10px -10px 100px">Copy and Paste your resume into the box below:</h3></br>
			<textarea rows=15 style="margin: 10px 10px 10px 100px; width:1000px; font-size: 16px; background: #FFFDFD" name="resume" id="resume">'. str_replace('<br />',"\n",$resume) .'</textarea>
			<input style="float: right; margin: 5px -100px 5px 50px; font-size: 20px; width: 300px" type="submit" value="Upload Resume" name="submit"/>
			</form>

			<button style="float:left; margin: -10px 5px 5px 100px; font-size: 20px; width: 300px;"><a style= "text-decoration: none; color: black;" href="profile.php?resume=true">Back</a></button>

		</div>';
	}//end get edit resume
		echo "</br></br></br></br></br>";
		include("footer.php");
?>


