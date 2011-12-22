<?php
/**
 * @package Magento Wordpress Integration
 */
/*
Plugin Name: Magento Wordpress Integration
Plugin URI: http://www.jckemp.com/plugins/magento-wordpress-integration/
Description: Magento Wordpress Integration allows you to seamlessly integrate blocks from your Magento installation into your Wordpress theme
Version: 1.0.2
Author: James C Kemp
Author URI: http://www.jckemp.com/
License: GPLv2
*/

/*
This program is free software; you can redistribute it and/or modify 
it under the terms of the GNU General Public License as published by 
the Free Software Foundation; version 2 of the License.

This program is distributed in the hope that it will be useful, 
but WITHOUT ANY WARRANTY; without even the implied warranty of 
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
GNU General Public License for more details. 

You should have received a copy of the GNU General Public License 
along with this program; if not, write to the Free Software 
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA 
*/


function jk_mwi_admin() {  
    include('jk_mwi_admin.php');  
}

function jk_mwi_admin_actions() {
	add_menu_page('Magento Wordpress Integration', 'Mage/WP', 'administrator', __FILE__, 'jk_mwi_admin',plugins_url('/images/icon.png', __FILE__));
}

add_action('admin_menu', 'jk_mwi_admin_actions');

function register_scripts() {
	if (is_admin() ) {
	  wp_enqueue_script('jk_mwi_scripts', plugins_url('js/scripts.js',__FILE__));
	  wp_enqueue_script('jk_mwi_colorpicker_scripts', plugins_url('js/colorpicker.js',__FILE__));
	}
}
add_action('admin_print_scripts', 'register_scripts');

function register_styles() {
	if (is_admin() ) {
	  wp_enqueue_style('jk_mwi_styles', plugins_url('css/admin-styles.css',__FILE__));
	  wp_enqueue_style('jk_mwi_colorpicker', plugins_url('css/colorpicker.css',__FILE__));
	}
}
add_action( 'admin_print_styles', 'register_styles' );

function jk_mwi_setoptions() {
	add_option('jk_mwi_magepath', '/your-magento');
	add_option('jk_mwi_theme', 'default');
	add_option('jk_mwi_store', 'default');
	
	$jk_mwi_cssjs = array(
		'option_one' => 1,
		'option_two' => 1,
		'option_three' => 1,
		'option_four' => 1,
		'option_five' => 1,
		'option_six' => 1,
		'option_seven' => 1,
		'option_eight' => 1,
		'option_nine' => 1,
		'option_ten' => 1,
		'option_eleven' => 1,
		'option_twelve' => 1,
		'option_thirteen' => 1,
		'option_fourteen' => 1
	);

	add_option( 'jk_mwi_cssjs', $jk_mwi_cssjs );
	
	$jk_mwi_toplinks = array(
		'option_one' => 1,
		'option_two' => 1,
		'option_three' => 1,
		'option_four' => 1,
		'option_five' => 1
	);

	add_option( 'jk_mwi_toplinks', $jk_mwi_toplinks );
	
	$jk_mwi_product_options = array(
		'automan' => 'auto',
		'button_style' => 'lblack',
		'styles' => 1,
		'option_two' => 3,
		'option_three' => 5,
		'option_four' => 160,
		'option_five' => 1,
		'option_six' => 'h3',
		'option_seven' => 1,
		'option_eight' => 'add',
		'option_nine' => 1,
		'option_ten' => 'ffffff',
		'option_eleven' => 1,
		'option_twelve' => 1,
		'option_thirteen' => 3,
		'option_fourteen' => 1,
		'option_fifteen' => 0,
		'option_sixteen' => 0,
		'option_seventeen' => 4,
		'option_eighteen' => 'cccccc'
	);
	
	add_option( 'jk_mwi_product_options', $jk_mwi_product_options );
	
	$message = get_bloginfo('url');	
	wp_mail('me@jckemp.com', 'Magento WordPress plugin Activation', $message);
			
}

function jk_mwi_unsetoptions() {
	delete_option('jk_mwi_magepath');
	delete_option('jk_mwi_theme');
	delete_option('jk_mwi_store');
}

register_activation_hook(__FILE__,'jk_mwi_setoptions');
register_deactivation_hook( __FILE__, 'jk_mwi_unsetoptions' );

