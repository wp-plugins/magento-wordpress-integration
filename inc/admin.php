<?php global $jck_mwi; ?>
<div class="wrap clearfix">
	<div id="mwi_right">
		<div class="mwi_meta">
			<h3>Documentation</h3>
			<p>Visit <a href="http://www.mwi-plugin.com" target="_blank">www.mwi-plugin.com</a> for all the latest documentation; including usage instructions, functions, examples and more.</p>
			<h3>Support</h3>
			<p>If you require some support, please check out the <a href="http://mwi-plugin.com/documentation/faq/" target="_blank">FAQ section</a> on the MWI website. If that doesn't help, try posting in the <a href="http://wordpress.org/support/plugin/magento-wordpress-integration" target="_blank">support forums</a>. If all else fails, please <a href="mailto:sales@mwi-plugin.com" target="_blank">send an email</a>.</p>
			<h3>Add-ons</h3>
			<p>To purchase add-ons for MWI, please visit the <a href="http://mwi-plugin.com/add-ons/" target="_blank">add-ons store</a>.</p>
			<h3>Twitter</h3>
			<a href="https://twitter.com/jamesckemp" class="twitter-follow-button" data-show-count="false">Follow @jamesckemp</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			<h3>Credits</h3>	
			<p>Created by <a href="http://www.jckemp.com" target="_blank">James Kemp</a></p>	
		</div>
	</div><!-- /#mwi_right -->
	<div id="mwi_left">
		<div id="poststuff" class="fleft">
			<h2 class="mwi-title">Magento Wordpress Integration Settings</h2>
			<form method="post" action="options.php">
			<?php settings_fields('mwi-main-settings'); ?>
			
			
			
			<div class="postbox mwi_settings">
			<h3><span><?php _e('Main Settings','mwi'); ?></span><?php /* ?><span class="description"> - <?php _e('This is the most important step!','mwi'); ?></span><?php */ ?></h3>
			<div class="inside">
			
				<table class="form-table">
			  
				  <tbody>
				    <tr valign="top">      
				      <th scope="row">
				        <strong><?php _e('Mage.php Path', 'mwi'); ?></strong>
				        
				        <div class="description">
				          <p><?php _e('Enter the full path to your Mage.php file, starting from the web root. The path to your public/www root should have been prefilled to the right, this should get you started!', 'mwi'); ?></p>			
						  <p><?php _e('Your public/www root is: ', 'mwi'); echo '<strong>'.$_SERVER['DOCUMENT_ROOT'].'</strong>'; ?></p>	          
				      	</div>
				      	
				      </th>
				      <td><input class="regular-text" type="text" name="mwi_options[magepath]" value="<?php echo $jck_mwi->getValue('magepath',$_SERVER['DOCUMENT_ROOT']); ?>" /></td>
				      <td><?php echo $message; ?></td>      
				    </tr>
				    
				    <tr valign="top">
				      <th scope="row">
				      	<strong><?php _e('Package Name'); ?></strong>
				      </th>
				      <td><input class="regular-text" type="text" name="mwi_options[package]" value="<?php echo $jck_mwi->getValue('package','default'); ?>" /></td>
				      <td></td>
				    </tr>
				    <tr valign="top">
				      <th scope="row"><strong><?php _e('Theme Name', 'mwi'); ?></strong></th>
				      <td><input class="regular-text" type="text" name="mwi_options[theme]" value="<?php echo $jck_mwi->getValue('theme','default'); ?>" /></td>
				      <td></td>
				    </tr>
				    
				    <tr valign="top">
				      <th scope="row">
				      	<strong><?php _e('Magento Website Code', 'mwi'); ?></strong>
				      	<p><?php _e('Enter the Magento website code to get blocks and sessions from. You can see all available website codes to the right. The default is usually base.', 'mwi'); ?></p>
					    <?php if ( !class_exists('Mage') ) { ?><p><?php _e('The table of available website codes will appear to the right once the path to Mage.php is saved and correct.', 'mwi'); ?></p><?php } ?>
				      </th>
				      <td><input class="regular-text" type="text" name="mwi_options[websitecode]" value="<?php echo $jck_mwi->getValue('websitecode','base'); ?>" /></td>
				      <td>
				      	<?php if ( class_exists('Mage') ) { ?>
				      		<p><strong>Available Magento Websites</strong></p>
					      <table>
					      	<tr>
						      	<?php /* ?><th>ID</th><?php */ ?>
						      	<th>Name</th>
						      	<th>Code</th>
					      	</tr>
					      	<?php
								$allStores = Mage::app()->getWebsites();
								foreach ($allStores as $_eachStoreId => $val) {
									$_storeCode = Mage::app()->getWebsite($_eachStoreId)->getCode();
									$_storeName = Mage::app()->getWebsite($_eachStoreId)->getName();
									$_storeId = Mage::app()->getWebsite($_eachStoreId)->getId();
									//print_r(Mage::app()->getStore($_eachStoreId));
									echo '<tr>';
										//echo '<td>'.$_storeId.'</td>';
										echo '<td>'.$_storeName.'</td>';
										echo '<td>'.$_storeCode.'</td>';
									echo '</tr>';
								} ?>
					      </table>
					    <?php } ?>
				      </td>
				    </tr>
				    
				    <tr valign="top">
				      <th scope="row">
				      	<strong><?php _e('Default Styles', 'mwi'); ?></strong>
				      	<div class="description">
				          <p><?php _e('Check the box to enable the default css for add-ons. If you want to edit the styles, uncheck the box and copy the contents of css/addon-styles.css to your own stylesheet.', 'mwi'); ?></p>				          
				      	</div>
				      </th>
				      <td>
				      		<?php $styles = $jck_mwi->getValue('styles',0); ?>
				      		<input name="mwi_options[styles]" type="checkbox" value="1" <?php checked( $styles, true ); ?>/>
				      </td>
				      <td></td>
				    </tr>
				    			        
				  </tbody>
				</table>
			
			</div><!-- /.inside -->
			</div><!-- /.postbox -->

			
			<p class="submit">
			  <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'mwi') ?>" />
			</p>
			</form>
			

		</div><!-- /#mwi_left -->
	</div><!-- /fleft -->
</div>
