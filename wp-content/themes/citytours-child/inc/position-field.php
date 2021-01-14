<?php

// Add Position field to new tour_type form.
add_action(
	'tour_type_add_form_fields',
	function( $taxonomy ) {
		echo '<div class="form-field">
		<label for="tax_position">Position</label>
		<input type="number" name="tax_position" id="tax_position" />
		<p>To be displayed in the frontend.</p>
		</div>';
	},
	10,
	1
);

// Add Position field to edit tour_type form.
add_action(
	'tour_type_edit_form_fields',
	function( $term, $taxonomy ) {
		$value = get_term_meta( $term->term_id, 'tax_position', true );

		echo '<tr class="form-field">
		<th>
			<label for="tax_position">Position</label>
		</th>
		<td>
			<input name="tax_position" id="tax_position" type="number" value="' . esc_attr( $value ) . '" />
			<p class="description">To be displayed in the frontend</p>
		</td>
		</tr>';
	},
	10,
	2
);

add_action( 'created_tour_type', 'ot_tour_type_save_term_fields' );
add_action( 'edited_tour_type', 'ot_tour_type_save_term_fields' );

/**
 * Update tour_type position.
 */
function ot_tour_type_save_term_fields( $term_id ) {
	update_term_meta(
		$term_id,
		'tax_position',
		sanitize_text_field( $_POST[ 'tax_position' ] )
	);
}

// Order tour_types by tax_position.
add_filter(
	'terms_clauses',
	function( $pieces, $taxonomies, $args ) {
		foreach ( $taxonomies as $taxonomy ) {
			if ( 'tour_type' === $taxonomy ) {
				global $wpdb;

				$join_statement = " LEFT JOIN $wpdb->termmeta AS term_meta ON t.term_id = term_meta.term_id AND term_meta.meta_key = 'tax_position'";

				if ( ! ( false !== strstr( $pieces['join'], $join_statement ) ) ) {
					$pieces['join'] .= $join_statement;
				}

				$pieces['orderby'] = 'ORDER BY CAST( term_meta.meta_value AS UNSIGNED )';
			}
		}
		return $pieces;
	},
	10,
	3
);
