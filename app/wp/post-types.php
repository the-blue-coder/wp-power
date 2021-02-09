<?php

/**
 * Post type name
 */
// add_action('init', function () {
//     $labels = [
//         'name' => __('Plural', WP_POWER_TEXT_DOMAIN),
//         'singular_name' => __('Singular', WP_POWER_TEXT_DOMAIN),
//         'add_new' => __('Add new', WP_POWER_TEXT_DOMAIN),
//         'add_new_item' => __('Add new singular', WP_POWER_TEXT_DOMAIN),
//         'edit' => __('Edit', WP_POWER_TEXT_DOMAIN),
//         'edit_item' => __('Edit singular', WP_POWER_TEXT_DOMAIN),
//         'new_item' => __('New singular', WP_POWER_TEXT_DOMAIN),
//         'view' => __('View singular', WP_POWER_TEXT_DOMAIN),
//         'view_item' => __('View singular', WP_POWER_TEXT_DOMAIN),
//         'search_items' => __('Search plural', WP_POWER_TEXT_DOMAIN),
//         'not_found' => __('No plural found', WP_POWER_TEXT_DOMAIN),
//         'not_found_in_trash' => __('No plural found in trash', WP_POWER_TEXT_DOMAIN),
//         // 'parent' => __('Parent singular', WP_POWER_TEXT_DOMAIN)
//     ];

//     $supports = [
//         'title',
//         'editor',
//         'custom-fields'
//     ];

//     register_post_type('slug', [
//         'labels' => $labels,
//         'public' => true,
//         'publicly_queryable' => true,
//         'show_ui' => true,
//         'has_archive' => true,
//         'menu_position' => 20,
//         'menu_icon' => 'dashicons-admin-post',
//         'show_in_menu' => true,
//         'exclude_from_search' => false,
//         'rewrite' => true,
//         'supports' => $supports
//     ]);
// });