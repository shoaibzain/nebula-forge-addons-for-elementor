<?php
/**
 * Changelog Page - Displays plugin changelog from readme.txt.
 *
 * @package NebulaForgeAddon
 * @since   0.2.0
 */

namespace NebulaForgeAddon\Admin;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Changelog_Page
 *
 * Renders the changelog admin page.
 */
final class Changelog_Page
{
    /**
     * Render the changelog page.
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
        $changelog_entries = $this->parse_changelog();
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
                        <span class="dashicons dashicons-backup"></span>
                        <?php esc_html_e('Changelog', 'nebula-forge-addons-for-elementor'); ?>
                    </h1>
                    <p class="nf-admin-header__tagline">
                        <?php esc_html_e('Track every improvement, fix, and new feature across releases.', 'nebula-forge-addons-for-elementor'); ?>
                    </p>
                </div>
                <div class="nf-header-stats nf-header-stats--compact">
                    <div class="nf-header-stat">
                        <span class="nf-header-stat__value"><?php echo esc_html(count($changelog_entries)); ?></span>
                        <span class="nf-header-stat__label"><?php esc_html_e('Releases', 'nebula-forge-addons-for-elementor'); ?></span>
                    </div>
                </div>
            </div>

            <?php if (class_exists(Ui_Helper::class)) : ?>
                <?php Ui_Helper::render_tabs(Admin_Manager::MENU_SLUG_CHANGELOG); ?>
            <?php endif; ?>

            <div class="nf-admin-content">
                <div class="nf-changelog-timeline">
                    <?php if (empty($changelog_entries)) : ?>
                        <div class="nf-card">
                            <p><?php esc_html_e('No changelog entries found.', 'nebula-forge-addons-for-elementor'); ?></p>
                        </div>
                    <?php else : ?>
                        <?php $is_first = true; ?>
                        <?php foreach ($changelog_entries as $entry) : ?>
                            <div class="nf-timeline-entry <?php echo $is_first ? 'nf-timeline-entry--latest' : ''; ?>">
                                <div class="nf-timeline-entry__marker">
                                    <span class="nf-timeline-entry__dot"></span>
                                </div>
                                <div class="nf-timeline-entry__card">
                                    <div class="nf-timeline-entry__header">
                                        <span class="nf-timeline-entry__version"><?php echo esc_html($entry['version']); ?></span>
                                        <?php if ($is_first) : ?>
                                            <span class="nf-badge nf-badge--latest"><?php esc_html_e('Latest', 'nebula-forge-addons-for-elementor'); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <ul class="nf-timeline-entry__changes">
                                        <?php foreach ($entry['changes'] as $change) : ?>
                                            <?php
                                            // Detect change type for color coding.
                                            $type_class = 'nf-change--default';
                                            $type_icon = 'dashicons-arrow-right-alt2';
                                            $lower = strtolower($change);
                                            if (strpos($lower, 'fix') !== false || strpos($lower, 'bug') !== false) {
                                                $type_class = 'nf-change--fix';
                                                $type_icon = 'dashicons-admin-tools';
                                            } elseif (strpos($lower, 'add') !== false || strpos($lower, 'new') !== false || strpos($lower, 'introduc') !== false) {
                                                $type_class = 'nf-change--add';
                                                $type_icon = 'dashicons-plus-alt2';
                                            } elseif (strpos($lower, 'improv') !== false || strpos($lower, 'updat') !== false || strpos($lower, 'enhanc') !== false || strpos($lower, 'refactor') !== false) {
                                                $type_class = 'nf-change--improve';
                                                $type_icon = 'dashicons-update';
                                            }
                                            ?>
                                            <li class="<?php echo esc_attr($type_class); ?>">
                                                <span class="dashicons <?php echo esc_attr($type_icon); ?>"></span>
                                                <?php echo esc_html($change); ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <?php $is_first = false; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Parse changelog from readme.txt.
     *
     * @return array<int, array{version: string, changes: array<int, string>}>
     */
    private function parse_changelog(): array
    {
        $lines = $this->get_readme_section('Changelog');

        if (empty($lines)) {
            return [];
        }

        $entries = [];
        $current_version = null;
        $current_changes = [];

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            // Version header: = 1.0.0 =
            if (preg_match('/^=\s*(.+?)\s*=$/', $line, $matches)) {
                if ($current_version !== null) {
                    $entries[] = [
                        'version' => $current_version,
                        'changes' => $current_changes,
                    ];
                }

                $current_version = $matches[1];
                $current_changes = [];
                continue;
            }

            // Change item: * Fixed something
            if (preg_match('/^\*\s+(.+)$/', $line, $matches)) {
                $current_changes[] = $matches[1];
            }
        }

        // Add last entry
        if ($current_version !== null) {
            $entries[] = [
                'version' => $current_version,
                'changes' => $current_changes,
            ];
        }

        return $entries;
    }

    /**
     * Extract section content from readme.txt.
     *
     * @param string $section_title Section title.
     * @return array<int, string> Lines from the section.
     */
    private function get_readme_section(string $section_title): array
    {
        if (!defined('NEBULA_FORGE_ADDON_PATH')) {
            return [];
        }

        $readme_path = NEBULA_FORGE_ADDON_PATH . 'readme.txt';

        if (!file_exists($readme_path)) {
            return [];
        }

        $contents = file_get_contents($readme_path);

        if (!is_string($contents) || $contents === '') {
            return [];
        }

        $lines = preg_split('/\R/', $contents);

        if (!is_array($lines)) {
            return [];
        }

        $target_heading = '== ' . trim($section_title) . ' ==';
        $in_section = false;
        $section_lines = [];

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if (!$in_section) {
                if (strcasecmp($trimmed, $target_heading) === 0) {
                    $in_section = true;
                }
                continue;
            }

            // Stop at next section
            if (preg_match('/^==\s*.+\s*==$/', $trimmed)) {
                break;
            }

            $section_lines[] = $line;
        }

        return $section_lines;
    }
}
