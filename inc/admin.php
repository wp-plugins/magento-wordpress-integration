<?php global $jck_mwi; ?>

<div class="wrap clearfix">

	<div id="mwi_right">
		<div class="mwi_meta">
		
			<h3><?php _e('Documentation', $jck_mwi->slug); ?></h3>
			<p>Visit <a href="http://www.mwi-plugin.com" target="_blank">www.mwi-plugin.com</a> for all the latest documentation; including usage instructions, functions, examples and more.</p>
			
			<h3><?php _e('Support', $jck_mwi->slug); ?></h3>
			<p>If you require some support, please check out the <a href="http://mwi-plugin.com/documentation/faq/" target="_blank">FAQ section</a> on the MWI website. If that doesn't help, try posting in the <a href="http://wordpress.org/support/plugin/magento-wordpress-integration" target="_blank">support forums</a>. If all else fails, please <a href="mailto:sales@mwi-plugin.com" target="_blank">send an email</a>.</p>
			
			<h3><?php _e('Add-ons', $jck_mwi->slug); ?></h3>
			<p>To purchase add-ons for MWI, please visit the <a href="http://mwi-plugin.com/add-ons/" target="_blank">add-ons store</a>.</p>
			
			<h3><?php _e('Twitter', $jck_mwi->slug); ?></h3>
			<a href="https://twitter.com/jamesckemp" class="twitter-follow-button" data-show-count="false">Follow @jamesckemp</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			
			<h3><?php _e('Credits', $jck_mwi->slug); ?></h3>	
			<p>Created by <a href="http://www.jckemp.com" target="_blank">James Kemp</a></p>
				
		</div>
	</div><!-- /#mwi_right -->
	
	
	<div id="mwi_left">
		<div id="poststuff" class="fleft">
			<h2 class="mwi-title"><?php echo $jck_mwi->name; ?></h2>
			<form method="post" action="options.php">
    			<?php settings_fields('mwi-main-settings'); ?>
    			
    			<?php
        			
    			$mwiSettings = array();
    			
    			$checkMage = $this->check_mage();
    			
    			$mwiSettings[] = array(
        		    'title'         =>  __('Mage.php Path', $jck_mwi->slug),
        		    'description'   =>  array(
            		                        __('Enter the path (absolute or relative from WordPress root) to your Mage.php file.', $jck_mwi->slug),
        		                            sprintf(__('Your public/www root is: %s', $jck_mwi->slug), $_SERVER['DOCUMENT_ROOT'])
                                        ),
        		    'name'          =>  'magepath',
        		    'type'          =>  'text',
        		    'value'         =>  $jck_mwi->getValue('magepath', $_SERVER['DOCUMENT_ROOT']),
        		    'additional'    =>  '<div class="'.$checkMage['class'].'">'.$checkMage['message'].'</div>'
    			);
    			
    			$mwiSettings[] = array(
        		    'title'         =>  __('Package Name', $jck_mwi->slug),
        		    'description'   =>  '',
        		    'name'          =>  'package',
        		    'type'          =>  'text',
        		    'value'         =>  $jck_mwi->getValue('package', 'default'),
        		    'additional'    =>  ''
    			);
    			
    			$mwiSettings[] = array(
        		    'title'         =>  __('Theme Name', $jck_mwi->slug),
        		    'description'   =>  '',
        		    'name'          =>  'theme',
        		    'type'          =>  'text',
        		    'value'         =>  $jck_mwi->getValue('theme', 'default'),
        		    'additional'    =>  ''
    			);
    			
    			$mwiSettings['websitecode'] = array(
        		    'title'         =>  __('Magento Website Code', $jck_mwi->slug),
        		    'description'   =>  array(
        		                            __('Enter the Magento website code to get blocks and sessions from. You can see all available website codes to the right. The default is usually base.', $jck_mwi->slug),
        		                            ( !class_exists('Mage') ? __('The table of available website codes will appear to the right once the path to Mage.php is saved and correct.', $jck_mwi->slug) : '' )
                                        ),
        		    'name'          =>  'websitecode',
        		    'type'          =>  'text',
        		    'value'         =>  $jck_mwi->getValue('websitecode', 'base'),
        		    'additional'    =>  ''
    			);
    			
    			if($checkMage['result'] == true):
        			
        			$codes = '<h4>Available Magento Websites</h4>';
        			
                    $codes .= '<table>';
                    
                        $codes .= '<tr>';
                            $codes .= '<th>Name</th>';
                            $codes .= '<th>Code</th>';
                        $codes .= '</tr>';
                        
                        $allStores = Mage::app()->getWebsites();
                        foreach ($allStores as $_eachStoreId => $val):
                        
                            $_storeCode = Mage::app()->getWebsite($_eachStoreId)->getCode();
                            $_storeName = Mage::app()->getWebsite($_eachStoreId)->getName();
                            $_storeId = Mage::app()->getWebsite($_eachStoreId)->getId();
                            
                            $codes .= '<tr>';
                                $codes .= '<td>'.$_storeName.'</td>';
                                $codes .= '<td>'.$_storeCode.'</td>';
                            $codes .= '</tr>';
                            
                        endforeach;
                        
                    $codes .= '</table>';
        			
        			$mwiSettings['websitecode']['additional'] = $codes;
        			
    			endif;
    			
    			/*
    			$mwiSettings[] = array(
        		    'title'         =>  __('Default Styles', $jck_mwi->slug),
        		    'description'   =>  __('Check the box to enable the default css for add-ons. If you want to edit the styles, uncheck the box and copy the contents of css/addon-styles.css to your own stylesheet.', $jck_mwi->slug),
        		    'name'          =>  'styles',
        		    'type'          =>  'checkbox',
        		    'value'         =>  $jck_mwi->getValue('styles', 0),
        		    'additional'    =>  ''
    			);
    			*/
    			
    			?>    			
    			
    			<div class="postbox mwi_settings">
    			
        			<h3><?php _e('Main Settings',$jck_mwi->slug); ?></h3>
        			
        			<div class="inside">
        			
        				<table class="form-table">
        			  
        				  <tbody>
        				  
        				    <?php foreach($mwiSettings as $mwiSetting): ?>
        				        
        				        <tr valign="top">      
                                    <th scope="row">
                                    
                                        <strong><?php echo $mwiSetting['title']; ?></strong>
                                        
                                        <?php if(is_array($mwiSetting['description']) && !empty($mwiSetting['description'])): ?>
                                            <div class="description">
                                                <?php foreach($mwiSetting['description'] as $paragraph): ?>
                                                    <p><?php echo $paragraph; ?></p>
                                                <?php endforeach; ?> 	          
                                            </div>
                                        <?php endif; ?>  
                                                                  
                                    </th>
                                    
                                    <td>
                                    
                                        <?php if( $mwiSetting['type'] == "text" ): ?>
                                        
                                            <input class="regular-text" type="text" name="mwi_options[<?php echo $mwiSetting['name']; ?>]" value="<?php echo $mwiSetting['value']; ?>" />
                                        
                                        <?php elseif( $mwiSetting['type'] == "checkbox" ): ?>
                                            
                                            <input name="mwi_options[<?php echo $mwiSetting['name']; ?>]" type="checkbox" value="1" <?php checked( $mwiSetting['value'], 1 ); ?>/>
                                            
                                        <?php endif; ?>
                                        
                                    </td>
                                    
                                    <td>
                                    
                                        <?php echo $mwiSetting['additional']; ?>
                                        
                                    </td>      
                                </tr>
        				        
                            <?php endforeach; ?>
        				    			        
        				  </tbody>
        				</table>
        			
        			</div><!-- /.inside -->
        			
    			</div><!-- /.postbox -->
    
    			
                <p class="submit">
                    <input type="submit" class="button-primary" value="<?php _e('Save Changes', $jck_mwi->slug); ?>" />
                </p>
        
            </form>

		</div><!-- /fleft -->
	</div><!-- /#mwi_left -->
</div>