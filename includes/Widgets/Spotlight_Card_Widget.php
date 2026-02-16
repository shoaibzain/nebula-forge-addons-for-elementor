<?php
/**
 * Spotlight Card Widget
 *
 * Rich content card with eyebrow, title, body text, featured image,
 * and a call-to-action button.
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
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Widget_Base;

class Spotlight_Card_Widget extends Widget_Base
{
    public function get_name(): string
    {
        return 'nfa-spotlight-card';
    }

    public function get_title(): string
    {
        return esc_html__('Spotlight Card', 'nebula-forge-addons-for-elementor');
    }

    public function get_icon(): string
    {
        return 'eicon-media-carousel';
    }

    public function get_categories(): array
    {
        return ['nebula-forge'];
    }

    public function get_keywords(): array
    {
        return ['card', 'spotlight', 'feature', 'call to action'];
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
            'eyebrow',
            [
                'label' => esc_html__('Eyebrow', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Spotlight', 'nebula-forge-addons-for-elementor'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Ship polished pages faster.', 'nebula-forge-addons-for-elementor'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'heading_tag',
            [
                'label' => esc_html__('Title HTML Tag', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'h3',
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
            'description',
            [
                'label' => esc_html__('Description', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('Reusable patterns, bold visuals, and sensible defaults that keep teams moving.', 'nebula-forge-addons-for-elementor'),
                'rows' => 3,
            ]
        );

        $this->add_control(
            'meta',
            [
                'label' => esc_html__('Meta Text', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('No code handoff required', 'nebula-forge-addons-for-elementor'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => esc_html__('Button Text', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('See how it works', 'nebula-forge-addons-for-elementor'),
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

        $this->add_control(
            'image',
            [
                'label' => esc_html__('Image', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image',
                'default' => 'large',
                'separator' => 'none',
            ]
        );

        $this->end_controls_section();

        /* ── Style: Card ─────────────────────────────────────────────── */
        $this->start_controls_section(
            'section_style_card',
            [
                'label' => esc_html__('Card', 'nebula-forge-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_bg',
            [
                'label' => esc_html__('Background', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .nfa-spotlight-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => esc_html__('Padding', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .nfa-spotlight-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'card_border_radius',
            [
                'label' => esc_html__('Border Radius', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .nfa-spotlight-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .nfa-spotlight-card',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_shadow',
                'selector' => '{{WRAPPER}} .nfa-spotlight-card',
            ]
        );

        $this->end_controls_section();

        /* ── Style: Image ────────────────────────────────────────────── */
        $this->start_controls_section(
            'section_style_image',
            [
                'label' => esc_html__('Image', 'nebula-forge-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => esc_html__('Border Radius', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .nfa-spotlight-card__media img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow',
                'selector' => '{{WRAPPER}} .nfa-spotlight-card__media img',
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
                'name' => 'eyebrow_typography',
                'selector' => '{{WRAPPER}} .nfa-spotlight-card__eyebrow',
            ]
        );

        $this->add_control(
            'eyebrow_color',
            [
                'label' => esc_html__('Eyebrow Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#0ea5e9',
                'selectors' => [
                    '{{WRAPPER}} .nfa-spotlight-card__eyebrow' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .nfa-spotlight-card__title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#131313',
                'selectors' => [
                    '{{WRAPPER}} .nfa-spotlight-card__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .nfa-spotlight-card__description',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__('Description Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(19,19,19,0.55)',
                'selectors' => [
                    '{{WRAPPER}} .nfa-spotlight-card__description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typography',
                'selector' => '{{WRAPPER}} .nfa-spotlight-card__meta',
            ]
        );

        $this->add_control(
            'meta_color',
            [
                'label' => esc_html__('Meta Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(19,19,19,0.4)',
                'selectors' => [
                    '{{WRAPPER}} .nfa-spotlight-card__meta' => 'color: {{VALUE}};',
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
                'selector' => '{{WRAPPER}} .nfa-spotlight-card__button',
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
                    'right' => '24',
                    'bottom' => '14',
                    'left' => '24',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-spotlight-card__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_radius',
            [
                'label' => esc_html__('Radius', 'nebula-forge-addons-for-elementor'),
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
                    '{{WRAPPER}} .nfa-spotlight-card__button' => 'border-radius: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .nfa-spotlight-card__button' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .nfa-spotlight-card__button' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .nfa-spotlight-card__button',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_shadow',
                'selector' => '{{WRAPPER}} .nfa-spotlight-card__button',
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
                    '{{WRAPPER}} .nfa-spotlight-card__button:hover, {{WRAPPER}} .nfa-spotlight-card__button:focus' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_bg',
            [
                'label' => esc_html__('Background', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .nfa-spotlight-card__button:hover, {{WRAPPER}} .nfa-spotlight-card__button:focus' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'button_hover_border',
                'selector' => '{{WRAPPER}} .nfa-spotlight-card__button:hover, {{WRAPPER}} .nfa-spotlight-card__button:focus',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_hover_shadow',
                'selector' => '{{WRAPPER}} .nfa-spotlight-card__button:hover, {{WRAPPER}} .nfa-spotlight-card__button:focus',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $button = $settings['button_url'] ?? [];
        ?>
        <div class="nfa-spotlight-card">
            <div class="nfa-spotlight-card__content">
                <?php if (!empty($settings['eyebrow'])) : ?>
                    <p class="nfa-spotlight-card__eyebrow"><?php echo esc_html($settings['eyebrow']); ?></p>
                <?php endif; ?>

                <?php if (!empty($settings['title'])) : ?>
                    <<?php echo esc_attr($settings['heading_tag']); ?> class="nfa-spotlight-card__title"><?php echo esc_html($settings['title']); ?></<?php echo esc_attr($settings['heading_tag']); ?>>
                <?php endif; ?>

                <?php if (!empty($settings['description'])) : ?>
                    <p class="nfa-spotlight-card__description"><?php echo esc_html($settings['description']); ?></p>
                <?php endif; ?>

                <?php if (!empty($settings['meta'])) : ?>
                    <p class="nfa-spotlight-card__meta"><?php echo esc_html($settings['meta']); ?></p>
                <?php endif; ?>

                <?php if (!empty($settings['button_text'])) : ?>
                    <?php
                    $this->add_render_attribute('button', 'class', 'nfa-spotlight-card__button');
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
                    <a <?php echo $this->get_render_attribute_string('button'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                        <?php echo esc_html($settings['button_text']); ?>
                    </a>
                <?php endif; ?>
            </div>

            <?php if (!empty($settings['image']['id']) || !empty($settings['image']['url'])) : ?>
                <div class="nfa-spotlight-card__media">
                    <?php
                    $image_html = Group_Control_Image_Size::get_attachment_image_html($settings, 'image', 'image');
                    if ($image_html) {
                        echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