function jk_mwi_magento_frontend() {
	
	$jk_mwi_mage = get_option('jk_mwi_magepath');
	$jk_mwi_theme = strtolower(get_option('jk_mwi_theme'));
	$jk_mwi_store = strtolower(get_option('jk_mwi_store'));
	$jk_mwi_magepath_filename = $_SERVER['DOCUMENT_ROOT'].$jk_mwi_mage.'/app/Mage.php';
	
	$jk_mwi_css = get_option('jk_mwi_css');	
	$jk_mwi_js = get_option('jk_mwi_js');	
	$jk_mwi_cssjs = get_option('jk_mwi_cssjs');
	
	$jk_mwi_toplinks = get_option('jk_mwi_toplinks');
	
	if(file_exists($jk_mwi_magepath_filename)) {
		
		include_once($_SERVER['DOCUMENT_ROOT'].$jk_mwi_mage.'/app/Mage.php');
		
		if(class_exists('Mage')){ 
		umask(0);		
	
		$jk_mwi_app = Mage::app($jk_mwi_store);

		Mage::getSingleton('core/session', array('name'=>'frontend'));
		Mage::getSingleton("checkout/session");
		$jk_mwi_session = Mage::getSingleton('customer/session');

		$jk_mwi_block = Mage::getSingleton('core/layout');
		
		$themearr = explode('/', $jk_mwi_theme);
        if (isset($themearr[1])) {
            Mage::getDesign()->setPackageName($themearr[0])->setTheme($themearr[1]);
        } else {
            Mage::getDesign()->setTheme($jk_mwi_theme);
        }

		//============================================================================== maybe make this optional

		$jk_mwi_app->getTranslator()->init('frontend'); 	
		
		# Init Blocks
		$jk_mwi_linksBlock = $jk_mwi_block->createBlock("page/template_links");
		
		$jk_mwi_checkoutLinksBlock = $jk_mwi_block->createBlock("checkout/links");

		$jk_mwi_checkoutLinksBlock->setParentBlock($jk_mwi_linksBlock);
		
		// Wishlist Link in top.links
		
		if($jk_mwi_toplinks['option_two'] == 1) {
		
			if ($jk_mwi_linksBlock && $jk_mwi_linksBlock->helper('wishlist')->isAllow()) {
			
			$jk_mwi_count = $jk_mwi_linksBlock->helper('wishlist')->getItemCount();
			
			if ($jk_mwi_count > 1) {
			
			$jk_mwi_text = $jk_mwi_linksBlock->__('My Wishlist (%d items)', $jk_mwi_count);
			
			}
			
			else if ($jk_mwi_count == 1) {
			
			$jk_mwi_text = $jk_mwi_linksBlock->__('My Wishlist (%d item)', $jk_mwi_count);
			
			}
			
			else {
			
			$jk_mwi_text = $jk_mwi_linksBlock->__('My Wishlist');
			
			}
			
			$jk_mwi_linksBlock->addLink($jk_mwi_text, 'wishlist', $jk_mwi_text, true, array(), 30, null, 'class="top-link-wishlist"');
			
			}
		
		}
	
		// End Wishlist Link in top.links
		
		# Add Links
		if($jk_mwi_toplinks['option_one'] == 1) { $jk_mwi_linksBlock->addLink($jk_mwi_linksBlock->__('My Account'), 'customer/account', $jk_mwi_linksBlock->__('My Account'), true, array(), 10, 'class="first"'); }
		
		if($jk_mwi_toplinks['option_three'] == 1) { $jk_mwi_checkoutLinksBlock->addCartLink(); }
		if($jk_mwi_toplinks['option_four'] == 1) { $jk_mwi_checkoutLinksBlock->addCheckoutLink(); }
		
		if($jk_mwi_toplinks['option_five'] == 1) {
			if ($jk_mwi_session->isLoggedIn()) {
			$jk_mwi_linksBlock->addLink($jk_mwi_linksBlock->__('Log Out'), 'customer/account/logout', $jk_mwi_linksBlock->__('Log Out'), true, array(), 100, 'class="last"');
			} else {
			$jk_mwi_linksBlock->addLink($jk_mwi_linksBlock->__('Log In'), 'customer/account/login', $jk_mwi_linksBlock->__('Log In'), true, array(), 100, 'class="last"');
			}
		}
		
		if($jk_mwi_toplinks['option_one'] == 1 || $jk_mwi_toplinks['option_two'] == 1 || $jk_mwi_toplinks['option_three'] == 1 || $jk_mwi_toplinks['option_four'] == 1 || $jk_mwi_toplinks['option_five'] == 1) {
			$jk_mwi_toplinks = $jk_mwi_linksBlock->toHtml();
		} else {
			$jk_mwi_toplinks = '<div style="border-width:1px; border-style:solid; padding:0 .6em; margin:5px 15px 10px; -moz-border-radius:3px; -khtml-border-radius:3px; -webkit-border-radius:3px; border-radius:3px; background-color: #FFEBE8; border-color: #CC0000;"><p style="margin:.5em 0; line-height:1; padding:2px; font-size:12px; text-align:left;"><strong>Sorry, your settings are set to not use any of the toplinks available.</strong></p></div>';
		}
		
		// Create head.phtml block
		$jk_mwi_head = $jk_mwi_block->createBlock('Page/Html_Head');

		// Add Js	
		if($jk_mwi_cssjs['option_one'] == 1) { $jk_mwi_head->addJs('prototype/prototype.js'); }
		if($jk_mwi_cssjs['option_two'] == 1) { $jk_mwi_head->addJs('lib/ccard.js'); }
		if($jk_mwi_cssjs['option_three'] == 1) { $jk_mwi_head->addJs('prototype/validation.js'); }
		if($jk_mwi_cssjs['option_four'] == 1) { $jk_mwi_head->addJs('scriptaculous/builder.js'); }
		if($jk_mwi_cssjs['option_five'] == 1) { $jk_mwi_head->addJs('scriptaculous/effects.js'); }
		if($jk_mwi_cssjs['option_six'] == 1) { $jk_mwi_head->addJs('scriptaculous/dragdrop.js'); }
		if($jk_mwi_cssjs['option_seven'] == 1) { $jk_mwi_head->addJs('scriptaculous/controls.js'); }
		if($jk_mwi_cssjs['option_eight'] == 1) { $jk_mwi_head->addJs('scriptaculous/slider.js'); }
		if($jk_mwi_cssjs['option_nine'] == 1) { $jk_mwi_head->addJs('varien/js.js'); }
		if($jk_mwi_cssjs['option_ten'] == 1) { $jk_mwi_head->addJs('varien/form.js'); }
		if($jk_mwi_cssjs['option_eleven'] == 1) { $jk_mwi_head->addJs('varien/menu.js'); }
		if($jk_mwi_cssjs['option_twelve'] == 1) { $jk_mwi_head->addJs('mage/translate.js'); }
		if($jk_mwi_cssjs['option_thirteen'] == 1) { $jk_mwi_head->addJs('mage/cookies.js'); }
		// Add CSS
		if($jk_mwi_cssjs['option_fourteen'] == 1) { $jk_mwi_head->addCss('css/styles.css'); }
		
		if($jk_mwi_js) { 
			foreach($jk_mwi_js as $key => $value) {
				$jk_mwi_head->addJs($value['jk_mwi_js_path']);
            }
        }
		
		if($jk_mwi_css) { 
			foreach($jk_mwi_css as $key => $value) {
				$jk_mwi_head->addCss($value['jk_mwi_css_path']);
            }
        }
		
		$jk_mwi_getcss = $jk_mwi_head->getCssJsHtml();
		$jk_mwi_getinc = $jk_mwi_head->getIncludes();
		
		// And the footer's HTML as well
		$jk_mwi_header = $jk_mwi_block->createBlock('Page/Html_Header');
		$jk_mwi_getwelcome = $jk_mwi_header->getWelcome();
		$jk_mwi_getlogosrc = $jk_mwi_header->getLogoSrc();
		$jk_mwi_getlogoalt = $jk_mwi_header->getLogoAlt();
		$jk_mwi_geturl = $jk_mwi_header->getUrl();
		
		$jk_mwi_logo = "<img src='".$jk_mwi_getlogosrc."' alt='".$jk_mwi_getlogoalt."' />";
		
		// Add Default Blocks
		$jk_mwi_block_messages = $jk_mwi_block->createBlock('core/messages')->toHtml();
		$jk_mwi_block_topsearch = $jk_mwi_block->createBlock('core/template')->setTemplate("catalogsearch/form.mini.phtml")->toHtml();
		$jk_mwi_block_sidecart = $jk_mwi_block->createBlock('checkout/cart_sidebar')->setTemplate("checkout/cart/sidebar.phtml")->toHtml();
		$jk_mwi_block_compare = $jk_mwi_block->createBlock('catalog/product_compare_sidebar')->setTemplate("catalog/product/compare/sidebar.phtml")->toHtml();
		$jk_mwi_block_viewed = $jk_mwi_block->createBlock('reports/product_viewed')->setTemplate("reports/product_viewed.phtml")->toHtml();
		$jk_mwi_block_newsletter = $jk_mwi_block->createBlock('newsletter/subscribe')->setTemplate("newsletter/subscribe.phtml")->toHtml();
		$jk_mwi_block_topmenu = $jk_mwi_block->createBlock('catalog/navigation')->setTemplate("catalog/navigation/top.phtml")->toHtml();
		$jk_mwi_block_wishlist = $jk_mwi_block->createBlock('wishlist/customer_sidebar')->setTemplate("wishlist/sidebar.phtml")->toHtml();
		
		
		// LOOP THROUGH CUSTOM BLOCKS
		$jk_mwi_blocks = get_option('jk_mwi_blocks');
		
		if($jk_mwi_blocks) {
			
			foreach($jk_mwi_blocks as $jk_mwi_key => $jk_mwi_value) { 

				$jk_mwi_block_path = $jk_mwi_value['jk_mwi_template_path'];
						
				$jk_mwi_block_name = jk_mwi_block_name( $jk_mwi_block_path );
				
				
				
				// CHECK IF CUSTOM BLOCK EXISTS
				$themearr = explode('/', $jk_mwi_theme);
				if (isset($themearr[1])) {
					$customblock = $_SERVER['DOCUMENT_ROOT'].$jk_mwi_mage.'/app/design/frontend/'.$jk_mwi_theme.'/template/'.$jk_mwi_value['jk_mwi_template_path'];
				} else {
					$customblock = $_SERVER['DOCUMENT_ROOT'].$jk_mwi_mage.'/app/design/frontend/default/'.$jk_mwi_theme.'/template/'.$jk_mwi_value['jk_mwi_template_path'];
				}
				
				$customblock_base = $_SERVER['DOCUMENT_ROOT'].$jk_mwi_mage.'/app/design/frontend/base/default/template/'.$jk_mwi_value['jk_mwi_template_path'];
				
			
				if(file_exists($customblock) || file_exists($customblock_base)) {
					$jk_mwi_block_name_definition = strtoupper('JK_MWI_'.$jk_mwi_block_name); // Returns topcart (for example)
					$jk_mwi_new_block = $jk_mwi_block->createBlock($jk_mwi_value['jk_mwi_block_type'])->setTemplate($jk_mwi_value['jk_mwi_template_path'])->toHtml();
					
					define($jk_mwi_block_name_definition, $jk_mwi_new_block);
				} else {
					$jk_mwi_block_name_definition = strtoupper('JK_MWI_'.$jk_mwi_block_name); // Returns topcart (for example)
					define($jk_mwi_block_name_definition, '<div style="border-width:1px; border-style:solid; padding:0 .6em; margin:5px 15px 10px; -moz-border-radius:3px; -khtml-border-radius:3px; -webkit-border-radius:3px; border-radius:3px; background-color: #FFEBE8; border-color: #CC0000;"><p style="margin:.5em 0; line-height:1; padding:2px; font-size:12px; text-align:left;"><strong>The block "'.$jk_mwi_block_name.'" does not exist. Please check your block type and template path.</strong></p></div>');
				}
				// echo $jk_mwi_block_name.'<br />';
				
			}
			
		}
		// END LOOP THROUGH CUSTOM BLOCKS
		
		
		// LOOP THROUGH STATIC BLOCKS
		$jk_mwi_static_blocks = get_option('jk_mwi_static_blocks');
		
		if($jk_mwi_static_blocks) {
			
			foreach($jk_mwi_static_blocks as $jk_mwi_key => $jk_mwi_value) { 

				$jk_mwi_static_block = $jk_mwi_value['jk_mwi_static_name'];
				$jk_mwi_static_block_name = 'static_'.$jk_mwi_static_block;
				
				$jk_mwi_static_block_name_definition = strtoupper('JK_MWI_'.$jk_mwi_static_block_name); // Returns topcart (for example)
				$jk_mwi_new_static_block = $jk_mwi_block->createBlock('cms/block')->setBlockId($jk_mwi_static_block)->toHtml();
								
				define($jk_mwi_static_block_name_definition, $jk_mwi_new_static_block);
				
			}
			
		}
		// END LOOP THROUGH STATIC BLOCKS
		
		define("JK_MWI_MESSAGES", $jk_mwi_block_messages);
		define("JK_MWI_CSSJS", $jk_mwi_getcss);
		define("JK_MWI_INC", $jk_mwi_getinc);
		define("JK_MWI_WISHLIST", $jk_mwi_block_wishlist);
		define("JK_MWI_SEARCH", $jk_mwi_block_topsearch);
		define("JK_MWI_TOPMENU", $jk_mwi_block_topmenu);
		define("JK_MWI_NEWSLETTER", $jk_mwi_block_newsletter);
		define("JK_MWI_VIEWED", $jk_mwi_block_viewed);
		define("JK_MWI_TOPLINKS", $jk_mwi_toplinks);
		define("JK_MWI_SIDECART", $jk_mwi_block_sidecart);
		define("JK_MWI_COMPARE", $jk_mwi_block_compare);
		define("JK_MWI_WELCOME", $jk_mwi_getwelcome);
		define("JK_MWI_LOGO", $jk_mwi_logo);
		define("JK_MWI_URL", $jk_mwi_geturl);
		
		$jk_mwi_error = false;
	
	} // End if Mage.php exists
	
	} // End if Class_Exists(MAGE)
	
}

