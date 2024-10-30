<?php
/*
Plugin Name: Hook Me Up Ajax Filter
Plugin URI:  http://ukcoding.com
Description: Ajax filter for woocommerce store
Version:     1.0.0
Author:      Noureddine Latreche
Text Domain: Hook Me Up
Domain Path: /languages
License:     GPL3

*/
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
define('HMU_WAF_SITE_ROOT', realpath(dirname(__FILE__)));


/**
 * first we call the files we are using
 */
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

use HmuWAF\Base\Activate;

use HmuWAF\Base\Deactivate;

function hmuWAFAjaxFilterActivate()
{
    Activate::activate();
}
function hmuWAFAjaxFilterDeactivate()
{
    Deactivate::deactivate();
}
register_activation_hook(__FILE__, 'hmuWAFAjaxFilterActivate');
register_deactivation_hook(__FILE__, 'hmuWAFAjaxFilterDeactivate');


if (class_exists('HmuWAF\\Init')) {
    HmuWAF\Init::registerServices();
}
