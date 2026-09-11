<?php
/**
 * Plugin Name: Hello WP Plugin
 * Description: "Hello WP at php" を表示するサンプルプラグイン
 * Version: 1.0.0
 * Author: Your Name
 */

function hello_wp_at_php_shortcode_() {
    return '<p>Hello WP at php</p>';
}

add_shortcode(
    'hello_wp_at_php_',
    'hello_wp_at_php_shortcode_'
);