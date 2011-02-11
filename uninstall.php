<?php
if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') )
	exit();

delete_option("smcf_link_url"); 
delete_option("smcf_link_title");
delete_option("smcf_form_subject");
delete_option("smcf_form_cc_sender");
delete_option("smcf_to_email");
delete_option("smcf_subject");
delete_option("smcf_ip");
delete_option("smcf_ua");

?>