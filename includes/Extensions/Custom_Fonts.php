<?php
/**
 * Custom Fonts Module
 *
 * Allows users to upload custom font files (.woff2, .woff, .ttf)
 * and automatically registers them with Elementor's font system
 * so they appear in every typography picker.
 *
 * @package NebulaForgeAddon
 * @since   0.6.0
 */

namespace NebulaForgeAddon\Extensions;

if (!defined('ABSPATH')) {
    exit;
}

final class Custom_Fonts
{
    public const OPTION_KEY = 'nfa_custom_fonts';
    public const NONCE_ACTION = 'nfa_custom_fonts_save';
    public const NONCE_NAME = 'nfa_custom_fonts_nonce';

    private static bool $registered = false;

    /**
     * Boot the extension.
     */
    public static function init(): void
    {
        if (self::$registered) {
            return;
        }
        self::$registered = true;

        // Allow font file uploads.
        add_filter('upload_mimes', [__CLASS__, 'allow_font_mimes']);
        add_filter('wp_check_filetype_and_ext', [__CLASS__, 'fix_font_upload'], 10, 5);

        // Register with Elementor's font system.
        add_filter('elementor/fonts/groups', [__CLASS__, 'add_font_group']);
        add_filter('elementor/fonts/additional_fonts', [__CLASS__, 'register_fonts']);

        // Enqueue font CSS on frontend + editor.
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_font_css']);
        add_action('elementor/editor/after_enqueue_styles', [__CLASS__, 'enqueue_font_css']);
        add_action('elementor/preview/enqueue_styles', [__CLASS__, 'enqueue_font_css']);

        // Admin AJAX save handler.
        add_action('wp_ajax_nfa_save_custom_fonts', [__CLASS__, 'ajax_save']);
        add_action('wp_ajax_nfa_delete_custom_font', [__CLASS__, 'ajax_delete']);
    }

    /**
     * Add font MIME types to allowed uploads.
     *
     * @param array $mimes Existing MIME types.
     * @return array
     */
    public static function allow_font_mimes(array $mimes): array
    {
        $mimes['woff']  = 'font/woff';
        $mimes['woff2'] = 'font/woff2';
        $mimes['ttf']   = 'font/ttf';

        return $mimes;
    }

    /**
     * Fix WordPress file type checking for font extensions.
     *
     * @param array       $data     File data array.
     * @param string|null $file     Full path to file.
     * @param string|null $filename The file name.
     * @param string[]|null $mimes  Allowed MIME types.
     * @param string|bool $real_mime Real mime type or false.
     * @return array
     */
    public static function fix_font_upload($data, $file, $filename, $mimes, $real_mime = false): array
    {
        if (!empty($data['ext']) && !empty($data['type'])) {
            return $data;
        }

        $ext = '';
        if (!empty($filename)) {
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        }

        $font_mimes = [
            'woff'  => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf'   => 'font/ttf',
        ];

        if (isset($font_mimes[$ext])) {
            $data['ext']             = $ext;
            $data['type']            = $font_mimes[$ext];
            $data['proper_filename'] = $filename;
        }

        return $data;
    }

    /**
     * Add our custom font group to Elementor.
     *
     * @param array $groups Font groups.
     * @return array
     */
    public static function add_font_group(array $groups): array
    {
        $groups['nfa-custom'] = esc_html__('Nebula Forge Custom', 'nebula-forge-addons-for-elementor');
        return $groups;
    }

    /**
     * Register saved custom fonts with Elementor.
     *
     * @param array $fonts Additional fonts.
     * @return array
     */
    public static function register_fonts(array $fonts): array
    {
        $custom_fonts = self::get_fonts();

        foreach ($custom_fonts as $font) {
            if (!empty($font['family'])) {
                $fonts[$font['family']] = 'nfa-custom';
            }
        }

        return $fonts;
    }

