<?php
/**
 * Nebula Forge Addons for Elementor — Uninstall
 *
 * Fires when the plugin is deleted via the WordPress admin.
 * Cleans up all options and custom post type data created by the plugin.
 *
 * @package NebulaForgeAddon
 */

// If uninstall not called from WordPress, exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// ── Options ──────────────────────────────────────────────────────────────────
$options = [
    'nfa_enabled_widgets',
    'nfa_enabled_extensions',
    'nfa_settings',
    'nfa_custom_fonts',
    'nfa_activation_redirect',
    'nfa_version',
];

foreach ($options as $option) {
    delete_option($option);
}

// ── Form Submissions (custom post type: nfa_submission) ──────────────────────
$submissions = get_posts([
    'post_type'      => 'nfa_submission',
    'post_status'    => 'any',
    'posts_per_page' => -1,
    'fields'         => 'ids',
]);

if (!empty($submissions)) {
    foreach ($submissions as $post_id) {
        wp_delete_post($post_id, true); // true = force delete, skip trash.
    }
}

// ── Transients ───────────────────────────────────────────────────────────────
delete_transient('nfa_changelog_data');

// ── User meta (dismiss notices, etc.) ────────────────────────────────────────
delete_metadata('user', 0, 'nfa_dismissed_welcome', '', true);
