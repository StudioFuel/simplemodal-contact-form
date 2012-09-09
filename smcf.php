<?php

/*
Plugin Name: SimpleModal Contact Form (SMCF)
Plugin URI: http://www.ericmmartin.com/projects/smcf/
Description: A modal Ajax contact form built on the SimpleModal jQuery plugin. Once Activated, go to "Options" or "Settings" and select "SimpleModal Contact Form".
Version: 1.2.9
Author: Eric Martin
Author URI: http://www.ericmmartin.com
*/

/*	Copyright 2012 Eric Martin (eric@ericmmartin.com)

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

$smcf_dir = preg_replace("/^.*[\/\\\]/", "", dirname(__FILE__));
define("SMCF_DIR", "/wp-content/plugins/" . $smcf_dir);

class SimpleModalContactForm {

	var $version = "1.2.9";

	function init() {
		load_plugin_textdomain("smcf", false, SMCF_DIR . "/lang/");

		if (!is_admin()) {
			// add javascript files
			wp_enqueue_script("jquery-simplemodal", get_option("siteurl") . SMCF_DIR . "/js/jquery.simplemodal.js", array("jquery"), "1.4.3", true);
			wp_enqueue_script("smcf", get_option("siteurl") . SMCF_DIR . "/js/smcf.js", array("jquery-simplemodal"), $this->version, true);

			// add styling
			wp_enqueue_style("smcf", get_option("siteurl") . SMCF_DIR . "/css/smcf.css", false, $this->version, "screen");
		}
	}

	function submenu() {
		if (function_exists("add_submenu_page")) {
			add_submenu_page("options-general.php", "SimpleModal Contact Form", "SimpleModal Contact Form", "manage_options", "smcf-config", array($this, "config_page"));
		}
	}

	function config_page() {
		$message = null;

		if (isset($_POST["action"]) && $_POST["action"] == "update") {
			// save options
			$message = _e("Options saved.", "smcf");
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
<table class="form-table">
	<tr valign="top">
		<th scope="row"><?php _e("Contact Link URL:", "smcf"); ?></th>
		<td><input type="text" id="smcf_link_url" name="smcf_link_url" value="<?php echo $smcf_link_url; ?>" size="40" class="code"/>
		<p><?php _e("The URL for the contact link to your contact form page. This is the URL that non-JavaScript users will be taken to.", "smcf"); ?></p></td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e("Contact Link Title:", "smcf"); ?></th>
		<td><input type="text" id="smcf_link_title" name="smcf_link_title" value="<?php echo $smcf_link_title; ?>" size="40" class="code"/>
		<p><?php _e("The title for the contact link to your contact form page. If you are using wp_page_menu() or wp_list_pages() to build menus dynamically, SMCF will look for a link with this title.", "smcf"); ?></p></td>
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
	<input type="submit" name="submit" value="<?php _e("Save Changes", "smcf"); ?>" />
</p>
<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="smcf_link_url,smcf_link_title,smcf_form_title,smcf_form_subject,smcf_form_cc_sender,smcf_to_email,smcf_subject,smcf_ip,smcf_ua" />
</form>

</div>
<?php
	}

	function head() {
		/*
		 * WordPress 2.6.5 and below does not include the wp_print_styles filter in wp_head...
		 * So, we need to call it here, just in case
		 */
		if (function_exists("wp_print_styles")) {
			wp_print_styles("smcf");
		}
	}

	function footer() {
		$title = get_option("smcf_form_title");
		$title = empty($title) ? __("Send me a message", "smcf") : $title;

		$url = parse_url(get_bloginfo("wpurl") . SMCF_DIR);

		$output = "
	<script type='text/javascript'>
		var smcf_messages = {
			loading: '" . addslashes(__("Loading...", "smcf")) . "',
			sending: '" . addslashes(__("Sending...", "smcf")) . "',
			thankyou: '" . addslashes(__("Thank You!", "smcf")) . "',
			error: '" . addslashes(__("Uh oh...", "smcf")) . "',
			goodbye: '" . addslashes(__("Goodbye...", "smcf")) . "',
			name: '" . addslashes(__("Name", "smcf")) . "',
			email: '" . addslashes(__("Email", "smcf")) . "',
			emailinvalid: '" . addslashes(__("Email is invalid.", "smcf")) . "',
			message: '" . addslashes(__("Message", "smcf")) . "',
			and: '" . addslashes(__("and", "smcf")) . "',
			is: '" . addslashes(__("is", "smcf")) . "',
			are: '" . addslashes(__("are", "smcf")) . "',
			required: '" . addslashes(__("required.", "smcf")) . "'
		}
	</script>";

		// create the contact form HTML
		$output .= "<div id='smcf-content' style='display:none'>
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
	<div class='smcf-bottom'>&nbsp;</div>
</div>";

		echo $output;
	}

	function page_menu_list($page) {
		$title = get_option("smcf_link_title");
		$needle = ">$title<";

		if (strstr($page, ">$title<")) {
			$page = preg_replace("/>$title</", " class='smcf-link'>$title<", $page);
		}

		return $page;
	}

	function token() {
		$admin_email = get_option("admin_email");
		return md5("smcf-" . $admin_email . date("WY"));
	}
}

$smcf = new SimpleModalContactForm();

// Initialize textdomain - L10n and load scripts
add_action("init", array($smcf, "init"));

// Place a 'SimpleModal Contact Form' sub menu item on the Options page
add_action("admin_menu", array($smcf, "submenu"));

// Include SimpleModal Contact Form code to a page
add_action("wp_head", array($smcf, "head"));
add_action("wp_footer", array($smcf, "footer"), 10);

// Look for a contact link in the page menus/list
add_filter('wp_page_menu', array($smcf, "page_menu_list"));
add_filter('wp_list_pages', array($smcf, "page_menu_list"));
add_filter('wp_nav_menu_items', array($smcf, "page_menu_list"));

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