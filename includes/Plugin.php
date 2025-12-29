<?php
namespace NebulaForgeAddon;

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Plugin as ElementorPlugin;
use Elementor\Widgets_Manager;

class Plugin
{
    private const MIN_PHP_VERSION = '7.4';
    private const MIN_WP_VERSION = '6.2';
    private const MIN_ELEMENTOR_VERSION = '3.20.0';

    private static ?Plugin $instance = null;

    private string $plugin_file = '';

    private bool $is_ready = false;

    public static function instance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function register(string $plugin_file): void
    {
        $this->plugin_file = $plugin_file;

        register_activation_hook($plugin_file, [$this, 'on_activation']);
        register_deactivation_hook($plugin_file, [$this, 'on_deactivation']);

        add_action('plugins_loaded', [$this, 'on_plugins_loaded']);
    }

    public function on_plugins_loaded(): void
    {
        if (!$this->passes_basic_checks('runtime')) {
            add_action('admin_notices', [$this, 'render_admin_notice']);
            return;
        }

        $this->is_ready = true;

        add_action('elementor/elements/categories_registered', [$this, 'register_widget_category']);
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
    }

    public function on_activation(): void
    {
        if (!$this->passes_basic_checks('activation')) {
            deactivate_plugins(plugin_basename($this->plugin_file));
            wp_die(
                esc_html__(
                    'Nebula Forge Elementor Addons requires Elementor and minimum versions of WordPress and PHP. Please install/activate Elementor and ensure your site meets the plugin requirements, then try again.',
                    'nebula-forge-elementor-addons'
                )
            );
        }
    }

    public function on_deactivation(): void
    {
        // No-op for now.
    }

    public function register_widgets(Widgets_Manager $widgets_manager): void
    {
        if (!$this->is_ready) {
            return;
        }

        $widgets_manager->register(new Widgets\Hero_Cta_Widget());
        $widgets_manager->register(new Widgets\Feature_List_Widget());
        $widgets_manager->register(new Widgets\Spotlight_Card_Widget());
        $widgets_manager->register(new Widgets\Stats_Grid_Widget());
    }

    public function register_widget_category($elements_manager): void
    {
        if (!$this->is_ready) {
            return;
        }

        $elements_manager->add_category(
            'nebula-forge',
            [
                'title' => esc_html__('Nebula Forge', 'nebula-forge-elementor-addons'),
                'icon' => 'fa fa-star',
            ]
        );
    }

    public function enqueue_frontend_assets(): void
    {
        if (!$this->is_ready) {
            return;
        }

        wp_register_style(
            'nebula-forge-elementor-addon-frontend',
            NEBULA_FORGE_ADDON_URL . 'assets/css/frontend.css',
            [],
            NEBULA_FORGE_ADDON_VERSION
        );

        wp_register_script(
            'nebula-forge-elementor-addon-frontend',
            NEBULA_FORGE_ADDON_URL . 'assets/js/frontend.js',
            ['elementor-frontend', 'jquery'],
            NEBULA_FORGE_ADDON_VERSION,
            true
        );

        wp_enqueue_style('nebula-forge-elementor-addon-frontend');
        wp_enqueue_script('nebula-forge-elementor-addon-frontend');
    }

    private function passes_basic_checks(string $context = 'runtime'): bool
    {
        global $wp_version;

        if (version_compare(PHP_VERSION, self::MIN_PHP_VERSION, '<')) {
            return false;
        }

        if (version_compare($wp_version, self::MIN_WP_VERSION, '<')) {
            return false;
        }

        if ('activation' === $context) {
            return $this->is_elementor_active_for_activation();
        }

        if (!did_action('elementor/loaded')) {
            return false;
        }

        if (!defined('ELEMENTOR_VERSION')) {
            return false;
        }

        if (version_compare(ELEMENTOR_VERSION, self::MIN_ELEMENTOR_VERSION, '<')) {
            return false;
        }

        return true;
    }

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

    public function render_admin_notice(): void
    {
        if (!current_user_can('activate_plugins')) {
            return;
        }

        if (function_exists('get_current_screen')) {
            $screen = get_current_screen();
            if ($screen && 'plugins' !== $screen->id) {
                return;
            }
        }

        echo '<div class="notice notice-error"><p>' . esc_html__('Nebula Forge Elementor Addons requires Elementor, WordPress, and PHP versions that meet the minimum requirements. Please install/activate Elementor and re-check your site versions.', 'nebula-forge-elementor-addons') . '</p></div>';
    }
}
