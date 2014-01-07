<?php
/*
Plugin Name: Eisma Subscriptions
Plugin URI:
Description:

Version: 1.0.0
Requires at least: 3.0

Author: Pronamic, StefanBoonstra
Author URI: http://pronamic.eu/

Text Domain: eisma_subscriptions
Domain Path: /languages/

License: GPL

GitHub URI:
*/

require_once dirname(__FILE__) . '/classes/EismaSubscriptions/WP/Plugin.php';
require_once dirname(__FILE__) . '/classes/EismaSubscriptions/WP/PostType/Subscriptions.php';
require_once dirname(__FILE__) . '/classes/EismaSubscriptions/WP/PostType/Slides.php';
require_once dirname(__FILE__) . '/classes/EismaSubscriptions/WP/Shortcode/Subscriptions.php';
require_once dirname(__FILE__) . '/classes/EismaSubscriptions/WP/Shortcode/Slides.php';

global $eisma_subscriptions_plugin;

$eisma_subscriptions_plugin = new EismaSubscriptions_WP_Plugin( __FILE__ );