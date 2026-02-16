<?php
/**
 * CTA Banner Widget
 *
 * Conversion-focused call-to-action banner with gradient backgrounds,
 * dual buttons, icon support, and full style controls.
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
use Elementor\Icons_Manager;
use Elementor\Widget_Base;

class Cta_Banner_Widget extends Widget_Base
{
    public function get_name(): string
    {
        return 'nfa-cta-banner';
    }

    public function get_title(): string
    {
        return esc_html__('CTA Banner', 'nebula-forge-addons-for-elementor');
    }

    public function get_icon(): string
    {
        return 'eicon-banner';
    }

    public function get_categories(): array
    {
        return ['nebula-forge'];
    }

    public function get_keywords(): array
    {
        return ['cta', 'banner', 'call to action', 'promo', 'conversion'];
    }

    public function get_style_depends(): array
    {
        return ['nebula-forge-elementor-addon-frontend'];
    }

    protected function register_controls(): void
    {
        /* ── Content ───────────────────────────────────── */
        $this->start_controls_section('section_content', [
            'label' => esc_html__('Content', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('layout', [
            'label'   => esc_html__('Layout', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'centered',
            'options' => [
                'centered'  => esc_html__('Centered', 'nebula-forge-addons-for-elementor'),
                'left'      => esc_html__('Left Aligned', 'nebula-forge-addons-for-elementor'),
                'side'      => esc_html__('Side by Side', 'nebula-forge-addons-for-elementor'),
            ],
        ]);

        $this->add_control('icon', [
            'label'   => esc_html__('Icon', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fas fa-rocket', 'library' => 'fa-solid'],
        ]);

        $this->add_control('kicker', [
            'label'   => esc_html__('Kicker / Label', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::TEXT,
            'default' => esc_html__('LIMITED TIME OFFER', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('title', [
            'label'       => esc_html__('Title', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Ready to get started?', 'nebula-forge-addons-for-elementor'),
            'label_block' => true,
        ]);

        $this->add_control('heading_tag', [
            'label'   => esc_html__('Title HTML Tag', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'h2',
            'options' => [
                'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3',
                'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6',
                'div' => 'div', 'p' => 'p',
            ],
        ]);

        $this->add_control('description', [
            'label'   => esc_html__('Description', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => esc_html__('Join thousands of creators building beautiful pages with our Elementor widgets. Start your free trial today.', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->end_controls_section();

        /* ── Buttons ───────────────────────────────────── */
        $this->start_controls_section('section_buttons', [
            'label' => esc_html__('Buttons', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('primary_text', [
            'label'   => esc_html__('Primary Button', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::TEXT,
            'default' => esc_html__('Get Started Free', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('primary_link', [
            'label'       => esc_html__('Primary Link', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::URL,
            'placeholder' => 'https://example.com',
        ]);

        $this->add_control('secondary_text', [
            'label'   => esc_html__('Secondary Button', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::TEXT,
            'default' => esc_html__('Learn More', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('secondary_link', [
            'label'       => esc_html__('Secondary Link', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::URL,
            'placeholder' => 'https://example.com',
        ]);

        $this->end_controls_section();

        /* ── Style: Banner ─────────────────────────────── */
        $this->start_controls_section('section_style_banner', [
            'label' => esc_html__('Banner', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'banner_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .nfa-cta-banner',
            'fields_options' => [
                'background' => ['default' => 'gradient'],
                'color'      => ['default' => '#0ea5e9'],
                'color_b'    => ['default' => '#6366f1'],
                'gradient_angle' => ['default' => ['size' => 135, 'unit' => 'deg']],
            ],
        ]);

        $this->add_responsive_control('banner_padding', [
            'label'      => esc_html__('Padding', 'nebula-forge-addons-for-elementor'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => ['top' => '60', 'right' => '40', 'bottom' => '60', 'left' => '40', 'unit' => 'px', 'isLinked' => false],
            'selectors'  => ['{{WRAPPER}} .nfa-cta-banner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('banner_radius', [
            'label'     => esc_html__('Border Radius', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 80]],
            'default'   => ['size' => 24, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .nfa-cta-banner' => 'border-radius: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'     => 'banner_shadow',
            'selector' => '{{WRAPPER}} .nfa-cta-banner',
        ]);

        $this->end_controls_section();

        /* ── Style: Text ───────────────────────────────── */
        $this->start_controls_section('section_style_text', [
            'label' => esc_html__('Text', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('kicker_color', [
            'label'     => esc_html__('Kicker Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255,255,255,0.7)',
            'selectors' => ['{{WRAPPER}} .nfa-cta-banner__kicker' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'title_typography',
            'selector' => '{{WRAPPER}} .nfa-cta-banner__title',
        ]);

        $this->add_control('title_color', [
            'label'     => esc_html__('Title Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => ['{{WRAPPER}} .nfa-cta-banner__title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'desc_typography',
            'selector' => '{{WRAPPER}} .nfa-cta-banner__desc',
        ]);

        $this->add_control('desc_color', [
            'label'     => esc_html__('Description Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255,255,255,0.85)',
            'selectors' => ['{{WRAPPER}} .nfa-cta-banner__desc' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('icon_color', [
            'label'     => esc_html__('Icon Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(255,255,255,0.9)',
            'selectors' => ['{{WRAPPER}} .nfa-cta-banner__icon' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('icon_size', [
            'label'     => esc_html__('Icon Size', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 16, 'max' => 80]],
            'default'   => ['size' => 40, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .nfa-cta-banner__icon' => 'font-size: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        /* ── Style: Buttons ────────────────────────────── */
        $this->start_controls_section('section_style_buttons', [
            'label' => esc_html__('Buttons', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'btn_typography',
            'selector' => '{{WRAPPER}} .nfa-cta-banner__btn',
        ]);

        $this->add_control('primary_bg', [
            'label'     => esc_html__('Primary BG', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => ['{{WRAPPER}} .nfa-cta-banner__btn--primary' => 'background: {{VALUE}};'],
        ]);

        $this->add_control('primary_color', [
            'label'     => esc_html__('Primary Text', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#0ea5e9',
            'selectors' => ['{{WRAPPER}} .nfa-cta-banner__btn--primary' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('secondary_bg', [
            'label'     => esc_html__('Secondary BG', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'transparent',
            'selectors' => ['{{WRAPPER}} .nfa-cta-banner__btn--secondary' => 'background: {{VALUE}};'],
        ]);

        $this->add_control('secondary_color', [
            'label'     => esc_html__('Secondary Text', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => ['{{WRAPPER}} .nfa-cta-banner__btn--secondary' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('btn_radius', [
            'label'     => esc_html__('Border Radius', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 50]],
            'default'   => ['size' => 999, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .nfa-cta-banner__btn' => 'border-radius: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();
    }

    /**
     * Build link attributes string.
     */
    private function build_link_attrs(array $link): string
    {
        if (empty($link['url'])) {
            return '';
        }

        $attrs = ' href="' . esc_url($link['url']) . '"';

        $rel_parts = [];
        if (!empty($link['is_external'])) {
            $attrs .= ' target="_blank"';
            $rel_parts[] = 'noopener';
            $rel_parts[] = 'noreferrer';
        }
        if (!empty($link['nofollow'])) {
            $rel_parts[] = 'nofollow';
        }
        if ($rel_parts) {
            $attrs .= ' rel="' . esc_attr(implode(' ', $rel_parts)) . '"';
        }

        return $attrs;
    }

    protected function render(): void
    {
        $s       = $this->get_settings_for_display();
        $layout  = $s['layout'] ?? 'centered';
        $tag     = $s['heading_tag'] ?? 'h2';
        $p_link  = $s['primary_link'] ?? [];
        $s_link  = $s['secondary_link'] ?? [];
        $p_tag   = !empty($p_link['url']) ? 'a' : 'span';
        $s_tag   = !empty($s_link['url']) ? 'a' : 'span';
        $p_attrs = $this->build_link_attrs($p_link);
        $s_attrs = $this->build_link_attrs($s_link);
        ?>
        <div class="nfa-cta-banner nfa-cta-banner--<?php echo esc_attr($layout); ?>">
            <div class="nfa-cta-banner__content">
                <?php if (!empty($s['icon']['value'])) : ?>
                    <div class="nfa-cta-banner__icon">
                        <?php Icons_Manager::render_icon($s['icon'], ['aria-hidden' => 'true']); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($s['kicker'])) : ?>
                    <div class="nfa-cta-banner__kicker"><?php echo esc_html($s['kicker']); ?></div>
                <?php endif; ?>

                <?php if (!empty($s['title'])) : ?>
                    <<?php echo esc_attr($tag); ?> class="nfa-cta-banner__title"><?php echo esc_html($s['title']); ?></<?php echo esc_attr($tag); ?>>
                <?php endif; ?>

                <?php if (!empty($s['description'])) : ?>
                    <p class="nfa-cta-banner__desc"><?php echo esc_html($s['description']); ?></p>
                <?php endif; ?>

                <?php if (!empty($s['primary_text']) || !empty($s['secondary_text'])) : ?>
                    <div class="nfa-cta-banner__buttons">
                        <?php if (!empty($s['primary_text'])) : ?>
                            <<?php echo esc_attr($p_tag); ?> class="nfa-cta-banner__btn nfa-cta-banner__btn--primary"<?php echo $p_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                                <?php echo esc_html($s['primary_text']); ?>
                            </<?php echo esc_attr($p_tag); ?>>
                        <?php endif; ?>

                        <?php if (!empty($s['secondary_text'])) : ?>
                            <<?php echo esc_attr($s_tag); ?> class="nfa-cta-banner__btn nfa-cta-banner__btn--secondary"<?php echo $s_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                                <?php echo esc_html($s['secondary_text']); ?>
                            </<?php echo esc_attr($s_tag); ?>>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