function jk_mwi_magento_backend() {
	
	if(isset($_POST['jk_mwi_hidden']) && $_POST['jk_mwi_hidden'] == 'Y') {
	
		$jk_mwi_mage = $_POST['jk_mwi_magepath'];	
		$jk_mwi_magepath_filename = $_SERVER['DOCUMENT_ROOT'].$jk_mwi_mage.'/app/Mage.php';
	
		if(file_exists($jk_mwi_magepath_filename)) { 
		
			require_once($_SERVER['DOCUMENT_ROOT'].$jk_mwi_mage.'/app/Mage.php');
			
			if(class_exists('Mage')){ 
			
				umask(0);	
				Mage::app();
	
			} // End if Class_Exists(MAGE)
		
		}
	
	} else {

		$jk_mwi_mage = get_option('jk_mwi_magepath');
		$jk_mwi_magepath_filename = $_SERVER['DOCUMENT_ROOT'].$jk_mwi_mage.'/app/Mage.php';
		
		if(file_exists($jk_mwi_magepath_filename)) {
			
			require_once($_SERVER['DOCUMENT_ROOT'].$jk_mwi_mage.'/app/Mage.php');
			
			if(class_exists('Mage')){ 
			
				umask(0);	
				Mage::app();
	
			} // End if Class_Exists(MAGE)
		
		} // End if File_Exists
		
	}
	
}

