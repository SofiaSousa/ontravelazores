<?php
	global $post_id, $before_list;
	$user_id = get_current_user_id();
	$wishlist = get_user_meta( $user_id, 'wishlist', true );	
?>
<div class="row">
	<?php
		if ( empty( $wishlist ) ) {
			echo '<h5 class="empty-list">' . esc_html__( 'Your wishlist is empty.', 'citytours' ) . '</h5>';
		} else {
			foreach( $wishlist as $postid ) {
				$post_id = $postid;
				$post_type = get_post_type( $post_id );
				if ( ! empty( $post_type ) ) {
					$before_list = '<div class="col-md-4 col-sm-6">';
					ct_get_template( 'loop-grid.php', '/templates/' . get_post_type( $post_id ) . '/');
				}
			}
		}
	?>
</div>