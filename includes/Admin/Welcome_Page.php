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
        if (!class_exists(Ui_Helper::class) && defined('NEBULA_FORGE_ADDON_PATH')) {
            $ui_helper_path = NEBULA_FORGE_ADDON_PATH . 'includes/Admin/Ui_Helper.php';
            if (file_exists($ui_helper_path)) {
                require_once $ui_helper_path;
            }
        }
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

            <?php if (class_exists(Ui_Helper::class)) : ?>
                <?php Ui_Helper::render_tabs(Admin_Manager::MENU_SLUG_WELCOME); ?>
            <?php endif; ?>

            <div class="nf-callout nf-callout--info">
                <div class="nf-callout__title">
                    <span class="dashicons dashicons-sos"></span>
                    <?php esc_html_e('Start Here', 'nebula-forge-addons-for-elementor'); ?>
                </div>
                <p class="nf-callout__text">
                    <?php esc_html_e('Use the tabs above to configure widgets, review updates, and get oriented quickly. Each widget includes built-in style controls for typography, spacing, and colors.', 'nebula-forge-addons-for-elementor'); ?>
                </p>
                <ul class="nf-callout__list">
                    <li><?php esc_html_e('Open any page with Elementor and look for the Nebula Forge category.', 'nebula-forge-addons-for-elementor'); ?></li>
                    <li><?php esc_html_e('Drag widgets into sections and customize content in the left panel.', 'nebula-forge-addons-for-elementor'); ?></li>
                    <li><?php esc_html_e('Use the Settings tab to hide widgets you are not using.', 'nebula-forge-addons-for-elementor'); ?></li>
                </ul>
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
                                    <?php
                                    $badge = isset($widget['badge']) ? $widget['badge'] : '';
                                    $badge_color = isset($widget['badge_color']) ? sanitize_hex_color($widget['badge_color']) : '';
                                    $badge_style = $badge_color ? '--nf-badge-color: ' . $badge_color . ';' : '';
                                    ?>
                                    <div class="nf-widget-item">
                                        <div class="nf-widget-item__icon">
                                            <span class="<?php echo esc_attr($widget['icon']); ?>"></span>
                                        </div>
                                        <div class="nf-widget-item__content">
                                            <h3>
                                                <?php echo esc_html($widget['label']); ?>
                                                <?php if (!empty($badge)) : ?>
                                                    <span class="nf-badge-chip" style="<?php echo esc_attr($badge_style); ?>">
                                                        <?php echo esc_html($badge); ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if (!empty($widget['tooltip'])) : ?>
                                                    <span class="nf-tooltip" data-tooltip="<?php echo esc_attr($widget['tooltip']); ?>" tabindex="0" aria-label="<?php echo esc_attr($widget['tooltip']); ?>">
                                                        <span class="dashicons dashicons-editor-help" aria-hidden="true"></span>
                                                    </span>
                                                <?php endif; ?>
                                            </h3>
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
                                <li>
                                    <a href="<?php echo esc_url('https://wordpress.org/plugins/nebula-forge-addons-for-elementor/'); ?>" target="_blank" rel="noopener noreferrer">
                                        <span class="dashicons dashicons-megaphone"></span>
                                        <?php esc_html_e('Plugin Page', 'nebula-forge-addons-for-elementor'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo esc_url('https://wordpress.org/support/plugin/nebula-forge-addons-for-elementor/reviews/#new-post'); ?>" target="_blank" rel="noopener noreferrer">
                                        <span class="dashicons dashicons-star-filled"></span>
                                        <?php esc_html_e('Leave a Review', 'nebula-forge-addons-for-elementor'); ?>
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
