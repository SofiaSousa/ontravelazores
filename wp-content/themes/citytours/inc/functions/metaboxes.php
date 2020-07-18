<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'admin_enqueue_scripts', 'ct_admin_enqueue_scripts', 10, 1 );

add_filter( 'rwmb_meta_boxes', 'ct_plugin_register_meta_boxes' );
add_filter( 'rwmb_meta_boxes', 'ct_theme_register_meta_boxes' );


add_action( 'admin_enqueue_scripts', 'ct_metabox_admin_enqueue_scripts' );
/*
 * tour metabox enqueue script
 */
if ( ! function_exists( 'ct_admin_enqueue_scripts' ) ) {
    function ct_admin_enqueue_scripts( $hook ) {
        $screen = get_current_screen();

        if ( 'post' == $screen->base ) {
            wp_enqueue_script( 'ct_admin_hotel_admin_js', CT_TEMPLATE_DIRECTORY_URI . '/js/admin/admin.js', array('jquery'), '', true );
        }
    }
}

/*
 * post metabox registration
 */
if ( ! function_exists( 'ct_post_register_meta_boxes' ) ) { 
    function ct_post_register_meta_boxes() {
        $meta_boxes = array();
        
            $meta_boxes[] = array(
                'id'          => 'slider_setting',
                'title'       => esc_html__( 'Slider Setting', 'citytours' ),
                'pages'        => array('page'),
                'context'     => 'normal',
                'priority'    => 'high',
                'fields'      => array( 
                    array(
                    'name'      => esc_html__( 'Slider Shortcode', 'citytours' ),
                    'id'        => "_slider_shortcode",
                    'desc'      => esc_html__( 'If you add slider shortcode, Header Image Setting will not work.', 'citytours' ),
                    'type'      => 'text',
                    'std'       => '',
                    )
                )
            );
        
        $meta_boxes[] = array(
            'id' => 'header_image_setting',
            'title' => esc_html__( 'Header Image Setting', 'citytours' ),
            'pages' => array( 'post', 'page', 'tour', 'hotel', 'car' ),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name'   => esc_html__( 'Header Image', 'citytours' ),
                    'id'    => "_header_image",
                    'type'   => 'image_advanced',
                    'desc' => wp_kses_post( sprintf( __( 'If you do not set this field, default image src that you set in <a href="%s" target="_blank">theme options panel</a> will work.', 'citytours' ), admin_url( 'admin.php?page=theme_options' ) ) ) ,
                    'max_file_uploads' => 1,
                ),
                array(
                    'name'  => esc_html__( 'Height (px)', 'citytours' ),
                    'id'      => "_header_image_height",
                    'desc' => wp_kses_post( sprintf( __( 'If you do not set this field, default image height that you set in <a href="%s" target="_blank">theme options panel</a> will work.', 'citytours' ), admin_url( 'admin.php?page=theme_options' ) ) ) ,
                    'type'  => 'text',
                ),
                array(
                    'name' => esc_html__( 'Header Content', 'citytours' ),
                    'id'   => "_header_content",
                    'type' => 'wysiwyg',
                    'raw'  => true,
                    'options' => array(
                        'textarea_rows' => 4,
                    ),
                ),
            )
        );

        $meta_boxes[] = array(
            'id' => 'custom_css',
            'title' => esc_html__( 'Custom CSS', 'citytours' ),
            'pages' => array( 'post', 'page', 'tour', 'hotel', 'car' ),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name'  => esc_html__( 'Custom CSS', 'citytours' ),
                    'id'      => "_custom_css",
                    'desc'  => esc_html__( 'Enter custom css code here.', 'citytours' ),
                    'type'  => 'textarea',
                )
            )
        );

        global $wp_registered_sidebars;

        foreach($wp_registered_sidebars as $sidebar) {
            $sidebars[$sidebar['id']] = $sidebar['name'];
        }
        $sidebars['default'] = esc_html__('Default', 'citytours');

        $meta_boxes[] = array(
            'id' => 'ct-metabox-page-sidebar',
            'title' => esc_html__( 'Page layout', 'citytours' ),
            'pages' => array( 'post', 'page' ),
            'context' => 'side',
            'priority' => 'low',
            'fields' => array(
                // Sidebar option
                array(
                    'name' => esc_html__( 'Sidebar position:', 'citytours' ),
                    'id' => '_ct_sidebar_position',
                    'type' => 'radio',
                    'std' => 'default',
                    'desc' => esc_html__( 'If select "Default", the setting on Theme-Options panel will work.', 'citytours' ),
                    'options' => array(
                        'default' => esc_html__( 'Default', 'citytours' ),
                        'no' => esc_html__( 'No Sidebar', 'citytours' ),
                        'left' => esc_html__( 'Left', 'citytours' ),
                        'right' => esc_html__( 'Right', 'citytours' ),
                    )
                ),

                // Sidebar widget area
                array(
                    'name' => esc_html__( 'Select Sidebar:', 'citytours' ),
                    'id' => '_ct_sidebar_widget_area',
                    'type' => 'select',
                    'options' => $sidebars,
					'std' => 'default',
					'hidden' => array( '_ct_sidebar_position', '=', 'no' ),
                ),
            ),

        );

        return $meta_boxes;
    }
}

/*
 * rwmb metabox registration
 */
if ( ! function_exists( 'ct_theme_register_meta_boxes' ) ) {
    function ct_theme_register_meta_boxes( $meta_boxes ) {
        $meta_boxes = array_merge( $meta_boxes, ct_post_register_meta_boxes() );
        return $meta_boxes;
    }
}

/*
 * tour metabox enqueue script
 */
