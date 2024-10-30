<?php

/**
 * Activate class called on activation of the plugin
 *
 * @package   hmu-ajax-filter
 * @author    Another Author <nourleeds@yahoo.co.uk>
 * @copyright 2018 Noureddine Latreche
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   CVS: 1.0.0
 * @link      Null
 */

namespace HmuWAF\Base;

class Activate
{

    public static function activate()
    {
        flush_rewrite_rules();
    }
}
