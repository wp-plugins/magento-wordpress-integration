<?php 
/*
	Loads Products defined in a post or page
	@since 2.0.0

	Magento Wordpress Integration
	Copyright (c) 2011 James C Kemp

*/	
require_once('load-wp.php' ); 

$post_sku = get_post_meta($post->ID, 'jk_mwi_product_sku', TRUE); 

$jk_mwi_product_options = get_option('jk_mwi_product_options');

// Setup
$jk_mwi_grid_col = $jk_mwi_product_options['option_two'];
$jk_mwi_grid_col_spacing = $jk_mwi_product_options['option_three'];
$jk_mwi_initial_image_width = $jk_mwi_product_options['option_four'];

// Border
$jk_mwi_image_border = $jk_mwi_product_options['option_nine'];
$jk_mwi_border_color = $jk_mwi_product_options['option_ten'];
$jk_mwi_border_width = $jk_mwi_product_options['option_eleven'];
$jk_mwi_border_corners = $jk_mwi_product_options['option_twelve'];
$jk_mwi_border_corner_radius = $jk_mwi_product_options['option_thirteen'];

// Shadow
$jk_mwi_shadow = $jk_mwi_product_options['option_fourteen'];
$jk_mwi_shadow_x = $jk_mwi_product_options['option_fifteen'];
$jk_mwi_shadow_y = $jk_mwi_product_options['option_sixteen'];
$jk_mwi_shadow_blur = $jk_mwi_product_options['option_seventeen'];
$jk_mwi_shadow_color = $jk_mwi_product_options['option_eighteen'];

// Widths
if($jk_mwi_image_border == 1) {
	$jk_mwi_image_width = $jk_mwi_initial_image_width - ($jk_mwi_border_width * 2);
} else {
	$jk_mwi_image_width = $jk_mwi_initial_image_width;
}

// Data
$jk_mwi_show_title = $jk_mwi_product_options['option_five'];
$jk_mwi_title_tag = $jk_mwi_product_options['option_six'];
$jk_mwi_show_desc = $jk_mwi_product_options['option_seven'];
$jk_mwi_add_or_view = $jk_mwi_product_options['option_eight'];
$jk_mwi_btn_style = $jk_mwi_product_options['button_style'];

if($jk_mwi_btn_style == 'lgreen' || $jk_mwi_btn_style == 'lblack' || $jk_mwi_btn_style == 'lsilver') {
	$btn_style = 'large';
} else {
	$btn_style = 'small';
}

// End STYLING

$post_skus = explode(',',$post_sku);

$count_products = count($post_skus);
$product_number = 1;

$output = '';


