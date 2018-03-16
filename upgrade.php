<?php $title = "The Reverse Job Board - Upgrade"; ?>
 
<?php
//start session to secure variables
session_start();

include("functions.php");
include("header.php");
?>

<h1 style="text-align: center"> Upgrade </h1>

<?php 
if(isset($_GET["status"]) && ($_GET["status"]=="upgrade")){

upgradeAccount($_SESSION['user_id']);?>

<p style="margin: 10px; text-align: center; font-size: 30px">Your Account Has Been Upgraded!</p>
<button style="width: 200px; margin: 10px 44.25%"><a style="text-decoration: none; color: black;"href='dashboard.php'>Back to Dashboard</a></button>




<?php
}else{
?>

<form style="text-align: center;" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<table style="margin: auto; color: navy">
<tr style=><td><input style="margin: auto;" type="hidden" name="on0" value="Choose Subscription...">Choose Subscription...</td></tr><tr><td><select style="height: 30px" name="os0">
	<option value="1 month subscription">1 month subscription : $10.00 USD - monthly</option>
	<option value="12 month subscription">12 month subscription : $100.00 USD - yearly</option>
</select> </td></tr>
</table>
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</br>
<button style="margin: 0% 44.25% ;"><a style='text-decoration: none; color: black' href=upgrade.php?status=upgrade>Paid</a></button>

<?php
}
include("footer.php");

?>