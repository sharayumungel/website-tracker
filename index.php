<?php
// require function library
require ("functions.php");

//start a session
session_start();

// fix magic quotes if turned on
if ( get_magic_quotes_gpc() ) {
    $_GET = array_map('stripslashes',$_GET);
    $_POST = array_map('stripslashes',$_POST);
    $_COOKIE = array_map('stripslashes',$_COOKIE);
}

// form submitted?
if (isset($_POST['submit'])) {

	// start with no errors
	$is_error = FALSE;
	
	// declare an array to hold any error messages
	$errs = NULL;

	// check $url and $thedate first as they are needed in each following case...
	
	// was the website/url submitted?
	if (isset($_POST['url'])) {
		// is website/url field blank?
		if (empty($_POST['url'])) {
			$is_error = TRUE;
			$errs[] = "Website is required.";
			// is the website/url valid?
		} elseif (!validSite($_POST['url'])) {
			$is_error = TRUE;
			$errs[] = "Website is invalid.";
		} else {
			// store website/url for later use
			$_SESSION['url'] = $_POST['url'];
			// make url safe for mysql
			$url = safe($_POST['url']);
		}
	}

	// was date submitted?
	if (isset($_POST['thedate'])) {
		// is date field blank?
		if (empty($_POST['thedate'])) {
			$is_error = TRUE;
			$errs[] = "Date is required.";
			// is the date valid?
		} elseif (!validDate($_POST['thedate'])) {
			$is_error = TRUE;
			$errs[] = "Date is invalid.";
		} else {
			// store date for later use
			$_SESSION['thedate'] = $_POST['thedate'];
			// make date safe for mysql
			$thedate = safe($_POST['thedate']);
		}	
	}

	// was status submitted?
	if (isset($_POST['status'])) {
		// if status is launch...
		if ($_POST['status'] == "launch") {
			// confirm required site description
			if (empty($_POST['sitedesc'])) {
				$is_error = TRUE;
				$errs[] = "Site Description is required.";
			} else {
				// make sitedesc safe for mysql
				$sitedesc = safe($_POST['sitedesc']);
			} 
			
			// was the first name field submitted?
			if (isset($_POST['fname'])) {
				// is first name field blank?
				if (empty($_POST['fname'])) {
					$is_error = TRUE;
					$errs[] = "First Name is required.";
					// is the first name valid?
				} elseif (!validName($_POST['fname'])) {
					$is_error = TRUE;
					$errs[] = "First Name is invalid.";
				} else {
					// store first name for later use if other errors
					$_SESSION['fname'] = $_POST['fname'];
					// make first name safe for mysql
					$fname = safe($_POST['fname']);
				}
			} 	
			
			// was the last name field submitted?
			if (isset($_POST['lname'])) {
				// is the last name field blank?
				if (empty($_POST['lname'])) {
					$is_error = TRUE;
					$errs[] = "Last Name is required.";
					// is the last name field valid?
				} elseif (!validName($_POST['lname'])) {
					$is_error = TRUE;
					$errs[] = "Last Name is invalid.";
				} else {
					// store last name for later use
					$_SESSION['lname'] = $_POST['lname'];
					// make last name safe for mysql
					$lname = safe($_POST['lname']);
				}	
			}	
	
			// was the email field submitted?
			if (isset($_POST['email'])) {
				// is the email field blank?
				if (empty($_POST['email'])) {
					$is_error = TRUE;
					$errs[] = "Email is required.";	
					// is the email address valid?
				} elseif (!validEmail($_POST['email'])) {
					$is_error = TRUE;
					$errs[] = "Email is invalid.";
				} else {
					// store email for later use
					$_SESSION['email'] = $_POST['email'];
					// make email safe for mysql
					$email = safe($_POST['email']);
				}
			}
			
			// was the phone field submitted?
			if (isset($_POST['phone'])) {
				// is phone field blank?
				if (empty($_POST['phone'])) {
					// ...then set to NULL for later INSERT
					$phone = NULL;
					// make sure the phone number is less than the 15 characters allowed in the mysql table column
				} elseif (strlen($_POST['phone']) > 15) { 
					$is_error = TRUE;
					$errs[] = "Phone number must be less than 15 characters.";
					// is the provided phone number valid?
				} elseif (!validPhone($_POST['phone'])) {
					$is_error = TRUE;
					$errs[] = "Phone is invalid.";
				} else {
					// store phone for later use
					$_SESSION['phone'] = $_POST['phone'];
					// make phone number safe for mysql				
					$phone = safe($_POST['phone']);
				}
			}	
		}
		
		// if status is relaunch...
		if ($_POST['status'] == "relaunch") {
			
			// are change notes blank?
			if (empty($_POST['notes'])) {
				// set notes to NULL for INSERT
				$notes = NULL;
			} else {
				// make notes safe for mysql
				$notes = safe($_POST['notes']);
			}	
		}
				
		// make sure there were no errors thus far...
		if ($is_error == FALSE) {
			// perform one of two database actions depending on status
			switch ($_POST['status']) {
				case "launch":
					// connect to database
					$db = mysql_connect('localhost', 'webuser', 'webpass');
					// make sure we connected...
					if (!$db) {
					    $errs[] = "Could not connect to database, please try again later.";
					}
					// select the exercise database
					mysql_select_db('exercise');
					
					// make sure submitted website does not already exist...
					$query = "SELECT SiteId FROM Sites WHERE SiteUrl='$url'";
					$result = mysql_query($query);
					if (mysql_num_rows($result) > 0) {
						$is_error = TRUE;
						$errs[] = "The website you submitted has already been launched!";
					} else {
						// make sure Customer is not already in database and adding a second website
						$query = "SELECT (CustId) FROM Customers WHERE CustEmail='$email'";
						$result = mysql_query($query, $db);
						if (mysql_num_rows($result) > 0) {
							// customer is adding a second website
							while ($row = mysql_fetch_assoc($result)) {
								$cust_id = $row["CustId"];
							}
							$query = "INSERT INTO Sites (CustId, SiteUrl, SiteDate, SiteDesc) VALUES ('$cust_id', '$url', '$thedate', '$sitedesc')";
							$result = mysql_query($query, $db);
							if (!$result) {
								$errs[] = "Could not add your additional website's launch date, please try again later.";
							} else {
								session_destroy();
								header('Location: thankyou.html');
							}
						} else {
							// go ahead and add customer
							$query = "INSERT INTO Customers (CustFn, CustLn, CustEmail, CustPhone) VALUES ('$fname', '$lname', '$email', '$phone')";
							$result = mysql_query($query, $db);
							if (!$result) {
								$errs[] = "Could not add your information, please try again later.";
							}
							
							// get Customer Id (PK of Customers) for use as FK in Sites 
							$cust_id = mysql_insert_id($db);

							$query = "INSERT INTO Sites (CustId, SiteUrl, SiteDate, SiteDesc) VALUES ('$cust_id', '$url', '$thedate', '$sitedesc')";
							$result = mysql_query($query, $db);
							if (!$result) {
								$errs[] = "Could not add your website launch date, please try again later.";
							} else {
								session_destroy();
								header('Location: thankyou.html');
							}
						}
					}
				break;
				
				case "relaunch":

					// connect to database
					$db = mysql_connect('localhost', 'webuser', 'webpass');
					// make sure we connected...
					if (!$db) {
					    $errs[] = "Could not connect to database, please try again later.";
					}
					// select the exercise database
					mysql_select_db('exercise');
					
					// confirm that provided website has already been launched...
					$query = "SELECT SiteId FROM Sites WHERE SiteUrl='$url'";
					$result = mysql_query($query, $db);
					if(!$result) {
						$errs[] = "Could not retrieve your website's information at this time, please try again later.";
					} 					
					if (mysql_num_rows($result) == 0) {
						$errs[] = "The website you entered has not even been launched yet!";
					} else {
						// we will need the SiteId later (PK of Sites table)
						while ($row = mysql_fetch_assoc($result)) {
							$site_id = $row["SiteId"];
						}	
						$query = "INSERT INTO Updates (Updated, UpdateNotes) VALUES ('$thedate', '$notes')";
						$result = mysql_query($query, $db);
						if (!$result) {
							$errs[] = "Could not add your website relaunch date and change notes, please try again later.";
						}
						
						// get UpdateId (PK of Updates table)
						$update_id = mysql_insert_id($db);

						// insert many-to-many/identifying relationship in pivot table: Sites_Updates
						$query = "INSERT INTO Sites_Updates (SiteId, UpdateId) VALUES ('$site_id', '$update_id')";
						$result = mysql_query($query, $db);
						if (!$result) {
							$errs[] = "Could not associate the relaunch date you provided with your existing website, please try again later.";
						}
						else {
							session_destroy();
							header('Location: thankyou.html');
						}
						mysql_free_result($result);
					}
				break;
			}
		}
	} else {
		// status is not set so something is fishy...
		die("Perhaps you are trying to inject post vars?");
	}
} 
/* form has not been submitted so display it with errors, if any... 
* and previously entered form fields, if any...
* these values have been stored as SESSION vars
*/
require("form.php");
?>