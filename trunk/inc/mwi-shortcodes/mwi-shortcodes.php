<?php
/*
* @package jck_mwi_shortcodes requires jck_mwi (magento wordpress integration)
* @version 1.0
* @updated 2.1.4
*/

/*
Plugin Name: Mage/WP Shortcodes
Plugin URI: http://wordpress.org/extend/plugins/admin-quick-jump/
Description: 
Author: James Kemp
Version: 1.0
Author URI: http://www.jckemp.com/
*/

class jck_mwi_shortcodes {
	
	################################################
	###                                          ###
	###               Shortcodes                 ###
	###                                          ###
	################################################
	
	function product($atts) {
		extract(shortcode_atts(array(
			'sku' => '', // product SKU from Magento
			'title' => "true", // true/false
			'title_tag' => 'h2', // anything
			'desc' => "true", // true/false
			'img' => "true", // true/false
			'price' => "true", // true/false
			'img_width' => 200, // width of image			
			'type' => 'add', // add/view
			'btn_link' => 'button', //Should it be a button or an anchor,
			'btn_color' => 'blue', // Color of the button blue/orange/black/gray/white/red/rosy/green/pink/none
			'cols' => 3 // Number of columns for when there is more than one product on show
		), $atts));
		
		include("inc/shortcode-products.php");
			
		return $shortcode;		 			 
	}
	
	################################################
	###                                          ###
	###            Construct Class               ###
	###                                          ###
	################################################
	
	// PHP 4 Compatible Constructor
	function jck_mwi_widgets() {
		$this->__construct();
	}
	
	// PHP 5 Constructor
	function __construct() { 
		add_shortcode('mwi_product', array(&$this, 'product') );
	}  
	
}
if($jck_mwi->u('widgetsshortcodes')) { $jck_mwi_shortcodes = new jck_mwi_shortcodes; } // Start an instance of the plugin class