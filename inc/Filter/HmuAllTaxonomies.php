<?php

/**
 * HmuAllTaxonomies class generate the terms and the html
 *
 * @package   hmu-ajax-filter
 * @author    Another Author <nourleeds@yahoo.co.uk>
 * @copyright 2018 Noureddine Latreche
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version   CVS: 1.0.0
 * @link      Null
 */


namespace HmuWAF\Filter;

use HmuWAF\Base\BaseController;

class HmuAllTaxonomies extends BaseController
{


    public function register()
    {
        /*
         * 1- get the args you want to pass to the post loop
         * 2- get the result from the post loop
         * 3- pass the result to return html output
         */
        $args = $this->hmuPostArgs();
        $result = $this->hmuPostTermsQuery($args);
        $this->hmuTaxonomyOptionToHtml($result);
    }
    public function hmuPostTermsQuery($args)
    {
        /* when using namespace dont forget to add \ to WP_Query */
        $query = new \WP_Query($args);
        if ($query->have_posts()) :
            while ($query->have_posts()) {
                $query->the_post();

                $post_taxonomies = get_post_taxonomies(get_the_ID());
                foreach ($post_taxonomies as $post_taxonomy) {
                    // if( ! in_array( $post_taxonomy, $unique_tax ) ):
                    // array_push($unique_tax, $post_taxonomy);
                    $term_list = wp_get_post_terms(get_the_ID(), $post_taxonomy, array("fields" => "all"));

                    foreach ($term_list as $term) :
                        $unique_terms[$post_taxonomy][] = $term;
                    endforeach;
                }
            }
        endif;
        wp_reset_postdata();

        $result = array();
        if (isset($unique_terms)) {
            foreach ($unique_terms as $key => $unique_term) {
                foreach ($unique_term as $term) {
                    $result [$term->taxonomy][] = $term->name;
                }
            }
        }

        return $result;
    }

