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

        // Save extensions.
        $enabled_extensions = [];
        $available_extensions = Admin_Manager::get_available_extensions();

        $raw_extensions = isset($post['nf_extensions']) ? $post['nf_extensions'] : [];
        if (is_array($raw_extensions)) {
            foreach ($raw_extensions as $ext_key) {
                $sanitized = sanitize_key($ext_key);
                if (array_key_exists($sanitized, $available_extensions)) {
                    $enabled_extensions[] = $sanitized;
                }
            }
        }

        update_option(Admin_Manager::OPTION_EXTENSIONS, $enabled_extensions);

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
        $extensions = Admin_Manager::get_available_extensions();
        $enabled_extensions = Admin_Manager::get_enabled_extensions();
        $total = count($widgets);
        $active = count($enabled_widgets);
        $ext_active = count($enabled_extensions);
        $ext_total = count($extensions);
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- simple status flag for UI, not processing sensitive form data.
        $settings_updated = isset($_GET['settings-updated']) && sanitize_text_field(wp_unslash($_GET['settings-updated'])) === '1';
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
            <div class="nf-admin-header nf-admin-header--compact">
                <div class="nf-admin-header__content">
                    <h1>
                        <span class="dashicons dashicons-admin-generic"></span>
                        <?php esc_html_e('Widget & Extension Settings', 'nebula-forge-addons-for-elementor'); ?>
                    </h1>
                    <p class="nf-admin-header__tagline">
                        <?php esc_html_e('Manage all widgets and pro extensions. Everything below is included free — no upgrade needed.', 'nebula-forge-addons-for-elementor'); ?>
                    </p>
                </div>
                <div class="nf-header-stats nf-header-stats--compact">
                    <div class="nf-header-stat">
                        <span class="nf-header-stat__value"><?php echo esc_html($active); ?></span>
                        <span class="nf-header-stat__label"><?php esc_html_e('Widgets On', 'nebula-forge-addons-for-elementor'); ?></span>
                    </div>
                    <div class="nf-header-stat">
                        <span class="nf-header-stat__value"><?php echo esc_html($total - $active); ?></span>
                        <span class="nf-header-stat__label"><?php esc_html_e('Widgets Off', 'nebula-forge-addons-for-elementor'); ?></span>
                    </div>
                    <div class="nf-header-stat">
                        <span class="nf-header-stat__value"><?php echo esc_html($ext_active); ?></span>
                        <span class="nf-header-stat__label"><?php esc_html_e('Extensions', 'nebula-forge-addons-for-elementor'); ?></span>
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

                    <!-- ── Pro Extensions Section (top, prominent) ── -->
                    <div class="nf-pro-section">
                        <div class="nf-pro-section__header">
                            <div>
                                <h2 class="nf-pro-section__title">
                                    <span class="dashicons dashicons-admin-plugins"></span>
                                    <?php esc_html_e('Pro Extensions', 'nebula-forge-addons-for-elementor'); ?>
                                    <span class="nf-pro-section__free-badge"><?php esc_html_e('ALL FREE', 'nebula-forge-addons-for-elementor'); ?></span>
                                </h2>
                                <p class="nf-pro-section__desc">
                                    <?php esc_html_e('Advanced capabilities that other plugins charge a premium for. Every extension below is included at no cost.', 'nebula-forge-addons-for-elementor'); ?>
                                </p>
                            </div>
                            <span class="nf-toolbar__counter">
                                <span><?php echo esc_html($ext_active); ?></span> / <?php echo esc_html($ext_total); ?>
                                <?php esc_html_e('active', 'nebula-forge-addons-for-elementor'); ?>
                            </span>
                        </div>
                        <div class="nf-extensions-grid">
                            <?php foreach ($extensions as $ext_key => $ext) : ?>
                                <?php $is_ext_enabled = in_array($ext_key, $enabled_extensions, true); ?>
                                <div class="nf-extension-card <?php echo $is_ext_enabled ? '' : 'nf-extension-card--disabled'; ?>">
                                    <div class="nf-extension-card__icon">
                                        <span class="dashicons <?php echo esc_attr($ext['icon']); ?>"></span>
                                    </div>
                                    <div class="nf-extension-card__body">
                                        <div class="nf-extension-card__header">
                                            <span class="nf-extension-card__label">
                                                <?php echo esc_html($ext['label']); ?>
                                                <span class="nf-extension-card__pro-chip"><?php esc_html_e('FREE', 'nebula-forge-addons-for-elementor'); ?></span>
                                            </span>
                                            <label class="nf-toggle">
                                                <input type="checkbox" name="nf_extensions[]" value="<?php echo esc_attr($ext_key); ?>" <?php checked($is_ext_enabled); ?>>
                                                <span class="nf-toggle__slider"></span>
                                            </label>
                                        </div>
                                        <p class="nf-extension-card__desc"><?php echo esc_html($ext['description']); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- ── Widgets Section ── -->
                    <div class="nf-widgets-section">
                        <div class="nf-widgets-section__header">
                            <h2 class="nf-widgets-section__title">
                                <span class="dashicons dashicons-screenoptions"></span>
                                <?php esc_html_e('Widgets', 'nebula-forge-addons-for-elementor'); ?>
                            </h2>
                            <p class="nf-widgets-section__desc">
                                <?php esc_html_e('Enable or disable individual widgets. Only active widgets appear in the Elementor editor panel.', 'nebula-forge-addons-for-elementor'); ?>
                            </p>
                        </div>

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
                            <div class="nf-toolbar__center">
                                <input type="text" id="nf-widget-search" class="nf-search-input" placeholder="<?php esc_attr_e('Search widgets...', 'nebula-forge-addons-for-elementor'); ?>" autocomplete="off">
                            </div>
                            <div class="nf-toolbar__right">
                                <span class="nf-toolbar__counter">
                                    <span id="nf-active-count"><?php echo esc_html($active); ?></span> / <?php echo esc_html($total); ?>
                                    <?php esc_html_e('active', 'nebula-forge-addons-for-elementor'); ?>
                                </span>
                            </div>
                        </div>

                        <!-- Category Filter Buttons -->
                        <div class="nf-category-filter">
                            <button type="button" class="nf-category-filter__btn nf-category-filter__btn--active" data-category="all">
                                <?php esc_html_e('All', 'nebula-forge-addons-for-elementor'); ?>
                                <span class="nf-category-filter__count"><?php echo esc_html($total); ?></span>
                            </button>
                            <?php foreach ($categories as $cat_key => $cat) : ?>
                                <?php if (empty($cat['widgets'])) continue; ?>
                                <button type="button" class="nf-category-filter__btn" data-category="<?php echo esc_attr($cat_key); ?>">
                                    <span class="dashicons <?php echo esc_attr($cat['icon']); ?>"></span>
                                    <?php echo esc_html($cat['label']); ?>
                                    <span class="nf-category-filter__count"><?php echo esc_html(count($cat['widgets'])); ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>

                        <!-- Widgets by Category -->
                        <?php foreach ($categories as $cat_key => $cat) : ?>
                            <?php if (empty($cat['widgets'])) continue; ?>
                            <div class="nf-settings-category" data-cat="<?php echo esc_attr($cat_key); ?>">
                                <h3 class="nf-settings-category__title">
                                    <span class="dashicons <?php echo esc_attr($cat['icon']); ?>"></span>
                                    <?php echo esc_html($cat['label']); ?>
                                    <span class="nf-category-section__count"><?php echo esc_html(count($cat['widgets'])); ?></span>
                                </h3>
                                <div class="nf-widget-grid">
                                    <?php foreach ($cat['widgets'] as $widget_key => $widget_data) : ?>
                                        <?php
                                        $is_enabled = in_array($widget_key, $enabled_widgets, true);
                                        $card_class = $is_enabled ? 'nf-widget-card' : 'nf-widget-card nf-widget-card--disabled';
                                        $tooltip = isset($widget_data['tooltip']) ? $widget_data['tooltip'] : '';
                                        $badge = isset($widget_data['badge']) ? $widget_data['badge'] : '';
                                        $badge_color = isset($widget_data['badge_color']) ? sanitize_hex_color($widget_data['badge_color']) : '';
                                        $badge_style = $badge_color ? '--nf-badge-color: ' . $badge_color . ';' : '';
                                        ?>
                                        <div class="<?php echo esc_attr($card_class); ?>" data-widget="<?php echo esc_attr($widget_key); ?>" data-label="<?php echo esc_attr(strtolower($widget_data['label'])); ?>">
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

            /* Category Filter */
            var catBtns = document.querySelectorAll('.nf-category-filter__btn');
            var catSections = document.querySelectorAll('.nf-settings-category');
            catBtns.forEach(function(btn){
                btn.addEventListener('click', function(){
                    catBtns.forEach(function(b){ b.classList.remove('nf-category-filter__btn--active'); });
                    btn.classList.add('nf-category-filter__btn--active');
                    var cat = btn.getAttribute('data-category');
                    catSections.forEach(function(sec){
                        if(cat === 'all' || sec.getAttribute('data-cat') === cat){
                            sec.style.display = '';
                        } else {
                            sec.style.display = 'none';
                        }
                    });
                });
            });

            /* Search Filter */
            var searchInput = document.getElementById('nf-widget-search');
            if(searchInput){
                searchInput.addEventListener('input', function(){
                    var query = this.value.toLowerCase().trim();
                    var cards = form.querySelectorAll('.nf-widget-card');
                    cards.forEach(function(card){
                        var label = card.getAttribute('data-label') || '';
                        if(!query || label.indexOf(query) !== -1){
                            card.style.display = '';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                    /* Show all categories when searching */
                    if(query){
                        catSections.forEach(function(sec){ sec.style.display = ''; });
                        catBtns.forEach(function(b){ b.classList.remove('nf-category-filter__btn--active'); });
                        catBtns[0].classList.add('nf-category-filter__btn--active');
                    }
                });
            }
        })();
        </script>
        <?php
    }
}
