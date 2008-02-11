<?php

/*
Plugin Name: SimpleModal Contact Form (SMCF)
Plugin URI: http://www.ericmmartin.com/projects/smcf/
Description: A modal Ajax contact form built on the SimpleModal jQuery plugin. Once Activated, go to "Options" and select "SimpleModal Contact Form".
Version: 1.0
Author: Eric Martin
Author URI: http://www.ericmmartin.com
*/

/*  Copyright 2007  Eric Martin (eric@ericmmartin.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Public function to create a link for the contact form
// This can be called from any file in your theme
function smcf() {
	echo "<a href='#' class='smcf_link'>Contact</a>";
}

$dir = preg_replace('/^.*[\/\\\]/', '', dirname(__FILE__));
define ("SMCF_DIR", "/wp-content/plugins/" . $dir);

class SimpleModalContactForm {

	var $version = '1.0';

	function submenu() {
		if (function_exists('add_submenu_page')) {
			add_submenu_page('options-general.php', 'SimpleModal Contact Form', 'SimpleModal Contact Form', 'manage_options', 'smcf-config', array($this, 'configPage'));
		}
	}

	function configPage() {
		$message = null;

		if ($_POST['action'] && $_POST['action'] == 'update') {
			// save options
			$message = _e('Options saved.');
			update_option('smcf_jquery_js', $_POST['smcf_jquery_js']);
			update_option('smcf_simplemodal_js', $_POST['smcf_simplemodal_js']);
			update_option('smcf_to_email', $_POST['smcf_to_email']);
			update_option('smcf_subject', $_POST['smcf_subject']);
			update_option('smcf_ip', $_POST['smcf_ip']);
			update_option('smcf_ua', $_POST['smcf_ua']);
		}

		$admin_email = get_option('admin_email');
		$smcf_to_email = get_option('smcf_to_email');
		// if a contact form to: email has not been set, use the admin_email
		$email = !empty($smcf_to_email) ? $smcf_to_email : $admin_email;
?>
<?php if (!empty($message)) : ?>
<div id='message' class='updated fade'><p><strong><?php echo $message ?></strong></p></div>
<?php endif; ?>
<div class='wrap'>
<h2><?php _e('SimpleModal Contact Form Configuration'); ?></h2>

<form id='smcf_form' method='post' action='options.php'>
<?php wp_nonce_field('update-options') ?>
<p class='submit'>
	<input type='submit' name='Submit' value='<?php _e('Update Options &raquo;') ?>' />
</p>
<table class="optiontable">
	<tr valign="top">
		<th scope="row"><?php _e('JavaScript:'); ?></th>
		<td>
			<label for="smcf_jquery_js">
			<input name="smcf_jquery_js" type="checkbox" id="smcf_jquery_js" value="1" <?php checked('1', get_option('smcf_jquery_js')); ?> />
			<?php _e('Include jQuery') ?></label>
			<p><?php _e('Select the option above if you do not already have the jQueryJavaScript file included in your site. This plugin requires jQuery 1.2 or greater.'); ?></p>
			<label for="smcf_simplemodal_js"><input name="smcf_simplemodal_js" type="checkbox" id="smcf_simplemodal_js" value="1" <?php checked('1', get_option('smcf_simplemodal_js')); ?> /> <?php _e('Include SimpleModal') ?></label>
			<p><?php _e('Select the option above if you do not already have the SimpleModal JavaScript file included in your site.'); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e('To:'); ?></th>
		<td><input type='text' id='smcf_to_email' name='smcf_to_email' value='<?php echo $email; ?>' size='40' class='code'/>
		<p><?php _e('Enter the email address that you want all contact emails to be sent to. The default is your WordPress administrator email.'); ?></p></td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e('Subject:'); ?></th>
		<td><input type='text' id='smcf_subject' name='smcf_subject' value='<?php echo get_option('smcf_subject'); ?>' size='40' class='code'/>
		<p><?php _e('Enter the subject that you want all contact emails to be sent with.'); ?></p></td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e('Extra:') ?></th>
		<td>
			<label for="smcf_ip">
			<input name="smcf_ip" type="checkbox" id="smcf_ip" value="1" <?php checked('1', get_option('smcf_ip')); ?> />
			<?php _e('Include the users IP Address') ?></label><br />
			<label for="smcf_ua"><input name="smcf_ua" type="checkbox" id="smcf_ua" value="1" <?php checked('1', get_option('smcf_ua')); ?> /> <?php _e('Include the users User Agent') ?>
			<p><?php _e('Select the options above to included extra user information in the contact email.'); ?></p></label>
		</td>
	</tr>
</table>
<input type='hidden' name='action' value='update' />
<input type='hidden' name='page_options' value='smcf_jquery_js,smcf_simplemodal_js,smcf_to_email,smcf_subject,smcf_ip,smcf_ua' />
</form>

</div>
<?php
	}

	function head() {
		// add javascript files
		if (function_exists('wp_enqueue_script')) {
			if (get_option('smcf_jquery_js') == 1) {
				wp_enqueue_script('smcf_jquery', get_option('siteurl') . SMCF_DIR . '/js/jquery.js', null, null);
			}
			if (get_option('smcf_simplemodal_js') == 1) {
				wp_enqueue_script('smcf_simplemodal', get_option('siteurl') . SMCF_DIR . '/js/jquery.simplemodal.js', null, null);
			}
			wp_enqueue_script('smcf', get_option('siteurl') . SMCF_DIR . '/js/smcf_javascript.php', null, null);
			wp_print_scripts();
		}

		// add styling
		echo "<link type='text/css' rel='stylesheet' href='" . get_bloginfo('wpurl') . SMCF_DIR . "/css/smcf.css' media='screen'/>
<!--[if lt IE 7]>
<link type='text/css' rel='stylesheet' href='" . get_bloginfo('wpurl') . SMCF_DIR . "/css/smcf-ie.css' media='screen' />
<![endif]-->";
	}

	function footer() {
		// Send back the contact form HTML
		echo "<div id='smcf_content' style='display:none'>
	<a href='#' title='Close' class='modalCloseX modalClose'>x</a>
	<div class='top'></div>
	<div class='content'>
		<h1 class='title'>Send a message:</h1>
		<div class='loading' style='display:none'></div>
		<div class='message' style='display:none'></div>
		<form action='#'>
			<label for='name'>Name:</label>
			<input type='text' id='name' name='name' tabindex='1001' />
			<label for='email'>Email:</label>
			<input type='text' id='email' name='email' tabindex='1002' />
			<label for='message'>Message:</label>
			<textarea id='message' name='message' cols='40' rows='4' tabindex='1003'></textarea>
			<br/>
			<label>&nbsp;</label>
			<button type='submit' class='send' tabindex='1004'></button>
			<button type='submit' class='cancel modalClose' tabindex='1005'></button>
			<br/>
		</form>
	</div>
	<div class='bottom'><a href='http://www.ericmmartin.com/projects/smcf/'>Powered by SimpleModal Contact Form</a></div>
</div>";
	}

}

$smcf = new SimpleModalContactForm();

// Place a 'SimpleModal Contact Form' sub menu item on the Options page
add_action('admin_menu', array($smcf, 'submenu'));

// Include SimpleModal Contact Form code to a page
add_action('wp_head', array($smcf, 'head'));
add_action('wp_footer', array($smcf, 'footer'));

?>