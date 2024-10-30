<?php

/**
 * SettingsApi class handles plugins fields
 *
 * @package   hmu-ajax-filter
 * @author    Another Author <nourleeds@yahoo.co.uk>
 * @copyright 2018 Noureddine Latreche
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   CVS: 1.0.0
 * @link      Null
 */

namespace HmuWAF\Api;

class SettingsApi
{


    // fields
    public $settings = array();
    public $sections = array();
    public $fields = array();


    /**
     * Initialize the call for the fields.
     */
    public function register()
    {

        if (!empty($this->settings)) {
            add_action('admin_init', array($this, 'registerCustomFields'));
        }
    }

    /*
    * Registering fields
    * 1- register settings
    * 2- add settings section
    * 3- add settings field
    */
    public function setSettings($settings)
    {
        $this->settings = $settings;

        return $this;
    }

    public function setSections($sections)
    {
        $this->sections = $sections;

        return $this;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;

        return $this;
    }

    public function registerCustomFields()
    {
        // register setting
        foreach ($this->settings as $setting) {
            register_setting(
                $setting["option_group"],
                $setting["option_name"],
                (isset($setting["callback"]) ? $setting["callback"] : '')
            );
        }

        // add settings section
        foreach ($this->sections as $section) {
            add_settings_section(
                $section["id"],
                $section["title"],
                (isset($section["callback"]) ? $section["callback"] : ''),
                $section["page"]
            );
        }

        // add settings field
        foreach ($this->fields as $field) {
            add_settings_field(
                $field["id"],
                $field["title"],
                (isset($field["callback"]) ? $field["callback"] : ''),
                $field["page"],
                $field["section"],
                (isset($field["args"]) ? $field["args"] : '')
            );
        }
    }
}
