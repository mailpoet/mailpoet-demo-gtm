<?php
/**
 * Plugin Name: Insert Google Tag Manager
 * Version: 1.0.0
 * Requires at least: 5.7
 * Requires PHP: 7.4
 * Tested up to: 5.7
 * Author: Automattic
 * Author URI: http://www.automattic.com
 * Description: Inserts Google Tag Manager code into the demo page
 * License: GPLv2 or later
 */

require_once __DIR__ . '/InsertGoogleTagManager.php';

new InsertGoogleTagManager();
