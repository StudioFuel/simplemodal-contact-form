=== Plugin Name ===
Contributors: emartin24
Tags: contact, modal, ajax, jquery, javascript
Requires at least: ?
Tested up to: 2.3
Stable tag: 1.0

SimpleModal Contact Form (SMCF) is an Ajax powered modal dialog contact form. It is built on jQuery and uses the SimpleModal jQuery plugin.

== Description ==

SimpleModal Contact Form (SMCF) is an Ajax powered modal dialog contact form. 

It is built on the jQuery JavaScript library and uses the SimpleModal jQuery plugin.

Currently, you'd have to edit the plugin file (smcf.php) to change the output of the contact form or scmf function.

== Installation ==

1. Unzip SMCF archive and put all files/folders into your "plugins" folder (/wp-content/plugins/). You should end up with /wp-content/plugins/smcf/.
2. Activate the plugin
3. Go to Options > SimpleModal Contact Form, adjust the options according to your needs, and save them.
4. In your theme, a) add a CSS class to an existing Contact link or b) add the smcf function call to dynamically create a "Contact" link. See examples below.

Examples:

	a) <a href="/contact" class="smcf_link">Contact</a>
	b) <?php if (function_exists('smcf')) : ?>
	     <?php smcf(); ?>
	   <?php endif; ?>

== Frequently Asked Questions ==

= How do I change the output of the smcf() function? =

* Go to Plugins > Plugin Editor > SimpleModal Contact Form (SMCF).
* Look for "function smcf()"
* Edit the value of the href attribute and/or the link text. 
* *Note: Be careful not to enter anything that would cause PHP errors. =)*

= How do I change the elements in the contact form? =

Same as above, but look for "function footer()" instead.

= How do I change the styling of the contact form? =

Open smcf/css/smcf.css and modify the CSS to fit your needs. Put IE specific values in smcf/css/smcf-ie.css.

There are a couple of CSS values that are set in the JavaScript (smcf/js/smcf_javascript.php).

== Screenshots ==

1. The default contact form. Customize and style to fit your sites look and feel!

== Arbitrary section ==

* Version 1.0
	* Initial release
