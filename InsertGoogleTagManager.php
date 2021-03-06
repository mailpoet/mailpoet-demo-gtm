<?php

class InsertGoogleTagManager {
  public function __construct() {
    $file_data = get_file_data(__FILE__, ['Version' => 'Version']);

    // Plugin Details
    $this->plugin = new stdClass;
    $this->plugin->name = 'mailpoet-demo-gtm'; // Plugin Folder
    $this->plugin->displayName = 'Insert Google Tag Manager'; // Plugin Name
    $this->plugin->version = $file_data['Version'];
    $this->plugin->folder = plugin_dir_path(__FILE__);
    $this->plugin->url = plugin_dir_url(__FILE__);
    $this->plugin->db_welcome_dismissed_key = $this->plugin->name . '_welcome_dismissed_key';
    $this->body_open_supported = function_exists('wp_body_open') && version_compare(get_bloginfo('version'), '7.4', '>=');

    add_action('wp_head', [$this, 'printHeader']);// frontend header
    add_action('admin_head', [$this, 'printHeader']);// admin header
    add_action('wp_body_open', [$this, 'printBody'], 1);// frontend body
    add_action('wp_footer', [$this, 'printFooter']);
    add_action('admin_footer', [$this, 'printFooter']);
    add_action('wp_enqueue_scripts', [$this, 'enqueueAssets']);
    add_action('admin_enqueue_scripts', [$this, 'enqueueAssets']);
  }

  function printHeader() {
    // Ignore feed, robots or trackbacks
    if (is_feed() || is_robots() || is_trackback()) {
      return;
    }
    // Page view tracking
    // Add page data to dataLayer even though cookie consent is missing
    // so that it can be tracked after a user gives the consent in cookie banner
    echo "
<!-- Google Page Tag -->
<script> 
window.dataLayer = window.dataLayer || [];
dataLayer.push({
 'page_name': '". $this->getPageName() ."',
 'site_section': 'demo',
 'login_status': 'logged out'
});
</script>
<!-- End Google Page Tag -->";

    $cookie_consent = $_COOKIE['cookie_notice_accepted'] ?? false;
    if (!$cookie_consent) {
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
    $cookie_consent = $_COOKIE['cookie_notice_accepted'] ?? false;
    if (!$cookie_consent) {
      return;
    }
    echo '
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MX5LJ9Q" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    ';
  }

  function printFooter() {
    echo '
<div class="cookie-notice hidden" id="cookie-notice">
  <div class="container level">
    <div class="level-left">
      <p>
        Our websites use cookies. By continuing, you agree to their use.
        <a class="text-link" href="https://automattic.com/privacy/" target="blank">Our Privacy Policy</a>
        <br>
        <a class="text-link" href="#" id="deny-cookies">Deny cookies</a>
      </p>
    </div>
    <div class="level-right">
      <a class="button is-primary"
        href="#"
        id="accept-cookies">
          Accept cookies
      </a>
    </div>
  </div>
</div>
    ';
  }

  function enqueueAssets() {
    wp_register_style('mailpoet_demo_gtm_cookie_banner_css', plugin_dir_url(__FILE__) . 'css/cookie_banner.css');
    wp_enqueue_style('mailpoet_demo_gtm_cookie_banner_css');

    wp_register_script('mailpoet_demo_gtm_cookie_banner_js', plugin_dir_url(__FILE__) . 'js/cookie_banner.js');
    wp_enqueue_script('mailpoet_demo_gtm_cookie_banner_js');
  }

  function getPageName(): string {
    if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === '/') {
      return '/ga-virtual/demo/try';
    }
    if (function_exists('get_current_screen')) {
      $screen = get_current_screen();
      return "/ga-virtual/demo/" . $this->sanitizeString($screen->id);
    }
    // Fallback for unexpected cases
    return "/ga-virtual/demo/" . $this->sanitizeString($_SERVER['REQUEST_URI']);
  }

  function sanitizeString($string): string {
    return preg_replace(['/[_\s]/', '/[\&]/', '/[^[:alnum:]-]/'],['-', 'and', ''], strtolower($string));
  }
}