    public function hmuPostArgs()
    {
        $term_list = array();
        @$obj = get_queried_object();
        @$obj_slug = $obj->slug;

        @$obj_tax = get_terms($obj->taxonomy, 'orderby=count');

        // $obj_term_id = $obj_term->term_id;
        @ $taxonomy = $obj->taxonomy; // this is the name of the taxonomy

        if (is_shop()) {
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => -1,
                'orderby' => array(
                    'ID' => 'DESC',
                ),

            );
        } else {
            $args = array(
                'posts_per_page' => -1,
                'post_type' => 'product',
                'tax_query' => array(
                    'relation' => 'AND',
                    array(
                        'taxonomy' => $taxonomy,
                        'field' => 'term_id',
                        'terms' => $obj->term_id,
                    )
                )
            );
        }
        return $args;
    }


    /*
     * hmu filter output html
     */
    public function hmuTaxonomyOptionToHtml($result)
    {
        @$obj = get_queried_object();
        @$obj_slug = $obj->slug;
        // $obj_term_id = $obj_term->term_id;
        @$taxonomy = $obj->taxonomy;

        $taxonomies = $result;

        $option_terms = get_option('hmu_ajax_filter') ? get_option('hmu_ajax_filter') : array();
        //for tax
        $option_keys =  array_keys($option_terms);
        $option_values = array_values($option_terms);
        $new_option_values = array();
        foreach ($option_values as $subArray) {
            foreach ($subArray as $key => $val) {
                $new_option_values [] = $key;
            }
        }

        $checkbox = '0';
        $hide_parent = '0';
        $custom_css = '';
        $removeLoader = '1';
        $select = '';
        if ($dashboard = get_option('hmu_dashboard')) {
            $checkbox = array_key_exists('use_checkbox', $dashboard)  ? $dashboard['use_checkbox'] : '0';
            $hide_parent = array_key_exists('hide_parent', $dashboard) ? $dashboard['hide_parent'] : '0' ;
            $custom_css = array_key_exists('custom-css', $dashboard) ? $dashboard['custom-css'] : '' ;
            $removeLoader = array_key_exists('remove_loader', $dashboard) ? $dashboard['remove_loader'] : '' ;
            $select = array_key_exists('use_select', $dashboard)
                ?  $dashboard["use_select"] : '';
            //  var_dump($dashboard);
        }
        echo '<div class="hmu-filter-ajax">';
        if ($custom_css !=='') {
            echo '<style>';
            echo $custom_css;
            echo '</style>';
        }


        if (!empty($taxonomies)) : ?>
            <?php if ($select == '') : ?>
            <div class="hmu-filter">
                <div class="block--shop_filter_attributes" id="hmuFilter">
                    <?php if ($removeLoader == '') { ?>
                    <div class="hmu-loader" id="hmuloader-2">

                    </div>
                    <?php } ?>
                    <?php
                    foreach ($taxonomies as $key => $b) {
                        ?>
                        <?php
                        if ($hide_parent == '0') :
                            if (in_array($key, $option_keys)) :
                                echo '<div class="hmu-container">
                                      <div class="hmu-row">
                                      <div class="hmucol-md-10 hmucol-sm-10">
                                      <h2 class="text-left hmu-parent-title">' . self::hmuCleanString($key) . '</h2>
                                      </div>
                                      </div></div>';
                            endif; // check key in option
                        endif; // hide parent
                        foreach (array_count_values($b) as $child => $count) {
                            if (in_array(self::hmuSeoUrl($child), $new_option_values)) :
                                ?>
                            <div class="hmu-container hmu-term-container">
                                <div class="hmu-row">

                                    <div class="hmucol-md-10 hmucol-sm-10 text-left">
                                        <label class="text-left hmu-label"
                                               for="<?php echo $child; ?>"><?php echo $child; ?></label>
                                        <span>(<?php echo $count; ?>)</span>

                                    </div>

                                    <div class="
                                               <?php echo $checkbox == '1'
                                                           ? 'hmu-hide-input' : 'hmucol-md-2 hmucol-sm-2' ?>">
                                        <input
                                                id="<?php echo $child; ?>"
                                                type="checkbox"
                                                value="<?php ?>"
                                                data-term-tax="<?php echo $key ?>"
                                                data-term-slug="<?php echo self::hmuSeoUrl($child); ?>"
                                                data-term="<?php echo $child; ?>"
                                                class="hmu-<?php echo self::hmuSeoUrl($key) ?> hmu_filter_attributes"
                                                data-archive-tax="<?php echo is_shop() ? '' : $taxonomy ?>"
                                                data-archive-term="<?php echo is_shop() ? '' : $obj_slug ?>"
                                        />
                                    </div>

                                </div>
                            </div>
                                <?php
                            endif; // check for child in option
                        }

                    }
                    ?>
                </div>
            </div>
            <?php else : ?>
             <div class="hmu-filter hmu_select_wrapper">
                <div class="block--shop_filter_attributes " id="hmuFilter">
                    <?php if ($removeLoader == '') { ?>
                    <div class="hmu-loader" id="hmuloader-2">

                    </div>
                    <?php } ?>
                    <?php
                    foreach ($taxonomies as $key => $b) { ?>
                        <?php
                        if ($hide_parent == '0') :
                            if (in_array($key, $option_keys)) :
                                echo '
                                      <select class="hmu_filter_select">
                                      <option>' . $key . '</option>
                                      ';
                            endif; // check key in option
                        endif; // hide parent
                        foreach (array_count_values($b) as $child => $count) {
                            if (in_array(self::hmuSeoUrl($child), $new_option_values)) :
                                ?>
                                        <option
                                                id="<?php echo $child; ?>"
                                                type="checkbox"
                                                value="<?php ?>"
                                                data-term-tax="<?php echo $key ?>"
                                                data-term-slug="<?php echo self::hmuSeoUrl($child); ?>"
                                                data-term="<?php echo $child; ?>"
                                                class="hmu-<?php echo self::hmuSeoUrl($key) ?> hmu_filter_attributes"
                                                data-archive-tax="<?php echo is_shop() ? '' : $taxonomy ?>"
                                                data-archive-term="<?php echo is_shop() ? '' : $obj_slug ?>"
                                        >
                                            <?php echo $child; ?>(<?php echo $count; ?>)
                                        </option>

                                <?php
                            endif; // check for child in option
                        }
                        echo '</select>';
                    }
                    ?>
                </div>
            </div>
            <?php endif;
        else :
                echo 'No categories found';
        endif;
            echo '</div>';
    }
}
