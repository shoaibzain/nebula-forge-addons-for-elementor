<?php
/**
 * Wrapper Link Extension
 *
 * Makes any Elementor widget, column, section, or container
 * fully clickable by wrapping it in a link.
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

final class Wrapper_Link
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

        // Add controls to all element types.
        add_action('elementor/element/common/_section_style/after_section_end', [__CLASS__, 'register_controls'], 10, 2);
        add_action('elementor/element/section/section_advanced/after_section_end', [__CLASS__, 'register_controls'], 10, 2);
        add_action('elementor/element/column/section_advanced/after_section_end', [__CLASS__, 'register_controls'], 10, 2);
        add_action('elementor/element/container/section_layout/after_section_end', [__CLASS__, 'register_controls'], 10, 2);

        // Apply wrapper link data attributes.
        add_action('elementor/frontend/widget/before_render', [__CLASS__, 'apply_link']);
        add_action('elementor/frontend/section/before_render', [__CLASS__, 'apply_link']);
        add_action('elementor/frontend/column/before_render', [__CLASS__, 'apply_link']);
        add_action('elementor/frontend/container/before_render', [__CLASS__, 'apply_link']);
    }

    /**
     * Register Wrapper Link controls.
     *
     * @param Element_Base $element The element.
     */
    public static function register_controls(Element_Base $element): void
    {
        $element->start_controls_section('nfa_wrapper_link_section', [
            'label' => esc_html__('Wrapper Link', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_ADVANCED,
        ]);

        $element->add_control('nfa_wrapper_link_enable', [
            'label'        => esc_html__('Enable Wrapper Link', 'nebula-forge-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'default'      => '',
            'return_value' => 'yes',
            'description'  => esc_html__('Make the entire element clickable as a link.', 'nebula-forge-addons-for-elementor'),
        ]);

        $element->add_control('nfa_wrapper_link_url', [
            'label'         => esc_html__('Link', 'nebula-forge-addons-for-elementor'),
            'type'          => Controls_Manager::URL,
            'placeholder'   => 'https://example.com',
            'show_external' => true,
            'default'       => [
                'url'         => '',
                'is_external' => false,
                'nofollow'    => false,
            ],
            'dynamic'       => ['active' => true],
            'condition'     => ['nfa_wrapper_link_enable' => 'yes'],
        ]);

        $element->end_controls_section();
    }

    /**
     * Apply wrapper link as data attributes (JS handles the click).
     *
     * @param Element_Base $element The element.
     */
    public static function apply_link(Element_Base $element): void
    {
        $settings = $element->get_settings_for_display();

        if (empty($settings['nfa_wrapper_link_enable']) || $settings['nfa_wrapper_link_enable'] !== 'yes') {
            return;
        }

        $url = $settings['nfa_wrapper_link_url']['url'] ?? '';
        if (empty($url)) {
            return;
        }

        $is_external = !empty($settings['nfa_wrapper_link_url']['is_external']);
        $nofollow    = !empty($settings['nfa_wrapper_link_url']['nofollow']);

        $element->add_render_attribute('_wrapper', [
            'data-nfa-wrapper-link'     => esc_url($url),
            'data-nfa-link-external'    => $is_external ? '1' : '0',
            'data-nfa-link-nofollow'    => $nofollow ? '1' : '0',
            'style'                     => 'cursor: pointer;',
            'role'                      => 'link',
            'tabindex'                  => '0',
            'aria-label'               => esc_attr(
                /* translators: %s: URL */
                sprintf(__('Link to %s', 'nebula-forge-addons-for-elementor'), $url)
            ),
        ]);
    }
}
