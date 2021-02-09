<?php

/**
 * NB: The following codes require the "LH User Taxonomies" WordPress plugin found at https://wordpress.org/plugins/lh-user-taxonomies/
 */

/**
 * User taxonomy plural name
 */
// add_action('init', function () {
//     $labels = [
//         'name' => __('Plural', env('APP_TD')),
//         'singular_name' => __('Singular', env('APP_TD')),
//         'menu_name' => __('Menu name', env('APP_TD')),
//         'add_new_item' => __('Add new singular', env('APP_TD')),
//         'new_item_name' => __('New singular name', env('APP_TD')),
//         'edit_item' => __('Edit singular', env('APP_TD')),
//         'update_item' => __('Update singular', env('APP_TD')),
//         'all_items' => __('All plural', env('APP_TD')),
//         'popular_items' => __('Popular plural', env('APP_TD')),
//         'search_items' => __('Search plural', env('APP_TD')),
//         'separate_items_with_commas' => __('Separate plural with commas', env('APP_TD')),
//         'add_or_remove_items' => __('Add or remove plural', env('APP_TD')),
//         'choose_from_most_used' => __('Choose from the most popular plural', env('APP_TD'))
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
//             'single_value' => false,
//             'show_admin_column' => false,
//             'capabilities' => $capabilities,
//             'rewrite' => $rewrite
//         ]
//     );
// });