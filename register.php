<?php $title = "The Reverse Job Board - Register"; ?>
 
<?php 
//once submit button is pressed, redirects here.
if($_SERVER["REQUEST_METHOD"] == "POST"){
	include("functions.php");

	//if any value is null, redirect to "try again" page
	if($_POST["fname"] == "" || $_POST["lname"] == "" || $_POST["phone"] == "" || $_POST["email"] == "" || $_POST["pass"] == "" || $_POST["confirm"] == "" || $_POST["accountType"] == ""){
		header("location: register.php?status=failed");
	}

	//assign fields to variables			
	$fname = ucfirst(trim(filter_input(INPUT_POST,"fname",FILTER_SANITIZE_STRING)));
	$lname = ucfirst(trim(filter_input(INPUT_POST,"lname",FILTER_SANITIZE_STRING)));
	$phone = trim(filter_input(INPUT_POST,"phone",FILTER_SANITIZE_NUMBER_INT));
	$email = trim(filter_input(INPUT_POST,"email",FILTER_SANITIZE_EMAIL));
	$pass = trim(filter_input(INPUT_POST,"pass",FILTER_SANITIZE_SPECIAL_CHARS));
	$confpass = trim(filter_input(INPUT_POST,"confirm",FILTER_SANITIZE_SPECIAL_CHARS));
	$type = trim($_POST["accountType"]);

	//if password fields don't match, redirect to "try again" page
	if($confpass != $pass){
		header("location: register.php?status=failed");	
		}
	else{
		//create account with passed variables then redirect to login and dashboard (through function).
		createAccount($fname,$lname,$phone,$email,$pass,$type);
		}
}//end if($_SERVER)

include("header.php");

if(isset($_GET["status"]) && $_GET["status"] == "failed"){//writes out error message if error occurs during creation
	echo "<p style = 'color: red'> </b>Input Error - passwords didn't match, or account may already exist.</b></p>";
}

?>
<form id="registration" method="post" action="register.php">
<table>
	<tr>
		<th><label for="fname">First name</label></th>
		<td><input type=text name="fname" id="fname"></td>
	</tr>
	<tr>
		<th><label for="lname">Last name</label></th>
		<td><input type=text name="lname" id="lname"></td>
	</tr>
	<tr>
		<th><label for="phone">Phone number</label></th>
		<td><input type=tel name="phone" id="phone"></td>
	</tr>
	<tr>
		<th><label for="email">Email</label></th>
		<td><input type=text name="email" id="email"></td>
	</tr>
	<tr>
		<th><label for="pass">Create Password</label></th>
		<td><input type=password name="pass" id="pass"></td>
	</tr>
	<tr>
		<th><label for="confirm">Confirm Password</label></th>
		<td><input type=password name="confirm" id="confirm"></td>
	</tr>
		<th><label>            </label></th>
		<td><select class=dropdown name="accountType">
			<option value="JS">Job Seeker</option>
  			<option value="EM" <?php if((isset($_GET["type"])) && ($_GET["type"]="emp")){echo "selected=true";}?>>Employer</option>
		</select></td>
	<tr>
		<th><label>            </label></th>
		<td><input type="submit" value="Create Account"></td>
	</tr>
</table>

<?php 
include("footer.php");
?>