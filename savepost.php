<?php
/*
* This page is used as Save post hook.
* Created by Chirag Patel
*/

function tsapi_apipost($post_id) {

	$tsapi_post_type = get_post_type($post_id);

    // If this isn't a 'post' post-type, don't update it.
	if ( "post" != $tsapi_post_type ) return;
	
	$tsapi_api_Siteurl="https://theshire.co/apipost/";

	$post = get_post($post_id);

	// **** Get Category of Article :: 
	$metavals=$_POST['theshire_category_box_selects'];
 	update_post_meta( $post_id = $post->ID, $key = '_theshire_category_box_select', $value = $metavals );
 
 	$tsapi_cat_id= get_post_meta( $post_id,'_theshire_category_box_select',true);

	if(($tsapi_cat_id=="") || ($tsapi_cat_id==null)){ $tsapi_cat_id="14"; }

    $tsapi_post_category=$tsapi_cat_id;

	// **** Get Subject of Article :: title
	$tsapi_p_title = get_the_title( $post_id );
	
	// **** Get Link of Article :: link
	$tsapi_post_url = get_permalink( $post_id );

	// **** Get content for Article 

	//Get excerpt and body content
	$tsapi_post_excerpt=get_the_excerpt($post_id);
	$tsapi_p_content = $post->post_content;

	// **** Image for Article
	$tsapi_default_image_url = plugin_dir_url( __FILE__ ) . "admin/images/theshire-default.jpg";
	$tsapi_image_options = get_option('theshire_api_image_options');
    $tsapi_option_image_url= $tsapi_image_options['theshire_api_field_imageupload'];

	$tsapi_p_image = get_the_post_thumbnail_url( $post_id, 'medium' );

	// Validate all data before add in API settings
	// validate Subject (post title) of Article
	$tsapi_post_title=substr($tsapi_p_title, 0, -1);

	// validate Content / Excerpt of Article
	if($tsapi_p_content!="" && $tsapi_post_excerpt!=""){
	 	$tsapi_post_content=substr($tsapi_post_excerpt, 0, 320);
	}

	if($tsapi_p_content==""){
	 	$tsapi_post_content=substr($tsapi_post_excerpt, 0, 320);
	}

	if($tsapi_post_excerpt==""){
	 	$tsapi_post_content=substr($tsapi_p_content, 0, 320);
	}

	// validate Image of Article
	if($tsapi_p_image==''){
		if(($tsapi_option_image_url=='') || ($tsapi_option_image_url==null)){
			$tsapi_post_image=$tsapi_default_image_url;
		}else{
	 		$tsapi_post_image=$tsapi_option_image_url;
	 	}
	}
	else{
	 	$tsapi_post_image=$tsapi_p_image;
	}

 	$tsapi_options = get_option('theshire_api_options');

	$tsapi_date = gmdate('D, d M Y H:i:s T');
	$tsapi_username= $tsapi_options['theshire_api_field_username'];
	$tsapi_private_key= $tsapi_options['theshire_api_field_privatekey'];
	$tsapi_public_key= $tsapi_options['theshire_api_field_publickey'];
	$tsapi_contentType = 'application/xml';
	$tsapi_signatureBase = $tsapi_contentType . "\n";
	$tsapi_signatureBase .= $tsapi_date . "\n";
	$tsapi_signatureBase .= 'x-bol-date:' . $tsapi_date . "\n";
	
	$tsapi_data="<?xml version='1.0' encoding='UTF-8'?>
			<channel>
			<item>
			<category>".$tsapi_post_category."</category>
			<title>".$tsapi_post_title."</title>
			<link>".$tsapi_post_url."</link>
			<description>".$tsapi_post_content."</description>
			<mediatype>Image</mediatype>
			<mediaurl>".$tsapi_post_image."</mediaurl>
			</item>
			</channel>";

	$tsapi_signature = base64_encode($tsapi_username.'||'.$tsapi_public_key.'||'.$tsapi_private_key.'||'.$tsapi_date);

	$body = array(
    'XML' => $tsapi_data
	);
 
	$args = array(
	    'body' => $body,
	    'timeout' => '5',
	    'redirection' => '5',
	    'httpversion' => '1.0',
	    'blocking' => true,
	    'headers' => array(
	    	'X-SHIRE-Date' => $tsapi_date,
		    'X-SHIRE-Authorization' => $tsapi_signature
	    	),
	    'cookies' => array()
	);
 
	$response = wp_remote_post( $tsapi_api_Siteurl, $args );
	$res_body = wp_remote_retrieve_body( $response );
	
}
add_action( 'draft_to_publish', 'tsapi_apipost');
?>