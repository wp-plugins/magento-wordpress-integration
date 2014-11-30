<?php
/*
Plugin Name: MWI - Mage/WP Integration
Plugin URI: http://wordpress.org/extend/plugins/magento-wordpress-integration/
Description: Magento WordPress Integration is the simplest way to get blocks & sessions from your Magento store.
Author: James Kemp
Version: 3.1.0
Author URI: http://www.jckemp.com/
License: GPL
Copyright: James Kemp
*/

class jck_mwi {
    
    public $name = 'Magento WordPress Integration';
    public $shortname = 'Mage/WP';
    public $slug = 'jckmwi';
    public $version = "3.1.0";
    public $plugin_path;
    public $plugin_url;
	
/**	=============================
    *
    * Construct the plugin
    *
    ============================= */
   	
    public function __construct()
    {
        
        $this->plugin_path = plugin_dir_path( __FILE__ );
        $this->plugin_url = plugin_dir_url( __FILE__ );
        
        // Hook up to the init action
        add_action( 'init', array( &$this, 'initiate_hook' ) );
        
    }

/**	=============================
    *
    * Initiate Plugin
    *
    * Run after the current user is set (http://codex.wordpress.org/Plugin_API/Action_Reference)
    *
    ============================= */
   	
	public function initiate_hook()
	{	    
	    
	    // Run on admin
        if(is_admin())
        {
            add_action( 'admin_menu',           array(&$this, 'add_settings_page') );
        }
        
        // Run on frontend
        else
        {
            add_action( 'template_redirect',    array(&$this, 'mage') );
    		add_action( 'wp_enqueue_scripts',   array(&$this, 'scripts') );
        }
        
	}
	
/**	=============================
    *
    * Get Layout
    *
    * Add specific layout handles to our layout and then load them,
    * either from the global, or dynamically
    *
    * @return object
    *
    ============================= */
  	
    public function layout() {
    
        if($GLOBALS['layout']) {
        
            $layout = $GLOBALS['layout'];
            
        } else {
            
            $app = $this->getApp();
            $layout = $app->getLayout();            
            $module = $app->getRequest()->getModuleName(); // Check if page belongs to Magento
            
            if(!$module):
            
                $customerSession = Mage::getSingleton('customer/session');	
                $logged = ($customerSession->isLoggedIn()) ? 'customer_logged_in' : 'customer_logged_out';  
                
                $layout->getUpdate()
                    ->addHandle('default')
                    ->addHandle($logged)
                    ->load();
                
                $layout->generateXml()
                    ->generateBlocks();
            
            endif;
            
            $GLOBALS['layout'] = $layout;
        
        }
        
        return $layout;
        
    } 

/**	=============================
    *
    * Get App
    *
    * @return object
    *
    ============================= */
    
    public function getApp() {
        
        $app = false;
        
        if(class_exists( 'Mage' ) && !is_admin()):
        
            $app = Mage::app($this->getValue('websitecode', 'base'), 'website');
            
        endif;
        
        return $app;
    
    }
	
/**	=============================
    *
    * Get Value
    *
    * Helper function that allows me to set a default value if 
    * the one I'm requesting does not exist
    *
    * @return object
    *
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
	
/**	=============================
    *
    * Initiate Mage
    *
    * Include Mage.php and then configure app and sessions
    * based on package/theme
    *
    ============================= */
    
    public function mage() {
		
		$magepath = $this->get_mage_path();
        
		if ( !empty( $magepath ) && file_exists( $magepath ) && !is_dir( $magepath )) {
    		
    		$package = $this->getValue('package','default');
    		$theme = $this->getValue('theme','default');
			
			require_once($magepath);
			umask(0);
			
			if(class_exists( 'Mage' ) && !is_admin()) {
				$app = $this->getApp();
				
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
	
/**	=============================
    *
    * Add Settings Page
    *
    ============================= */
    
	public function add_settings_page() {
    	
		$page = add_options_page( $this->name, $this->shortname, 'administrator', $this->slug, array(&$this, 'render_settings_page') );
		add_action( 'admin_init', array(&$this, 'register_mwi_settings') );
		add_action( 'admin_print_styles-' . $page, array(&$this, 'admin_styles_scripts') );
		
	}
	
/**	=============================
    *
    * Render Settings Page
    *
    ============================= */

	public function render_settings_page() {		
    	
		require_once("inc/admin.php");		
	
	}

/**	=============================
    *
    * Check Mage.php
    *
    * Check if Mage.php has been found and works
    *
    * @return array Returns array with true/false, and message and class for settings page
    *
    ============================= */
	
	public function check_mage() {
    	
    	$return = array(
        	'result' => false,
        	'message' => '',
        	'class' => ''
    	);
    	
		$magepath = $this->get_mage_path();
		
		if ( !empty( $magepath ) && !file_exists( $magepath ) ):
    		
    		$return['message'] = __('Invalid URL', $this->slug);
    		$return['class'] = 'mwi-success';
    		
		elseif ( !empty( $magepath ) && file_exists( $magepath ) ):
			
			$this->mage();
			
			if( class_exists( 'Mage' ) ):
			    
			    $return['result'] = true;
			    $return['message'] = __('Mage.php was found!', $this->slug);
			    $return['class'] = 'mwi-success';
			    
			else:
			    
			    $return['message'] = __('Mage object not found!', $this->slug);
			    $return['class'] = 'mwi-error';
			    
			endif;
		
		endif;
		
		return $return;
		
	}

/**	=============================
    *
    * Get Mage.php Path
    *
    * @return str Path to Mage.php, relative or absolute
    *
    ============================= */
	
	public function get_mage_path() {
    	
    	// get path to Mage.php
		$magepath = $this->getValue('magepath');
		
		//check for relative path from WordPress root
        if(file_exists(ABSPATH . $magepath)) {
        	$magepath = ABSPATH . $magepath;
        }
        
        return $magepath;
        
	}
	
/**	=============================
    *
    * Validate WMI Settings
    *
    * @return array
    *
    ============================= */
    
	public function validate_mwi_settings($data) {
    	
		return $data;	
	
	}
	
/**	=============================
    *
    * Register MWI Settings
    *
    ============================= */
    
	public function register_mwi_settings() {
		register_setting( 'mwi-main-settings', 'mwi_options', array(&$this, 'validate_mwi_settings') );
	}
	
/**	=============================
    *
    * Admin Styles and Scripts
    *
    ============================= */
    
	public function admin_styles_scripts() {
        
        wp_register_style( 'mwiAdminCss', plugins_url('css/admin.css', __FILE__) );
		wp_register_script( 'mwiAdminJS', plugins_url('js/admin.js', __FILE__), array('jquery') );
		
		wp_enqueue_style( 'mwiAdminCss' );
		wp_enqueue_script( 'mwiAdminJS' );
		
	}
	
/**	=============================
    *
    * Frontend Scripts
    *
    ============================= */
	
	public function scripts() {
		wp_register_script( 'mwi_scripts', plugins_url('/js/mwi_scripts.js', __FILE__), array(), false, true);
		wp_enqueue_script( 'mwi_scripts' );
	} 
  
}

$jck_mwi = new jck_mwi();

include_once('inc/template-functions.php');