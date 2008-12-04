<?php

/*
Plugin Name: SimpleModal Contact Form (SMCF)
Plugin URI: http://www.ericmmartin.com/projects/smcf/
Description: A modal Ajax contact form built on the SimpleModal jQuery plugin. Once Activated, go to "Options" or "Settings" and select "SimpleModal Contact Form".
Version: 1.1.5
Author: Eric Martin
Author URI: http://www.ericmmartin.com
*/

/*	Copyright 2008 Eric Martin (eric@ericmmartin.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

$dir = preg_replace("/^.*[\/\\\]/", "", dirname(__FILE__));
define ("SMCF_DIR", "/wp-content/plugins/" . $dir);

class SimpleModalContactForm {

	function init() {
		if (function_exists("load_plugin_textdomain")) {
			load_plugin_textdomain("smcf", SMCF_DIR . "/lang/");
		}
	}

	function submenu() {
		if (function_exists("add_submenu_page")) {
			add_submenu_page("options-general.php", "SimpleModal Contact Form", "SimpleModal Contact Form", "manage_options", "smcf-config", array($this, "configPage"));
		}
	}

	function configPage() {
		$message = null;

		if ($_POST["action"] && $_POST["action"] == "update") {
			// save options
			$message = _e("Options saved.", "smcf");
			update_option("smcf_jquery_js", $_POST["smcf_jquery_js"]);
			update_option("smcf_simplemodal_js", $_POST["smcf_simplemodal_js"]); 
			update_option("smcf_link_url", $_POST["smcf_link_url"]); 
			update_option("smcf_link_title", $_POST["smcf_link_title"]);
			update_option("smcf_form_subject", $_POST["smcf_form_subject"]);
			update_option("smcf_form_cc_sender", $_POST["smcf_form_cc_sender"]);
			update_option("smcf_to_email", $_POST["smcf_to_email"]);
			update_option("smcf_subject", $_POST["smcf_subject"]);
			update_option("smcf_ip", $_POST["smcf_ip"]);
			update_option("smcf_ua", $_POST["smcf_ua"]);
		}

		$admin_email = get_option("admin_email");
		$smcf_to_email = get_option("smcf_to_email");
		// if a contact form to: email has not been set, use the admin_email
		$email = empty($smcf_to_email) ? $admin_email : $smcf_to_email;

		$smcf_form_title = get_option("smcf_form_title");
		$smcf_form_title = empty($smcf_form_title) ? __("Send me a message", "smcf") : $smcf_form_title;

		$smcf_link_url = get_option("smcf_link_url");
		$smcf_link_url = empty($smcf_link_url) ? "/contact" : $smcf_link_url;

		$smcf_link_title = get_option("smcf_link_title");
		$smcf_link_title = empty($smcf_link_title) ? "Contact" : $smcf_link_title;

		$smcf_subject = get_option("smcf_subject");
		$smcf_subject = empty($smcf_subject) ? "SimpleModal Contact Form" : $smcf_subject;

?>
<?php if (!empty($message)) : ?>
<div id="message" class="updated fade"><p><strong><?php echo $message ?></strong></p></div>
<?php endif; ?>
<div class="wrap">
<h2><?php _e("SimpleModal Contact Form Configuration", "smcf"); ?></h2>

<form id="smcf_form" method="post" action="options.php">
<?php wp_nonce_field("update-options") ?>
<p class="submit">
	<input type="submit" name="Submit" value="<?php _e("Update Options &raquo;", "smcf"); ?>" />
</p>
<table class="optiontable">
	<tr valign="top">
		<th scope="row"><?php _e("JavaScript:", "smcf"); ?></th>
		<td>
			<label for="smcf_jquery_js">
			<input name="smcf_jquery_js" type="checkbox" id="smcf_jquery_js" value="1" <?php checked("1", get_option("smcf_jquery_js")); ?> />
			<?php _e("Include jQuery", "smcf"); ?></label>
			<p><?php _e("Select the option above if you do not already have the jQuery JavaScript file included in your site. This plugin requires jQuery 1.2 or greater.", "smcf"); ?></p>
			<label for="smcf_simplemodal_js"><input name="smcf_simplemodal_js" type="checkbox" id="smcf_simplemodal_js" value="1" <?php checked("1", get_option("smcf_simplemodal_js")); ?> /> <?php _e("Include SimpleModal", "smcf"); ?></label>
			<p><?php _e("Select the option above if you do not already have the SimpleModal JavaScript file included in your site.", "smcf"); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e("Contact Link URL:", "smcf"); ?></th>
		<td><input type="text" id="smcf_link_url" name="smcf_link_url" value="<?php echo $smcf_link_url; ?>" size="40" class="code"/>
		<p><?php _e("The URL for the contact link to your contact form page. This is the URL that non-JavaScript users will be taken to.", "smcf"); ?></p></td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e("Contact Link Title:", "smcf"); ?></th>
		<td><input type="text" id="smcf_link_title" name="smcf_link_title" value="<?php echo $smcf_link_title; ?>" size="40" class="code"/>
		<p><?php _e("The title for the contact link to your contact form page.", "smcf"); ?></p></td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e("Form Title:", "smcf"); ?></th>
		<td><input type="text" id="smcf_form_title" name="smcf_form_title" value="<?php echo $smcf_form_title; ?>" size="40" class="code"/>
		<p><?php _e("Enter the title that you want displayed on your contact form.", "smcf"); ?></p></td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e("Form To:", "smcf"); ?></th>
		<td><input type="text" id="smcf_to_email" name="smcf_to_email" value="<?php echo $email; ?>" size="40" class="code"/>
		<p><?php _e("Enter the email address that you want all contact emails to be sent to. The default is your WordPress administrator email.", "smcf"); ?></p></td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e("Form Subject:", "smcf"); ?></th>
		<td><input type="text" id="smcf_subject" name="smcf_subject" value="<?php echo $smcf_subject; ?>" size="40" class="code"/>
		<p><?php _e("Enter the default subject that you want all contact emails to be sent with. This value will be used if you do not enable the subject field or if you do enable the subject field, but the user does not enter a subject.", "smcf"); ?></p></td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e("Form Elements:", "smcf"); ?></th>
		<td>
			<label for="smcf_form_subject">
			<input name="smcf_form_subject" type="checkbox" id="smcf_form_subject" value="1" <?php checked("1", get_option("smcf_form_subject")); ?> />
			<?php _e("Include Subject Field", "smcf"); ?></label>
			<p><?php _e("Select the option above if you would like the contact form to include a subject field. The field will not be required and if not entered, will use the Default Subject value from below.", "smcf"); ?></p>
			<label for="smcf_form_cc_sender"><input name="smcf_form_cc_sender" type="checkbox" id="smcf_form_cc_sender" value="1" <?php checked("1", get_option("smcf_form_cc_sender")); ?> /> <?php _e("Include 'Send me a copy' Option", "smcf"); ?></label>
			<p><?php _e("Select the option above if you would like the contact form to include a 'Send me a copy' option for the sender.", "smcf"); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e("Extra:", "smcf"); ?></th>
		<td>
			<label for="smcf_ip">
			<input name="smcf_ip" type="checkbox" id="smcf_ip" value="1" <?php checked("1", get_option("smcf_ip")); ?> />
			<?php _e("Include the users IP Address", "smcf"); ?></label><br />
			<label for="smcf_ua"><input name="smcf_ua" type="checkbox" id="smcf_ua" value="1" <?php checked("1", get_option("smcf_ua")); ?> /> <?php _e("Include the users User Agent", "smcf"); ?>
			<p><?php _e("Select the options above to included extra user information in the contact email.<br/><b>Note:</b> These values will be sent to the sender if 'Send me a copy' is enabled and checked.", "smcf"); ?></p></label>
		</td>
	</tr>
</table>
<p class="submit">
	<input type="submit" name="submit" value="<?php _e("Update Options &raquo;", "smcf"); ?>" />
</p>
<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="smcf_jquery_js,smcf_simplemodal_js,smcf_link_url,smcf_link_title,smcf_form_title,smcf_form_subject,smcf_form_cc_sender,smcf_to_email,smcf_subject,smcf_ip,smcf_ua" />
</form>

</div>
<?php
	}

	function head() {
		// add javascript files
		if (function_exists("wp_enqueue_script")) {
			if (get_option("smcf_jquery_js") == 1) {
				wp_enqueue_script("smcf_jquery", get_option("siteurl") . SMCF_DIR . "/js/jquery.js", null, null);
			}
			wp_print_scripts();
		}

		// add styling
		echo "<link type='text/css' rel='stylesheet' href='" . get_bloginfo("wpurl") . SMCF_DIR . "/css/smcf.css' media='screen'/>";
	}

	function footer() {
		// add javascript files
		if (function_exists("wp_enqueue_script")) {
			if (get_option("smcf_simplemodal_js") == 1) {
				wp_enqueue_script("smcf_simplemodal", get_option("siteurl") . SMCF_DIR . "/js/jquery.simplemodal.js", null, null);
			}
			wp_enqueue_script("smcf", get_option("siteurl") . SMCF_DIR . "/js/smcf.js");
			wp_print_scripts();
		}

		$title = get_option("smcf_form_title");
		$title = empty($title) ? __("Send me a message", "smcf") : $title;

		$url = parse_url(get_bloginfo("wpurl") . SMCF_DIR);

		$output = "
	<script type='text/javascript'>
		var smcf_messages = {
			loading: '" . __("Loading...", "smcf") . "',
			sending: '" . __("Sending...", "smcf") . "',
			thankyou: '" . __("Thank You!", "smcf") . "',
			error: '" . __("Uh oh...", "smcf") . "',
			goodbye: '" . __("Goodbye...", "smcf") . "',
			namerequired: '" . __("Name is required.", "smcf") . "',
			emailrequired: '" . __("Email is required.", "smcf") . "',
			emailinvalid: '" . __("Email is invalid.", "smcf") . "',
			messagerequired: '" . __("Message is required.", "smcf") . "'
		}
	</script>";

		// create the contact form HTML
		$output .= "<div id='smcf-content' style='display:none'>
	<a href='#' title='Close' class='modalCloseX simplemodal-close'>x</a>
	<div class='smcf-top'></div>
	<div class='smcf-content'>
		<h1 class='smcf-title'>" . $title . "</h1>
		<div class='smcf-loading' style='display:none'></div>
		<div class='smcf-message' style='display:none'></div>
		<form action='" . $url["path"] . "/smcf_data.php' style='display:none'>
			<label for='smcf-name'>*" . __("Name", "smcf") . ":</label>
			<input type='text' id='smcf-name' class='smcf-input' name='name' value='' tabindex='1001' />
			<label for='smcf-email'>*" . __("Email", "smcf") . ":</label>
			<input type='text' id='smcf-email' class='smcf-input' name='email' value='' tabindex='1002' />";

		if (get_option("smcf_form_subject") == 1) {
			$output .= "<label for='smcf-subject'>" . __("Subject", "smcf") . ":</label>
			<input type='text' id='smcf-subject' class='smcf-input' name='subject' value='' tabindex='1003' />";
		}

		$output .= "<label for='smcf-message'>*" . __("Message", "smcf") . ":</label>
			<textarea id='smcf-message' class='smcf-input' name='message' cols='40' rows='4' tabindex='1004'></textarea><br/>";

		if (get_option("smcf_form_cc_sender") == 1) {
			$output .= "<label>&nbsp;</label>
			<input type='checkbox' id='smcf-cc' name='cc' value='1' tabindex='1005' /> <span class='smcf-cc'>" . __("Send me a copy", "smcf") . "</span>
			<br/>";
		}

		$output .= "<label>&nbsp;</label>
			<button type='submit' class='smcf-button smcf-send' tabindex='1006'>" . __("Send", "smcf") . "</button>
			<button type='submit' class='smcf-button smcf-cancel simplemodal-close' tabindex='1007'>" . __("Cancel", "smcf") . "</button>
			<input type='hidden' name='token' value='" . $this->token() . "'/>
			<br/>
		</form>
	</div>
	<div class='smcf-bottom'><a href='http://www.ericmmartin.com/projects/smcf/'>" . __('Powered by', 'smcf') . " SimpleModal Contact Form</a></div>
</div>";

		echo $output;
	}

	function token() {
		$admin_email = get_option("admin_email");
		return md5("smcf-" . $admin_email . date("WY"));
	}
}

$smcf = new SimpleModalContactForm();

// Initialize textdomain - L10n
add_action("init", array($smcf, "init"));

// Place a 'SimpleModal Contact Form' sub menu item on the Options page
add_action("admin_menu", array($smcf, "submenu"));

// Include SimpleModal Contact Form code to a page
add_action("wp_head", array($smcf, "head"));
add_action("wp_footer", array($smcf, "footer"), 10);

/*
 * Public function to create a link for the contact form
 * This can be called from any file in your theme
 */
function smcf() {
	$url = get_option("smcf_link_url");
	$url = empty($url) ? "/contact" : $url;

	$title = get_option("smcf_link_title");
	$title = empty($title) ? __("Contact", "smcf") : $title;

	echo "<a href='$url'class='smcf-link'>$title</a>";
}

?>