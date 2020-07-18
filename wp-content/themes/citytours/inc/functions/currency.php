<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
 * Currency converter
 */
if ( ! function_exists( 'ct_currency_converter' ) ) {
	function ct_currency_converter( $amount, $from_currency, $to_currency ) {

		global $ct_options;
		
		if ( strtoupper( $from_currency ) == strtoupper( $to_currency ) ) {
			return $amount;
		}

		$converter 			= 'free_currency_converter';
		$converted_amount 	= 0.0;

		//google currency convert
		if ( 'google_converter' == $converter ) {
			$amount 		= urlencode( $amount );
			$from_currency  = urlencode( $from_currency );
			$to_currency 	= urlencode( $to_currency );
			$remote_get_raw = wp_remote_get( "https://finance.google.com/finance/converter?a=$amount&from=$from_currency&to=$to_currency" );
			$result 		= '';

			if ( ! is_wp_error( $remote_get_raw ) ) {
				$result = $remote_get_raw['body'];
				$result = explode( "<span class=bld>", $result );

				if ( is_array( $result ) && isset( $result[1] ) ) {
					$result = explode( "</span>", $result[1] );
				} else {
					return false;
				}
			} else {
				return false;
			}

			$converted_amount = floatval( preg_replace( "/[^0-9\.]/", null, $result[0] ) );
		} else if ( $converter == 'free_currency_converter' ) {
			$apikey = $ct_options['currency_converter_key'];
			if ( empty( $apikey ) ) {
				return $converted_amount;
			}

			$from_Currency = urlencode( strtoupper( $from_currency ) );
			$to_Currency = urlencode( strtoupper( $to_currency ) );
			$query =  "{$from_Currency}_{$to_Currency}";

			if ( ! empty( $ct_options['currency_converter_key_type'] ) && $ct_options['currency_converter_key_type'] == 'premium' ) {
				$json = file_get_contents("https://api.currconv.com/api/v7/convert?q={$query}&compact=ultra&apiKey={$apikey}");			
			} else {
			$json = file_get_contents("https://free.currconv.com/api/v7/convert?q={$query}&compact=ultra&apiKey={$apikey}");
			}

			$obj = json_decode($json, true);

			return floatval($obj["$query"]);
			
			/*$remote_get_raw = wp_remote_get( "http://free.currencyconverterapi.com/api/v6/convert?q=$from_currency" . "_" . "$to_currency&compact=ultra&apiKey=54ec7502e3949835de99" );

			if ( ! is_wp_error( $remote_get_raw ) ) {
				$data = json_decode( $remote_get_raw['body'], true );
				$currency_key = strtoupper( $from_currency . '_' . $to_currency );
				if ( ! empty( $data[$currency_key] ) ) {
				$converted_amount = $data[$currency_key];
				} else {
					$converted_amount = 0;
				}
			}*/
		}

		return $converted_amount;
	}
}

/*
 * Get all currencies from DB
 */
if ( ! function_exists( 'ct_get_all_available_currencies' ) ) {
	function ct_get_all_available_currencies() {
		global $wpdb;

		$sql = "SELECT * FROM " . CT_CURRENCIES_TABLE;
		$results = $wpdb->get_results( $sql, ARRAY_A );

		$all_currencies = array();
		foreach ( $results as $result ) {
			if ( ! empty( $result['currency_code'] ) ) {
				$all_currencies[ strtolower( $result['currency_code'] ) ] = $result['currency_label'];
			}
		}

		return $all_currencies;
	}
}

/*
 * Get default site currencies after theme setup
 */
if ( ! function_exists( 'ct_get_default_available_currencies' ) ) {
	function ct_get_default_available_currencies() {
		return array(
			'usd' => '1',
			'eur' => '1'
		);
	}
}

/*
 * Get currency symbol from currency code
 */
