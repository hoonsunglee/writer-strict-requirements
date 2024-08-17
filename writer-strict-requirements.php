<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Plugin Name: Writer Strict Requirements
 * Description: Prevent authors from uploading posts without fulfilling requirements.
 * Version: 1.0.0
 * Author URI: https://hoonsung.dev
 * Author: Hoonsung Lee
 * License: GPLv2 or later
 */


require_once plugin_dir_path(__FILE__) . 'src/strictreq-options-page.php';
require_once plugin_dir_path(__FILE__) . 'src/strictreq-main.php';
?>
