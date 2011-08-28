<?php header("Content-type: text/css");
/*
	Styles for Magento Wordpess Integration Products
	@since 2.0.0

	Magento Wordpess Integration
	Copyright (c) 2011 James C Kemp

*/ 
require_once('../includes/load-wp.php' );
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


$products_grid_width = (($jk_mwi_grid_col - 1) * $jk_mwi_grid_col_spacing) + ($jk_mwi_initial_image_width * $jk_mwi_grid_col);
$blur_max = max($jk_mwi_shadow_x, $jk_mwi_shadow_y);

?>

button::-moz-focus-inner { 
    border: 0;
    padding: 0;
}

#jk_mwi_products { 
	<?php if($jk_mwi_grid_col != 1) { ?>
	width:<?php echo $products_grid_width + (($jk_mwi_shadow_blur + $blur_max)*2); ?>px; 
    <?php } else { ?>
    width:100%;
    <?php } ?>
    overflow:hidden;
    <?php if($jk_mwi_shadow == 1) { ?>
    margin: -<?php echo ($jk_mwi_shadow_blur + $blur_max); ?>px -<?php echo ($jk_mwi_shadow_blur + $blur_max); ?>px <?php echo $jk_mwi_grid_col_spacing; ?>px;
    <?php } else { ?>
    margin: 0 0 <?php echo $jk_mwi_grid_col_spacing; ?>px;
    <?php } ?>
}

.jk_mwi_products_grid { 
	<?php if($jk_mwi_grid_col != 1) { ?>
	width:<?php echo $products_grid_width; ?>px;
    <?php } else { ?>
    width:100%;
    <?php } ?>
    overflow:hidden; 
    <?php if($jk_mwi_shadow == 1) { ?>
    padding: <?php echo ($jk_mwi_shadow_blur + $blur_max); ?>px;
    <?php } ?>
    float: left;
    clear: both;
    margin:0 0 20px;
}
.jk_mwi_product { 
	<?php if($jk_mwi_grid_col != 1) { ?>
    width:<?php echo $jk_mwi_initial_image_width; ?>px; 
    <?php } else { ?>
    width:100%;
    <?php } ?>
    float:left; 
    display:inline; 
    margin:0 <?php echo $jk_mwi_grid_col_spacing; ?>px 0 0
}

.jk_mwi_product p { margin: 0 0 15px !important; }

.last { margin:0; }

.jk_mwi_image { 
	display:block; 
    overflow:hidden; 
    width:<?php echo $jk_mwi_image_width; ?>px;  
    <?php if($jk_mwi_shadow == 1) { ?>
    -moz-box-shadow: <?php echo $jk_mwi_shadow_x; ?>px <?php echo $jk_mwi_shadow_y; ?>px <?php echo $jk_mwi_shadow_blur; ?>px #<?php echo $jk_mwi_shadow_color; ?>; /* Firefox */
  	-webkit-box-shadow: <?php echo $jk_mwi_shadow_x; ?>px <?php echo $jk_mwi_shadow_y; ?>px <?php echo $jk_mwi_shadow_blur; ?>px #<?php echo $jk_mwi_shadow_color; ?>; /* Safari, Chrome */
  	box-shadow: <?php echo $jk_mwi_shadow_x; ?>px <?php echo $jk_mwi_shadow_y; ?>px <?php echo $jk_mwi_shadow_blur; ?>px #<?php echo $jk_mwi_shadow_color; ?>; /* CSS3 */
    <?php } ?>
    <?php if($jk_mwi_image_border == 1) { ?>
    border: <?php echo $jk_mwi_border_width.'px solid #'; echo $jk_mwi_border_color; ?>;
    <?php if($jk_mwi_border_corners == 1) { ?>
    -webkit-border-radius: <?php echo $jk_mwi_border_corner_radius; ?>px <?php echo $jk_mwi_border_corner_radius; ?>px <?php echo $jk_mwi_border_corner_radius; ?>px <?php echo $jk_mwi_border_corner_radius; ?>px;
    -moz-border-radius: <?php echo $jk_mwi_border_corner_radius; ?>px <?php echo $jk_mwi_border_corner_radius; ?>px <?php echo $jk_mwi_border_corner_radius; ?>px <?php echo $jk_mwi_border_corner_radius; ?>px;
    border-radius: <?php echo $jk_mwi_border_corner_radius; ?>px <?php echo $jk_mwi_border_corner_radius; ?>px <?php echo $jk_mwi_border_corner_radius; ?>px <?php echo $jk_mwi_border_corner_radius; ?>px;
    <?php } ?>
    <?php } ?>
    margin:0 0 15px;
    <?php if($jk_mwi_grid_col == 1) { ?>
    float:left;
    display:inline;
    <?php } ?>
}
.jk_mwi_image img { float:left; width:<?php echo $jk_mwi_image_width; ?>px; }


