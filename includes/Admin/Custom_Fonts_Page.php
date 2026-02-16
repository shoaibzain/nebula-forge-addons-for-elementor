<?php
/**
 * Custom Fonts Page
 *
 * Admin page allowing users to upload and manage custom font files
 * for use inside Elementor's typography controls.
 *
 * @package NebulaForgeAddon
 * @since   0.6.0
 */

namespace NebulaForgeAddon\Admin;

if (!defined('ABSPATH')) {
    exit;
}

use NebulaForgeAddon\Extensions\Custom_Fonts;

final class Custom_Fonts_Page
{
    /**
     * Render the Custom Fonts admin page.
     */
    public function render(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to access this page.', 'nebula-forge-addons-for-elementor'));
        }

        $fonts = Custom_Fonts::get_fonts();
        ?>
        <div class="wrap nf-admin-wrap">
            <div class="nf-admin-header nf-admin-header--compact">
                <div class="nf-admin-header__content">
                    <h1>
                        <span class="dashicons dashicons-editor-textcolor"></span>
                        <?php esc_html_e('Custom Fonts', 'nebula-forge-addons-for-elementor'); ?>
                    </h1>
                    <p class="nf-admin-header__tagline">
                        <?php esc_html_e('Upload custom font files (.woff2, .woff, .ttf) to use them in Elementor\'s typography controls.', 'nebula-forge-addons-for-elementor'); ?>
                    </p>
                </div>
            </div>

            <?php if (class_exists(Ui_Helper::class)) : ?>
                <?php Ui_Helper::render_tabs(Admin_Manager::MENU_SLUG_FONTS); ?>
            <?php endif; ?>

            <div class="nf-admin-content">
                <!-- Add New Font Form -->
                <div class="nf-card nf-card--add-font">
                    <h3 class="nf-card__title">
                        <span class="dashicons dashicons-plus-alt2"></span>
                        <?php esc_html_e('Add New Font', 'nebula-forge-addons-for-elementor'); ?>
                    </h3>

                    <div class="nf-font-form" id="nfa-font-form">
                        <div class="nf-font-form__row">
                            <label for="nfa-font-family"><?php esc_html_e('Font Family Name', 'nebula-forge-addons-for-elementor'); ?></label>
                            <input type="text" id="nfa-font-family" class="regular-text" placeholder="<?php esc_attr_e('e.g. Satoshi', 'nebula-forge-addons-for-elementor'); ?>" />
                        </div>

                        <div class="nf-font-variants" id="nfa-font-variants">
                            <h4><?php esc_html_e('Font Files', 'nebula-forge-addons-for-elementor'); ?></h4>

                            <div class="nf-font-variant" data-variant="0">
                                <div class="nf-font-variant__row">
                                    <div class="nf-font-variant__field">
                                        <label><?php esc_html_e('Weight', 'nebula-forge-addons-for-elementor'); ?></label>
                                        <select class="nfa-font-weight">
                                            <option value="100">100 (Thin)</option>
                                            <option value="200">200 (Extra Light)</option>
                                            <option value="300">300 (Light)</option>
                                            <option value="400" selected>400 (Regular)</option>
                                            <option value="500">500 (Medium)</option>
                                            <option value="600">600 (Semi Bold)</option>
                                            <option value="700">700 (Bold)</option>
                                            <option value="800">800 (Extra Bold)</option>
                                            <option value="900">900 (Black)</option>
                                        </select>
                                    </div>
                                    <div class="nf-font-variant__field">
                                        <label><?php esc_html_e('Style', 'nebula-forge-addons-for-elementor'); ?></label>
                                        <select class="nfa-font-style">
                                            <option value="normal"><?php esc_html_e('Normal', 'nebula-forge-addons-for-elementor'); ?></option>
                                            <option value="italic"><?php esc_html_e('Italic', 'nebula-forge-addons-for-elementor'); ?></option>
                                        </select>
                                    </div>
                                    <div class="nf-font-variant__field nf-font-variant__field--file">
                                        <label><?php esc_html_e('Font File', 'nebula-forge-addons-for-elementor'); ?></label>
                                        <button type="button" class="button nfa-upload-font-btn"><?php esc_html_e('Upload File', 'nebula-forge-addons-for-elementor'); ?></button>
                                        <span class="nfa-font-filename"></span>
                                        <input type="hidden" class="nfa-font-url" value="" />
                                    </div>
                                    <button type="button" class="button nfa-remove-variant" title="<?php esc_attr_e('Remove', 'nebula-forge-addons-for-elementor'); ?>">&times;</button>
                                </div>
                            </div>
                        </div>

                        <div class="nf-font-form__actions">
                            <button type="button" class="button" id="nfa-add-variant">
                                <span class="dashicons dashicons-plus"></span>
                                <?php esc_html_e('Add Variant', 'nebula-forge-addons-for-elementor'); ?>
                            </button>
                            <button type="button" class="button button-primary" id="nfa-save-font">
                                <span class="dashicons dashicons-saved"></span>
                                <?php esc_html_e('Save Font', 'nebula-forge-addons-for-elementor'); ?>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Existing Fonts -->
                <div class="nf-card nf-card--font-list">
                    <h3 class="nf-card__title">
                        <span class="dashicons dashicons-list-view"></span>
                        <?php esc_html_e('Saved Fonts', 'nebula-forge-addons-for-elementor'); ?>
                        <span class="nf-badge-chip" style="--nf-badge-color:#6366f1"><?php echo esc_html(count($fonts)); ?></span>
                    </h3>

