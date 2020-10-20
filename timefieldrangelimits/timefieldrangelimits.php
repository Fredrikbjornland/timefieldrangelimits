<?php
/*
Plugin Name: Gravity Forms time range limit
Plugin URI: http://www.gravityforms.com
Description: A simple add-on to 
Version: 1.0
Author: Spire Consulting
Author URI: https://www.spireconsulting.no*/
define( 'GF_TIMEFIELD_RANGELIMIT', '1.0' );
 
add_action( 'gform_loaded', array( 'GF_Timefield_Rangelimits_Bootstrap', 'load' ), 5 );
 
class GF_Timefield_Rangelimits_Bootstrap {
 
    public static function load() {
 
        if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
            return;
        }
 
        require_once( 'class-gftimefieldrangelimits.php' );
 
        GFAddOn::register( 'GFTimefieldRangelimits' );
    }
 
}
 
function gf_timefield_rangelimit() {
    return GFTimefieldRangelimits::get_instance();
}