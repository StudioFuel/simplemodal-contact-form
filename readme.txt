=== Plugin Name ===
Contributors: emartin24
Tags: contact, contact form, modal, ajax, plugin, jquery, javascript
Requires at least: ?
Tested up to: 2.5
Stable tag: 1.1.3

SimpleModal Contact Form (SMCF) is an Ajax powered modal contact form. It utilizes the jQuery JavaScript library and the SimpleModal jQuery plugin.

== Description ==

SimpleModal Contact Form (SMCF) is an Ajax powered modal contact form. 

It utilizes the jQuery JavaScript library and the SimpleModal jQuery plugin.

SMCF has options to include the jQuery and SimpleModal files as well as whether to include certain contact form elements, like a Subject field and "Send me a copy" option for the sender.

== Installation ==

*Requirements:*

* PHP `mail()`
* jQuery 1.2 or greater

*Steps:*

1. Unzip SMCF archive and put all files/folders into your "plugins" folder (`/wp-content/plugins/`). You should end up with `/wp-content/plugins/simplemodal-contact-form-smcf/`.
2. Activate the plugin (WordPress Admin > Plugins > Click "Activate" for "SimpleModal Contact Form (SMCF)")
3. Set the desired options (WordPress Admin >  Options (Settings in WordPress 2.5) > SimpleModal Contact Form)
4. Enable SMCF on your site! SMCF works by looking for links (HTML A elements) with a class of "smcf-link". See below:

You have 2 options:

a) Add the "smcf-link" to your existing contact link:

	<a href="/contact" class="smcf-link">Contact</a>
	
b) Use the "smcf()" function in one of your theme files (`sidebar.php`, for example):

	<?php if (function_exists('smcf')) : ?>
		<?php smcf(); ?>
	<?php endif; ?>

== Frequently Asked Questions ==

= How do I change the output of the smcf() function? =

You can modify the link URL and link title on the SMCF WordPress options page.

= How do I change the elements in the contact form? =

* Go to Plugins > Plugin Editor > SimpleModal Contact Form (SMCF).
* Look for `function footer()`
* Edit the HTML in the `$form` variable. 
* *Note*: Be careful not to enter anything that would cause PHP errors. =)

= How do I change the styling of the contact form? =

Open `simplemodal-contact-form-smcf/css/smcf.css` and modify the CSS to fit your needs. Put IE6 specific values in `simplemodal-contact-form-smcf/css/smcf-ie.css`.

*Note*: There are some browser specific CSS values that are set in the JavaScript (`simplemodal-contact-form-smcf/js/smcf_javascript.js`).

= What does the "Unfortunately, your message could not be verified." message mean? =

Starting in SMCF v1.1, there is a new "security" feature that attempts to ward off unwanted spam. A "token" is created and placed in the contact form. When the form is submitted, the token is verified and if the token does not exist or fails verification, the user will see the "Unfortunately, your message could not be verified." message. If this is happening for legitimate users, please let me know!

= I've followed all of the instructions, but it doesn't work...what gives? =

Here are some troubleshooting steps to follow:

* Use Firefox and Firebug or if you must use IE, turn on JavaScript debugging and install the Developer Toolbar (search Google if you are unsure of how to do any of these)
* Make sure the footer.php file in your theme, contains `<?php wp_footer(); ?>`
* Make sure all of the JavaScript files are loaded
* Make sure no other plugins are loading older version of jQuery (SMCF requires jQuery 1.2 or greater)
* Make sure PHP's `mail()` is installed and working. (You can also open `smcf_data.php` and remove the @ from `@mail`. That will echo out any errors that it throws while trying to send mail.)

== Screenshots ==

1. A contact form with the Subject and "Send me a copy" options enabled.
2. The validation messages displayed for required/invalid fields.
3. The contact form in the process of sending.
4. A successful message.
5. The WordPress Admin options for SMCF.

== Arbitrary section ==

* Version 1.0
	* Initial release
* Version 1.0.1
	* Bug fix - removed the hard-coded plugins/smcf path. It is now dynamically determined.
* Version 1.1
	* Fixed image pre-loading to actually pre-load ;)
	* Added new effects on form open and close
	* Added a security feature
	* Added optional subject and cc sender form elements
	* Added common classes to form elements
	* Renamed all classes and ID's to prevent collisions
	* Added WordPress translation ability on text elements (__() and _e() functions)
	* Upgraded to SimpleModal v1.1.1 and jQuery 1.2.3
	* Moved SimpleModal and SMCF JavaScript file loading to the footer
* Version 1.1.1
	* Added UTF-8 support
	* Modified CSS
	* Fixed URL to smcf_data.php in smcf_javascript.php
	* Changed Ajax function in smcf_javascript.php
	* Added back recognition for .smcf_link for previous versions
* Version 1.1.2
	* Fixed parse_url function in smcf_javascript.php to support PHP < 5.1.2
* Version 1.1.3
	* Changed smcf_javascript.php to smcf.js - removed php functions
	* Changed form action URL to point to smcf_data.php
	* Changed verificaton method to prevent false failures
	* Changed failure messages to indicate type of failure (verification/server failure)
	* Modified CSS