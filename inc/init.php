<?php

  ################################################
  ###                                          ###
  ###            Deactivate Addon              ###
  ###                                          ###
  ################################################
 
	if(isset($_POST['mwi_field_deactivate'])) {
		// vars
		$mwi_message = "";
		$field = $_POST['mwi_field_deactivate'];
		
		// delete field
		delete_option('mwi_'.$field.'_ac');
		
		//set message
		if($field == "widgetsshortcodes")
		{
			$mwi_message = '<p>' . __("Widget/Shortcodes field deactivated",'mwi') . '</p>';
		}
		
		if($field == "widgetspecific")
		{
			$mwi_message = '<p>' . __("Category Specific Widget field deactivated",'mwi') . '</p>';
		}
		
		// show message on page
		$this->admin_message($mwi_message);
	}

  ################################################
  ###                                          ###
  ###             Activate Addon               ###
  ###                                          ###
  ################################################
  
	if(isset($_POST['mwi_field_activate']) && isset($_POST['key'])) {
		// vars
		$mwi_message = "";
		$field = $_POST['mwi_field_activate'];
		$key = trim($_POST['key']);
	
		// update option
		update_option('mwi_'.$field.'_ac', $key);
		
		// did it unlock?
		if($this->u($field))
		{
			//set message
			if($field == "widgetsshortcodes")
			{
				$mwi_message = '<p>' . __("Widget/Shortcodes field activated",'mwi') . '</p>';
			}
			
			if($field == "widgetspecific")
			{
				$mwi_message = '<p>' . __("Category Specific Widget field activated",'mwi') . '</p>';
			}
		}
		else
		{
			$mwi_message = '<p>' . __("License key unrecognised",'mwi') . '</p>';
		}
		
		$this->admin_message($mwi_message);

	}