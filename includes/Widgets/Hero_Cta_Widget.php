<?php
/**
 * Hero CTA Widget
 *
 * Full-width hero banner with kicker text, headline, sub-copy,
 * and a prominent call-to-action button.
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
use Elementor\Widget_Base;

class Hero_Cta_Widget extends Widget_Base
{
    public function get_name(): string
    {
        return 'nfa-hero-cta';
    }

    public function get_title(): string
    {
        return esc_html__('Hero CTA', 'nebula-forge-addons-for-elementor');
    }

    public function get_icon(): string
    {
        return 'eicon-call-to-action';
    }

    public function get_categories(): array
    {
        return ['nebula-forge'];
    }

    public function get_keywords(): array
    {
        return ['hero', 'cta', 'call to action', 'banner'];
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
            'kicker',
            [
                'label' => esc_html__('Kicker', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('New Release', 'nebula-forge-addons-for-elementor'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'headline',
            [
                'label' => esc_html__('Headline', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Design expressive hero sections faster.', 'nebula-forge-addons-for-elementor'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'heading_tag',
            [
                'label' => esc_html__('Heading HTML Tag', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'p' => 'p',
                ],
            ]
        );

        $this->add_control(
            'body',
            [
                'label' => esc_html__('Body Copy', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('Pair bold typography with a focused call-to-action optimized for Elementor workflows.', 'nebula-forge-addons-for-elementor'),
                'rows' => 3,
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => esc_html__('Primary Button Label', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Explore Playbook', 'nebula-forge-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'button_url',
            [
                'label' => esc_html__('Primary Button Link', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_kicker',
            [
                'label' => esc_html__('Kicker', 'nebula-forge-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'kicker_color',
            [
                'label' => esc_html__('Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .nfa-hero-cta__kicker' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'kicker_typography',
                'selector' => '{{WRAPPER}} .nfa-hero-cta__kicker',
            ]
        );

        $this->add_responsive_control(
            'kicker_spacing',
            [
                'label' => esc_html__('Bottom Spacing', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 80,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-hero-cta__kicker' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_headline',
            [
                'label' => esc_html__('Headline', 'nebula-forge-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'headline_color',
            [
                'label' => esc_html__('Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .nfa-hero-cta__headline' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'headline_typography',
                'selector' => '{{WRAPPER}} .nfa-hero-cta__headline',
            ]
        );

        $this->add_responsive_control(
            'headline_spacing',
            [
                'label' => esc_html__('Bottom Spacing', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 120,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-hero-cta__headline' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_body',
            [
                'label' => esc_html__('Body', 'nebula-forge-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'body_color',
            [
                'label' => esc_html__('Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .nfa-hero-cta__body' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'body_typography',
                'selector' => '{{WRAPPER}} .nfa-hero-cta__body',
            ]
        );

        $this->add_responsive_control(
            'body_max_width',
            [
                'label' => esc_html__('Max Width', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 1400,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .nfa-hero-cta__body' => 'max-width: {{SIZE}}{{UNIT}};',
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
                'selector' => '{{WRAPPER}} .nfa-hero-cta__button',
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Padding', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '14',
                    'right' => '28',
                    'bottom' => '14',
                    'left' => '28',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-hero-cta__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .nfa-hero-cta__button' => 'border-radius: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .nfa-hero-cta__button' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .nfa-hero-cta__button' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .nfa-hero-cta__button',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_shadow',
                'selector' => '{{WRAPPER}} .nfa-hero-cta__button',
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
                    '{{WRAPPER}} .nfa-hero-cta__button:hover, {{WRAPPER}} .nfa-hero-cta__button:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_bg',
            [
                'label' => esc_html__('Background', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .nfa-hero-cta__button:hover, {{WRAPPER}} .nfa-hero-cta__button:focus' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_hover_border',
                'selector' => '{{WRAPPER}} .nfa-hero-cta__button:hover, {{WRAPPER}} .nfa-hero-cta__button:focus',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_hover_shadow',
                'selector' => '{{WRAPPER}} .nfa-hero-cta__button:hover, {{WRAPPER}} .nfa-hero-cta__button:focus',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $button = $settings['button_url'];

        $this->add_render_attribute('hero', 'class', 'nfa-hero-cta');

        $this->add_render_attribute('button', 'class', 'nfa-hero-cta__button');

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
            $this->add_render_attribute('button', 'role', 'button');
            $this->add_render_attribute('button', 'tabindex', '0');
        }
        ?>
            <section <?php echo $this->get_render_attribute_string('hero'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor renders sanitized attributes ?>>
            <?php if (!empty($settings['kicker'])) : ?>
                <p class="nfa-hero-cta__kicker"><?php echo esc_html($settings['kicker']); ?></p>
            <?php endif; ?>
            <?php
            $allowed_tags = ['h1','h2','h3','h4','h5','h6','div','p'];
            $heading_tag = in_array($settings['heading_tag'], $allowed_tags, true) ? $settings['heading_tag'] : 'h2';
            ?>
            <?php if (!empty($settings['headline'])) : ?>
                <<?php echo esc_html($heading_tag); ?> class="nfa-hero-cta__headline"><?php echo esc_html($settings['headline']); ?></<?php echo esc_html($heading_tag); ?>>
            <?php endif; ?>
            <?php if (!empty($settings['body'])) : ?>
                <p class="nfa-hero-cta__body"><?php echo esc_html($settings['body']); ?></p>
            <?php endif; ?>
                <a <?php echo $this->get_render_attribute_string('button'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor renders sanitized attributes ?>>
                <?php echo esc_html($settings['button_text']); ?>
            </a>
        </section>
        <?php
    }
}
