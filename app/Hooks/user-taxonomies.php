<?php

/**
 * NB: The following codes require the "LH User Taxonomies" WordPress plugin found at https://wordpress.org/plugins/lh-user-taxonomies/
 */

/**
 * User taxonomy plural name
 */
// add_action('init', function () {
//     $labels = [
//         'name' => __('Plural', WP_POWER_TEXT_DOMAIN),
//         'singular_name' => __('Singular', WP_POWER_TEXT_DOMAIN),
//         'menu_name' => __('Menu name', WP_POWER_TEXT_DOMAIN),
//         'add_new_item' => __('Add new singular', WP_POWER_TEXT_DOMAIN),
//         'new_item_name' => __('New singular name', WP_POWER_TEXT_DOMAIN),
//         'view' => __('Voir type d\'intervenant', WP_POWER_TEXT_DOMAIN),
//         'view_item' => __('Voir type d\'intervenant', WP_POWER_TEXT_DOMAIN),
//         'edit_item' => __('Edit singular', WP_POWER_TEXT_DOMAIN),
//         'update_item' => __('Update singular', WP_POWER_TEXT_DOMAIN),
//         'all_items' => __('All plural', WP_POWER_TEXT_DOMAIN),
//         'popular_items' => __('Popular plural', WP_POWER_TEXT_DOMAIN),
//         'search_items' => __('Search plural', WP_POWER_TEXT_DOMAIN),
//         'separate_items_with_commas' => __('Separate plural with commas', WP_POWER_TEXT_DOMAIN),
//         'add_or_remove_items' => __('Add or remove plural', WP_POWER_TEXT_DOMAIN),
//         'choose_from_most_used' => __('Choose from the most popular plural', WP_POWER_TEXT_DOMAIN),
//         'parent_item' => __('Type d\'intervenant parent', WP_POWER_TEXT_DOMAIN),
//         'parent_item_colon' => __('Type d\'intervenant parent :', WP_POWER_TEXT_DOMAIN)
//     ];

//     $capabilities = [
//         'manage_terms' => 'edit_users',
//         'edit_terms' => 'edit_users',
//         'delete_terms' => 'edit_users',
//         'assign_terms' => 'read'
//     ];

//     $rewrite = [
//         'with_front' => true,
//         'slug' => 'plural-slug'
//     ];

//     register_taxonomy(
//         'plural-slug', 
//         'user', 
//         [
//             'labels' => $labels,
//             'public' =>true,
//             'hierarchical' => true,
//             'single_value' => false,
//             'show_admin_column' => false,
//             'capabilities' => $capabilities,
//             'rewrite' => $rewrite
//         ]
//     );
// });