if ( ! function_exists( 'ct_metabox_admin_enqueue_scripts' ) ) {
    function ct_metabox_admin_enqueue_scripts() {
        $screen = get_current_screen();

        if ( 'post' != $screen->base || ! in_array( $screen->post_type, array( 'tour', 'hotel', 'car', 'page', 'post' ) ) ) 
            return;

        wp_enqueue_script( 'ct-meta-custom-js', CT_TEMPLATE_DIRECTORY_URI . '/js/admin/metabox-custom.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'rwmb', RWMB_JS_URL . 'script.js', array( 'jquery' ), RWMB_VER, true );
        wp_enqueue_script( 'rwmb-clone', RWMB_JS_URL . 'clone.js', array( 'jquery' ), RWMB_VER, true );
        wp_enqueue_style( 'ct-meta-custom-css', CT_TEMPLATE_DIRECTORY_URI . '/css/admin/metabox-custom.css' );
        RWMB_Date_Field::admin_enqueue_scripts();
    }
}

/*
 * tour metabox registration
 */
if ( ! function_exists( 'ct_tour_register_meta_boxes' ) ) {
    function ct_tour_register_meta_boxes() {
        $meta_boxes = array();

        $prefix = '_tour_';

        // Details for 'Default Tour Setting'.
        $meta_boxes[] = array(
            'id' => 'tour_default_details',
            'title' => esc_html__( 'Default Tour Settings', 'citytours' ),
            'pages' => array( 'tour' ),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name'  => esc_html__( 'Type', 'citytours' ),
                    'id'      => "{$prefix}type",
                    'desc'  => esc_html__( 'Select a tour type', 'citytours' ),
                    'placeholder'  => esc_html__( 'Select a tour type', 'citytours' ),
                    'type'  => 'taxonomy',
                    // 'multiple' => true,
                    'options' => array(
                        'taxonomy' => 'tour_type',
                        'type' => 'select_advanced',
                    ),
                ),
                array(
                    'name'  => esc_html__( 'Maximum People', 'citytours' ),
                    'id'      => "{$prefix}max_people",
                    'type'  => 'number',
                    'desc' => esc_html__( 'Please set maximum number of people in a day. You can leave this field empty to make it no limit.', 'citytours' ),
                ),
                array(
					'name'		=> esc_html__( 'Price Type', 'citytours' ),
					'id'		=> "{$prefix}price_type",
					'type'		=> 'select',
					'options'	=> array(
							'per_person'	=> esc_html__( 'Per Person', 'citytours' ),
							'per_group'		=> esc_html__( 'Per Group', 'citytours' ),
						),
					'default'       => 'per_person',
				),
				array(
					'name'  => esc_html__( 'Price', 'citytours' ),
                    'id'      => "{$prefix}price",
                    'type'  => 'text',
                ),
                array(
                    'name'  => esc_html__( 'Charge For Children', 'citytours' ),
                    'id'      => "{$prefix}charge_child",
                    'type'  => 'checkbox',
                    'desc' => esc_html__( 'Charge for children?', 'citytours' ),
                    'std'  => 0,
					'visible'	=> array( $prefix . 'price_type', '=', 'per_person' ),
                ),
                array(
                    'name'  => esc_html__( 'Price Per Child', 'citytours' ),
                    'id'      => "{$prefix}price_child",
                    'type'  => 'text',
					'visible'	=> array( $prefix . 'charge_child', true ),
                ),
                array(
                    'name'  => esc_html__( 'Tour Brief', 'citytours' ),
                    'id'      => "{$prefix}brief",
                    'desc'  => esc_html__( 'This is tour brief field and the value is shown on search result page and detail page .', 'citytours' ),
                    'type'  => 'textarea',
                ),
                array(
                    'name'  => esc_html__( 'Facilities', 'citytours' ),
                    'id'      => "{$prefix}facilities",
                    'desc'  => esc_html__( 'Select Facilities', 'citytours' ),
                    'type'  => 'taxonomy',
                    'placeholder' => esc_html__( 'Select Facilities', 'citytours' ),
                    'options' => array(
                        'taxonomy' => 'tour_facility',
                        'type' => 'checkbox_list',
                    ),
                ),
                array(
					'name'  => esc_html__( 'Phone', 'citytours' ),
					'id'      => "{$prefix}phone",
					'type'  => 'text',
				),
				array(
					'name'  => esc_html__( 'Email', 'citytours' ),
					'id'      => "{$prefix}email",
					'type'  => 'text',
				),
				array(
                    'name'  => esc_html__( 'Address', 'citytours' ),
                    'id'      => "{$prefix}address",
                    'type'  => 'text',
                ),
                array(
                    'name'        => esc_html__( 'Location', 'citytours' ),
                    'id'            => "{$prefix}loc",
                    'type'        => 'map',
                    'style'      => 'width: 500px; height: 300px',
                    'address_field' => "{$prefix}address",                   // Name of text field where address is entered. Can be list of text fields, separated by commas (for ex. city, state)
                ),
                array(
                    'name'  => esc_html__( 'Slider Content', 'citytours' ),
                    'id'      => "{$prefix}slider",
                    'desc'  => esc_html__( 'Please write slider shortcode here. For example [sliderpro id="1"] or [rev_slider concept]', 'citytours' ),
                    'type'  => 'text',
                ),
                array(
                    'name'  => esc_html__( 'Schedule Info', 'citytours' ),
                    'id'      => "{$prefix}schedule_info",
                    'desc'  => esc_html__( 'Please write schedule info here.', 'citytours' ),
                    'type'  => 'wysiwyg',
                ),
                array(
                    'name'  => esc_html__( 'Related Hotels and Tours', 'citytours' ),
                    'id'      => "{$prefix}related",
                    'desc'  => esc_html__( 'Select Hotels or Tours related to this Tour', 'citytours' ),
                    'placeholder'  => esc_html__( 'Please type a name of Hotel or Tours', 'citytours' ),
                    'type'  => 'post',
                    'post_type' => array( 'hotel', 'tour' ),
                    'field_type' => 'select_advanced',
                    'multiple' => true,
                ),
            )
        );

        // Tour Booking Type Setting
        $meta_boxes[] = array(
            'id'        => 'tour_booking_settings',
            'title'     => esc_html__( 'Tour Booking Type', 'citytours' ),
            'pages'     => array( 'tour' ),
            'context'   => 'normal',
            'priority'  => 'high',
            'fields'    => array(
                array(
                    'name'          => esc_html__( 'Tour Booking Type', 'citytours' ),
                    'id'            => "{$prefix}booking_type",
                    'type'          => 'select',
                    'options'       => array(
                            'default'   => esc_html__( 'Original Booking Type', 'citytours' ),
                            'inquiry'   => esc_html__( 'Inquiry Form', 'citytours' ),
                            'external'  => esc_html__( 'External/Affiliate Tour', 'citytours' ),
                            'empty'     => esc_html__( 'Empty Booking Form', 'citytours' )
                        ),
                    'desc'          => esc_html__( 'Please select Tour Booking Type.', 'citytours' ),
                    'default'       => 'default'
                )
            ),
        );

        // Details for 'Original Tour Booking'.
        $meta_boxes[] = array(
            'id' => 'tour_details',
            'title' => esc_html__( 'Original Booking Settings', 'citytours' ),
            'pages' => array( 'tour' ),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name' => esc_html__( 'Tour Repeatability', 'citytours' ),
                    'id'    => "{$prefix}repeated",
                    'desc' => esc_html__( 'If you set Repeated, this tour booking form will have date selection field.', 'citytours' ),
                    'type'  => 'radio',
                    'std'  => '1',
                    'options' => array(
                        '1' => esc_html__( 'Repeated', 'citytours' ),
                        '0' => esc_html__( 'Not Repeated', 'citytours' ),
                    ),
                ),
                array(
                    'name'  => esc_html__( 'Tour Date', 'citytours' ),
                    'id'      => "{$prefix}date",
                    'type'  => 'date',
                    'desc' => esc_html__( 'Please set tour date', 'citytours' ),
                ),
                array(
                    'name'  => esc_html__( 'Tour Start Date', 'citytours' ),
                    'id'      => "{$prefix}start_date",
                    'type'  => 'date',
                    'desc' => esc_html__( 'Please set tour start date', 'citytours' ),
                ),
                array(
                    'name'  => esc_html__( 'Tour End Date', 'citytours' ),
                    'id'      => "{$prefix}end_date",
                    'type'  => 'date',
                    'desc' => esc_html__( 'Please set tour end date', 'citytours' ),
                ),
                array(
                    'name'          => esc_html__( 'Available Times', 'citytours' ),
                    'id'            => "{$prefix}time",
                    'type'          => 'text',
                    'desc'          => esc_html__( 'Please add available times for tour. comma separated. You can leave it blank.', 'citytours' ),
                    'placeholder'   => esc_html__( 'Ex: 09:00 AM, 01:00 PM', 'citytours' ),
                ),
                array(
                    'name'      => esc_html__( 'Monday', 'citytours' ),
                    'id'        => "{$prefix}monday_available",
                    'desc'      => esc_html__( 'Please check if tour is available each Monday.', 'citytours' ),
                    'type'      => 'checkbox',
                    'std'       => 1,
                ),
                array(
                    'name'          => esc_html__( 'Monday Price', 'citytours' ),
                    'id'            => "{$prefix}monday_price",
                    'type'          => 'text',
                    'desc'          => esc_html__( 'If you leave it blank, "Price Per Person" field will be used.', 'citytours' ),
					'visible'		=> array( $prefix . 'monday_available', true ),
                ),
                array(
                    'name'      => esc_html__( 'Tuesday', 'citytours' ),
                    'id'        => "{$prefix}tuesday_available",
                    'desc'      => esc_html__( 'Please check if tour is available each Tuesday.', 'citytours' ),
                    'type'      => 'checkbox',
                    'std'       => 1,
                ),
                array(
                    'name'          => esc_html__( 'Tuesday Price', 'citytours' ),
                    'id'            => "{$prefix}tuesday_price",
                    'type'          => 'text',
                    'desc'          => esc_html__( 'If you leave it blank, "Price Per Person" field will be used.', 'citytours' ),
					'visible'		=> array( $prefix . 'tuesday_available', true ),
                ),
                array(
                    'name'      => esc_html__( 'Wednesday', 'citytours' ),
                    'id'        => "{$prefix}wednesday_available",
                    'desc'      => esc_html__( 'Please check if tour is available each Wednesday.', 'citytours' ),
                    'type'      => 'checkbox',
                    'std'       => 1,
                ),
                array(
                    'name'          => esc_html__( 'Wednesday Price', 'citytours' ),
                    'id'            => "{$prefix}wednesday_price",
                    'type'          => 'text',
                    'desc'          => esc_html__( 'If you leave it blank, "Price Per Person" field will be used.', 'citytours' ),
					'visible'		=> array( $prefix . 'wednesday_available', true ),
                ),
                array(
                    'name'      => esc_html__( 'Thursday', 'citytours' ),
                    'id'        => "{$prefix}thursday_available",
                    'desc'      => esc_html__( 'Please check if tour is available each Thursday.', 'citytours' ),
                    'type'      => 'checkbox',
                    'std'       => 1,
                ),
                array(
                    'name'          => esc_html__( 'Thursday Price', 'citytours' ),
                    'id'            => "{$prefix}thursday_price",
                    'type'          => 'text',
                    'desc'          => esc_html__( 'If you leave it blank, "Price Per Person" field will be used.', 'citytours' ),
					'visible'		=> array( $prefix . 'thursday_available', true ),
                ),
                array(
                    'name'      => esc_html__( 'Friday', 'citytours' ),
                    'id'        => "{$prefix}friday_available",
                    'desc'      => esc_html__( 'Please check if tour is available each Friday.', 'citytours' ),
                    'type'      => 'checkbox',
                    'std'       => 1,
                ),
                array(
                    'name'          => esc_html__( 'Friday Price', 'citytours' ),
                    'id'            => "{$prefix}friday_price",
                    'type'          => 'text',
                    'desc'          => esc_html__( 'If you leave it blank, "Price Per Person" field will be used.', 'citytours' ),
					'visible'		=> array( $prefix . 'friday_available', true ),
                ),
                array(
                    'name'      => esc_html__( 'Saturday', 'citytours' ),
                    'id'        => "{$prefix}saturday_available",
                    'desc'      => esc_html__( 'Please check if tour is available each Saturday.', 'citytours' ),
                    'type'      => 'checkbox',
                    'std'       => 1,
                ),
                array(
                    'name'          => esc_html__( 'Saturday Price', 'citytours' ),
                    'id'            => "{$prefix}saturday_price",
                    'type'          => 'text',
                    'desc'          => esc_html__( 'If you leave it blank, "Price Per Person" field will be used.', 'citytours' ),
					'visible'		=> array( $prefix . 'saturday_available', true ),
                ),
                array(
                    'name'      => esc_html__( 'Sunday', 'citytours' ),
                    'id'        => "{$prefix}sunday_available",
                    'desc'      => esc_html__( 'Please check if tour is available each Sunday.', 'citytours' ),
                    'type'      => 'checkbox',
                    'std'       => 1,
                    ),
                array(
                    'name'          => esc_html__( 'Sunday Price', 'citytours' ),
                    'id'            => "{$prefix}sunday_price",
                    'type'          => 'text',
                    'desc'          => esc_html__( 'If you leave it blank, "Price Per Person" field will be used.', 'citytours' ),
					'visible'		=> array( $prefix . 'sunday_available', true ),
                ),
                array(
                    'name'  => esc_html__( 'Security Deposit Amount(%)', 'citytours' ),
                    'id'      => "{$prefix}security_deposit",
                    'desc'  => esc_html__( 'Leave it blank if security deposit is not needed. And can insert value 100 if you want customers to pay whole amount of money while booking.', 'citytours' ),
                    'type'  => 'text',
                    'std'  => '100',
                ),
            )
        );

        // Inquiry Form
        $meta_boxes[] = array(
            'id' => 'inquiry_form',
            'title' => esc_html__( 'Inquiry Form Setting', 'citytours' ),
            'pages' => array( 'tour' ),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name'  => esc_html__( 'Inquiry Form', 'citytours' ),
                    'id'      => "{$prefix}inquiry_form",
                    'desc'  => esc_html__( 'Please write form shortcode here.', 'citytours' ),
                    'type'  => 'text',
                ),
            )
        );

        // External/Affiliate Link
        $meta_boxes[] = array(
            'id' => 'external_link',
            'title' => esc_html__( 'External/Affiliate Tour', 'citytours' ),
            'pages' => array( 'tour' ),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name'          => esc_html__( 'External Link:', 'citytours' ),
                    'id'            => "{$prefix}external_link",
                    'type'          => 'text',
                    'placeholder'   => esc_html__( 'Enter URL here', 'citytours' ),
                    'desc'          => esc_html__( 'Input external URL which you want to redirect customers.', 'citytours' )
                )
            )
        );

        $meta_boxes[] = array(
            'id' => 'is_fixed_sidebar',
            'title' => esc_html__( 'Sidebar Setting', 'citytours' ),
            'pages' => array( 'tour' ),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name'  => esc_html__( 'Fixed Sidebar', 'citytours' ),
                    'id'      => "{$prefix}fixed_sidebar",
                    'type'  => 'checkbox',
                    'desc' => esc_html__( 'Is fixed sidebar?', 'citytours' ),
                    'std'  => 0,
                ),
            )
        );

        //tour_settings
        $meta_boxes[] = array(
            'id' => 'tour_settings',
            'title' => __( 'Tour Settings', 'citytours' ),
            'pages' => array( 'tour' ),
            'context' => 'side',
            'priority' => 'default',
            'fields' => array(
                array(
                    'name' => __( 'Feature This Tour', 'citytours' ),
                    'id'    => "{$prefix}featured",
                    'desc' => __( 'Add this tour to featured list.', 'citytours' ),
                    'type' => 'checkbox',
                    'std' => array(),
                ),
                array(
                    'name' => __( 'Discount This Tour', 'citytours' ),
                    'id' => "{$prefix}hot",
                    'desc' => __( 'Add this tour to hot list.', 'citytours' ),
                    'type' => 'checkbox',
                    'std' => array(),
                ),
                array(
                    'name' => __( 'Discount Rate', 'citytours' ),
                    'id' => "{$prefix}discount_rate",
                    'desc' => __( '%', 'citytours' ),
                    'type' => 'number',
                    'std' => 0,
                ),
            )
        );

        $meta_boxes = apply_filters( 'ct_tour_register_meta_boxes', $meta_boxes );

        return $meta_boxes;
    }
}