if ( ! function_exists( 'ct_get_currency_symbol' ) ) {
	function ct_get_currency_symbol( $currency_code ) {
		global $wpdb;

		if ( empty( $currency_code ) ) {
			return false;
		}

		$sql = "SELECT currency_symbol FROM " . CT_CURRENCIES_TABLE . " where currency_code = '" . esc_sql( $currency_code ) . "' limit 1";
		$currency_symbol = $wpdb->get_var( $sql );

		return $currency_symbol;
	}
}

/*
 * Get current user currency
 */
if ( ! function_exists( 'ct_get_user_currency' ) ) {
	function ct_get_user_currency() {
		global $ct_options;

		$currency = ct_get_def_currency();

		if ( ! empty( $_GET['selected_currency'] ) ) {
			$currency = sanitize_text_field( $_GET['selected_currency'] );
		} elseif ( ! empty( $_SESSION['user_currency'] ) ) {
			$currency = $_SESSION['user_currency'];
		} elseif ( ! empty( $_COOKIE['selected_currency'] ) ) {
			$currency = sanitize_text_field( $_COOKIE['selected_currency'] );
		}

		if ( ! array_key_exists( $currency, $ct_options['site_currencies'] ) ) {
			$currency = ct_get_def_currency();
		}

		return $currency;
	}
}

/*
 * Return site defaul currency symbol
 */
if ( ! function_exists( 'ct_get_site_currency_symbol' ) ) {
	function ct_get_site_currency_symbol() {
		return ct_get_currency_symbol( ct_get_def_currency() );
	}
}

/*
 * init curency function
 */
if ( ! function_exists( 'ct_init_currency' ) ) {
	function ct_init_currency() {
		global $ct_options;

		if ( ! empty( $_GET['selected_currency'] ) ) {
			if ( ! array_key_exists( $_GET['selected_currency'] , $ct_options['site_currencies'] ) ) {
				$_GET['selected_currency'] = ct_get_def_currency();
			}

			$_SESSION['user_currency'] = sanitize_text_field( $_GET['selected_currency'] );
			$_SESSION['exchange_rate'] = ct_currency_converter( 1 , ct_get_def_currency(), $_SESSION['user_currency'] );
			$_SESSION['currency_symbol'] = ct_get_currency_symbol( $_SESSION['user_currency'] );

			setcookie( "selected_currency", $_SESSION['user_currency'], time()+3600*24*365 );

			if ( is_user_logged_in() ) {
				$current_user = wp_get_current_user();
				update_user_meta( $current_user->ID, 'selected_currency', $_SESSION['user_currency'] );
			}
		}

		//user_currency init
		if ( empty( $_SESSION['user_currency'] ) ) {
			$user_currency = '';
			if ( is_user_logged_in() ) {
				$current_user = wp_get_current_user();
				$user_currency = get_user_meta( $current_user->ID, 'selected_currency', true );
			}

			if ( ! empty( $user_currency ) ) {
				$_SESSION['user_currency'] = $user_currency;
			} elseif ( isset( $_COOKIE['selected_currency'] ) ) {
				$_SESSION['user_currency'] = $_COOKIE['selected_currency'];
			} else {
				$_SESSION['user_currency'] = ct_get_def_currency();
			}
		}

		//exchange_rate init
		if ( empty( $_SESSION['exchange_rate'] ) ) {
			$_SESSION['exchange_rate'] = ct_currency_converter( 1 , ct_get_def_currency(), $_SESSION['user_currency'] );
		}

		//currency_symbol init
		if ( empty( $_SESSION['currency_symbol'] ) ) {
			$_SESSION['currency_symbol'] = ct_get_currency_symbol( $_SESSION['user_currency'] );
		}
	}
}

/*
 * check multi currency
 */
if ( ! function_exists( 'ct_is_multi_currency' ) ) {
	function ct_is_multi_currency( ) {
		global $ct_options;

		if ( ! empty( $ct_options['site_currencies'] ) && is_array( $ct_options['site_currencies'] ) && count( array_filter( $ct_options['site_currencies'] ) ) > 1 ) {
			return true;
		} else {
			return false;
		}
	}
}

