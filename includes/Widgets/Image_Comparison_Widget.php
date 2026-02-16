<?php
/**
 * Image Comparison Widget
 *
 * Before / after image comparison slider with draggable
 * handle, orientation options, and label overlays.
 *
 * @package NebulaForgeAddon
 * @since   0.5.0
 */

namespace NebulaForgeAddon\Widgets;

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Widget_Base;

class Image_Comparison_Widget extends Widget_Base
{
    public function get_name(): string
    {
        return 'nfa-image-comparison';
    }

    public function get_title(): string
    {
        return esc_html__('Image Comparison', 'nebula-forge-addons-for-elementor');
    }

    public function get_icon(): string
    {
        return 'eicon-image-before-after';
    }

    public function get_categories(): array
    {
        return ['nebula-forge'];
    }

    public function get_keywords(): array
    {
        return ['compare', 'before', 'after', 'slider', 'image'];
    }

    public function get_style_depends(): array
    {
        return ['nebula-forge-elementor-addon-frontend'];
    }

    public function get_script_depends(): array
    {
        return ['nebula-forge-elementor-addon-frontend'];
    }

    protected function register_controls(): void
    {
        /* ── Content ────────────────────────────────── */
        $this->start_controls_section('section_images', [
            'label' => esc_html__('Images', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('before_image', [
            'label'   => esc_html__('Before Image', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => Utils::get_placeholder_image_src()],
        ]);

        $this->add_control('before_label', [
            'label'   => esc_html__('Before Label', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::TEXT,
            'default' => esc_html__('Before', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('after_image', [
            'label'   => esc_html__('After Image', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => Utils::get_placeholder_image_src()],
        ]);

        $this->add_control('after_label', [
            'label'   => esc_html__('After Label', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::TEXT,
            'default' => esc_html__('After', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->end_controls_section();

        /* ── Settings ──────────────────────────────── */
        $this->start_controls_section('section_settings', [
            'label' => esc_html__('Settings', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('orientation', [
            'label'   => esc_html__('Orientation', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'horizontal',
            'options' => [
                'horizontal' => esc_html__('Horizontal', 'nebula-forge-addons-for-elementor'),
                'vertical'   => esc_html__('Vertical', 'nebula-forge-addons-for-elementor'),
            ],
        ]);

        $this->add_control('initial_position', [
            'label'   => esc_html__('Handle Start (%)', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['%' => ['min' => 10, 'max' => 90]],
            'default' => ['size' => 50, 'unit' => '%'],
        ]);

        $this->add_control('show_labels', [
            'label'        => esc_html__('Show Labels', 'nebula-forge-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'default'      => 'yes',
            'return_value' => 'yes',
        ]);

        $this->add_control('show_on_hover', [
            'label'        => esc_html__('Labels Only on Hover', 'nebula-forge-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'default'      => '',
            'return_value' => 'yes',
            'condition'    => ['show_labels' => 'yes'],
        ]);

        $this->end_controls_section();

        /* ── Style: Container ─────────────────────── */
        $this->start_controls_section('section_style_container', [
            'label' => esc_html__('Container', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('container_radius', [
            'label'     => esc_html__('Border Radius', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 50]],
            'default'   => ['size' => 12, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .nfa-compare' => 'border-radius: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'container_border',
            'selector' => '{{WRAPPER}} .nfa-compare',
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'     => 'container_shadow',
            'selector' => '{{WRAPPER}} .nfa-compare',
        ]);

        $this->end_controls_section();

        /* ── Style: Handle ────────────────────────── */
        $this->start_controls_section('section_style_handle', [
            'label' => esc_html__('Handle', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('handle_color', [
            'label'     => esc_html__('Handle Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .nfa-compare__handle' => 'background: {{VALUE}};',
                '{{WRAPPER}} .nfa-compare__line'   => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('handle_size', [
            'label'     => esc_html__('Handle Size', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 24, 'max' => 60]],
            'default'   => ['size' => 40, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-compare__handle' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('line_width', [
            'label'     => esc_html__('Divider Width', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 1, 'max' => 6]],
            'default'   => ['size' => 3, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-compare--horizontal .nfa-compare__line' => 'width: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .nfa-compare--vertical .nfa-compare__line'   => 'height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        /* ── Style: Labels ────────────────────────── */
        $this->start_controls_section('section_style_labels', [
            'label'     => esc_html__('Labels', 'nebula-forge-addons-for-elementor'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['show_labels' => 'yes'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'label_typography',
            'selector' => '{{WRAPPER}} .nfa-compare__label',
        ]);

        $this->add_control('label_color', [
            'label'     => esc_html__('Text Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => ['{{WRAPPER}} .nfa-compare__label' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('label_bg', [
            'label'     => esc_html__('Background', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(0,0,0,0.55)',
            'selectors' => ['{{WRAPPER}} .nfa-compare__label' => 'background: {{VALUE}};'],
        ]);

        $this->add_control('label_padding', [
            'label'      => esc_html__('Padding', 'nebula-forge-addons-for-elementor'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => ['top' => '4', 'right' => '14', 'bottom' => '4', 'left' => '14', 'unit' => 'px', 'isLinked' => false],
            'selectors'  => ['{{WRAPPER}} .nfa-compare__label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('label_radius', [
            'label'     => esc_html__('Border Radius', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 30]],
            'default'   => ['size' => 6, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .nfa-compare__label' => 'border-radius: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $s      = $this->get_settings_for_display();
        $orient = $s['orientation'] ?? 'horizontal';
        $start  = $s['initial_position']['size'] ?? 50;
        $labels = $s['show_labels'] === 'yes';
        $hover  = $s['show_on_hover'] === 'yes';

        $before_url = $s['before_image']['url'] ?? '';
        $after_url  = $s['after_image']['url']  ?? '';

        if (!$before_url || !$after_url) {
            return;
        }

        $cls = 'nfa-compare nfa-compare--' . esc_attr($orient);
        if ($hover) {
            $cls .= ' nfa-compare--hover-labels';
        }
        ?>
        <div class="<?php echo esc_attr($cls); ?>"
             data-compare="true"
             data-orientation="<?php echo esc_attr($orient); ?>"
             data-start="<?php echo esc_attr($start); ?>">

            <div class="nfa-compare__before" style="clip-path: inset(0 <?php echo esc_attr(100 - (int) $start); ?>% 0 0);">
                <img src="<?php echo esc_url($before_url); ?>"
                     alt="<?php echo esc_attr($s['before_label'] ?? ''); ?>"
                     draggable="false" />
                <?php if ($labels && !empty($s['before_label'])) : ?>
                    <span class="nfa-compare__label nfa-compare__label--before"><?php echo esc_html($s['before_label']); ?></span>
                <?php endif; ?>
            </div>

            <div class="nfa-compare__after">
                <img src="<?php echo esc_url($after_url); ?>"
                     alt="<?php echo esc_attr($s['after_label'] ?? ''); ?>"
                     draggable="false" />
                <?php if ($labels && !empty($s['after_label'])) : ?>
                    <span class="nfa-compare__label nfa-compare__label--after"><?php echo esc_html($s['after_label']); ?></span>
                <?php endif; ?>
            </div>

            <div class="nfa-compare__slider" style="<?php echo $orient === 'horizontal' ? 'left:' . esc_attr($start) . '%' : 'top:' . esc_attr($start) . '%'; ?>">
                <span class="nfa-compare__line"></span>
                <span class="nfa-compare__handle" aria-label="<?php esc_attr_e('Drag to compare', 'nebula-forge-addons-for-elementor'); ?>">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="currentColor" aria-hidden="true">
                        <path d="M4.5 1L0 7l4.5 6M9.5 1L14 7l-9.5 6" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </div>
        </div>
        <?php
    }
}
