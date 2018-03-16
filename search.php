<?php $title = "The Reverse Job Board - Search"; ?>
 
<?php
//starts session to use session variables
session_start();
include("header.php");
include("functions.php");

//redirects here after search is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	
	//if any boxes do not have values chosen, then error and return back to search
	if($_POST["selectSkill"] == ""  || $_POST["selectProgram"] == "" || $_POST["selectTitle"] == ""){
		header("location: search.php?status=empty");
	}//end if($_POST)
	else{
	//set arrays with values chosen
	$selectSkill = $_POST["selectSkill"];
	$selectProgram = $_POST["selectProgram"];
	$selectTitle = $_POST["selectTitle"];

	//call search function
	$_SESSION['output']=search($selectSkill,$selectProgram,$selectTitle);

	header("location:search.php?status=results");
	}
}//end if($_SERVER)

//post results of search
if(isset($_GET["status"]) && $_GET["status"] == "results"){
	
	//sets session variable
	$output = array_unique($_SESSION['output']);
	//$_SESSION['output'] = "";
	
 ?>
 <!--display results -->
<?php if(count($output)>0){ ?>
		<table id="results">
 			<tr style="background-color=#FCFCFC">
 				<th>Name</th>
 				<th>Skills</th>
 				<th>Programs Used</th>
 				<th>Job Titles</th>
 				<th>Companies worked for</th>
 				<th>Profile</th>
 				<th>Resume</th>
 			</tr>
	
 		<?php 
 		//prints out search results (hits from database)
 		print_results($output,$_SESSION['paid']);
 		 ?>
	</table>
	
	 <div style="padding: 10px 0px; text-align: center">
	<button style="width: 200px"><a style='text-decoration: none; color: black;' href='dashboard.php'>Back to Dashboard</a></button>
	<button><a style='text-decoration: none; color: black;' href='search.php'>Start Over</a></button>
	</div>

	<?php }else{

	echo "<p style='color: navy; text-align: center; font-family: Helvetica; font-size: 30px;'>Your search criteria returned no matched results. Please try again.</p>";
	echo "<div style='text-align: center'>";
	echo '<button style="width: 200px"><a style="text-decoration: none; color: black;"href="dashboard.php">Back to Dashboard</a></button>';
	echo "<button><a style='text-decoration: none; color: black;' href='search.php'>Start Over</a></button>";
	echo "</div>";
	} ?>
	
<?php }//end if(isset)

else{ //main page
	//pulls all data from database.
	//$_SESSION['output']=null;
	$skills = loadSkills();
	$programs = loadPrograms();
	$jobTitles = loadJobTitles();
?>


	<form id="chooseCriteria" style="text-align: center; margin: auto;" method="post" action="search.php">
	<h2> Choose Search Criteria </h2>
		<?php if(isset($_GET["status"]) && $_GET["status"] == "empty"){//if any box is empty, show this message
	    	    echo "<p style = 'color: red'> </b>Please choose at least one option in each box:</b></p>";
	        }
	  	?>
		<div style="float:left; width: 30%; margin: 0px 2px 0px 40px;">
		 <h3>Choose Skills</h3>	
			<select multiple name='selectSkill[]'>
		<?php	
			foreach ($skills as $skill){
			echo '<option value="';
			echo $skill;
			echo '">' . ucfirst($skill);
					
			 } ?>
		</select>
		</div>
		<div style="float:left; width: 30%; margin: 0px 2px 0px 2px;">
		<h3>Choose Programs</h3>	
		
		<select multiple name='selectProgram[]'>
		<?php
			foreach ($programs as $program){
			echo '<option value="';
			echo $program;
			echo '">' . ucfirst($program);
					
		 } ?>
		</select>
		</div>
		<div style="float:left; width: 30%; margin: 0px 2px 0px 2px;">
		<h3>Choose Job Titles</h3>	
		<select multiple name='selectTitle[]'>
		<?php
			foreach ($jobTitles as $title){
			echo '<option value="';
			echo $title;
			echo '">' . ucfirst($title);
					
		 } ?>
		</select>
		</div>
		<h3>   </h3>
		<div style="margin: 0 auto; text-align: center;">
		<input type = submit name = "submit" value = "Search"/>
		<button style="width: 200px;"><a style="text-decoration: none; color: black;" href="dashboard.php">Back To Dashboard</a></button>
		</div>
	</form>	
	<?php
	}//end else (main page)
	echo "</br></br></br>";

	include("footer.php");

	?>