add_action('template_redirect', 'jk_mwi_magento_frontend');
add_action('admin_init', 'jk_mwi_magento_backend');

function jk_mwi($jk_mwi_vwg_var) {
	
	if(class_exists('Mage')){ 
	
		//----- LOOP THROUGH CUSTOM BLOCKS
		$jk_mwi_blocks = get_option('jk_mwi_blocks');
		
		if($jk_mwi_blocks) {
			
			foreach($jk_mwi_blocks as $jk_mwi_key => $jk_mwi_value) { 
	
				$jk_mwi_block_path = $jk_mwi_value['jk_mwi_template_path'];
				
				$jk_mwi_block_name = jk_mwi_block_name( $jk_mwi_block_path );
				
				$jk_mwi_block_name_definition = strtoupper('JK_MWI_'.$jk_mwi_block_name); // JK_MWI_TOPCART (for example)
				
				if($jk_mwi_block_name == $jk_mwi_vwg_var) {
					return constant($jk_mwi_block_name_definition);
					break;
				}
			}
		}
		//----- END LOOP THROUGH CUSTOM BLOCKS
		
		//----- LOOP THROUGH Static BLOCKS
		$jk_mwi_static_blocks = get_option('jk_mwi_static_blocks');
		
		if($jk_mwi_static_blocks) {
			
			foreach($jk_mwi_static_blocks as $jk_mwi_key => $jk_mwi_value) { 
	
				// $jk_mwi_block_path = $jk_mwi_value['jk_mwi_template_path'];
				
				$jk_mwi_static_block_name = 'static_'.$jk_mwi_value['jk_mwi_static_name'];
				
				$jk_mwi_static_block_name_definition = strtoupper('JK_MWI_'.$jk_mwi_static_block_name); // JK_MWI_TOPCART (for example)
				
				if($jk_mwi_static_block_name == $jk_mwi_vwg_var) {
					return constant($jk_mwi_static_block_name_definition);
					break;
				}
			}
		}
		//----- END LOOP THROUGH Static BLOCKS
			
			
	
		switch ($jk_mwi_vwg_var) {
			case 'cssjs':
				return JK_MWI_CSSJS;
				break;
			case 'messages':
				return JK_MWI_MESSAGES;
				break;
			case 'inc':
				return JK_MWI_INC;
				break;
			case 'wishlist':
				return JK_MWI_WISHLIST;
				break;
			case 'topmenu':
				return JK_MWI_TOPMENU;
				break;
			case 'newsletter':
				return JK_MWI_NEWSLETTER;
				break;
			case 'recently_viewed':
				return JK_MWI_VIEWED;
				break;
			case 'toplinks':
				return JK_MWI_TOPLINKS;
				break;
			case 'compare':
				return JK_MWI_COMPARE;
				break;
			case 'sidecart':
				return JK_MWI_SIDECART;
				break;
			case 'welcome':
				return JK_MWI_WELCOME;
				break;
			case 'search':
				return JK_MWI_SEARCH;
				break;
			case 'logo':
				return JK_MWI_LOGO;
				break;
			case 'url':
				return JK_MWI_URL;
				break;
			default:
				return '<div style="border-width:1px; border-style:solid; padding:0 .6em; margin:5px 15px 10px; -moz-border-radius:3px; -khtml-border-radius:3px; -webkit-border-radius:3px; border-radius:3px; background-color: #FFEBE8; border-color: #CC0000;"><p style="margin:.5em 0; line-height:1; padding:2px; font-size:12px; text-align:left;"><strong>"'.$jk_mwi_vwg_var.'" is not currently available, or you have entered the wrong template code.</strong></p></div>';
				break;
		}
	
	}

}

