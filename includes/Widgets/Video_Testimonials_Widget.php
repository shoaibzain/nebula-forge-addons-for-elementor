<?php
/**
 * Video Testimonials Widget
 *
 * Video-based testimonial cards with Grid and Carousel layout modes.
 * Each card shows a video thumbnail with play overlay, person name,
 * role, quote, and star rating. Full hover effects and style controls.
 *
 * @package NebulaForgeAddon
 * @since   0.9.0
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
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;

class Video_Testimonials_Widget extends Widget_Base
{
    public function get_name(): string
    {
        return 'nfa-video-testimonials';
    }

    public function get_title(): string
    {
        return esc_html__('Video Testimonials', 'nebula-forge-addons-for-elementor');
    }

    public function get_icon(): string
    {
        return 'eicon-video-playlist';
    }

    public function get_categories(): array
    {
        return ['nebula-forge'];
    }

    public function get_keywords(): array
    {
        return ['video', 'testimonials', 'reviews', 'carousel', 'grid', 'reel', 'play', 'social proof'];
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
        $this->register_style_thumbnail_controls();
        $this->register_style_play_button_controls();
        $this->register_style_content_controls();
        $this->register_style_hover_controls();
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
                'carousel' => esc_html__('Carousel', 'nebula-forge-addons-for-elementor'),
                'reel'     => esc_html__('Reel', 'nebula-forge-addons-for-elementor'),
            ],
        ]);

        $this->add_control('video_action', [
            'label'   => esc_html__('Click Action', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'lightbox',
            'options' => [
                'lightbox' => esc_html__('Lightbox', 'nebula-forge-addons-for-elementor'),
                'inline'   => esc_html__('Play Inline', 'nebula-forge-addons-for-elementor'),
                'new_tab'  => esc_html__('Open in New Tab', 'nebula-forge-addons-for-elementor'),
            ],
        ]);

        /* ── Reel Settings ────────────────────────────────────────── */
        $this->add_responsive_control('reel_columns', [
            'label'          => esc_html__('Columns', 'nebula-forge-addons-for-elementor'),
            'type'           => Controls_Manager::SLIDER,
            'size_units'     => ['col'],
            'range'          => ['col' => ['min' => 1, 'max' => 6]],
            'default'        => ['size' => 4, 'unit' => 'col'],
            'tablet_default' => ['size' => 2, 'unit' => 'col'],
            'mobile_default' => ['size' => 1, 'unit' => 'col'],
            'selectors'      => [
                '{{WRAPPER}} .nfa-vtestimonials__reel' => 'grid-template-columns: repeat({{SIZE}}, minmax(0, 1fr));',
            ],
            'condition' => ['layout' => 'reel'],
        ]);

        $this->add_responsive_control('reel_gap', [
            'label'   => esc_html__('Gap', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 0, 'max' => 60]],
            'default' => ['size' => 16, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__reel' => 'gap: {{SIZE}}{{UNIT}};',
            ],
            'condition' => ['layout' => 'reel'],
        ]);

        $this->add_responsive_control('reel_aspect_ratio', [
            'label'   => esc_html__('Aspect Ratio', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SELECT,
            'default' => '9/16',
            'options' => [
                '9/16'  => esc_html__('9:16 (Reel)', 'nebula-forge-addons-for-elementor'),
                '3/4'   => esc_html__('3:4 (Portrait)', 'nebula-forge-addons-for-elementor'),
                '4/5'   => esc_html__('4:5 (Social)', 'nebula-forge-addons-for-elementor'),
                '1/1'   => esc_html__('1:1 (Square)', 'nebula-forge-addons-for-elementor'),
            ],
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__reel-card' => 'aspect-ratio: {{VALUE}};',
            ],
            'condition' => ['layout' => 'reel'],
        ]);

        $this->add_control('reel_radius', [
            'label'   => esc_html__('Border Radius', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 0, 'max' => 40]],
            'default' => ['size' => 16, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__reel-card' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
            'condition' => ['layout' => 'reel'],
        ]);

        $this->add_control('reel_overlay_color', [
            'label'     => esc_html__('Overlay Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(0,0,0,0.15)',
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__reel-card::after' => 'background: {{VALUE}};',
            ],
            'condition' => ['layout' => 'reel'],
        ]);

        $this->add_control('reel_hover_overlay_color', [
            'label'     => esc_html__('Hover Overlay Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(0,0,0,0.30)',
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__reel-card:hover::after' => 'background: {{VALUE}};',
            ],
            'condition' => ['layout' => 'reel'],
        ]);

        $this->add_control('reel_play_size', [
            'label'   => esc_html__('Play Button Size', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 30, 'max' => 100]],
            'default' => ['size' => 56, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__reel-play' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
            'condition' => ['layout' => 'reel'],
        ]);

        $this->add_control('reel_play_bg', [
            'label'     => esc_html__('Play Button BG', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(0,0,0,0.45)',
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__reel-play' => 'background-color: {{VALUE}};',
            ],
            'condition' => ['layout' => 'reel'],
        ]);

        $this->add_control('reel_play_icon_color', [
            'label'     => esc_html__('Play Icon Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__reel-play svg' => 'fill: {{VALUE}};',
            ],
            'condition' => ['layout' => 'reel'],
        ]);

        $this->end_controls_section();
    }

    /* ── Content ─────────────────────────────────────────────────────── */
    private function register_content_controls(): void
    {
        $this->start_controls_section('section_content', [
            'label' => esc_html__('Testimonials', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $repeater = new Repeater();

        $repeater->add_control('video_url', [
            'label'       => esc_html__('Video URL', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::URL,
            'placeholder' => 'https://www.youtube.com/watch?v=...',
            'label_block' => true,
            'default'     => ['url' => ''],
        ]);

        $repeater->add_control('thumbnail', [
            'label'   => esc_html__('Thumbnail', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::MEDIA,
            'default' => [
                'url' => Utils::get_placeholder_image_src(),
            ],
        ]);

        $repeater->add_control('name', [
            'label'       => esc_html__('Name', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('John Doe', 'nebula-forge-addons-for-elementor'),
            'label_block' => true,
        ]);

        $repeater->add_control('role', [
            'label'       => esc_html__('Role / Company', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('CEO, Acme Corp', 'nebula-forge-addons-for-elementor'),
            'label_block' => true,
        ]);

        $repeater->add_control('quote', [
            'label'   => esc_html__('Quote', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => esc_html__('This product completely transformed our workflow. Highly recommended!', 'nebula-forge-addons-for-elementor'),
            'rows'    => 3,
        ]);

        $repeater->add_control('avatar', [
            'label' => esc_html__('Avatar', 'nebula-forge-addons-for-elementor'),
            'type'  => Controls_Manager::MEDIA,
        ]);

        $repeater->add_control('rating', [
            'label'   => esc_html__('Rating (0-5)', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::NUMBER,
            'min'     => 0,
            'max'     => 5,
            'step'    => 1,
            'default' => 5,
        ]);

        $repeater->add_control('video_duration', [
            'label'       => esc_html__('Duration Label', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => '2:30',
            'description' => esc_html__('Optional. Shown on thumbnail corner.', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('testimonials', [
            'label'   => esc_html__('Testimonials', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::REPEATER,
            'fields'  => $repeater->get_controls(),
            'default' => [
                [
                    'name'           => esc_html__('Sarah Johnson', 'nebula-forge-addons-for-elementor'),
                    'role'           => esc_html__('Marketing Director, BrightWave', 'nebula-forge-addons-for-elementor'),
                    'quote'          => esc_html__('The results exceeded our expectations. Our conversion rate doubled within weeks.', 'nebula-forge-addons-for-elementor'),
                    'rating'         => 5,
                    'video_duration' => '1:45',
                ],
                [
                    'name'           => esc_html__('Michael Chen', 'nebula-forge-addons-for-elementor'),
                    'role'           => esc_html__('Founder, TechStart', 'nebula-forge-addons-for-elementor'),
                    'quote'          => esc_html__('A game-changer for our team. Simple to use and incredibly powerful.', 'nebula-forge-addons-for-elementor'),
                    'rating'         => 5,
                    'video_duration' => '3:12',
                ],
                [
                    'name'           => esc_html__('Emma Williams', 'nebula-forge-addons-for-elementor'),
                    'role'           => esc_html__('Product Lead, InnovateCo', 'nebula-forge-addons-for-elementor'),
                    'quote'          => esc_html__('Professional quality and outstanding support. Worth every penny.', 'nebula-forge-addons-for-elementor'),
                    'rating'         => 4,
                    'video_duration' => '2:08',
                ],
            ],
            'title_field' => '{{{ name }}}',
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
            'label'          => esc_html__('Slides Per View', 'nebula-forge-addons-for-elementor'),
            'type'           => Controls_Manager::SELECT,
            'default'        => '3',
            'tablet_default' => '2',
            'mobile_default' => '1',
            'options'        => [
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
            'default' => ['size' => 24, 'unit' => 'px'],
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
            'label'     => esc_html__('Arrow Position', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'bottom-center',
            'options'   => [
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
            'default'      => 'yes',
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

    /* ── Style: Card ─────────────────────────────────────────────────── */
    private function register_style_card_controls(): void
    {
        $this->start_controls_section('section_style_card', [
            'label' => esc_html__('Card', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('grid_columns', [
            'label'      => esc_html__('Columns', 'nebula-forge-addons-for-elementor'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['col'],
            'range'      => ['col' => ['min' => 1, 'max' => 4]],
            'default'    => ['size' => 3, 'unit' => 'col'],
            'tablet_default' => ['size' => 2, 'unit' => 'col'],
            'mobile_default' => ['size' => 1, 'unit' => 'col'],
            'selectors'  => [
                '{{WRAPPER}} .nfa-vtestimonials__grid' => 'grid-template-columns: repeat({{SIZE}}, minmax(0, 1fr));',
            ],
            'condition' => ['layout' => 'grid'],
        ]);

        $this->add_responsive_control('grid_gap', [
            'label'   => esc_html__('Gap', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 0, 'max' => 80]],
            'default' => ['size' => 24, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__grid' => 'gap: {{SIZE}}{{UNIT}};',
            ],
            'condition' => ['layout' => 'grid'],
        ]);

        $this->add_control('heading_card_style_notice', [
            'type'            => Controls_Manager::RAW_HTML,
            'raw'             => esc_html__('Card style options are not applicable in Reel layout.', 'nebula-forge-addons-for-elementor'),
            'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            'condition'       => ['layout' => 'reel'],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'card_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .nfa-vtestimonials__card',
            'fields_options' => [
                'color' => ['default' => '#ffffff'],
            ],
        ]);

        $this->add_responsive_control('card_padding', [
            'label'      => esc_html__('Padding', 'nebula-forge-addons-for-elementor'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'default'    => [
                'top' => '0', 'right' => '0', 'bottom' => '20', 'left' => '0',
                'unit' => 'px', 'isLinked' => false,
            ],
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('card_radius', [
            'label'   => esc_html__('Border Radius', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 0, 'max' => 60]],
            'default' => ['size' => 16, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__card' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'card_border',
            'selector' => '{{WRAPPER}} .nfa-vtestimonials__card',
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'     => 'card_shadow',
            'selector' => '{{WRAPPER}} .nfa-vtestimonials__card',
        ]);

        $this->end_controls_section();
    }

    /* ── Style: Thumbnail ────────────────────────────────────────────── */
    private function register_style_thumbnail_controls(): void
    {
        $this->start_controls_section('section_style_thumbnail', [
            'label'     => esc_html__('Thumbnail', 'nebula-forge-addons-for-elementor'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['layout!' => 'reel'],
        ]);

        $this->add_responsive_control('thumb_height', [
            'label'   => esc_html__('Height', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 100, 'max' => 500]],
            'default' => ['size' => 220, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__thumb' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('thumb_radius', [
            'label'   => esc_html__('Border Radius', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 0, 'max' => 40]],
            'default' => ['size' => 12, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__thumb' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('thumb_overlay_color', [
            'label'     => esc_html__('Overlay Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(0,0,0,0.25)',
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__thumb::after' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('thumb_hover_overlay_color', [
            'label'     => esc_html__('Hover Overlay Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(0,0,0,0.45)',
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__card:hover .nfa-vtestimonials__thumb::after' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('duration_bg', [
            'label'     => esc_html__('Duration Badge BG', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(0,0,0,0.7)',
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__duration' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('duration_color', [
            'label'     => esc_html__('Duration Badge Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__duration' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    /* ── Style: Play Button ──────────────────────────────────────────── */
    private function register_style_play_button_controls(): void
    {
        $this->start_controls_section('section_style_play', [
            'label'     => esc_html__('Play Button', 'nebula-forge-addons-for-elementor'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['layout!' => 'reel'],
        ]);

        $this->add_control('play_size', [
            'label'   => esc_html__('Size', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 30, 'max' => 100]],
            'default' => ['size' => 56, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__play' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('play_bg', [
            'label'     => esc_html__('Background', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255,255,255,0.95)',
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__play' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('play_icon_color', [
            'label'     => esc_html__('Icon Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1a1a2e',
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__play svg' => 'fill: {{VALUE}};',
            ],
        ]);

        $this->add_control('play_hover_bg', [
            'label'     => esc_html__('Hover Background', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__card:hover .nfa-vtestimonials__play' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('play_hover_scale', [
            'label'   => esc_html__('Hover Scale', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 100, 'max' => 150]],
            'default' => ['size' => 115, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__card:hover .nfa-vtestimonials__play' => 'transform: translate(-50%, -50%) scale(calc({{SIZE}} / 100));',
            ],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'     => 'play_shadow',
            'selector' => '{{WRAPPER}} .nfa-vtestimonials__play',
        ]);

        $this->end_controls_section();
    }

    /* ── Style: Content ──────────────────────────────────────────────── */
    private function register_style_content_controls(): void
    {
        $this->start_controls_section('section_style_content', [
            'label'     => esc_html__('Content', 'nebula-forge-addons-for-elementor'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['layout!' => 'reel'],
        ]);

        $this->add_responsive_control('content_padding', [
            'label'      => esc_html__('Content Padding', 'nebula-forge-addons-for-elementor'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => [
                'top' => '16', 'right' => '16', 'bottom' => '4', 'left' => '16',
                'unit' => 'px', 'isLinked' => false,
            ],
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        /* Quote */
        $this->add_control('heading_quote_style', [
            'label'     => esc_html__('Quote', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'quote_typography',
            'selector' => '{{WRAPPER}} .nfa-vtestimonials__quote',
        ]);

        $this->add_control('quote_color', [
            'label'     => esc_html__('Quote Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#333333',
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__quote' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('quote_spacing', [
            'label'   => esc_html__('Quote Spacing', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 0, 'max' => 40]],
            'default' => ['size' => 12, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__quote' => 'margin-bottom: {{SIZE}}{{UNIT}};',
            ],
        ]);

        /* Name */
        $this->add_control('heading_name_style', [
            'label'     => esc_html__('Name', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'name_typography',
            'selector' => '{{WRAPPER}} .nfa-vtestimonials__name',
        ]);

        $this->add_control('name_color', [
            'label'     => esc_html__('Name Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1a1a2e',
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__name' => 'color: {{VALUE}};',
            ],
        ]);

        /* Role */
        $this->add_control('heading_role_style', [
            'label'     => esc_html__('Role', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'role_typography',
            'selector' => '{{WRAPPER}} .nfa-vtestimonials__role',
        ]);

        $this->add_control('role_color', [
            'label'     => esc_html__('Role Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#888888',
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__role' => 'color: {{VALUE}};',
            ],
        ]);

        /* Rating */
        $this->add_control('heading_rating_style', [
            'label'     => esc_html__('Rating', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('rating_color', [
            'label'     => esc_html__('Star Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#fbbf24',
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__rating' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('rating_size', [
            'label'   => esc_html__('Star Size', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 10, 'max' => 30]],
            'default' => ['size' => 16, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__rating' => 'font-size: {{SIZE}}{{UNIT}};',
            ],
        ]);

        /* Avatar */
        $this->add_control('heading_avatar_style', [
            'label'     => esc_html__('Avatar', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_responsive_control('avatar_size', [
            'label'   => esc_html__('Avatar Size', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 24, 'max' => 80]],
            'default' => ['size' => 40, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__avatar' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'avatar_border',
            'selector' => '{{WRAPPER}} .nfa-vtestimonials__avatar',
        ]);

        $this->end_controls_section();
    }

    /* ── Style: Hover ────────────────────────────────────────────────── */
    private function register_style_hover_controls(): void
    {
        $this->start_controls_section('section_style_hover', [
            'label'     => esc_html__('Hover Effects', 'nebula-forge-addons-for-elementor'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['layout!' => 'reel'],
        ]);

        $this->add_control('hover_lift', [
            'label'        => esc_html__('Hover Lift', 'nebula-forge-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'nebula-forge-addons-for-elementor'),
            'label_off'    => esc_html__('No', 'nebula-forge-addons-for-elementor'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('hover_lift_amount', [
            'label'   => esc_html__('Lift Amount', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 0, 'max' => 20]],
            'default' => ['size' => 6, 'unit' => 'px'],
            'condition' => ['hover_lift' => 'yes'],
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__card:hover' => 'transform: translateY(-{{SIZE}}{{UNIT}});',
            ],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'     => 'card_hover_shadow',
            'label'    => esc_html__('Hover Shadow', 'nebula-forge-addons-for-elementor'),
            'selector' => '{{WRAPPER}} .nfa-vtestimonials__card:hover',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'card_hover_background',
            'label'    => esc_html__('Hover Background', 'nebula-forge-addons-for-elementor'),
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .nfa-vtestimonials__card:hover',
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'card_hover_border',
            'label'    => esc_html__('Hover Border', 'nebula-forge-addons-for-elementor'),
            'selector' => '{{WRAPPER}} .nfa-vtestimonials__card:hover',
        ]);

        $this->add_control('hover_thumb_zoom', [
            'label'        => esc_html__('Thumbnail Zoom on Hover', 'nebula-forge-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'nebula-forge-addons-for-elementor'),
            'label_off'    => esc_html__('No', 'nebula-forge-addons-for-elementor'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('hover_thumb_zoom_amount', [
            'label'   => esc_html__('Zoom Scale (%)', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 100, 'max' => 140]],
            'default' => ['size' => 108, 'unit' => 'px'],
            'condition' => ['hover_thumb_zoom' => 'yes'],
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__card:hover .nfa-vtestimonials__thumb img' => 'transform: scale(calc({{SIZE}} / 100));',
            ],
        ]);

        $this->end_controls_section();
    }

    /* ── Style: Navigation ───────────────────────────────────────────── */
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
                '{{WRAPPER}} .nfa-vtestimonials__arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('arrow_color', [
            'label'     => esc_html__('Arrow Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1a1a2e',
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__arrow' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('arrow_bg', [
            'label'     => esc_html__('Arrow Background', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__arrow' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('arrow_border_color', [
            'label'     => esc_html__('Arrow Border Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#e0e0e0',
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__arrow' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('arrow_hover_bg', [
            'label'     => esc_html__('Arrow Hover BG', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1a1a2e',
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__arrow:hover' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('arrow_hover_color', [
            'label'     => esc_html__('Arrow Hover Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__arrow:hover' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('heading_dots_style', [
            'label'     => esc_html__('Dots', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => ['show_dots' => 'yes'],
        ]);

        $this->add_control('dot_color', [
            'label'     => esc_html__('Dot Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#d1d5db',
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__dot' => 'background-color: {{VALUE}};',
            ],
            'condition' => ['show_dots' => 'yes'],
        ]);

        $this->add_control('dot_active_color', [
            'label'     => esc_html__('Active Dot Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1a1a2e',
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__dot.is-active' => 'background-color: {{VALUE}};',
            ],
            'condition' => ['show_dots' => 'yes'],
        ]);

        $this->add_control('dot_size', [
            'label'   => esc_html__('Dot Size', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 6, 'max' => 20]],
            'default' => ['size' => 10, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-vtestimonials__dot' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
            'condition' => ['show_dots' => 'yes'],
        ]);

        $this->end_controls_section();
    }

    /* ------------------------------------------------------------------
     * Render helpers
     * ----------------------------------------------------------------*/

    /**
     * Extract a video embed URL from a given link.
     */
    private function get_embed_url(string $url): string
    {
        // YouTube
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1] . '?autoplay=1&rel=0';
        }
        // Vimeo
        if (preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $url, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1] . '?autoplay=1';
        }
        // Fallback (self-hosted / direct)
        return $url;
    }

    /**
     * Render a single testimonial card.
     */
    private function render_card(array $item, string $video_action): void
    {
        $video_url   = $item['video_url']['url'] ?? '';
        $has_video   = !empty($video_url);
        $thumb_url   = $item['thumbnail']['url'] ?? '';
        $name        = $item['name'] ?? '';
        $role        = $item['role'] ?? '';
        $quote       = $item['quote'] ?? '';
        $duration    = $item['video_duration'] ?? '';
        $avatar_url  = $item['avatar']['url'] ?? '';
        $rating      = isset($item['rating']) ? max(0, min(5, (int) $item['rating'])) : 0;

        $card_attrs = '';
        if ($has_video) {
            if ($video_action === 'lightbox') {
                $card_attrs = ' data-video-url="' . esc_attr($this->get_embed_url($video_url)) . '" data-action="lightbox"';
            } elseif ($video_action === 'inline') {
                $card_attrs = ' data-video-url="' . esc_attr($this->get_embed_url($video_url)) . '" data-action="inline"';
            } elseif ($video_action === 'new_tab') {
                $card_attrs = ' data-video-url="' . esc_attr(esc_url($video_url)) . '" data-action="new_tab"';
            }
        }
        ?>
        <div class="nfa-vtestimonials__card"<?php echo $card_attrs; ?>>
            <?php if (!empty($thumb_url)) : ?>
                <div class="nfa-vtestimonials__thumb">
                    <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($name); ?>" loading="lazy">
                    <?php if ($has_video) : ?>
                        <button class="nfa-vtestimonials__play" type="button" aria-label="<?php echo esc_attr(
                            /* translators: %s: person name */
                            sprintf(__('Play video testimonial from %s', 'nebula-forge-addons-for-elementor'), $name)
                        ); ?>">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                        </button>
                    <?php endif; ?>
                    <?php if (!empty($duration)) : ?>
                        <span class="nfa-vtestimonials__duration"><?php echo esc_html($duration); ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="nfa-vtestimonials__content">
                <?php if ($rating > 0) : ?>
                    <div class="nfa-vtestimonials__rating" aria-label="<?php echo esc_attr(
                        sprintf(__('Rated %d out of 5', 'nebula-forge-addons-for-elementor'), $rating)
                    ); ?>">
                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <?php echo $i <= $rating ? '&#9733;' : '&#9734;'; ?>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($quote)) : ?>
                    <p class="nfa-vtestimonials__quote"><?php echo esc_html($quote); ?></p>
                <?php endif; ?>

                <div class="nfa-vtestimonials__author">
                    <?php if (!empty($avatar_url)) : ?>
                        <img class="nfa-vtestimonials__avatar" src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($name); ?>" loading="lazy">
                    <?php endif; ?>
                    <div class="nfa-vtestimonials__author-info">
                        <?php if (!empty($name)) : ?>
                            <div class="nfa-vtestimonials__name"><?php echo esc_html($name); ?></div>
                        <?php endif; ?>
                        <?php if (!empty($role)) : ?>
                            <div class="nfa-vtestimonials__role"><?php echo esc_html($role); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /* ------------------------------------------------------------------
     * Render
     * ----------------------------------------------------------------*/
    protected function render(): void
    {
        $settings     = $this->get_settings_for_display();
        $testimonials = !empty($settings['testimonials']) && is_array($settings['testimonials']) ? $settings['testimonials'] : [];
        $layout       = $settings['layout'] ?? 'grid';
        $video_action = $settings['video_action'] ?? 'lightbox';

        if (empty($testimonials)) {
            return;
        }

        if ($layout === 'carousel') {
            $this->render_carousel($settings, $testimonials, $video_action);
        } elseif ($layout === 'reel') {
            $this->render_reel($settings, $testimonials, $video_action);
        } else {
            $this->render_grid($settings, $testimonials, $video_action);
        }
    }

    /* ── Grid Render ─────────────────────────────────────────────────── */
    private function render_grid(array $settings, array $testimonials, string $video_action): void
    {
        $hover_lift  = ($settings['hover_lift'] ?? '') === 'yes' ? ' nfa-vtestimonials--lift' : '';
        $thumb_zoom  = ($settings['hover_thumb_zoom'] ?? '') === 'yes' ? ' nfa-vtestimonials--thumb-zoom' : '';
        ?>
        <div class="nfa-vtestimonials nfa-vtestimonials--grid<?php echo esc_attr($hover_lift . $thumb_zoom); ?>">
            <div class="nfa-vtestimonials__grid">
                <?php foreach ($testimonials as $item) : ?>
                    <?php $this->render_card($item, $video_action); ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    /* ── Reel Render ──────────────────────────────────────────────────── */
    private function render_reel(array $settings, array $testimonials, string $video_action): void
    {
        ?>
        <div class="nfa-vtestimonials nfa-vtestimonials--reel">
            <div class="nfa-vtestimonials__reel">
                <?php foreach ($testimonials as $item) : ?>
                    <?php $this->render_reel_card($item, $video_action); ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Render a single reel card (video-only, portrait thumbnail).
     */
    private function render_reel_card(array $item, string $video_action): void
    {
        $video_url  = $item['video_url']['url'] ?? '';
        $has_video  = !empty($video_url);
        $thumb_url  = $item['thumbnail']['url'] ?? '';
        $name       = $item['name'] ?? '';

        $card_attrs = '';
        if ($has_video) {
            if ($video_action === 'lightbox') {
                $card_attrs = ' data-video-url="' . esc_attr($this->get_embed_url($video_url)) . '" data-action="lightbox"';
            } elseif ($video_action === 'inline') {
                $card_attrs = ' data-video-url="' . esc_attr($this->get_embed_url($video_url)) . '" data-action="inline"';
            } elseif ($video_action === 'new_tab') {
                $card_attrs = ' data-video-url="' . esc_attr(esc_url($video_url)) . '" data-action="new_tab"';
            }
        }
        ?>
        <div class="nfa-vtestimonials__reel-card nfa-vtestimonials__card"<?php echo $card_attrs; ?>>
            <?php if (!empty($thumb_url)) : ?>
                <img class="nfa-vtestimonials__reel-img" src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($name); ?>" loading="lazy">
            <?php endif; ?>

            <?php if ($has_video) : ?>
                <button class="nfa-vtestimonials__reel-play nfa-vtestimonials__play" type="button" aria-label="<?php echo esc_attr(
                    sprintf(__('Play video testimonial from %s', 'nebula-forge-addons-for-elementor'), $name)
                ); ?>">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                </button>
            <?php endif; ?>

            <div class="nfa-vtestimonials__reel-controls">
                <?php if ($has_video) : ?>
                    <span class="nfa-vtestimonials__reel-ctrl" aria-hidden="true">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                    </span>
                    <span class="nfa-vtestimonials__reel-ctrl" aria-hidden="true">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/></svg>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    /* ── Carousel Render ─────────────────────────────────────────────── */
    private function render_carousel(array $settings, array $testimonials, string $video_action): void
    {
        $per_view         = intval($settings['slides_per_view'] ?? 3);
        $per_view_tablet  = intval($settings['slides_per_view_tablet'] ?? min($per_view, 2));
        $per_view_mobile  = intval($settings['slides_per_view_mobile'] ?? 1);
        $gap              = intval($settings['slide_gap']['size'] ?? 24);
        $show_arrows      = !empty($settings['show_arrows']) && $settings['show_arrows'] === 'yes';
        $nav_position     = $settings['nav_position'] ?? 'bottom-center';
        $show_dots        = !empty($settings['show_dots']) && $settings['show_dots'] === 'yes';
        $mouse_drag       = (!empty($settings['mouse_drag']) && $settings['mouse_drag'] === 'yes') ? 'yes' : 'no';
        $infinite_loop    = (!empty($settings['infinite_loop']) && $settings['infinite_loop'] === 'yes') ? 'yes' : 'no';
        $transition_speed = intval($settings['transition_speed'] ?? 450);
        $autoplay         = $settings['autoplay'] ?? 'no';
        $autoplay_speed   = $settings['autoplay_speed'] ?? 4000;
        $pause_on_hover   = $settings['pause_on_hover'] ?? 'yes';
        $hover_lift       = ($settings['hover_lift'] ?? '') === 'yes' ? ' nfa-vtestimonials--lift' : '';
        $thumb_zoom       = ($settings['hover_thumb_zoom'] ?? '') === 'yes' ? ' nfa-vtestimonials--thumb-zoom' : '';

        $wrapper_class = 'nfa-vtestimonials nfa-vtestimonials--carousel' . $hover_lift . $thumb_zoom;
        if ($show_arrows) {
            $wrapper_class .= ' nfa-vtestimonials--nav-' . $nav_position;
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
            <button class="nfa-vtestimonials__arrow nfa-vtestimonials__arrow--prev nfa-vtestimonials__arrow--side" aria-label="<?php esc_attr_e('Previous', 'nebula-forge-addons-for-elementor'); ?>">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M10 12L6 8L10 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <?php endif; ?>

            <div class="nfa-vtestimonials__viewport">
                <div class="nfa-vtestimonials__track" style="gap:<?php echo esc_attr($gap); ?>px;">
                    <?php foreach ($testimonials as $item) : ?>
                        <?php $this->render_card($item, $video_action); ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if ($show_arrows && $nav_position === 'sides') : ?>
            <button class="nfa-vtestimonials__arrow nfa-vtestimonials__arrow--next nfa-vtestimonials__arrow--side" aria-label="<?php esc_attr_e('Next', 'nebula-forge-addons-for-elementor'); ?>">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M6 4L10 8L6 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <?php endif; ?>

            <?php if ($show_arrows && $nav_position !== 'sides') : ?>
            <div class="nfa-vtestimonials__nav">
                <button class="nfa-vtestimonials__arrow nfa-vtestimonials__arrow--prev" aria-label="<?php esc_attr_e('Previous', 'nebula-forge-addons-for-elementor'); ?>">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M10 12L6 8L10 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <button class="nfa-vtestimonials__arrow nfa-vtestimonials__arrow--next" aria-label="<?php esc_attr_e('Next', 'nebula-forge-addons-for-elementor'); ?>">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M6 4L10 8L6 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
            <?php endif; ?>

            <?php if ($show_dots) : ?>
            <div class="nfa-vtestimonials__dots"></div>
            <?php endif; ?>
        </div>
        <?php
    }
}
