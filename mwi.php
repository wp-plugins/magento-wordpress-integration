<?php
/*
Plugin Name: MWI - Mage/WP Integration
Plugin URI: http://wordpress.org/extend/plugins/magento-wordpress-integration/
Description: Magento WordPress Integration is the simplest way to get blocks & sessions from your Magento store.
Author: James Kemp
Version: 3.0.1
Author URI: http://www.jckemp.com/
*/

$GLOBALS['layout'] = '';
class jck_mwi
{
  
/* 	=============================
   	Core Functions 
   	============================= */
  	
  	public function layout() {
  	
  		if($GLOBALS['layout']) {
  			return $GLOBALS['layout'];
  		} else {
	  		$app = self::getApp();
			$layout = $app->getLayout();
			
			$module = $app->getRequest()->getModuleName(); // Check if page belongs to Magento
	  	
			if(!$module) {
				
		  		$customerSession = Mage::getSingleton('customer/session');	
				$logged = ($customerSession->isLoggedIn()) ? 'customer_logged_in' : 'customer_logged_out';  
		  		
				$layout->getUpdate()
				    ->addHandle('default')
				    ->addHandle($logged)
				    ->load();
				
				$layout->generateXml()
				    ->generateBlocks();
			       
			}
			$GLOBALS['layout'] = $layout;
			return $layout;
		}
  	} 
  	
  	public function getApp() {
	  	if(class_exists( 'Mage' ) && !is_admin()) {
	  		$app = Mage::app(self::getValue('websitecode','base'), 'website');
	  		return $app;
	  	}
  	}
	
/* 	=============================
   	Get values to prevent errors 
   	============================= */
	
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
	
/* 	=============================
   	Initiate Mage.php 
   	============================= */
	
	public function mage() {
		
		// Mage Path
		$magepath = self::getValue('magepath');
		
		// Theme Info
		$package = self::getValue('package','default');
		$theme = self::getValue('theme','default');
		
		if ( !empty( $magepath ) && file_exists( $magepath ) && !is_dir( $magepath )) {
			
			require_once($magepath);
			umask(0);
			
			if(class_exists( 'Mage' ) && !is_admin()) {
				$app = self::getApp();
				
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
	
/* 	=============================
   	Admin Page 
   	============================= */
	
	// Add Admin Page and trigger settings
	public function admin_menu() {		
		$page = add_options_page( 'Magento WordPress Integration Settings', 'Mage/WP', 'administrator', 'mwi', array(&$this, 'mwi_admin_page') );
		add_action( 'admin_init', array(&$this, 'mwi_settings') );
		add_action( 'admin_print_styles-' . $page, array(&$this, 'admin_styles') );		
	}
	
	// Build admin Page
	public function mwi_admin_page() {		
		// Mage Path
		$magepath = self::getValue('magepath');
		
		// notification/error messages
		if ( !empty( $magepath ) && !file_exists( $magepath ) ) {
			$message = '<div class="mwi-error">'.__('Invalid URL','mwi').'</div>';
		} elseif ( !empty( $magepath ) && file_exists( $magepath ) ) {
			self::mage();
			$message = ( class_exists( 'Mage' ) ) ? '<div class="mwi-success">'.__('Mage.php was found!','mwi').'</div>' : '<div class="mwi-error">'.__('Mage object not found!','mwi').'</div>';
		} else {
			$message = '';
		}
	
		require_once("inc/admin.php");		
	}
	
	// Validate Settings 
	public function validate_mwi_settings($data) {		
		//$data['multiple_sv'] = array_values($data['multiple_sv']);
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
	
/* 	=============================
   	Frontend Scripts 
   	============================= */
	
	public function scripts() {
		wp_register_script( 'mwi_scripts', plugins_url('/js/mwi_scripts.js', __FILE__), array(), false, true);
		wp_enqueue_script( 'mwi_scripts' );
	} 
	
	public function stylesheets() {
        wp_register_style( 'mwi_addon_styles', plugins_url('css/addon-styles.css', __FILE__) );
        wp_enqueue_style( 'mwi_addon_styles' );
    }
	
/* 	=============================
   	Admin Message 
   	============================= */	
   	
	public function admin_message($message = "", $type = 'updated') {
		$GLOBALS['mwi_mesage'] = $message;
		$GLOBALS['mwi_mesage_type'] = $type;
		
		add_action( 'admin_notices', array(&$this, 'mwi_admin_notice') );
	}
	
	public function mwi_admin_notice() {
	    echo '<div class="' . $GLOBALS['mwi_mesage_type'] . '">'.$GLOBALS['mwi_mesage'].'</div>';
	}
  
/* 	=============================
   	Construct Class 
   	============================= */
  
	// PHP 4 Compatible Constructor
	public function jck_mwi() {
		self::__construct();
	}
	
	// PHP 5 Constructor
	public function __construct() { 
		add_action( 'template_redirect', array(&$this, 'mage') );
		add_action( 'admin_menu', array(&$this, 'admin_menu') );
		add_action( 'admin_init', array(&$this, 'admin_init') );
		add_action( 'wp_enqueue_scripts',  array(&$this, 'scripts') );
		if(self::getValue('styles',0) == 1) { add_action( 'wp_enqueue_scripts', array(&$this, 'stylesheets') ); }
	}  
  
} // End jck_mwi Class

global $jck_mwi;
$jck_mwi = new jck_mwi; // Start an instance of the plugin class

include_once('inc/template-functions.php');