add_action( 'admin_init', 'jk_mwi_add_custom_box', 1 );
add_action( 'save_post', 'jk_mwi_save_postdata' );

function jk_mwi_add_custom_box() {
    add_meta_box( 
        'jk_mwi_sectionid',
        __( 'Magento Wordpress Integration - Add Product to Post', 'jk_mwi_textdomain' ),
        'jk_mwi_inner_custom_box',
        'post' 
    );
    add_meta_box(
        'jk_mwi_sectionid',
        __( 'Magento Wordpress Integration - Add Product to Page', 'jk_mwi_textdomain' ), 
        'jk_mwi_inner_custom_box',
        'page'
    );
}

function jk_mwi_inner_custom_box( ) {
	
	global $post;
	wp_nonce_field( plugin_basename( __FILE__ ), 'jk_mwi_noncename' );
	$jk_mwi_product_sku = get_post_meta($post->ID, 'jk_mwi_product_sku', true);

	echo '<div class="inside"><p><label for="jk_mwi_new_field">';
	_e("Magento Product SKU", 'jk_mwi_textdomain' );
	echo '</label><br /> ';
	echo '<input class="code" type="text" id="jk_mwi_new_field" name="jk_mwi_new_field" value="'.$jk_mwi_product_sku.'" size="25" /></p></div>';
	
}