/*
 * price format
 */
if ( ! function_exists( 'ct_get_price_format' ) ) {
	function ct_get_price_format( $type = "" ) {
		global $ct_options;

		$currency_pos = ! empty( $ct_options['cs_pos'] ) ? $ct_options['cs_pos'] : 'left';
		$format 	  = '%1$s%2$s';

		if ( 'special' == $type ) {
			switch ( $currency_pos ) {
				case 'right' :
					$format = '<span>%2$s<sup>%1$s</sup></span>';
					break;
				case 'left_space' :
					$format = '<span><sup>%1$s</sup>&nbsp;%2$s</span>';
					break;
				case 'right_space' :
					$format = '<span>%2$s&nbsp;<sup>%1$s</sup></span>';
					break;
				case 'left' :
				default:
					$format = '<span><sup>%1$s</sup>%2$s</span>';
					break;
			}
		} else {
			switch ( $currency_pos ) {
				case 'left' :
					$format = '%1$s%2$s';
					break;
				case 'right' :
					$format = '%2$s%1$s';
					break;
				case 'left_space' :
					$format = '%1$s&nbsp;%2$s';
					break;
				case 'right_space' :
					$format = '%2$s&nbsp;%1$s';
					break;
			}
		}

		return apply_filters( 'ct_price_format', $format, $currency_pos, $type );
	}
}

/*
 * price function
 */
if ( ! function_exists( 'ct_price' ) ) {
	function ct_price( $amount, $type="", $currency = '', $convert = 1 ) {
		global $ct_options;

		$exchange_rate = 1;
		$currency_symbol = '';

		if ( empty( $amount ) ) {
			$amount = 0;
		} else {
			$amount = floatval( $amount );
		}

		//if ( ct_is_woocommerce_integration_enabled() ) { 
		//	$currency_symbol = get_woocommerce_currency_symbol();
		//} else { 
			if ( empty( $currency ) ) {
				if ( ! isset( $_SESSION['exchange_rate'] ) || ! isset( $_SESSION['currency_symbol'] ) ) {
					ct_init_currency();
				}
				$exchange_rate 	 = $_SESSION['exchange_rate'];
				$currency_symbol = $_SESSION['currency_symbol'];
			} else {
				$exchange_rate 	 = ct_currency_converter( 1 , ct_get_def_currency(), $currency );
				$currency_symbol = ct_get_currency_symbol( $currency );
			}
		//}

		if ( $convert ) { 
			$amount *= $exchange_rate; 
		}

		$decimal_prec 	= isset( $ct_options['decimal_prec'] ) ? $ct_options['decimal_prec'] : 2;
		$decimal_sep 	= isset( $ct_options['decimal_sep'] ) ? $ct_options['decimal_sep'] : '.';
		$thousands_sep  = isset( $ct_options['thousands_sep'] ) ? $ct_options['thousands_sep'] : ',';
		$price_label 	= number_format( $amount, $decimal_prec, $decimal_sep, $thousands_sep );

		$format 		= ct_get_price_format( $type );

		return sprintf( $format, $currency_symbol, $price_label );
	}
}

/*
 * return default currency code ( WooCommerce currency code if integration enabled )
 */
if ( ! function_exists( 'ct_get_def_currency' ) ) {
	function ct_get_def_currency() {
		global $ct_options;

		$currency = 'usd';
		//if ( ct_is_woocommerce_integration_enabled() ) { 
		//	$currency = get_woocommerce_currency();
		//} else if ( isset( $ct_options['def_currency'] ) && ! empty( $ct_options['def_currency'] ) ) { 
		if ( isset( $ct_options['def_currency'] ) && ! empty( $ct_options['def_currency'] ) ) { 
			$currency = $ct_options['def_currency'];
		}

		return $currency;
		//return apply_filters( 'ct_def_currency', $currency );
	}
}