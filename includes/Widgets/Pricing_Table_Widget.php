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

class Pricing_Table_Widget extends Widget_Base
{
    public function get_name(): string
    {
        return 'nfa-pricing-table';
    }

    public function get_title(): string
    {
        return esc_html__('Pricing Table', 'nebula-forge-addons-for-elementor');
    }

    public function get_icon(): string
    {
        return 'eicon-price-table';
    }

    public function get_categories(): array
    {
        return ['nebula-forge'];
    }

    public function get_keywords(): array
    {
        return ['pricing', 'plan', 'subscription', 'table'];
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

        $this->add_control(
            'plan_label',
            [
                'label' => esc_html__('Plan Label', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Starter', 'nebula-forge-addons-for-elementor'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'plan_description',
            [
                'label' => esc_html__('Description', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('For growing teams shipping faster.', 'nebula-forge-addons-for-elementor'),
                'rows' => 3,
            ]
        );

        $this->add_control(
            'price',
            [
                'label' => esc_html__('Price', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => '$29',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'price_suffix',
            [
                'label' => esc_html__('Price Suffix', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('/month', 'nebula-forge-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'highlight',
            [
                'label' => esc_html__('Highlight Plan', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => '',
            ]
        );

        $this->add_control(
            'highlight_label',
            [
                'label' => esc_html__('Highlight Badge', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Most Popular', 'nebula-forge-addons-for-elementor'),
                'condition' => [
                    'highlight' => 'yes',
                ],
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'feature_text',
            [
                'label' => esc_html__('Feature', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Unlimited landing pages', 'nebula-forge-addons-for-elementor'),
                'label_block' => true,
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
                        'feature_text' => esc_html__('Unlimited landing pages', 'nebula-forge-addons-for-elementor'),
                    ],
                    [
                        'feature_text' => esc_html__('Premium starter templates', 'nebula-forge-addons-for-elementor'),
                    ],
                    [
                        'feature_text' => esc_html__('Priority support', 'nebula-forge-addons-for-elementor'),
                    ],
                ],
                'title_field' => '{{{ feature_text }}}',
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => esc_html__('Button Label', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Get Started', 'nebula-forge-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'button_url',
            [
                'label' => esc_html__('Button Link', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_container',
            [
                'label' => esc_html__('Container', 'nebula-forge-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'container_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .nfa-pricing-table',
                'fields_options' => [
                    'color' => [
                        'default' => '#0b1220',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => esc_html__('Padding', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '36',
                    'right' => '36',
                    'bottom' => '36',
                    'left' => '36',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-pricing-table' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'container_radius',
            [
                'label' => esc_html__('Border Radius', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 80,
                    ],
                ],
                'default' => [
                    'size' => 24,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-pricing-table' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_shadow',
                'selector' => '{{WRAPPER}} .nfa-pricing-table',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .nfa-pricing-table',
            ]
        );

        $this->add_control(
            'content_align',
            [
                'label' => esc_html__('Alignment', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'nebula-forge-addons-for-elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'nebula-forge-addons-for-elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'nebula-forge-addons-for-elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .nfa-pricing-table' => 'text-align: {{VALUE}}; align-items: {{content_align_flex.VALUE}};',
                ],
                'selectors_dictionary' => [
                    'left' => 'flex-start',
                    'center' => 'center',
                    'right' => 'flex-end',
                ],
            ]
        );

        $this->add_control(
            'featured_border_color',
            [
                'label' => esc_html__('Featured Border Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .nfa-pricing-table--featured' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_header',
            [
                'label' => esc_html__('Header', 'nebula-forge-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .nfa-pricing-table__label',
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => esc_html__('Label Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f8fafc',
                'selectors' => [
                    '{{WRAPPER}} .nfa-pricing-table__label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .nfa-pricing-table__description',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__('Description Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#cbd5e1',
                'selectors' => [
                    '{{WRAPPER}} .nfa-pricing-table__description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_price',
            [
                'label' => esc_html__('Price', 'nebula-forge-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'selector' => '{{WRAPPER}} .nfa-pricing-table__amount',
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => esc_html__('Price Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f8fafc',
                'selectors' => [
                    '{{WRAPPER}} .nfa-pricing-table__amount' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'suffix_typography',
                'selector' => '{{WRAPPER}} .nfa-pricing-table__suffix',
            ]
        );

        $this->add_control(
            'suffix_color',
            [
                'label' => esc_html__('Suffix Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#94a3b8',
                'selectors' => [
                    '{{WRAPPER}} .nfa-pricing-table__suffix' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_features',
            [
                'label' => esc_html__('Features', 'nebula-forge-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'feature_typography',
                'selector' => '{{WRAPPER}} .nfa-pricing-table__features li',
            ]
        );

        $this->add_control(
            'feature_color',
            [
                'label' => esc_html__('Feature Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#cbd5e1',
                'selectors' => [
                    '{{WRAPPER}} .nfa-pricing-table__features li' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'feature_gap',
            [
                'label' => esc_html__('Row Gap', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 40,
                    ],
                ],
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-pricing-table__features' => 'row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_button',
            [
                'label' => esc_html__('Button', 'nebula-forge-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .nfa-pricing-table__button',
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Padding', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '12',
                    'right' => '24',
                    'bottom' => '12',
                    'left' => '24',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-pricing-table__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_radius',
            [
                'label' => esc_html__('Border Radius', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => 999,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-pricing-table__button' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('button_style_tabs');

        $this->start_controls_tab(
            'button_tab_normal',
            [
                'label' => esc_html__('Normal', 'nebula-forge-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => esc_html__('Text Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .nfa-pricing-table__button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg',
            [
                'label' => esc_html__('Background', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ff6b35',
                'selectors' => [
                    '{{WRAPPER}} .nfa-pricing-table__button' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .nfa-pricing-table__button',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_shadow',
                'selector' => '{{WRAPPER}} .nfa-pricing-table__button',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_tab_hover',
            [
                'label' => esc_html__('Hover', 'nebula-forge-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'button_hover_color',
            [
                'label' => esc_html__('Text Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .nfa-pricing-table__button:hover, {{WRAPPER}} .nfa-pricing-table__button:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_bg',
            [
                'label' => esc_html__('Background', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .nfa-pricing-table__button:hover, {{WRAPPER}} .nfa-pricing-table__button:focus' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_hover_border',
                'selector' => '{{WRAPPER}} .nfa-pricing-table__button:hover, {{WRAPPER}} .nfa-pricing-table__button:focus',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_hover_shadow',
                'selector' => '{{WRAPPER}} .nfa-pricing-table__button:hover, {{WRAPPER}} .nfa-pricing-table__button:focus',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $features = isset($settings['features']) ? $settings['features'] : [];
        $button = isset($settings['button_url']) ? $settings['button_url'] : [];
        $highlighted = isset($settings['highlight']) && $settings['highlight'] === 'yes';

        $this->add_render_attribute('wrapper', 'class', 'nfa-pricing-table');
        if ($highlighted) {
            $this->add_render_attribute('wrapper', 'class', 'nfa-pricing-table--featured');
        }

        $this->add_render_attribute('button', 'class', 'nfa-pricing-table__button');
        if (!empty($button['url'])) {
            $this->add_render_attribute('button', 'href', esc_url($button['url']));
            if (!empty($button['is_external'])) {
                $this->add_render_attribute('button', 'target', '_blank');
                $this->add_render_attribute('button', 'rel', 'noopener');
                $this->add_render_attribute('button', 'rel', 'noreferrer');
            }
            if (!empty($button['nofollow'])) {
                $this->add_render_attribute('button', 'rel', 'nofollow');
            }
        } else {
            $this->add_render_attribute('button', 'href', '#');
        }
        ?>
        <div <?php echo $this->get_render_attribute_string('wrapper'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
            <?php if ($highlighted && !empty($settings['highlight_label'])) : ?>
                <span class="nfa-pricing-table__badge"><?php echo esc_html($settings['highlight_label']); ?></span>
            <?php endif; ?>
            <div class="nfa-pricing-table__header">
                <?php if (!empty($settings['plan_label'])) : ?>
                    <h3 class="nfa-pricing-table__label"><?php echo esc_html($settings['plan_label']); ?></h3>
                <?php endif; ?>
                <?php if (!empty($settings['plan_description'])) : ?>
                    <p class="nfa-pricing-table__description"><?php echo esc_html($settings['plan_description']); ?></p>
                <?php endif; ?>
            </div>

            <div class="nfa-pricing-table__price">
                <?php if (!empty($settings['price'])) : ?>
                    <span class="nfa-pricing-table__amount"><?php echo esc_html($settings['price']); ?></span>
                <?php endif; ?>
                <?php if (!empty($settings['price_suffix'])) : ?>
                    <span class="nfa-pricing-table__suffix"><?php echo esc_html($settings['price_suffix']); ?></span>
                <?php endif; ?>
            </div>

            <?php if (!empty($features)) : ?>
                <ul class="nfa-pricing-table__features">
                    <?php foreach ($features as $feature) : ?>
                        <?php if (!empty($feature['feature_text'])) : ?>
                            <li><?php echo esc_html($feature['feature_text']); ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if (!empty($settings['button_text'])) : ?>
                <a <?php echo $this->get_render_attribute_string('button'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                    <?php echo esc_html($settings['button_text']); ?>
                </a>
            <?php endif; ?>
        </div>
        <?php
    }
}
