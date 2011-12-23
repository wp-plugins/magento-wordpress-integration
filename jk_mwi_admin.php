<?php if(isset($_POST['jk_mwi_hidden']) && $_POST['jk_mwi_hidden'] == 'Y') {
	
	$jk_mwi_mage = $_POST['jk_mwi_magepath'];			
	$jk_mwi_magepath_filename = $_SERVER['DOCUMENT_ROOT'].$jk_mwi_mage.'/app/Mage.php';

	if(!file_exists($jk_mwi_magepath_filename)) { 

		echo '<div class="error"><p><strong>The path "'.$jk_mwi_mage.'" does not exist, please re-enter it below.</strong></p></div>';
	
	} else  { 
	
		update_option('jk_mwi_magepath', $jk_mwi_mage); 
	
	}
	
	$jk_mwi_theme = $_POST['jk_mwi_theme'];
	$jk_mwi_blocks = $_POST['jk_mwi_blocks'];
	$jk_mwi_static_blocks = $_POST['jk_mwi_static_blocks'];
	$jk_mwi_css = $_POST['jk_mwi_css'];
	$jk_mwi_js = $_POST['jk_mwi_js'];			
	$jk_mwi_cssjs = $_POST['jk_mwi_cssjs'];
	$jk_mwi_toplinks = $_POST['jk_mwi_toplinks'];			
	$jk_mwi_product_options = $_POST['jk_mwi_product_options'];
	
	//Form data sent		
	update_option('jk_mwi_theme', $jk_mwi_theme);
	
	$jk_mwi_store = $_POST['jk_mwi_store'];
	
	if(file_exists($jk_mwi_magepath_filename)) {
		$stores = Mage::getModel('core/store')->getCollection();
	
		$codes = array();
		foreach($stores as $store) {
			$store_data = $store->getData();
			
			foreach($store_data as $key => $data) {
				if($key == 'code') { array_push($codes, $data); }
				
			}
		}
		
		if(!in_array($jk_mwi_store,$codes)) {
			echo '<div class="error"><p><strong>The store-view code "'.$jk_mwi_store.'" does not exist, please re-enter it below.</strong></p></div>';
        } else {
            update_option('jk_mwi_store', $jk_mwi_store);
        }
		
	}

	$jk_mwi_blocks = array_filter(array_map('array_filter', $jk_mwi_blocks));
	$jk_mwi_blocks = array_values($jk_mwi_blocks);
	update_option('jk_mwi_blocks', $jk_mwi_blocks);
	
	$jk_mwi_static_blocks = array_filter(array_map('array_filter', $jk_mwi_static_blocks));
	$jk_mwi_static_blocks = array_values($jk_mwi_static_blocks);
	update_option('jk_mwi_static_blocks', $jk_mwi_static_blocks);
	
	$jk_mwi_css = array_filter(array_map('array_filter', $jk_mwi_css));
	$jk_mwi_css = array_values($jk_mwi_css);
	update_option('jk_mwi_css', $jk_mwi_css);
	
	$jk_mwi_js = array_filter(array_map('array_filter', $jk_mwi_js));
	$jk_mwi_js = array_values($jk_mwi_js);
	update_option('jk_mwi_js', $jk_mwi_js);
	
	update_option('jk_mwi_cssjs', $jk_mwi_cssjs);
	update_option('jk_mwi_toplinks', $jk_mwi_toplinks);
	update_option('jk_mwi_product_options', $jk_mwi_product_options);
	?>
            
    <div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
    
<?php } else {
	
	//Normal page display
	$jk_mwi_mage = get_option('jk_mwi_magepath');
	$jk_mwi_theme = get_option('jk_mwi_theme');
	$jk_mwi_store = get_option('jk_mwi_store');
	
	$jk_mwi_blocks = get_option('jk_mwi_blocks');	
	$jk_mwi_static_blocks = get_option('jk_mwi_static_blocks');	
	$jk_mwi_css = get_option('jk_mwi_css');	
	$jk_mwi_js = get_option('jk_mwi_js');	
	
	$jk_mwi_cssjs = get_option('jk_mwi_cssjs');	
	$jk_mwi_toplinks = get_option('jk_mwi_toplinks');	
	
	$jk_mwi_product_options = get_option('jk_mwi_product_options');	
				
} ?>

