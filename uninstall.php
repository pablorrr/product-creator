<?php

//https://developer.wordpress.org/plugins/plugin-basics/uninstall-methods/

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

$opt_ver  = 'product-creator-version';

delete_option($opt_ver);

// for site options in Multisite
delete_site_option($opt_ver);

// drop a custom database table
global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}product_creator");


