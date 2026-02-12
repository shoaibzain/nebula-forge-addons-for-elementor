<?php
/**
 * Showcase Carousel Widget
 *
 * Image card carousel with badges, titles, descriptions, tags,
 * navigation arrows, dots, and autoplay.
 *
 * @package NebulaForgeAddon
 * @since   0.3.0
 */

namespace NebulaForgeAddon\Widgets;

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;

class Showcase_Carousel_Widget extends Widget_Base
{
    public function get_name(): string
    {
        return 'nfa-showcase-carousel';
    }

    public function get_title(): string
    {
        return esc_html__('Showcase Carousel', 'nebula-forge-addons-for-elementor');
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
        return ['carousel', 'slider', 'showcase', 'cards', 'gallery'];
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
        $this->register_content_controls();
        $this->register_carousel_controls();
        $this->register_style_card_controls();
        $this->register_style_content_controls();
        $this->register_style_navigation_controls();
    }

    /* ── Content ─────────────────────────────────────────────────────── */
    private function register_content_controls(): void
    {
        $this->start_controls_section('section_slides', [
            'label' => esc_html__('Slides', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $repeater = new Repeater();

        $repeater->add_control('image', [
            'label'   => esc_html__('Background Image', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => Utils::get_placeholder_image_src()],
        ]);

        $repeater->add_control('badge', [
            'label'       => esc_html__('Badge', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Premium', 'nebula-forge-addons-for-elementor'),
            'label_block' => false,
        ]);

        $repeater->add_control('title', [
            'label'       => esc_html__('Title', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Card Title', 'nebula-forge-addons-for-elementor'),
            'label_block' => true,
        ]);

        $repeater->add_control('description', [
            'label'   => esc_html__('Description', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => esc_html__('A short description for this card.', 'nebula-forge-addons-for-elementor'),
            'rows'    => 3,
        ]);

        $repeater->add_control('tags', [
            'label'       => esc_html__('Tags (comma-separated)', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Tag 1, Tag 2', 'nebula-forge-addons-for-elementor'),
            'label_block' => true,
        ]);

        $repeater->add_control('link', [
            'label'       => esc_html__('Link', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::URL,
            'placeholder' => esc_html__('https://your-link.com', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('slides', [
            'label'       => esc_html__('Slides', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [
                    'title'       => esc_html__('Emaar Properties', 'nebula-forge-addons-for-elementor'),
                    'badge'       => esc_html__('Since 1997', 'nebula-forge-addons-for-elementor'),
                    'description' => esc_html__('Master developer of Downtown Dubai and Dubai Hills Estate.', 'nebula-forge-addons-for-elementor'),
                    'tags'        => 'Downtown Dubai, Dubai Hills',
                ],
                [
                    'title'       => esc_html__('Sobha Realty', 'nebula-forge-addons-for-elementor'),
                    'badge'       => esc_html__('Premium', 'nebula-forge-addons-for-elementor'),
                    'description' => esc_html__('Premium finishes and superior building longevity.', 'nebula-forge-addons-for-elementor'),
                    'tags'        => 'Sobha Hartland, Luxury Villas',
                ],
                [
                    'title'       => esc_html__('DAMAC Properties', 'nebula-forge-addons-for-elementor'),
                    'badge'       => esc_html__('Branded Living', 'nebula-forge-addons-for-elementor'),
                    'description' => esc_html__('Branded luxury residences and master-planned communities.', 'nebula-forge-addons-for-elementor'),
                    'tags'        => 'DAMAC Lagoons, Branded Residences',
                ],
                [
                    'title'       => esc_html__('Binghatti Developers', 'nebula-forge-addons-for-elementor'),
                    'badge'       => esc_html__('Iconic Design', 'nebula-forge-addons-for-elementor'),
                    'description' => esc_html__('Rapid project delivery and iconic architectural designs.', 'nebula-forge-addons-for-elementor'),
                    'tags'        => 'Bugatti Residences, Mercedes-Benz',
                ],
                [
                    'title'       => esc_html__('Meraas', 'nebula-forge-addons-for-elementor'),
                    'badge'       => esc_html__('Lifestyle', 'nebula-forge-addons-for-elementor'),
                    'description' => esc_html__('Sophisticated lifestyle destinations integrated with retail and tourism.', 'nebula-forge-addons-for-elementor'),
                    'tags'        => 'City Walk, Bluewaters',
                ],
            ],
            'title_field' => '{{{ title }}}',
        ]);

        $this->end_controls_section();
    }

    /* ── Carousel Settings ───────────────────────────────────────────── */
    private function register_carousel_controls(): void
    {
        $this->start_controls_section('section_carousel', [
            'label' => esc_html__('Carousel Settings', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_CONTENT,
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

        $this->add_control('slide_gap', [
            'label'   => esc_html__('Gap (px)', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 0, 'max' => 60]],
            'default' => ['size' => 20, 'unit' => 'px'],
        ]);

        $this->add_control('show_arrows', [
            'label'        => esc_html__('Navigation Arrows', 'nebula-forge-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Show', 'nebula-forge-addons-for-elementor'),
            'label_off'    => esc_html__('Hide', 'nebula-forge-addons-for-elementor'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('show_dots', [
            'label'        => esc_html__('Pagination Dots', 'nebula-forge-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Show', 'nebula-forge-addons-for-elementor'),
            'label_off'    => esc_html__('Hide', 'nebula-forge-addons-for-elementor'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('autoplay', [
            'label'        => esc_html__('Autoplay', 'nebula-forge-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'nebula-forge-addons-for-elementor'),
            'label_off'    => esc_html__('No', 'nebula-forge-addons-for-elementor'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('autoplay_speed', [
            'label'     => esc_html__('Autoplay Speed (ms)', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::NUMBER,
            'default'   => 4000,
            'min'       => 1000,
            'max'       => 15000,
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

        $this->add_control('card_height', [
            'label'   => esc_html__('Card Height', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 200, 'max' => 700]],
            'default' => ['size' => 420, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-showcase__card' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('card_border_radius', [
            'label'   => esc_html__('Border Radius', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 0, 'max' => 60]],
            'default' => ['size' => 20, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-showcase__card' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('card_bg', [
            'label'     => esc_html__('Card Background', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .nfa-showcase__card' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .nfa-showcase__card',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_shadow',
                'selector' => '{{WRAPPER}} .nfa-showcase__card',
            ]
        );

        $this->add_control('overlay_gradient', [
            'label'     => esc_html__('Overlay Gradient', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'dark',
            'options'   => [
                'dark'  => esc_html__('Dark', 'nebula-forge-addons-for-elementor'),
                'light' => esc_html__('Light', 'nebula-forge-addons-for-elementor'),
                'none'  => esc_html__('None', 'nebula-forge-addons-for-elementor'),
            ],
        ]);

        $this->end_controls_section();
    }

    /* ── Style: Content ──────────────────────────────────────────────── */
    private function register_style_content_controls(): void
    {
        $this->start_controls_section('section_style_content', [
            'label' => esc_html__('Content', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('heading_badge_style', [
            'label'     => esc_html__('Badge', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::HEADING,
        ]);

        $this->add_control('badge_bg_color', [
            'label'     => esc_html__('Background', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(244,244,244,0.15)',
            'selectors' => [
                '{{WRAPPER}} .nfa-showcase__badge' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('badge_text_color', [
            'label'     => esc_html__('Text Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#F4F4F4',
            'selectors' => [
                '{{WRAPPER}} .nfa-showcase__badge' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'badge_typography',
            'selector' => '{{WRAPPER}} .nfa-showcase__badge',
        ]);

        $this->add_control('badge_border_radius', [
            'label'   => esc_html__('Badge Radius', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 0, 'max' => 30]],
            'selectors' => [
                '{{WRAPPER}} .nfa-showcase__badge' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('heading_title_style', [
            'label'     => esc_html__('Title', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('title_color', [
            'label'     => esc_html__('Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#F4F4F4',
            'selectors' => [
                '{{WRAPPER}} .nfa-showcase__title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'title_typography',
            'selector' => '{{WRAPPER}} .nfa-showcase__title',
        ]);

        $this->add_control('heading_desc_style', [
            'label'     => esc_html__('Description', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('desc_color', [
            'label'     => esc_html__('Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(244,244,244,0.65)',
            'selectors' => [
                '{{WRAPPER}} .nfa-showcase__desc' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'desc_typography',
            'selector' => '{{WRAPPER}} .nfa-showcase__desc',
        ]);

        $this->add_control('heading_tag_style', [
            'label'     => esc_html__('Tags', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('tag_text_color', [
            'label'     => esc_html__('Text Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(244,244,244,0.6)',
            'selectors' => [
                '{{WRAPPER}} .nfa-showcase__tag' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('tag_border_color', [
            'label'     => esc_html__('Border Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(244,244,244,0.12)',
            'selectors' => [
                '{{WRAPPER}} .nfa-showcase__tag' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'tag_typography',
            'selector' => '{{WRAPPER}} .nfa-showcase__tag',
        ]);

        $this->add_control('tag_border_radius', [
            'label'   => esc_html__('Tag Radius', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 0, 'max' => 30]],
            'selectors' => [
                '{{WRAPPER}} .nfa-showcase__tag' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
    }

    /* ── Style: Navigation ───────────────────────────────────────────── */
    private function register_style_navigation_controls(): void
    {
        $this->start_controls_section('section_style_nav', [
            'label' => esc_html__('Navigation', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('arrow_size', [
            'label'   => esc_html__('Arrow Button Size', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 28, 'max' => 64]],
            'default' => ['size' => 48, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-showcase__arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
            'condition' => ['show_arrows' => 'yes'],
        ]);

        $this->add_control('arrow_color', [
            'label'     => esc_html__('Arrow Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .nfa-showcase__arrow' => 'color: {{VALUE}};',
            ],
            'condition' => ['show_arrows' => 'yes'],
        ]);

        $this->add_control('arrow_border_color', [
            'label'     => esc_html__('Arrow Border Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(19,19,19,0.15)',
            'selectors' => [
                '{{WRAPPER}} .nfa-showcase__arrow' => 'border-color: {{VALUE}};',
            ],
            'condition' => ['show_arrows' => 'yes'],
        ]);

        $this->add_control('arrow_hover_bg', [
            'label'     => esc_html__('Arrow Hover Background', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#131313',
            'selectors' => [
                '{{WRAPPER}} .nfa-showcase__arrow:hover' => 'background: {{VALUE}};',
            ],
            'condition' => ['show_arrows' => 'yes'],
        ]);

        $this->add_control('arrow_hover_color', [
            'label'     => esc_html__('Arrow Hover Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#F4F4F4',
            'selectors' => [
                '{{WRAPPER}} .nfa-showcase__arrow:hover' => 'color: {{VALUE}}; border-color: {{VALUE}};',
            ],
            'condition' => ['show_arrows' => 'yes'],
        ]);

        $this->add_control('heading_dots_style', [
            'label'     => esc_html__('Dots', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => ['show_dots' => 'yes'],
        ]);

        $this->add_control('dots_color', [
            'label'     => esc_html__('Dot Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(19,19,19,0.2)',
            'selectors' => [
                '{{WRAPPER}} .nfa-showcase__dot' => 'background: {{VALUE}};',
            ],
            'condition' => ['show_dots' => 'yes'],
        ]);

        $this->add_control('dots_active_color', [
            'label'     => esc_html__('Active Dot Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#131313',
            'selectors' => [
                '{{WRAPPER}} .nfa-showcase__dot--active' => 'background: {{VALUE}};',
            ],
            'condition' => ['show_dots' => 'yes'],
        ]);

        $this->end_controls_section();
    }

    /* ------------------------------------------------------------------
     * Render
     * ----------------------------------------------------------------*/
    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $slides   = $settings['slides'] ?? [];
        if (empty($slides)) {
            return;
        }

        $per_view        = (int) ($settings['slides_per_view'] ?? 3);
        $gap             = (int) ($settings['slide_gap']['size'] ?? 20);
        $show_arrows     = $settings['show_arrows'] === 'yes';
        $show_dots       = $settings['show_dots'] === 'yes';
        $autoplay        = $settings['autoplay'] === 'yes';
        $autoplay_speed  = (int) ($settings['autoplay_speed'] ?? 4000);
        $pause_on_hover  = $settings['pause_on_hover'] === 'yes';
        $overlay_variant = $settings['overlay_gradient'] ?? 'dark';

        // Responsive per-view.
        $per_view_tablet = (int) ($settings['slides_per_view_tablet'] ?? min($per_view, 2));
        $per_view_mobile = (int) ($settings['slides_per_view_mobile'] ?? 1);
        ?>
        <div class="nfa-showcase"
             data-per-view="<?php echo esc_attr($per_view); ?>"
             data-per-view-tablet="<?php echo esc_attr($per_view_tablet); ?>"
             data-per-view-mobile="<?php echo esc_attr($per_view_mobile); ?>"
             data-gap="<?php echo esc_attr($gap); ?>"
             data-autoplay="<?php echo esc_attr($autoplay ? 'yes' : 'no'); ?>"
             data-autoplay-speed="<?php echo esc_attr($autoplay_speed); ?>"
             data-pause-on-hover="<?php echo esc_attr($pause_on_hover ? 'yes' : 'no'); ?>">

            <?php if ($show_arrows) : ?>
            <div class="nfa-showcase__nav">
                <button class="nfa-showcase__arrow nfa-showcase__arrow--prev" aria-label="<?php esc_attr_e('Previous', 'nebula-forge-addons-for-elementor'); ?>">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M10 12L6 8L10 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <button class="nfa-showcase__arrow nfa-showcase__arrow--next" aria-label="<?php esc_attr_e('Next', 'nebula-forge-addons-for-elementor'); ?>">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M6 4L10 8L6 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
            <?php endif; ?>

            <div class="nfa-showcase__viewport">
                <div class="nfa-showcase__track" style="gap:<?php echo esc_attr($gap); ?>px;">
                    <?php foreach ($slides as $index => $slide) :
                        $image_url = $slide['image']['url'] ?? '';
                        $link      = $slide['link'] ?? [];
                        $has_link  = !empty($link['url']);
                        $tag_el    = $has_link ? 'a' : 'div';
                        $tag_attr  = '';
                        if ($has_link) {
                            $tag_attr .= ' href="' . esc_url($link['url']) . '"';
                            if (!empty($link['is_external'])) {
                                $tag_attr .= ' target="_blank"';
                            }
                            if (!empty($link['nofollow'])) {
                                $tag_attr .= ' rel="nofollow"';
                            }
                        }
                    ?>
                    <<?php echo $tag_el; ?> class="nfa-showcase__card nfa-showcase__card--overlay-<?php echo esc_attr($overlay_variant); ?>"<?php echo $tag_attr; ?>>
                        <?php if ($image_url) : ?>
                        <div class="nfa-showcase__img">
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($slide['title'] ?? ''); ?>" loading="lazy">
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($slide['badge'])) : ?>
                        <span class="nfa-showcase__badge"><?php echo esc_html($slide['badge']); ?></span>
                        <?php endif; ?>

                        <div class="nfa-showcase__body">
                            <?php if (!empty($slide['title'])) : ?>
                            <div class="nfa-showcase__title"><?php echo esc_html($slide['title']); ?></div>
                            <?php endif; ?>

                            <?php if (!empty($slide['description'])) : ?>
                            <p class="nfa-showcase__desc"><?php echo esc_html($slide['description']); ?></p>
                            <?php endif; ?>

                            <?php
                            $tags_raw = $slide['tags'] ?? '';
                            $tags     = array_filter(array_map('trim', explode(',', $tags_raw)));
                            if (!empty($tags)) : ?>
                            <div class="nfa-showcase__tags">
                                <?php foreach ($tags as $tag) : ?>
                                <span class="nfa-showcase__tag"><?php echo esc_html($tag); ?></span>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </<?php echo $tag_el; ?>>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if ($show_dots) : ?>
            <div class="nfa-showcase__dots"></div>
            <?php endif; ?>
        </div>
        <?php
    }

    protected function content_template(): void
    {
        ?>
        <#
        var slides     = settings.slides || [];
        var perView    = parseInt( settings.slides_per_view ) || 3;
        var gap        = settings.slide_gap ? parseInt( settings.slide_gap.size ) : 20;
        var overlay    = settings.overlay_gradient || 'dark';
        var showArrows = settings.show_arrows === 'yes';
        var showDots   = settings.show_dots === 'yes';
        #>
        <div class="nfa-showcase"
             data-per-view="{{ perView }}"
             data-per-view-tablet="{{ parseInt( settings.slides_per_view_tablet ) || Math.min( perView, 2 ) }}"
             data-per-view-mobile="{{ parseInt( settings.slides_per_view_mobile ) || 1 }}"
             data-gap="{{ gap }}"
             data-autoplay="{{ settings.autoplay || 'no' }}"
             data-autoplay-speed="{{ settings.autoplay_speed || 4000 }}"
             data-pause-on-hover="{{ settings.pause_on_hover || 'yes' }}">

            <# if ( showArrows ) { #>
            <div class="nfa-showcase__nav">
                <button class="nfa-showcase__arrow nfa-showcase__arrow--prev" aria-label="Previous">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M10 12L6 8L10 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <button class="nfa-showcase__arrow nfa-showcase__arrow--next" aria-label="Next">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M6 4L10 8L6 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
            <# } #>

            <div class="nfa-showcase__viewport">
                <div class="nfa-showcase__track" style="gap:{{ gap }}px;">
                    <# _.each( slides, function( slide, index ) {
                        var imgUrl  = slide.image && slide.image.url ? slide.image.url : '';
                        var hasLink = slide.link && slide.link.url;
                        var tagEl   = hasLink ? 'a' : 'div';
                        var tagAttr = hasLink ? ' href="' + slide.link.url + '"' : '';
                        if ( hasLink && slide.link.is_external ) tagAttr += ' target="_blank"';
                        if ( hasLink && slide.link.nofollow ) tagAttr += ' rel="nofollow"';
                        var tags    = slide.tags ? slide.tags.split(',').map(function(t){ return t.trim(); }).filter(Boolean) : [];
                    #>
                    <{{{ tagEl }}} class="nfa-showcase__card nfa-showcase__card--overlay-{{ overlay }}"{{{ tagAttr }}}>
                        <# if ( imgUrl ) { #>
                        <div class="nfa-showcase__img">
                            <img src="{{ imgUrl }}" alt="{{ slide.title }}" loading="lazy">
                        </div>
                        <# } #>

                        <# if ( slide.badge ) { #>
                        <span class="nfa-showcase__badge">{{{ slide.badge }}}</span>
                        <# } #>

                        <div class="nfa-showcase__body">
                            <# if ( slide.title ) { #>
                            <div class="nfa-showcase__title">{{{ slide.title }}}</div>
                            <# } #>

                            <# if ( slide.description ) { #>
                            <p class="nfa-showcase__desc">{{{ slide.description }}}</p>
                            <# } #>

                            <# if ( tags.length ) { #>
                            <div class="nfa-showcase__tags">
                                <# _.each( tags, function( tag ) { #>
                                <span class="nfa-showcase__tag">{{{ tag }}}</span>
                                <# }); #>
                            </div>
                            <# } #>
                        </div>
                    </{{{ tagEl }}}>
                    <# }); #>
                </div>
            </div>

            <# if ( showDots ) { #>
            <div class="nfa-showcase__dots"></div>
            <# } #>
        </div>
        <?php
    }
}
