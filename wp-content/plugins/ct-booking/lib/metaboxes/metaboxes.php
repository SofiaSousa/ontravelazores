<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'add_meta_boxes', 'ct_hotel_rooms_meta_box' );
add_action( 'add_meta_boxes', 'ct_add_services_meta_box' );
add_action( 'add_meta_boxes', 'ct_add_popup_meta_box' );
add_action( 'save_post', 'ct_save_schedule_data' );
add_action( 'save_post', 'ct_save_add_service_data' );
add_action( 'save_post', 'ct_save_popup_data' );
// add_action( "add_meta_boxes", "ct_tour_schedule_meta_box" );

/*
 * room types meta box HTML on Hotel page
 */
if ( ! function_exists( 'ct_hotel_rooms_meta_box_html' ) ) {
    function ct_hotel_rooms_meta_box_html( $post )
    {
        if ( isset( $_GET['post'] ) ) {
            $hotel_id = $_GET['post'];
            $args = array(
                'post_type' => 'room_type',
                'meta_query' => array(
                    array(
                        'key' => '_room_hotel_id',
                        'value' => array( sanitize_text_field( $_GET['post'] ) ),
                    )
                ),
                'suppress_filters' => 0,
            );
            $room_types = get_posts( $args );
            if ( ! empty( $room_types ) ) {
                echo '<ul>';
                foreach ($room_types as $room_type) {
                    echo '<li>' . esc_html( get_the_title($room_type->ID) ) . '  <a href="' . esc_url( get_edit_post_link($room_type->ID) ) . '">edit</a></li>';
                }
                echo '</ul>';
            } else {
                echo 'No Room Types in This Hotel. <br />';
            }
            echo '<a href="' . esc_url( admin_url('post-new.php?post_type=room_type&hotel_id=' . $hotel_id) ) . '">Add New Room Type</a>';
            //wp_reset_postdata();
        } else { //in case of new
            echo 'No Room Types in This Hotel. <br />';
            echo '<a href="' . esc_url( admin_url('post-new.php?post_type=room_type') ) . '">Add New Room Type</a>';
        }
    }
}

/*
 * Register room types meta box on hotel page
 */
if ( ! function_exists( 'ct_hotel_rooms_meta_box' ) ) {
    function ct_hotel_rooms_meta_box( $post )
    {
        add_meta_box( 
            'ct_hotel_rooms_meta_box', // this is HTML id
            'Room Types in This Hotel', 
            'ct_hotel_rooms_meta_box_html', // the callback function
            'hotel', // register on post type = page
            'side', // 
            'default'
        );
    }
}

/*
 * Register schedule meta box on tour page
 */
if ( ! function_exists( 'ct_add_popup_meta_box' ) ) {
    function ct_add_popup_meta_box( $post )
    {
        $screens = array( 'tour', 'hotel', 'page', 'post', 'car' );

        foreach ( $screens as $screen ) {
            add_meta_box( 
                'ct_add_popup_meta_box',
                __('Pop-up Informations', 'citytours'),
                'ct_add_popup_meta_box_html',
                $screen
            );
        }
    }
}

/*
 * Add schedule meta box on tour page
 */
