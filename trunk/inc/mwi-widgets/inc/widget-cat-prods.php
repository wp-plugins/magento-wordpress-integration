<?php
	/*
	* @package jck_mwi
	* @version 2.0
	* @description Creates our Category Products widget
	* @updated 2.1.4
	*/
class cat_prods extends WP_Widget {
	
  ################################################
  ###                                          ###
  ###              Widget Setup                ###
  ###                                          ###
  ################################################
	
	function cat_prods() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'cat_prods', 'description' => 'Display products as a list from any Magento category.' );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'cat-prods' );

		/* Create the widget. */
		$this->WP_Widget( 'cat-prods', 'Mage/WP: Products from category', $widget_ops, $control_ops );
	}
	
  ################################################
  ###                                          ###
  ###             Widget Display               ###
  ###                                          ###
  ################################################
	
	function widget( $args, $instance ) {
		
		global $jck_mwi;
		$app = Mage::app();
		
		extract( $args );
		
		// Settings obtained from widget	
		$widget_title = $instance['widget_title'];
			
		$cat_id = $instance['cat_id'];
		$randomise = isset( $instance['randomise'] ) ? $instance['randomise'] : false;
		$out_of_stock = isset( $instance['out_of_stock'] ) ? 1 : 0;
		$show = $instance['show'];
		
		$show_title = isset( $instance['show_title'] ) ? $instance['show_title'] : false;
		$link_title = isset( $instance['link_title'] ) ? $instance['link_title'] : false;
		
		$show_price = isset( $instance['show_price'] ) ? $instance['show_price'] : false;
		
		$link_img = isset( $instance['link_img'] ) ? $instance['link_img'] : false;
		$show_img = isset( $instance['show_img'] ) ? $instance['show_img'] : false;
		$img_width = $instance['img_width'];
		
		$view_product = isset( $instance['view_product'] ) ? $instance['view_product'] : false;
		$vp_btn_link = $instance['vp_btn_link'];
		$vp_btn_link_color = $instance['vp_btn_link_color'];
		
		$add_to_cart = isset( $instance['add_to_cart'] ) ? $instance['add_to_cart'] : false;
		$atc_btn_link = $instance['atc_btn_link'];
		$atc_btn_link_color = $instance['atc_btn_link_color'];
		
		$theProductBlock = new Mage_Catalog_Block_Product;
		
		// Initiate Magento products		
		$storeId = $app->getStore()->getId(); // Get current store ID		
		$category = new Mage_Catalog_Model_Category();
		$category->load($cat_id);
		$collection = $category->getProductCollection()
		//->addAttributeToSelect('*')
		->addAttributeToSelect(array('name', 'product_url', 'thumbnail', 'price', 'special_price', 'group_price'))
	    ->addAttributeToFilter('status', 1)
	    ->addAttributeToFilter('visibility', array('neq' => 1));
		
		$store_url = Mage::getBaseUrl();
		
		$ids = array();
		foreach($collection as $product) {
			$ids[] = $product->getId();
		}
		
		if($randomise) {
			shuffle($ids);
		}
		
		// Start widget output
		echo $before_widget;	
		echo $before_title;
		echo $widget_title;
		echo $after_title;	
		
		$html = "<ul class='mwi_product_widget'>";
		
			$i = 0; foreach($ids as $id){
				$prod_model = Mage::getModel('catalog/product')->load($id); /* Load Products by ID*/
				
				if($prod_model->getIsInStock() >= $out_of_stock) {
				
					$name = trim($prod_model->getName());
					$poductUrl = $prod_model->getProductUrl();
					//$attribute = trim($prod_model->getAttributeText('colour')); // get attribute text
					
					$class = ($i+1 == $show) ? ' class="last"' : '';
					$html .= "<li".$class.">";
					
					
						if($show_img) {
							
							if($link_img) { $html .= '<a class="product_img" href="'.$poductUrl.'" title="'.$name.'">'; $img_class = ''; } else {  $img_class = ' class="product_img"'; }
							$html .= '<img'.$img_class.' width="'.$img_width.'" title="' . $name . '" src="' . Mage::helper('catalog/image')->init($prod_model, 'thumbnail')->constrainOnly(TRUE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize($img_width) . '" alt="' . $name . '">';
							if($link_img) { $html .= '</a>'; }
						
						} // End if $show_img
						
						if($show_title) {
							if($link_title) { 
								$html .= '<a class="product_title" href="'.$poductUrl.'" title="'.$name.'">'.$name.'</a>'; 
							} else { 
								$html .= '<span class="product_title">'.$name.'</span>'; 
							}
						} // End if $show_title
						
						if($show_price) {				
							// #### Price #### //	
							$html .= $theProductBlock->getPriceHtml($prod_model, true);
						} // End if price
						
						
						// Add to cart button
						if($view_product) {
							if($vp_btn_link == 'btn') {
								$html .= '<button class="product_btn ' . $vp_btn_link_color .' form-button product_view" onclick="setLocation(\''. $poductUrl .'\')"><span>'. Mage::helper('core')->__('View Product') .'</span></button>';
							} else {
								$html .= '<a class="product_btn ' . $vp_btn_link_color .' product_view" href="'.$poductUrl.'" title="'.$name.'">'. Mage::helper('core')->__('View Product') .'</a>';
							}
						}
						
						
						// Add to cart button
						if($add_to_cart && $prod_model->isSaleable()) {						
							if($atc_btn_link == 'btn') {
								$html .= '<button class="form-button product_btn ' . $atc_btn_link_color .'" onclick="setLocation(\''. Mage::helper('checkout/cart')->getAddUrl($prod_model) .'\')"><span>'. Mage::helper('core')->__('Add to Cart') .'</span></button>';
							} else {
								$html .= '<a class="product_btn ' . $atc_btn_link_color .'" href="'.Mage::helper('checkout/cart')->getAddUrl($prod_model).'" title="'.$name.'">'. Mage::helper('core')->__('Add to Cart') .'</a>';
							}
						}
					
					$html .= '</li>';
					
					if (++$i == $show) break;
					
				} // end check if in stock
				
			} // End foreach
		
		$html .= "</ul>";
		
		echo $html;
			
		echo $after_widget;

	}
	
  ################################################
  ###                                          ###
  ###              Widget Save                 ###
  ###                                          ###
  ################################################
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags (if needed) and update the widget settings. */
		$instance['widget_title'] = strip_tags( $new_instance['widget_title'] );
		
		$instance['cat_id'] = strip_tags( $new_instance['cat_id'] );
		$instance['randomise'] = $new_instance['randomise'];
		$instance['out_of_stock'] = $new_instance['out_of_stock'];
		$instance['show'] = strip_tags( $new_instance['show'] );
			
		$instance['show_title'] = $new_instance['show_title'];
		$instance['link_title'] = $new_instance['link_title'];
		
		$instance['show_price'] = $new_instance['show_price'];
		
		$instance['link_img'] = $new_instance['link_img'];
		$instance['show_img'] = $new_instance['show_img'];
		$instance['img_width'] = strip_tags( $new_instance['img_width'] );
		
		$instance['view_product'] = $new_instance['view_product'];
		$instance['vp_btn_link'] = $new_instance['vp_btn_link'];
		$instance['vp_btn_link_color'] = $new_instance['vp_btn_link_color'];
		
		$instance['add_to_cart'] = $new_instance['add_to_cart'];
		$instance['atc_btn_link'] = $new_instance['atc_btn_link'];
		$instance['atc_btn_link_color'] = $new_instance['atc_btn_link_color'];
		
		// $instance['sex'] = $new_instance['sex'];

		return $instance;
	}
	
  ################################################
  ###                                          ###
  ###              Widget Save                 ###
  ###                                          ###
  ################################################
	
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 
			'widget_title' => 'Featured Products', 
			'cat_id' => '', 
			'randomise' => 'on',
			'out_of_stock' => 'on', 
			'show' => 5,
			'show_title' => 'on', 
			'link_title' => 'on',
			'show_price' => 'on',
			'link_img' => 'on',
			'show_img' => 'on', 
			'img_width' => 150 ,
			'view_product' => 'on',
			'vp_btn_link' => 'btn',
			'vp_btn_link_color' => 'blue',
			'add_to_cart' => 'on',
			'atc_btn_link' => 'btn',
			'atc_btn_link_color' => 'blue'
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
    
    <h4 style="margin-top:0;"><?php _e('Widget Settings'); ?></h4>
    
    <p>
			<label for="<?php echo $this->get_field_id( 'widget_title' ); ?>"><?php _e('Widget Title:'); ?><br />
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'widget_title' ); ?>" name="<?php echo $this->get_field_name( 'widget_title' ); ?>" value="<?php echo $instance['widget_title']; ?>" />
      </label>
		</p>
    
    <h4 style="margin-top:0;"><?php _e('Product Display'); ?></h4>
    
    <p>
			<label for="<?php echo $this->get_field_id( 'cat_id' ); ?>"><?php _e('Category ID:'); ?><br />
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'cat_id' ); ?>" name="<?php echo $this->get_field_name( 'cat_id' ); ?>" value="<?php echo $instance['cat_id']; ?>" style="width:60px;" />
      </label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'randomise' ); ?>">
      	<input class="checkbox" type="checkbox" <?php checked( $instance['randomise'], 'on' ); ?> id="<?php echo $this->get_field_id( 'randomise' ); ?>" name="<?php echo $this->get_field_name( 'randomise' ); ?>" />
				<?php _e('Randomise products?'); ?>
      </label>
		</p>
    
    <p>
			<label for="<?php echo $this->get_field_id( 'show' ); ?>"><?php _e('Number of Products to show:'); ?><br />
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'show' ); ?>" name="<?php echo $this->get_field_name( 'show' ); ?>" value="<?php echo $instance['show']; ?>" style="width:60px;" />
      </label>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'out_of_stock' ); ?>">
      	<input class="checkbox" type="checkbox" <?php checked( $instance['out_of_stock'], 'on' ); ?> id="<?php echo $this->get_field_id( 'out_of_stock' ); ?>" name="<?php echo $this->get_field_name( 'out_of_stock' ); ?>" />
				<?php _e('Hide out of stock products?'); ?>
      </label>
		</p>
    
    <hr style="height:0; border:none; border-top:1px solid #DFDFDF; border-bottom:1px solid #fff; width:100%; margin:20px 0 0;" />
    
    <h4><?php _e('Product Title'); ?></h4>
    
    <p>
			<label for="<?php echo $this->get_field_id( 'show_title' ); ?>">
      	<input class="checkbox" type="checkbox" <?php checked( $instance['show_title'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_title' ); ?>" name="<?php echo $this->get_field_name( 'show_title' ); ?>" />
				<?php _e('Show Title?'); ?>
      </label>
		</p>
    
    <p>
			<label for="<?php echo $this->get_field_id( 'link_title' ); ?>">
      	<input class="checkbox" type="checkbox" <?php checked( $instance['link_title'], 'on' ); ?> id="<?php echo $this->get_field_id( 'link_title' ); ?>" name="<?php echo $this->get_field_name( 'link_title' ); ?>" />
				<?php _e('Link Title?'); ?>
      </label>
		</p>
    
    <hr style="height:0; border:none; border-top:1px solid #DFDFDF; border-bottom:1px solid #fff; width:100%; margin:20px 0 0;" />
    
    <h4><?php _e('Product Price'); ?></h4>
    
    <p>
			<label for="<?php echo $this->get_field_id( 'show_price' ); ?>">
      	<input class="checkbox" type="checkbox" <?php checked( $instance['show_price'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_price' ); ?>" name="<?php echo $this->get_field_name( 'show_price' ); ?>" />
				<?php _e('Show Price?'); ?>
      </label>
		</p>
		
	<hr style="height:0; border:none; border-top:1px solid #DFDFDF; border-bottom:1px solid #fff; width:100%; margin:20px 0 0;" />
    
    <h4><?php _e('Product Images'); ?></h4>
    
    <p>
			<label for="<?php echo $this->get_field_id( 'show_img' ); ?>">
      	<input class="checkbox" type="checkbox" <?php checked( $instance['show_img'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_img' ); ?>" name="<?php echo $this->get_field_name( 'show_img' ); ?>" />
				<?php _e('Show Image?'); ?>
      </label>
		</p>
    
    <p>
			<label for="<?php echo $this->get_field_id( 'link_img' ); ?>">
      	<input class="checkbox" type="checkbox" <?php checked( $instance['link_img'], 'on' ); ?> id="<?php echo $this->get_field_id( 'link_img' ); ?>" name="<?php echo $this->get_field_name( 'link_img' ); ?>" />
				<?php _e('Link Image?'); ?>
      </label>
		</p>
    
    <p>
			<label for="<?php echo $this->get_field_id( 'img_width' ); ?>"><?php _e('Image Width:'); ?><br />
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'img_width' ); ?>" name="<?php echo $this->get_field_name( 'img_width' ); ?>" value="<?php echo $instance['img_width']; ?>" style="width:60px;" /> <?php _e('px'); ?>
      </label>
		</p>
    
    <hr style="height:0; border:none; border-top:1px solid #DFDFDF; border-bottom:1px solid #fff; width:100%; margin:20px 0 0;" />
    
    <h4><?php _e('View Product Button'); ?></h4>
    
    <p>
			<label for="<?php echo $this->get_field_id( 'view_product' ); ?>">
      	<input class="checkbox" type="checkbox" <?php checked( $instance['view_product'], 'on' ); ?> id="<?php echo $this->get_field_id( 'view_product' ); ?>" name="<?php echo $this->get_field_name( 'view_product' ); ?>" />
				<?php _e('Show View Product Button?'); ?>
      </label>
		</p>
    
    <p>
			<label for="<?php echo $this->get_field_id( 'vp_btn_link' ); ?>"><?php _e('Button or Link?'); ?><br />
			<select id="<?php echo $this->get_field_id( 'vp_btn_link' ); ?>" name="<?php echo $this->get_field_name( 'vp_btn_link' ); ?>" class="widefat" style="width:100%;">
				<option value="btn" <?php if ( 'btn' == $instance['vp_btn_link'] ) echo 'selected="selected"'; ?>>Button</option>
				<option value="link" <?php if ( 'link' == $instance['vp_btn_link'] ) echo 'selected="selected"'; ?>>Link</option>
			</select>
      </label>
		</p>
    
    <p>
			<label for="<?php echo $this->get_field_id( 'vp_btn_link_color' ); ?>"><?php _e('Button/Link Color:'); ?><br />
			<select id="<?php echo $this->get_field_id( 'vp_btn_link_color' ); ?>" name="<?php echo $this->get_field_name( 'vp_btn_link_color' ); ?>" class="widefat" style="width:100%;">
				<option value="blue" <?php if ( 'blue' == $instance['vp_btn_link_color'] ) echo 'selected="selected"'; ?>>Blue</option>
				<option value="orange" <?php if ( 'orange' == $instance['vp_btn_link_color'] ) echo 'selected="selected"'; ?>>Orange</option>
				<option value="black" <?php if ( 'black' == $instance['vp_btn_link_color'] ) echo 'selected="selected"'; ?>>Black</option>
				<option value="gray" <?php if ( 'gray' == $instance['vp_btn_link_color'] ) echo 'selected="selected"'; ?>>Gray</option>
				<option value="white" <?php if ( 'white' == $instance['vp_btn_link_color'] ) echo 'selected="selected"'; ?>>White</option>
				<option value="red" <?php if ( 'red' == $instance['vp_btn_link_color'] ) echo 'selected="selected"'; ?>>Red</option>
				<option value="rosy" <?php if ( 'rosy' == $instance['vp_btn_link_color'] ) echo 'selected="selected"'; ?>>Rosy</option>
				<option value="green" <?php if ( 'green' == $instance['vp_btn_link_color'] ) echo 'selected="selected"'; ?>>Green</option>
				<option value="pink" <?php if ( 'pink' == $instance['vp_btn_link_color'] ) echo 'selected="selected"'; ?>>Pink</option>
			</select>
      </label>
		</p>
    
    <hr style="height:0; border:none; border-top:1px solid #DFDFDF; border-bottom:1px solid #fff; width:100%; margin:20px 0 0;" />
    
    <h4><?php _e('Add to Cart Button'); ?></h4>
    
    <p>
			<label for="<?php echo $this->get_field_id( 'add_to_cart' ); ?>">
      	<input class="checkbox" type="checkbox" <?php checked( $instance['add_to_cart'], 'on' ); ?> id="<?php echo $this->get_field_id( 'add_to_cart' ); ?>" name="<?php echo $this->get_field_name( 'add_to_cart' ); ?>" />
				<?php _e('Show Add to Cart Button?'); ?>
      </label>
		</p>
    
    <p>
			<label for="<?php echo $this->get_field_id( 'atc_btn_link' ); ?>"><?php _e('Button or Link?'); ?><br />
			<select id="<?php echo $this->get_field_id( 'atc_btn_link' ); ?>" name="<?php echo $this->get_field_name( 'atc_btn_link' ); ?>" class="widefat" style="width:100%;">
				<option value="btn" <?php if ( 'btn' == $instance['atc_btn_link'] ) echo 'selected="selected"'; ?>>Button</option>
				<option value="link" <?php if ( 'link' == $instance['atc_btn_link'] ) echo 'selected="selected"'; ?>>Link</option>
			</select>
      </label>
		</p>
    
    <p>
			<label for="<?php echo $this->get_field_id( 'atc_btn_link_color' ); ?>"><?php _e('Button/Link Color:'); ?><br />
				<select id="<?php echo $this->get_field_id( 'atc_btn_link_color' ); ?>" name="<?php echo $this->get_field_name( 'atc_btn_link_color' ); ?>" class="widefat" style="width:100%;">
					<option value="blue" <?php if ( 'blue' == $instance['atc_btn_link_color'] ) echo 'selected="selected"'; ?>>Blue</option>
					<option value="orange" <?php if ( 'orange' == $instance['atc_btn_link_color'] ) echo 'selected="selected"'; ?>>Orange</option>
				</select>
      </label>
		</p>
    
    <?php
	}
	
} // End class cat_prods