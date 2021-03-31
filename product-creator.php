<?php
/*
Plugin Name: Woo Product Creator
Plugin URI:
Description: Create Woocommerce Product
Author: Pavel Cal
Version: 2.0.0
Text Domain: cwp
 Author URI: https://websitecreator.cba.pl
*/


use ProductCreator\libraries\ProductCreator;
use ProductCreator\libraries\ProductCreator_Updater;


//TODO: WALIDACJA FORMULARZA !!!!

if (!defined('ABSPATH')) exit;


require "vendor/autoload.php";

//use WP plugin update ver
$updater = new ProductCreator_Updater(__FILE__);
$updater->set_username('pablorrr');
$updater->set_repository('product-creator');
$updater->initialize();
//init plugin
$ProductCreator = new ProductCreator();
