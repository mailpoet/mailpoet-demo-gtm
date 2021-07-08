<?php
/**
 * Plugin Name: Insert Google Tracking Manager
 * Version: 1.0.0
 * Requires at least: 5.7
 * Requires PHP: 7.4
 * Tested up to: 5.7
 * Author: Automattic
 * Author URI: http://www.automattic.com
 * Description: Inserts Google Tracking Manager code into the demo page
 * License: GPLv2 or later
 */

class InsertGoogleTrackingManager {
  public function __construct() {
    $file_data = get_file_data(__FILE__, ['Version' => 'Version']);

    // Plugin Details
    $this->plugin = new stdClass;
    $this->plugin->name = 'mailpoet-demo-gtm'; // Plugin Folder
    $this->plugin->displayName = 'Insert Google Tracking Manager'; // Plugin Name
    $this->plugin->version = $file_data['Version'];
    $this->plugin->folder = plugin_dir_path(__FILE__);
    $this->plugin->url = plugin_dir_url(__FILE__);
    $this->plugin->db_welcome_dismissed_key = $this->plugin->name . '_welcome_dismissed_key';
    $this->body_open_supported = function_exists('wp_body_open') && version_compare(get_bloginfo('version'), '7.4', '>=');

    add_action('wp_head', [$this, 'printHeader']);// frontend header
    add_action('admin_head', [$this, 'printHeader']);// admin header
    add_action('wp_body_open', [$this, 'printBody'], 1);// frontend body
  }

  function printHeader() {
    // Ignore admin, feed, robots or trackbacks
    if (is_feed() || is_robots() || is_trackback()) {
      return;
    }
    echo "
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MX5LJ9Q');</script>
<!-- End Google Tag Manager -->
    ";
  }

  function printBody() {
    // Ignore admin, feed, robots or trackbacks
    if (is_feed() || is_robots() || is_trackback()) {
      return;
    }
    echo '
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MX5LJ9Q" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    ';
  }
}

new InsertGoogleTrackingManager();
