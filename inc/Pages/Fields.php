<?php

/**
 * Fields class defines the fields used for the plugin settings
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

use HmuWAF\Api\SettingsApi;

use HmuWAF\Api\Callbacks\FieldsCallbacks;

class Fields extends BaseController
{
    public $settings;
    public $fields_callbacks;
    public $pages = array();
    public $subpages = array();

    public function register()
    {
        $this->settings = new SettingsApi();
        $this->fields_callbacks = new FieldsCallbacks();
        $this->setSettings();
        $this->setSections();
        $this->setFields();
        $this->settings->register();
    }

    /*
    * create fields
    */
    public function setSettings()
    {
        /*
         * # for each page create group of fields and give each group option name
         * #
         */
        $args = array(
            array(
                'option_group' => 'hmu_dashboard_options_group',
                'option_name' => 'hmu_dashboard',
                'callback' => array( $this->fields_callbacks,'sanitizeCallback' )
            )
        );
        $this->settings->setSettings($args);
    }

    public function setSections()
    {
        $args = array(
            array(
                'id' => 'hmu_dashboard_index',
                'title' => 'Dashboard',
                'callback' => array( $this->fields_callbacks, 'dashboardSectionManager' ),
                'page' => 'hmu_ajax_filter' //dahboard page
            )
        );
        $this->settings->setSections($args);
    }

    public function dahboardFields()
    {
        return  array(
            // ID
            //0- title 1- callback 2-page 3- section 4- option name 5-input type
            'wrapper_id' =>
                array('Products wrapper #id or .class',
                    'hmuWrapperID',
                    'hmu_ajax_filter',
                    'hmu_dashboard_index',
                    'hmu_dashboard',
                ),
            'custom_class' =>
                array('class attribute',
                    'hmuCustomCLass',
                    'hmu_ajax_filter',
                    'hmu_dashboard_index',
                    'hmu_dashboard',
                ),
            'use_select' =>
                array('Use select box',
                    'hmuSelectBox',
                    'hmu_ajax_filter',
                    'hmu_dashboard_index',
                    'hmu_dashboard',
                ),
            'use_checkbox' =>
                array('Remove checkboxes',
                    'hmuCheckboxLabel',
                    'hmu_ajax_filter',
                    'hmu_dashboard_index',
                    'hmu_dashboard',
                ),
            'hide_parent' =>
                array('Hide categories parents',
                    'hmuHideParents',
                    'hmu_ajax_filter',
                    'hmu_dashboard_index',
                    'hmu_dashboard',
                ),
            'remove_loader' =>
                array('Remove ajax spinner',
                    'hmuRemoveLoader',
                    'hmu_ajax_filter',
                    'hmu_dashboard_index',
                    'hmu_dashboard',
                ),
            'custom-css' =>
                array('Insert your Custom CSS',
                    'hmuCssCallback',
                    'hmu_ajax_filter',
                    'hmu_dashboard_index',
                    'hmu_dashboard',
                ),


        );
    }

    public function setFields()
    {
        $args = array ();
        foreach ($this->dahboardFields() as $id_dash => $dashtitle_callback) {
            $args[] = array (
                'id' => $id_dash,
                'title' => $dashtitle_callback[0],
                'callback' => array( $this->fields_callbacks, $dashtitle_callback[1] ),
                'page' => $dashtitle_callback[2],
                'section' => $dashtitle_callback[3],
                    'args' => array(
                        'option_name' => $dashtitle_callback[4],
                        'label_for' => $id_dash,
                        'class' => 'hmu-upload'
                    )
                );
        }
        $this->settings->setFields($args);
    }
}
