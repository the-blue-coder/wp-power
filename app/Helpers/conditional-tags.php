<?php

/**
 * Single post
 */
if (!function_exists('is_single_post')) {
    function is_single_post()
    {
        return get_post_type() === 'post';
    }
}

/**
 * Single page
 */
if (!function_exists('is_single_page')) {
    function is_single_page()
    {
        return get_post_type() === 'page';
    }
}

/**
 * Page with flexible ACF blocks
 */
if (!function_exists('is_flexible_acf_blocks_template')) {
    function is_flexible_acf_blocks_template()
    {
        return get_page_template_slug() === 'page-with-flexible-acf-blocks';
    }
}

/**
 * Blog
 */
if (!function_exists('is_blog_template')) {
    function is_blog_template()
    {
        return get_page_template_slug() === 'blog';
    }
}

/**
 * Contact
 */
if (!function_exists('is_contact_template')) {
    function is_contact_template()
    {
        return get_page_template_slug() === 'contact';
    }
}