if ( ! function_exists( 'ct_add_popup_meta_box_html' ) ) {
    function ct_add_popup_meta_box_html( $post ) {
        global $wpdb;

        $post_id = $post->ID;
        $popup_infos = get_post_meta( $post_id, 'popup_infos', true );

        wp_nonce_field( 'ct_popup_infos', 'ct_popup_infos_nonce' );

        echo '<div class="rwmb-clone-wrapper">';
        echo '<div class="rwmb-input">';
        
        if ( empty( $popup_infos ) ) {
            echo '<div class="rwmb-clone">
                    <div class="rwmb-field rwmb-select-wrapper">
                        <div class="rwmb-label">
                            <label>Icon type</label>                            
                        </div>
                        <div class="rwmb-clone-input">
                            <select name="popup_icon_type[0]" class="rwmb-text">
                                <option value="class" selected="selected">Class</option>
                                <option value="image">Image</option>
                            </select>
                        </div>
                    </div>
                    <div class="rwmb-field rwmb-text-wrapper">
                        <div class="rwmb-label">
                            <label>Icon(use icon class or image url)</label>                            
                        </div>
                        <div class="rwmb-clone-input">
                            <input type="text" class="rwmb-text large-text" name="popup_icon[0]">
                        </div>
                    </div>
                    <div class="rwmb-field rwmb-text-wrapper">
                        <div class="rwmb-label">
                            <label>Title(use html)</label>                            
                        </div>
                        <div class="rwmb-clone-input">
                            <input type="text" class="rwmb-text large-text" name="popup_title[0]">
                        </div>
                    </div>
                    <div class="rwmb-field rwmb-text-wrapper">
                        <div class="rwmb-label">
                            <label>Content(use html)</label>                            
                        </div>
                        <div class="rwmb-clone-input">
                            <textarea class="rwmb-textarea large-text" name="popup_content[0]" ></textarea>
                        </div>
                    </div>
                    <div class="rwmb-field rwmb-text-wrapper">
                        <div class="rwmb-label">
                            <label>Delay time before showing(ms)</label>                            
                        </div>
                        <div class="rwmb-clone-input">
                            <input type="text" class="rwmb-text" name="popup_delay_time[0]">
                        </div>
                    </div>
                    <a href="#" class="rwmb-button button remove-clone" style="display: none;">-</a>
                  </div>';
        } else {
            foreach ( $popup_infos as $key=>$popup_info ) {
                if ( !empty( $popup_info['popup_icon_type'] ) && $popup_info['popup_icon_type'] == "image" ) {
                    $image_selected = 'selected';
                    $class_selected = '';
                } else {
                    $image_selected = '';
                    $class_selected = 'selected';
                }

                echo '<div class="rwmb-clone">
                        <div class="rwmb-field rwmb-select-wrapper">
                            <div class="rwmb-label">
                                <label>Icon type</label>                            
                            </div>
                            <div class="rwmb-clone-input">
                                <select name="popup_icon_type[' . $key . ']" class="rwmb-text">
                                    <option value="class" ' . $class_selected . '>Class</option>
                                    <option value="image" ' . $image_selected . '>Image</option>
                                </select>
                            </div>
                        </div>
                        <div class="rwmb-field rwmb-text-wrapper">
                            <div class="rwmb-label">
                                <label>Icon(use icon class or image url)</label>                            
                            </div>
                            <div class="rwmb-clone-input">
                                <input type="text" class="rwmb-text large-text" name="popup_icon[' . $key . ']" value="' . $popup_info['popup_icon'] . '">
                            </div>
                        </div>
                        <div class="rwmb-field rwmb-text-wrapper">
                            <div class="rwmb-label">
                                <label>Title(use html)</label>                            
                            </div>
                            <div class="rwmb-clone-input">
                                <input type="text" class="rwmb-text large-text" name="popup_title[' . $key . ']" value="' . $popup_info['popup_title'] . '">
                            </div>
                        </div>
                        <div class="rwmb-field rwmb-text-wrapper">
                            <div class="rwmb-label">
                                <label>Content(use html)</label>                            
                            </div>
                            <div class="rwmb-clone-input">
                                <textarea class="rwmb-textarea large-text" name="popup_content[' . $key . ']" >' . $popup_info['popup_content'] . '</textarea>
                            </div>
                        </div>
                        <div class="rwmb-field rwmb-text-wrapper">
                            <div class="rwmb-label">
                                <label>Delay time before showing(ms)</label>                            
                            </div>
                            <div class="rwmb-clone-input">
                                <input type="text" class="rwmb-text" name="popup_delay_time[' . $key . ']" value="' . $popup_info['popup_delay_time'] . '">
                            </div>
                        </div>
                        <a href="#" class="rwmb-button button remove-clone" style="display: none;">-</a>
                      </div>';                
            }
        }

        echo '<a href="#" class="rwmb-button button-primary add-clone">+</a>
        </div></div>';
    }
}

/*
 * rwmb metabox save action
 */