    /**
     * Enqueue inline CSS with @font-face declarations.
     */
    public static function enqueue_font_css(): void
    {
        $fonts = self::get_fonts();
        if (empty($fonts)) {
            return;
        }

        $css = '';
        foreach ($fonts as $font) {
            if (empty($font['family']) || empty($font['files'])) {
                continue;
            }

            foreach ($font['files'] as $variant) {
                $weight = $variant['weight'] ?? '400';
                $style  = $variant['style']  ?? 'normal';
                $url    = $variant['url']    ?? '';

                if (empty($url)) {
                    continue;
                }

                $format = 'truetype';
                if (strpos($url, '.woff2') !== false) {
                    $format = 'woff2';
                } elseif (strpos($url, '.woff') !== false) {
                    $format = 'woff';
                }

                $css .= sprintf(
                    "@font-face{font-family:'%s';src:url('%s') format('%s');font-weight:%s;font-style:%s;font-display:swap;}",
                    esc_attr($font['family']),
                    esc_url($url),
                    $format,
                    esc_attr($weight),
                    esc_attr($style)
                );
            }
        }

        if ($css) {
            wp_register_style('nfa-custom-fonts', false); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
            wp_enqueue_style('nfa-custom-fonts');
            wp_add_inline_style('nfa-custom-fonts', $css);
        }
    }

    /**
     * Handle AJAX save for a custom font.
     */
    public static function ajax_save(): void
    {
        check_ajax_referer(self::NONCE_ACTION, self::NONCE_NAME);

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => esc_html__('Permission denied.', 'nebula-forge-addons-for-elementor')]);
        }

        $family = isset($_POST['font_family']) ? sanitize_text_field(wp_unslash($_POST['font_family'])) : '';
        if (empty($family)) {
            wp_send_json_error(['message' => esc_html__('Font family name is required.', 'nebula-forge-addons-for-elementor')]);
        }

        $files = [];
        $raw_files = isset($_POST['font_files']) ? $_POST['font_files'] : []; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput

        if (is_array($raw_files)) {
            foreach ($raw_files as $file_data) {
                $url    = isset($file_data['url']) ? esc_url_raw(wp_unslash($file_data['url'])) : '';
                $weight = isset($file_data['weight']) ? sanitize_text_field(wp_unslash($file_data['weight'])) : '400';
                $style  = isset($file_data['style']) ? sanitize_text_field(wp_unslash($file_data['style'])) : 'normal';

                if (!empty($url)) {
                    $files[] = [
                        'url'    => $url,
                        'weight' => $weight,
                        'style'  => $style,
                    ];
                }
            }
        }

        if (empty($files)) {
            wp_send_json_error(['message' => esc_html__('At least one font file is required.', 'nebula-forge-addons-for-elementor')]);
        }

        $fonts = self::get_fonts();

        // Generate unique ID.
        $id = sanitize_title($family) . '-' . wp_rand(1000, 9999);

        $fonts[$id] = [
            'family' => $family,
            'files'  => $files,
        ];

        update_option(self::OPTION_KEY, $fonts);

        wp_send_json_success([
            'message' => esc_html__('Font saved successfully.', 'nebula-forge-addons-for-elementor'),
            'fonts'   => $fonts,
        ]);
    }

    /**
     * Handle AJAX delete for a custom font.
     */
    public static function ajax_delete(): void
    {
        check_ajax_referer(self::NONCE_ACTION, self::NONCE_NAME);

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => esc_html__('Permission denied.', 'nebula-forge-addons-for-elementor')]);
        }

        $font_id = isset($_POST['font_id']) ? sanitize_key(wp_unslash($_POST['font_id'])) : '';
        if (empty($font_id)) {
            wp_send_json_error(['message' => esc_html__('Font ID is required.', 'nebula-forge-addons-for-elementor')]);
        }

        $fonts = self::get_fonts();
        if (isset($fonts[$font_id])) {
            unset($fonts[$font_id]);
            update_option(self::OPTION_KEY, $fonts);
        }

        wp_send_json_success([
            'message' => esc_html__('Font deleted.', 'nebula-forge-addons-for-elementor'),
            'fonts'   => $fonts,
        ]);
    }

    /**
     * Get saved custom fonts.
     *
     * @return array
     */
    public static function get_fonts(): array
    {
        $fonts = get_option(self::OPTION_KEY, []);
        return is_array($fonts) ? $fonts : [];
    }
}
