<!--Footer for all pages-->

	</br></br></br></br></br>
	</body>
	<footer>
			<a href=<?php if(isset($_SESSION['fname'])){ echo "'dashboard.php'";}else{echo "'index.php'";}?>><?php if(isset($_SESSION['fname'])){ echo 'Dashboard';}else{echo 'Home';}?></a> <a href="contact.php">Contact</a>  <a href="about.php">About</a> 
		</footer>
	 </body>
 </html>