if ( ! function_exists( 'ct_save_popup_data' ) ) {
    function ct_save_popup_data( $post_id ) {
        if ( ! isset( $_POST['ct_popup_infos_nonce'] ) ) {
            return $post_id;
        }

        $nonce = $_POST['ct_popup_infos_nonce'];
        if ( ! wp_verify_nonce( $nonce, 'ct_popup_infos' ) ) {
            return $post_id;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        $popup_icon_types = $_POST['popup_icon_type'];
        $popup_icons = $_POST['popup_icon'];
        $popup_titles = $_POST['popup_title'];
        $popup_contents = $_POST['popup_content'];
        $popup_delay_times = $_POST['popup_delay_time'];

        // Check the user's permissions.
        if ( current_user_can( 'edit_post', $post_id ) ) {
            
            if( empty( $popup_icons ) ) return;

            $popup_infos = array();

            foreach ( $popup_icons as $index => $popup_icon ) {
                if ( empty( $popup_titles[$index] ) && empty( $popup_contents[$index] ) ) {
                    continue;
                }

                $popup_infos[] = array(
                    'popup_icon_type'   => $popup_icon_types[$index],
                    'popup_icon'        => $popup_icons[$index], 
                    'popup_title'       => $popup_titles[$index], 
                    'popup_content'      => $popup_contents[$index], 
                    'popup_delay_time'  => $popup_delay_times[$index]
                );
            }

            if ( ! add_post_meta( $post_id, 'popup_infos', $popup_infos, true ) ) { 
               update_post_meta( $post_id, 'popup_infos', $popup_infos );
            }
        }
    }
}

/*
 * Register schedule meta box on tour page
 */
if ( ! function_exists( 'ct_tour_schedule_meta_box' ) ) {
    function ct_tour_schedule_meta_box( $post )
    {
        add_meta_box( 
            'ct_tour_schedule_meta_box',
            'Schedules', 
            'ct_tour_schedule_meta_box_html',
            'tour'
        );
    }
}

/*
 * Register service meta box on tour page
 */
if ( ! function_exists( 'ct_add_services_meta_box' ) ) {
    function ct_add_services_meta_box( $post )
    {
        $screens = array( 'tour', 'hotel', 'car' );

        foreach ( $screens as $screen ) {
            add_meta_box( 
                'ct_add_services_meta_box',
                __('Additional Services', 'citytours'), 
                'ct_add_services_meta_box_html',
                $screen
            );
        }
    }
}

/*
 * Add service meta box on tour page
 */
if ( ! function_exists( 'ct_add_services_meta_box_html' ) ) {
    function ct_add_services_meta_box_html( $post ) {
        global $wpdb;

        $post_id = $post->ID;
        $services = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . CT_ADD_SERVICES_TABLE . ' WHERE post_id=%d', $post_id ) );

        wp_nonce_field( 'ct_services', 'ct_services_nonce' );

        echo '<div class="rwmb-clone-wrapper">';
        echo '<div class="rwmb-input">';

        if ( empty( $services ) ) {
            echo '<div class="rwmb-clone">
                    <div class="rwmb-field rwmb-text-wrapper">
                        <div class="rwmb-label">
                            <label>Title</label>                            
                        </div>
                        <div class="rwmb-clone-input">
                            <input type="text" class="rwmb-text" name="service_title[0]">
                        </div>
                    </div>
                    <div class="rwmb-field rwmb-text-wrapper">
                        <div class="rwmb-label">
                            <label>Price</label>                            
                        </div>
                        <div class="rwmb-clone-input">
                            <input type="text" class="rwmb-text" name="service_price[0]">
                        </div>
                    </div>
                    <div class="rwmb-field rwmb-text-wrapper">
                        <div class="rwmb-label">
                            <label>Icon Class</label>                            
                        </div>
                        <div class="rwmb-clone-input">
                            <input type="text" class="rwmb-text" name="service_icon_class[0]" >
                        </div>
                    </div>
                    
                    <a href="#" class="rwmb-button button remove-clone" style="display: none;">-</a>
                  </div>';
        } else {
            foreach ( $services as $key=>$service ) {
                echo '<div class="rwmb-clone">
                    <div class="rwmb-field rwmb-text-wrapper">
                        <div class="rwmb-label">
                            <label>Title</label>                            
                        </div>
                        <div class="rwmb-clone-input">
                            <input type="hidden" class="rwmb-text" name="service_id[' . $key . ']" value="' . esc_html( $service->id ) . '"><input type="text" class="rwmb-text" name="service_title[' . $key . ']" value="' . esc_html( $service->title ) . '">
                        </div>
                    </div>
                    <div class="rwmb-field rwmb-text-wrapper">
                        <div class="rwmb-label">
                            <label>Price</label>                            
                        </div>
                        <div class="rwmb-clone-input">
                            <input type="text" class="rwmb-text" name="service_price[' . $key . ']"  value="' . esc_html( $service->price ) . '">
                        </div>
                    </div>
                    <div class="rwmb-field rwmb-text-wrapper">
                        <div class="rwmb-label">
                            <label>Icon Class</label>                            
                        </div>
                        <div class="rwmb-clone-input">
                            <input type="text" class="rwmb-text" name="service_icon_class[' . $key . ']" value="' . esc_html( $service->icon_class ) . '" >
                        </div>
                    </div>
                    
                    <a href="#" class="rwmb-button button remove-clone" style="display: none;">-</a>
                  </div>';
            }
        }

        echo '<a href="#" class="rwmb-button button-primary add-clone">+</a>
        </div></div>';
    }
}

