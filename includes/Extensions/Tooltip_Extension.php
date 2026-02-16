<?php
/**
 * Tooltip Extension
 *
 * Adds a configurable tooltip to the Advanced tab of every
 * Elementor widget — supporting text, position, trigger,
 * and full style controls.
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
use Elementor\Group_Control_Typography;

final class Tooltip_Extension
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

        // Register controls.
        add_action('elementor/element/common/_section_style/after_section_end', [__CLASS__, 'register_controls'], 10, 2);

        // Render tooltip markup & data attributes.
        add_action('elementor/widget/before_render_content', [__CLASS__, 'before_render']);
        add_action('elementor/widget/after_render_content', [__CLASS__, 'after_render']);

        // Enqueue tooltip CSS + JS when needed.
        add_action('elementor/frontend/after_register_styles', [__CLASS__, 'register_assets']);
        add_action('elementor/frontend/after_register_scripts', [__CLASS__, 'register_assets']);
    }

    /**
     * Register tooltip controls in the Advanced tab.
     *
     * @param Element_Base $element The element.
     */
    public static function register_controls(Element_Base $element): void
    {
        $element->start_controls_section('nfa_tooltip_section', [
            'label' => esc_html__('Tooltip', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_ADVANCED,
        ]);

        $element->add_control('nfa_tooltip_enable', [
            'label'        => esc_html__('Enable Tooltip', 'nebula-forge-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'default'      => '',
            'return_value' => 'yes',
        ]);

        $element->add_control('nfa_tooltip_text', [
            'label'       => esc_html__('Tooltip Text', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::TEXTAREA,
            'default'     => esc_html__('This is a tooltip', 'nebula-forge-addons-for-elementor'),
            'rows'        => 3,
            'dynamic'     => ['active' => true],
            'condition'   => ['nfa_tooltip_enable' => 'yes'],
        ]);

        $element->add_control('nfa_tooltip_position', [
            'label'     => esc_html__('Position', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'top',
            'options'   => [
                'top'    => esc_html__('Top', 'nebula-forge-addons-for-elementor'),
                'bottom' => esc_html__('Bottom', 'nebula-forge-addons-for-elementor'),
                'left'   => esc_html__('Left', 'nebula-forge-addons-for-elementor'),
                'right'  => esc_html__('Right', 'nebula-forge-addons-for-elementor'),
            ],
            'condition' => ['nfa_tooltip_enable' => 'yes'],
        ]);

        $element->add_control('nfa_tooltip_trigger', [
            'label'     => esc_html__('Trigger', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'hover',
            'options'   => [
                'hover' => esc_html__('Hover', 'nebula-forge-addons-for-elementor'),
                'click' => esc_html__('Click', 'nebula-forge-addons-for-elementor'),
            ],
            'condition' => ['nfa_tooltip_enable' => 'yes'],
        ]);

        $element->add_control('nfa_tooltip_arrow', [
            'label'        => esc_html__('Show Arrow', 'nebula-forge-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'default'      => 'yes',
            'return_value' => 'yes',
            'condition'    => ['nfa_tooltip_enable' => 'yes'],
        ]);

        $element->add_control('nfa_tooltip_duration', [
            'label'     => esc_html__('Animation Duration (ms)', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 1000, 'step' => 50]],
            'default'   => ['size' => 200, 'unit' => 'px'],
            'condition' => ['nfa_tooltip_enable' => 'yes'],
        ]);

        $element->add_control('nfa_tooltip_heading_style', [
            'label'     => esc_html__('Style', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => ['nfa_tooltip_enable' => 'yes'],
        ]);

        $element->add_control('nfa_tooltip_bg', [
            'label'     => esc_html__('Background', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#131313',
            'condition' => ['nfa_tooltip_enable' => 'yes'],
            'selectors' => [
                '{{WRAPPER}} .nfa-tooltip' => 'background: {{VALUE}};',
                '{{WRAPPER}} .nfa-tooltip::after' => 'border-color: {{VALUE}};',
            ],
        ]);

        $element->add_control('nfa_tooltip_color', [
            'label'     => esc_html__('Text Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'condition' => ['nfa_tooltip_enable' => 'yes'],
            'selectors' => ['{{WRAPPER}} .nfa-tooltip' => 'color: {{VALUE}};'],
        ]);

        $element->add_control('nfa_tooltip_width', [
            'label'     => esc_html__('Max Width', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 100, 'max' => 500]],
            'default'   => ['size' => 250, 'unit' => 'px'],
            'condition' => ['nfa_tooltip_enable' => 'yes'],
            'selectors' => ['{{WRAPPER}} .nfa-tooltip' => 'max-width: {{SIZE}}{{UNIT}};'],
        ]);

        $element->add_control('nfa_tooltip_radius', [
            'label'     => esc_html__('Border Radius', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 20]],
            'default'   => ['size' => 6, 'unit' => 'px'],
            'condition' => ['nfa_tooltip_enable' => 'yes'],
            'selectors' => ['{{WRAPPER}} .nfa-tooltip' => 'border-radius: {{SIZE}}{{UNIT}};'],
        ]);

        $element->add_control('nfa_tooltip_padding', [
            'label'      => esc_html__('Padding', 'nebula-forge-addons-for-elementor'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => ['top' => '8', 'right' => '14', 'bottom' => '8', 'left' => '14', 'unit' => 'px', 'isLinked' => false],
            'condition'  => ['nfa_tooltip_enable' => 'yes'],
            'selectors'  => ['{{WRAPPER}} .nfa-tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $element->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'nfa_tooltip_typography',
            'selector'  => '{{WRAPPER}} .nfa-tooltip',
            'condition' => ['nfa_tooltip_enable' => 'yes'],
        ]);

        $element->end_controls_section();
    }

    /**
     * Add tooltip wrapper data attributes before widget render.
     *
     * @param Element_Base $element The widget.
     */
    public static function before_render(Element_Base $element): void
    {
        $settings = $element->get_settings_for_display();

        if (empty($settings['nfa_tooltip_enable']) || $settings['nfa_tooltip_enable'] !== 'yes') {
            return;
        }

        $text = $settings['nfa_tooltip_text'] ?? '';
        if (empty($text)) {
            return;
        }

        $element->add_render_attribute('_wrapper', [
            'data-nfa-tooltip'          => esc_attr($text),
            'data-nfa-tooltip-pos'      => esc_attr($settings['nfa_tooltip_position'] ?? 'top'),
            'data-nfa-tooltip-trigger'  => esc_attr($settings['nfa_tooltip_trigger'] ?? 'hover'),
            'data-nfa-tooltip-arrow'    => esc_attr($settings['nfa_tooltip_arrow'] ?? 'yes'),
            'data-nfa-tooltip-duration' => esc_attr($settings['nfa_tooltip_duration']['size'] ?? 200),
        ]);
    }

    /**
     * Placeholder — tooltip content is rendered via JavaScript.
     */
    public static function after_render(Element_Base $element): void
    {
        // Tooltip DOM is injected by JS.
    }

    /**
     * Register tooltip assets — they'll be loaded on-demand by the widget dependency system.
     */
    public static function register_assets(): void
    {
        // Tooltip styles and scripts are bundled into the main frontend asset.
    }
}
