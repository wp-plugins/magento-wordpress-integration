<div class="wrap clearfix">
	<div id="mwi_right">
		<div class="mwi_meta">
			<h3>Documentation</h3>
			<p>Visit <a href="http://www.magentowp.com" target="_blank">www.magentowp.com</a> for all the latest documentation; including usage instructions, functions, examples and more.</p>
			<h3>Support</h3>
			<p>If you require some support, please check out the <a href="http://magentowp.com/documentation/faq/" target="_blank">FAQ section</a> on the MWI website. If that doesn't help, try posting in the <a href="http://wordpress.org/support/plugin/magento-wordpress-integration" target="_blank">support forums</a>. If all else fails, please <a href="mailto:sales@magentowp.com" target="_blank">send an email</a>.</p>
			<h3>Add-ons</h3>
			<p>To purchase add-ons for MWI, please visit the <a href="http://magentowp.com/add-ons/" target="_blank">add-ons store</a>.</p>
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
			<h3><span><?php _e('Main Settings','mwi'); ?></span><span class="description"> - <?php _e('This is the most important step!','mwi'); ?></span></h3>
			<div class="inside">
			
				<table class="form-table">
			  
				  <tbody>
				    <tr valign="top">      
				      <th scope="row">
				        <strong><?php _e('Mage.php Path'); ?></strong>
				        
				        <div class="description">
				          <p><?php _e('Enter the full path to your Mage.php file, starting from the web root. The path to your public/www root should have been prefilled to the right, this should get you started!'); ?></p>				          
				      	</div>
				      	
				      </th>
				      <td><input class="regular-text" type="text" name="mwi_options[magepath]" value="<?php echo jck_mwi::getValue('magepath',$_SERVER['DOCUMENT_ROOT']); ?>" /></td>
				      <td><?php echo $message; ?></td>      
				    </tr>
				    
				    <tr valign="top">
				      <th scope="row">
				      	<strong><?php _e('Package Name'); ?></strong>
				      </th>
				      <td><input class="regular-text" type="text" name="mwi_options[package]" value="<?php echo jck_mwi::getValue('package','default'); ?>" /></td>
				      <td></td>
				    </tr>
				    <tr valign="top">
				      <th scope="row"><strong><?php _e('Theme Name'); ?></strong></th>
				      <td><input class="regular-text" type="text" name="mwi_options[theme]" value="<?php echo jck_mwi::getValue('theme','default'); ?>" /></td>
				      <td></td>
				    </tr>
				    <tr valign="top">
				      <th scope="row">
				      	<strong><?php _e('Default Store View Code'); ?></strong>
				        
				        <div class="description">
				        <p><?php _e('Enter the Store View Code (SVC) to be used by default. This is the store view that MWI will get blocks and sessions from by default.'); ?></p>
				        </div>
				      </th>
				      <td><input class="regular-text" type="text" name="mwi_options[default_sv]" value="<?php echo jck_mwi::getValue('default_sv', 'default'); ?>" /></td>
				      <td></td>
				    </tr>
				        
				  </tbody>
				</table>
			
			</div><!-- /.inside -->
			</div><!-- /.postbox -->

			
			
			
			<p class="submit">
			  <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
			
			
			<div class="postbox mwi_settings">
			<h3><span><?php _e('Multiple Store Views','mwi'); ?></span></h3>
			<div class="inside">
			
				<table class="form-table">
				  
				  <tr valign="top">
				    <th scope="row">
				    	<strong><?php _e('Multiple Store Views'); ?></strong>
				      
				      <div class="description">
				      
				        <p><?php _e('If your Magento website has multiple store views, enter the paths and Store View Codes here. The default Store View Code defined above will be used anywhere that isn\'t explicitly set here.'); ?></p>
				        <?php /* ?>
				        <h3><?php _e('Examples'); ?> (<a href="#" onclick="javascript: tb_remove(); return false;"><?php _e('Close'); ?></a>)</h3>
				        
				        <p><?php _e('Magento Installed in web root:'); ?></p>
				        <code><?php echo $_SERVER['DOCUMENT_ROOT']; ?>app/Mage.php</code>
				
				        <p><?php _e('Magento Installed in sub-directory of the web root:'); ?></p>
				        <code><?php echo $_SERVER['DOCUMENT_ROOT']; ?>subfolder-name/app/Mage.php</code>
				
				        <br /><br /><h3><?php _e('Note'); ?></h3>
				        <p><?php _e('Your web root path is: '); ?></p>
				        <code><?php echo $_SERVER['DOCUMENT_ROOT']; ?></code> 
				        <?php */ ?>
				      </div>
				    </th>
				    <td id="svc_repeaters">
				    	
				      <table class="mwi_repeater">
				      
				      <colgroup>
				      	<col>
				      	<col>
				      	<col width="60">
				      </colgroup>
				      
				      <?php $multiple_sv = jck_mwi::getValue('multiple_sv'); ?>
				      
				      <?php if(is_array($multiple_sv)) { // If any additional store views are set, do this ?>
				      
				      	<?php foreach($multiple_sv as $i => $sv) { ?>
				        
				        	<tr valign="top" class="<?php echo ($i == 0) ? 'first_row ' : ''; ?>msv_row">
				            <td>
				              <input class="regular-text sv_url" type="text" name="mwi_options[multiple_sv][<?php echo $i; ?>][url]" value="<?php echo $sv['url']; ?>" /><br />
				              <em><?php _e('Store View URL (full path).'); ?></em>
				            </td>
				            <td>
				              <input class="regular-text sv_code" type="text" name="mwi_options[multiple_sv][<?php echo $i; ?>][store_view_code]" value="<?php echo $sv['store_view_code']; ?>" /><br />
				              <em><?php _e('Store View Code.'); ?></em>
				            </td>
				            <td class="actions">
				              <div class="clearfix"><a href="#" class="add">+</a> <a href="#" class="remove">-</a></div>
				            </td>
				          </tr>
				        	
				          
				        
				        <?php } ?>
				      
				      <?php } else { // If no additional store views are set, do this ?>          
				      
				        <tr valign="top" class="first_row msv_row">
				          <td>
				            <input class="regular-text sv_url" type="text" name="mwi_options[multiple_sv][0][url]" value="" /><br />
				            <em><?php _e('Store View URL (full path).'); ?></em>
				          </td>
				          <td>
				            <input class="regular-text sv_code" type="text" name="mwi_options[multiple_sv][0][store_view_code]" value="" /><br />
				            <em><?php _e('Store View Code.'); ?></em>
				          </td>
				          <td class="actions">
				            <div class="clearfix"><a href="#" class="add">+</a> <a href="#" class="remove">-</a></div>
				          </td>
				        </tr>
				      
				      <?php } ?>
				      
				      
				      
				      </table>
				      
				    </td>
				   
				  </tr>
				</table>
			
			</div><!-- /.inside -->
			</div><!-- /.postbox -->
			
			
				
			<p class="submit">
			  <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
			</form>
			
			
			
			
			
			
			
			<table class="mwi_activate widefat">
				<thead>
					<tr>
						<th>Add-on</th>
						<th>Status</th>
						<th>Activation Code</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Shortcodes &amp; Widgets</td>
						<td><?php if($this->u('widgetsshortcodes')){ _e('Active'); } else { _e('Inactive'); } ?></td>
						<td>
						
							<form method="post" action="">
								<?php if($this->u('widgetsshortcodes')){
									echo '<span class="activation_code">XXXX-XXXX-XXXX-'.substr($this->k('widgetsshortcodes'),-4) .'</span>';
									echo '<input type="hidden" name="mwi_field_deactivate" value="widgetsshortcodes" />';
									echo '<input type="submit" class="button" value="Deactivate" />';
								}
								else
								{
									echo '<input type="text" name="key" value="" class="regular-text" />';
									echo '<input type="hidden" name="mwi_field_activate" value="widgetsshortcodes" />';
									echo '<input type="submit" class="button" value="Activate" />';
								} ?>
							</form>
										
						</td>
					</tr>
					
				</tbody>
			</table>
			
			<p>To activate the add-ons, you will need to purchase a license key from the <a href="http://magentowp.com/add-ons/" target="_blank">add-ons store</a>.


		</div><!-- /#mwi_left -->
	</div><!-- /fleft -->
</div>
