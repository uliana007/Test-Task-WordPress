<?php
if (!defined('ABSPATH')) {
    exit;
}

function basketball_wetten_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);

    register_nav_menus([
        'landing_menu' => __('Landing Menu', 'test-task-basketball'),
    ]);
}
add_action('after_setup_theme', 'basketball_wetten_setup');

function basketball_wetten_assets() {
    wp_enqueue_style(
        'test-task-basketball-style',
        get_stylesheet_uri(),
        [],
        wp_get_theme()->get('Version')
    );

    $landing_css_rel_path = '/assets/css/basketball.css';
    $landing_css_abs_path = get_template_directory() . $landing_css_rel_path;
    $landing_css_ver      = file_exists($landing_css_abs_path)
        ? (string) filemtime($landing_css_abs_path)
        : wp_get_theme()->get('Version');

    wp_enqueue_style(
        'test-task-basketball-landing',
        get_template_directory_uri() . $landing_css_rel_path,
        ['test-task-basketball-style'],
        $landing_css_ver
    );
}
add_action('wp_enqueue_scripts', 'basketball_wetten_assets');

function basketball_wetten_menu_fallback() {
    echo '<div class="nav-links">';
    echo '<a href="#grundlagen">Grundlagen</a>';
    echo '<a href="#wettarten">Wettarten</a>';
    echo '<a href="#strategien">Strategien</a>';
    echo '<a href="#tipps">Expertentipps</a>';
    echo '</div>';
}