/*
 * hotel metabox registration
 */
if ( ! function_exists( 'ct_hotel_register_meta_boxes' ) ) {
    function ct_hotel_register_meta_boxes() {
        $meta_boxes = array();

        $prefix = '_hotel_';

        // Details for 'Default Hotel Setting'.
        $meta_boxes[] = array(
            'id' => 'hotel_default_settings',
            'title' => esc_html__( 'Default Hotel Settings', 'citytours' ),
            'pages' => array( 'hotel' ),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name'          => esc_html__( 'District', 'citytours' ),
                    'id'            => "{$prefix}type",
                    'desc'          => esc_html__( 'Select district', 'citytours' ),
                    'placeholder'   => esc_html__( 'Select district', 'citytours' ),
                    'type'          => 'taxonomy',
                    'options'       => array(
                        'taxonomy'  => 'district',
                        'type'      => 'select_advanced',
                    ),
                ),
                array(
                    'name'  => esc_html__( 'Star rating', 'citytours' ),
                    'id'      => "{$prefix}star",
                    'type' => 'slider',
                    'suffix' => esc_html__( ' star', 'citytours' ),
                    'std'  => 0,
                    'js_options' => array(
                        'min'   => 0,
                        'max'   => 5,
                        'step'  => 1,
                    ),
                ),
                array(
                    'name'  => esc_html__( 'AVG/NIGHT Price', 'citytours' ),
                    'id'      => "{$prefix}price",
                    'type'  => 'text',
                ),
                array(
                    'name'  => esc_html__( 'Hotel Brief', 'citytours' ),
                    'id'      => "{$prefix}brief",
                    'desc'  => esc_html__( 'This is hotel brief field and the value is shown on search result page and detail page .', 'citytours' ),
                    'type'  => 'textarea',
                ),
                array(
                    'name'  => esc_html__( 'Facilities', 'citytours' ),
                    'id'      => "{$prefix}facilities",
                    'desc'  => esc_html__( 'Select Facilities', 'citytours' ),
                    'type'  => 'taxonomy',
                    'placeholder' => esc_html__( 'Select Facilities', 'citytours' ),
                    'options' => array(
                        'taxonomy' => 'hotel_facility',
                        'type' => 'checkbox_list',
                    ),
                ),
                array(
                    'name'  => esc_html__( 'Address', 'citytours' ),
                    'id'      => "{$prefix}address",
                    'type'  => 'text',
                ),
                array(
                    'name'        => esc_html__( 'Location', 'citytours' ),
                    'id'            => "{$prefix}loc",
                    'type'        => 'map',
                    'style'      => 'width: 500px; height: 300px',
                    'address_field' => "{$prefix}address",                   // Name of text field where address is entered. Can be list of text fields, separated by commas (for ex. city, state)
                ),
                array(
                    'name'  => esc_html__( 'Email', 'citytours' ),
                    'id'      => "{$prefix}email",
                    'type'  => 'text',
                ),
                array(
                    'name'  => esc_html__( 'Phone', 'citytours' ),
                    'id'      => "{$prefix}phone",
                    'type'  => 'text',
                ),
                array(
                    'name'  => esc_html__( 'Slider Content', 'citytours' ),
                    'id'      => "{$prefix}slider",
                    'desc'  => esc_html__( 'Please write slider shortcode here.', 'citytours' ),
                    'type'  => 'text',
                ),
                array(
                    'name'  => esc_html__( 'Related Hotels and Tours', 'citytours' ),
                    'id'      => "{$prefix}related",
                    'desc'  => esc_html__( 'Select Hotels or Tours related to this Hotel', 'citytours' ),
                    'placeholder'  => esc_html__( 'Please type a name of Hotel or Tours', 'citytours' ),
                    'type'  => 'post',
                    'post_type' => array( 'hotel', 'tour' ),
                    'field_type' => 'select_advanced',
                    'multiple' => true,
                ),
            )
        );
        
        $meta_boxes[] = array(
            'id' => 'is_fixed_sidebar',
            'title' => esc_html__( 'Sidebar Setting', 'citytours' ),
            'pages' => array( 'hotel' ),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name'  => esc_html__( 'Fixed Sidebar', 'citytours' ),
                    'id'      => "{$prefix}fixed_sidebar",
                    'type'  => 'checkbox',
                    'desc' => esc_html__( 'Is fixed sidebar?', 'citytours' ),
                    'std'  => 0,
                ),
            )
        );
        
        // Hotel Booking Type Settings
        $meta_boxes[] = array(
            'id'        => 'hotel_booking_settings',
            'title'     => esc_html__( 'Hotel Booking Type', 'citytours' ),
            'pages'     => array( 'hotel' ),
            'context'   => 'normal',
            'priority'  => 'high',
            'fields'    => array(
                array(
                    'name'          => esc_html__( 'Hotel Booking Type', 'citytours' ),
                    'id'            => "{$prefix}booking_type",
                    'type'          => 'select',
                    'options'       => array(
                            'default'   => esc_html__( 'Original Booking Type', 'citytours' ),
                            'inquiry'   => esc_html__( 'Inquiry Form', 'citytours' ),
                            'external'  => esc_html__( 'External/Affiliate Hotel', 'citytours' )
                        ),
                    'desc'          => esc_html__( 'Please select Hotel Booking Type.', 'citytours' ),
                    'default'       => 'default'
                )
            ),
        );

        // Details for 'Original Hotel Booking Settings'.
        $meta_boxes[] = array(
            'id' => 'hotel_details',
            'title' => esc_html__( 'Original Booking Settings', 'citytours' ),
            'pages' => array( 'hotel' ),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name'  => esc_html__( 'Minimum Stay Info', 'citytours' ),
                    'id'      => "{$prefix}minimum_stay",
                    'desc'  => esc_html__( 'Leave it blank if this hotel does not have minimum stay', 'citytours' ),
                    'type'  => 'number',
                    'suffix'=> 'Nights'
                ),
                array(
                    'name'  => esc_html__( 'Security Deposit Amount(%)', 'citytours' ),
                    'id'      => "{$prefix}security_deposit",
                    'desc'  => esc_html__( 'Leave it blank if security deposit is not needed. And can insert value 100 if you want customers to pay whole amount of money while booking.', 'citytours' ),
                    'type'  => 'text',
                    'std'  => '100',
                ),
            )
        );

        // Inquiry Form
        $meta_boxes[] = array(
            'id' => 'inquiry_form',
            'title' => esc_html__( 'Inquiry Form Setting', 'citytours' ),
            'pages' => array( 'hotel' ),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name'  => esc_html__( 'Inquiry Form', 'citytours' ),
                    'id'      => "{$prefix}inquiry_form",
                    'desc'  => esc_html__( 'Please write form shortcode here.', 'citytours' ),
                    'type'  => 'text',
                ),
            )
        );

        // External/Affiliate Link
        $meta_boxes[] = array(
            'id' => 'external_link',
            'title' => esc_html__( 'External/Affiliate Tour', 'citytours' ),
            'pages' => array( 'hotel' ),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name'          => esc_html__( 'External Link:', 'citytours' ),
                    'id'            => "{$prefix}external_link",
                    'type'          => 'text',
                    'placeholder'   => esc_html__( 'Enter URL here', 'citytours' ),
                    'desc'          => esc_html__( 'Input external URL which you want to redirect customers.', 'citytours' )
                )
            )
        );

        //hotel_settings
        $meta_boxes[] = array(
            'id' => 'hotel_settings',
            'title' => __( 'Hotel Settings', 'citytours' ),
            'pages' => array( 'hotel' ),
            'context' => 'side',
            'priority' => 'default',
            'fields' => array(
                array(
                    'name' => __( 'Feature This Hotel', 'citytours' ),
                    'id'    => "{$prefix}featured",
                    'desc' => __( 'Add this hotel to featured list.', 'citytours' ),
                    'type' => 'checkbox',
                    'std' => array(),
                ),
                array(
                    'name' => __( 'Discount This Hotel', 'citytours' ),
                    'id' => "{$prefix}hot",
                    'desc' => __( 'Add this hotel to hot list.', 'citytours' ),
                    'type' => 'checkbox',
                    'std' => array(),
                ),
                array(
                    'name' => __( 'Discount Rate', 'citytours' ),
                    'id' => "{$prefix}discount_rate",
                    'desc' => __( '%', 'citytours' ),
                    'type' => 'number',
                    'std' => 0,
                ),
            )
        );

        $prefix = '_room_';
        // Room details
        $meta_boxes[] = array(
            'id' => 'room_details',
            'title' => esc_html__( 'Details', 'citytours' ),
            'pages' => array( 'room_type' ),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name'  => esc_html__( 'hotel', 'citytours' ),
                    'id'      => "{$prefix}hotel_id",
                    'type'  => 'post',
                    'std' => isset($_GET['hotel_id']) ? sanitize_text_field( $_GET['hotel_id'] ) : '',
                    'post_type' => 'hotel',
                ),
                array(
                    'name'  => esc_html__( 'Max Adults', 'citytours' ),
                    'id'    => "{$prefix}max_adults",
                    'desc'  => esc_html__( 'How many adults are allowed in the room?', 'citytours' ),
                    'type' => 'number',
                    'std' => 1
                ),
                array(
                    'name'  => esc_html__( 'Max Children', 'citytours' ),
                    'id'    => "{$prefix}max_kids",
                    'desc'  => esc_html__( 'How many children are allowed in the room?', 'citytours' ),
                    'type' => 'number',
                    'std' => 0
                ),
                array(
                    'name'  => esc_html__( 'Facilities', 'citytours' ),
                    'id'      => "{$prefix}facilities",
                    'desc'  => esc_html__( 'Select Facilities', 'citytours' ),
                    'type'  => 'taxonomy',
                    'placeholder' => esc_html__( 'Select Facilities', 'citytours' ),
                    'options' => array(
                        'taxonomy' => 'hotel_facility',
                        'type' => 'checkbox_list',
                    ),
                ),
                array(
                    'name'           => esc_html__( 'Gallery Images', 'citytours' ),
                    'id'                => "_gallery_imgs",
                    'type'           => 'image_advanced',
                    'max_file_uploads' => 50,
                ),
            )
        );

        $meta_boxes = apply_filters( 'ct_hotel_register_meta_boxes', $meta_boxes );

        return $meta_boxes;
    }
}

