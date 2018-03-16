<?php 
session_start();
include("functions.php");
$title = "The Reverse Job Board - Home";
 
//after pressing login, redirects back to hoempage but now enters this code
if($_SERVER["REQUEST_METHOD"] == "POST"){


	if($_POST["name"] == "" || $_POST["pw"] == ""){
		header("location: index.php?status=failed");
	}
	$username = trim(filter_input(INPUT_POST,"name",FILTER_SANITIZE_EMAIL));
	$password = trim(filter_input(INPUT_POST,"pw",FILTER_SANITIZE_SPECIAL_CHARS));
	$type = $_POST["accountType"];

	login($username,$password,$type);
}

include("header.php"); 

//runs this code after "delete account" is pressed and confirmed
if(isset($_GET["status"]) && $_GET["status"]=="deleted"){
	 echo "<p style = 'color: red'> </b>Account Successfully Deleted</b></p>";
}

?>

<!-- Runs this code every time, home page code -->
	<section class="container">
		<div class="home-page-boxes">
			<h2> Existing Users </h2>		
			<?php if(isset($_GET["status"]) && $_GET["status"] == "failed"){//if login error occurs, redirects to homepage, but writes error message out.
    	      echo "<p style = 'color: red'> </b>Invalid User Name, Password, or Account Type!  Please try again:</b></p>";
        	}
  			?>
			<form method="post" action="index.php">
				<table>
					<tr>
						<th><label for="name"> Email address: </label></th>  
						<td><input id="name" name="name" value="" style = "padding: 5; margin: 0 15; border-radius: 2px;" type = text></td>
					</tr>
					<tr>
						<th><label for="pw">Password: </label></th>
						<td><input id="pw" name="pw" value="" style = "padding: 5; margin: 0 41; border-radius: 2px" type = password></td>
					</tr>
					<tr>
						<th style="padding: 25px 0px 0px 0px; font-size: 18px;"><input type = radio name = "accountType" checked = "checked" value = "JS">Job Seeker</th>
					</tr>
					<tr style="padding: -200px 0px 25px 0px; font-size: 18px;">
						<th><input type = radio  name = "accountType" value = "EM">Employer</th>
						<td><input type = submit name = "submit" value = "Log In"</td>
					</tr>
				</table>
			</form>
		
		</div>
		<div class="home-page-boxes">
			<h2> New Users </h2>
			<p> No account yet?  Register for one here! </p>
			<p> <a href="register.php"><button style = "margin: 15px; width: 150px; padding: 5px"> Create Account </button> </a></p>
		</div>
			
	</section>
	
	
	
	
<?php include("footer.php"); ?>