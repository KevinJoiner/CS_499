<?php include 'config/header.php'; ?>

<html>
<div id="home_page">
 <body>
     <div>
		<?php
			if (isset($_SESSION['user'])){
				echo '<h2>Hey there! Click the links on the header to navigate the site.</h2>';
			}
			else{
				echo '<h2>Welcome User please login or sign up!</h2>';
			}
			
		?>
</body>
</div>
</html>

