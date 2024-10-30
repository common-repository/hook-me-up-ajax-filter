<?php

/**
 * HmuShortcode class generate the shortcode for the plugin
 *
 * @package   hmu-ajax-filter
 * @author    Another Author <nourleeds@yahoo.co.uk>
 * @copyright 2018 Noureddine Latreche
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   CVS: 1.0.0
 * @link      Null
 */


namespace HmuWAF\Filter;

use HmuWAF\Filter\HmuAllTaxonomies;
use HmuWAF\Filter\HmuAjax;

class HmuShortcode
{
    public function __construct()
    {
        new HmuAjax();
        add_shortcode('HmuTaxonomies', array($this, 'hmuTaxonomyOptionShortcode'));
    }


    public function hmuTaxonomyOptionShortcode($atts)
    {

        $category = new HmuAllTaxonomies();
        $category->register();
    }
}
