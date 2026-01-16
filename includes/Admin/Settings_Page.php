<?php
/**
 * Settings Page - Widget enable/disable settings.
 *
 * @package NebulaForgeAddon
 * @since   0.2.0
 */

namespace NebulaForgeAddon\Admin;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Settings_Page
 *
 * Renders and handles the settings admin page.
 */
final class Settings_Page
{
    private const NONCE_ACTION = 'nf_save_settings';
    private const NONCE_NAME = 'nf_settings_nonce';

    /**
     * Handle settings save.
     */
    public function handle_save(): void
    {
        // Take a local copy of POST data and unslash it prior to validation/sanitization.
        $post = wp_unslash($_POST);

        if (!isset($post[self::NONCE_NAME])) {
            return;
        }

        $nonce = sanitize_text_field($post[self::NONCE_NAME]);
        if (!wp_verify_nonce($nonce, self::NONCE_ACTION)) {
            return;
        }

        if (!current_user_can('manage_options')) {
            return;
        }

        $enabled = [];
        $available_widgets = Widget_Registry::get_available_widgets();

        $raw_widgets = isset($post['nf_widgets']) ? $post['nf_widgets'] : [];
        if (is_array($raw_widgets)) {
            foreach ($raw_widgets as $widget_key) {
                $sanitized_key = sanitize_key($widget_key);
                if (array_key_exists($sanitized_key, $available_widgets)) {
                    $enabled[] = $sanitized_key;
                }
            }
        }

        update_option(Admin_Manager::OPTION_WIDGETS, $enabled);

        wp_safe_redirect(
            add_query_arg('settings-updated', '1', admin_url('admin.php?page=' . Admin_Manager::MENU_SLUG_SETTINGS))
        );
        exit;
    }

    /**
     * Render the settings page.
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
        $widgets = Widget_Registry::get_available_widgets();
        $enabled_widgets = Admin_Manager::get_enabled_widgets();
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- simple status flag for UI, not processing sensitive form data.
        $settings_updated = isset($_GET['settings-updated']) && sanitize_text_field(wp_unslash($_GET['settings-updated'])) === '1';
        if (!class_exists(Ui_Helper::class) && defined('NEBULA_FORGE_ADDON_PATH')) {
            $ui_helper_path = NEBULA_FORGE_ADDON_PATH . 'includes/Admin/Ui_Helper.php';
            if (file_exists($ui_helper_path)) {
                require_once $ui_helper_path;
            }
        }
        ?>
        <div class="wrap nf-admin-wrap">
            <div class="nf-admin-header nf-admin-header--settings">
                <div class="nf-admin-header__content">
                    <h1><?php esc_html_e('Widget Settings', 'nebula-forge-addons-for-elementor'); ?></h1>
                    <p class="nf-admin-header__tagline">
                        <?php esc_html_e('Enable or disable individual widgets. Disabled widgets will not appear in the Elementor editor.', 'nebula-forge-addons-for-elementor'); ?>
                    </p>
                </div>
            </div>

            <?php if (class_exists(Ui_Helper::class)) : ?>
                <?php Ui_Helper::render_tabs(Admin_Manager::MENU_SLUG_SETTINGS); ?>
            <?php endif; ?>

            <div class="nf-callout nf-callout--note">
                <div class="nf-callout__title">
                    <span class="dashicons dashicons-info-outline"></span>
                    <?php esc_html_e('How this works', 'nebula-forge-addons-for-elementor'); ?>
                </div>
                <p class="nf-callout__text">
                    <?php esc_html_e('Use the toggles to hide widgets you do not need. This keeps the Elementor sidebar tidy and helps teams stay focused.', 'nebula-forge-addons-for-elementor'); ?>
                </p>
                <p class="nf-callout__text">
                    <?php esc_html_e('Hover the help icon on each widget to see suggested usage tips.', 'nebula-forge-addons-for-elementor'); ?>
                </p>
            </div>

            <div class="nf-admin-content nf-settings-content">
                <?php if ($settings_updated) : ?>
                    <div class="nf-notice nf-notice--success">
                        <span class="dashicons dashicons-yes-alt"></span>
                        <?php esc_html_e('Settings saved successfully!', 'nebula-forge-addons-for-elementor'); ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="" class="nf-settings-form">
                    <?php wp_nonce_field(self::NONCE_ACTION, self::NONCE_NAME); ?>

                    <div class="nf-widget-grid">
                        <?php foreach ($widgets as $widget_key => $widget_data) : ?>
                            <?php
                            $is_enabled = in_array($widget_key, $enabled_widgets, true);
                            $card_class = $is_enabled ? 'nf-widget-card' : 'nf-widget-card nf-widget-card--disabled';
                            $tooltip = isset($widget_data['tooltip']) ? $widget_data['tooltip'] : '';
                            $badge = isset($widget_data['badge']) ? $widget_data['badge'] : '';
                            $badge_color = isset($widget_data['badge_color']) ? sanitize_hex_color($widget_data['badge_color']) : '';
                            $badge_style = $badge_color ? '--nf-badge-color: ' . $badge_color . ';' : '';
                            ?>
                            <div class="<?php echo esc_attr($card_class); ?>" data-widget="<?php echo esc_attr($widget_key); ?>">
                                <div class="nf-widget-card__icon">
                                    <span class="<?php echo esc_attr($widget_data['icon']); ?>"></span>
                                </div>
                                <div class="nf-widget-card__content">
                                    <h3>
                                        <?php echo esc_html($widget_data['label']); ?>
                                        <?php if (!empty($badge)) : ?>
                                            <span class="nf-badge-chip" style="<?php echo esc_attr($badge_style); ?>">
                                                <?php echo esc_html($badge); ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if (!empty($tooltip)) : ?>
                                            <span class="nf-tooltip" data-tooltip="<?php echo esc_attr($tooltip); ?>" tabindex="0" aria-label="<?php echo esc_attr($tooltip); ?>">
                                                <span class="dashicons dashicons-editor-help" aria-hidden="true"></span>
                                            </span>
                                        <?php endif; ?>
                                    </h3>
                                    <p><?php echo esc_html($widget_data['description']); ?></p>
                                </div>
                                <label class="nf-toggle">
                                    <input 
                                        type="checkbox" 
                                        name="nf_widgets[]" 
                                        value="<?php echo esc_attr($widget_key); ?>"
                                        <?php checked($is_enabled); ?>
                                        onchange="this.closest('.nf-widget-card').classList.toggle('nf-widget-card--disabled', !this.checked)"
                                    >
                                    <span class="nf-toggle__slider"></span>
                                    <span class="nf-toggle__label">
                                        <?php echo $is_enabled ? esc_html__('Enabled', 'nebula-forge-addons-for-elementor') : esc_html__('Disabled', 'nebula-forge-addons-for-elementor'); ?>
                                    </span>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="nf-settings-footer">
                        <button type="submit" class="nf-button nf-button--primary">
                            <span class="dashicons dashicons-saved"></span>
                            <?php esc_html_e('Save Settings', 'nebula-forge-addons-for-elementor'); ?>
                        </button>
                        <p class="nf-settings-footer__note">
                            <?php esc_html_e('Changes will take effect after saving. You may need to refresh the Elementor editor.', 'nebula-forge-addons-for-elementor'); ?>
                        </p>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
}