/*
 * rwmb metabox save action
 */
if ( ! function_exists( 'ct_save_add_service_data' ) ) {
    function ct_save_add_service_data( $post_id ) {
        if ( ! isset( $_POST['ct_services_nonce'] ) ) {
            return $post_id;
        }

        $nonce = $_POST['ct_services_nonce'];
        if ( ! wp_verify_nonce( $nonce, 'ct_services' ) ) {
            return $post_id;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        $ids = $_POST['service_id'];
        $titles = $_POST['service_title'];
        $prices = $_POST['service_price'];
        $per_persons = ! empty( $_POST['service_per_person'] ) ? $_POST['service_per_person'] : array();
        $inc_childs = ! empty( $_POST['service_inc_child'] ) ? $_POST['service_inc_child'] : array();
        $icon_classes = $_POST['service_icon_class'];

        // Check the user's permissions.
        if ( current_user_can( 'edit_post', $post_id ) ) {
            global $wpdb;

            // delete original data
            $sql = 'DELETE FROM ' . CT_ADD_SERVICES_TABLE . ' WHERE post_id=%d';
            $wpdb->query( $wpdb->prepare( $sql, $post_id ) );

            foreach ( $titles as $index => $title ) {
                if ( empty( $titles[$index] ) && empty( $prices[$index] ) ) {
                    continue;
                }

                $add_services_data = array( 
                    'post_id'    => $post_id, 
                    'title'      => $titles[$index], 
                    'price'      => $prices[$index], 
                    'per_person' => isset( $per_persons[$index] ) ? 1 : 0, 
                    'inc_child'  => isset( $inc_childs[$index] ) ? 1 : 0, 
                    'icon_class' => $icon_classes[$index] 
                );

                $format = array( '%d', '%s', '%f', '%d', '%d', '%s' );

                // validation if the add_service id is correct
                if ( ! empty( $ids[$index] ) ) {
                    $add_services_data['id'] = $ids[$index];
                    $format[] = '%d';
                }

                $wpdb->insert( CT_ADD_SERVICES_TABLE, $add_services_data, $format ); // add additional services
            }
        }
    }
}

/*
 * schedule meta box html
 */
if ( ! function_exists( 'ct_tour_schedule_meta_box_html' ) ) {
    function ct_tour_schedule_meta_box_html( $post )
    {
        global $wpdb;

        $days = array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' );
        $post_id = $post->ID;
        $has_multi_schedules = get_post_meta( $post_id, '_has_multi_schedules', true );
        $schedules = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . CT_TOUR_SCHEDULES_TABLE . ' WHERE tour_id=%d', $post_id ) );

        wp_nonce_field( 'ct_schedule', 'ct_schedule_nonce' );

        echo '<div class="schedule-wrapper rwmb-input">';
            echo '<div><label><input type="checkbox" name="has_multi_schedules" class="has_multi_schedules" ' . ( !empty($has_multi_schedules) ? 'checked' : '' ) . '> Has multiple schedules?</label></div>';
            if ( empty( $schedules ) ) {
                echo '<div class="rwmb-clone">
                    <div class="rwmb-field schedule-header">
                        <label>From</label> <input type="text" class="rwmb-text schedule-from-date ct_datepicker" name="schedule_from_date[]" value=""><br />
                        <label>To</label> <input type="text" class="rwmb-text schedule-to-date ct_datepicker" name="schedule_to_date[]" value="">
                    </div>
                    <a href="#" class="rwmb-button button remove-clone" style="display: none;">-</a>
                    <table class="schedule-table">
                        <tr class="rwmb-field">
                            <th>Day</th>
                            <th>Is Closed?</th>
                            <th>Open Time</th> 
                            <th>Close Time</th>
                        </tr>
                        <tr class="rwmb-field">
                            <td>Monday</td>
                            <td><input type="checkbox" class="rwmb-checkbox" name="schedule_closed[0][]" value="0"></td>
                            <td><input type="text" class="rwmb-text" name="schedule_open_time[0][]"></td>
                            <td><input type="text" class="rwmb-text" name="schedule_close_time[0][]"></td>
                        </tr>
                        <tr class="rwmb-field">
                            <td>Tuesday</td>
                            <td><input type="checkbox" class="rwmb-checkbox" name="schedule_closed[0][]" value="1"></td>
                            <td><input type="text" class="rwmb-text" name="schedule_open_time[0][]"></td>
                            <td><input type="text" class="rwmb-text" name="schedule_close_time[0][]"></td>
                        </tr>
                        <tr class="rwmb-field">
                            <td>Wednesday</td>
                            <td><input type="checkbox" class="rwmb-checkbox" name="schedule_closed[0][]" value="2"></td>
                            <td><input type="text" class="rwmb-text" name="schedule_open_time[0][]"></td>
                            <td><input type="text" class="rwmb-text" name="schedule_close_time[0][]"></td>
                        </tr>
                        <tr class="rwmb-field">
                            <td>Thursday</td>
                            <td><input type="checkbox" class="rwmb-checkbox" name="schedule_closed[0][]" value="3"></td>
                            <td><input type="text" class="rwmb-text" name="schedule_open_time[0][]"></td>
                            <td><input type="text" class="rwmb-text" name="schedule_close_time[0][]"></td>
                        </tr>
                        <tr class="rwmb-field">
                            <td>Friday</td>
                            <td><input type="checkbox" class="rwmb-checkbox" name="schedule_closed[0][]" value="4"></td>
                            <td><input type="text" class="rwmb-text" name="schedule_open_time[0][]"></td>
                            <td><input type="text" class="rwmb-text" name="schedule_close_time[0][]"></td>
                        </tr>
                        <tr class="rwmb-field">
                            <td>Saturday</td>
                            <td><input type="checkbox" class="rwmb-checkbox" name="schedule_closed[0][]" value="5"></td>
                            <td><input type="text" class="rwmb-text" name="schedule_open_time[0][]"></td>
                            <td><input type="text" class="rwmb-text" name="schedule_close_time[0][]"></td>
                        </tr>
                        <tr class="rwmb-field">
                            <td>Sunday</td>
                            <td><input type="checkbox" class="rwmb-checkbox" name="schedule_closed[0][]" value="6"></td>
                            <td><input type="text" class="rwmb-text" name="schedule_open_time[0][]"></td>
                            <td><input type="text" class="rwmb-text" name="schedule_close_time[0][]"></td>
                        </tr>
                    </table>
                </div>';
            } else {
                foreach ( $schedules as $key => $schedule ) {
                    $schedule_id = $schedule->id;
                    $from_date = $schedule->from;
                    $to_date = $schedule->to;
                    echo '<div class="rwmb-clone">
                        <div class="rwmb-field schedule-header">
                            <label>From</label> <input type="text" class="rwmb-text schedule-from-date ct_datepicker" name="schedule_from_date[]" value="' . ( $from_date != '0000-00-00' ? $from_date : '' ) . '"><br />
                            <label>To</label> <input type="text" class="rwmb-text schedule-to-date ct_datepicker" name="schedule_to_date[]" value="' . ( $to_date != '0000-00-00' ? $to_date : '' ) . '">
                        </div>
                        <a href="#" class="rwmb-button button remove-clone" style="display: none;">-</a>
                        <table class="schedule-table">
                            <tr class="rwmb-field">
                                <th>Day</th>
                                <th>Is Closed?</th>
                                <th>Open Time</th> 
                                <th>Close Time</th>
                            </tr>';
                    $schedule_meta_data = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . CT_TOUR_SCHEDULE_META_TABLE . ' WHERE schedule_id=%d ORDER BY day ASC', $schedule_id ) );
                    foreach( $schedule_meta_data as $schedule_meta ) {
                        echo '<tr class="rwmb-field">
                            <td>' . $days[ $schedule_meta->day ] . '</td>
                            <td><input type="checkbox" class="rwmb-checkbox" name="schedule_closed[' . $key . '][]" value="' . $schedule_meta->day . '" ' . ( !empty($schedule_meta->is_closed) ? 'checked' : '' ) . '></td>
                            <td><input type="text" class="rwmb-text" name="schedule_open_time[' . $key . '][]" value="' . $schedule_meta->open_time . '"></td>
                            <td><input type="text" class="rwmb-text" name="schedule_close_time[' . $key . '][]" value="' . $schedule_meta->close_time . '"></td>
                        </tr>';
                    }
                    echo '</table></div>';
                }
            }
            echo '<a href="#" class="rwmb-button button-primary add-clone">+</a></div>';
    }
}