// Start Output
if($post_sku) {
	
	$output .= '<div id="jk_mwi_products">';
	
	$i = 1; foreach($post_skus as $post_sku) {
		
		$_product = Mage::getModel('catalog/product')->loadByAttribute('sku', $post_sku);
		
		if($_product) {
			
			if($i == 1) { $output .= '<div class="jk_mwi_products_grid">'; } 
			$output .= '<div class="jk_mwi_product'; if($i == $jk_mwi_grid_col) { $output .= ' last'; } $output .= '">';

                $output .= '<a class="jk_mwi_image" href="' . $prod_url . '" title="' . $_product->getName() . '">';
                    $output .= '<img width="' . $jk_mwi_image_width . '" title="' . $_product->getName() . '" src="' . Mage::helper('catalog/image')->init($_product, 'thumbnail')->constrainOnly(TRUE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize($jk_mwi_image_width) . '" alt="' . $_product->getName() . '">';
                $output .= '</a>';
				
				if($jk_mwi_grid_col == 1) {
					$output .= '<div class="jk_mwi_f-right">';
				}
				
				if($jk_mwi_show_title) {
				$output .= '<'.$jk_mwi_title_tag.' class="jk_mwi_product_title"><a href="' . $prod_url . '" title="' . $_product->getName() . '">' . $_product->getName() . '</a></'.$jk_mwi_title_tag.'>';
				}

				$output .= '<div class="post-product-info">';
					
					if($jk_mwi_show_desc) {
						$output .= $_product->getShortDescription();
					}
					
					if ($_product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_SIMPLE && $jk_mwi_add_or_view == "add") {
						
						$attVal = Mage::getModel('Catalog/Product_Option')->getProductOptionCollection($_product);
						if($post_product_url) { $prod_url = $post_product_url; } else { $prod_url = $_product->getProductUrl(); };
						$jk_mwi_mage = get_option('jk_mwi_magepath');
                        
                        $output .= '<form method="get" action="' . Mage::getUrl('checkout/cart') . 'add">';
                        	$output .= '<input type="hidden" value="' . $_product->getID() . '" name="product" />';

							$options = "";
                            $hasAtts = 0;
                            
                            // $attVal = $_product->getOptions();
                            
                            if(sizeof($attVal)) {
                            
                            $hasAtts++;
                            
                            foreach($attVal as $optionVal) {
                                $options .= '<div style="clear:both; margin:0 0 15px;">';
                                $options .= $optionVal->getTitle().": ";
                                $options .= "<select name='options[".$optionVal->getId()."]'>";
                                
                                foreach($optionVal->getValues() as $valuesKey => $valuesVal) {
                                    $options .= "<option value='".$valuesVal->getId()."'>".$valuesVal->getTitle()."</option>";
                                }
                                
                                $options .= "</select>";
                                $options .= '</div>';
                            }
                            
                            $output .= '$options';
                            
                            }
                            

                            $output .= '<span class="qty-box"><label for="qty">Qty:</label>';
                            $output .= '<input type="text" value="" maxlength="12" class="input-text qty" name="qty"></span>';
							
							if($btn_style == 'large') {
								$output .= '<button class="jk_mwi-addto" type="submit"><span><span>&pound;' . number_format($_product->getFinalPrice(), 2, '.', '') . '</span></span></button>';
							} else {
								$output .= '<span class="jk_mwi_price">&pound;' . number_format($_product->getFinalPrice(), 2, '.', '').'</span>';
								$output .= '<button class="jk_mwi-addto" type="submit">Add to Cart</button>';
							}
						$output .= '</form>';

					} elseif ($_product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_SIMPLE && $jk_mwi_add_or_view == "view") {
						
						if($btn_style == 'large') {
							$output .= '<button class="jk_mwi-addto simple" type="submit"><span><span>&pound;' . number_format($_product->getFinalPrice(), 2, '.', '') . '</span></span></button>';
						} else {
							$output .= '<span class="jk_mwi_price">&pound;' . number_format($_product->getFinalPrice(), 2, '.', '').'</span>';
							$output .= '<button class="jk_mwi-addto simple" type="submit">More Info</button>';
						}

					} elseif ($_product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE) {
						
						if($btn_style == 'large') {
							$output .= '<button class="jk_mwi-addto bundle" type="submit"><span><span>&pound;' . number_format($_product->getFinalPrice(), 2, '.', '') . '</span></span></button>';
						} else {
							$output .= '<span class="jk_mwi_price">&pound;' . number_format($_product->getFinalPrice(), 2, '.', '').'</span>';
							$output .= '<button class="jk_mwi-addto bundle" type="submit">More Info</button>';
						}

					} elseif ($_product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_GROUPED) {

						if($btn_style == 'large') {
							$output .= '<button class="jk_mwi-addto grouped" type="submit"><span><span>&pound;' . number_format($_product->getFinalPrice(), 2, '.', '') . '</span></span></button>';
						} else {
							$output .= '<span class="jk_mwi_price">&pound;' . number_format($_product->getFinalPrice(), 2, '.', '').'</span>';
							$output .= '<button class="jk_mwi-addto grouped" type="submit">More Info</button>';
						}

					} elseif ($_product->getTypeId() == Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE) {

						if($btn_style == 'large') {
							$output .= '<button class="jk_mwi-addto configurable" type="submit"><span><span>&pound;' . number_format($_product->getFinalPrice(), 2, '.', '') . '</span></span></button>';
						} else {
							$output .= '<span class="jk_mwi_price">&pound;' . number_format($_product->getFinalPrice(), 2, '.', '').'</span>';
							$output .= '<button class="jk_mwi-addto configurable" type="submit">More Info</button>';
						}

					}

				$output .= '</div>';
				
				if($jk_mwi_grid_col == 1) {
					$output .= '</div>';
				}

			} else {
				
				if($i == 1) { $output .= '<div class="jk_mwi_products_grid">'; } 

				$output .= '<div class="jk_mwi_product'; if($i == $jk_mwi_grid_col) { $output .= ' last'; } $output .= '">';
					$output .= '<p>Sorry, the product "' . $post_sku . '" does not exist.</p>';
			
			} // End if($_product)
			
		$output .= '</div>'; // End productitem

	if($i == $jk_mwi_grid_col && $count_products != $product_number) { $i = 0; $output .= '</div>'; /* End grid wrapper */ }
	if($count_products == $product_number) { $output .= '</div>';  /* End grid wrapper */ }

	$i++; $product_number++; } // End Foreach
	
	$output .= '</div>'; // End #jk_mwi_products

}

return $output;

?>