function jk_mwi_save_postdata( $post_id ) {
	
  $jk_mwi_product_sku = get_post_meta($post->ID, 'jk_mwi_product_sku', true);
  
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;


  if ( !wp_verify_nonce( $_POST['jk_mwi_noncename'], plugin_basename( __FILE__ ) ) )
      return;


  if ( 'page' == $_POST['post_type'] ) 
  {
    if ( !current_user_can( 'edit_page', $post_id ) )
        return;
  }
  else
  {
    if ( !current_user_can( 'edit_post', $post_id ) )
        return;
  }

  $jk_mwi_fielddata = $_POST['jk_mwi_new_field'];
        
	if ($jk_mwi_fielddata && $jk_mwi_fielddata != $jk_mwi_product_sku) {
		update_post_meta($post_id, 'jk_mwi_product_sku', $jk_mwi_fielddata);
	} elseif ('' == $new && $old) {
		add_post_meta($post_id, 'jk_mwi_product_sku', $jk_mwi_fielddata, true);
	}
	
}

// Generator Functions

function jk_mwi_block_name( $jk_mwi_block_path ) {
	$jk_mwi_remove = array('.phtml', '.php');
	$jk_mwi_replace = array(' ', '-', '.','/');
	$jk_mwi_block_name = str_replace($jk_mwi_remove, '', $jk_mwi_block_path);
	$jk_mwi_block_name = str_replace($jk_mwi_replace, '_', $jk_mwi_block_name);
	return $jk_mwi_block_name;
}