<?php if($btn_style == 'large') { ?>
<?php if($jk_mwi_product_options['button_style'] == 'lgreen') { ?>
.jk_mwi-addto {  
    display: block;   
    cursor: pointer;   
    border:none;
	background:url(../images/green-addto.png) no-repeat left 0;
	padding:0;
	margin:15px 0 0;
	width:auto;
	overflow:visible;					
	text-align:center;	
	white-space:nowrap;	
	height:42px;
}
.jk_mwi-addto span { 
	background:url(../images/green-addto.png) no-repeat right 0; 
    height:42px; 
    padding:0 0 0 112px; 
    color: #678338;
    float: left;
    font: bold 13px/42px Tahoma,Verdana,Arial,sans-serif;
    text-align: center;
    text-transform: uppercase;
    white-space: nowrap;
}
.jk_mwi-addto span span { background:url(../images/green-addto-repeat.png) repeat 0 0; margin:0 20px 0 0; padding:0 0 0 10px; line-height:42px; text-shadow: -1px 1px 0 #d0e5a4  }
<?php } ?>

<?php if($jk_mwi_product_options['button_style'] == 'lblack') { ?>
.jk_mwi-addto {  
    display: block;   
    cursor: pointer;   
    border:none;
	background:url(../images/black-addto.png) no-repeat left 0;
	padding:0;
	margin:15px 0 0;
	width:auto;
	overflow:visible;					
	text-align:center;	
	white-space:nowrap;	
	height:42px;
}
.jk_mwi-addto span { 
	background:url(../images/black-addto.png) no-repeat right 0; 
    height:42px; 
    padding:0 0 0 112px; 
    color: #fff;
    float: left;
    font: bold 13px/42px Tahoma,Verdana,Arial,sans-serif;
    text-align: center;
    text-transform: uppercase;
    white-space: nowrap;
}
.jk_mwi-addto span span { background:url(../images/black-addto-repeat.png) repeat 0 0; margin:0 20px 0 0; padding:0 0 0 10px; line-height:42px; text-shadow: -1px 1px 0 #000  }
<?php } ?>

<?php if($jk_mwi_product_options['button_style'] == 'lsilver') { ?>
.jk_mwi-addto {  
    display: block;   
    cursor: pointer;   
    border:none;
	background:url(../images/silver-addto.png) no-repeat left 0;
	padding:0;
	margin:15px 0 0;
	width:auto;
	overflow:visible;					
	text-align:center;	
	white-space:nowrap;	
	height:42px;
}
.jk_mwi-addto span { 
	background:url(../images/silver-addto.png) no-repeat right 0; 
    height:42px; 
    padding:0 0 0 112px; 
    color: #222;
    float: left;
    font: bold 13px/42px Tahoma,Verdana,Arial,sans-serif;
    text-align: center;
    text-transform: uppercase;
    white-space: nowrap;
}
.jk_mwi-addto span span { background:url(../images/silver-addto-repeat.png) repeat 0 0; margin:0 20px 0 0; padding:0 0 0 10px; line-height:42px; text-shadow: -1px 1px 0 #fff  }
<?php } ?>
.configurable, .grouped, .bundle, .simple {
	background-position: left bottom;
    margin:0
}
<?php } else { ?>
<?php if($jk_mwi_product_options['button_style'] != 'nostyle') { ?>
.jk_mwi-addto {  
    display: inline;   
    cursor: pointer;   
    border:none;
	background:url(../images/small-buttons.png) no-repeat -1px 0;
	padding:0;
	margin:15px 0 0 10px;
	width:89px;
	overflow:visible;					
	text-align:center;	
	white-space:nowrap;	
	height:25px;
    overflow:hidden;
    text-indent:-9999px;
    float:left;
}
.jk_mwi_price {
	float: left;
    line-height: 25px;
    margin: 15px 0;
    display:inline;
}

.configurable, .grouped, .bundle, .simple {
	background-position: -101px 0;
}

<?php if($jk_mwi_product_options['button_style'] == 'sblack') { ?>
.jk_mwi-addto {
	background-position:-1px -40px;
}
.configurable, .grouped, .bundle, .simple {
	background-position: -101px -40px;
}
<?php } ?>

<?php if($jk_mwi_product_options['button_style'] == 'ssilver') { ?>
.jk_mwi-addto {
	background-position:-1px -80px;
}
.configurable, .grouped, .bundle, .simple {
	background-position: -101px -80px;
}
<?php } ?>

<?php } ?>
<?php } ?>
.jk_mwi_product .qty-box {
    overflow:hidden;
    display:block;
    margin:0 0 5px;
}

.jk_mwi_product .qty-box .qty {
	background:#FFFFFF;
    border: 4px solid #EEEEEE;
    border-radius: 3px 3px 3px 3px;
    -webkit-border-radius: 3px 3px 3px 3px;
    -moz-border-radius: 3px 3px 3px 3px;
    box-shadow: none;
    float: left;
    height: 20px;
    line-height: 20px;
    margin: 0 0 0 10px;
    width: 35px;
    display:inline;
}
.jk_mwi_product .qty-box label {
	float:left;
    display:inline;
    width:34px;
    line-height: 29px;
}

.jk_mwi_f-right {
	float: left;
    display:inline;
    margin: 0 0 0 20px;
    width: 400px;
}