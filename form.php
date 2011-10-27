<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Website Tracker</title>
        <!-- CSS -->
        <link type="text/css" rel="stylesheet" href="css/main.css"/>
        <!-- Javascript --> 
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/website-tracker.js"></script>
    </head>
    
	<body>
		
		<h1>Website Tracker</h1>	
		
		<div id="wrapper">	
			<!-- displays form errors from PHP... a bit messy (mixes layers) but without template class or framework, this is how it goes... -->
			<div class="errors">
				<?php 
					if (isset($errs)) {
						foreach($errs as $err) {
							echo $err."<br/>";
						}
					}
				?>
			</div>
			
			<form id="exerciseForm" method="post" action="index.php">  
				
				<fieldset>
					<legend>Is this request a Launch or Relaunch?</legend>
					
					<div>
						<input type="radio" name="status" value="launch" checked="checked" />
						<strong>Launch</strong>
					</div>
					
					<div>
						<label for="sitedesc">Site Description:</label>
						<textarea id="sitedesc" name="sitedesc" rows="5" cols="40"></textarea>
					</div>
					
					<div>
						<input type="radio" name="status" value="relaunch"/>
						<strong>Relaunch</strong>
					</div>
					
					<div>
						<label for="notes">Change Notes: <em>(optional)</em></label>
						<textarea id="notes" name="notes" rows="5" cols="40"></textarea>
					</div>					
				</fieldset>
				
			    <fieldset>
			        <legend>Please enter your information below</legend>
			            
		        	<div class="forlaunch">
		        		<label for="fname">First Name:</label>  
						<input type="text" class="text" id="fname" name="fname" value="<?php if (isset($_SESSION['fname'])) {echo $_SESSION['fname'];}?>" size="20"/>	
		        	</div>
		  
		  			<div class="forlaunch">
		            	<label for="lname">Last Name:</label>  
						<input type="text" class="text" id="lname" name="lname" value="<?php if (isset($_SESSION['lname'])) {echo $_SESSION['lname'];}?>" size="20"/>
					</div>
					
					<div class="forlaunch">
						<label for="email">Email Address:</label>
						<input type="text" class="text" id="email" name="email" value="<?php if (isset($_SESSION['email'])) {echo $_SESSION['email'];}?>" size="20"/>
					</div>
					
					<div class="forlaunch">
						<label for="phone">Telephone: <em>(optional)</em></label>
						<input type="text" class="text" id="phone" name="phone" value="<?php if (isset($_SESSION['phone'])) {echo $_SESSION['phone'];}?>" size="20"/>
						<span class="formInfo">i.e. (123) 456-7890</span>
					</div>
					
					<div>
						<label for="url">Full Website Address:</label>
						<input type="text" class="text" id="url" name="url" value="<?php if (isset($_SESSION['url'])) {echo $_SESSION['url'];}?>" size="20"/>
						<span class="formInfo">i.e. http://example.com</span>
					</div>
					
					<div>
						<label for="thedate">Date: <em>(Launch or Relaunch)</em></label>
						<input type="text" class="text" id="date" name="thedate" value="<?php if (isset($_SESSION['thedate'])) {echo $_SESSION['thedate'];}?>" size="20"/>
						<span class="formInfo">i.e 2011-01-31</span>
					</div>	
					
					<div class="center">
						<button type="submit" id="submitBtn">Submit &raquo;</button>
						<input type="hidden" name="submit" id="submit" value="true"/>
					</div>
					
				</fieldset>
				
			</form>
			
		</div>
		
	</body>
	
</html>