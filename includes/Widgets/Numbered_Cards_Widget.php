<?php
/**
 * Numbered Cards Widget
 *
 * Numbered feature/process cards with two layout modes:
 * Grid (icon variant) and Carousel (image variant).
 *
 * @package NebulaForgeAddon
 * @since   0.8.0
 */

namespace NebulaForgeAddon\Widgets;

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;

class Numbered_Cards_Widget extends Widget_Base
{
    public function get_name(): string
    {
        return 'nfa-numbered-cards';
    }

    public function get_title(): string
    {
        return esc_html__('Numbered Cards', 'nebula-forge-addons-for-elementor');
    }

    public function get_icon(): string
    {
        return 'eicon-number-field';
    }

    public function get_categories(): array
    {
        return ['nebula-forge'];
    }

    public function get_keywords(): array
    {
        return ['numbered', 'cards', 'process', 'steps', 'features', 'grid', 'carousel'];
    }

    public function get_style_depends(): array
    {
        return ['nebula-forge-elementor-addon-frontend'];
    }

    public function get_script_depends(): array
    {
        return ['nebula-forge-elementor-addon-frontend'];
    }

    /* ------------------------------------------------------------------
     * Controls
     * ----------------------------------------------------------------*/
    protected function register_controls(): void
    {
        $this->register_layout_controls();
        $this->register_content_controls();
        $this->register_carousel_controls();
        $this->register_style_card_controls();
        $this->register_style_steps_controls();
        $this->register_style_number_controls();
        $this->register_style_icon_controls();
        $this->register_style_content_controls();
        $this->register_style_navigation_controls();
    }

