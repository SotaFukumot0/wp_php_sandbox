<?php

function hello_wp_at_php_shortcode() {
    return '<p>Hello WP at php</p>';
}

add_shortcode(
    'hello_wp_at_php',
    'hello_wp_at_php_shortcode'
);