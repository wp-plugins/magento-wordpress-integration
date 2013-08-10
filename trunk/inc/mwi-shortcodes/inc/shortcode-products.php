<?php
	/*
	* @package jck_mwi
	* @version 2.0
	* @updated 2.1.4
	*/
	
	global $jck_mwi;
	$app = Mage::app();

	$shortcode = '';
	$store_url = Mage::getBaseUrl();
	$theProductBlock = new Mage_Catalog_Block_Product;

  ################################################
  ###                                          ###
  ###              If SKU Entered              ###
  ###                                          ###
  ################################################

	if($sku != '') {
	
		$skus = explode(',',$sku);		
		$skucount = count($skus);
	
			$i = 0; foreach($skus as $sku) {
			
				$shortcode .= ($i % $cols == 0 && $skucount >= 2) ? '<div class="products">' : ''; // Check if there is 1 or more products, and open .products wrapper
				
				$product_data = Mage::getModel('catalog/product')
				->getCollection()
				->addAttributeToSelect(array('name', 'product_url', 'thumbnail', 'price', 'special_price', 'group_price', 'short_description'))
				->addFieldToFilter('sku',array('like'=>$sku))
				->getFirstItem();
			
				if($product_data->getName() != "") { // If product exists
				
					$poductUrl = $product_data->getProductUrl();
					
					$singleclass = ($skucount == 1) ? " single" : "";
					$shortcode .= '<div class="product'.$singleclass.'">';
						
						// #### Thumbnail #### //
						if($img == "true") {
						$shortcode .= '<a class="product_img" href="'.$poductUrl.'" title="' . $product_data->getName() . '">';
								$shortcode .= '<img width="'.$img_width.'" title="' . $product_data->getName() . '" src="' . Mage::helper('catalog/image')->init($product_data, 'thumbnail')->constrainOnly(TRUE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize($img_width) . '" alt="' . $product_data->getName() . '">';
						$shortcode .= '</a>';
						}
						
						// #### Title #### //
						if($title == "true") {
							if($title_tag != '') { 
								$shortcode .= '<'.$title_tag.' class="product_title">'.'<a href="'.$poductUrl.'" title="' . $product_data->getName() . '">'.$product_data->getName().'</a>'.'</'.$title_tag.'>'; 
							} else {
								$shortcode .= '<a href="'.$poductUrl.'" title="' . $product_data->getName() . '">'.$product_data->getName().'</a>'; 
							}
						}
						
						// #### Description #### //
						if($desc == "true") { 
							$shortcode .= '<div class="product_desc">'.$product_data->getShortDescription().'</div>';
						}				
						
						if($price == "true") {
							// #### Price #### //	
							$shortcode .= $theProductBlock->getPriceHtml($product_data, true);					
							
						} // End Price
						
						$button_color = ($btn_color == 'none') ? 'product_btn_nostyle' : $btn_color.' product_btn';
						
						// Add to cart button
						if($type == 'view') {
							if($btn_link == 'button') {
								$shortcode .= '<button class="' . $button_color . ' form-button product_view" onclick="setLocation(\''. $poductUrl .'\')"><span>'. Mage::helper('core')->__('View Product') .'</span></button>';
							} else {
								$shortcode .= '<a class="' . $button_color . ' product_view" href="'.$poductUrl.'" title="'.$name.'">'. Mage::helper('core')->__('View Product') .'</a>';
							}
						}
						
						// Add to cart button
						if($type == 'add' && $product_data->isSaleable()) {						
							if($btn_link == 'button') {
								$shortcode .= '<button class="form-button ' . $button_color . '" onclick="setLocation(\''. Mage::helper('checkout/cart')->getAddUrl($product_data) .'\')"><span>'. Mage::helper('core')->__('Add to Cart') .'</span></button>';
							} else {
								$shortcode .= '<a class="' . $button_color . '" href="'.Mage::helper('checkout/cart')->getAddUrl($product_data).'" title="'.$name.'">'. Mage::helper('core')->__('Add to Cart') .'</a>';
							}
						}
						
						
					$shortcode .= '</div>';
					
				} else { // If product _does not_ exist
				
					$shortcode .= '<p>'.__('Sorry, this product does not exist.','mwi').'</p>';
				
				}
				
				$i++; $shortcode .= ($i % $cols == 0 && count($skus) >= 2) ? '</div>' : ''; // Check if there is 1 or more products, and close .products wrapper
		
			} // End foreach($skus)
			
			$shortcode .= ($i % $cols != 0 && count($skus) >= 2) ? '</div>' : ""; // Check if there is 1 or more products, and close .products wrapper (if there is an uneven number of products in a col)
					
	
  ################################################
  ###                                          ###
  ###        If SKU is _not_ Entered           ###
  ###                                          ###
  ################################################

	} else {
	
		$shortcode .= __('Please specify an SKU for your product or products.','mwi');
	
	}