    /* ── Layout ──────────────────────────────────────────────────────── */
    private function register_layout_controls(): void
    {
        $this->start_controls_section('section_layout', [
            'label' => esc_html__('Layout', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('layout', [
            'label'   => esc_html__('Layout', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'grid',
            'options' => [
                'grid'     => esc_html__('Grid', 'nebula-forge-addons-for-elementor'),
                'steps'    => esc_html__('Steps', 'nebula-forge-addons-for-elementor'),
                'carousel' => esc_html__('Carousel', 'nebula-forge-addons-for-elementor'),
            ],
        ]);

        $this->add_control('media_type', [
            'label'   => esc_html__('Media Type', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'icon',
            'options' => [
                'icon'  => esc_html__('Icon', 'nebula-forge-addons-for-elementor'),
                'image' => esc_html__('Image', 'nebula-forge-addons-for-elementor'),
                'none'  => esc_html__('None', 'nebula-forge-addons-for-elementor'),
            ],
        ]);

        $this->end_controls_section();
    }

    /* ── Content ─────────────────────────────────────────────────────── */
    private function register_content_controls(): void
    {
        $this->start_controls_section('section_content', [
            'label' => esc_html__('Cards', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $repeater = new Repeater();

        $repeater->add_control('card_number', [
            'label'   => esc_html__('Number', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::TEXT,
            'default' => '01',
        ]);

        $repeater->add_control('card_icon', [
            'label'   => esc_html__('Icon', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::ICONS,
            'default' => [
                'value'   => 'fas fa-chart-line',
                'library' => 'fa-solid',
            ],
        ]);

        $repeater->add_control('card_image', [
            'label'   => esc_html__('Image', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => ''],
        ]);

        $repeater->add_control('card_title', [
            'label'       => esc_html__('Title', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Feature Title', 'nebula-forge-addons-for-elementor'),
            'label_block' => true,
        ]);

        $repeater->add_control('card_description', [
            'label'   => esc_html__('Description', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => esc_html__('A brief description of this feature or process step.', 'nebula-forge-addons-for-elementor'),
            'rows'    => 3,
        ]);

        $this->add_control('cards', [
            'label'       => esc_html__('Cards', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [
                    'card_number'      => '01',
                    'card_title'       => esc_html__('Maximized Income & High Occupancy', 'nebula-forge-addons-for-elementor'),
                    'card_description' => esc_html__('Experience up to 27% higher rental income with consistently high occupancy rates.', 'nebula-forge-addons-for-elementor'),
                    'card_icon'        => ['value' => 'fas fa-chart-line', 'library' => 'fa-solid'],
                ],
                [
                    'card_number'      => '02',
                    'card_title'       => esc_html__('Full Transparency & Monthly Payments', 'nebula-forge-addons-for-elementor'),
                    'card_description' => esc_html__('Enjoy complete transparency with our booking system, detailed monthly financial statements, and reliable payouts.', 'nebula-forge-addons-for-elementor'),
                    'card_icon'        => ['value' => 'fas fa-dollar-sign', 'library' => 'fa-solid'],
                ],
                [
                    'card_number'      => '03',
                    'card_title'       => esc_html__('Premium Property Care', 'nebula-forge-addons-for-elementor'),
                    'card_description' => esc_html__('Benefit from luxurious interior design, 100% anti-damage protection, and full adherence to regulations.', 'nebula-forge-addons-for-elementor'),
                    'card_icon'        => ['value' => 'fas fa-home', 'library' => 'fa-solid'],
                ],
                [
                    'card_number'      => '04',
                    'card_title'       => esc_html__('Exceptional Guest Experience', 'nebula-forge-addons-for-elementor'),
                    'card_description' => esc_html__('Our dedicated Guest Experience Manager and highly trained team ensure unparalleled guest satisfaction.', 'nebula-forge-addons-for-elementor'),
                    'card_icon'        => ['value' => 'fas fa-users', 'library' => 'fa-solid'],
                ],
                [
                    'card_number'      => '05',
                    'card_title'       => esc_html__('Strategic Market Leadership', 'nebula-forge-addons-for-elementor'),
                    'card_description' => esc_html__('Leverage our comprehensive pricing strategies and data-driven insights for optimal performance and growth.', 'nebula-forge-addons-for-elementor'),
                    'card_icon'        => ['value' => 'fas fa-arrow-up', 'library' => 'fa-solid'],
                ],
                [
                    'card_number'      => '06',
                    'card_title'       => esc_html__('Continuous Improvement', 'nebula-forge-addons-for-elementor'),
                    'card_description' => esc_html__('Our commitment to excellence means we constantly refine our strategies to stay ahead of the market.', 'nebula-forge-addons-for-elementor'),
                    'card_icon'        => ['value' => 'fas fa-award', 'library' => 'fa-solid'],
                ],
            ],
            'title_field' => '{{{ card_number }}} — {{{ card_title }}}',
        ]);

        $this->end_controls_section();
    }

    /* ── Carousel Settings ───────────────────────────────────────────── */
    private function register_carousel_controls(): void
    {
        $this->start_controls_section('section_carousel', [
            'label'     => esc_html__('Carousel Settings', 'nebula-forge-addons-for-elementor'),
            'tab'       => Controls_Manager::TAB_CONTENT,
            'condition' => ['layout' => 'carousel'],
        ]);

        $this->add_responsive_control('slides_per_view', [
            'label'   => esc_html__('Slides Per View', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SELECT,
            'default' => '3',
            'tablet_default' => '2',
            'mobile_default' => '1',
            'options' => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
            ],
        ]);

        $this->add_responsive_control('slide_gap', [
            'label'   => esc_html__('Gap', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 0, 'max' => 60]],
            'default' => ['size' => 20, 'unit' => 'px'],
        ]);

        $this->add_control('show_arrows', [
            'label'        => esc_html__('Arrows', 'nebula-forge-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Show', 'nebula-forge-addons-for-elementor'),
            'label_off'    => esc_html__('Hide', 'nebula-forge-addons-for-elementor'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('nav_position', [
            'label'   => esc_html__('Arrow Position', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'bottom-right',
            'options' => [
                'bottom-right'  => esc_html__('Bottom Right', 'nebula-forge-addons-for-elementor'),
                'bottom-left'   => esc_html__('Bottom Left', 'nebula-forge-addons-for-elementor'),
                'bottom-center' => esc_html__('Bottom Center', 'nebula-forge-addons-for-elementor'),
                'top-right'     => esc_html__('Top Right', 'nebula-forge-addons-for-elementor'),
                'top-left'      => esc_html__('Top Left', 'nebula-forge-addons-for-elementor'),
                'top-center'    => esc_html__('Top Center', 'nebula-forge-addons-for-elementor'),
                'sides'         => esc_html__('Sides (overlay)', 'nebula-forge-addons-for-elementor'),
            ],
            'condition' => ['show_arrows' => 'yes'],
        ]);

        $this->add_control('show_dots', [
            'label'        => esc_html__('Dots', 'nebula-forge-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Show', 'nebula-forge-addons-for-elementor'),
            'label_off'    => esc_html__('Hide', 'nebula-forge-addons-for-elementor'),
            'return_value' => 'yes',
            'default'      => '',
        ]);

        $this->add_control('mouse_drag', [
            'label'        => esc_html__('Mouse Drag', 'nebula-forge-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'nebula-forge-addons-for-elementor'),
            'label_off'    => esc_html__('No', 'nebula-forge-addons-for-elementor'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('infinite_loop', [
            'label'        => esc_html__('Infinite Loop', 'nebula-forge-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'nebula-forge-addons-for-elementor'),
            'label_off'    => esc_html__('No', 'nebula-forge-addons-for-elementor'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('transition_speed', [
            'label'   => esc_html__('Transition Speed (ms)', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::NUMBER,
            'default' => 450,
            'min'     => 100,
            'max'     => 2000,
            'step'    => 50,
        ]);

        $this->add_control('autoplay', [
            'label'        => esc_html__('Autoplay', 'nebula-forge-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'nebula-forge-addons-for-elementor'),
            'label_off'    => esc_html__('No', 'nebula-forge-addons-for-elementor'),
            'return_value' => 'yes',
            'default'      => '',
        ]);

        $this->add_control('autoplay_speed', [
            'label'     => esc_html__('Autoplay Speed (ms)', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::NUMBER,
            'default'   => 4000,
            'min'       => 1000,
            'max'       => 12000,
            'step'      => 500,
            'condition' => ['autoplay' => 'yes'],
        ]);

        $this->add_control('pause_on_hover', [
            'label'        => esc_html__('Pause on Hover', 'nebula-forge-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'nebula-forge-addons-for-elementor'),
            'label_off'    => esc_html__('No', 'nebula-forge-addons-for-elementor'),
            'return_value' => 'yes',
            'default'      => 'yes',
            'condition'    => ['autoplay' => 'yes'],
        ]);

        $this->end_controls_section();
    }

    /* ── Grid Settings (inside card style, shown for grid) ───────────── */
    private function register_style_card_controls(): void
    {
        $this->start_controls_section('section_style_card', [
            'label' => esc_html__('Card', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('grid_columns', [
            'label'   => esc_html__('Columns', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'size_units' => ['col'],
            'range'   => ['col' => ['min' => 1, 'max' => 4]],
            'default' => ['size' => 3, 'unit' => 'col'],
            'tablet_default' => ['size' => 2, 'unit' => 'col'],
            'mobile_default' => ['size' => 1, 'unit' => 'col'],
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards .nfa-ncards__grid' => 'grid-template-columns: repeat({{SIZE}}, minmax(0, 1fr));',
            ],
            'condition' => ['layout' => ['grid', 'steps']],
        ]);

        $this->add_responsive_control('grid_gap', [
            'label'   => esc_html__('Gap', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 0, 'max' => 80]],
            'default' => ['size' => 20, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards .nfa-ncards__grid' => 'gap: {{SIZE}}{{UNIT}};',
            ],
            'condition' => ['layout' => ['grid', 'steps']],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'card_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .nfa-ncards .nfa-ncards__card',
            'fields_options' => [
                'color' => ['default' => '#e8e4de'],
            ],
        ]);

        $this->add_responsive_control('card_padding', [
            'label'      => esc_html__('Padding', 'nebula-forge-addons-for-elementor'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'default'    => [
                'top' => '28', 'right' => '28', 'bottom' => '28', 'left' => '28',
                'unit' => 'px', 'isLinked' => true,
            ],
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards .nfa-ncards__card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('card_radius', [
            'label'   => esc_html__('Border Radius', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 0, 'max' => 60]],
            'default' => ['size' => 16, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards .nfa-ncards__card' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'card_border',
            'selector' => '{{WRAPPER}} .nfa-ncards .nfa-ncards__card',
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'     => 'card_shadow',
            'selector' => '{{WRAPPER}} .nfa-ncards .nfa-ncards__card',
        ]);

        $this->end_controls_section();
    }

    /* ── Number Style ────────────────────────────────────────────────── */
    private function register_style_number_controls(): void
    {
        $this->start_controls_section('section_style_number', [
            'label' => esc_html__('Number', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'number_typography',
            'selector' => '{{WRAPPER}} .nfa-ncards__number',
        ]);

        $this->add_control('number_color', [
            'label'     => esc_html__('Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#3b3a2f',
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__number' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('number_spacing', [
            'label'   => esc_html__('Bottom Spacing', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 0, 'max' => 60]],
            'default' => ['size' => 16, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__header' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
    }

    /* ── Icon Style ──────────────────────────────────────────────────── */
    private function register_style_icon_controls(): void
    {
        $this->start_controls_section('section_style_icon', [
            'label'     => esc_html__('Icon / Image', 'nebula-forge-addons-for-elementor'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['media_type!' => 'none'],
        ]);

        $this->add_control('icon_color', [
            'label'     => esc_html__('Icon Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#3b3a2f',
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
            ],
            'condition' => ['media_type' => 'icon'],
        ]);

        $this->add_responsive_control('icon_size', [
            'label'   => esc_html__('Icon Size', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 12, 'max' => 80]],
            'default' => ['size' => 28, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__icon' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .nfa-ncards__icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
            'condition' => ['media_type' => 'icon'],
        ]);

        $this->add_responsive_control('image_width', [
            'label'   => esc_html__('Image Width', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 40, 'max' => 280]],
            'default' => ['size' => 120, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__image' => 'width: {{SIZE}}{{UNIT}};',
            ],
            'condition' => ['media_type' => 'image'],
        ]);

        $this->add_responsive_control('image_height', [
            'label'   => esc_html__('Image Height', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 40, 'max' => 280]],
            'default' => ['size' => 120, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__image' => 'height: {{SIZE}}{{UNIT}};',
            ],
            'condition' => ['media_type' => 'image'],
        ]);

        $this->end_controls_section();
    }

    /* ── Content Style ───────────────────────────────────────────────── */
    private function register_style_content_controls(): void
    {
        $this->start_controls_section('section_style_content', [
            'label' => esc_html__('Title & Description', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'title_typography',
            'selector' => '{{WRAPPER}} .nfa-ncards__title',
        ]);

        $this->add_control('title_color', [
            'label'     => esc_html__('Title Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#3b3a2f',
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('title_spacing', [
            'label'   => esc_html__('Title Spacing', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 0, 'max' => 40]],
            'default' => ['size' => 12, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'desc_typography',
            'selector' => '{{WRAPPER}} .nfa-ncards__desc',
        ]);

        $this->add_control('desc_color', [
            'label'     => esc_html__('Description Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#3b3a2f',
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__desc' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    /* ── Steps Style ─────────────────────────────────────────────────── */
    private function register_style_steps_controls(): void
    {
        $this->start_controls_section('section_style_steps', [
            'label'     => esc_html__('Steps', 'nebula-forge-addons-for-elementor'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['layout' => 'steps'],
        ]);

        $this->add_control('step_prefix', [
            'label'   => esc_html__('Step Prefix', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::TEXT,
            'default' => 'STEP',
        ]);

        $this->add_control('heading_badge_style', [
            'label'     => esc_html__('Icon Badge', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('badge_bg', [
            'label'     => esc_html__('Badge Background', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1e3a5f',
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__badge' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('badge_icon_color', [
            'label'     => esc_html__('Badge Icon Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__badge' => 'color: {{VALUE}}; fill: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('badge_size', [
            'label'   => esc_html__('Badge Size', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 32, 'max' => 100]],
            'default' => ['size' => 56, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__badge' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('badge_icon_size', [
            'label'   => esc_html__('Badge Icon Size', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 12, 'max' => 48]],
            'default' => ['size' => 22, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__badge' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .nfa-ncards__badge svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('heading_step_label_style', [
            'label'     => esc_html__('Step Label', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('step_label_color', [
            'label'     => esc_html__('Step Label Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#b8976a',
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__step-label' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'step_label_typography',
            'selector' => '{{WRAPPER}} .nfa-ncards__step-label',
        ]);

        $this->add_control('heading_step_title_style', [
            'label'     => esc_html__('Step Title', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('step_title_color', [
            'label'     => esc_html__('Step Title Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1e3a5f',
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__step-title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'step_title_typography',
            'selector' => '{{WRAPPER}} .nfa-ncards__step-title',
        ]);

        $this->add_control('heading_divider_style', [
            'label'     => esc_html__('Divider', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('divider_color', [
            'label'     => esc_html__('Divider Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1e3a5f',
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__divider' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('divider_width', [
            'label'   => esc_html__('Divider Width', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 20, 'max' => 200]],
            'default' => ['size' => 50, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__divider' => 'width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('heading_step_desc_style', [
            'label'     => esc_html__('Description', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('step_desc_color', [
            'label'     => esc_html__('Description Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#666666',
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__step-desc' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'step_desc_typography',
            'selector' => '{{WRAPPER}} .nfa-ncards__step-desc',
        ]);

        $this->add_control('heading_accent_border_style', [
            'label'     => esc_html__('Accent Border', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('accent_border_color', [
            'label'     => esc_html__('Accent Border Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#b8976a',
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__card--step::before' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('accent_border_position', [
            'label'   => esc_html__('Accent Position', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'left',
            'options' => [
                'left'   => esc_html__('Left', 'nebula-forge-addons-for-elementor'),
                'top'    => esc_html__('Top', 'nebula-forge-addons-for-elementor'),
                'right'  => esc_html__('Right', 'nebula-forge-addons-for-elementor'),
                'bottom' => esc_html__('Bottom', 'nebula-forge-addons-for-elementor'),
                'none'   => esc_html__('None', 'nebula-forge-addons-for-elementor'),
            ],
            'prefix_class' => 'nfa-ncards-accent--',
        ]);

        $this->end_controls_section();
    }

    /* ── Navigation Style ────────────────────────────────────────────── */
    private function register_style_navigation_controls(): void
    {
        $this->start_controls_section('section_style_nav', [
            'label'     => esc_html__('Navigation', 'nebula-forge-addons-for-elementor'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['layout' => 'carousel'],
        ]);

        $this->add_control('arrow_size', [
            'label'   => esc_html__('Arrow Size', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 24, 'max' => 64]],
            'default' => ['size' => 40, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('arrow_color', [
            'label'     => esc_html__('Arrow Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__arrow' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('arrow_border_color', [
            'label'     => esc_html__('Arrow Border Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255,255,255,0.5)',
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__arrow' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('arrow_hover_bg', [
            'label'     => esc_html__('Arrow Hover Background', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255,255,255,0.15)',
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__arrow:hover' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('arrow_bg', [
            'label'     => esc_html__('Arrow Background', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '',
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__arrow' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('heading_dots_style', [
            'label'     => esc_html__('Dots', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => ['show_dots' => 'yes', 'layout' => 'carousel'],
        ]);

        $this->add_control('dot_color', [
            'label'     => esc_html__('Dot Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255,255,255,0.4)',
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__dot' => 'background-color: {{VALUE}};',
            ],
            'condition' => ['show_dots' => 'yes', 'layout' => 'carousel'],
        ]);

        $this->add_control('dot_active_color', [
            'label'     => esc_html__('Active Dot Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__dot.is-active' => 'background-color: {{VALUE}};',
            ],
            'condition' => ['show_dots' => 'yes', 'layout' => 'carousel'],
        ]);

        $this->add_control('dot_size', [
            'label'   => esc_html__('Dot Size', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 6, 'max' => 20]],
            'default' => ['size' => 10, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-ncards__dot' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
            'condition' => ['show_dots' => 'yes', 'layout' => 'carousel'],
        ]);

        $this->end_controls_section();
    }

    /* ------------------------------------------------------------------
     * Render
     * ----------------------------------------------------------------*/
    protected function render(): void
    {
        $settings   = $this->get_settings_for_display();
        $cards      = !empty($settings['cards']) && is_array($settings['cards']) ? $settings['cards'] : [];
        $layout     = $settings['layout'] ?? 'grid';
        $media_type = $settings['media_type'] ?? 'icon';

        if (empty($cards)) {
            return;
        }

        if ($layout === 'carousel') {
            $this->render_carousel($settings, $cards, $media_type);
        } elseif ($layout === 'steps') {
            $this->render_steps($settings, $cards, $media_type);
        } else {
            $this->render_grid($settings, $cards, $media_type);
        }
    }

    /* ── Grid Render ─────────────────────────────────────────────────── */
    private function render_grid(array $settings, array $cards, string $media_type): void
    {
        ?>
        <div class="nfa-ncards nfa-ncards--grid">
            <div class="nfa-ncards__grid">
                <?php foreach ($cards as $card) : ?>
                    <div class="nfa-ncards__card">
                        <div class="nfa-ncards__header">
                            <?php if (!empty($card['card_number'])) : ?>
                                <span class="nfa-ncards__number"><?php echo esc_html($card['card_number']); ?></span>
                            <?php endif; ?>

                            <?php if ($media_type === 'icon' && !empty($card['card_icon']['value'])) : ?>
                                <span class="nfa-ncards__icon" aria-hidden="true">
                                    <?php Icons_Manager::render_icon($card['card_icon'], ['aria-hidden' => 'true']); ?>
                                </span>
                            <?php elseif ($media_type === 'image' && !empty($card['card_image']['url'])) : ?>
                                <span class="nfa-ncards__image">
                                    <img src="<?php echo esc_url($card['card_image']['url']); ?>" alt="<?php echo esc_attr($card['card_title'] ?? ''); ?>" loading="lazy">
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="nfa-ncards__body">
                            <?php if (!empty($card['card_title'])) : ?>
                                <h4 class="nfa-ncards__title"><?php echo esc_html($card['card_title']); ?></h4>
                            <?php endif; ?>

                            <?php if (!empty($card['card_description'])) : ?>
                                <p class="nfa-ncards__desc"><?php echo esc_html($card['card_description']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
    /* ── Steps Render ─────────────────────────────────────────────── */
    private function render_steps(array $settings, array $cards, string $media_type): void
    {
        $step_prefix = $settings['step_prefix'] ?? 'STEP';
        ?>
        <div class="nfa-ncards nfa-ncards--steps">
            <div class="nfa-ncards__grid">
                <?php foreach ($cards as $card) : ?>
                    <div class="nfa-ncards__card nfa-ncards__card--step">
                        <?php if ($media_type === 'icon' && !empty($card['card_icon']['value'])) : ?>
                            <span class="nfa-ncards__badge" aria-hidden="true">
                                <?php Icons_Manager::render_icon($card['card_icon'], ['aria-hidden' => 'true']); ?>
                            </span>
                        <?php elseif ($media_type === 'image' && !empty($card['card_image']['url'])) : ?>
                            <span class="nfa-ncards__badge nfa-ncards__badge--img">
                                <img src="<?php echo esc_url($card['card_image']['url']); ?>" alt="<?php echo esc_attr($card['card_title'] ?? ''); ?>" loading="lazy">
                            </span>
                        <?php endif; ?>

                        <?php if (!empty($card['card_number'])) : ?>
                            <span class="nfa-ncards__step-label"><?php echo esc_html($step_prefix . ' ' . $card['card_number']); ?></span>
                        <?php endif; ?>

                        <?php if (!empty($card['card_title'])) : ?>
                            <h4 class="nfa-ncards__step-title"><?php echo esc_html($card['card_title']); ?></h4>
                        <?php endif; ?>

                        <span class="nfa-ncards__divider"></span>

                        <?php if (!empty($card['card_description'])) : ?>
                            <p class="nfa-ncards__step-desc"><?php echo esc_html($card['card_description']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
    /* ── Carousel Render ─────────────────────────────────────────────── */
    private function render_carousel(array $settings, array $cards, string $media_type): void
    {
        $per_view         = intval($settings['slides_per_view'] ?? 3);
        $per_view_tablet  = intval($settings['slides_per_view_tablet'] ?? min($per_view, 2));
        $per_view_mobile  = intval($settings['slides_per_view_mobile'] ?? 1);
        $gap              = intval($settings['slide_gap']['size'] ?? 20);
        $show_arrows      = !empty($settings['show_arrows']) && $settings['show_arrows'] === 'yes';
        $nav_position     = $settings['nav_position'] ?? 'bottom-right';
        $show_dots        = !empty($settings['show_dots']) && $settings['show_dots'] === 'yes';
        $mouse_drag       = !empty($settings['mouse_drag']) && $settings['mouse_drag'] === 'yes' ? 'yes' : 'no';
        $infinite_loop    = !empty($settings['infinite_loop']) && $settings['infinite_loop'] === 'yes' ? 'yes' : 'no';
        $transition_speed = intval($settings['transition_speed'] ?? 450);
        $autoplay         = $settings['autoplay'] ?? 'no';
        $autoplay_speed   = $settings['autoplay_speed'] ?? 4000;
        $pause_on_hover   = $settings['pause_on_hover'] ?? 'yes';

        $wrapper_class = 'nfa-ncards nfa-ncards--carousel';
        if ($show_arrows) {
            $wrapper_class .= ' nfa-ncards--nav-' . $nav_position;
        }
        ?>
        <div class="<?php echo esc_attr($wrapper_class); ?>"
             data-per-view="<?php echo esc_attr($per_view); ?>"
             data-per-view-tablet="<?php echo esc_attr($per_view_tablet); ?>"
             data-per-view-mobile="<?php echo esc_attr($per_view_mobile); ?>"
             data-gap="<?php echo esc_attr($gap); ?>"
             data-mouse-drag="<?php echo esc_attr($mouse_drag); ?>"
             data-infinite="<?php echo esc_attr($infinite_loop); ?>"
             data-speed="<?php echo esc_attr($transition_speed); ?>"
             data-autoplay="<?php echo esc_attr($autoplay); ?>"
             data-autoplay-speed="<?php echo esc_attr($autoplay_speed); ?>"
             data-pause-on-hover="<?php echo esc_attr($pause_on_hover); ?>">

            <?php if ($show_arrows && $nav_position === 'sides') : ?>
            <button class="nfa-ncards__arrow nfa-ncards__arrow--prev nfa-ncards__arrow--side" aria-label="<?php esc_attr_e('Previous', 'nebula-forge-addons-for-elementor'); ?>">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M10 12L6 8L10 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <?php endif; ?>

            <div class="nfa-ncards__viewport">
                <div class="nfa-ncards__track" style="gap:<?php echo esc_attr($gap); ?>px;">
                    <?php foreach ($cards as $card) : ?>
                        <div class="nfa-ncards__card">
                            <div class="nfa-ncards__header">
                                <?php if (!empty($card['card_number'])) : ?>
                                    <span class="nfa-ncards__number"><?php echo esc_html($card['card_number']); ?></span>
                                <?php endif; ?>

                                <?php if ($media_type === 'icon' && !empty($card['card_icon']['value'])) : ?>
                                    <span class="nfa-ncards__icon" aria-hidden="true">
                                        <?php Icons_Manager::render_icon($card['card_icon'], ['aria-hidden' => 'true']); ?>
                                    </span>
                                <?php elseif ($media_type === 'image' && !empty($card['card_image']['url'])) : ?>
                                    <span class="nfa-ncards__image">
                                        <img src="<?php echo esc_url($card['card_image']['url']); ?>" alt="<?php echo esc_attr($card['card_title'] ?? ''); ?>" loading="lazy">
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="nfa-ncards__body">
                                <?php if (!empty($card['card_title'])) : ?>
                                    <h4 class="nfa-ncards__title"><?php echo esc_html($card['card_title']); ?></h4>
                                <?php endif; ?>

                                <?php if (!empty($card['card_description'])) : ?>
                                    <p class="nfa-ncards__desc"><?php echo esc_html($card['card_description']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if ($show_arrows && $nav_position === 'sides') : ?>
            <button class="nfa-ncards__arrow nfa-ncards__arrow--next nfa-ncards__arrow--side" aria-label="<?php esc_attr_e('Next', 'nebula-forge-addons-for-elementor'); ?>">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M6 4L10 8L6 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <?php endif; ?>

            <?php if ($show_arrows && $nav_position !== 'sides') : ?>
            <div class="nfa-ncards__nav">
                <button class="nfa-ncards__arrow nfa-ncards__arrow--prev" aria-label="<?php esc_attr_e('Previous', 'nebula-forge-addons-for-elementor'); ?>">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M10 12L6 8L10 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <button class="nfa-ncards__arrow nfa-ncards__arrow--next" aria-label="<?php esc_attr_e('Next', 'nebula-forge-addons-for-elementor'); ?>">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M6 4L10 8L6 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
            <?php endif; ?>

            <?php if ($show_dots) : ?>
            <div class="nfa-ncards__dots"></div>
            <?php endif; ?>
        </div>
        <?php
    }
}
