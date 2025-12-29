<?php
/**
 * Admin Manager - Handles all admin functionality.
 *
 * @package NebulaForgeAddon
 * @since   0.2.0
 */

namespace NebulaForgeAddon\Admin;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Admin_Manager
 *
 * Centralizes admin menu registration and page routing.
 */
final class Admin_Manager
{
    public const MENU_SLUG_WELCOME = 'nebula-forge-addon-welcome';
    public const MENU_SLUG_SETTINGS = 'nebula-forge-addon-settings';
    public const MENU_SLUG_CHANGELOG = 'nebula-forge-addon-changelog';

    public const OPTION_ACTIVATION_REDIRECT = 'nebula_forge_addon_activation_redirect';
    public const OPTION_WIDGETS = 'nebula_forge_addon_widgets';

    private static ?self $instance = null;

    private ?Settings_Page $settings_page = null;
    private ?Welcome_Page $welcome_page = null;
    private ?Changelog_Page $changelog_page = null;

    /**
     * Get singleton instance.
     */
    public static function instance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Private constructor.
     */
    private function __construct()
    {
        $this->settings_page = new Settings_Page();
        $this->welcome_page = new Welcome_Page();
        $this->changelog_page = new Changelog_Page();
    }

    /**
     * Initialize admin hooks.
     */
    public function init(): void
    {
        add_action('admin_menu', [$this, 'register_menus']);
        add_action('admin_init', [$this, 'maybe_redirect_to_welcome']);
        add_action('admin_init', [$this->settings_page, 'handle_save']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    /**
     * Register admin menus.
     */
    public function register_menus(): void
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        add_menu_page(
            esc_html__('Nebula Forge', 'nebula-forge-elementor-addons'),
            esc_html__('Nebula Forge', 'nebula-forge-elementor-addons'),
            'manage_options',
            self::MENU_SLUG_WELCOME,
            [$this->welcome_page, 'render'],
            'dashicons-star-filled',
            59
        );

        // Rename first submenu from "Nebula Forge" to "Welcome"
        add_submenu_page(
            self::MENU_SLUG_WELCOME,
            esc_html__('Welcome', 'nebula-forge-elementor-addons'),
            esc_html__('Welcome', 'nebula-forge-elementor-addons'),
            'manage_options',
            self::MENU_SLUG_WELCOME,
            [$this->welcome_page, 'render']
        );

        add_submenu_page(
            self::MENU_SLUG_WELCOME,
            esc_html__('Settings', 'nebula-forge-elementor-addons'),
            esc_html__('Settings', 'nebula-forge-elementor-addons'),
            'manage_options',
            self::MENU_SLUG_SETTINGS,
            [$this->settings_page, 'render']
        );

        add_submenu_page(
            self::MENU_SLUG_WELCOME,
            esc_html__('Changelog', 'nebula-forge-elementor-addons'),
            esc_html__('Changelog', 'nebula-forge-elementor-addons'),
            'manage_options',
            self::MENU_SLUG_CHANGELOG,
            [$this->changelog_page, 'render']
        );
    }

    /**
     * Redirect to welcome page on activation.
     */
    public function maybe_redirect_to_welcome(): void
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (is_network_admin() || wp_doing_ajax()) {
            return;
        }

        if (!empty($_GET['activate-multi'])) {
            return;
        }

        $should_redirect = (int) get_option(self::OPTION_ACTIVATION_REDIRECT, 0);

        if ($should_redirect !== 1) {
            return;
        }

        delete_option(self::OPTION_ACTIVATION_REDIRECT);

        wp_safe_redirect(admin_url('admin.php?page=' . self::MENU_SLUG_WELCOME));
        exit;
    }

    /**
     * Enqueue admin assets.
     */
    public function enqueue_assets(string $hook): void
    {
        if (strpos($hook, 'nebula-forge') === false) {
            return;
        }

        wp_enqueue_style(
            'nebula-forge-admin',
            NEBULA_FORGE_ADDON_URL . 'assets/css/admin.css',
            [],
            NEBULA_FORGE_ADDON_VERSION
        );
    }

    /**
     * Set activation redirect flag.
     */
    public static function set_activation_redirect(): void
    {
        update_option(self::OPTION_ACTIVATION_REDIRECT, 1);
    }

    /**
     * Get enabled widgets.
     */
    public static function get_enabled_widgets(): array
    {
        $saved = get_option(self::OPTION_WIDGETS, []);

        if (empty($saved) || !is_array($saved)) {
            return array_keys(Widget_Registry::get_available_widgets());
        }

        return $saved;
    }
}
