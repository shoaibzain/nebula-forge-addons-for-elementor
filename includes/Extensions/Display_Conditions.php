<?php
/**
 * Display Conditions Extension
 *
 * Adds conditional visibility controls to the Advanced tab of
 * every Elementor widget — show / hide based on user role,
 * login status, device type, date range, and page type.
 *
 * @package NebulaForgeAddon
 * @since   0.6.0
 */

namespace NebulaForgeAddon\Extensions;

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Element_Base;

final class Display_Conditions
{
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

        add_action('elementor/element/common/_section_style/after_section_end', [__CLASS__, 'register_controls'], 10, 2);
        add_action('elementor/element/section/section_advanced/after_section_end', [__CLASS__, 'register_controls'], 10, 2);
        add_action('elementor/element/column/section_advanced/after_section_end', [__CLASS__, 'register_controls'], 10, 2);

        // Container support (Elementor 3.6+).
        add_action('elementor/element/container/section_layout/after_section_end', [__CLASS__, 'register_controls'], 10, 2);

        add_action('elementor/frontend/widget/before_render', [__CLASS__, 'apply_conditions']);
        add_action('elementor/frontend/section/before_render', [__CLASS__, 'apply_conditions']);
        add_action('elementor/frontend/column/before_render', [__CLASS__, 'apply_conditions']);
        add_action('elementor/frontend/container/before_render', [__CLASS__, 'apply_conditions']);
    }

    /**
     * Register the Display Conditions controls in the Advanced tab.
     *
     * @param Element_Base $element The element instance.
     */
    public static function register_controls(Element_Base $element): void
    {
        $element->start_controls_section('nfa_display_conditions_section', [
            'label' => esc_html__('Display Conditions', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_ADVANCED,
        ]);

        $element->add_control('nfa_dc_enable', [
            'label'        => esc_html__('Enable Conditions', 'nebula-forge-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'default'      => '',
            'return_value' => 'yes',
            'description'  => esc_html__('When enabled, this element is only visible if ALL selected conditions match.', 'nebula-forge-addons-for-elementor'),
        ]);

        $element->add_control('nfa_dc_action', [
            'label'     => esc_html__('When Conditions Met', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'show',
            'options'   => [
                'show' => esc_html__('Show Element', 'nebula-forge-addons-for-elementor'),
                'hide' => esc_html__('Hide Element', 'nebula-forge-addons-for-elementor'),
            ],
            'condition' => ['nfa_dc_enable' => 'yes'],
        ]);

        /* ── User Login Status ─────────────────────── */
        $element->add_control('nfa_dc_login_heading', [
            'label'     => esc_html__('User Status', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => ['nfa_dc_enable' => 'yes'],
        ]);

        $element->add_control('nfa_dc_login', [
            'label'     => esc_html__('Login Status', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SELECT,
            'default'   => '',
            'options'   => [
                ''           => esc_html__('Any', 'nebula-forge-addons-for-elementor'),
                'logged_in'  => esc_html__('Logged In', 'nebula-forge-addons-for-elementor'),
                'logged_out' => esc_html__('Logged Out', 'nebula-forge-addons-for-elementor'),
            ],
            'condition' => ['nfa_dc_enable' => 'yes'],
        ]);

        /* ── User Role ─────────────────────────────── */
        $element->add_control('nfa_dc_roles', [
            'label'       => esc_html__('User Roles', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::SELECT2,
            'multiple'    => true,
            'default'     => [],
            'options'     => self::get_user_roles(),
            'description' => esc_html__('Leave empty to skip role check.', 'nebula-forge-addons-for-elementor'),
            'condition'   => [
                'nfa_dc_enable' => 'yes',
                'nfa_dc_login'  => 'logged_in',
            ],
        ]);

        /* ── Date & Time ───────────────────────────── */
        $element->add_control('nfa_dc_date_heading', [
            'label'     => esc_html__('Date & Time', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => ['nfa_dc_enable' => 'yes'],
        ]);

        $element->add_control('nfa_dc_date_from', [
            'label'       => esc_html__('Show From', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::DATE_TIME,
            'default'     => '',
            'description' => esc_html__('Leave empty to skip.', 'nebula-forge-addons-for-elementor'),
            'condition'   => ['nfa_dc_enable' => 'yes'],
        ]);

        $element->add_control('nfa_dc_date_to', [
            'label'       => esc_html__('Show Until', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::DATE_TIME,
            'default'     => '',
            'description' => esc_html__('Leave empty to skip.', 'nebula-forge-addons-for-elementor'),
            'condition'   => ['nfa_dc_enable' => 'yes'],
        ]);

        $element->add_control('nfa_dc_days', [
            'label'       => esc_html__('Days of Week', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::SELECT2,
            'multiple'    => true,
            'default'     => [],
            'options'     => [
                'monday'    => esc_html__('Monday', 'nebula-forge-addons-for-elementor'),
                'tuesday'   => esc_html__('Tuesday', 'nebula-forge-addons-for-elementor'),
                'wednesday' => esc_html__('Wednesday', 'nebula-forge-addons-for-elementor'),
                'thursday'  => esc_html__('Thursday', 'nebula-forge-addons-for-elementor'),
                'friday'    => esc_html__('Friday', 'nebula-forge-addons-for-elementor'),
                'saturday'  => esc_html__('Saturday', 'nebula-forge-addons-for-elementor'),
                'sunday'    => esc_html__('Sunday', 'nebula-forge-addons-for-elementor'),
            ],
            'description' => esc_html__('Leave empty to show every day.', 'nebula-forge-addons-for-elementor'),
            'condition'   => ['nfa_dc_enable' => 'yes'],
        ]);

        /* ── Page Type ─────────────────────────────── */
        $element->add_control('nfa_dc_page_heading', [
            'label'     => esc_html__('Page Type', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => ['nfa_dc_enable' => 'yes'],
        ]);

        $element->add_control('nfa_dc_page_type', [
            'label'       => esc_html__('Show On', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::SELECT2,
            'multiple'    => true,
            'default'     => [],
            'options'     => [
                'front_page' => esc_html__('Front Page', 'nebula-forge-addons-for-elementor'),
                'single'     => esc_html__('Single Post', 'nebula-forge-addons-for-elementor'),
                'page'       => esc_html__('Page', 'nebula-forge-addons-for-elementor'),
                'archive'    => esc_html__('Archive', 'nebula-forge-addons-for-elementor'),
                'search'     => esc_html__('Search Results', 'nebula-forge-addons-for-elementor'),
                '404'        => esc_html__('404 Page', 'nebula-forge-addons-for-elementor'),
            ],
            'description' => esc_html__('Leave empty to show on all pages.', 'nebula-forge-addons-for-elementor'),
            'condition'   => ['nfa_dc_enable' => 'yes'],
        ]);

        /* ── Browser / OS ──────────────────────────── */
        $element->add_control('nfa_dc_browser_heading', [
            'label'     => esc_html__('Browser & OS', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => ['nfa_dc_enable' => 'yes'],
        ]);

        $element->add_control('nfa_dc_browsers', [
            'label'       => esc_html__('Browsers', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::SELECT2,
            'multiple'    => true,
            'default'     => [],
            'options'     => [
                'chrome'  => esc_html__('Chrome', 'nebula-forge-addons-for-elementor'),
                'firefox' => esc_html__('Firefox', 'nebula-forge-addons-for-elementor'),
                'safari'  => esc_html__('Safari', 'nebula-forge-addons-for-elementor'),
                'edge'    => esc_html__('Edge', 'nebula-forge-addons-for-elementor'),
                'opera'   => esc_html__('Opera', 'nebula-forge-addons-for-elementor'),
            ],
            'description' => esc_html__('Leave empty to show on all browsers.', 'nebula-forge-addons-for-elementor'),
            'condition'   => ['nfa_dc_enable' => 'yes'],
        ]);

        $element->add_control('nfa_dc_os', [
            'label'       => esc_html__('Operating System', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::SELECT2,
            'multiple'    => true,
            'default'     => [],
            'options'     => [
                'windows' => esc_html__('Windows', 'nebula-forge-addons-for-elementor'),
                'mac'     => esc_html__('macOS', 'nebula-forge-addons-for-elementor'),
                'linux'   => esc_html__('Linux', 'nebula-forge-addons-for-elementor'),
                'android' => esc_html__('Android', 'nebula-forge-addons-for-elementor'),
                'ios'     => esc_html__('iOS', 'nebula-forge-addons-for-elementor'),
            ],
            'description' => esc_html__('Leave empty to show on all OS.', 'nebula-forge-addons-for-elementor'),
            'condition'   => ['nfa_dc_enable' => 'yes'],
        ]);

        $element->end_controls_section();
    }

    /**
     * Evaluate conditions and hide the element when they fail.
     *
     * @param Element_Base $element The Elementor element.
     */
    public static function apply_conditions(Element_Base $element): void
    {
        $settings = $element->get_settings_for_display();

        if (empty($settings['nfa_dc_enable']) || $settings['nfa_dc_enable'] !== 'yes') {
            return;
        }

        $action    = $settings['nfa_dc_action'] ?? 'show';
        $condition = self::evaluate($settings);

        // If action is "show" and condition fails, or action is "hide" and condition passes → hide.
        $should_hide = ($action === 'show' && !$condition) || ($action === 'hide' && $condition);

        if ($should_hide) {
            $element->add_render_attribute('_wrapper', 'style', 'display:none !important;');
            $element->add_render_attribute('_wrapper', 'aria-hidden', 'true');
        }
    }

    /**
     * Evaluate all enabled conditions (AND logic).
     *
     * @param array $s Element settings.
     * @return bool True when ALL conditions pass.
     */
    private static function evaluate(array $s): bool
    {
        // ── Login status ──────────────────────────
        if (!empty($s['nfa_dc_login'])) {
            $logged_in = is_user_logged_in();
            if ($s['nfa_dc_login'] === 'logged_in' && !$logged_in) {
                return false;
            }
            if ($s['nfa_dc_login'] === 'logged_out' && $logged_in) {
                return false;
            }
        }

        // ── User role ─────────────────────────────
        if (!empty($s['nfa_dc_roles']) && is_array($s['nfa_dc_roles'])) {
            $user = wp_get_current_user();
            if (!$user || !$user->exists()) {
                return false;
            }
            $intersect = array_intersect($user->roles, $s['nfa_dc_roles']);
            if (empty($intersect)) {
                return false;
            }
        }

        // ── Date range ────────────────────────────
        $now = current_time('timestamp'); // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested

        if (!empty($s['nfa_dc_date_from'])) {
            $from = strtotime($s['nfa_dc_date_from']);
            if ($from && $now < $from) {
                return false;
            }
        }

        if (!empty($s['nfa_dc_date_to'])) {
            $to = strtotime($s['nfa_dc_date_to']);
            if ($to && $now > $to) {
                return false;
            }
        }

        // ── Day of week ───────────────────────────
        if (!empty($s['nfa_dc_days']) && is_array($s['nfa_dc_days'])) {
            $today = strtolower(current_time('l'));
            if (!in_array($today, $s['nfa_dc_days'], true)) {
                return false;
            }
        }

        // ── Page type ─────────────────────────────
        if (!empty($s['nfa_dc_page_type']) && is_array($s['nfa_dc_page_type'])) {
            $match = false;
            foreach ($s['nfa_dc_page_type'] as $type) {
                switch ($type) {
                    case 'front_page':
                        if (is_front_page()) {
                            $match = true;
                        }
                        break;
                    case 'single':
                        if (is_singular('post')) {
                            $match = true;
                        }
                        break;
                    case 'page':
                        if (is_page()) {
                            $match = true;
                        }
                        break;
                    case 'archive':
                        if (is_archive()) {
                            $match = true;
                        }
                        break;
                    case 'search':
                        if (is_search()) {
                            $match = true;
                        }
                        break;
                    case '404':
                        if (is_404()) {
                            $match = true;
                        }
                        break;
                }
            }
            if (!$match) {
                return false;
            }
        }

        // ── Browser ───────────────────────────────
        if (!empty($s['nfa_dc_browsers']) && is_array($s['nfa_dc_browsers'])) {
            $ua      = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'])) : '';
            $ua_low  = strtolower($ua);
            $browser = self::detect_browser($ua_low);
            if (!in_array($browser, $s['nfa_dc_browsers'], true)) {
                return false;
            }
        }

        // ── Operating System ──────────────────────
        if (!empty($s['nfa_dc_os']) && is_array($s['nfa_dc_os'])) {
            $ua     = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'])) : '';
            $ua_low = strtolower($ua);
            $os     = self::detect_os($ua_low);
            if (!in_array($os, $s['nfa_dc_os'], true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Detect browser from user-agent string.
     */
    private static function detect_browser(string $ua): string
    {
        if (strpos($ua, 'edg') !== false) {
            return 'edge';
        }
        if (strpos($ua, 'opr') !== false || strpos($ua, 'opera') !== false) {
            return 'opera';
        }
        if (strpos($ua, 'chrome') !== false || strpos($ua, 'crios') !== false) {
            return 'chrome';
        }
        if (strpos($ua, 'firefox') !== false || strpos($ua, 'fxios') !== false) {
            return 'firefox';
        }
        if (strpos($ua, 'safari') !== false) {
            return 'safari';
        }
        return '';
    }

    /**
     * Detect OS from user-agent string.
     */
    private static function detect_os(string $ua): string
    {
        if (strpos($ua, 'iphone') !== false || strpos($ua, 'ipad') !== false) {
            return 'ios';
        }
        if (strpos($ua, 'android') !== false) {
            return 'android';
        }
        if (strpos($ua, 'mac') !== false) {
            return 'mac';
        }
        if (strpos($ua, 'windows') !== false) {
            return 'windows';
        }
        if (strpos($ua, 'linux') !== false) {
            return 'linux';
        }
        return '';
    }

    /**
     * Get all WordPress user roles as key => label.
     *
     * @return array<string, string>
     */
    private static function get_user_roles(): array
    {
        global $wp_roles;

        if (!$wp_roles) {
            return [];
        }

        $roles = [];
        foreach ($wp_roles->roles as $slug => $data) {
            $roles[$slug] = $data['name'];
        }

        return $roles;
    }
}
