<?php
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
use Elementor\Widget_Base;

class Stats_Grid_Widget extends Widget_Base
{
    public function get_name(): string
    {
        return 'nfa-stats-grid';
    }

    public function get_title(): string
    {
        return esc_html__('Stats Grid', 'nebula-forge-addons-for-elementor');
    }

    public function get_icon(): string
    {
        return 'eicon-counter-circle';
    }

    public function get_categories(): array
    {
        return ['nebula-forge'];
    }

    public function get_keywords(): array
    {
        return ['stats', 'metrics', 'kpi', 'counter'];
    }

    public function get_style_depends(): array
    {
        return ['nebula-forge-elementor-addon-frontend'];
    }

    protected function register_controls(): void
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Content', 'nebula-forge-addons-for-elementor'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'stat_value',
            [
                'label' => esc_html__('Value', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '99.9%',
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'stat_label',
            [
                'label' => esc_html__('Label', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Uptime', 'nebula-forge-addons-for-elementor'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'stat_helper',
            [
                'label' => esc_html__('Helper Text', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Past 12 months', 'nebula-forge-addons-for-elementor'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'stats',
            [
                'label' => esc_html__('Stats', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'stat_value' => '99.9%',
                        'stat_label' => esc_html__('Uptime', 'nebula-forge-addons-for-elementor'),
                        'stat_helper' => esc_html__('Past 12 months', 'nebula-forge-addons-for-elementor'),
                    ],
                    [
                        'stat_value' => '4.8',
                        'stat_label' => esc_html__('CSAT', 'nebula-forge-addons-for-elementor'),
                        'stat_helper' => esc_html__('Avg. app store rating', 'nebula-forge-addons-for-elementor'),
                    ],
                    [
                        'stat_value' => '320%',
                        'stat_label' => esc_html__('ROI', 'nebula-forge-addons-for-elementor'),
                        'stat_helper' => esc_html__('After 6 months', 'nebula-forge-addons-for-elementor'),
                    ],
                    [
                        'stat_value' => '24/7',
                        'stat_label' => esc_html__('Support', 'nebula-forge-addons-for-elementor'),
                        'stat_helper' => esc_html__('Global coverage', 'nebula-forge-addons-for-elementor'),
                    ],
                ],
                'title_field' => '{{{ stat_value }}} — {{{ stat_label }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_items',
            [
                'label' => esc_html__('Items', 'nebula-forge-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'items_columns',
            [
                'label' => esc_html__('Columns', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['col'],
                'range' => [
                    'col' => [
                        'min' => 1,
                        'max' => 4,
                    ],
                ],
                'default' => [
                    'size' => 2,
                    'unit' => 'col',
                ],
                'tablet_default' => [
                    'size' => 2,
                    'unit' => 'col',
                ],
                'mobile_default' => [
                    'size' => 1,
                    'unit' => 'col',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-stats-grid__items' => 'grid-template-columns: repeat({{SIZE}}, minmax(0, 1fr));',
                ],
            ]
        );

        $this->add_responsive_control(
            'items_gap',
            [
                'label' => esc_html__('Row/Column Gap', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 120,
                    ],
                ],
                'default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-stats-grid__items' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__('Item Padding', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '16',
                    'right' => '16',
                    'bottom' => '16',
                    'left' => '16',
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-stats-grid__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'item_radius',
            [
                'label' => esc_html__('Item Radius', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 80,
                    ],
                ],
                'default' => [
                    'size' => 14,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-stats-grid__item' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .nfa-stats-grid__item',
                'fields_options' => [
                    'color' => [
                        'default' => 'rgba(255,255,255,0.03)',
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .nfa-stats-grid__item',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow',
                'selector' => '{{WRAPPER}} .nfa-stats-grid__item',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_text',
            [
                'label' => esc_html__('Text', 'nebula-forge-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'value_typography',
                'selector' => '{{WRAPPER}} .nfa-stats-grid__value',
            ]
        );

        $this->add_control(
            'value_color',
            [
                'label' => esc_html__('Value Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f8fafc',
                'selectors' => [
                    '{{WRAPPER}} .nfa-stats-grid__value' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .nfa-stats-grid__label',
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => esc_html__('Label Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#cbd5e1',
                'selectors' => [
                    '{{WRAPPER}} .nfa-stats-grid__label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'helper_typography',
                'selector' => '{{WRAPPER}} .nfa-stats-grid__helper',
            ]
        );

        $this->add_control(
            'helper_color',
            [
                'label' => esc_html__('Helper Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#9ca3af',
                'selectors' => [
                    '{{WRAPPER}} .nfa-stats-grid__helper' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $stats = $settings['stats'] ?? [];
        ?>
        <div class="nfa-stats-grid">
            <?php if (!empty($stats)) : ?>
                <div class="nfa-stats-grid__items">
                    <?php foreach ($stats as $stat) : ?>
                        <div class="nfa-stats-grid__item">
                            <?php if (!empty($stat['stat_value'])) : ?>
                                <div class="nfa-stats-grid__value"><?php echo esc_html($stat['stat_value']); ?></div>
                            <?php endif; ?>
                            <?php if (!empty($stat['stat_label'])) : ?>
                                <div class="nfa-stats-grid__label"><?php echo esc_html($stat['stat_label']); ?></div>
                            <?php endif; ?>
                            <?php if (!empty($stat['stat_helper'])) : ?>
                                <div class="nfa-stats-grid__helper"><?php echo esc_html($stat['stat_helper']); ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
