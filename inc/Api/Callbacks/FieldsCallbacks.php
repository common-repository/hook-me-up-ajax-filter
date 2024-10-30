<?php

/**
 * FieldsCallbacks class handles fields callbacks and sanitization
 *
 * @package   hmu-ajax-filter
 * @author    Another Author <nourleeds@yahoo.co.uk>
 * @copyright 2018 Noureddine Latreche
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   CVS: 1.0.0
 * @link      Null
 */

namespace HmuWAF\Api\Callbacks;

use HmuWAF\Base\BaseController;

class FieldsCallbacks extends BaseController
{

    public function sanitizeCallback($options)
    {
        return BaseController::hmuSanitizeArray($options);
    }
    public function dashboardSectionManager()
    {
        echo esc_html('Dashboard Control');
    }

    public function hmuWrapperID($args)
    {
        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $value = get_option($option_name);
        $isvalue = isset($value[$name]) ? $value[$name] : '';
        echo '<input type="text" class="regular-text hmu-input" name="' . $option_name . '[' . $name . ']"  value="' . $isvalue . '"  placeholder="class or id wrapping the products list">';
    }

    public function hmuCustomClass($args)
    {
        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $value = get_option($option_name);
        $isvalue = isset($value[$name]) ? $value[$name] : '';
        echo '<input id="' . $name . '" type="text" class="' . $classes . ' regular-text hmu-input" name="' . $option_name . '[' . $name . ']"  value="' . $isvalue . '"  placeholder="Custom class">';
    }

    public function hmuCheckboxLabel($args)
    {

        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $checkbox = get_option($option_name);
        $checked = isset($checkbox[$name]) ? ($checkbox[$name] ? true : false) : false;

        echo '<div id="toggles">
                        <input id="hmuCheckbox" class="ios-toggle" type="checkbox" name="' . $option_name . '[' . $name . ']" value="1"   ' . ($checked ? "checked" : "") . '>
                        <label for="hmuCheckbox" class="checkbox-label" data-off="off" data-on="on">
                        </label></div>';
    }
    public function hmuSelectBox($args)
    {

        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $checkbox = get_option($option_name);
        $checked = isset($checkbox[$name]) ? ($checkbox[$name] ? true : false) : false;

        echo '<div id="toggles">
                        <input id="hmuCheckbox" class="ios-toggle" type="checkbox" name="' . $option_name . '[' . $name . ']" value="1"   ' . ($checked ? "checked" : "") . '>
                        <label for="hmuCheckbox" class="checkbox-label" data-off="off" data-on="on">
                        </label></div>';
    }

    public function hmuHideParents($args)
    {

        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $checkbox = get_option($option_name);
        $checked = isset($checkbox[$name]) ? ($checkbox[$name] ? true : false) : false;

        echo '<div id="toggles">
                <input id="hmuHideParent" class="ios-toggle" type="checkbox" name="' . $option_name . '[' . $name . ']" value="1"   ' . ($checked ? "checked" : "") . '>
                <label for="hmuHideParent" class="checkbox-label" data-off="off" data-on="on">
                </label></div>';
    }

    public function hmuRemoveLoader($args)
    {

        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $checkbox = get_option($option_name);
        $checked = isset($checkbox[$name]) ? ($checkbox[$name] ? true : false) : false;

        echo '<div id="toggles">
                <input id="hmuHideLoader" class="ios-toggle" type="checkbox" name="' . $option_name . '[' . $name . ']" value="1"   ' . ($checked ? "checked" : "") . '>
                <label for="hmuHideLoader" class="checkbox-label" data-off="off" data-on="on">
                </label></div>';
    }

    public function hmuCssCallback($args)
    {
        $name = $args['label_for'];
        $classes = $args['class'];
        $option_name = $args['option_name'];
        $value = get_option($option_name);
        $isvalue = isset($value[$name]) ? $value[$name] : '';
        echo '<div id="customCss">' . $isvalue . '</div><textarea id="hmu_css" name="' . $option_name . '[' . $name . ']" style="display: none;">' . $isvalue . '</textarea>';
    }
}
