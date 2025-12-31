<?php
/**
 * Welcome Page - Plugin welcome/about screen.
 *
 * @package NebulaForgeAddon
 * @since   0.2.0
 */

namespace NebulaForgeAddon\Admin;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Welcome_Page
 *
 * Renders the welcome/about admin page.
 */
final class Welcome_Page
{
    /**
     * Render the welcome page.
     */
    public function render(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to access this page.', 'nebula-forge-addons-for-elementor'));
        }

        $this->render_template();
    }

    /**
     * Render the page template.
     */
    private function render_template(): void
    {
        $plugin_version = defined('NEBULA_FORGE_ADDON_VERSION') ? NEBULA_FORGE_ADDON_VERSION : '1.0.0';
        $widgets = Widget_Registry::get_available_widgets();
        ?>
        <div class="wrap nf-admin-wrap">
            <div class="nf-admin-header">
                <div class="nf-admin-header__content">
                    <h1><?php esc_html_e('Nebula Forge Addons for Elementor', 'nebula-forge-addons-for-elementor'); ?></h1>
                    <p class="nf-admin-header__version">
                        <?php
                        // translators: %s: Plugin version number.
                        printf(esc_html__('Version %s', 'nebula-forge-addons-for-elementor'), esc_html($plugin_version));
                        ?>
                    </p>
                    <p class="nf-admin-header__tagline">
                        <?php esc_html_e('Modern Elementor widgets for landing pages and product sections.', 'nebula-forge-addons-for-elementor'); ?>
                    </p>
                </div>
            </div>

            <div class="nf-admin-content">
                <div class="nf-admin-row">
                    <!-- Main Content -->
                    <div class="nf-admin-col nf-admin-col--main">
                        <div class="nf-card">
                            <h2 class="nf-card__title">
                                <span class="dashicons dashicons-welcome-widgets-menus"></span>
                                <?php esc_html_e('Available Widgets', 'nebula-forge-addons-for-elementor'); ?>
                            </h2>
                            <div class="nf-widget-list">
                                <?php foreach ($widgets as $key => $widget) : ?>
                                    <div class="nf-widget-item">
                                        <div class="nf-widget-item__icon">
                                            <span class="<?php echo esc_attr($widget['icon']); ?>"></span>
                                        </div>
                                        <div class="nf-widget-item__content">
                                            <h3><?php echo esc_html($widget['label']); ?></h3>
                                            <p><?php echo esc_html($widget['description']); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="nf-admin-col nf-admin-col--sidebar">
                        <div class="nf-card">
                            <h2 class="nf-card__title">
                                <span class="dashicons dashicons-lightbulb"></span>
                                <?php esc_html_e('Quick Start', 'nebula-forge-addons-for-elementor'); ?>
                            </h2>
                            <ol class="nf-quick-start">
                                <li><?php esc_html_e('Ensure Elementor is installed and activated.', 'nebula-forge-addons-for-elementor'); ?></li>
                                <li><?php esc_html_e('Edit any page with Elementor.', 'nebula-forge-addons-for-elementor'); ?></li>
                                <li><?php esc_html_e('Find widgets under "Nebula Forge" category.', 'nebula-forge-addons-for-elementor'); ?></li>
                                <li><?php esc_html_e('Drag and drop widgets to build your page.', 'nebula-forge-addons-for-elementor'); ?></li>
                            </ol>
                        </div>

                        <div class="nf-card">
                            <h2 class="nf-card__title">
                                <span class="dashicons dashicons-admin-links"></span>
                                <?php esc_html_e('Quick Links', 'nebula-forge-addons-for-elementor'); ?>
                            </h2>
                            <ul class="nf-links-list">
                                <li>
                                    <a href="<?php echo esc_url(admin_url('admin.php?page=' . Admin_Manager::MENU_SLUG_SETTINGS)); ?>">
                                        <span class="dashicons dashicons-admin-settings"></span>
                                        <?php esc_html_e('Widget Settings', 'nebula-forge-addons-for-elementor'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo esc_url(admin_url('admin.php?page=' . Admin_Manager::MENU_SLUG_CHANGELOG)); ?>">
                                        <span class="dashicons dashicons-backup"></span>
                                        <?php esc_html_e('View Changelog', 'nebula-forge-addons-for-elementor'); ?>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="nf-card nf-card--highlight">
                            <h2 class="nf-card__title">
                                <span class="dashicons dashicons-info"></span>
                                <?php esc_html_e('Requirements', 'nebula-forge-addons-for-elementor'); ?>
                            </h2>
                            <ul class="nf-requirements">
                                <li>
                                    <span class="dashicons dashicons-yes-alt"></span>
                                    <?php esc_html_e('WordPress 6.2+', 'nebula-forge-addons-for-elementor'); ?>
                                </li>
                                <li>
                                    <span class="dashicons dashicons-yes-alt"></span>
                                    <?php esc_html_e('PHP 7.4+', 'nebula-forge-addons-for-elementor'); ?>
                                </li>
                                <li>
                                    <span class="dashicons dashicons-yes-alt"></span>
                                    <?php esc_html_e('Elementor 3.20+ (Free)', 'nebula-forge-addons-for-elementor'); ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
