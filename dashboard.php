<?php 

$title = "The Reverse Job Board - Dashboard"; 
 
//start session to pull user information
session_start();
include("header.php");
$username = $_SESSION['login_user'];
$email = $_SESSION['email'];
$userID = $_SESSION['user_id'];
$fname = ucfirst($_SESSION['fname']);
$type = $_SESSION['accountType'];

?>
<section>
<!--Write out welcome message with first name -->
<h1 style="padding:0 200px"> Welcome, <?php echo $fname ?>!</h1>
<!--Display dashboard -->
<div id="dash">
	<!-- check to see if job seeker, if so displays job seeker dash, otherwise displays employer's -->
	<?php if($type =='JS'){
	?><table>
		<tr>
		<!--	<td><a href="inbox.php">Inbox</a></td> -->
			<td><a href="profile.php">View/Edit Profile</a></td>
		</tr>
		<tr>
			
		 	<td><a href="logout.php">Log Out</a></td>
		</tr>
		</tr>
		<td><a href="delete.php?status=account">Delete Account</a></td>
		</tr>
	</table>	
	 <?php } 
	 else{ ?>
	<table >
		<tr>
		<!--	<td><a href="inbox.php">Inbox</a></td>-->
			<td><a href="search.php">Search Job Seekers</a></td>
		</tr>
		<tr>
			<td><a href="profile.php">View/Edit Profile</a></td>
		</tr>
		<tr>	
			<td><a href="upgrade.php">Upgrade/Manage Account</a></td>
		</tr>
		
			
		 	<td><a href="logout.php">Log Out</a></td>
		</tr>
		<tr>
			<td><a href="delete.php?status=account">Delete Account</a></td>
		</tr>
	
		
	</table>
	
<?php	 } ?>
</div>

</section>

<?php include("footer.php") ?>
