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
        $total = count($widgets);
        $active = count($enabled_widgets);
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
            <div class="nf-admin-header nf-admin-header--compact">
                <div class="nf-admin-header__content">
                    <h1>
                        <span class="dashicons dashicons-admin-generic"></span>
                        <?php esc_html_e('Widget Settings', 'nebula-forge-addons-for-elementor'); ?>
                    </h1>
                    <p class="nf-admin-header__tagline">
                        <?php esc_html_e('Enable or disable individual widgets. Only active widgets appear in the Elementor editor.', 'nebula-forge-addons-for-elementor'); ?>
                    </p>
                </div>
                <div class="nf-header-stats nf-header-stats--compact">
                    <div class="nf-header-stat">
                        <span class="nf-header-stat__value"><?php echo esc_html($active); ?></span>
                        <span class="nf-header-stat__label"><?php esc_html_e('Active', 'nebula-forge-addons-for-elementor'); ?></span>
                    </div>
                    <div class="nf-header-stat">
                        <span class="nf-header-stat__value"><?php echo esc_html($total - $active); ?></span>
                        <span class="nf-header-stat__label"><?php esc_html_e('Disabled', 'nebula-forge-addons-for-elementor'); ?></span>
                    </div>
                </div>
            </div>

            <?php if (class_exists(Ui_Helper::class)) : ?>
                <?php Ui_Helper::render_tabs(Admin_Manager::MENU_SLUG_SETTINGS); ?>
            <?php endif; ?>

            <div class="nf-admin-content nf-settings-content">
                <?php if ($settings_updated) : ?>
                    <div class="nf-notice nf-notice--success">
                        <span class="dashicons dashicons-yes-alt"></span>
                        <?php esc_html_e('Settings saved successfully.', 'nebula-forge-addons-for-elementor'); ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="" class="nf-settings-form">
                    <?php wp_nonce_field(self::NONCE_ACTION, self::NONCE_NAME); ?>

                    <!-- Toolbar -->
                    <div class="nf-toolbar">
                        <div class="nf-toolbar__left">
                            <button type="button" class="nf-button nf-button--outline nf-button--sm" id="nf-enable-all">
                                <span class="dashicons dashicons-yes"></span>
                                <?php esc_html_e('Enable All', 'nebula-forge-addons-for-elementor'); ?>
                            </button>
                            <button type="button" class="nf-button nf-button--outline nf-button--sm" id="nf-disable-all">
                                <span class="dashicons dashicons-no-alt"></span>
                                <?php esc_html_e('Disable All', 'nebula-forge-addons-for-elementor'); ?>
                            </button>
                        </div>
                        <div class="nf-toolbar__right">
                            <span class="nf-toolbar__counter">
                                <span id="nf-active-count"><?php echo esc_html($active); ?></span> / <?php echo esc_html($total); ?>
                                <?php esc_html_e('active', 'nebula-forge-addons-for-elementor'); ?>
                            </span>
                        </div>
                    </div>

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
                                <div class="nf-widget-card__header">
                                    <div class="nf-widget-card__icon" style="<?php echo esc_attr($badge_style); ?>">
                                        <span class="<?php echo esc_attr($widget_data['icon']); ?>"></span>
                                    </div>
                                    <label class="nf-toggle">
                                        <input
                                            type="checkbox"
                                            name="nf_widgets[]"
                                            value="<?php echo esc_attr($widget_key); ?>"
                                            <?php checked($is_enabled); ?>
                                            class="nf-widget-toggle"
                                        >
                                        <span class="nf-toggle__slider"></span>
                                    </label>
                                </div>
                                <div class="nf-widget-card__content">
                                    <h3>
                                        <?php echo esc_html($widget_data['label']); ?>
                                        <?php if (!empty($badge)) : ?>
                                            <span class="nf-badge-chip" style="<?php echo esc_attr($badge_style); ?>">
                                                <?php echo esc_html($badge); ?>
                                            </span>
                                        <?php endif; ?>
                                    </h3>
                                    <p><?php echo esc_html($widget_data['description']); ?></p>
                                </div>
                                <?php if (!empty($tooltip)) : ?>
                                    <div class="nf-widget-card__footer">
                                        <span class="dashicons dashicons-info-outline"></span>
                                        <span class="nf-widget-card__tip"><?php echo esc_html($tooltip); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="nf-settings-footer">
                        <button type="submit" class="nf-button nf-button--primary">
                            <span class="dashicons dashicons-saved"></span>
                            <?php esc_html_e('Save Settings', 'nebula-forge-addons-for-elementor'); ?>
                        </button>
                        <p class="nf-settings-footer__note">
                            <?php esc_html_e('Changes take effect immediately. Refresh the Elementor editor to see updates.', 'nebula-forge-addons-for-elementor'); ?>
                        </p>
                    </div>
                </form>
            </div>
        </div>
        <script>
        (function(){
            var form = document.querySelector('.nf-settings-form');
            if (!form) return;
            var toggles = form.querySelectorAll('.nf-widget-toggle');
            var counter = document.getElementById('nf-active-count');

            function updateUI(){
                var active = 0;
                toggles.forEach(function(t){
                    var card = t.closest('.nf-widget-card');
                    if (t.checked){
                        card.classList.remove('nf-widget-card--disabled');
                        active++;
                    } else {
                        card.classList.add('nf-widget-card--disabled');
                    }
                });
                if(counter) counter.textContent = active;
            }

            toggles.forEach(function(t){ t.addEventListener('change', updateUI); });

            var enableAll = document.getElementById('nf-enable-all');
            var disableAll = document.getElementById('nf-disable-all');
            if(enableAll) enableAll.addEventListener('click', function(){ toggles.forEach(function(t){ t.checked = true; }); updateUI(); });
            if(disableAll) disableAll.addEventListener('click', function(){ toggles.forEach(function(t){ t.checked = false; }); updateUI(); });
        })();
        </script>
        <?php
    }
}
