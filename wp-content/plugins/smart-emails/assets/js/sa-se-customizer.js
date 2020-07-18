/**
 * Smart Emails admin JS
 *
 * @package smart-emails/assets/js
 */

( function( jQuery ) {
	'use strict';

	/*Email Theme live Customization script*/

	var template_id = sa_se_params.current_template;

	// Header Logo - Brand identity->Brand Logo.
	wp.customize(
		'se_brand_identity[header_logo]',
		function( value ) {
			value.bind(
				function( newval ) {
					jQuery( '#template_header_image p img ' ).attr( 'src', newval );
				}
			);
		}
	);

	// Facebook Logo.
	wp.customize(
		'se_brand_identity[facebook_logo]',
		function( value ) {
			value.bind(
				function( newval ) {
					jQuery( '#se_facebook_logo ' ).attr( 'src', newval );
				}
			);
		}
	);

	// Facebook link - Brand identity->Social Links.
	wp.customize(
		'se_brand_identity[facebook_link]',
		function( value ) {
			value.bind(
				function( newval ) {
					jQuery( '#facebook_link' ).attr( 'href', newval );
				}
			);
		}
	);

	// Twitter Logo.
	wp.customize(
		'se_brand_identity[twitter_logo]',
		function( value ) {
			value.bind(
				function( newval ) {
					jQuery( '#se_twitter_logo' ).attr( 'src', newval );
				}
			);
		}
	);

	// Twitter Link - Brand identity->Social Links.
	wp.customize(
		'se_brand_identity[twitter_link]',
		function( value ) {
			value.bind(
				function( newval ) {
					jQuery( '#twitter_link' ).attr( 'href', newval );
				}
			);
		}
	);

	// Instagram Logo.
	wp.customize(
		'se_brand_identity[instagram_logo]',
		function( value ) {
			value.bind(
				function( newval ) {
					jQuery( '#se_instagram_logo ' ).attr( 'src', newval );
				}
			);
		}
	);

	// Instagram Link - Brand identity->Social Links.
	wp.customize(
		'se_brand_identity[instagram_link]',
		function( value ) {
			value.bind(
				function( newval ) {
					jQuery( '#instagram_link' ).attr( 'href', newval );
				}
			);
		}
	);

	// Footer Content - Brand identity->Footer Text.
	wp.customize(
		'se_brand_identity[footer_text]',
		function( value ) {
			value.bind(
				function( newval ) {
					jQuery( '.se_footer' ).html( newval );
				}
			);
		}
	);

	// Header Color - Simple.
	wp.customize(
		'se_simple[header_color]',
		function( value ) {
			value.bind(
				function( newval ) {
					jQuery( '#template_header' ).css( 'background', newval );
				}
			);
		}
	);

	// Header Text Color - Simple.
	wp.customize(
		'se_simple[header_text_color]',
		function( value ) {
			value.bind(
				function( newval ) {
					jQuery( '#header_wrapper #header_text' ).css( 'color', newval );
				}
			);
		}
	);

	// Email Background Color - Simple.
	wp.customize(
		'se_simple[background_color]',
		function( value ) {
			value.bind(
				function( newval ) {
					jQuery( '#wrapper' ).css( 'background-color', newval );
				}
			);
		}
	);

	// Header Text - Simple.
	wp.customize(
		'se_simple[' + template_id + '_header_text]',
		function( value ) {
			value.bind(
				function( newval ) {
					jQuery( '#header_wrapper #header_text' ).html( newval );
				}
			);
		}
	);

	// Header Content - Simple.
	/*wp.customize( 'se_simple['+template_id+'_header_content]', function( value ) {
		value.bind( function( newval ) {
			jQuery( '#header_content' ).html( newval );
		} );
	} );*/

	// Deluxe Theme - Menus.
	for ( var i = 1; i <= 5; i++ ) {
		wp.customize(
			'se_deluxe[url' + i + ']',
			function( value ) {
				value.bind(
					function( newval ) {
						jQuery( 'a#url' + i ).attr( 'href', newval );
					}
				);
			}
		);

		wp.customize(
			'se_deluxe[text' + i + ']',
			function( value ) {
				value.bind(
					function( newval ) {
						jQuery( 'span#text' + i ).html( newval );
					}
				);
			}
		);
	}

	// Deluxe Theme - Email Background Color.
	wp.customize(
		'se_deluxe[background_color]',
		function( value ) {
			value.bind(
				function( newval ) {
					jQuery( '#wrapper' ).css( 'background-color', newval );
				}
			);
		}
	);

	// Deluxe Theme - Border Color.
	wp.customize(
		'se_deluxe[border_color]',
		function( value ) {
			value.bind(
				function( newval ) {
					jQuery( '#template_container' ).css( 'border-color', newval );
				}
			);
		}
	);

	// Deluxe Theme - Body Color.
	wp.customize(
		'se_deluxe[body_color]',
		function( value ) {
			value.bind(
				function( newval ) {
					jQuery( '#header_menu' ).css( 'background-color', newval );
				}
			);
		}
	);

	// Classic - Email Background Color.
	wp.customize(
		'se_classic[background_color]',
		function( value ) {
			value.bind(
				function( newval ) {
					jQuery( '#wrapper' ).css( 'background-color', newval );
				}
			);
		}
	);

	// Classic - Top Border Color.
	wp.customize(
		'se_classic[top_border_color]',
		function( value ) {
			value.bind(
				function( newval ) {
					jQuery( '#template_container' ).css( 'border-top', '5px solid ' + newval );
				}
			);
		}
	);

	// Classic - Header Color.
	wp.customize(
		'se_classic[header_color]',
		function( value ) {
			value.bind(
				function( newval ) {
					jQuery( '#template_header' ).css( 'background-color', newval );
				}
			);
		}
	);

	// Classic - Footer Color.
	wp.customize(
		'se_classic[footer_color]',
		function( value ) {
			value.bind(
				function( newval ) {
					jQuery( '#template_footer' ).css( 'background-color', newval );
				}
			);
		}
	);

	// Classic - Footer Text Color.
	wp.customize(
		'se_classic[footer_text_color]',
		function( value ) {
			value.bind(
				function( newval ) {
					jQuery( '#template_footer' ).css( 'color', newval );
				}
			);
		}
	);

	// Classic - Border Color.
	wp.customize(
		'se_classic[border_color]',
		function( value ) {
			value.bind(
				function( newval ) {
					jQuery( '#template_container' ).css(
						{
							"border-left": "1px solid " + newval,
							"border-right": "1px solid " + newval,
							"border-bottom": "1px solid " + newval
						}
					);
				}
			);
		}
	);

	// Classic - Body Color.
	wp.customize(
		'se_classic[body_color]',
		function( value ) {
			value.bind(
				function( newval ) {
					jQuery( '#template_body' ).css( 'background-color', newval );
				}
			);
		}
	);

	// Classic - Promotional Image Link.
	wp.customize(
		'se_classic[promotional_image_link]',
		function( value ) {
			value.bind(
				function( newval ) {
					jQuery( '#promotional_image_link' ).attr( 'href', newval );
				}
			);
		}
	);

	// Elegant - Email Background Color.
	wp.customize(
		'se_elegant[background_color]',
		function( value ) {
			value.bind(
				function( newval ) {
					jQuery( '#wrapper' ).css( 'background-color', newval );
				}
			);
		}
	);

	// Elegant - Body Background Color.
	wp.customize(
		'se_elegant[body_background_color]',
		function( value ) {
			value.bind(
				function( newval ) {
					jQuery( '#template_container' ).css( 'background-color', newval );
				}
			);
		}
	);

	// Elegant - Body Color.
	wp.customize(
		'se_elegant[body_color]',
		function( value ) {
			value.bind(
				function( newval ) {
					jQuery( '#template_body' ).css( 'background-color', newval );
				}
			);
		}
	);

} )( jQuery );
