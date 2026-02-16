<?php
/**
 * Feature List Widget
 *
 * Responsive grid of feature items with icons, titles, and descriptions.
 *
 * @package NebulaForgeAddon
 * @since   0.1.0
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
use Elementor\Widget_Base;

class Feature_List_Widget extends Widget_Base
{
    public function get_name(): string
    {
        return 'nfa-feature-list';
    }

    public function get_title(): string
    {
        return esc_html__('Feature List', 'nebula-forge-addons-for-elementor');
    }

    public function get_icon(): string
    {
        return 'eicon-bullet-list';
    }

    public function get_categories(): array
    {
        return ['nebula-forge'];
    }

    public function get_keywords(): array
    {
        return ['features', 'list', 'icon', 'benefits'];
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
            'feature_icon',
            [
                'label' => esc_html__('Icon', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-check',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $repeater->add_control(
            'feature_title',
            [
                'label' => esc_html__('Title', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Fast setup', 'nebula-forge-addons-for-elementor'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'feature_text',
            [
                'label' => esc_html__('Description', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('Launch campaigns without developer overhead.', 'nebula-forge-addons-for-elementor'),
                'rows' => 2,
            ]
        );

        $this->add_control(
            'features',
            [
                'label' => esc_html__('Features', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'feature_title' => esc_html__('Fast setup', 'nebula-forge-addons-for-elementor'),
                        'feature_text' => esc_html__('Launch campaigns without developer overhead.', 'nebula-forge-addons-for-elementor'),
                        'feature_icon' => [
                            'value' => 'fas fa-check',
                            'library' => 'fa-solid',
                        ],
                    ],
                    [
                        'feature_title' => esc_html__('Scales with you', 'nebula-forge-addons-for-elementor'),
                        'feature_text' => esc_html__('Performance-focused blocks for growing sites.', 'nebula-forge-addons-for-elementor'),
                        'feature_icon' => [
                            'value' => 'fas fa-rocket',
                            'library' => 'fa-solid',
                        ],
                    ],
                    [
                        'feature_title' => esc_html__('Design ready', 'nebula-forge-addons-for-elementor'),
                        'feature_text' => esc_html__('Modern defaults that stay on-brand.', 'nebula-forge-addons-for-elementor'),
                        'feature_icon' => [
                            'value' => 'fas fa-palette',
                            'library' => 'fa-solid',
                        ],
                    ],
                ],
                'title_field' => '{{{ feature_title }}}',
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
            'items_gap',
            [
                'label' => esc_html__('Row Gap', 'nebula-forge-addons-for-elementor'),
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
                    '{{WRAPPER}} .nfa-feature-list__items' => 'row-gap: {{SIZE}}{{UNIT}};',
                ],
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
                    '{{WRAPPER}} .nfa-feature-list__items' => 'grid-template-columns: repeat({{SIZE}}, minmax(0, 1fr));',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'item_title_typography',
                'selector' => '{{WRAPPER}} .nfa-feature-list__title',
            ]
        );

        $this->add_control(
            'item_title_color',
            [
                'label' => esc_html__('Title Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#131313',
                'selectors' => [
                    '{{WRAPPER}} .nfa-feature-list__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'item_text_typography',
                'selector' => '{{WRAPPER}} .nfa-feature-list__text',
            ]
        );

        $this->add_control(
            'item_text_color',
            [
                'label' => esc_html__('Text Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(19,19,19,0.55)',
                'selectors' => [
                    '{{WRAPPER}} .nfa-feature-list__text' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .nfa-feature-list__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selectors' => [
                    '{{WRAPPER}} .nfa-feature-list__item' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .nfa-feature-list__item',
                'fields_options' => [
                    'color' => [
                        'default' => 'rgba(255,255,255,0.02)',
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .nfa-feature-list__item',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow',
                'selector' => '{{WRAPPER}} .nfa-feature-list__item',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__('Icon', 'nebula-forge-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#0ea5e9',
                'selectors' => [
                    '{{WRAPPER}} .nfa-feature-list__icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__('Size', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 12,
                        'max' => 80,
                    ],
                ],
                'default' => [
                    'size' => 24,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-feature-list__icon' => 'font-size: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .nfa-feature-list__icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_background_color',
            [
                'label' => esc_html__('Background Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(125, 211, 252, 0.1)',
                'selectors' => [
                    '{{WRAPPER}} .nfa-feature-list__icon-wrap' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => esc_html__('Padding', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '10',
                    'right' => '10',
                    'bottom' => '10',
                    'left' => '10',
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-feature-list__icon-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_radius',
            [
                'label' => esc_html__('Corner Radius', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 120,
                    ],
                ],
                'default' => [
                    'size' => 12,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-feature-list__icon-wrap' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $features = $settings['features'] ?? [];
        ?>
        <div class="nfa-feature-list">
            <?php if (!empty($features)) : ?>
                <div class="nfa-feature-list__items">
                    <?php foreach ($features as $feature) : ?>
                        <div class="nfa-feature-list__item">
                            <div class="nfa-feature-list__icon-wrap">
                                <?php if (!empty($feature['feature_icon'])) : ?>
                                    <span class="nfa-feature-list__icon" aria-hidden="true">
                                        <?php Icons_Manager::render_icon($feature['feature_icon'], ['aria-hidden' => 'true']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="nfa-feature-list__content">
                                <?php if (!empty($feature['feature_title'])) : ?>
                                    <h4 class="nfa-feature-list__title"><?php echo esc_html($feature['feature_title']); ?></h4>
                                <?php endif; ?>
                                <?php if (!empty($feature['feature_text'])) : ?>
                                    <p class="nfa-feature-list__text"><?php echo esc_html($feature['feature_text']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