/*
 * car metabox registration
 */
if ( ! function_exists( 'ct_car_register_meta_boxes' ) ) {
    function ct_car_register_meta_boxes() {
        $meta_boxes = array();

        $prefix = '_car_';

        // Details for 'Default Car Setting'.
        $meta_boxes[] = array(
            'id' => 'car_default_details',
            'title' => esc_html__( 'Default Car Settings', 'citytours' ),
            'pages' => array( 'car' ),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name'  => esc_html__( 'Type', 'citytours' ),
                    'id'      => "{$prefix}type",
                    'desc'  => esc_html__( 'Select a car transfer type', 'citytours' ),
                    'placeholder'  => esc_html__( 'Select a car transfer type', 'citytours' ),
                    'type'  => 'taxonomy',
                    // 'multiple' => true,
                    'options' => array(
                        'taxonomy' => 'car_type',
                        'type' => 'select_advanced',
                    ),
                ),
                array(
                    'name'  => esc_html__( 'Price Per Person', 'citytours' ),
                    'id'      => "{$prefix}price",
                    'type'  => 'text',
                ),
                array(
                    'name'  => esc_html__( 'Charge For Children', 'citytours' ),
                    'id'      => "{$prefix}charge_child",
                    'type'  => 'checkbox',
                    'desc' => esc_html__( 'Charge for children?', 'citytours' ),
                    'std'  => 0,
                ),
                array(
                    'name'  => esc_html__( 'Price Per Child', 'citytours' ),
                    'id'      => "{$prefix}price_child",
                    'type'  => 'text',
					'visible'	=> array( $prefix . 'charge_child', true ),
                ),
                array(
                    'name'  => esc_html__( 'Car Brief', 'citytours' ),
                    'id'      => "{$prefix}brief",
                    'desc'  => esc_html__( 'This is car brief field and the value is shown on search result page and detail page .', 'citytours' ),
                    'type'  => 'textarea',
                ),
                array(
                    'name'  => esc_html__( 'Facilities', 'citytours' ),
                    'id'      => "{$prefix}facilities",
                    'desc'  => esc_html__( 'Select Facilities', 'citytours' ),
                    'type'  => 'taxonomy',
                    'placeholder' => esc_html__( 'Select Facilities', 'citytours' ),
                    'options' => array(
                        'taxonomy' => 'car_facility',
                        'type' => 'checkbox_list',
                    ),
                ),
                array(
                    'name'  => esc_html__( 'Address', 'citytours' ),
                    'id'      => "{$prefix}address",
                    'type'  => 'text',
                ),
                array(
                    'id' => "{$prefix}pickup_location",
                    'type' => 'text',
                    'name' => esc_html__( 'Pick up location', 'citytours' ),
                    'clone' => true,
                ),
                array(
                    'id' => "{$prefix}dropoff_location",
                    'type' => 'text',
                    'name' => esc_html__( 'Drop off location', 'citytours' ),
                    'clone' => true,
                ),
                array(
                    'name'  => esc_html__( 'Slider Content', 'citytours' ),
                    'id'      => "{$prefix}slider",
                    'desc'  => esc_html__( 'Please write slider shortcode here. For example [sliderpro id="1"] or [rev_slider concept]', 'citytours' ),
                    'type'  => 'text',
                ),
            )
        );

        // Car Booking Type Setting
        $meta_boxes[] = array(
            'id'        => 'car_booking_settings',
            'title'     => esc_html__( 'Car Booking Type', 'citytours' ),
            'pages'     => array( 'car' ),
            'context'   => 'normal',
            'priority'  => 'high',
            'fields'    => array(
                array(
                    'name'          => esc_html__( 'Car Booking Type', 'citytours' ),
                    'id'            => "{$prefix}booking_type",
                    'type'          => 'select',
                    'options'       => array(
                            'default'   => esc_html__( 'Original Booking Type', 'citytours' ),
                            'inquiry'   => esc_html__( 'Inquiry Form', 'citytours' ),
                            'external'  => esc_html__( 'External/Affiliate Car', 'citytours' ),
                            'empty'     => esc_html__( 'Empty Booking Form', 'citytours' )
                        ),
                    'desc'          => esc_html__( 'Please select Car Booking Type.', 'citytours' ),
                    'default'       => 'default'
                )
            ),
        );

        // Details for 'Original Car Booking'.
        $meta_boxes[] = array(
            'id' => 'car_details',
            'title' => esc_html__( 'Original Booking Settings', 'citytours' ),
            'pages' => array( 'car' ),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name' => esc_html__( 'Car Repeatability', 'citytours' ),
                    'id'    => "{$prefix}repeated",
                    'desc' => esc_html__( 'If you set Repeated, this car booking form will have date selection field.', 'citytours' ),
                    'type'  => 'radio',
                    'std'  => '1',
                    'options' => array(
                        '1' => esc_html__( 'Repeated', 'citytours' ),
                        '0' => esc_html__( 'Not Repeated', 'citytours' ),
                    ),
                ),
                array(
                    'name'  => esc_html__( 'Car Date', 'citytours' ),
                    'id'      => "{$prefix}date",
                    'type'  => 'date',
                    'desc' => esc_html__( 'Please set car date', 'citytours' ),
                ),
                array(
                    'name'  => esc_html__( 'Car Start Date', 'citytours' ),
                    'id'      => "{$prefix}start_date",
                    'type'  => 'date',
                    'desc' => esc_html__( 'Please set car start date', 'citytours' ),
                ),
                array(
                    'name'  => esc_html__( 'Car End Date', 'citytours' ),
                    'id'      => "{$prefix}end_date",
                    'type'  => 'date',
                    'desc' => esc_html__( 'Please set car end date', 'citytours' ),
                ),
                array(
                    'name'      => esc_html__( 'Monday', 'citytours' ),
                    'id'        => "{$prefix}monday_available",
                    'desc'      => esc_html__( 'Please check if car is available each Monday.', 'citytours' ),
                    'type'      => 'checkbox',
                    'std'       => 1,
                ),
                array(
                    'name'          => esc_html__( 'Monday Price', 'citytours' ),
                    'id'            => "{$prefix}monday_price",
                    'type'          => 'text',
                    'desc'          => esc_html__( 'If you leave it blank, "Price Per Person" field will be used.', 'citytours' ),
                ),
                array(
                    'name'      => esc_html__( 'Tuesday', 'citytours' ),
                    'id'        => "{$prefix}tuesday_available",
                    'desc'      => esc_html__( 'Please check if car is available each Tuesday.', 'citytours' ),
                    'type'      => 'checkbox',
                    'std'       => 1,
                ),
                array(
                    'name'          => esc_html__( 'Tuesday Price', 'citytours' ),
                    'id'            => "{$prefix}tuesday_price",
                    'type'          => 'text',
                    'desc'          => esc_html__( 'If you leave it blank, "Price Per Person" field will be used.', 'citytours' ),
                ),
                array(
                    'name'      => esc_html__( 'Wednesday', 'citytours' ),
                    'id'        => "{$prefix}wednesday_available",
                    'desc'      => esc_html__( 'Please check if car is available each Wednesday.', 'citytours' ),
                    'type'      => 'checkbox',
                    'std'       => 1,
                ),
                array(
                    'name'          => esc_html__( 'Wednesday Price', 'citytours' ),
                    'id'            => "{$prefix}wednesday_price",
                    'type'          => 'text',
                    'desc'          => esc_html__( 'If you leave it blank, "Price Per Person" field will be used.', 'citytours' ),
                ),
                array(
                    'name'      => esc_html__( 'Thursday', 'citytours' ),
                    'id'        => "{$prefix}thursday_available",
                    'desc'      => esc_html__( 'Please check if car is available each Thursday.', 'citytours' ),
                    'type'      => 'checkbox',
                    'std'       => 1,
                ),
                array(
                    'name'          => esc_html__( 'Thursday Price', 'citytours' ),
                    'id'            => "{$prefix}thursday_price",
                    'type'          => 'text',
                    'desc'          => esc_html__( 'If you leave it blank, "Price Per Person" field will be used.', 'citytours' ),
                ),
                array(
                    'name'      => esc_html__( 'Friday', 'citytours' ),
                    'id'        => "{$prefix}friday_available",
                    'desc'      => esc_html__( 'Please check if car is available each Friday.', 'citytours' ),
                    'type'      => 'checkbox',
                    'std'       => 1,
                ),
                array(
                    'name'          => esc_html__( 'Friday Price', 'citytours' ),
                    'id'            => "{$prefix}friday_price",
                    'type'          => 'text',
                    'desc'          => esc_html__( 'If you leave it blank, "Price Per Person" field will be used.', 'citytours' ),
                ),
                array(
                    'name'      => esc_html__( 'Saturday', 'citytours' ),
                    'id'        => "{$prefix}saturday_available",
                    'desc'      => esc_html__( 'Please check if car is available each Saturday.', 'citytours' ),
                    'type'      => 'checkbox',
                    'std'       => 1,
                ),
                array(
                    'name'          => esc_html__( 'Saturday Price', 'citytours' ),
                    'id'            => "{$prefix}saturday_price",
                    'type'          => 'text',
                    'desc'          => esc_html__( 'If you leave it blank, "Price Per Person" field will be used.', 'citytours' ),
                ),
                array(
                    'name'      => esc_html__( 'Sunday', 'citytours' ),
                    'id'        => "{$prefix}sunday_available",
                    'desc'      => esc_html__( 'Please check if car is available each Sunday.', 'citytours' ),
                    'type'      => 'checkbox',
                    'std'       => 1,
                ),
                array(
                    'name'          => esc_html__( 'Sunday Price', 'citytours' ),
                    'id'            => "{$prefix}sunday_price",
                    'type'          => 'text',
                    'desc'          => esc_html__( 'If you leave it blank, "Price Per Person" field will be used.', 'citytours' ),
                ),
                array(
                    'name'  => esc_html__( 'Security Deposit Amount(%)', 'citytours' ),
                    'id'      => "{$prefix}security_deposit",
                    'desc'  => esc_html__( 'Leave it blank if security deposit is not needed. And can insert value 100 if you want customers to pay whole amount of money while booking.', 'citytours' ),
                    'type'  => 'text',
                    'std'  => '100',
                ),
            )
        );

        // Inquiry Form
        $meta_boxes[] = array(
            'id' => 'inquiry_form',
            'title' => esc_html__( 'Inquiry Form Setting', 'citytours' ),
            'pages' => array( 'car' ),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name'  => esc_html__( 'Inquiry Form', 'citytours' ),
                    'id'      => "{$prefix}inquiry_form",
                    'desc'  => esc_html__( 'Please write form shortcode here.', 'citytours' ),
                    'type'  => 'text',
                ),
            )
        );

        // External/Affiliate Link
        $meta_boxes[] = array(
            'id' => 'external_link',
            'title' => esc_html__( 'External/Affiliate Car', 'citytours' ),
            'pages' => array( 'car' ),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name'          => esc_html__( 'External Link:', 'citytours' ),
                    'id'            => "{$prefix}external_link",
                    'type'          => 'text',
                    'placeholder'   => esc_html__( 'Enter URL here', 'citytours' ),
                    'desc'          => esc_html__( 'Input external URL which you want to redirect customers.', 'citytours' )
                )
            )
        );

        $meta_boxes[] = array(
            'id' => 'is_fixed_sidebar',
            'title' => esc_html__( 'Sidebar Setting', 'citytours' ),
            'pages' => array( 'car' ),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'name'  => esc_html__( 'Fixed Sidebar', 'citytours' ),
                    'id'      => "{$prefix}fixed_sidebar",
                    'type'  => 'checkbox',
                    'desc' => esc_html__( 'Is fixed sidebar?', 'citytours' ),
                    'std'  => 0,
                ),
            )
        );

        //car_settings
        $meta_boxes[] = array(
            'id' => 'car_settings',
            'title' => __( 'Car Settings', 'citytours' ),
            'pages' => array( 'car' ),
            'context' => 'side',
            'priority' => 'default',
            'fields' => array(
                array(
                    'name' => __( 'Feature This Car', 'citytours' ),
                    'id'    => "{$prefix}featured",
                    'desc' => __( 'Add this car to featured list.', 'citytours' ),
                    'type' => 'checkbox',
                    'std' => array(),
                ),
                array(
                    'name' => __( 'Discount This Car', 'citytours' ),
                    'id' => "{$prefix}hot",
                    'desc' => __( 'Add this car to hot list.', 'citytours' ),
                    'type' => 'checkbox',
                    'std' => array(),
                ),
                array(
                    'name' => __( 'Discount Rate', 'citytours' ),
                    'id' => "{$prefix}discount_rate",
                    'desc' => __( '%', 'citytours' ),
                    'type' => 'number',
                    'std' => 0,
                ),
            )
        );

        $meta_boxes = apply_filters( 'ct_car_register_meta_boxes', $meta_boxes );

        return $meta_boxes;
    }
}

/*
 * rwmb metabox registration
 */
if ( ! function_exists( 'ct_plugin_register_meta_boxes' ) ) {
    function ct_plugin_register_meta_boxes( $meta_boxes ) {
        global $ct_options;

        //tour custom post type
        if ( ct_is_tour_enabled() ) :
            $tour_meta_boxes = ct_tour_register_meta_boxes();
            $meta_boxes = array_merge( $meta_boxes, $tour_meta_boxes );
        endif;

        if ( ct_is_hotel_enabled() ) :
            $hotel_meta_boxes = ct_hotel_register_meta_boxes();
            $meta_boxes = array_merge( $meta_boxes, $hotel_meta_boxes );
        endif;

        if ( ct_is_car_enabled() ) :
            $car_meta_boxes = ct_car_register_meta_boxes();
            $meta_boxes = array_merge( $meta_boxes, $car_meta_boxes );
        endif;

        return $meta_boxes;
    }
}