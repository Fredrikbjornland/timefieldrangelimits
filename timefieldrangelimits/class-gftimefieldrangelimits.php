<?php

GFForms::include_addon_framework();
 
class GFTimefieldRangelimits extends GFAddOn {
 
    protected $_version = GF_TIMEFIELD_RANGELIMIT;
    protected $_min_gravityforms_version = '1.0';
    protected $_slug = 'timefieldrangelimits';
    protected $_path = 'timefieldrangelimits/timefieldrangelimits.php';
    protected $_full_path = __FILE__;
    protected $_title = 'Gravity Forms timefield rangelimit';
    protected $_short_title = 'timefield rangelimt';
 
    private static $_instance = null;
 
    public static function get_instance() {
        if ( self::$_instance == null ) {
            self::$_instance = new GFTimefieldRangelimits();
        }
 
        return self::$_instance;
    }
 
    public function init() {
        parent::init();
        function console_log( $data ){
            echo '<script>';
            echo 'console.log('. json_encode( $data ) .')';
            echo '</script>';
          };
        add_filter( 'gform_field_validation_1_8', 'validate_time', 10, 4 );
        function validate_time( $result, $value, $form, $field ) {
            //convert the entire time field array into a string, separating values with colons
            $input_time = implode( ':', $value );
            $time = strtotime( $input_time );
            $max_time = strtotime( '23:00' );
            $min_time = strtotime( '19:00' );
            if ($time == false) {
                $result['is_valid'] = false;
                $result['message'] = 'Vennligst fyll inn begge feltene';
            }
            elseif ( $time < $min_time OR $time > $max_time ) {
                $result['is_valid'] = false;
                $result['message'] = 'Velg et tidspunkt mellom 19:00 og 23:00';
            }
            return $result;
        }
    }
    
}