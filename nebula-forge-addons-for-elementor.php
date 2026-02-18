<?php
/**
 * Plugin Name:       Nebula Forge Addons for Elementor
 * Plugin URI:        https://wordpress.org/plugins/nebula-forge-addons-for-elementor/
 * Description:       Professional Elementor widgets for any WordPress page — blogs, portfolios, landing pages, WooCommerce, and beyond.
 * Version:           0.9.2
 * Author:            Zainaster
 * Author URI:        https://profiles.wordpress.org/shoaibzain/
 * Requires Plugins:  elementor
 * Text Domain:       nebula-forge-addons-for-elementor
 * Domain Path:       /languages
 * Requires at least: 6.2
 * Requires PHP:      7.4
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package NebulaForgeAddon
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

define('NEBULA_FORGE_ADDON_VERSION', '0.9.2');
define('NEBULA_FORGE_ADDON_FILE', __FILE__);
define('NEBULA_FORGE_ADDON_BASENAME', plugin_basename(__FILE__));
define('NEBULA_FORGE_ADDON_PATH', plugin_dir_path(__FILE__));
define('NEBULA_FORGE_ADDON_URL', plugin_dir_url(__FILE__));

/**
 * Basic PSR-4 style autoloader for the plugin namespace.
 */
spl_autoload_register(static function ($class) {
    $prefix = 'NebulaForgeAddon\\';
    $base_dir = NEBULA_FORGE_ADDON_PATH . 'includes/';
    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $relative_path = str_replace('\\', DIRECTORY_SEPARATOR, $relative_class) . '.php';
    $file = $base_dir . $relative_path;

    if (file_exists($file)) {
        require $file;
    }
});

NebulaForgeAddon\Plugin::instance()->register(NEBULA_FORGE_ADDON_FILE);
