<?php
	/*
	* @package jck_mwi
	* @version 2.0
	* @updated 2.0.3
	*/
	
	$app = jck_mwi::getapp();

	$shortcode = '';
	$store_url = Mage::getBaseUrl();

  ################################################
  ###                                          ###
  ###              If SKU Entered              ###
  ###                                          ###
  ################################################

	if($sku != '') {
	
		$skus = explode(',',$sku);
		
		$shortcode .= (count($skus) >= 2) ? '<div class="products">' : ''; // Check if there is 1 or more products, and open .products wrapper
	
			foreach($skus as $sku) {
			
				$product_data = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);
			
				if($product_data) { // If product exists
				
					$poductUrl = $store_url.Mage::getModel('catalog/product_url')->getUrlPath($product_data);
					
					$shortcode .= '<div class="product">';
						
						// #### Thumbnail #### //
						if($img === true) {
						$shortcode .= '<a class="product_img" href="'.$poductUrl.'" title="' . $product_data->getName() . '">';
								$shortcode .= '<img width="'.$img_width.'" title="' . $product_data->getName() . '" src="' . Mage::helper('catalog/image')->init($product_data, 'thumbnail')->constrainOnly(TRUE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize($img_width) . '" alt="' . $product_data->getName() . '">';
						$shortcode .= '</a>';
						}
						
						// #### Title #### //
						if($title === true) {
							if($title_tag != '') { 
								$shortcode .= '<'.$title_tag.' class="product_title">'.'<a class="product_img" href="'.$poductUrl.'" title="' . $product_data->getName() . '">'.$product_data->getName().'</a>'.'</'.$title_tag.'>'; 
							} else {
								$shortcode .= '<a class="product_img" href="'.$poductUrl.'" title="' . $product_data->getName() . '">'.$product_data->getName().'</a>'; 
							}
						}
						
						// #### Description #### //
						if($desc === true) { 
							$shortcode .= '<div class="product_desc">'.$product_data->getShortDescription().'</div>';
						}				
						
						if($price === true) {
							// #### Price #### //						
							if($product_data->getTypeId() != Mage_Catalog_Model_Product_Type::TYPE_BUNDLE && $product_data->getTypeId() != Mage_Catalog_Model_Product_Type::TYPE_GROUPED) {
					
								// Don't show price if it's a grouped or bundle product
							
								// #####
								// The below code checks if the price has been explicitly set per website, to avoid converting at normal conversion rates
								// #####							
								$default_sv = jck_mwi::getValue('default_sv', 'default');				
								$stores = $app->getStores();				
								foreach($stores as $store) {					
									if($store->getCode() == $default_sv) { // finding the store if of the default store, to get the default product price					
										$default_store_id = $store->getStoreId();						
										$productId = $product_data->getId(); // get current product ID
										$product = Mage::getModel('catalog/product')->setStoreId($default_store_id)->load($productId); // Check price in default store
										$defaultPrice = $product->getFinalPrice();											
									}					
								}
								
								if($product_data->getFinalPrice() != $defaultPrice) {
									
									$options = array();
									//$options = array( 'position' => 16 ); // Set currency sign to the right.
									$price = $app->getStore()->getCurrentCurrency()->format($product_data->getPrice(), $options, true);
										
								} else {
									
									$price = Mage::helper('core')->currencyByStore($product_data->getFinalPrice(),$storeId,true,false);	
									
								}
								$shortcode.= '<div class="price_box">';
								$shortcode.= '<span class="price">'.$price.'</span>';
								$shortcode.= '</div>';
								// #####
								// End price check
								// #####		
							
							} // End check if grouped or bundle
						} // End Price
						
						// Add to cart button
						if($type == 'view') {
							if($btn_link == 'button') {
								$shortcode .= '<button class="form-button product_view" onclick="setLocation(\''. $poductUrl .'\')"><span>'. __($btn_text,'mwi') .'</span></button>';
							} else {
								$shortcode .= '<a class="product_view" href="'.$poductUrl.'" title="'.$name.'">'. __($btn_text,'mwi') .'</a>';
							}
						}
						
						// Add to cart button
						if($type == 'add' && $product_data->isSaleable()) {						
							if($btn_link == 'button') {
								$shortcode .= '<button class="form-button product_btn" onclick="setLocation(\''. Mage::helper('checkout/cart')->getAddUrl($product_data) .'\')"><span>'. __($btn_text,'mwi') .'</span></button>';
							} else {
								$shortcode .= '<a class="product_btn" href="'.Mage::helper('checkout/cart')->getAddUrl($product_data).'" title="'.$name.'">'. __($btn_text,'mwi') .'</a>';
							}
						}
						
						
					$shortcode .= '</div>';
					
				} else { // If product _does not_ exist
				
					$shortcode .= __('Sorry, this product does not exist.','mwi');
				
				}
		
			} // End foreach($skus)
			
		$shortcode .= (count($skus) >= 2) ? '</div>' : ''; // Check if there is 1 or more products, and close .products wrapper
	
  ################################################
  ###                                          ###
  ###        If SKU is _not_ Entered           ###
  ###                                          ###
  ################################################

	} else {
	
		$shortcode .= __('Please specify an SKU for your product or products.','mwi');
	
	}