<div class="wrap">
  <?php    echo "<h2>" . __( 'Magento/Wordpress Integration Options', 'jk_mwi_trdom' ) . "</h2>"; ?>
  <ul class="tabs">
    <li class="active"><a href="#tab1">Initial Setup</a></li>
    <li><a href="#tab2">JS &amp; CSS</a></li>
    <li><a href="#tab3">Toplinks</a></li>
    <li><a href="#tab4">Custom Magento Blocks</a></li>
    <li><a href="#static">Static Blocks</a></li>
    <li><a href="#tab5">Products in Posts/Pages</a></li>
    <li><a href="#tab6">Usage</a></li>
  </ul>
  <form name="jk_mwi_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <input type="hidden" name="jk_mwi_hidden" value="Y">
    <div class="tab_container">
      <div id="tab1" class="tab_content first">
        <table class="form-table">
          <tbody>
            <tr valign="top">
              <th scope="row"><label for="jk_mwi_magepath">Path to Magento:</label></th>
              <td><input type="text" name="jk_mwi_magepath" value="<?php echo $jk_mwi_mage; ?>" size="20">
                <span class="description">E.g: If your Magento is at http://www.domain.com/shop then enter <strong>/shop</strong>, if it is a root installation of Magento, simply enter <strong>/</strong></span></td>
            </tr>
            <tr valign="top">
              <th scope="row"><label for="jk_mwi_theme">Magento Theme Name:</label></th>
              <td><input type="text" name="jk_mwi_theme" value="<?php echo $jk_mwi_theme; ?>" size="20">
                <span class="description">E.g: default, modern, blank, packagename/themename, etc</span></td>
            </tr>
            <tr valign="top">
              <th scope="row"><label for="jk_mwi_store">Magento Store-view Code:</label></th>
              <td><input type="text" name="jk_mwi_store" value="<?php echo $jk_mwi_store; ?>" size="20">
                <span class="description">E.g: If you only have one store on your Magento installation, leave this as default - otherwise you will need to enter the store-view code for the store you want to get blocks from.</span></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div id="tab2" class="tab_content">
        <p>Currently, Magento Wordpress Integration will only load in the default javascript and stylesheets. Below you can choose which javascript and stylesheets you want to keep, as well as add additional ones.</p>
        <p>All of the below get called out when you use the following snippet in your Wordpress theme's header.php file:</p>
        <p><code>&lt;?php echo jk_mwi('cssjs'); ?&gt;</code></p>
        
        <hr />
        
        <div class="wg_left">
            
            	<p><a onclick="more_css(); return false;" href="#">Add More Stylesheets</a></p>
                <table width="100%" id="jk_mwi_css" border="0" cellspacing="10">
                  <thead>
                    <tr>
                      <td><strong>CSS Path</strong> <em>(All *.css files are sourced from http://www.domain.com/magento/skin/frontend/packagename/themename/)</em> <br /><br /><strong>Example:</strong> css/styles.css<br /><br /></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if($jk_mwi_css) { ?>
                    <?php $i = 1; foreach($jk_mwi_css as $key => $value) { ?>
                    <tr<?php echo ($i==1) ? ' id="jk_mwi_first_css"' : ''; ?>>
                      <td><input type="text" name="jk_mwi_css[<?php echo $key; ?>][jk_mwi_css_path]" value="<?php echo $value['jk_mwi_css_path']; ?>" style="width:90%;"></td>
                    </tr>
                    <?php $i++; } ?>
                    <?php } else { ?>
                    <tr id="jk_mwi_first_css">
                      <td><input type="text" name="jk_mwi_css[10000][jk_mwi_css_path]" value="" style="width:90%;"></td>
                      <td></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
                
                <hr />            
            
            	<p><a onclick="more_js(); return false;" href="#">Add More Javascript Files</a></p>
                <table width="100%" id="jk_mwi_js" border="0" cellspacing="10">
                  <thead>
                    <tr>
                      <td><strong>Javascript Path</strong> <em>(All *.js files are sourced from http://www.domain.com/magento/js/)</em> <br /><br /><strong>Example:</strong> prototype/prototype.js<br /><br /></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if($jk_mwi_js) { ?>
                    <?php $i = 1; foreach($jk_mwi_js as $key => $value) { ?>
                    <tr<?php echo ($i==1) ? ' id="jk_mwi_first_js"' : ''; ?>>
                      <td><input type="text" name="jk_mwi_js[<?php echo $key; ?>][jk_mwi_js_path]" value="<?php echo $value['jk_mwi_js_path']; ?>" style="width:90%;"></td>
                    </tr>
                    <?php $i++; } ?>
                    <?php } else { ?>
                    <tr id="jk_mwi_first_js">
                      <td><input type="text" name="jk_mwi_js[10000][jk_mwi_js_path]" value="" style="width:90%;"></td>
                      <td></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>

        </div>
        <div class="wg_right">
        
        <table width="320" border="0" cellspacing="10" cellpadding="10">
          <tr>
            <th scope="col">Javascript/Stylesheet File Name</th>
            <th scope="col">Use?</th>
          </tr>
          <tr>
            <td>prototype/prototype.js</td>
            <td><input type="checkbox" name="jk_mwi_cssjs[option_one]" value="1"<?php checked( 1 == $jk_mwi_cssjs['option_one'] ); ?> /></td>
          </tr>
          <tr>
            <td>lib/ccard.js</td>
            <td><input type="checkbox" name="jk_mwi_cssjs[option_two]" value="1"<?php checked( 1 == $jk_mwi_cssjs['option_two'] ); ?> /></td>
          </tr>
          <tr>
            <td>prototype/validation.js</td>
            <td><input type="checkbox" name="jk_mwi_cssjs[option_three]" value="1"<?php checked( 1 == $jk_mwi_cssjs['option_three'] ); ?> /></td>
          </tr>
          <tr>
            <td>scriptaculous/builder.js</td>
            <td><input type="checkbox" name="jk_mwi_cssjs[option_four]" value="1"<?php checked( 1 == $jk_mwi_cssjs['option_four'] ); ?> /></td>
          </tr>
          <tr>
            <td>scriptaculous/effects.js</td>
            <td><input type="checkbox" name="jk_mwi_cssjs[option_five]" value="1"<?php checked( 1 == $jk_mwi_cssjs['option_five'] ); ?> /></td>
          </tr>
          <tr>
            <td>scriptaculous/dragdrop.js</td>
            <td><input type="checkbox" name="jk_mwi_cssjs[option_six]" value="1"<?php checked( 1 == $jk_mwi_cssjs['option_six'] ); ?> /></td>
          </tr>
          <tr>
            <td>scriptaculous/controls.js</td>
            <td><input type="checkbox" name="jk_mwi_cssjs[option_seven]" value="1"<?php checked( 1 == $jk_mwi_cssjs['option_seven'] ); ?> /></td>
          </tr>
          <tr>
            <td>scriptaculous/slider.js</td>
            <td><input type="checkbox" name="jk_mwi_cssjs[option_eight]" value="1"<?php checked( 1 == $jk_mwi_cssjs['option_eight'] ); ?> /></td>
          </tr>
          <tr>
            <td>varien/js.js</td>
            <td><input type="checkbox" name="jk_mwi_cssjs[option_nine]" value="1"<?php checked( 1 == $jk_mwi_cssjs['option_nine'] ); ?> /></td>
          </tr>
          <tr>
            <td>varien/form.js</td>
            <td><input type="checkbox" name="jk_mwi_cssjs[option_ten]" value="1"<?php checked( 1 == $jk_mwi_cssjs['option_ten'] ); ?> /></td>
          </tr>
          <tr>
            <td>varien/menu.js</td>
            <td><input type="checkbox" name="jk_mwi_cssjs[option_eleven]" value="1"<?php checked( 1 == $jk_mwi_cssjs['option_eleven'] ); ?> /></td>
          </tr>
          <tr>
            <td>mage/translate.js</td>
            <td><input type="checkbox" name="jk_mwi_cssjs[option_twelve]" value="1"<?php checked( 1 == $jk_mwi_cssjs['option_twelve'] ); ?> /></td>
          </tr>
          <tr>
            <td>mage/cookies.js</td>
            <td><input type="checkbox" name="jk_mwi_cssjs[option_thirteen]" value="1"<?php checked( 1 == $jk_mwi_cssjs['option_thirteen'] ); ?> /></td>
          </tr>
          <tr>
            <td>css/styles.css</td>
            <td><input type="checkbox" name="jk_mwi_cssjs[option_fourteen]" value="1"<?php checked( 1 == $jk_mwi_cssjs['option_fourteen'] ); ?> /></td>
          </tr>
        </table>
        </div>
        
        
     
        <script type="text/javascript">
		var css_increment = 10001;

		function more_css(){

			jQuery("#jk_mwi_first_css").before("<tr><td><input type='text' style='width:90%;' value='' name='jk_mwi_css["+css_increment+"][jk_mwi_css_path]'></td></tr>");

			css_increment++;

		}
		
		var js_increment = 10001;

		function more_js(){

			jQuery("#jk_mwi_first_js").before("<tr><td><input type='text' style='width:90%;' value='' name='jk_mwi_js["+js_increment+"][jk_mwi_js_path]'></td></tr>");

			js_increment++;

		}

	</script> 
      </div>
      <div id="tab3" class="tab_content">
      	<p>Most of the links in toplinks will get brought out no matter what, so below you can choose which ones you would like to show. Use the code below in your WordPress template files to bring out the toplinks:</p>
        <p><code>&lt;?php echo jk_mwi('toplinks'); ?&gt;</code></p>
        <hr />
        <table width="320" border="0" cellspacing="10" cellpadding="10">
          <tr>
            <th scope="col">Toplinks Item</th>
            <th scope="col">Use?</th>
          </tr>
          <tr>
            <td>My Account</td>
            <td><input type="checkbox" name="jk_mwi_toplinks[option_one]" value="1"<?php checked( 1 == $jk_mwi_toplinks['option_one'] ); ?> /></td>
          </tr>
          <tr>
            <td>My Wishlist</td>
            <td><input type="checkbox" name="jk_mwi_toplinks[option_two]" value="1"<?php checked( 1 == $jk_mwi_toplinks['option_two'] ); ?> /></td>
          </tr>
          <tr>
            <td>My Cart</td>
            <td><input type="checkbox" name="jk_mwi_toplinks[option_three]" value="1"<?php checked( 1 == $jk_mwi_toplinks['option_three'] ); ?> /></td>
          </tr>
          <tr>
            <td>Checkout</td>
            <td><input type="checkbox" name="jk_mwi_toplinks[option_four]" value="1"<?php checked( 1 == $jk_mwi_toplinks['option_four'] ); ?> /></td>
          </tr>
          <tr>
            <td>Login/Logout</td>
            <td><input type="checkbox" name="jk_mwi_toplinks[option_five]" value="1"<?php checked( 1 == $jk_mwi_toplinks['option_five'] ); ?> /></td>
          </tr>
        </table>
      </div>
      <div id="tab4" class="tab_content">
        <p>If you have added your own blocks in Magento, such as creating a topcart.phtml block to move your sidebar cart into your header, then you will need to fill in the fields below to add it.</p>
        <p>To add a custom block you will need to enter the block type, and the template path for that block. To do this, open up the xml file where you added your block code.</p>
        <p><code>&lt;block type="<strong>checkout/cart_sidebar</strong>" name="cart_sidebar" as="topcart" template="<strong>checkout/cart/sidebar.phtml</strong>"/&gt;</code></p>
        <p>The parts in bold and underlined above are the parts you will need to paste into the fields below.</p>
        <p><strong>Once you have added your block, use the Template Code that is generated upon saving to add it to your WordPress template files.</strong></p>
        
        <hr />
        
        <p><a onclick="more_custom_blocks(); return false;" href="#">Add More Blocks</a></p>
        <table width="100%" id="jk_mwi_blocks">
          <thead>
            <tr>
              <td>Block Type</td>
              <td>Magento Template Path</td>
              <td>Template Code</td>
            </tr>
          </thead>
          <tbody>
            <?php if($jk_mwi_blocks) { ?>
            <?php $i = 1; foreach($jk_mwi_blocks as $key => $value) { ?>
            <tr<?php echo ($i==1) ? ' id="jk_mwi_first_block"' : ''; ?>>
              <td><input type="text" name="jk_mwi_blocks[<?php echo $key; ?>][jk_mwi_block_type]" value="<?php echo $value['jk_mwi_block_type']; ?>" style="width:90%;"></td>
              <td><input type="text" name="jk_mwi_blocks[<?php echo $key; ?>][jk_mwi_template_path]" value="<?php echo $value['jk_mwi_template_path']; ?>" style="width:90%;"></td>
              <?php
        $block_path = $value['jk_mwi_template_path'];
				
        $jk_mwi_block_name = jk_mwi_block_name( $block_path );
		?>
              <td><code>&lt;?php echo jk_mwi('<?php echo $jk_mwi_block_name; ?>'); ?&gt;</code></td>
            </tr>
            <?php $i++; } ?>
            <?php } else { ?>
            <tr id="jk_mwi_first_block">
              <td><input type="text" name="jk_mwi_blocks[10000][jk_mwi_block_type]" value="" style="width:90%;"></td>
              <td><input type="text" name="jk_mwi_blocks[10000][jk_mwi_template_path]" value="" style="width:90%;"></td>
              <td></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <script type="text/javascript">
		var increment = 10001;

		function more_custom_blocks(){

			jQuery("#jk_mwi_first_block").before("<tr><td><input type='text' style='width:90%;' value='' name='jk_mwi_blocks["+increment+"][jk_mwi_block_type]'></td><td><input type='text' style='width:90%;' value='' name='jk_mwi_blocks["+increment+"][jk_mwi_template_path]'></td><td></td></tr>");

			increment++;

		}

	</script> 
      </div>
      <div id="static" class="tab_content">
        <p>Magento Wordpress Integration gives you the ability to add any of the static blocks from your Magento installion, into your WordPress theme - simply enter the <strong>Identifier</strong> of the static block below. You can also add more by clicking "Add More Blocks".</p>
        
        <p><strong>Note:</strong> The <strong>Identifier</strong> can be found by visiting <strong>CMS &raquo; Static Blocks</strong> in your Magento admin area.</p>
        
        <p><strong>Once you have added your block, use the Template Code that is generated upon saving to add it to your WordPress template files.</strong></p>
        
        <hr />
        
        <p><a onclick="more_static_blocks(); return false;" href="#">Add More Blocks</a></p>
        <table width="100%" id="jk_mwi_static_blocks">
          <thead>
            <tr>
              <td>Static Block Identifier</td>
              <td>Template Code</td>
            </tr>
          </thead>
          <tbody>
            <?php if($jk_mwi_static_blocks) { ?>
            <?php $i = 1; foreach($jk_mwi_static_blocks as $key => $value) { ?>
            <tr<?php echo ($i==1) ? ' id="jk_mwi_first_static_block"' : ''; ?>>
              <td><input type="text" name="jk_mwi_static_blocks[<?php echo $key; ?>][jk_mwi_static_name]" value="<?php echo $value['jk_mwi_static_name']; ?>" style="width:90%;"></td>
              <?php
				$jk_mwi_static_name = 'static_'.$value['jk_mwi_static_name'];
				?>
              <td><code>&lt;?php echo jk_mwi('<?php echo $jk_mwi_static_name; ?>'); ?&gt;</code></td>
            </tr>
            <?php $i++; } ?>
            <?php } else { ?>
            <tr id="jk_mwi_first_static_block">
              <td><input type="text" name="jk_mwi_static_blocks[10000][jk_mwi_static_name]" value="" style="width:90%;"></td>
              <td></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <script type="text/javascript">
		var increment = 10001;

		function more_static_blocks(){

			jQuery("#jk_mwi_first_static_block").before("<tr><td><input type='text' style='width:90%;' value='' name='jk_mwi_static_blocks["+increment+"][jk_mwi_static_name]'></td><td></td></tr>");

			increment++;

		}

	</script> 
      </div>
      <div id="tab5" class="tab_content">
      	<p>Magento Wordpress Integration allows you to add Magento products to Wordpress Posts or Pages. All you need to do is enter an SKU (or a comma separated list of your product SKUs) when editing a post or page.</p>
        <p>Choose below whether you want Magento Wordpress Integration to automatically append the products after the_content(), or whether you want to manually add the code snippet to your template file.</p>
        <p><strong>Note:</strong> The manual code needs to be placed within the post/page loop.</p>
        <hr />
        <table border="0" cellspacing="10" cellpadding="10">
          <tr>
            <td>Automatic or Manual Implementation</td>
            <td>
            	<select name="jk_mwi_product_options[automan]">
                	<option value="auto" <?php selected( $jk_mwi_product_options['automan'], 'auto' ); ?>>Automatic</option>
    				<option value="man" <?php selected( $jk_mwi_product_options['automan'], 'man' ); ?>>Manual</option>
                </select>
          	</td>
            <td>
            	<span class="description">For manual implementation, use: <code>&lt;?php echo jk_mwi_products(); ?&gt;</code> in your loop.</span>
          	</td>
          </tr>
        </table>
        <hr />
        <div class="wg_left">
        <h3>Setup</h3>
        <table width="320" border="0" cellspacing="10" cellpadding="10">
          <tr>
            <td>Thumbnail Width</td>
            <td><input type="text" name="jk_mwi_product_options[option_four]" maxlength="3" value="<?php echo $jk_mwi_product_options['option_four']; ?>" size="3" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>Show Product Title</td>
            <td><input type="checkbox" name="jk_mwi_product_options[option_five]" value="1"<?php checked( 1 == $jk_mwi_product_options['option_five'] ); ?> /></td>
          </tr>
          <tr>
            <td>Title Tag</td>
            <td><input type="text" name="jk_mwi_product_options[option_six]" maxlength="2" value="<?php echo $jk_mwi_product_options['option_six']; ?>" size="2" /></td>
          </tr>
          <tr>
            <td>Show Description</td>
            <td><input type="checkbox" name="jk_mwi_product_options[option_seven]" value="1"<?php checked( 1 == $jk_mwi_product_options['option_seven'] ); ?> /></td>
          </tr>
          <tr>
            <td>Button Type</td>
            <td>
            	<select name="jk_mwi_product_options[option_eight]">
                	<option value="add" <?php selected( $jk_mwi_product_options['option_eight'], 'add' ); ?>>Add to Cart</option>
    				<option value="view" <?php selected( $jk_mwi_product_options['option_eight'], 'view' ); ?>>More Info</option>
                </select>
            </td>
          </tr>
          <tr>
          	<td colspan="2"><span class="description"><strong>Note:</strong> Add to Cart only works with Simple products currently. All other product types will always use the "More Info" style button.</span></td>
          </tr>
        </table>
        
        
        </div>
        <div class="wg_right">
        <h3>Styling</h3>
        <table width="320" border="0" cellspacing="10" cellpadding="10">
          <tr>
            <td>Enable Styles</td>
            <td><input type="checkbox" name="jk_mwi_product_options[styles]" value="1"<?php checked( 1 == $jk_mwi_product_options['styles'] ); ?> /></td>
          </tr>
          <tr>
          	<td colspan="2"><span class="description"><strong>Note:</strong> Unchecking this box will strip out all styles for the products, so everything below here becomes useless.</span></td>
          </tr>
          <tr>
            <td>Columns</td>
            <td><input type="text" name="jk_mwi_product_options[option_two]" maxlength="2" value="<?php echo $jk_mwi_product_options['option_two']; ?>" size="2" /></td>
          </tr>
          <tr>
            <td>Spacing</td>
            <td><input type="text" name="jk_mwi_product_options[option_three]" maxlength="2" value="<?php echo $jk_mwi_product_options['option_three']; ?>" size="2" /></td>
          </tr>
          <tr>
            <td>Button Style</td>
            <td>
            	<select name="jk_mwi_product_options[button_style]">
                	<option value="lgreen" <?php selected( $jk_mwi_product_options['button_style'], 'lgreen' ); ?>>Large Green</option>
    				<option value="lblack" <?php selected( $jk_mwi_product_options['button_style'], 'lblack' ); ?>>Large Black</option>
                    <option value="lsilver" <?php selected( $jk_mwi_product_options['button_style'], 'lsilver' ); ?>>Large Silver</option>
                    <option value="sgreen" <?php selected( $jk_mwi_product_options['button_style'], 'sgreen' ); ?>>Small Green</option>
                    <option value="sblack" <?php selected( $jk_mwi_product_options['button_style'], 'sblack' ); ?>>Small Black</option>
                    <option value="ssilver" <?php selected( $jk_mwi_product_options['button_style'], 'ssilver' ); ?>>Small Silver</option>
                    <option value="nostyle" <?php selected( $jk_mwi_product_options['button_style'], 'nostyle' ); ?>>No Style</option>
                </select>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>Show Thumbnail Border</td>
            <td><input type="checkbox" name="jk_mwi_product_options[option_nine]" value="1"<?php checked( 1 == $jk_mwi_product_options['option_nine'] ); ?> /></td>
          </tr>
          <tr>
            <td>Border Colour</td>
            <td><input type="text" value="<?php echo $jk_mwi_product_options['option_ten']; ?>" name="jk_mwi_product_options[option_ten]" id="colorpickerField1" size="6" maxlength="6" /></td>
          </tr>
          <tr>
            <td>Border Width</td>
            <td><input type="text" name="jk_mwi_product_options[option_eleven]" maxlength="2" value="<?php echo $jk_mwi_product_options['option_eleven']; ?>" size="2" /></td>
          </tr>
          <tr>
            <td>Rounded Corners?</td>
            <td><input type="checkbox" name="jk_mwi_product_options[option_twelve]" value="1"<?php checked( 1 == $jk_mwi_product_options['option_twelve'] ); ?> /></td>
          </tr>
          <tr>
            <td>Border Radius</td>
            <td><input type="text" name="jk_mwi_product_options[option_thirteen]" maxlength="2" value="<?php echo $jk_mwi_product_options['option_thirteen']; ?>" size="2" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>Thumbnail Shadow</td>
            <td><input type="checkbox" name="jk_mwi_product_options[option_fourteen]" value="1"<?php checked( 1 == $jk_mwi_product_options['option_fourteen'] ); ?> /></td>
          </tr>
          <tr>
            <td>Shadow x Position (px)</td>
            <td><input type="text" name="jk_mwi_product_options[option_fifteen]" maxlength="2" value="<?php echo $jk_mwi_product_options['option_fifteen']; ?>" size="2" /></td>
          </tr>
          <tr>
            <td>Shadow y Position (px)</td>
            <td><input type="text" name="jk_mwi_product_options[option_sixteen]" maxlength="2" value="<?php echo $jk_mwi_product_options['option_sixteen']; ?>" size="2" /></td>
          </tr>
          <tr>
            <td>Shadow Blur (px)</td>
            <td><input type="text" name="jk_mwi_product_options[option_seventeen]" maxlength="2" value="<?php echo $jk_mwi_product_options['option_seventeen']; ?>" size="2" /></td>
          </tr>
          <tr>
            <td>Shadow Colour</td>
            <td><input type="text" value="<?php echo $jk_mwi_product_options['option_eighteen']; ?>" name="jk_mwi_product_options[option_eighteen]" id="colorpickerField2" size="6" maxlength="6" /></td>
          </tr>
        </table>
        </div>
      </div>
      <div id="tab6" class="tab_content">
        <p>Using this plugin, there are a variety of blocks you can bring out from your Magento installation. Enter the path to your Magento installation in the box above, and then enter the theme you are using into the second box.</p>
        <p>Once you have done this, you are ready to put the code snippets into your Wordpress template to bring out some Magento blocks.</p>
        <p>The codes to use are as follows:</p>
        <hr />
        <?php $plugname = "jk_mwi"; ?>
        <dl>
          <dt><strong>CSS/JS</strong> <em>(This is usually in the head.phtml file of your Magento theme.)</em></dt>
          <dd><code>&lt;?php echo <?php echo $plugname; ?>('cssjs'); ?&gt;</code></dd>
          <dt><strong>Includes</strong> <em>(This is usually in the head.phtml file of your Magento theme.)</em></dt>
          <dd><code>&lt;?php echo <?php echo $plugname; ?>('inc'); ?&gt;</code></dd>
          <dt><strong>Welcome Message</strong> <em>(This is usually in the header.phtml file of your Magento theme.)</em></dt>
          <dd><code>&lt;?php echo <?php echo $plugname; ?>('welcome'); ?&gt;</code></dd>
          <dt><strong>Logo</strong> <em>(This is usually in the header.phtml file of your Magento theme.)</em></dt>
          <dd><code>&lt;?php echo <?php echo $plugname; ?>('logo'); ?&gt;</code></dd>
          <dt><strong>URL</strong> <em>(This is usually in the header.phtml file of your Magento theme, around the logo.)</em></dt>
          <dd><code>&lt;?php echo <?php echo $plugname; ?>('url'); ?&gt;</code></dd>
          <dt><strong>Top Links</strong> <em>(This is usually in the header.phtml file of your Magento theme.)</em></dt>
          <dd><code>&lt;?php echo <?php echo $plugname; ?>('toplinks'); ?&gt;</code></dd>
          <dt><strong>Search</strong> <em>(This is usually in topBar.)</em></dt>
          <dd><code>&lt;?php echo <?php echo $plugname; ?>('search'); ?&gt;</code></dd>
          <dt><strong>Top Menu</strong> <em>(This is the main menu, requires the css/js to be loaded for dropdowns.)</em></dt>
          <dd><code>&lt;?php echo <?php echo $plugname; ?>('topmenu'); ?&gt;</code></dd>
          <dt><strong>Wishlist</strong> <em>(This is usually in the left sidebar.)</em></dt>
          <dd><code>&lt;?php echo <?php echo $plugname; ?>('wishlist'); ?&gt;</code></dd>
          <dt><strong>Recently Viewed</strong> <em>(This is usually in the left sidebar.)</em></dt>
          <dd><code>&lt;?php echo <?php echo $plugname; ?>('recently_viewed'); ?&gt;</code></dd>
          <dt><strong>Compare</strong> <em>(This is usually in the left sidebar.)</em></dt>
          <dd><code>&lt;?php echo <?php echo $plugname; ?>('compare'); ?&gt;</code></dd>
          <dt><strong>Sidebar Cart</strong> <em>(This is usually in the left sidebar.)</em></dt>
          <dd><code>&lt;?php echo <?php echo $plugname; ?>('sidecart'); ?&gt;</code></dd>
          <dt><strong>Newsletter</strong> <em>(This is usually in the footer.)</em></dt>
          <dd><code>&lt;?php echo <?php echo $plugname; ?>('newsletter'); ?&gt;</code></dd>
          <dt><strong>Messages</strong> <em>(This returns messages, for example when a user signs up to the newsletter.)</em></dt>
          <dd><code>&lt;?php echo <?php echo $plugname; ?>('messages'); ?&gt;</code></dd>
        </dl>
      </div>
    </div>
    <p class="submit">
      <input type="submit" name="Submit" value="<?php _e('Update Options', 'jk_mwi_trdom' ) ?>" />
    </p>
  </form>
  
  <table width="100%" border="0" cellspacing="10" cellpadding="0">
    <tr style="vertical-align:top;">
      <td>
        <h3>Support</h3>
        <p>For help and support, please visit <a target="_blank" href="http://www.jckemp.com/plugins/magento-wordpress-integration/" title="Magento Wordpress Integration" target="_blank">the support page</a>.</p>
      </td>
      <td>
        <h3>Follow Me?</h3>
        <p>Feel free to follow me on <strong>Twitter</strong>: <a target="_blank" href="https://twitter.com/#!/jamesckemp">@jamesckemp</a></p>
      </td>
      <td>
        <h3>Donate</h3>
        <p>If you're feeling generous, then I wouldn't turn down a hefty donation!</p>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
          <input type="hidden" name="cmd" value="_s-xclick">
          <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHLwYJKoZIhvcNAQcEoIIHIDCCBxwCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYBxZ9GoSF6HAedyq8o9dmRBY+xvY70BKM2r67c5BE0kZisZWMJJUkimdAN4WoydDkiHJfzkXBTLD+1z0Xviv/9YgR4Lve+mmoIP3aRJz4JzSlZZW8/QRvysKxVTMNmYimVHiQp8PtOzsQxdhmISchmHQx7cAAjaFYZOC7yMgu9DcTELMAkGBSsOAwIaBQAwgawGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQIbpFsQntFKnqAgYh8CQQbjKQWDbJ1U4Qu5RPvYYHaUApl9ObDlqK1KL0pJr9YCyEQeNyco1Mfm4IoaVMfCrOS5h7X7XCPw9zXuSsbiD4DONnHKbA11Vun7aiLvpV2Tfdtc76lm2pwSRwKUa4kni6EiU4nnDUulL5gjwGn91VkKp3UTds0KkIXY926WN+pSTb87ftYoIIDhzCCA4MwggLsoAMCAQICAQAwDQYJKoZIhvcNAQEFBQAwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tMB4XDTA0MDIxMzEwMTMxNVoXDTM1MDIxMzEwMTMxNVowgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tMIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDBR07d/ETMS1ycjtkpkvjXZe9k+6CieLuLsPumsJ7QC1odNz3sJiCbs2wC0nLE0uLGaEtXynIgRqIddYCHx88pb5HTXv4SZeuv0Rqq4+axW9PLAAATU8w04qqjaSXgbGLP3NmohqM6bV9kZZwZLR/klDaQGo1u9uDb9lr4Yn+rBQIDAQABo4HuMIHrMB0GA1UdDgQWBBSWn3y7xm8XvVk/UtcKG+wQ1mSUazCBuwYDVR0jBIGzMIGwgBSWn3y7xm8XvVk/UtcKG+wQ1mSUa6GBlKSBkTCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb22CAQAwDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQUFAAOBgQCBXzpWmoBa5e9fo6ujionW1hUhPkOBakTr3YCDjbYfvJEiv/2P+IobhOGJr85+XHhN0v4gUkEDI8r2/rNk1m0GA8HKddvTjyGw/XqXa+LSTlDYkqI8OwR8GEYj4efEtcRpRYBxV8KxAW93YDWzFGvruKnnLbDAF6VR5w/cCMn5hzGCAZowggGWAgEBMIGUMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbQIBADAJBgUrDgMCGgUAoF0wGAYJKoZIhvcNAQkDMQsGCSqGSIb3DQEHATAcBgkqhkiG9w0BCQUxDxcNMTEwODI2MTkxNjEzWjAjBgkqhkiG9w0BCQQxFgQUwAdU0z3EFpvq+4aANnWALLfBZWswDQYJKoZIhvcNAQEBBQAEgYBoYla8FqE7dHzkanTg+Hz1Biqcq0ZujUamOU6xeyh5OrVXNgY12ffHdCz8T1I212NE68xcS5PBI+Rbo5TbYSJ+3wQfgiMcuVj9wt4pJJJs/+oXX3uWFTdyUjWE/QHK3z1Nj3EOnFrbBjZuyQyhaJDHG2Er9/gDo77LNQ0EoclD5A==-----END PKCS7-----
          ">
          <input style="margin:0 0 0 -5px;" type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
          <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
      	</form>

      </td>
    </tr>
  </table>


</div>
</div>