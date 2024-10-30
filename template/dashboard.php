<?php

use HmuWAF\Base\BaseController;
$base = new BaseController();

?>
<h1><?php echo esc_html(get_admin_page_title()); ?> </h1>

<div class="<?php echo esc_attr('wrap'); ?>">
    <?php settings_errors(); ?>
    <?php
    $active_tab = '';
    if (isset($_GET['tab'])) {
        $active_tab = sanitize_text_field($_GET['tab']);
    }
    ?>


    <h2 class="<?php echo esc_attr('nav-tab-wrapper'); ?>">
        <a href="?page=hmu_ajax_filter&tab=taxonomy_settings"
           class="<?php echo esc_attr('nav-tab'); ?> <?php echo (sanitize_text_field($_GET['tab']) == '' || sanitize_text_field($_GET['tab']) == 'taxonomy_settings')
               ? esc_attr('tab-active') : ''; ?>
        <?php echo ($_GET['tab'] == '') ? $_GET['tab'] : ''; ?>"><?php echo esc_html('Taxonomy settings') ?></a>
        <a href=<?php echo esc_url("?page=hmu_ajax_filter&tab=global_settings"); ?>
           class="<?php echo esc_attr('nav-tab') ?> <?php echo ($_GET['tab'] == 'global_settings')
               ? esc_attr('tab-active') : ''; ?>"><?php echo esc_html('Global settings') ?></a>
    </h2>

    <div class="<?php echo esc_attr('container'); ?>">
        <?php if ($active_tab == 'global_settings') : ?>
            <form method="post" class="hmu-general-form" action="options.php">
                <?php
                settings_fields('hmu_dashboard_options_group');
                do_settings_sections('hmu_ajax_filter');
                submit_button('Save Settings', 'hmu-btn hmu-primary', 'btnSubmit');
                ?>
            </form>
        <?php endif; ?>

        <?php if ($active_tab == 'taxonomy_settings' || $active_tab == '') : ?>
                <?php
                if (isset($_POST['hmu_ajax_filter_meta_nonce'])
                    && wp_verify_nonce($_POST['hmu_ajax_filter_meta_nonce'], 'hmu_ajax_filter_form_nonce')) {
                    unset($_POST['action'], $_POST['hmu_ajax_filter_meta_nonce'], $_POST['submit'], $_POST['parent']);
                    update_option('hmu_ajax_filter', $base::hmuSanitizeArray($_POST));
                }
                if (isset($_GET['reset']) && $_GET['reset'] == 'true') {
                    update_option('hmu_ajax_filter', array());
                    wp_redirect(admin_url('admin.php?page=hmu_ajax_filter'));
                }
                ?>
        <p><?php echo esc_html('Choose categories you want to filter by:') ?></p>
        <div class="<?php echo esc_attr('block--shop_filter') ?>">
            <div class="<?php echo esc_attr('block--shop_filter_attributes'); ?>" >
                <form action="<?php echo esc_url(admin_url('admin.php?page=hmu_ajax_filter')); ?>" method="post"
                      id="<?php echo esc_attr('nds_add_user_meta_form'); ?>">
                    <input type="hidden" name="action" value="hmu_form_response">
                    <input type="hidden" name="hmu_ajax_filter_meta_nonce"
                           value="<?php echo wp_create_nonce('hmu_ajax_filter_form_nonce') ?>"/>
                    <?php
                    $option_terms = get_option('hmu_ajax_filter') ? get_option('hmu_ajax_filter') : array();
                    $taxonomies = get_taxonomies();
                    $taxonomy_object_clean = array();
                    $taxonomy_objects_all = get_object_taxonomies('product', 'names');
                    $taxonomy_objects = array_diff(
                        $taxonomy_objects_all,
                        ["product_type", "product_visibility", "product_tag", "product_shipping_class"]
                    );
                    foreach ($taxonomy_objects as $taxonomy_object) {
                        if (strpos($taxonomy_object, 'pa_') === false) {
                            $taxonomy_object_clean [] = $taxonomy_object;
                        }

                    }
                    foreach ($taxonomy_objects as $tax) {
                        echo '<div class="wrapper ' . $tax . '-wrapper">';
                        echo '<div class="'.esc_attr('tax-parent').'">
                              <label class="'.esc_attr('taxonomy-label parent-label').'"><strong>' . $tax . '</strong></label>';
                        echo '<input type="checkbox" name="'.esc_attr('parent').'" class="'.esc_attr('parent-checkbox').'" data-class="' . $tax . '" >
                              </div>';
                        $terms = get_terms($tax, 'orderby=count&hide_empty=1');
                        if (!empty($terms) && !is_wp_error($terms)) {
                            foreach ($terms as $term) { ?>
                                <div class="tax-parent">
                                    <label class="taxonomy-label" for=""><?php echo $term->name; ?></label>
                                    <input
                                            class="<?php echo $tax ?> taxonomy-checkbox"
                                            data-value=" <?php echo $term->term_id; ?> "
                                            type="checkbox"
                                            name="<?php echo $tax . '[' . $base::hmuSeoUrl($term->name) . ']'; ?>"
                                        <?php foreach ($option_terms as $key => $option_term) {
                                            echo array_key_exists(
                                                $base::hmuSeoUrl($term->name),
                                                $option_term
                                            )
                                                ? 'value="' .$term->term_id . '"' : '';
                                            echo array_key_exists(
                                                $base::hmuSeoUrl($term->name),
                                                $option_term
                                            ) ? 'checked' : '';
                                        } ?>
                                    />

                                    <?php
                                    $chilterms = get_terms(
                                        $tax,
                                        array('parent' => $term->term_id, 'orderby' => 'slug', 'hide_empty' => false)
                                    );
                                    if (!empty($chilterms) && !is_wp_error($chilterms)) {
                                        foreach ($chilterms as $childterm) {
                                            //  var_dump($childterm);
                                            ?>
                                            <div class="<?php echo esc_attr('tax-children'); ?>">
                                                <label class="<?php echo esc_attr('taxonomy-label'); ?>"
                                                       for=""><?php echo $childterm->name; ?></label>
                                                <input
                                                        class="<?php echo $tax; ?> taxonomy-checkbox"
                                                        type="<?php echo esc_attr('checkbox')?>"
                                                        data-value=" <?php echo $childterm->term_id; ?> "
                                                        name="<?php
                                                        echo $base::hmuSeoUrl($term->name)
                                                            . '[' . $base::hmuSeoUrl($childterm->name) . ']'; ?>"
                                                    <?php foreach ($option_terms as $key => $option_term) {
                                                        echo array_key_exists(
                                                            $base::hmuSeoUrl($childterm->name),
                                                            $option_term
                                                        ) ? 'value="' . $childterm->name . '"' : '';
                                                        echo array_key_exists(
                                                            $base::hmuSeoUrl($childterm->name),
                                                            $option_term
                                                        ) ? 'checked' : '';
                                                    } ?>
                                                />
                                            </div>


                                            <?php

                                        }
                                    } ?>
                                </div>

                                <?php
                            }

                        }
                        echo ' </div>';
                    } ?>


                    <input type="submit" value="submit" name="submit">
                </form>


                </form>
                <div class="<?php echo esc_attr('hmu_delete_wrapper'); ?>">
                    <a class="<?php echo esc_attr('hmu_delete'); ?>"
                       href="<?php echo esc_url(admin_url('admin.php?page=hmu_ajax_filter&reset=true')); ?>">
                        <?php echo esc_html('Reset to default'); ?></a>
                </div>
            </div>


            <script>
                jQuery(function ($) {
                    $('.hmu_delete').on('click', function (e) {

                        let r = confirm("Are you sure you want to rest settings?");
                        if (r === false) {
                            e.preventDefault();
                        }
                    });
                    $('.taxonomy-checkbox').on('change', function () {
                        var value = $(this).attr('data-value');
                        if ($(this).is(':checked')) {
                            $(this).attr('value', value)
                        } else {
                            $(this).attr('value', 0)
                        }
                        console.log($(this).attr('value'));
                    })

                    $('.parent-checkbox').on('change', function () {
                        var dataClass = $(this).attr('data-class');
                        console.log(dataClass);
                        if ($(this).is(':checked')) {
                            $('.' + dataClass).prop('checked', true);

                        } else {
                            $('.' + dataClass).prop('checked', false);

                        }
                    });
                })
            </script>
        <?php endif; ?>

        </div>
    </div>
