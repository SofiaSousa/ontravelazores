<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// location taxonomy fields
if ( ! class_exists( 'Tax_Meta_Class' ) ) {
	return;
}
if (is_admin()){
	$prefix = 'ct_';
    
	$config = array(
		'id' => 'ct_info',
		'title' => 'Custom Icon Class',
		'pages' => array( 'tour_type', 'tour_facility', 'hotel_type', 'hotel_facility', 'car_facility' ),
		'context' => 'normal',
		'fields' => array(),
		'local_images' => false,
		'use_with_theme' => true
	);

	$my_meta =  new Tax_Meta_Class($config);
	$my_meta->addText($prefix.'tax_icon_class',array('name'=> esc_html__('Custom Icon Class','citytours'),'desc' => 'You can check <a href="http://www.soaptheme.net/wordpress/citytours/icon-pack-1/">Icon Pack1</a> and <a href="http://www.soaptheme.net/wordpress/citytours/icon-pack-2/">Icon Pack2</a> for class detail'));

	$my_meta->Finish();

	$config = array(
        'id' => 'ct_info_img',
        'title' => 'Marker Image in Map',
        'pages' => array('tour_type'),
        'context' => 'normal',
        'fields' => array(),
        'local_images' => false,
        'use_with_theme' => true
    );
    $my_meta =  new Tax_Meta_Class($config);
    $my_meta->addText($prefix.'tax_marker_img', array( 'type'=>'image', 'name'=> esc_html__( 'Marker Images in Map','citytours' ) ) );

    $my_meta->Finish();
}