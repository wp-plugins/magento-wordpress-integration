<?php
/*
* @package jck_mwi
* @version 2.1.2
*/

/*
Plugin Name: Mage/WP Integration
Plugin URI: http://wordpress.org/extend/plugins/magento-wordpress-integration/
Description: Magento WordPress Integration is the simplest way to get blocks & sessions from your Magento store.
Author: James Kemp
Version: 2.1.2
Author URI: http://www.jckemp.com/
*/

class jck_mwi
{
  
  ################################################
  ###                                          ###
  ###             Core Functions               ###
  ###                                          ###
  ################################################
  
  	// Get storeview
  	public function storeview(){
	  	// Store View
  		$default_sv = $this->getValue('default_sv','default');
		$multiple_sv = $this->getValue('multiple_sv');
		
		$sv = $default_sv;
		// Loop through multiple Store View codes, if they exist - if not, set default.
		if($multiple_sv && !empty($multiple_sv[0]['url'])) {
			
			$currUrl = $this->curPageURL(); // Get Current Page URL
			
			foreach($multiple_sv as $single_sv) {
				
				if($this->compareUrls($currUrl,$single_sv['url'])) {
					$sv = $single_sv['store_view_code'];
				} 
				
			}
			
		} // End if $multiple_sv
		
		return $sv;
  	}
  	
  	// Get App for current store
  	// Added v2.0.3
  	public function getapp(){
	  	// Store View
  		$sv = $this->storeview();
		return Mage::app($sv);
  	}
  	
  	// Generate layout
  	public function layout() {
  	
  		$app = $this->getapp();  		
		$layout = $app->getLayout();
		
		$module = $app->getRequest()->getModuleName(); // Check if page belongs to Magento
  	
		if(!$module) {
			
	  		$customerSession = Mage::getSingleton('customer/session');	
			$logged = ($customerSession->isLoggedIn()) ? 'customer_logged_in' : 'customer_logged_out';  
			
			$sv = $this->storeview();	
	  		
			$layout->getUpdate()
			    ->addHandle('default')
			    ->addHandle('STORE_'.$sv)
			    ->addHandle($logged)
			    ->load();
			
			$layout->generateXml()
			       ->generateBlocks();
		       
		}
		
		return $layout;
  	} 
  
	// Get current page URL (http://webcheatsheet.com/php/get_current_page_url.php)
	public function curPageURL() {
		
		$pageURL = 'http';
		if(isset($_SERVER["HTTPS"])) {
			if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		}
		$pageURL .= "://";
		
		if(isset($_SERVER["SERVER_PORT"])) {
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		}
		
		return $pageURL;
	}
	
	// Strip generic parts of URL
	public function stripHttp($string) {
		$replace = array('http://','www.','https://');
		return str_replace($replace, '', $string);
	}
	
	// Add trailing slash to all URLs
	public function addSlash($string) {
		$lastChar = substr($string, -1);
		if($lastChar != '/') {
			$string = $string.'/';
		}
		return $string;
	}
	
	// Compose the URL
	public function composeUrl($string) {
		return $this->stripHttp($this->addSlash($string));
	}
  
	// Compare URLs and return true if they match or if User inputted URL is contained within current browser URL
	public function compareUrls($currUrl, $userUrl) {
	
		$currUrl = $this->composeUrl($currUrl);
		$userUrl = $this->composeUrl($userUrl);
		
		if($currUrl == $userUrl) {
			
			return true;
		
		} else {
			
			$strpos = strpos($currUrl,$userUrl);
			
			if($strpos || $strpos === (int)0) {
				return true;
			} else {
				return false;
			}
		}
		
	}
	
	################################################
	###                                          ###
	###      Get values to prevent errors        ###
	###                                          ###
	################################################	
	
	public function getValue($key, $default = '') {		
	
		$options = get_option('mwi_options');	
		
		if (isset($options[$key])) {
		
			if($options[$key] == '') {
				return $default;
			} else {
				return $options[$key];
			}
		
		} else {
		
			return $default;
		
		}
	
	}
	
  ################################################
  ###                                          ###
  ###           Initiate Mage.php              ###
  ###                                          ###
  ################################################
	
	public function mage() {
		
		// Mage Path
		$magepath = $this->getValue('magepath');
		
		// Theme Info
		$package = $this->getValue('package','default');
		$theme = $this->getValue('theme','default');
		
		if ( !empty( $magepath ) && file_exists( $magepath ) && !is_dir( $magepath )) {
			
			require_once($magepath);
			umask(0);
			
			if(class_exists( 'Mage' ) && !is_admin()) {
				$app = $this->getapp();
				
				$locale = $app->getLocale()->getLocaleCode();
				Mage::getSingleton('core/translate')->setLocale($locale)->init('frontend', true);
				
				// Session setup
				Mage::getSingleton('core/session', array('name'=>'frontend'));
				Mage::getSingleton("checkout/session");
				// End session setups
				
				Mage::getDesign()->setPackageName($package)->setTheme($theme); // Set theme so Magento gets blocks from the right place.
			}
			
		}
		
	}
	
