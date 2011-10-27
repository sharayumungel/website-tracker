<?php
/* removes or encodes unwanted and dangerous characters from a string prior to mysql insertion
 * @param string $str 
 * @return string safe for mysql insert
 */
function safe($str) {	
    if(get_magic_quotes_gpc()) {
        $str = stripslashes($str);
    } 
	$str = strip_tags($str);
	$str = htmlentities($str, ENT_QUOTES, "UTF-8");
	$str = mysql_real_escape_string($str);
	return $str;
}

/* validates a reasonable first/last name including spaces, hyphens and apostrophe
 * @param string $str
 * @return boolean
 */
function validName($str) {
	$regex = "/^[A-Za-z '-]/";
	if ( preg_match( $regex, $str ) ) {
    	return true;
	} else {
		return false;
	}
}

/*
 * validates an email address using built in PHP filter, acceptable for many cases
 * @param string $str
 * @return boolean
 */
function validEmail($str) {
	if (filter_var($str, FILTER_VALIDATE_EMAIL)) {
    	return true;
	} else {
    	return false;
	}
}

/*
 * validates a phone number...
 * @param string $str
 * @return boolean
 * the following values PASS: 
 * 1-234-567-8901, 1-234-567-8901 x1234, 1-234-567-8901 ext1234, 1 (234) 567-8901, 1.234.567.8901
 * the following values FAIL:
 * 1/234/567/8901, 12345678901
 */
function validPhone($str) {
	if (preg_match('/\(?\d{3}\)?[-\s.]?\d{3}[-\s.]\d{4}/x', $str)) {
	    return true;
	} else {
	    return false;
	}
}

/*
 * validates a full website/domain address
 * @param string $str
 * @return boolean
 */
function validSite($str) {
	if (preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $str)) {
	    return true;
	} else {
	    return false;
	}
}

/*
 * validates a mysql date type 0000-00-00
 * @param string $str
 * @return boolean
 */
function validDate($str) {
	if (preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $str)){
		return true;
	} else {
		return false;
	}
}
?>