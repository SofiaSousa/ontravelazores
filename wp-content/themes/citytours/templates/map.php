<?php 
if ( ! defined( 'ABSPATH' ) ) { 
    exit; 
}

global $ct_options, $container_id, $center, $related, $zoom, $maptypecontrol, $maptype, $show_filter;

$map_id = 'map-' . mt_rand( 10000, 99999 );

if ( ! empty( $ct_options['tour_map_maker_img'] ) && ! empty( $ct_options['tour_map_maker_img']['url'] ) ) {
    $tour_marker_img_url = $ct_options['tour_map_maker_img']['url'];
} else {
    $tour_marker_img_url = CT_IMAGE_URL . "/pins/tour.png";
}

if ( ! empty( $ct_options['hotel_map_maker_img'] ) && ! empty( $ct_options['hotel_map_maker_img']['url'] ) ) {
    $hotel_marker_img_url = $ct_options['hotel_map_maker_img']['url'];
} else {
    $hotel_marker_img_url = CT_IMAGE_URL . "/pins/hotel.png";
}

$map_data = array();
foreach ( $related as $each_ht ) {
	if ( get_post_type( $each_ht ) == 'hotel' ) {
		$each_pos = get_post_meta( $each_ht, '_hotel_loc', true );
		$map_data['hotels']['name'] = __( 'Hotels', 'citytours' );
		$map_data['hotels']['icon'] = 'icon_set_1_icon-6';
		$map_data['hotels']['data'][] = array( 'id' => $each_ht, 'pos' => $each_pos );
		$map_data['hotels']['img'] = $hotel_marker_img_url;
	} else {
		$each_pos = get_post_meta( $each_ht, '_tour_loc', true );
		$tour_types = wp_get_post_terms( $each_ht, 'tour_type' );
		if ( ! $tour_types || is_wp_error( $tour_types ) ) {			
			$map_data['undefined']['name'] = __( 'Undefined', 'citytours' );
			$map_data['undefined']['data'][] = array( 'id' => $each_ht, 'pos' => $each_pos );
			$map_data['undefined']['img'] = $tour_marker_img_url;
		} else {
			$map_data[$tour_types[0]->slug]['name'] = $tour_types[0]->name;
			$map_data[$tour_types[0]->slug]['icon'] = get_tax_meta( $tour_types[0]->term_id, 'ct_tax_icon_class', true );
			$map_data[$tour_types[0]->slug]['data'][] = array( 'id' => $each_ht, 'pos' => $each_pos );
			
			$img = get_tax_meta( $tour_types[0]->term_id, 'ct_tax_marker_img', true );
			if ( isset( $img ) && is_array( $img ) ) {
				$map_data[$tour_types[0]->slug]['img'] = $img['src'];
			} else {
				$map_data[$tour_types[0]->slug]['img'] = $tour_marker_img_url;
			}
		}
	}
}

if ( ! empty( $container_id ) ) { ?>
	<div class="collapse" id="collapseMap<?php echo esc_attr( $container_id ); ?>">
<?php } ?>

<div id="<?php echo esc_attr( $map_id ); ?>" class="citytours-map"></div>

<?php 
	if ( $show_filter ) {
?>
		<div id="map_filter">
			<ul>
				<li><a class="filter-option" data-cat="showall" href="javascript:void(0);"><i class="icon_set_1_icon-46"></i><span><?php _e( 'Show All', 'citytours' ); ?></span></a></li>
				<?php foreach ( $map_data as $key => $data ) : ?>
					<li><a class="filter-option" data-cat="<?php echo esc_attr( $key ); ?>" href="javascript:void(0);"><?php echo ( ! empty( $data['icon'] ) ) ? '<i class="' . esc_attr( $data['icon'] ) . '"></i>':''; ?><span><?php echo esc_html( $data['name'] ); ?></span></a></li>
				<?php endforeach; ?>
			</ul>
			<select class="map_filter form-control">
				<option value="showall" selected><?php _e( 'Show All', 'citytours' ); ?></option>
				<?php foreach ( $map_data as $key => $data ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $data['name'] ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
<?php
	}
?>

<?php if ( ! empty( $container_id ) ) { ?>
	</div>
<?php } ?>


<script type="text/javascript">
	jQuery(document).ready(function($){
		$('#map_filter a.filter-option').on('click', function(){
			var key = $(this).data('cat');
			$('#map_filter select.map_filter option').each(function(){
				if( $(this).val() == key ) $(this).attr('selected', 'selected');
			});
			toggleMarkers(key);
		});

		$('#map_filter select.map_filter').on('change', function(){
			toggleMarkers($(this).val());
		});

		<?php if ( ! empty( $container_id ) ) {  ?>
		$('#collapseMap').on('shown.bs.collapse', function(e){
		<?php } ?>
			var mapId = '<?php echo esc_js( $map_id ); ?>';
			var zoom = <?php echo esc_js( $zoom ); ?>;
			var mapType = <?php echo esc_js( $maptype ); ?>;
			var mapTypeControl = <?php echo esc_js( $maptypecontrol ); ?>;
			var markersData = {
				<?php foreach ( $map_data as $key => $data ) { ?>
					'<?php echo esc_js( $key );; ?>' : [
					<?php
						if ( ! empty( $data['data'] ) ) {
							foreach ( $data['data'] as $d ) {
								$each_pos = $d['pos'];
					if ( ! empty( $each_pos ) ) {
						$each_pos = explode( ',', $each_pos );
						$description = str_replace( "'", "\'", wp_trim_words( strip_shortcodes(get_post_field("post_content", $d['id'])), 20, '...' ) );
						 ?>
									{
										name: '<?php echo esc_js( get_the_title( $d['id'] ) ); ?>',
										type: '<?php echo esc_js( $data['img'] ); ?>',
										location_latitude: <?php echo esc_js( $each_pos[0] ); ?>,
										location_longitude: <?php echo esc_js( $each_pos[1] ); ?>,
										map_image_url: '<?php echo ct_get_header_image_src( $d['id'], "ct-map-thumb" ); ?>',
										name_point: '<?php echo esc_js( get_the_title( $d['id'] ) ); ?>',
										description_point: '<?php echo esc_js( $description ); ?>',
										url_point: '<?php echo get_permalink( $d['id'] ); ?>'
									},
						<?php
					}
							} 
						}
					?>
					],
				<?php } ?>
			};
			<?php 
			if ( ! empty( $center ) ) {
				$center_pos = explode( ',', $center );
			} else if ( empty( $center ) && ! empty( $related[0] ) ) {
				if ( get_post_type( $related[0] ) == 'hotel' ) {
					$center_pos = get_post_meta( $related[0], '_hotel_loc', true );
				} else { 
					$center_pos = get_post_meta( $related[0], '_tour_loc', true );
				}
				$center_pos = explode( ',', $center_pos );
			}
			 ?>

			var lati = <?php echo trim($center_pos[0]) ?>;
			var long = <?php echo trim($center_pos[1]) ?>;
			var _center = [lati, long];
			renderMap( _center, markersData, zoom, mapType, mapTypeControl, mapId, '<?php echo esc_js( $show_filter ); ?>' );

		<?php if ( !empty( $container_id ) ) {  ?>
		});
		<?php } ?>
	});
</script>