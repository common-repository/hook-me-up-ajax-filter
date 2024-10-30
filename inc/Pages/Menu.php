<?php

/**
 * Menu class defines the menu page and subpages
 *
 * @package   hmu-ajax-filter
 * @author    Another Author <nourleeds@yahoo.co.uk>
 * @copyright 2018 Noureddine Latreche
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   CVS: 1.0.0
 * @link      Null
 */


namespace HmuWAF\Pages;

use HmuWAF\Base\BaseController;

class Menu extends BaseController
{
    public function register()
    {
        add_action('admin_menu', array($this, 'hmuAjaxFilterPages'));
    }
    public function hmuAjaxFilterPages()
    {
        add_menu_page(
            'Hmu ajax filter',
            'Filter',
            'manage_options',
            'hmu_ajax_filter',
            array($this, 'hmuMenuCallback'),
            'dashicons-networking',
            '50'
        );
        add_submenu_page(
            'hmu_ajax_filter',
            'Hmu ajax filter',
            'Hmu filter',
            'manage_options',
            'hmu_ajax_filter',
            array($this, 'hmuSubMenuCallback')
        );
    }
    public function hmuMenuCallback()
    {
        require_once($this->plugin_path."/template/dashboard.php");
    }

    public function hmuSubMenuCallback()
    {
        require_once($this->plugin_path."/template/dashboard.php");
    }
}
