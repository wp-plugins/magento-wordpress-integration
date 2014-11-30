<?php global $jck_mwi; ?>

<div class="wrap clearfix">

	<?php require_once('admin-sidebar.php'); ?>
	
	<div id="mwi_left">
		<div id="poststuff" class="fleft">
			<h2 class="mwi-title"><?php echo $jck_mwi->name; ?></h1>
			<p><?php _e('The modified functions.php in Magento could not be found. It is very important this is created before MWI will work. Please see the instructions below.', $jck_mwi->slug); ?></p>
			<h2><?php _e('Installation', $jck_mwi->slug); ?></h2>
			
			<ol>
			    <li><?php _e('Navigate to ~/your-magento/app/code/core/Mage/Core/functions.php', $jck_mwi->slug); ?></li>
			    <li><?php _e('Duplicate that file and place the new version in ~/your-magento/app/code/local/Mage/Core/functions.php – this file will now be used over the original, and will remain during Magento upgrades. If the destination folders do not exist, you can create them (maintain the capital lettering).', $jck_mwi->slug); ?></li>
			    <li><?php _e('Open the newly created file and browse to around line 90, where you will find this:', $jck_mwi->slug); ?><br>
                    <code>function __() { return Mage::app()->getTranslator()->translate(func_get_args()); }</code>
			    </li>
			    <li><?php _e('Replace the entire function, which usually spans over approximately 3 lines, with:', $jck_mwi->slug); ?><br>
			        <code>if(!function_exists('__')) { function __() { return Mage::app()->getTranslator()->translate(func_get_args()); } }</code>
			    </li>
			    <li><?php _e('Upload the file to your server, and you are done!', $jck_mwi->slug); ?></li>
			</ol>
			
			<p><?php _e("Once you've done the above you'll be able to modify the MWI settings here.", $jck_mwi->slug); ?></p>
		</div>
	</div>
	
</div>