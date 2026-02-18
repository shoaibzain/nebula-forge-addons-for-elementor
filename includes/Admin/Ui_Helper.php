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
                'label' => esc_html__('Dashboard', 'nebula-forge-addons-for-elementor'),
                'icon'  => 'dashicons-dashboard',
                'desc'  => esc_html__('Overview & widgets', 'nebula-forge-addons-for-elementor'),
            ],
            Admin_Manager::MENU_SLUG_SETTINGS => [
                'label' => esc_html__('Settings', 'nebula-forge-addons-for-elementor'),
                'icon'  => 'dashicons-admin-generic',
                'desc'  => esc_html__('Toggle widgets & extensions', 'nebula-forge-addons-for-elementor'),
            ],
            Admin_Manager::MENU_SLUG_FONTS => [
                'label' => esc_html__('Custom Fonts', 'nebula-forge-addons-for-elementor'),
                'icon'  => 'dashicons-editor-textcolor',
                'desc'  => esc_html__('Upload & manage fonts', 'nebula-forge-addons-for-elementor'),
                'pro'   => true,
            ],
            Admin_Manager::MENU_SLUG_SUBMISSIONS => [
                'label' => esc_html__('Submissions', 'nebula-forge-addons-for-elementor'),
                'icon'  => 'dashicons-email-alt',
                'desc'  => esc_html__('Form entries & data', 'nebula-forge-addons-for-elementor'),
                'pro'   => true,
            ],
            Admin_Manager::MENU_SLUG_CHANGELOG => [
                'label' => esc_html__('Changelog', 'nebula-forge-addons-for-elementor'),
                'icon'  => 'dashicons-backup',
                'desc'  => esc_html__('Version history', 'nebula-forge-addons-for-elementor'),
            ],
        ];
        ?>
        <nav class="nf-tabs" aria-label="<?php esc_attr_e('Nebula Forge Navigation', 'nebula-forge-addons-for-elementor'); ?>">
            <?php foreach ($tabs as $slug => $tab) : ?>
                <?php
                $is_active = $slug === $active_slug;
                $class = $is_active ? 'nf-tab nf-tab--active' : 'nf-tab';
                $is_pro = !empty($tab['pro']);
                ?>
                <a class="<?php echo esc_attr($class); ?>" href="<?php echo esc_url(admin_url('admin.php?page=' . $slug)); ?>">
                    <span class="dashicons <?php echo esc_attr($tab['icon']); ?>"></span>
                    <span class="nf-tab__text">
                        <span class="nf-tab__label">
                            <?php echo esc_html($tab['label']); ?>
                            <?php if ($is_pro) : ?>
                                <span class="nf-tab__pro-badge"><?php esc_html_e('FREE', 'nebula-forge-addons-for-elementor'); ?></span>
                            <?php endif; ?>
                        </span>
                        <span class="nf-tab__desc"><?php echo esc_html($tab['desc']); ?></span>
                    </span>
                </a>
            <?php endforeach; ?>
        </nav>
        <?php
    }
}
