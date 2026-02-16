<?php
/**
 * Core Plugin Class
 *
 * Handles plugin initialization, Elementor integration, and lifecycle hooks.
 *
 * @package NebulaForgeAddon
 * @since   0.1.0
 */

namespace NebulaForgeAddon;

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Plugin as ElementorPlugin;
use Elementor\Widgets_Manager;
use NebulaForgeAddon\Admin\Admin_Manager;
use NebulaForgeAddon\Admin\Widget_Registry;
use NebulaForgeAddon\Extensions\Custom_Fonts;
use NebulaForgeAddon\Extensions\Display_Conditions;
use NebulaForgeAddon\Extensions\Tooltip_Extension;
use NebulaForgeAddon\Extensions\Wrapper_Link;
use NebulaForgeAddon\Extensions\Form_Handler;

/**
 * Class Plugin
 *
 * Main plugin class implementing singleton pattern.
 */
final class Plugin
{
    /**
     * Minimum PHP version required.
     */
    private const MIN_PHP_VERSION = '7.4';

    /**
     * Minimum WordPress version required.
     */
    private const MIN_WP_VERSION = '6.2';

    /**
     * Minimum Elementor version required.
     */
    private const MIN_ELEMENTOR_VERSION = '3.20.0';

    /**
     * Singleton instance.
     */
    private static ?self $instance = null;

    /**
     * Plugin file path.
     */
    private string $plugin_file = '';

    /**
     * Whether the plugin is ready (all checks passed).
     */
    private bool $is_ready = false;

    /**
     * Get singleton instance.
     *
     * @return self
     */
    public static function instance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Private constructor to prevent direct instantiation.
     */
    private function __construct()
    {
        // Singleton.
    }

    /**
     * Register plugin with WordPress.
     *
     * @param string $plugin_file Main plugin file path.
     */
    public function register(string $plugin_file): void
    {
        $this->plugin_file = $plugin_file;

        // Lifecycle hooks.
        register_activation_hook($plugin_file, [$this, 'on_activation']);
        register_deactivation_hook($plugin_file, [$this, 'on_deactivation']);

        // Core initialization.
        add_action('plugins_loaded', [$this, 'on_plugins_loaded']);

        // Admin initialization.
        if (is_admin()) {
            add_action('plugins_loaded', [$this, 'init_admin'], 15);
        }
    }

    /**
     * Handle plugins_loaded hook.
     */
    public function on_plugins_loaded(): void
    {
        // Translations for plugins hosted on WordPress.org are loaded automatically.
        // Historically plugins called load_plugin_textdomain() here, but since WP 4.6
        // this is not required and is discouraged. Keeping translations in the
        // `languages` folder is recommended.

        // Check requirements.
        if (!$this->passes_requirements('bootstrap')) {
            add_action('admin_notices', [$this, 'render_requirements_notice']);
            return;
        }

        if (did_action('elementor/loaded')) {
            $this->on_elementor_loaded();
            return;
        }

        add_action('elementor/loaded', [$this, 'on_elementor_loaded']);
    }

    /**
     * Initialize Elementor-dependent hooks after Elementor has loaded.
     */
    public function on_elementor_loaded(): void
    {
        if ($this->is_ready) {
            return;
        }

        if (!$this->passes_requirements('elementor')) {
            add_action('admin_notices', [$this, 'render_requirements_notice']);
            return;
        }

        $this->is_ready = true;

        // Register Elementor hooks.
        add_action('elementor/elements/categories_registered', [$this, 'register_widget_category']);
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        add_action('elementor/frontend/after_register_styles', [$this, 'register_frontend_styles']);
        add_action('elementor/frontend/after_register_scripts', [$this, 'register_frontend_scripts']);

        // Initialize extensions.
        $this->init_extensions();
    }

    /**
     * Initialize pro extensions.
     */
    private function init_extensions(): void
    {
        $enabled = Admin_Manager::get_enabled_extensions();

        if (in_array('display_conditions', $enabled, true)) {
            Display_Conditions::init();
        }
        if (in_array('custom_fonts', $enabled, true)) {
            Custom_Fonts::init();
        }
        if (in_array('tooltip', $enabled, true)) {
            Tooltip_Extension::init();
        }
        if (in_array('wrapper_link', $enabled, true)) {
            Wrapper_Link::init();
        }

        // Form handler is always active (not a toggle-able extension).
        Form_Handler::init();
    }

    /**
     * Initialize admin functionality.
     */
    public function init_admin(): void
    {
        Admin_Manager::instance()->init();
    }

    /**
     * Handle plugin activation.
     */
    public function on_activation(): void
    {
        if (!$this->passes_requirements('activation')) {
            deactivate_plugins(plugin_basename($this->plugin_file));
            wp_die(
                esc_html__(
                    'Nebula Forge Elementor Addons requires Elementor and minimum versions of WordPress and PHP. Please install/activate Elementor and ensure your site meets the plugin requirements, then try again.',
                    'nebula-forge-addons-for-elementor'
                ),
                esc_html__('Plugin Activation Error', 'nebula-forge-addons-for-elementor'),
                ['back_link' => true]
            );
        }

        // Set flag for welcome page redirect.
        Admin_Manager::set_activation_redirect();
    }

