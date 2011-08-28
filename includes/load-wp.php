<?php 
/*
	Loads WordPress
	@since 2.0.0

	Magento Wordpress Integration
	Copyright (c) 2011 James C Kemp

*/
if ( file_exists( '../../../wp-load.php' ) ) {
	require_once( '../../../wp-load.php' );
} else {
	$absolute_path = __FILE__;
	$path_to_file = explode( 'wp-content', $absolute_path );
	$path_to_wp = $path_to_file[0];
	require_once( $path_to_wp.'/wp-load.php' );
}
global $post;
?>