  ################################################
  ###                                          ###
  ###               Admin Page                 ###
  ###                                          ###
  ################################################
	
	// Add Admin Page and trigger settings
	public function admin_menu() {		
		$page = add_options_page( 'Magento WordPress Integration Settings', 'Mage/WP', 'administrator', 'mwi', array(&$this, 'mwi_admin_page') );
		add_action( 'admin_init', array(&$this, 'mwi_settings') );
		add_action( 'admin_print_styles-' . $page, array(&$this, 'admin_styles') );		
	}
	
	// Build admin Page
	public function mwi_admin_page() {		
		// Mage Path
		$magepath = $this->getValue('magepath');
		
		// notification/error messages
		if ( !empty( $magepath ) && !file_exists( $magepath ) ) {
			$message = '<div class="mwi-error">'.__('Invalid URL','mwi').'</div>';
		} elseif ( !empty( $magepath ) && file_exists( $magepath ) ) {
			$this->mage();
			$message = ( class_exists( 'Mage' ) ) ? '<div class="mwi-success">'.__('Mage.php was found!','mwi').'</div>' : '<div class="mwi-error">'.__('Mage object not found!','mwi').'</div>';
		} else {
			$message = '';
		}
	
		require_once("inc/admin.php");		
	}
	
	// Validate Settings 
	public function validate_mwi_settings($data) {		
		$data['multiple_sv'] = array_values($data['multiple_sv']);
		return $data;	
	}
	
	// Register MWI Options
	public function mwi_settings() {
		register_setting( 'mwi-main-settings', 'mwi_options', array(&$this, 'validate_mwi_settings') );
	}
	
	// Enqueue Styles and Scripts
	public function admin_styles() {
		wp_enqueue_style( 'mwiAdminCss' );
		wp_enqueue_script( 'mwiAdminJS' );
	}
	public function admin_init() {
		wp_register_style( 'mwiAdminCss', plugins_url('css/admin.css', __FILE__) );
		wp_register_script( 'mwiAdminJS', plugins_url('js/admin.js', __FILE__), array('jquery') );
	}
	
  ################################################
  ###                                          ###
  ###            Frontend Scripts              ###
  ###                                          ###
  ################################################
	
	public function scripts() {
		wp_register_script( 'mwi_scripts', plugins_url('/js/mwi_scripts.js', __FILE__), array(), false, true);
		wp_enqueue_script( 'mwi_scripts' );
	} 
	
  ################################################
  ###                                          ###
  ###                Unlocked                  ###
  ###                                          ###
  ################################################
	
	public function u($field_name)
	{
		switch ($field_name) {
		    case 'widgetsshortcodes':
		    	if(md5($this->k($field_name)) == "a529679f11e4e30766ee7b20bdf62547"){ return true; } else { return false; }
		        break;
		    case 'widgetspecific':
		    	if(md5($this->k($field_name)) == "dc87cedbdb5a14e4b39dec55a24f6f0c"){ return true; } else { return false; }
		        break;
	    }
	}
	
  ################################################
  ###                                          ###
  ###                The Key                   ###
  ###                                          ###
  ################################################
	
	private function k($field_name)
	{
		return get_option('mwi_' . $field_name . '_ac');
	}
	
  ################################################
  ###                                          ###
  ###                  Init                    ###
  ###                                          ###
  ################################################
	
	public function init()
	{	
		include('inc/init.php');
	}
	
  ################################################
  ###                                          ###
  ###             Admin Message                ###
  ###                                          ###
  ################################################
	
	public function admin_message($message = "", $type = 'updated') {
		$GLOBALS['mwi_mesage'] = $message;
		$GLOBALS['mwi_mesage_type'] = $type;
		
		add_action( 'admin_notices', array(&$this, 'mwi_admin_notice') );
	}
	
	public function mwi_admin_notice() {
	    echo '<div class="' . $GLOBALS['mwi_mesage_type'] . '">'.$GLOBALS['mwi_mesage'].'</div>';
	}
  
  ################################################
  ###                                          ###
  ###            Construct Class               ###
  ###                                          ###
  ################################################
  
	// PHP 4 Compatible Constructor
	public function jck_mwi() {
	$this->__construct();
	}
	
	// PHP 5 Constructor
	public function __construct() { 
		add_action( 'template_redirect', array(&$this, 'mage') );
		add_action( 'admin_menu', array(&$this, 'admin_menu') );
		add_action( 'admin_init', array(&$this, 'admin_init') );
		add_action( 'wp_enqueue_scripts',  array(&$this, 'scripts') );
		add_action( 'init', array($this, 'init') );
	}  
  
} // End jck_mwi Class

global $jck_mwi;
$jck_mwi = new jck_mwi; // Start an instance of the plugin class

include_once('inc/template-functions.php');

include_once('inc/mwi-shortcodes/mwi-shortcodes.php');
include_once('inc/mwi-widgets/mwi-widgets.php');