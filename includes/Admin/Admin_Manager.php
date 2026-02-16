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
    public const MENU_SLUG_FONTS = 'nebula-forge-addon-fonts';
    public const MENU_SLUG_CHANGELOG = 'nebula-forge-addon-changelog';
    public const MENU_SLUG_SUBMISSIONS = 'nebula-forge-addon-submissions';

    public const OPTION_ACTIVATION_REDIRECT = 'nebula_forge_addon_activation_redirect';
    public const OPTION_WIDGETS = 'nebula_forge_addon_widgets';
    public const OPTION_EXTENSIONS = 'nebula_forge_addon_extensions';

    private static ?self $instance = null;

    private ?Settings_Page $settings_page = null;
    private ?Welcome_Page $welcome_page = null;
    private ?Changelog_Page $changelog_page = null;
    private ?Custom_Fonts_Page $fonts_page = null;
    private ?Submissions_Page $submissions_page = null;

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
        $this->fonts_page = new Custom_Fonts_Page();
        $this->submissions_page = new Submissions_Page();
    }

    /**
     * Initialize admin hooks.
     */
    public function init(): void
    {
        add_action('admin_menu', [$this, 'register_menus']);
        add_action('admin_init', [$this, 'maybe_redirect_to_welcome']);
        // Only invoke settings save handler when a settings POST is detected.
        add_action('admin_init', function () {
            $method = isset($_SERVER['REQUEST_METHOD']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_METHOD'])) : '';
            if (strtoupper($method) === 'POST' && isset($_POST['nf_settings_nonce'])) {
                $nonce = isset($_POST['nf_settings_nonce']) ? sanitize_text_field(wp_unslash($_POST['nf_settings_nonce'])) : '';
                if (wp_verify_nonce($nonce, 'nf_save_settings')) {
                    $this->settings_page->handle_save();
                }
            }
        });
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
            esc_html__('Nebula Forge', 'nebula-forge-addons-for-elementor'),
            esc_html__('Nebula Forge', 'nebula-forge-addons-for-elementor'),
            'manage_options',
            self::MENU_SLUG_WELCOME,
            [$this->welcome_page, 'render'],
            NEBULA_FORGE_ADDON_URL . 'assets/img/logo-20x20.png',
            59
        );

        // Rename first submenu from "Nebula Forge" to "Welcome"
        add_submenu_page(
            self::MENU_SLUG_WELCOME,
            esc_html__('Welcome', 'nebula-forge-addons-for-elementor'),
            esc_html__('Welcome', 'nebula-forge-addons-for-elementor'),
            'manage_options',
            self::MENU_SLUG_WELCOME,
            [$this->welcome_page, 'render']
        );

        add_submenu_page(
            self::MENU_SLUG_WELCOME,
            esc_html__('Settings', 'nebula-forge-addons-for-elementor'),
            esc_html__('Settings', 'nebula-forge-addons-for-elementor'),
            'manage_options',
            self::MENU_SLUG_SETTINGS,
            [$this->settings_page, 'render']
        );

        add_submenu_page(
            self::MENU_SLUG_WELCOME,
            esc_html__('Custom Fonts', 'nebula-forge-addons-for-elementor'),
            esc_html__('Custom Fonts', 'nebula-forge-addons-for-elementor'),
            'manage_options',
            self::MENU_SLUG_FONTS,
            [$this->fonts_page, 'render']
        );

        add_submenu_page(
            self::MENU_SLUG_WELCOME,
            esc_html__('Submissions', 'nebula-forge-addons-for-elementor'),
            esc_html__('Submissions', 'nebula-forge-addons-for-elementor'),
            'manage_options',
            self::MENU_SLUG_SUBMISSIONS,
            [$this->submissions_page, 'render']
        );

        add_submenu_page(
            self::MENU_SLUG_WELCOME,
            esc_html__('Changelog', 'nebula-forge-addons-for-elementor'),
            esc_html__('Changelog', 'nebula-forge-addons-for-elementor'),
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

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- activation flag provided by WP during plugin activation flow and not a user-submitted form.
        $activate_multi = isset($_GET['activate-multi']) ? sanitize_text_field(wp_unslash($_GET['activate-multi'])) : '';
        if (!empty($activate_multi)) {
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

        // Enqueue media uploader on fonts page.
        if (strpos($hook, 'fonts') !== false) {
            wp_enqueue_media();
        }
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
        $saved = get_option(self::OPTION_WIDGETS, null);

        if ($saved === null || !is_array($saved)) {
            return array_keys(Widget_Registry::get_available_widgets());
        }

        return $saved;
    }

    /**
     * Get enabled extensions.
     *
     * @return string[]
     */
    public static function get_enabled_extensions(): array
    {
        $saved = get_option(self::OPTION_EXTENSIONS, null);

        if ($saved === null || !is_array($saved)) {
            // All enabled by default.
            return ['display_conditions', 'custom_fonts', 'tooltip', 'wrapper_link'];
        }

        return $saved;
    }

    /**
     * Get available extensions metadata.
     *
     * @return array<string, array{label: string, description: string, icon: string}>
     */
    public static function get_available_extensions(): array
    {
        return [
            'display_conditions' => [
                'label'       => __('Display Conditions', 'nebula-forge-addons-for-elementor'),
                'description' => __('Show or hide any widget based on user role, login status, date range, page type, browser, and OS.', 'nebula-forge-addons-for-elementor'),
                'icon'        => 'dashicons-visibility',
            ],
            'custom_fonts' => [
                'label'       => __('Custom Fonts', 'nebula-forge-addons-for-elementor'),
                'description' => __('Upload custom font files and use them in Elementor typography controls.', 'nebula-forge-addons-for-elementor'),
                'icon'        => 'dashicons-editor-textcolor',
            ],
            'tooltip' => [
                'label'       => __('Widget Tooltip', 'nebula-forge-addons-for-elementor'),
                'description' => __('Add configurable hover or click tooltips to any Elementor widget.', 'nebula-forge-addons-for-elementor'),
                'icon'        => 'dashicons-info-outline',
            ],
            'wrapper_link' => [
                'label'       => __('Wrapper Link', 'nebula-forge-addons-for-elementor'),
                'description' => __('Make any widget, column, section, or container fully clickable with a URL.', 'nebula-forge-addons-for-elementor'),
                'icon'        => 'dashicons-admin-links',
            ],
        ];
    }
}
