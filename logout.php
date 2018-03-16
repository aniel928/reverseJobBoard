<?php 
//starts session (to obtain variables), unsets session and then destroys session before redirecting to home page.
session_start();
unset($_SESSION[""]);  
session_destroy();
header("Location: index.php");

?>