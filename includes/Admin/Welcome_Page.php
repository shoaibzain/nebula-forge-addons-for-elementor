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
        $enabled_widgets = Admin_Manager::get_enabled_widgets();
        $total = count($widgets);
        $active = count($enabled_widgets);

        if (!class_exists(Ui_Helper::class) && defined('NEBULA_FORGE_ADDON_PATH')) {
            $ui_helper_path = NEBULA_FORGE_ADDON_PATH . 'includes/Admin/Ui_Helper.php';
            if (file_exists($ui_helper_path)) {
                require_once $ui_helper_path;
            }
        }

        // Group widgets by category.
        $categories = [
            'layout'     => ['label' => __('Layout', 'nebula-forge-addons-for-elementor'), 'icon' => 'dashicons-layout', 'widgets' => []],
            'content'    => ['label' => __('Content', 'nebula-forge-addons-for-elementor'), 'icon' => 'dashicons-edit-large', 'widgets' => []],
            'data'       => ['label' => __('Data & Process', 'nebula-forge-addons-for-elementor'), 'icon' => 'dashicons-chart-bar', 'widgets' => []],
            'conversion' => ['label' => __('Conversion', 'nebula-forge-addons-for-elementor'), 'icon' => 'dashicons-megaphone', 'widgets' => []],
            'social'     => ['label' => __('Social Proof', 'nebula-forge-addons-for-elementor'), 'icon' => 'dashicons-groups', 'widgets' => []],
        ];
        foreach ($widgets as $key => $widget) {
            $cat = isset($widget['category'], $categories[$widget['category']]) ? $widget['category'] : 'content';
            $categories[$cat]['widgets'][$key] = $widget;
        }
        ?>
        <div class="wrap nf-admin-wrap">

            <!-- ── Header ── -->
            <div class="nf-admin-header">
                <div class="nf-admin-header__content">
                    <div class="nf-admin-header__top">
                        <div>
                            <span class="nf-admin-header__badge"><?php echo esc_html($plugin_version); ?></span>
                            <h1><?php esc_html_e('Nebula Forge', 'nebula-forge-addons-for-elementor'); ?></h1>
                            <p class="nf-admin-header__tagline">
                                <?php esc_html_e('Professional Elementor widgets for any WordPress page — blogs, portfolios, landing pages, and beyond.', 'nebula-forge-addons-for-elementor'); ?>
                            </p>
                        </div>
                        <div class="nf-header-stats">
                            <div class="nf-header-stat">
                                <span class="nf-header-stat__value"><?php echo esc_html($total); ?></span>
                                <span class="nf-header-stat__label"><?php esc_html_e('Widgets', 'nebula-forge-addons-for-elementor'); ?></span>
                            </div>
                            <div class="nf-header-stat">
                                <span class="nf-header-stat__value"><?php echo esc_html($active); ?></span>
                                <span class="nf-header-stat__label"><?php esc_html_e('Active', 'nebula-forge-addons-for-elementor'); ?></span>
                            </div>
                            <div class="nf-header-stat">
                                <span class="nf-header-stat__value"><?php echo esc_html(count($categories)); ?></span>
                                <span class="nf-header-stat__label"><?php esc_html_e('Categories', 'nebula-forge-addons-for-elementor'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (class_exists(Ui_Helper::class)) : ?>
                <?php Ui_Helper::render_tabs(Admin_Manager::MENU_SLUG_WELCOME); ?>
            <?php endif; ?>

            <!-- ── Getting Started ── -->
            <div class="nf-onboarding">
                <h2 class="nf-onboarding__title">
                    <span class="dashicons dashicons-flag"></span>
                    <?php esc_html_e('Get Started in 3 Steps', 'nebula-forge-addons-for-elementor'); ?>
                </h2>
                <div class="nf-onboarding__steps">
                    <div class="nf-onboard-step">
                        <span class="nf-onboard-step__num">1</span>
                        <h3><?php esc_html_e('Activate Elementor', 'nebula-forge-addons-for-elementor'); ?></h3>
                        <p><?php esc_html_e('Make sure the free Elementor plugin is installed and active.', 'nebula-forge-addons-for-elementor'); ?></p>
                    </div>
                    <div class="nf-onboard-step">
                        <span class="nf-onboard-step__num">2</span>
                        <h3><?php esc_html_e('Open the Editor', 'nebula-forge-addons-for-elementor'); ?></h3>
                        <p><?php esc_html_e('Edit any page or post with Elementor and find the Nebula Forge category.', 'nebula-forge-addons-for-elementor'); ?></p>
                    </div>
                    <div class="nf-onboard-step">
                        <span class="nf-onboard-step__num">3</span>
                        <h3><?php esc_html_e('Drag & Build', 'nebula-forge-addons-for-elementor'); ?></h3>
                        <p><?php esc_html_e('Drag widgets onto your page and customise content, style, and layout.', 'nebula-forge-addons-for-elementor'); ?></p>
                    </div>
                </div>
            </div>

            <!-- ── Main Content ── -->
            <div class="nf-admin-content">
                <div class="nf-admin-row">

                    <!-- Widget Showcase -->
                    <div class="nf-admin-col nf-admin-col--main">
                        <?php foreach ($categories as $cat_key => $cat) : ?>
                            <?php if (empty($cat['widgets'])) continue; ?>
                            <div class="nf-category-section" id="nf-cat-<?php echo esc_attr($cat_key); ?>">
                                <h2 class="nf-category-section__title">
                                    <span class="dashicons <?php echo esc_attr($cat['icon']); ?>"></span>
                                    <?php echo esc_html($cat['label']); ?>
                                    <span class="nf-category-section__count"><?php echo esc_html(count($cat['widgets'])); ?></span>
                                </h2>
                                <div class="nf-widget-showcase">
                                    <?php foreach ($cat['widgets'] as $key => $widget) : ?>
                                        <?php
                                        $badge = isset($widget['badge']) ? $widget['badge'] : '';
                                        $badge_color = isset($widget['badge_color']) ? sanitize_hex_color($widget['badge_color']) : '';
                                        $badge_style = $badge_color ? '--nf-badge-color: ' . $badge_color . ';' : '';
                                        $is_active = in_array($key, $enabled_widgets, true);
                                        ?>
                                        <div class="nf-showcase-card <?php echo $is_active ? '' : esc_attr('nf-showcase-card--inactive'); ?>">
                                            <div class="nf-showcase-card__header">
                                                <div class="nf-showcase-card__icon" style="<?php echo esc_attr($badge_style); ?>">
                                                    <span class="<?php echo esc_attr($widget['icon']); ?>"></span>
                                                </div>
                                                <?php if (!empty($badge)) : ?>
                                                    <span class="nf-badge-chip" style="<?php echo esc_attr($badge_style); ?>">
                                                        <?php echo esc_html($badge); ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if ($is_active) : ?>
                                                    <span class="nf-status-dot nf-status-dot--active" title="<?php esc_attr_e('Active', 'nebula-forge-addons-for-elementor'); ?>"></span>
                                                <?php endif; ?>
                                            </div>
                                            <h3 class="nf-showcase-card__title">
                                                <?php echo esc_html($widget['label']); ?>
                                                <?php if (!empty($widget['tooltip'])) : ?>
                                                    <span class="nf-tooltip" data-tooltip="<?php echo esc_attr($widget['tooltip']); ?>" tabindex="0" aria-label="<?php echo esc_attr($widget['tooltip']); ?>">
                                                        <span class="dashicons dashicons-editor-help" aria-hidden="true"></span>
                                                    </span>
                                                <?php endif; ?>
                                            </h3>
                                            <p class="nf-showcase-card__desc"><?php echo esc_html($widget['description']); ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Sidebar -->
                    <div class="nf-admin-col nf-admin-col--sidebar">

                        <!-- System Status -->
                        <div class="nf-card nf-card--status">
                            <h2 class="nf-card__title">
                                <span class="dashicons dashicons-heart"></span>
                                <?php esc_html_e('System Status', 'nebula-forge-addons-for-elementor'); ?>
                            </h2>
                            <ul class="nf-status-list">
                                <li>
                                    <span class="nf-status-list__icon nf-status-list__icon--ok"><span class="dashicons dashicons-yes-alt"></span></span>
                                    <span class="nf-status-list__text"><?php esc_html_e('WordPress', 'nebula-forge-addons-for-elementor'); ?></span>
                                    <span class="nf-status-list__value"><?php echo esc_html(get_bloginfo('version')); ?></span>
                                </li>
                                <li>
                                    <span class="nf-status-list__icon nf-status-list__icon--ok"><span class="dashicons dashicons-yes-alt"></span></span>
                                    <span class="nf-status-list__text"><?php esc_html_e('PHP', 'nebula-forge-addons-for-elementor'); ?></span>
                                    <span class="nf-status-list__value"><?php echo esc_html(PHP_VERSION); ?></span>
                                </li>
                                <li>
                                    <?php
                                    $elementor_active = defined('ELEMENTOR_VERSION');
                                    $el_icon_class = $elementor_active ? 'nf-status-list__icon--ok' : 'nf-status-list__icon--error';
                                    ?>
                                    <span class="nf-status-list__icon <?php echo esc_attr($el_icon_class); ?>"><span class="dashicons <?php echo esc_attr($elementor_active ? 'dashicons-yes-alt' : 'dashicons-warning'); ?>"></span></span>
                                    <span class="nf-status-list__text"><?php esc_html_e('Elementor', 'nebula-forge-addons-for-elementor'); ?></span>
                                    <span class="nf-status-list__value"><?php echo $elementor_active ? esc_html(ELEMENTOR_VERSION) : esc_html__('Not found', 'nebula-forge-addons-for-elementor'); ?></span>
                                </li>
                                <li>
                                    <span class="nf-status-list__icon nf-status-list__icon--ok"><span class="dashicons dashicons-yes-alt"></span></span>
                                    <span class="nf-status-list__text"><?php esc_html_e('Plugin', 'nebula-forge-addons-for-elementor'); ?></span>
                                    <span class="nf-status-list__value"><?php echo esc_html($plugin_version); ?></span>
                                </li>
                            </ul>
                        </div>

                        <!-- Quick Links -->
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
                                        <span class="nf-links-list__arrow dashicons dashicons-arrow-right-alt2"></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo esc_url(admin_url('admin.php?page=' . Admin_Manager::MENU_SLUG_CHANGELOG)); ?>">
                                        <span class="dashicons dashicons-backup"></span>
                                        <?php esc_html_e('View Changelog', 'nebula-forge-addons-for-elementor'); ?>
                                        <span class="nf-links-list__arrow dashicons dashicons-arrow-right-alt2"></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo esc_url('https://wordpress.org/plugins/nebula-forge-addons-for-elementor/'); ?>" target="_blank" rel="noopener noreferrer">
                                        <span class="dashicons dashicons-wordpress"></span>
                                        <?php esc_html_e('WordPress.org', 'nebula-forge-addons-for-elementor'); ?>
                                        <span class="nf-links-list__arrow dashicons dashicons-external"></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo esc_url('https://wordpress.org/support/plugin/nebula-forge-addons-for-elementor/reviews/#new-post'); ?>" target="_blank" rel="noopener noreferrer">
                                        <span class="dashicons dashicons-star-filled"></span>
                                        <?php esc_html_e('Rate this Plugin', 'nebula-forge-addons-for-elementor'); ?>
                                        <span class="nf-links-list__arrow dashicons dashicons-external"></span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Pro Tip -->
                        <div class="nf-card nf-card--tip">
                            <div class="nf-card__tip-icon">
                                <span class="dashicons dashicons-lightbulb"></span>
                            </div>
                            <h2 class="nf-card__title"><?php esc_html_e('Pro Tip', 'nebula-forge-addons-for-elementor'); ?></h2>
                            <p><?php esc_html_e('Disable widgets you don\'t use in Settings to keep the Elementor panel clean and improve editor load times.', 'nebula-forge-addons-for-elementor'); ?></p>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=' . Admin_Manager::MENU_SLUG_SETTINGS)); ?>" class="nf-button nf-button--outline nf-button--sm">
                                <?php esc_html_e('Open Settings', 'nebula-forge-addons-for-elementor'); ?>
                                <span class="dashicons dashicons-arrow-right-alt2"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
