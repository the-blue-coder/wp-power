<?php

/**
 * Taxonomy plural name
 */
// add_action('init', function () {
//     $labels = [
//         'name' => __('Plural', WP_POWER_TEXT_DOMAIN),
//         'singular_name' => __('Singular', WP_POWER_TEXT_DOMAIN),
//         'add_new_item' => __('Add new singular', WP_POWER_TEXT_DOMAIN),
//         'new_item_name' => __('New singular_name', WP_POWER_TEXT_DOMAIN),
//         'view' => __('View singular', WP_POWER_TEXT_DOMAIN),
//         'view_item' => __('View singular', WP_POWER_TEXT_DOMAIN),
//         'edit_item' => __('Edit singular', WP_POWER_TEXT_DOMAIN), 
//         'update_item' => __('Update singular', WP_POWER_TEXT_DOMAIN),
//         'all_items' => __('All plural', WP_POWER_TEXT_DOMAIN),
//         'search_items' =>  __('Search plural', WP_POWER_TEXT_DOMAIN),
//         'menu_name' => __('Plural', WP_POWER_TEXT_DOMAIN),
//         'parent_item' => __('Parent singular', WP_POWER_TEXT_DOMAIN),
//         'parent_item_colon' => __('Parent singular : ', WP_POWER_TEXT_DOMAIN)
//     ];    

//     $rewrite = [
//         'slug' => 'singular-slug'
//     ];

//     register_taxonomy(
//         'plural-slug',
//         [
//             'custom-post-type-slugs'
//         ], 
//         [
//             'labels' => $labels,
//             'hierarchical' => true,
//             'show_ui' => true,
//             'show_in_rest' => true,
//             'show_admin_column' => true,
//             'query_var' => true,
//             'rewrite' => $rewrite
//         ]
//     );
// });