<?php

require_once('../../../wp-config.php');

$dir = preg_replace('/^.*[\/\\\]/', '', dirname(__FILE__));
define ("SMCF_DIR", "/wp-content/plugins/" . $dir);

// Process
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
if (empty($action)) {
	// do nothing
}
else if ($action == 'send') {
	// Send the email
	$name = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
	$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
	$message = isset($_REQUEST['message']) ? $_REQUEST['message'] : '';

	sendEmail($name, $email, $message);
	echo "Message successfully sent.";
}

// Validate and send email
function sendEmail($name, $email, $message) {
	$to = get_option('smcf_to_email');
	$subject = get_option('smcf_subject');

	// Filter name
	$name = filter($name);

	// Filter and validate email
	$email = filter($email);
	if (!validateEmail($email)) {
		$subject .= " - invalid email";
		$message .= "\n\nBad email: $email";
		$email = $to;
	}

	// Add additional info to the message
	if (get_option('smcf_ip')) {
		$message .= "\n\nIP: " . $_SERVER['REMOTE_ADDR'];
	}
	if (get_option('smcf_ua')) {
		$message .= "\n\nUSER AGENT: " . $_SERVER['HTTP_USER_AGENT'];
	}

	// Set and wordwrap message body
	$body = "From: $name\n\n";
	$body .= "Message: $message";
	$body = wordwrap($body, 70);

	// Build header
	$header = "From: $email\n";
	$header .= "X-Mailer: PHP/SimpleModalContactForm";

	// Send email - suppress errors
	@mail($to, $subject, $body, $header) or 
		die('Unfortunately, your message could not be delivered.');
}

// Remove any un-safe values to prevent email injection
function filter($value) {
	$pattern = array("/\n/","/\r/","/content-type:/i","/to:/i", "/from:/i", "/cc:/i");
	$value = preg_replace($pattern, '', $value);
	return $value;
}

// Validate email address format in case client-side validation "fails"
function validateEmail($email) {
	$at = strrpos($email, "@");

	// Make sure the at (@) sybmol exists and  
	// it is not the first or last character
	if ($at && ($at < 1 || ($at + 1) == strlen($email)))
		return false;

	// Make sure there aren't multiple periods together
	if (preg_match('/(\.{2,})/', $email))
		return false;

	// Break up the local and domain portions
	$local = substr($email, 0, $at);
	$domain = substr($email, $at + 1);


	// Check lengths
	$locLen = strlen($local);
	$domLen = strlen($domain);
	if ($locLen < 1 || $locLen > 64 || $domLen < 4 || $domLen > 255)
		return false;

	// Make sure local and domain don't start with or end with a period
	if (preg_match('/(^\.|\.$)/', $local) || preg_match('/(^\.|\.$)/', $domain))
		return false;

	// Check for quoted-string addresses
	// Since almost anything is allowed in a quoted-string address,
	// we're just going to let them go through
	if (!preg_match('/^"(.+)"$/', $local)) {
		// It's a dot-string address...check for valid characters
		if (!preg_match('/^[-a-zA-Z0-9!#$%*\/?|^{}`~&\'+=_\.]*$/', $local))
			return false;
	}

	// Make sure domain contains only valid characters and at least one period
	if (!preg_match('/^[-a-zA-Z0-9\.]*$/', $domain) || !strpos($domain, "."))
		return false;	

	return true;
}

exit;

?>