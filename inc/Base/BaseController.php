<?php

/**
 * BaseController class holds common variables and functions
 *
 * @package   hmu-ajax-filter
 * @author    Another Author <nourleeds@yahoo.co.uk>
 * @copyright 2018 Noureddine Latreche
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   CVS: 1.0.0
 * @link      Null
 */

namespace HmuWAF\Base;

class BaseController
{
    public $plugin_path;

    public $plugin_url;

    public $plugin;

    public $subpagesOutput = array();

    //public $dahboardFields = array();
    public $fieldsOutput = array();

   

    public function __construct()
    {
       /* $this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));*/
        $this->plugin_url = plugin_dir_url(dirname(__DIR__));
         $this->plugin_path = HMU_WAF_SITE_ROOT.'/';
       /* $this->plugin_url = plugins_url().'/hmu-ajax-filter/';*/


        $this->subpagesOutput = array(

            'hmu_ajax_filter' =>
                array('Hmu ajax filter', 'hmu_filter_callback'),

        );
    }

    public static function hmuSeoUrl($string)
    {
        //Lower case everything
        $string = strtolower($string);
        //Make alphanumeric (removes all other characters)
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        //Clean up multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);
        return $string;
    }

    public static function hmuCleanString($string)
    {
       // $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = str_replace("pa_", "", $string);
        $string = preg_replace('/[^A-Za-z0-9\-]/', ' ', $string); // Removes special chars.

        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }

    public static function hmuSanitizeArray($array = array())
    {
        $new_input = array();
        foreach ( $array as $key => $val ) {
            if(!is_array($val)) {
                $new_input[sanitize_text_field($key)] = sanitize_text_field($val);
            }else {
                $new_input[sanitize_text_field($key)] = self::hmuSanitizeArray($val);
            }
        }
        return $new_input;
    }

}