/*
 * rwmb metabox save action
 */
if ( ! function_exists( 'ct_save_schedule_data' ) ) {
    function ct_save_schedule_data( $post_id ) {
        if ( ! isset( $_POST['ct_schedule_nonce'] ) ) {
            return $post_id;
        }

        $nonce = $_POST['ct_schedule_nonce'];
        if ( ! wp_verify_nonce( $nonce, 'ct_schedule' ) ) {
            return $post_id;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        // Check the user's permissions.
        if ( 'tour' == $_POST['post_type'] && current_user_can( 'edit_post', $post_id ) ) {
            global $wpdb;

            $has_multi_schedules = empty( $_POST['has_multi_schedules'] ) ? 0 : 1;
            $from_dates = $_POST['schedule_from_date'];
            $to_dates = $_POST['schedule_to_date'];
            $closed_data = $_POST['schedule_closed'];
            $open_time_data = $_POST['schedule_open_time'];
            $close_time_data = $_POST['schedule_close_time'];

            // update has multi schedule and count
            update_post_meta( $post_id, '_has_multi_schedules', $has_multi_schedules );

            // delete original data
            $sql = 'DELETE t1, t2 FROM ' . CT_TOUR_SCHEDULE_META_TABLE . ' AS t1 RIGHT JOIN ' . CT_TOUR_SCHEDULES_TABLE . ' AS t2 ON t1.schedule_id = t2.id WHERE t2.tour_id=%d';
            $wpdb->query( $wpdb->prepare( $sql, $post_id ) );

            for ( $index = 0; $index < count( $from_dates ); $index++ ) {
                $from_date = $from_dates[$index];
                $to_date = $to_dates[$index];
                $sc_new_data = array( 'tour_id' => $post_id, 'ts_id' => $index, 'from' => $from_date, 'to' => $to_date );
                $wpdb->insert( CT_TOUR_SCHEDULES_TABLE, $sc_new_data, array( '%d', '%d', '%s', '%s' ) ); // add schedule

                $schedule_id = $wpdb->insert_id;

                for ( $i = 0; $i < 7; $i++ ) {
                    $sc_meta_new_data = array( 
                        'schedule_id' => $schedule_id,
                        'day' => $i,
                        'is_closed' => !empty($closed_data[$index]) && in_array( $i, $closed_data[$index] ) ? 1 : 0,
                        'open_time' => $open_time_data[$index][$i],
                        'close_time' => $close_time_data[$index][$i]
                        );

                    $wpdb->insert( CT_TOUR_SCHEDULE_META_TABLE, $sc_meta_new_data, array( '%d', '%d', '%d', '%s', '%s' ) ); // add schedule meta
                }
            }
        }
    }
}