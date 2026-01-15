<?php
/**
 * Admin UI Helper.
 *
 * @package NebulaForgeAddon
 * @since   0.1.3
 */

namespace NebulaForgeAddon\Admin;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Ui_Helper
 *
 * Renders shared admin UI fragments.
 */
final class Ui_Helper
{
    /**
     * Render admin tabs.
     *
     * @param string $active_slug Active menu slug.
     */
    public static function render_tabs(string $active_slug): void
    {
        $tabs = [
            Admin_Manager::MENU_SLUG_WELCOME => [
                'label' => esc_html__('Welcome', 'nebula-forge-addons-for-elementor'),
                'icon' => 'dashicons-admin-home',
            ],
            Admin_Manager::MENU_SLUG_SETTINGS => [
                'label' => esc_html__('Settings', 'nebula-forge-addons-for-elementor'),
                'icon' => 'dashicons-admin-generic',
            ],
            Admin_Manager::MENU_SLUG_CHANGELOG => [
                'label' => esc_html__('Changelog', 'nebula-forge-addons-for-elementor'),
                'icon' => 'dashicons-backup',
            ],
        ];
        ?>
        <nav class="nf-tabs" aria-label="<?php esc_attr_e('Nebula Forge Navigation', 'nebula-forge-addons-for-elementor'); ?>">
            <?php foreach ($tabs as $slug => $tab) : ?>
                <?php
                $is_active = $slug === $active_slug;
                $class = $is_active ? 'nf-tab nf-tab--active' : 'nf-tab';
                ?>
                <a class="<?php echo esc_attr($class); ?>" href="<?php echo esc_url(admin_url('admin.php?page=' . $slug)); ?>">
                    <span class="dashicons <?php echo esc_attr($tab['icon']); ?>"></span>
                    <span><?php echo esc_html($tab['label']); ?></span>
                </a>
            <?php endforeach; ?>
        </nav>
        <?php
    }
}
