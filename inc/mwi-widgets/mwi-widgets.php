<?php
/*
* @package jck_mwi_widgets requires jck_mwi (magento wordpress integration)
* @version 1.0
*/

/*
Plugin Name: Mage/WP Widgets
Plugin URI: http://wordpress.org/extend/plugins/admin-quick-jump/
Description: 
Author: James Kemp
Version: 1.0
Author URI: http://www.jckemp.com/
*/

class jck_mwi_widgets {
	
  ################################################
  ###                                          ###
  ###                Widgets                   ###
  ###                                          ###
  ################################################
	
	function register_widgets() {
		register_widget( 'cat_prods' );
	}
	
  ################################################
  ###                                          ###
  ###            Construct Class               ###
  ###                                          ###
  ################################################
  
  // PHP 4 Compatible Constructor
  function jck_mwi_widgets()
  {
    $this->__construct();
  }
  
  // PHP 5 Constructor
  function __construct()
  { 
		add_action( 'widgets_init', array(&$this, 'register_widgets') );
  }  
	
}
if($jck_mwi->u('widgetsshortcodes')) { $jck_mwi_widgets = new jck_mwi_widgets; } // Start an instance of the plugin class
include_once('inc/widget-cat-prods.php');