                    <div id="nfa-fonts-list">
                        <?php if (empty($fonts)) : ?>
                            <p class="nf-empty-state"><?php esc_html_e('No custom fonts yet. Use the form above to add one.', 'nebula-forge-addons-for-elementor'); ?></p>
                        <?php else : ?>
                            <?php foreach ($fonts as $font_id => $font) : ?>
                                <div class="nf-font-item" data-font-id="<?php echo esc_attr($font_id); ?>">
                                    <div class="nf-font-item__info">
                                        <strong class="nf-font-item__name" style="font-family: '<?php echo esc_attr($font['family']); ?>', sans-serif;">
                                            <?php echo esc_html($font['family']); ?>
                                        </strong>
                                        <span class="nf-font-item__variants">
                                            <?php
                                            $variant_labels = [];
                                            foreach ($font['files'] as $v) {
                                                $variant_labels[] = ($v['weight'] ?? '400') . ($v['style'] === 'italic' ? 'i' : '');
                                            }
                                            echo esc_html(implode(', ', $variant_labels));
                                            ?>
                                        </span>
                                    </div>
                                    <div class="nf-font-item__preview" style="font-family: '<?php echo esc_attr($font['family']); ?>', sans-serif;">
                                        <?php esc_html_e('The quick brown fox jumps over the lazy dog', 'nebula-forge-addons-for-elementor'); ?>
                                    </div>
                                    <button type="button" class="button nfa-delete-font" data-font-id="<?php echo esc_attr($font_id); ?>">
                                        <span class="dashicons dashicons-trash"></span>
                                        <?php esc_html_e('Delete', 'nebula-forge-addons-for-elementor'); ?>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <script>
        (function($) {
            'use strict';

            var variantIndex = 1;
            var nonce = '<?php echo esc_js(wp_create_nonce(Custom_Fonts::NONCE_ACTION)); ?>';

            // Upload font file via media uploader.
            $(document).on('click', '.nfa-upload-font-btn', function(e) {
                e.preventDefault();
                var $btn = $(this);
                var $row = $btn.closest('.nf-font-variant');
                var frame = wp.media({
                    title: '<?php echo esc_js(__('Select Font File', 'nebula-forge-addons-for-elementor')); ?>',
                    multiple: false,
                    library: { type: ['font/woff', 'font/woff2', 'font/ttf', 'application/x-font-ttf', 'application/x-font-woff', 'application/font-woff', 'application/font-woff2'] }
                });
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $row.find('.nfa-font-url').val(attachment.url);
                    $row.find('.nfa-font-filename').text(attachment.filename || attachment.url.split('/').pop());
                });
                frame.open();
            });

            // Add variant row.
            $('#nfa-add-variant').on('click', function() {
                var $template = $('.nf-font-variant').first().clone();
                $template.attr('data-variant', variantIndex++);
                $template.find('.nfa-font-url').val('');
                $template.find('.nfa-font-filename').text('');
                $template.find('.nfa-font-weight').val('400');
                $template.find('.nfa-font-style').val('normal');
                $('#nfa-font-variants').append($template);
            });

            // Remove variant row.
            $(document).on('click', '.nfa-remove-variant', function() {
                if ($('.nf-font-variant').length > 1) {
                    $(this).closest('.nf-font-variant').remove();
                }
            });

            // Save font.
            $('#nfa-save-font').on('click', function() {
                var family = $('#nfa-font-family').val().trim();
                if (!family) {
                    alert('<?php echo esc_js(__('Please enter a font family name.', 'nebula-forge-addons-for-elementor')); ?>');
                    return;
                }
                var files = [];
                $('.nf-font-variant').each(function() {
                    var url = $(this).find('.nfa-font-url').val();
                    if (url) {
                        files.push({
                            url: url,
                            weight: $(this).find('.nfa-font-weight').val(),
                            style: $(this).find('.nfa-font-style').val()
                        });
                    }
                });
                if (!files.length) {
                    alert('<?php echo esc_js(__('Please upload at least one font file.', 'nebula-forge-addons-for-elementor')); ?>');
                    return;
                }
                var $btn = $(this).prop('disabled', true);
                $.post(ajaxurl, {
                    action: 'nfa_save_custom_fonts',
                    <?php echo esc_js(Custom_Fonts::NONCE_NAME); ?>: nonce,
                    font_family: family,
                    font_files: files
                }, function(resp) {
                    $btn.prop('disabled', false);
                    if (resp.success) {
                        location.reload();
                    } else {
                        alert(resp.data.message || 'Error');
                    }
                });
            });

            // Delete font.
            $(document).on('click', '.nfa-delete-font', function() {
                if (!confirm('<?php echo esc_js(__('Delete this font?', 'nebula-forge-addons-for-elementor')); ?>')) return;
                var $item = $(this).closest('.nf-font-item');
                var fontId = $(this).data('font-id');
                $.post(ajaxurl, {
                    action: 'nfa_delete_custom_font',
                    <?php echo esc_js(Custom_Fonts::NONCE_NAME); ?>: nonce,
                    font_id: fontId
                }, function(resp) {
                    if (resp.success) {
                        $item.fadeOut(300, function() { $(this).remove(); });
                    }
                });
            });
        })(jQuery);
        </script>
        <?php
    }
}
