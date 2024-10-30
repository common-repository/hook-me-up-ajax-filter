<?php

/**
 * HmuAjax class handles the ajax call received by the js file
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
use HmuWAF\Base\BaseController;

class HmuAjax extends BaseController
{
    public function __construct()
    {

        add_action('wp_ajax_customfilter', array($this, 'hmuAjaxCall'));
        add_action('wp_ajax_nopriv_customfilter', array($this, 'hmuAjaxCall'));

        add_action('wp_ajax_hmuTerms', array($this, 'hmuTermsAjaxCall'));
        add_action('wp_ajax_nopriv_hmuTerms', array($this, 'hmuTermsAjaxCall'));
    }

    public function hmuPosts()
    {
        $nonce = $_POST['nonce'];

        if (!wp_verify_nonce($nonce, 'ajax-nonce')) {
            die('Busted!');
        }
        $cpt = 'product';
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        if (array_key_exists('page', $_POST['attributes'])) {
            $paged = $_POST['attributes']['page'];
            unset($_POST['attributes']['page']);
        }



        $args = array(
            'post_type' => $cpt,
            'posts_per_page' => -1,
            'paged' => $paged,
            'orderby' => array(
                'ID' => 'DESC',
            ),

        );

        if ($_POST['args'] == 'true') {
            $result = array();
            foreach ($_POST['attributes'] as $tax => $term) {
                $result [] = array(
                    'taxonomy' => $tax,
                    'field' => 'slug',
                    'terms' => array($term),

                );
            }
            //var_dump($result);
            $args['tax_query'] = array(
                'relation' => 'AND',
                $result
            );
        }
        return $args;
    }

    public function hmuAjaxCall()
    {
        ?>
        <div>
            <?php
            $args = $this->hmuPosts();

            /* when using namespace dont forget to add \ to WP_Query */
            $query = new \WP_Query($args);
            if ($query->have_posts()) :
                while ($query->have_posts()) {
                    $query->the_post();
                    wc_get_template_part('content', 'product');
                }
                ?>
                <p class="woocommerce-result-count" id="hmuCount" style="display:none;">
                    <?php
                    $paged = max(1, $query->get('paged'));
                    $per_page = $query->get('posts_per_page');
                    $total = $query->found_posts;
                    $first = ($per_page * $paged) - $per_page + 1;
                    $last = min($total, $query->get('posts_per_page') * $paged);

                    if ($total <= $per_page || -1 === $per_page) {
                        /* translators: %d: total results */
                        printf(
                            _n('Showing the single result', 'Showing all %d results', $total, 'woocommerce'),
                            $total
                        );
                    } else {
                        /* translators: 1: first result 2: last result 3: total results */
                        printf(
                            nx(
                                'Showing the single result',
                                'Showing %1$d&ndash;%2$d of %3$d results',
                                $total,
                                'with first and last result',
                                'woocommerce'
                            ),
                            $first,
                            $last,
                            $total
                        );
                    }
                    ?>
                </p>
                <?php if ($query->max_num_pages > 1) : ?>
                <nav class="woocommerce-pagination" id="hmuPagination" style="display: none">
                    <?php
                    echo paginate_links(array(
                        'base' => home_url('/%_%'),
                        'format' => '&paged=%#%',
                        'add_args' => false,
                        'current' => $paged,
                        'total' => $query->max_num_pages,
                        'prev_text' => '&larr;',
                        'next_text' => '&rarr;',
                        'type' => 'list',
                        'end_size' => 3,
                        'mid_size' => 3,
                    ));
                    ?>
                </nav>
                <?php endif; ?>
                <?php
                wp_reset_postdata();
            else :
                echo '<h2> No Result found</h2>';
            endif;

            die();

            ?>
        </div>
        <?php
    }

    public function hmuTermsAjaxCall()
    {
        $allTax = new HmuAllTaxonomies();
        $args = $this->hmuPosts();
        $result = $allTax->hmuPostTermsQuery($args);
        $allTax->hmuTaxonomyOptionToHtml($result);
        die();
    }
}
