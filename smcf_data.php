<?php

require_once("../../../wp-config.php");

$dir = preg_replace("/^.*[\/\\\]/", "", dirname(__FILE__));
define ("SMCF_DIR", "/wp-content/plugins/" . $dir);

// process
$action = isset($_POST["action"]) ? $_POST["action"] : "";
if ($action == "send") {
	// send the email
	$name = isset($_POST["name"]) ? $_POST["name"] : "";
	$email = isset($_POST["email"]) ? $_POST["email"] : "";
	$subject = isset($_POST["subject"]) ? $_POST["subject"] : "";
	$message = isset($_POST["message"]) ? $_POST["message"] : "";
	$cc = isset($_POST["cc"]) ? $_POST["cc"] : "";
	$token = isset($_POST["token"]) ? $_POST["token"] : "";

	// make sure the token matches
	if ($token == SimpleModalContactForm::token()) {
		sendEmail($name, $email, $subject, $message, $cc);
		_e("Your message was successfully sent.", "smcf");
	}
	else {
		_e("Unfortunately, your message could not be verified.", "smcf");
	}
}

// validate and send email
function sendEmail($name, $email, $subject, $message, $cc) {
	$to = get_option("smcf_to_email");

	// filter name and subject
	$name = filter($name);
	$subject = empty($subject) ? get_option("smcf_subject") : filter($subject);

	// remove escaping done by magic_quotes
	$message = stripslashes($message);

	// filter and validate email
	$email = filter($email);
	if (!validateEmail($email)) {
		$subject .= " - invalid email";
		$message .= "\n\nBad email: $email";
		$email = $to;
		$cc = 0; // do not CC "sender"
	}

	// Add additional info to the message
	if (get_option("smcf_ip")) {
		$message .= "\n\nIP: " . $_SERVER["REMOTE_ADDR"];
	}
	if (get_option("smcf_ua")) {
		$message .= "\n\nUSER AGENT: " . $_SERVER["HTTP_USER_AGENT"];
	}

	// Set and wordwrap message body
	$body = "From: $name\n\n";
	$body .= "Message: $message";
	$body = wordwrap($body); // default is 75 characters

	// Build header
	$headers = "From: $email\n";
	if ($cc == 1) {
		$headers .= "Cc: $email\n";
	}
	$headers .= "X-Mailer: PHP/SimpleModalContactForm";

	// UTF-8
	if (function_exists('mb_encode_mimeheader')) {
		$subject = mb_encode_mimeheader($subject, "UTF-8", "B", "\n");
	}
	else {
		// you need to enable mb_encode_mimeheader or risk 
		// getting emails that are not UTF-8 encoded
	}
	$headers .= "MIME-Version: 1.0\n";
	$headers .= "Content-type: text/plain; charset=utf-8\n";
	$headers .= "Content-Transfer-Encoding: quoted-printable\n";

	// Send email - suppress errors
	@mail($to, $subject, $body, $headers) or 
		die(__("Unfortunately, a server issue prevented delivery of your message.", "smcf"));
}

// Remove any un-safe values to prevent email injection
function filter($value) {
	$pattern = array("/\n/", "/\r/", "/content-type:/i", "/to:/i", "/from:/i", "/cc:/i");
	$value = preg_replace($pattern, "", $value);
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
	if (preg_match("/(\.{2,})/", $email))
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
	if (preg_match("/(^\.|\.$)/", $local) || preg_match("/(^\.|\.$)/", $domain))
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