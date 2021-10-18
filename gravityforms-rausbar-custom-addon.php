<?php
/**
 * Plugin Name: Gravity Forms Raus Bar Custom Add-On
 * Plugin URI: https://github.com/Fredrikbjornland/timefieldrangelimits
 * Description: Custom Gravity Forms Add-On for Raus Bar.
 * Version: 1.3
 * Author: Spire Consulting
 * Author URI: https://www.spireconsulting.no
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) die;

// Don't run if the Gravity Forms plugin is not active.
if ( ! is_plugin_active( 'gravityforms/gravityforms.php' ) ) return;

define( 'RAUSBAR_GF_ADDON_VERSION', get_plugin_data( __FILE__ )['Version'] );
/**
 * Get the chosen day from the datefield to check if it is saturday or sunday
 */
$chosenDay = 0;
$chosenDate = 0;
$min_time = "19:00";

function getChosenday( $result, $value, $form, $field ) {
	global $chosenDay;
	global $chosenDate;
	global $min_time;
	// The two last weekends of nevember, and the two first weekends of december will open 1t 18:00 and not 19:00
	$secondLastFridayOfNovember = date("Y-m-d", strtotime("-7 days", strtotime("last friday of november")));
	$secondSaturdayOfDecember = date("Y-m-d", strtotime("+7 days", strtotime("first saturday of december")));

	//Replece from / to - for strtotime() to assume the string is in european order
	$convertedDate = str_replace("/", "-", $value);
	//Get day number to check if it is friday or saturday
	$chosenDay = date('w', strtotime($convertedDate));
	//Get week number to check if it is between week 47-51
	$chosenDate = date("Y-m-d", strtotime($convertedDate));

	date_default_timezone_set("Europe/Oslo");
	$currentHour = strtotime(date("H:i"));
	/**
	 * Extend open hours to 18:00 if it
	 * is the two last weekends of november or the two first
	 * weekends of december
	 */
	if ( $secondLastFridayOfNovember <= $chosenDate && $chosenDate <= $secondSaturdayOfDecember ) {
		if ( $chosenDay == 5 || $chosenDay == 6) {
			$min_time = '18:00';
		}
	}

	if ($currentHour >= strtotime($min_time)) {
		if ($chosenDate <= date("Y-m-d")) {
			$result['is_valid'] = false;
			$result['message']  = "Det er ikke mulig å booke bord for i dag etter kl {$min_time}";
		}
	}
	if ($chosenDay == 1) {
		$result['is_valid'] = false;
		$result['message']  = "Vi er stengt på mandager";
	}
	return $result;
}

add_filter( 'gform_field_validation_2_7', 'getChosenDay', 10, 4 );
/**
 * Validate that the time field is between 19:00 and 23:00.
 */
function rausbar_validate_time_field( $result, $value, $form, $field ) {
	global $chosenDay;
	global $chosenDate;
	global $min_time;
	$time     = strtotime( implode( ':', $value ) );
	$max_time = '23:00';
	
	if ( $time == false ) {
		$result['is_valid'] = false;
		$result['message']  = 'Vennligst fyll inn begge feltene.';
	} elseif ( $time < strtotime( $min_time ) || $time > strtotime( $max_time ) ) {
		$result['is_valid'] = false;
		$result['message']  = "Velg et tidspunkt mellom {$min_time} og {$max_time}.";
	}

	return $result;
}
add_filter( 'gform_field_validation_2_8', 'rausbar_validate_time_field', 10, 4 );

/**
 * Enqueue datepicker JS functions.
 */
function rausbar_enqueue_datepicker_script( $form, $is_ajax ) {
	wp_enqueue_script(
		'rausbar-gf-datepicker-script',
		plugins_url( 'assets/js/gf-date-picker-options.js' , __FILE__ ),
		['jquery', 'gform_datepicker_init'],
		RAUSBAR_GF_ADDON_VERSION
	);
}
add_action( 'gform_enqueue_scripts_2', 'rausbar_enqueue_datepicker_script', 10, 2 );
