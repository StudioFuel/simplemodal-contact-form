=== Plugin Name ===
Contributors: emartin24
Donate link: http://www.ericmmartin.com/donate/
Tags: contact, contact form, modal, ajax, plugin, jquery, javascript, mail, email
Requires at least: ?
Tested up to: 2.8.x
Stable tag: 1.2.4

SimpleModal Contact Form (SMCF) is an Ajax powered modal contact form. It utilizes the jQuery JavaScript library and the SimpleModal jQuery plugin.

== Description ==

SimpleModal Contact Form (SMCF) is an Ajax powered modal contact form. 

It utilizes the jQuery JavaScript library and the SimpleModal jQuery plugin.

SMCF has options to include certain contact form elements, like a Subject field and "Send me a copy" option for the sender.

*Translations*

* [French](http://smcf.googlecode.com/files/smcf-fr_fr.zip) - [E Neuville, ICS-INFORMATIQUE](http://www.ics-informatique.com)
* [German](http://smcf.googlecode.com/files/smcf-de_de.zip) - Mika
* [Italian](http://smcf.googlecode.com/files/smcf-it_it.zip) - [Gianni Diurno](http://gidibao.net/)
* [Polish](http://smcf.googlecode.com/files/smcf-pl_pl.zip) - [Tomek Nowak](http://rezist.com/)
* [Portuguese](http://smcf.googlecode.com/files/smcf-pt_br.zip) - [Vitor Borges](http://blogdovborges.net)
* [Russian](http://smcf.googlecode.com/files/smcf-ru_ru.zip) - [Alexey Kot](http://www.waytorise.com)
* [Turkish](http://smcf.googlecode.com/files/smcf-tr_tr-1.zip) (a) - [SanalDuva](http://sanalduvar.com/)
* [Turkish](http://smcf.googlecode.com/files/smcf-tr_tr-2.zip) (b) - Ugur Eskici

Thank you to all who have contributed these translations.

== Installation ==

*Requirements:*

* PHP `mail()`
* jQuery 1.2 or greater

*Steps:*

1. Unzip SMCF archive and put all files/folders into your "plugins" folder (`/wp-content/plugins/`). You should end up with `/wp-content/plugins/simplemodal-contact-form-smcf/`.
2. Activate the plugin (WordPress Admin > Plugins > Click "Activate" for "SimpleModal Contact Form (SMCF)")
3. Set the desired options (Admin Dashboard >  Settings > SimpleModal Contact Form)
4. Enable SMCF on your site! SMCF works by looking for links (HTML A elements) with a class of "smcf-link". See below:

You have 3 options:

a) Add the "smcf-link" to your existing contact link:

	<a href="/contact" class="smcf-link">Contact</a>
	
b) Use the "smcf()" function in one of your theme files (`sidebar.php`, for example):

	<?php if (function_exists('smcf')) : ?>
		<?php smcf(); ?>
	<?php endif; ?>

c) If your contact link is generated using `wp_page_menu()` or `wp_list_pages()`, you can enter the contact link title in the SMCF Options under "Contact Link Title" and SMCF will automatically attempt to add the smcf-link class for that link.

== Frequently Asked Questions ==

= How do I change the output of the smcf() function? =

You can modify the link URL and link title on the SMCF WordPress options page.

= How do I change the elements in the contact form? =

* Go to Plugins > Plugin Editor > SimpleModal Contact Form (SMCF).
* Look for `function footer()`
* Edit the HTML in the `$form` variable. 
* *Note*: Be careful not to enter anything that would cause PHP errors. =)

= How do I change the styling of the contact form? =

Open `simplemodal-contact-form-smcf/css/smcf.css` and modify the CSS to fit your needs.

*Note*: There are some browser specific CSS values that are set in the JavaScript (`simplemodal-contact-form-smcf/js/smcf.js`).

= What does the "Unfortunately, your message could not be verified." message mean? =

Starting in SMCF v1.1, there is a new "security" feature that attempts to ward off unwanted spam. A "token" is created and placed in the contact form. When the form is submitted, the token is verified and if the token does not exist or fails verification, the user will see the "Unfortunately, your message could not be verified." message. If this is happening for legitimate users, please let me know!

= What does the "Unfortunately, a server issue prevented delivery of your message." message mean? =

It means a server issue was encountered. See the last item in the next FAQ:

= I've followed all of the instructions, but it doesn't work...what gives? =

Here are some troubleshooting steps to follow:

* Use Firefox and Firebug or if you must use IE, turn on JavaScript debugging and install the Developer Toolbar (search Google if you are unsure of how to do any of these)
* Make sure the footer.php file in your theme, contains `<?php wp_footer(); ?>`
* Make sure all of the JavaScript files are loaded (jquery, jquery.simplemodal and smcf)
* Make sure no other plugins are loading an older version of jQuery (SMCF requires jQuery 1.2 or greater)
* Make sure PHP's `mail()` is installed and working. (You can also open `smcf_data.php` and remove the @ from `@mail`. That will echo out any errors that it throws while trying to send mail.)

== Screenshots ==

1. A contact form with the Subject and "Send me a copy" options enabled.
2. The validation messages displayed for required/invalid fields.
3. The contact form in the process of sending.
4. A successful message.

== Changelog ==

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
	* Changed verification method to prevent false failures
	* Changed failure messages to indicate type of failure (verification/server failure)
	* Modified CSS
* Version 1.1.4
	* Fixed broken Subject encoding
* Version 1.2
	* Upgraded SimpleModal to 1.2.1
	* Removed IE specific CSS and PNG files
	* Added localization (lang/smcf.pot)
	* Made contact form wider and increased height of textarea
	* Removed included jQuery - changed to using version included with WordPress
	* Switched style and script loading to use wp_enqueue_style and wp_enqueue_script respectively
	* Removed options to include jQuery and SimpleModal 
* Version 1.2.1
	* Upgraded SimpleModal to 1.2.2
	* Added function_exists() check for wp_print_styles
* Version 1.2.2
	* Upgraded SimpleModal to 1.2.3
	* Added addslashes() function for smcf_messages JavaScript object to prevent localization issues
	* Added stripslashes() function for the email message
	* Removed 70 character limit for wordwrap() function - defaults to 75
	* Added wp_page_menu and wp_list_pages filter to dynamically add smcf-link class to a contact menu link
	* Changed format of validation messages - requires translation updates.
* Version 1.2.3
	* Fixed bug in validation code. Forms without a subject were getting "subject required" errors.
* Version 1.2.4
	* Upgraded to SimpleModal 1.3
	* Fixed the bug that was supposed to be fixed in 1.2.3 ;)
	* Moved the JavaScript loading into the WordPress init() function
	* Optimized smcf.js variables to reduce file-size
	* Moved close (X) HTML from smcf.php to smcf.js (the SimpleModal closeHTML option)