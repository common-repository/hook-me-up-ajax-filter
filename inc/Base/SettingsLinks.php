<?php

/**
 * SettingsLinks class handles the settings link
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

class SettingsLinks extends BaseController
{

    public function register()
    {
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'settingsLink'));
    }

    public function settingsLink($links)
    {
        $settings_link  = array( '<a href="' . admin_url('admin.php?page=hmu_ajax_filter') . '">Settings</a>' );
        return array_merge($links, $settings_link);
    }
}
