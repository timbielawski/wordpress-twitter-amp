<?php
  /*
  * Plugin Name: Wordpress amp santisers
  * Description:  Santizers block code currently twitter and instagram supported thanks to Suraj Krishna Air
  * Version:  0.1
  * Plugin URI: https://github.com/timbielawski/wordpress-twitter-amp
  */

 add_filter( 'amp_content_sanitizers', 'amp_add_instagram_sanitizer', 10, 2 );
 add_filter( 'amp_content_sanitizers', 'amp_add_twitter_sanitizer', 10, 2 );

function amp_add_instagram_sanitizer( $sanitizer_classes, $post ) {
    require_once dirname(__FILE__).'/instagram.php';
	$sanitizer_classes[ 'AMP_Instagram_Embed_Sanitizer' ] = array(); 
	return $sanitizer_classes;
}

function amp_add_twitter_sanitizer( $sanitizer_classes, $post ) {
    require_once dirname(__FILE__).'/twitter.php';
	$sanitizer_classes[ 'AMP_Twitter_Embed_Sanitizer' ] = array(); 
	return $sanitizer_classes;
}
