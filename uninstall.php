<?php 

	if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
		exit;
	}	
	global $wpdb;
	
    $optiontable=$wpdb->prefix . 'options';
    $optionvalue='theshire_api_options';
    $optionDeleteQuery = "DELETE FROM $optiontable WHERE option_name='$optionvalue'";
	$wpdb->query($optionDeleteQuery);

	$optiontable=$wpdb->prefix . 'options';
    $imgoptionvalue='theshire_api_image_options';
    $imgoptionDeleteQuery = "DELETE FROM $optiontable WHERE option_name='$imgoptionvalue'";
	$wpdb->query($imgoptionDeleteQuery);

	#Disable code for delete meta of post because it help to retrive category of that post back.
	
	/*	$metatable=$wpdb->prefix . 'postmeta';
 		$metacatvalue='_theshire_category_box_select';
 		$metacatvalueDeleteQuery = "DELETE FROM $metatable WHERE meta_key='$metacatvalue'";
		$wpdb->query($metacatvalueDeleteQuery); */

	delete_option("theshire_db_version");

	function theshire_api_remove_options_page()
	{
	    remove_menu_page('theshire_api');
	}
	add_action('admin_menu', 'theshire_api_remove_options_page', 99);
	
	function theshire_api_image_remove_options_page()
	{
	    remove_menu_page('theshire_image_api');
	}
	add_action('admin_menu', 'theshire_api_image_remove_options_page', 99);
?>