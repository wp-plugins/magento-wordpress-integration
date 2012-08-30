<?php
/*
* @package jck_mwi_widgets requires jck_mwi (magento wordpress integration)
* @version 1.1
* @updated 2.1.4
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
		global $jck_mwi;
		if($jck_mwi->u('widgetsshortcodes')) { register_widget( 'cat_prods' ); }
		if($jck_mwi->u('widgetspecific')) { register_widget( 'cat_specific' ); }
	}
	
  ################################################
  ###                                          ###
  ###    Category fields for widgetspecific    ###
  ###                                          ###
  ################################################
	
	function extra_category_fields( $tag ) { //check for existing featured ID
	    $t_id = $tag->term_id;
	    $cat_meta = get_option( "widgetspecific_$t_id");
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="cat_Image_url"><?php _e('Related Magento Category ID', 'mwi'); ?></label></th>
			<td>
				<input type="text" name="Cat_meta[mage_cat_id]" id="Cat_meta[mage_cat_id]" size="3" style="width:60%;" value="<?php echo $cat_meta['mage_cat_id'] ? $cat_meta['mage_cat_id'] : ''; ?>"><br />
		        <span class="description"><?php _e('Enter the Magento category ID that relates to this WordPress category.', 'mwi'); ?></span>
		    </td>
		</tr>
		<?php
	}
	
	function save_extra_category_fileds( $term_id ) {
	    if ( isset( $_POST['Cat_meta'] ) ) {
	        $t_id = $term_id;
	        $cat_meta = get_option( "widgetspecific_$t_id");
	        $cat_keys = array_keys($_POST['Cat_meta']);
	            foreach ($cat_keys as $key){
	            if (isset($_POST['Cat_meta'][$key])){
	                $cat_meta[$key] = $_POST['Cat_meta'][$key];
	            }
	        }
	        //save the option array
	        update_option( "widgetspecific_$t_id", $cat_meta );
	    }
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
  		global $jck_mwi;
		add_action( 'widgets_init', array(&$this, 'register_widgets') );
		if($jck_mwi->u('widgetspecific')) { 
			add_action ( 'edit_category_form_fields', array(&$this, 'extra_category_fields') ); 
			add_action ( 'edited_category', array(&$this, 'save_extra_category_fileds') );
		}
  }  
	
}
$jck_mwi_widgets = new jck_mwi_widgets; // Start an instance of the plugin class
include_once('inc/widget-cat-prods.php');
include_once('inc/widget-cat-specific.php');