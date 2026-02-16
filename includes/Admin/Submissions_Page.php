<?php
/**
 * Submissions Page — Lists and manages form submissions.
 *
 * @package NebulaForgeAddon
 * @since   0.7.0
 */

namespace NebulaForgeAddon\Admin;

if (!defined('ABSPATH')) {
    exit;
}

use NebulaForgeAddon\Extensions\Form_Handler;

/**
 * Class Submissions_Page
 *
 * @package NebulaForgeAddon\Admin
 * @since   0.7.0
 */
final class Submissions_Page
{
    /**
     * Render the submissions list or single view.
     */
    public function render(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have permission to access this page.', 'nebula-forge-addons-for-elementor'));
        }

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $view_id = isset($_GET['view']) ? absint($_GET['view']) : 0;

        if ($view_id) {
            $this->render_single($view_id);
        } else {
            $this->render_list();
        }
    }

    /**
     * Render submissions list.
     */
    private function render_list(): void
    {
        // Handle bulk/single delete.
        if (isset($_POST['nfa_delete_submission'], $_POST['nfa_sub_nonce'])) {
            $nonce = sanitize_text_field(wp_unslash($_POST['nfa_sub_nonce']));
            if (wp_verify_nonce($nonce, 'nfa_submissions_action') && current_user_can('manage_options')) {
                $ids = array_map('absint', (array) $_POST['nfa_delete_submission']);
                foreach ($ids as $id) {
                    wp_delete_post($id, true);
                }
            }
        }

        // Handle bulk delete via checkboxes.
        if (isset($_POST['nfa_bulk_action'], $_POST['nfa_sub_nonce']) && $_POST['nfa_bulk_action'] === 'delete') {
            $nonce = sanitize_text_field(wp_unslash($_POST['nfa_sub_nonce']));
            if (wp_verify_nonce($nonce, 'nfa_submissions_action') && current_user_can('manage_options')) {
                $ids = isset($_POST['nfa_sub_ids']) ? array_map('absint', (array) $_POST['nfa_sub_ids']) : [];
                foreach ($ids as $id) {
                    wp_delete_post($id, true);
                }
            }
        }

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $paged = max(1, absint($_GET['paged'] ?? 1));
        $per_page = 20;

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $filter_form = isset($_GET['form_name']) ? sanitize_text_field(wp_unslash($_GET['form_name'])) : '';

        $args = [
            'post_type'      => Form_Handler::POST_TYPE,
            'post_status'    => 'publish',
            'posts_per_page' => $per_page,
            'paged'          => $paged,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ];

        if ($filter_form) {
            $args['meta_query'] = [
                [
                    'key'   => '_nfa_form_name',
                    'value' => $filter_form,
                ],
            ];
        }

        $query = new \WP_Query($args);

        // Get unique form names for filter dropdown.
        global $wpdb;
        $form_names = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} WHERE meta_key = %s ORDER BY meta_value ASC",
                '_nfa_form_name'
            )
        );

        if (!class_exists(Ui_Helper::class) && defined('NEBULA_FORGE_ADDON_PATH')) {
            $path = NEBULA_FORGE_ADDON_PATH . 'includes/Admin/Ui_Helper.php';
            if (file_exists($path)) {
                require_once $path;
            }
        }
        ?>
        <div class="wrap nf-admin-wrap">
            <div class="nf-admin-header nf-admin-header--compact">
                <div class="nf-admin-header__content">
                    <h1>
                        <span class="dashicons dashicons-email-alt"></span>
                        <?php esc_html_e('Form Submissions', 'nebula-forge-addons-for-elementor'); ?>
                    </h1>
                    <p class="nf-admin-header__tagline">
                        <?php
                        printf(
                            /* translators: %d: total submissions */
                            esc_html__('%d total submissions', 'nebula-forge-addons-for-elementor'),
                            $query->found_posts
                        );
                        ?>
                    </p>
                </div>
                <div class="nf-header-stats nf-header-stats--compact">
                    <div class="nf-header-stat">
                        <span class="nf-header-stat__value"><?php echo esc_html($query->found_posts); ?></span>
                        <span class="nf-header-stat__label"><?php esc_html_e('Total', 'nebula-forge-addons-for-elementor'); ?></span>
                    </div>
                </div>
            </div>

            <?php if (class_exists(Ui_Helper::class)) : ?>
                <?php Ui_Helper::render_tabs(Admin_Manager::MENU_SLUG_SUBMISSIONS); ?>
            <?php endif; ?>

            <div class="nf-admin-content">
                <!-- Filter Bar -->
                <form method="get" class="nf-toolbar" style="margin-bottom:16px;">
                    <input type="hidden" name="page" value="<?php echo esc_attr(Admin_Manager::MENU_SLUG_SUBMISSIONS); ?>">
                    <div class="nf-toolbar__left">
                        <select name="form_name" style="min-width:180px;padding:6px 10px;">
                            <option value=""><?php esc_html_e('All Forms', 'nebula-forge-addons-for-elementor'); ?></option>
                            <?php foreach ($form_names as $fn) : ?>
                                <option value="<?php echo esc_attr($fn); ?>" <?php selected($filter_form, $fn); ?>>
                                    <?php echo esc_html($fn); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="nf-button nf-button--outline nf-button--sm">
                            <span class="dashicons dashicons-filter"></span>
                            <?php esc_html_e('Filter', 'nebula-forge-addons-for-elementor'); ?>
                        </button>
                    </div>
                </form>

                <?php if ($query->have_posts()) : ?>
                    <form method="post">
                        <?php wp_nonce_field('nfa_submissions_action', 'nfa_sub_nonce'); ?>
                        <table class="widefat nf-submissions-table" style="border-radius:12px;overflow:hidden;">
                            <thead>
                                <tr>
                                    <th style="width:30px;"><input type="checkbox" id="nfa-check-all"></th>
                                    <th><?php esc_html_e('Form', 'nebula-forge-addons-for-elementor'); ?></th>
                                    <th><?php esc_html_e('Summary', 'nebula-forge-addons-for-elementor'); ?></th>
                                    <th><?php esc_html_e('Date', 'nebula-forge-addons-for-elementor'); ?></th>
                                    <th><?php esc_html_e('Status', 'nebula-forge-addons-for-elementor'); ?></th>
                                    <th style="width:120px;"><?php esc_html_e('Actions', 'nebula-forge-addons-for-elementor'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($query->have_posts()) : $query->the_post();
                                    $post_id   = get_the_ID();
                                    $form_nm   = get_post_meta($post_id, '_nfa_form_name', true);
                                    $data      = get_post_meta($post_id, '_nfa_form_data', true);
                                    $status    = get_post_meta($post_id, '_nfa_form_status', true) ?: 'unread';
                                    $summary   = '';

                                    if (is_array($data)) {
                                        $parts = [];
                                        foreach (array_slice($data, 0, 3) as $d) {
                                            $val = mb_strimwidth($d['value'], 0, 40, '…');
                                            $parts[] = '<strong>' . esc_html($d['label']) . ':</strong> ' . esc_html($val);
                                        }
                                        $summary = implode(' &middot; ', $parts);
                                    }

                                    $view_url = add_query_arg(['page' => Admin_Manager::MENU_SLUG_SUBMISSIONS, 'view' => $post_id], admin_url('admin.php'));
                                ?>
                                <tr class="<?php echo $status === 'unread' ? 'nfa-sub-row--unread' : ''; ?>">
                                    <td><input type="checkbox" name="nfa_sub_ids[]" value="<?php echo esc_attr($post_id); ?>"></td>
                                    <td><strong><?php echo esc_html($form_nm); ?></strong></td>
                                    <td><?php echo wp_kses_post($summary); ?></td>
                                    <td><?php echo esc_html(get_the_date('M j, Y g:i a')); ?></td>
                                    <td>
                                        <?php if ($status === 'unread') : ?>
                                            <span class="nfa-badge nfa-badge--blue"><?php esc_html_e('Unread', 'nebula-forge-addons-for-elementor'); ?></span>
                                        <?php else : ?>
                                            <span class="nfa-badge nfa-badge--gray"><?php esc_html_e('Read', 'nebula-forge-addons-for-elementor'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo esc_url($view_url); ?>" class="button button-small"><?php esc_html_e('View', 'nebula-forge-addons-for-elementor'); ?></a>
                                        <button type="submit" name="nfa_delete_submission" value="<?php echo esc_attr($post_id); ?>" class="button button-small" style="color:#ef4444;" onclick="return confirm('<?php esc_attr_e('Delete this submission?', 'nebula-forge-addons-for-elementor'); ?>');">
                                            <?php esc_html_e('Delete', 'nebula-forge-addons-for-elementor'); ?>
                                        </button>
                                    </td>
                                </tr>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </tbody>
                        </table>

                        <div class="nf-toolbar" style="margin-top:12px;">
                            <div class="nf-toolbar__left">
                                <button type="submit" name="nfa_bulk_action" value="delete" class="nf-button nf-button--outline nf-button--sm" style="color:#ef4444;" onclick="return confirm('<?php esc_attr_e('Delete selected submissions?', 'nebula-forge-addons-for-elementor'); ?>');">
                                    <span class="dashicons dashicons-trash"></span>
                                    <?php esc_html_e('Delete Selected', 'nebula-forge-addons-for-elementor'); ?>
                                </button>
                            </div>
                            <div class="nf-toolbar__right">
                                <?php
                                $total_pages = $query->max_num_pages;
                                if ($total_pages > 1) :
                                    $base_url = admin_url('admin.php?page=' . Admin_Manager::MENU_SLUG_SUBMISSIONS);
                                    if ($filter_form) {
                                        $base_url = add_query_arg('form_name', $filter_form, $base_url);
                                    }
                                ?>
                                    <span style="margin-right:8px;"><?php printf(esc_html__('Page %1$d of %2$d', 'nebula-forge-addons-for-elementor'), $paged, $total_pages); ?></span>
                                    <?php if ($paged > 1) : ?>
                                        <a class="button button-small" href="<?php echo esc_url(add_query_arg('paged', $paged - 1, $base_url)); ?>">&laquo; <?php esc_html_e('Prev', 'nebula-forge-addons-for-elementor'); ?></a>
                                    <?php endif; ?>
                                    <?php if ($paged < $total_pages) : ?>
                                        <a class="button button-small" href="<?php echo esc_url(add_query_arg('paged', $paged + 1, $base_url)); ?>"><?php esc_html_e('Next', 'nebula-forge-addons-for-elementor'); ?> &raquo;</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                <?php else : ?>
                    <div class="nf-card" style="text-align:center;padding:48px;">
                        <span class="dashicons dashicons-email-alt" style="font-size:48px;color:#cbd5e1;margin-bottom:16px;"></span>
                        <h3><?php esc_html_e('No submissions yet', 'nebula-forge-addons-for-elementor'); ?></h3>
                        <p style="color:#64748b;"><?php esc_html_e('Form submissions will appear here once visitors start submitting your forms.', 'nebula-forge-addons-for-elementor'); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <script>
        (function(){
            var ca = document.getElementById('nfa-check-all');
            if(ca){ ca.addEventListener('change', function(){ document.querySelectorAll('input[name="nfa_sub_ids[]"]').forEach(function(c){ c.checked = ca.checked; }); }); }
        })();
        </script>
        <?php
    }

    /**
     * Render single submission detail view.
     */
    private function render_single(int $post_id): void
    {
        $post = get_post($post_id);
        if (!$post || $post->post_type !== Form_Handler::POST_TYPE) {
            echo '<div class="wrap"><h1>' . esc_html__('Submission not found.', 'nebula-forge-addons-for-elementor') . '</h1></div>';
            return;
        }

        // Mark as read.
        update_post_meta($post_id, '_nfa_form_status', 'read');

        $form_name = get_post_meta($post_id, '_nfa_form_name', true);
        $data      = get_post_meta($post_id, '_nfa_form_data', true);
        $ip        = get_post_meta($post_id, '_nfa_form_ip', true);
        $ua        = get_post_meta($post_id, '_nfa_form_ua', true);
        $page_url  = get_post_meta($post_id, '_nfa_form_page', true);
        $date      = get_the_date('F j, Y g:i a', $post_id);
        $back_url  = admin_url('admin.php?page=' . Admin_Manager::MENU_SLUG_SUBMISSIONS);

        if (!class_exists(Ui_Helper::class) && defined('NEBULA_FORGE_ADDON_PATH')) {
            $path = NEBULA_FORGE_ADDON_PATH . 'includes/Admin/Ui_Helper.php';
            if (file_exists($path)) {
                require_once $path;
            }
        }
        ?>
        <div class="wrap nf-admin-wrap">
            <div class="nf-admin-header nf-admin-header--compact">
                <div class="nf-admin-header__content">
                    <h1>
                        <a href="<?php echo esc_url($back_url); ?>" style="text-decoration:none;margin-right:8px;">&larr;</a>
                        <?php esc_html_e('Submission Detail', 'nebula-forge-addons-for-elementor'); ?>
                    </h1>
                    <p class="nf-admin-header__tagline">
                        <?php echo esc_html($form_name); ?> &mdash; <?php echo esc_html($date); ?>
                    </p>
                </div>
            </div>

            <?php if (class_exists(Ui_Helper::class)) : ?>
                <?php Ui_Helper::render_tabs(Admin_Manager::MENU_SLUG_SUBMISSIONS); ?>
            <?php endif; ?>

            <div class="nf-admin-content">
                <div class="nf-card">
                    <h2 style="margin-top:0;">
                        <span class="dashicons dashicons-editor-table" style="margin-right:8px;"></span>
                        <?php esc_html_e('Form Data', 'nebula-forge-addons-for-elementor'); ?>
                    </h2>
                    <?php if (is_array($data) && !empty($data)) : ?>
                        <table class="widefat" style="border-radius:8px;overflow:hidden;">
                            <tbody>
                                <?php foreach ($data as $i => $d) : ?>
                                    <tr class="<?php echo $i % 2 === 0 ? 'alternate' : ''; ?>">
                                        <td style="font-weight:600;width:200px;"><?php echo esc_html($d['label']); ?></td>
                                        <td><?php echo nl2br(esc_html($d['value'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <p><?php esc_html_e('No data available.', 'nebula-forge-addons-for-elementor'); ?></p>
                    <?php endif; ?>
                </div>

                <div class="nf-card" style="margin-top:16px;">
                    <h2 style="margin-top:0;">
                        <span class="dashicons dashicons-info-outline" style="margin-right:8px;"></span>
                        <?php esc_html_e('Metadata', 'nebula-forge-addons-for-elementor'); ?>
                    </h2>
                    <table class="widefat" style="border-radius:8px;overflow:hidden;">
                        <tbody>
                            <tr>
                                <td style="font-weight:600;width:200px;"><?php esc_html_e('Form', 'nebula-forge-addons-for-elementor'); ?></td>
                                <td><?php echo esc_html($form_name); ?></td>
                            </tr>
                            <tr class="alternate">
                                <td style="font-weight:600;"><?php esc_html_e('Date', 'nebula-forge-addons-for-elementor'); ?></td>
                                <td><?php echo esc_html($date); ?></td>
                            </tr>
                            <?php if ($page_url) : ?>
                                <tr>
                                    <td style="font-weight:600;"><?php esc_html_e('Page', 'nebula-forge-addons-for-elementor'); ?></td>
                                    <td><a href="<?php echo esc_url($page_url); ?>" target="_blank"><?php echo esc_html($page_url); ?></a></td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($ip) : ?>
                                <tr class="alternate">
                                    <td style="font-weight:600;"><?php esc_html_e('IP Address', 'nebula-forge-addons-for-elementor'); ?></td>
                                    <td><?php echo esc_html($ip); ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($ua) : ?>
                                <tr>
                                    <td style="font-weight:600;"><?php esc_html_e('User Agent', 'nebula-forge-addons-for-elementor'); ?></td>
                                    <td style="word-break:break-all;"><?php echo esc_html($ua); ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div style="margin-top:16px;">
                    <a href="<?php echo esc_url($back_url); ?>" class="nf-button nf-button--outline">&larr; <?php esc_html_e('Back to Submissions', 'nebula-forge-addons-for-elementor'); ?></a>
                </div>
            </div>
        </div>
        <?php
    }
}
