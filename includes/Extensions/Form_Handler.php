<?php
/**
 * Form Handler — Processes AJAX form submissions.
 *
 * Handles validation, database storage, and email notifications
 * for the Advanced Form widget.
 *
 * @package NebulaForgeAddon
 * @since   0.7.0
 */

namespace NebulaForgeAddon\Extensions;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Form_Handler
 *
 * @package NebulaForgeAddon\Extensions
 * @since   0.7.0
 */
final class Form_Handler
{
    /**
     * Custom post type slug for submissions.
     */
    public const POST_TYPE = 'nfa_submission';

    /**
     * Whether the handler has been initialised.
     */
    private static bool $initialized = false;

    /**
     * Boot hooks.
     */
    public static function init(): void
    {
        if (self::$initialized) {
            return;
        }
        self::$initialized = true;

        add_action('init', [self::class, 'register_post_type']);
        add_action('wp_ajax_nfa_form_submit', [self::class, 'handle_submission']);
        add_action('wp_ajax_nopriv_nfa_form_submit', [self::class, 'handle_submission']);
    }

    /**
     * Register hidden CPT for form submissions.
     */
    public static function register_post_type(): void
    {
        register_post_type(self::POST_TYPE, [
            'labels' => [
                'name'          => __('Form Submissions', 'nebula-forge-addons-for-elementor'),
                'singular_name' => __('Submission', 'nebula-forge-addons-for-elementor'),
            ],
            'public'              => false,
            'show_ui'             => false,
            'show_in_menu'        => false,
            'show_in_admin_bar'   => false,
            'exclude_from_search' => true,
            'capability_type'     => 'post',
            'supports'            => ['title'],
        ]);
    }

    /**
     * Handle form AJAX submission.
     */
    public static function handle_submission(): void
    {
        // Verify nonce.
        $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
        if (!wp_verify_nonce($nonce, 'nfa_form_submit')) {
            wp_send_json_error(['message' => __('Security check failed. Please refresh and try again.', 'nebula-forge-addons-for-elementor')], 403);
        }

        // Rate-limiting via transient.
        $ip = self::get_client_ip();
        $rate_key = 'nfa_form_rate_' . md5($ip);
        $rate = (int) get_transient($rate_key);

        if ($rate >= 5) {
            wp_send_json_error(['message' => __('Too many submissions. Please try again later.', 'nebula-forge-addons-for-elementor')], 429);
        }

        set_transient($rate_key, $rate + 1, 60);

        // Gather settings from POST.
        $form_name     = sanitize_text_field(wp_unslash($_POST['form_name'] ?? 'Form'));
        $action_save   = !empty($_POST['action_save']);
        $action_email  = !empty($_POST['action_email']);
        $email_to      = sanitize_email(wp_unslash($_POST['email_to'] ?? ''));
        $email_subject = sanitize_text_field(wp_unslash($_POST['email_subject'] ?? ''));
        $email_from    = sanitize_text_field(wp_unslash($_POST['email_from_name'] ?? get_option('blogname')));
        $reply_to_key  = sanitize_text_field(wp_unslash($_POST['email_reply_to'] ?? ''));

        // Parse form fields.
        $raw_fields = isset($_POST['fields']) ? wp_unslash($_POST['fields']) : '[]';
        $fields = json_decode($raw_fields, true);

        if (!is_array($fields) || empty($fields)) {
            wp_send_json_error(['message' => __('No form data received.', 'nebula-forge-addons-for-elementor')], 400);
        }

        // Sanitize field data.
        $sanitized = [];
        $reply_to_email = '';

        foreach ($fields as $field) {
            $label = sanitize_text_field($field['label'] ?? '');
            $value = '';

            if (is_array($field['value'] ?? null)) {
                $value = implode(', ', array_map('sanitize_text_field', $field['value']));
            } else {
                $value = sanitize_textarea_field($field['value'] ?? '');
            }

            $sanitized[] = [
                'label' => $label,
                'value' => $value,
            ];

            // Match reply-to field.
            if ($reply_to_key && strtolower($label) === strtolower($reply_to_key) && is_email($value)) {
                $reply_to_email = $value;
            }
        }

        // Save to database.
        $post_id = 0;
        if ($action_save) {
            $post_id = wp_insert_post([
                'post_type'   => self::POST_TYPE,
                'post_status' => 'publish',
                'post_title'  => $form_name . ' — ' . current_time('Y-m-d H:i'),
            ]);

            if ($post_id && !is_wp_error($post_id)) {
                update_post_meta($post_id, '_nfa_form_name', $form_name);
                update_post_meta($post_id, '_nfa_form_data', $sanitized);
                update_post_meta($post_id, '_nfa_form_ip', $ip);
                update_post_meta($post_id, '_nfa_form_ua', sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'] ?? '')));
                update_post_meta($post_id, '_nfa_form_page', sanitize_url(wp_unslash($_POST['page_url'] ?? '')));
                update_post_meta($post_id, '_nfa_form_status', 'unread');
            }
        }

        // Send email.
        if ($action_email && $email_to) {
            $subject = str_replace('{form_name}', $form_name, $email_subject);

            $body = sprintf(
                /* translators: %s: form name */
                __('New submission from: %s', 'nebula-forge-addons-for-elementor'),
                $form_name
            ) . "\n\n";

            foreach ($sanitized as $f) {
                $body .= $f['label'] . ': ' . $f['value'] . "\n";
            }

            $body .= "\n---\n";
            $body .= sprintf(
                /* translators: %s: date/time */
                __('Submitted at: %s', 'nebula-forge-addons-for-elementor'),
                current_time('Y-m-d H:i:s')
            );

            $headers = ['Content-Type: text/plain; charset=UTF-8'];
            if ($email_from) {
                $headers[] = 'From: ' . $email_from . ' <' . get_option('admin_email') . '>';
            }
            if ($reply_to_email) {
                $headers[] = 'Reply-To: ' . $reply_to_email;
            }

            wp_mail($email_to, $subject, $body, $headers);
        }

        wp_send_json_success([
            'message' => __('Submission received.', 'nebula-forge-addons-for-elementor'),
            'id'      => $post_id,
        ]);
    }

    /**
     * Get the client IP address.
     */
    private static function get_client_ip(): string
    {
        // Only trust REMOTE_ADDR to prevent IP spoofing via proxy headers.
        // If behind a trusted reverse proxy (e.g. Cloudflare), filter in your
        // server config or WordPress constants instead.
        $ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '';

        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }

        return '0.0.0.0';
    }
}
