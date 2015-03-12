<?php 
    
class jck_mwi_helpers {

/**	=============================
    *
    * Get all required data for a product layout
    *
    * @product_model obj Magento product model
    * @args array WP shortcode atts
    * @return array
    *
    ============================= */
    
    public function get_product_data( $product_model, $args ) {
        
        $product_block = new Mage_Catalog_Block_Product;
        
        $product_data = array();
        
        $product_data['name']               =   trim($product_model->getName());
        $product_data['url']                =   $product_model->getProductUrl();	
        $product_data['images']             =   false;
        $product_data['description']        =   false;
        $product_data['price']              =   false;
        $product_data['add_url']            =   false;
        $product_data['saleable']           =   $product_model->isSaleable();
        $product_data['in_stock']           =   $product_model->getIsInStock();
        
        if( $args['img'] ) {
        			        
            $product_data['images']         =   array(
                                                    'thumb1x' => Mage::helper('catalog/image')->init($product_model, 'thumbnail')->constrainOnly(TRUE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize((int)$args['img_width'])->__toString(),
                                                    'thumb2x' => Mage::helper('catalog/image')->init($product_model, 'thumbnail')->constrainOnly(TRUE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize((int)$args['img_width']*2)->__toString()
                                                );
        
        }
        
        if( $args['desc'] ) {                   

            // get and trim description
            $desc = trim($product_model->getShortDescription());
            // remove first and last p tags
            $desc = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $desc);
            // replace p tags with br br
            $desc = str_replace(array('<p>','</p>'), array('','<br /><br />'), $desc);
                            
            $product_data['description']    =   $desc;
        
        }
        
        if( $args['price'] ) {
        
            $product_data['price']          =   preg_replace( '/\s+/', ' ', $product_block->getPriceHtml($product_model, true));
        
        }
        
        if( $args['type'] == "add" ) {
        
            $product_data['add_url']        =   Mage::helper('checkout/cart')->getAddUrl($product_model);
        
        }
        
        return $product_data;
        
    }
    
}