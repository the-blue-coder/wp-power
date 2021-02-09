<?php

/**
 * Taxonomy plural name
 */
// add_action('init', function () {
//     $labels = [
//         'name' => _x('Plural', env('APP_TD')),
//         'singular_name' => _x('Singular', env('APP_TD')),
//         'add_new_item' => __('Add new singular', env('APP_TD')),
//         'new_item_name' => __('New singular_name', env('APP_TD')),
//         'edit_item' => __('Edit singular', env('APP_TD')), 
//         'update_item' => __('Update singular', env('APP_TD')),
//         'all_items' => __('All plural', env('APP_TD')),
//         'search_items' =>  __('Search plural', env('APP_TD')),
//         'menu_name' => __('Plural', env('APP_TD'))
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
//             'rewrite' => ['slug' => 'plural-slug']
//         ]
//     );
// });