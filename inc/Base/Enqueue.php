<?php

/**
 * Enqueue class handles the scripts and styles of the plugin
 *
 * @package   hmu-ajax-filter
 * @author    Another Author <nourleeds@yahoo.co.uk>
 * @copyright 2018 Noureddine Latreche
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   CVS: 1.0.0
 * @link      Null
 */

namespace HmuWAF\Base;

use HmuWAF\Base\BaseController;

class Enqueue extends BaseController
{

    public function register()
    {
        add_action('wp_enqueue_scripts', array($this, 'hmuFilterScripts'));
        add_action('admin_enqueue_scripts', array($this, 'hmuFilterAdminScripts'));
    }

    public function hmuFilterScripts()
    {
        wp_enqueue_style('hmuCss', plugin_dir_url(dirname(__DIR__)) . '/assets/filter.css', array(), '1.0.1');
        wp_enqueue_script('hmuJs', plugin_dir_url(dirname(__DIR__)) . '/assets/filter.js', array(), null, true);

        $id = '';
        $select = '';
        if ($dashboard_option = get_option('hmu_dashboard')) {
            $id = array_key_exists('wrapper_id', $dashboard_option)
                ?  $dashboard_option["wrapper_id"] : 'container';
            $select = array_key_exists('use_select', $dashboard_option)
                ?  $dashboard_option["use_select"] : '';
        }

            // $translation_array = array('adminAjax' => admin_url('admin-ajax.php'));
        wp_localize_script('hmuJs', 'ajax_var', array(
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ajax-nonce'),
            'wrapper_id' => $id,
            'select' => $select
        ));
    }

    public function hmuFilterAdminScripts($hook)
    {
        if ($hook != 'toplevel_page_hmu_ajax_filter') {
            return;
        }

        wp_enqueue_style('hmuAdminCss', plugin_dir_url(dirname(__DIR__)) . '/assets/hmu-admin.css', array(), '1.0.1');
        wp_enqueue_style('hmuAdminStyleCss', plugin_dir_url(dirname(__DIR__)) . '/assets/hmu.custom_css.css', array(), '1.0.1');
        wp_enqueue_script('aceJs', plugin_dir_url(dirname(__DIR__)) . '/assets/ace/ace.js', array(), null, true);
        wp_enqueue_script('hmuCssJs', plugin_dir_url(dirname(__DIR__)) . '/assets/hmu_css.js', array(), null, true);
    }
}