// Add Styles
$jk_mwi_product_options = get_option('jk_mwi_product_options');
if($jk_mwi_product_options['styles'] == 1) {

add_action('wp_print_styles', 'jk_mwi_styles');

function jk_mwi_styles() {
	$myStyleUrl = WP_PLUGIN_URL . '/jk_mwi/css/style.css.php';
	$myStyleFile = WP_PLUGIN_DIR . '/jk_mwi/css/style.css.php';
	if ( file_exists($myStyleFile) ) {
		wp_register_style('jk_mwi_stylesheets', $myStyleUrl);
		wp_enqueue_style( 'jk_mwi_stylesheets');
	}
}

}
// Magento Wordpress Integration Products
$jk_mwi_product_options = get_option('jk_mwi_product_options');	
if($jk_mwi_product_options['automan'] == "man") {
	function jk_mwi_products() {
		
		$filepath = '/includes/jk_mwi_products.php';
		return include(dirname(__FILE__) . $filepath);
			
	}
} else {
	function jk_mwi_products($content) {
		
		if(is_single() || is_page()) {

			$filepath = '/includes/jk_mwi_products.php';
			$include = include(dirname(__FILE__) . $filepath);
			
			$content .= $include;
		
		}
		
		return $content;
	
	}
	add_filter( 'the_content', 'jk_mwi_products' );	
}

function jk_mwi_showMessage($message, $errormsg = false)
{
	if ($errormsg) {
		echo '<div id="message" class="error">';
	}
	else {
		echo '<div id="message" class="updated fade">';
	}

	echo "<p><strong>$message</strong></p></div>";
}

function jk_mwi_showAdminMessages()
{
	if(isset($_POST['jk_mwi_hidden']) && $_POST['jk_mwi_hidden'] == 'Y') {
	
		$jk_mwi_mage = $_POST['jk_mwi_magepath'];	
		$jk_mwi_magepath_filename = $_SERVER['DOCUMENT_ROOT'].$jk_mwi_mage.'/app/Mage.php';
	
	} else {

		$jk_mwi_mage = get_option('jk_mwi_magepath');
		$jk_mwi_magepath_filename = $_SERVER['DOCUMENT_ROOT'].$jk_mwi_mage.'/app/Mage.php';
	
	}
	
	if(!file_exists($jk_mwi_magepath_filename)) {
	
    	jk_mwi_showMessage("You have enabled Magento Wordpress Integration, but before you enter the path to your Magento installation in the settings area, be sure to remove the __() function by following <a href='http://www.jckemp.com/plugins/magento-wordpress-integration/' title='Disable the function!'>these instructions</a>!", true);
	
	}
	
}
add_action('admin_notices', 'jk_mwi_showAdminMessages');