<?php
	/*
	* @package jck_mwi
	* @version 2.0
	* @description All the front end functions, conveniently contained within one awesome file!
	* @updated 2.0.4
	*/

  ################################################
  ###                                          ###
  ###            Block Functions               ###
  ###                                          ###
  ################################################
  
  	function get_block($name, $render = true) {
  		global $jck_mwi;
	  	$layout = $jck_mwi->layout();
	  	$block = $layout->getBlock($name);
	  	if($block && $render) { 
	  		return $block->toHtml(); 
	  	} elseif($block && $render === false) {
	  		return $block;
	  	} else { 
	  		return __('Sorry, that block could not be found.','mwi');
	  	}
  	}
  	
  	function the_block($name) {
	  	global $jck_mwi;
	  	$layout = $jck_mwi->layout();
	  	$block = $layout->getBlock($name);
	  	if($block) { 
	  		echo $block->toHtml(); 
	  	} else { 
	  		_e('Sorry, that block could not be found.','mwi');
	  	}
  	}
  	
  ################################################
  ###                                          ###
  ###         Static Block Functions           ###
  ###                                          ###
  ################################################
	
	// Return a Static Block
	function get_static_block($identifier) {
		
		$layout = Mage::getSingleton('core/layout'); // Set layout block
		$block = $layout->createBlock('cms/block')->setBlockId($identifier)->toHtml();
		
		if($block) {
			return $block;
		} else {
			return __('Sorry, that block could not be found. Please check your <strong>block identifier</strong>.','mwi');
		}
		
	}
	
	// Echo a Static Block
	function the_static_block($identifier) {
		
		$layout = Mage::getSingleton('core/layout'); // Set layout block
		$block = $layout->createBlock('cms/block')->setBlockId($identifier)->toHtml();
		
		if($block) {
			echo $block;
		} else {
			_e('Sorry, that block could not be found. Please check your <strong>block identifier</strong>.');
		}
		
	}