    /**
     * Handle plugin deactivation.
     */
    public function on_deactivation(): void
    {
        // Clean up transients if needed.
    }

    /**
     * Register Elementor widgets.
     *
     * @param Widgets_Manager $widgets_manager Elementor widgets manager.
     */
    public function register_widgets(Widgets_Manager $widgets_manager): void
    {
        if (!$this->is_ready) {
            return;
        }

        $enabled_widgets = Admin_Manager::get_enabled_widgets();
        $available_widgets = Widget_Registry::get_available_widgets();

        foreach ($available_widgets as $widget_key => $widget_data) {
            if (in_array($widget_key, $enabled_widgets, true)) {
                $widget = Widget_Registry::create_widget($widget_key);
                if ($widget !== null) {
                    $widgets_manager->register($widget);
                }
            }
        }
    }

    /**
     * Register custom widget category.
     *
     * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
     */
    public function register_widget_category($elements_manager): void
    {
        if (!$this->is_ready) {
            return;
        }

        $elements_manager->add_category(
            'nebula-forge',
            [
                'title' => esc_html__('Nebula Forge', 'nebula-forge-addons-for-elementor'),
                'icon'  => 'fa fa-star',
            ]
        );
    }

    /**
     * Enqueue frontend assets.
     */
    public function register_frontend_styles(): void
    {
        if (!$this->is_ready) {
            return;
        }

        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        wp_register_style(
            'nebula-forge-elementor-addon-frontend',
            NEBULA_FORGE_ADDON_URL . 'assets/css/frontend' . $suffix . '.css',
            [],
            NEBULA_FORGE_ADDON_VERSION
        );
    }

    /**
     * Register frontend scripts for Elementor widgets.
     */
    public function register_frontend_scripts(): void
    {
        if (!$this->is_ready) {
            return;
        }

        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        wp_register_script(
            'nebula-forge-elementor-addon-frontend',
            NEBULA_FORGE_ADDON_URL . 'assets/js/frontend' . $suffix . '.js',
            ['elementor-frontend', 'jquery'],
            NEBULA_FORGE_ADDON_VERSION,
            true
        );
    }

    /**
     * Render requirements notice in admin.
     */
    public function render_requirements_notice(): void
    {
        if (!current_user_can('activate_plugins')) {
            return;
        }

        $screen = get_current_screen();
        if ($screen && 'plugins' !== $screen->id) {
            return;
        }

        printf(
            '<div class="notice notice-error"><p>%s</p></div>',
            esc_html__(
                'Nebula Forge Elementor Addons requires Elementor, WordPress 6.2+, and PHP 7.4+. Please install/activate Elementor and verify your site meets the requirements.',
                'nebula-forge-addons-for-elementor'
            )
        );
    }

    /**
     * Check if plugin requirements are met.
     *
     * @param string $context Check context ('activation', 'bootstrap', or 'elementor').
     * @return bool
     */
    private function passes_requirements(string $context = 'elementor'): bool
    {
        global $wp_version;

        // PHP version check.
        if (version_compare(PHP_VERSION, self::MIN_PHP_VERSION, '<')) {
            return false;
        }

        // WordPress version check.
        if (version_compare($wp_version, self::MIN_WP_VERSION, '<')) {
            return false;
        }

        // Elementor activation check (different for activation vs runtime).
        if ('activation' === $context) {
            return $this->is_elementor_active_for_activation();
        }

        if ('bootstrap' === $context) {
            return $this->is_elementor_plugin_active();
        }

        // Elementor loaded/runtime: Check if Elementor is loaded.
        if (!did_action('elementor/loaded')) {
            return false;
        }

        if (!defined('ELEMENTOR_VERSION')) {
            return false;
        }

        // Elementor version check.
        if (version_compare(ELEMENTOR_VERSION, self::MIN_ELEMENTOR_VERSION, '<')) {
            return false;
        }

        return true;
    }

    /**
     * Check if Elementor plugin is active before it loads.
     *
     * @return bool
     */
    private function is_elementor_plugin_active(): bool
    {
        if (defined('ELEMENTOR_VERSION')) {
            return true;
        }

        if (!function_exists('is_plugin_active')) {
            $plugin_file = ABSPATH . 'wp-admin/includes/plugin.php';
            if (file_exists($plugin_file)) {
                include_once $plugin_file;
            }
        }

        if (function_exists('is_plugin_active')) {
            return is_plugin_active('elementor/elementor.php');
        }

        return class_exists('\\Elementor\\Plugin');
    }

    /**
     * Check if Elementor is active during plugin activation.
     *
     * @return bool
     */
    private function is_elementor_active_for_activation(): bool
    {
        if (!function_exists('is_plugin_active')) {
            $plugin_file = ABSPATH . 'wp-admin/includes/plugin.php';
            if (file_exists($plugin_file)) {
                include_once $plugin_file;
            }
        }

        if (function_exists('is_plugin_active')) {
            return is_plugin_active('elementor/elementor.php');
        }

        return class_exists('\\Elementor\\Plugin');
    }
}
