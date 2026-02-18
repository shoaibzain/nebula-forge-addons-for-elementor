<?php
/**
 * Countdown Timer Widget
 *
 * Countdown to a target date showing days, hours, minutes and
 * seconds with multiple display styles and expiry actions.
 *
 * @package NebulaForgeAddon
 * @since   0.5.0
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
use Elementor\Widget_Base;

class Countdown_Timer_Widget extends Widget_Base
{
    public function get_name(): string
    {
        return 'nfa-countdown-timer';
    }

    public function get_title(): string
    {
        return esc_html__('Countdown Timer', 'nebula-forge-addons-for-elementor');
    }

    public function get_icon(): string
    {
        return 'eicon-countdown';
    }

    public function get_categories(): array
    {
        return ['nebula-forge'];
    }

    public function get_keywords(): array
    {
        return ['countdown', 'timer', 'clock', 'date', 'launch', 'coming soon'];
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
        $this->start_controls_section('section_content', [
            'label' => esc_html__('Countdown', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('target_date', [
            'label'       => esc_html__('Target Date', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::DATE_TIME,
            'default'     => gmdate('Y-m-d H:i', strtotime('+30 days')),
            'description' => esc_html__('Set the date & time to count down to.', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('display_style', [
            'label'   => esc_html__('Display Style', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'blocks',
            'options' => [
                'blocks' => esc_html__('Blocks', 'nebula-forge-addons-for-elementor'),
                'inline' => esc_html__('Inline', 'nebula-forge-addons-for-elementor'),
                'circle' => esc_html__('Circles', 'nebula-forge-addons-for-elementor'),
            ],
        ]);

        $this->add_control('show_days', [
            'label'        => esc_html__('Show Days', 'nebula-forge-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'default'      => 'yes',
            'return_value' => 'yes',
        ]);

        $this->add_control('show_hours', [
            'label'        => esc_html__('Show Hours', 'nebula-forge-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'default'      => 'yes',
            'return_value' => 'yes',
        ]);

        $this->add_control('show_minutes', [
            'label'        => esc_html__('Show Minutes', 'nebula-forge-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'default'      => 'yes',
            'return_value' => 'yes',
        ]);

        $this->add_control('show_seconds', [
            'label'        => esc_html__('Show Seconds', 'nebula-forge-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'default'      => 'yes',
            'return_value' => 'yes',
        ]);

        $this->add_control('separator', [
            'label'     => esc_html__('Separator', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::TEXT,
            'default'   => ':',
            'condition' => ['display_style' => 'inline'],
        ]);

        $this->end_controls_section();

        /* ── Labels ─────────────────────────────────── */
        $this->start_controls_section('section_labels', [
            'label' => esc_html__('Labels', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('show_labels', [
            'label'        => esc_html__('Show Labels', 'nebula-forge-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'default'      => 'yes',
            'return_value' => 'yes',
        ]);

        $this->add_control('label_days', [
            'label'     => esc_html__('Days', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::TEXT,
            'default'   => esc_html__('Days', 'nebula-forge-addons-for-elementor'),
            'condition' => ['show_labels' => 'yes'],
        ]);

        $this->add_control('label_hours', [
            'label'     => esc_html__('Hours', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::TEXT,
            'default'   => esc_html__('Hours', 'nebula-forge-addons-for-elementor'),
            'condition' => ['show_labels' => 'yes'],
        ]);

        $this->add_control('label_minutes', [
            'label'     => esc_html__('Minutes', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::TEXT,
            'default'   => esc_html__('Minutes', 'nebula-forge-addons-for-elementor'),
            'condition' => ['show_labels' => 'yes'],
        ]);

        $this->add_control('label_seconds', [
            'label'     => esc_html__('Seconds', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::TEXT,
            'default'   => esc_html__('Seconds', 'nebula-forge-addons-for-elementor'),
            'condition' => ['show_labels' => 'yes'],
        ]);

        $this->end_controls_section();

        /* ── Expiry ─────────────────────────────────── */
        $this->start_controls_section('section_expiry', [
            'label' => esc_html__('After Expiry', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('expiry_action', [
            'label'   => esc_html__('Action', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'message',
            'options' => [
                'none'    => esc_html__('Show Zeros', 'nebula-forge-addons-for-elementor'),
                'message' => esc_html__('Show Message', 'nebula-forge-addons-for-elementor'),
                'hide'    => esc_html__('Hide Widget', 'nebula-forge-addons-for-elementor'),
                'redirect' => esc_html__('Redirect', 'nebula-forge-addons-for-elementor'),
            ],
        ]);

        $this->add_control('expiry_message', [
            'label'     => esc_html__('Expiry Message', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::TEXTAREA,
            'default'   => esc_html__('The event has started!', 'nebula-forge-addons-for-elementor'),
            'condition' => ['expiry_action' => 'message'],
        ]);

        $this->add_control('expiry_redirect_url', [
            'label'       => esc_html__('Redirect URL', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::URL,
            'placeholder' => 'https://example.com',
            'condition'   => ['expiry_action' => 'redirect'],
        ]);

        $this->end_controls_section();

        /* ── Style: Digit Blocks ──────────────────── */
        $this->start_controls_section('section_style_digits', [
            'label' => esc_html__('Digit Blocks', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'digit_typography',
            'selector' => '{{WRAPPER}} .nfa-countdown__digit',
        ]);

        $this->add_control('digit_color', [
            'label'     => esc_html__('Digit Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#131313',
            'selectors' => ['{{WRAPPER}} .nfa-countdown__digit' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'digit_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .nfa-countdown__block',
        ]);

        $this->add_responsive_control('block_padding', [
            'label'      => esc_html__('Block Padding', 'nebula-forge-addons-for-elementor'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => ['top' => '20', 'right' => '20', 'bottom' => '20', 'left' => '20', 'unit' => 'px', 'isLinked' => true],
            'selectors'  => ['{{WRAPPER}} .nfa-countdown__block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('block_radius', [
            'label'     => esc_html__('Border Radius', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 50]],
            'default'   => ['size' => 12, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .nfa-countdown__block' => 'border-radius: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'block_border',
            'selector' => '{{WRAPPER}} .nfa-countdown__block',
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'     => 'block_shadow',
            'selector' => '{{WRAPPER}} .nfa-countdown__block',
        ]);

        $this->add_responsive_control('block_gap', [
            'label'     => esc_html__('Gap Between Blocks', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 60]],
            'default'   => ['size' => 16, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .nfa-countdown' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('block_min_width', [
            'label'     => esc_html__('Min Width', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 40, 'max' => 200]],
            'default'   => ['size' => 80, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .nfa-countdown__block' => 'min-width: {{SIZE}}{{UNIT}};'],
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
            'selector' => '{{WRAPPER}} .nfa-countdown__label',
        ]);

        $this->add_control('label_color', [
            'label'     => esc_html__('Label Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(19,19,19,0.55)',
            'selectors' => ['{{WRAPPER}} .nfa-countdown__label' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('label_spacing', [
            'label'     => esc_html__('Spacing', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 30]],
            'default'   => ['size' => 8, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .nfa-countdown__label' => 'margin-top: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        /* ── Style: Separator ─────────────────────── */
        $this->start_controls_section('section_style_sep', [
            'label'     => esc_html__('Separator', 'nebula-forge-addons-for-elementor'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['display_style' => 'inline'],
        ]);

        $this->add_control('separator_color', [
            'label'     => esc_html__('Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(19,19,19,0.3)',
            'selectors' => ['{{WRAPPER}} .nfa-countdown__sep' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        /* ── Style: Circle ────────────────────────── */
        $this->start_controls_section('section_style_circle', [
            'label'     => esc_html__('Circle', 'nebula-forge-addons-for-elementor'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['display_style' => 'circle'],
        ]);

        $this->add_responsive_control('circle_size', [
            'label'     => esc_html__('Circle Size', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 60, 'max' => 200]],
            'default'   => ['size' => 120, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-countdown__block--circle' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .nfa-countdown__block--circle svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('circle_track_color', [
            'label'   => esc_html__('Track Color', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::COLOR,
            'default' => 'rgba(19,19,19,0.08)',
        ]);

        $this->add_control('circle_fill_color', [
            'label'   => esc_html__('Progress Color', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::COLOR,
            'default' => '#0ea5e9',
        ]);

        $this->add_control('circle_stroke', [
            'label'   => esc_html__('Stroke Width', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 2, 'max' => 12]],
            'default' => ['size' => 4, 'unit' => 'px'],
        ]);

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $s     = $this->get_settings_for_display();
        $date  = $s['target_date'] ?? '';
        $style = $s['display_style'] ?? 'blocks';

        if (empty($date)) {
            return;
        }

        $units = [];
        if ($s['show_days'] === 'yes') {
            $units[] = 'days';
        }
        if ($s['show_hours'] === 'yes') {
            $units[] = 'hours';
        }
        if ($s['show_minutes'] === 'yes') {
            $units[] = 'minutes';
        }
        if ($s['show_seconds'] === 'yes') {
            $units[] = 'seconds';
        }

        if (empty($units)) {
            return;
        }

        $labels = [
            'days'    => $s['label_days']    ?? esc_html__('Days', 'nebula-forge-addons-for-elementor'),
            'hours'   => $s['label_hours']   ?? esc_html__('Hours', 'nebula-forge-addons-for-elementor'),
            'minutes' => $s['label_minutes'] ?? esc_html__('Minutes', 'nebula-forge-addons-for-elementor'),
            'seconds' => $s['label_seconds'] ?? esc_html__('Seconds', 'nebula-forge-addons-for-elementor'),
        ];

        $show_labels = $s['show_labels'] === 'yes';
        $separator   = $s['separator'] ?? ':';

        // Circle style settings
        $circle_track  = $s['circle_track_color'] ?? 'rgba(19,19,19,0.08)';
        $circle_fill   = $s['circle_fill_color']  ?? '#0ea5e9';
        $circle_stroke = $s['circle_stroke']['size'] ?? 4;

        // Expiry settings
        $expiry_data = [
            'action'  => $s['expiry_action'] ?? 'none',
            'message' => $s['expiry_message'] ?? '',
        ];
        if ($s['expiry_action'] === 'redirect' && !empty($s['expiry_redirect_url']['url'])) {
            $expiry_data['redirect'] = $s['expiry_redirect_url']['url'];
        }

        // Max values for circle progress
        $max_values = [
            'days'    => 365,
            'hours'   => 24,
            'minutes' => 60,
            'seconds' => 60,
        ];

        $block_class = 'nfa-countdown__block';
        if ($style === 'circle') {
            $block_class .= ' nfa-countdown__block--circle';
        }
        ?>
        <div class="nfa-countdown nfa-countdown--<?php echo esc_attr($style); ?>"
             data-countdown="true"
             data-target="<?php echo esc_attr($date); ?>"
             data-expiry="<?php echo esc_attr(wp_json_encode($expiry_data)); ?>"
             data-units="<?php echo esc_attr(implode(',', $units)); ?>">

            <?php foreach ($units as $i => $unit) : ?>
                <?php if ($style === 'inline' && $i > 0) : ?>
                    <span class="nfa-countdown__sep"><?php echo esc_html($separator); ?></span>
                <?php endif; ?>

                <div class="<?php echo esc_attr($block_class); ?>" data-unit="<?php echo esc_attr($unit); ?>">
                    <?php if ($style === 'circle') : ?>
                        <svg viewBox="0 0 100 100" class="nfa-countdown__ring">
                            <circle cx="50" cy="50" r="44"
                                    stroke="<?php echo esc_attr($circle_track); ?>"
                                    stroke-width="<?php echo esc_attr($circle_stroke); ?>"
                                    fill="none" />
                            <circle cx="50" cy="50" r="44"
                                    class="nfa-countdown__progress"
                                    stroke="<?php echo esc_attr($circle_fill); ?>"
                                    stroke-width="<?php echo esc_attr($circle_stroke); ?>"
                                    fill="none"
                                    stroke-linecap="round"
                                    stroke-dasharray="276.46"
                                    stroke-dashoffset="0"
                                    data-max="<?php echo esc_attr($max_values[$unit]); ?>"
                                    transform="rotate(-90 50 50)" />
                        </svg>
                    <?php endif; ?>

                    <span class="nfa-countdown__digit" data-digit="<?php echo esc_attr($unit); ?>">00</span>

                    <?php if ($show_labels) : ?>
                        <span class="nfa-countdown__label"><?php echo esc_html($labels[$unit]); ?></span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <?php if ($expiry_data['action'] === 'message') : ?>
                <div class="nfa-countdown__expiry" style="display:none;">
                    <?php echo esc_html($expiry_data['message']); ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
