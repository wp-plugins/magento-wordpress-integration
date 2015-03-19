<?php
/*
Plugin Name: MWI - Mage/WP Integration
Plugin URI: http://wordpress.org/extend/plugins/magento-wordpress-integration/
Description: Magento WordPress Integration is the simplest way to get blocks & sessions from your Magento store.
Author: James Kemp
Version: 3.1.11
Author URI: http://www.jckemp.com/
License: GPL
Copyright: James Kemp
*/

class jck_mwi {
    
    public $name = 'Magento WordPress Integration';
    public $shortname = 'Mage/WP';
    public $slug = 'jckmwi';
    public $version = "3.1.11";
    public $plugin_path;
    public $plugin_url;
    public $helpers;
	
/**	=============================
    *
    * Construct the plugin
    *
    ============================= */
   	
    public function __construct()
    {
        
        $this->plugin_path = plugin_dir_path( __FILE__ );
        $this->plugin_url = plugin_dir_url( __FILE__ );
        
        // set up additional classes
        require_once('class-helpers.php');
        $this->helpers = new jck_mwi_helpers();
        
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
	    
	    $active_addons = $this->active_addons();
	    
	    // Run on admin
        if(is_admin())
        {
            
            add_action( 'admin_menu',           array(&$this, 'add_settings_page') );
            
            if(is_admin() && $this->check_functions_file())
            {
                add_action( 'admin_head',               array(&$this, 'active_plugins_js') );
                
                // if shortcodes or cat listing addons are active, add mce button
                
                if( $active_addons && ( in_array( 'mwi-shortcodes', $active_addons ) || in_array( 'mwi-category-listing', $active_addons ) ) ) {
                    
                    add_action( 'admin_head',               array(&$this, 'add_mce_button') );
                    add_action( 'admin_enqueue_scripts',    array(&$this, 'add_mce_button_css') );
                
                }
            }
                
        }
        
        // Run on frontend
        else
        {
            
            add_action( 'template_redirect',    array(&$this, 'mage') );
    		add_action( 'wp_enqueue_scripts',   array(&$this, 'frontend_styles_scripts') );
        
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
            
                $customer_session = Mage::getSingleton('customer/session');	
                $logged = ($customer_session->isLoggedIn()) ? 'customer_logged_in' : 'customer_logged_out';  
                
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
		
		if($this->check_functions_file()):
		
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
		
		endif;
		
	}
	
/**	=============================
    *
    * Add Settings Page
    *
    ============================= */
    
	public function add_settings_page() {
    	
		$page = add_options_page( $this->name, $this->shortname, 'administrator', $this->slug, array(&$this, 'render_settings_page') );
		add_action( 'admin_init', array(&$this, 'register_mwi_settings') );
		add_action( 'admin_print_styles-' . $page, array(&$this, 'admin_styles') );
		
	}
	
/**	=============================
    *
    * Render Settings Page
    *
    ============================= */

	public function render_settings_page() {	
    	
    	$checkMage = $this->check_mage(true);
    	$magepath = $this->get_mage_path();
    	
    	if($checkMage['result'] == false || ($this->check_functions_file() && $checkMage['result'] == true)):
    	    require_once("inc/admin.php");
    	else:
    	    require_once("inc/admin-install.php");
    	endif;	
	
	}

/**	=============================
    *
    * Check Mage.php
    *
    * Check if Mage.php has been found and works
    *
    * @param bool Check for the file only? If false, will also check for Mage object
    * @return array Returns array with true/false, and message and class for settings page
    *
    ============================= */
	
	public function check_mage($pathOnly = false) {
    	
    	$return = array(
        	'result' => false,
        	'message' => '',
        	'class' => ''
    	);
    	
		$magepath = $this->get_mage_path();
		
		if ( (!empty( $magepath ) && !file_exists( $magepath )) || is_dir( $magepath ) ):
    		
    		$return['message'] = __('Invalid URL', $this->slug);
    		$return['class'] = 'mwi-error';
    		
		elseif ( !empty( $magepath ) && file_exists( $magepath ) && !is_dir( $magepath ) ):
		
		    if($pathOnly) {
    		    
    		    $return['result'] = true;
			    $return['message'] = __('Mage.php was found!', $this->slug);
			    $return['class'] = 'mwi-success';
			    
    		    return $return;
		    }
			
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
        
        $magepath = str_replace('//', '/', $magepath);
        
        return $magepath;
        
	}

/**	=============================
    *
    * Check functions.php
    *
    * @return bool
    *
    ============================= */
    
    public function check_functions_file() {
        $magepath = $this->get_mage_path();
        $apppath = str_replace('Mage.php', '', $magepath);
        
        return file_exists($apppath.'code/local/Mage/Core/functions.php');   
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
    * Admin Styles
    *
    ============================= */
    
	public function admin_styles() {
        
        // styles
        wp_register_style( 'mwi-admin-css', plugins_url('assets/admin/css/admin.css', __FILE__) );
		wp_enqueue_style( 'mwi-admin-css' );
			
	}

/**	=============================
    *
    * Admin Head Active Plugins JS
    *
    ============================= */
	
	public function active_plugins_js() {
    	
    	$active_addons = $this->active_addons();
    	
    	if($active_addons) {
        	
        	?>
            <script type="text/javascript">
            var mwi_active_addons = <?php echo json_encode($active_addons); ?>;
            </script>
            <?php
                
        }
        
	}

/**	=============================
    *
    * MCE - add button
    *
    ============================= */

    function add_mce_button() {

        // check user permissions
        if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
            return;
        }
        
    	// check if WYSIWYG is enabled
    	if ( get_user_option('rich_editing') == 'true') {
    		add_filter( "mce_external_plugins",     array(&$this, "add_mce_button_script") );
    		add_filter( 'mce_buttons',              array(&$this, 'register_mce_button') );
    	}
    	
    }
    
/**	=============================
    *
    * MCE - add button scripts
    *
    ============================= */
    
    function add_mce_button_script($plugin_array) {
        
       	$plugin_array['mwi_sc_button'] = $this->plugin_url . 'assets/admin/js/shortcode-button.js';
       	return $plugin_array;
       	
    }

/**	=============================
    *
    * MCE - register button
    *
    ============================= */
    
    function register_mce_button($buttons) {
        
       array_push($buttons, "mwi_sc_button");
       return $buttons;
       
    }

/**	=============================
    *
    * MCE - add button styles
    *
    ============================= */
    
    function add_mce_button_css() {
        
    	wp_enqueue_style( 'mwi_sc_button', $this->plugin_url . 'assets/admin/css/shortcode-button.css' );
    	
    }
    	
/**	=============================
    *
    * Frontend Scripts
    *
    ============================= */
	
	public function frontend_styles_scripts() {
    	
    	// styles
    	if( $this->active_addons() &&  $this->getValue('styles') ) {
            wp_register_style( 'mwi-addon-styles', plugins_url('assets/frontend/css/addon-styles.css', __FILE__) );
		    wp_enqueue_style( 'mwi-addon-styles' );
		}
		
    	// scripts
		wp_register_script( 'mwi-js', plugins_url('/assets/frontend/js/scripts.min.js', __FILE__), array(), false, true);
		wp_enqueue_script( 'mwi-js' );
		
	} 
	
/**	=============================
    *
    * Check for Active Addons
    *
    * return array|bool Returns array of active addons, if found, or false if not
    *
    ============================= */
    
    public function active_addons() {
        
        $active_addons = array();
        $active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
        
        if ( in_array( 'mwi-shortcodes/mwi-shortcodes.php', $active_plugins ) )
            $active_addons[] = 'mwi-shortcodes';
            
        if ( in_array( 'mwi-category-listing/mwi-category-listing.php', $active_plugins ) )
            $active_addons[] = 'mwi-category-listing';
            
        if( empty($active_addons) )
            return false;
        
        return $active_addons;        
    }
  
}

$jck_mwi = new jck_mwi();

include_once('inc/template-functions.php');