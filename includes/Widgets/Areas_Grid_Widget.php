<?php
/**
 * Areas Grid Widget
 *
 * Responsive grid of image cards with badges, titles, and descriptions.
 * The first card can optionally span two rows with a full-cover overlay.
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

class Areas_Grid_Widget extends Widget_Base
{
    public function get_name(): string
    {
        return 'nfa-areas-grid';
    }

    public function get_title(): string
    {
        return esc_html__('Areas Grid', 'nebula-forge-addons-for-elementor');
    }

    public function get_icon(): string
    {
        return 'eicon-gallery-grid';
    }

    public function get_categories(): array
    {
        return ['nebula-forge'];
    }

    public function get_keywords(): array
    {
        return ['areas', 'grid', 'cards', 'locations', 'gallery', 'image'];
    }

    public function get_style_depends(): array
    {
        return ['nebula-forge-elementor-addon-frontend'];
    }

    /* ------------------------------------------------------------------
     * Controls
     * ----------------------------------------------------------------*/
    protected function register_controls(): void
    {
        $this->register_content_controls();
        $this->register_grid_controls();
        $this->register_style_card_controls();
        $this->register_style_image_controls();
        $this->register_style_badge_controls();
        $this->register_style_content_controls();
        $this->register_style_featured_controls();
    }

    /* ── Content ─────────────────────────────────────────────────────── */
    private function register_content_controls(): void
    {
        $this->start_controls_section('section_cards', [
            'label' => esc_html__('Cards', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $repeater = new Repeater();

        $repeater->add_control('image', [
            'label'   => esc_html__('Image', 'nebula-forge-addons-for-elementor'),
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
            'default'     => esc_html__('Area Name', 'nebula-forge-addons-for-elementor'),
            'label_block' => true,
        ]);

        $repeater->add_control('description', [
            'label'   => esc_html__('Description', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => esc_html__('A short description about this area or location.', 'nebula-forge-addons-for-elementor'),
            'rows'    => 3,
        ]);

        $repeater->add_control('link', [
            'label'       => esc_html__('Link', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::URL,
            'placeholder' => esc_html__('https://your-link.com', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('cards', [
            'label'       => esc_html__('Cards', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [
                    'title'       => esc_html__('Downtown Dubai', 'nebula-forge-addons-for-elementor'),
                    'badge'       => esc_html__('Premium', 'nebula-forge-addons-for-elementor'),
                    'description' => esc_html__('The epicentre of luxury, home to the Burj Khalifa and high-demand short-term rentals.', 'nebula-forge-addons-for-elementor'),
                ],
                [
                    'title'       => esc_html__('Dubai Marina', 'nebula-forge-addons-for-elementor'),
                    'badge'       => esc_html__('Waterfront', 'nebula-forge-addons-for-elementor'),
                    'description' => esc_html__('A waterfront hub with consistently high occupancy rates and premium resale value.', 'nebula-forge-addons-for-elementor'),
                ],
                [
                    'title'       => esc_html__('Jumeirah Village Circle', 'nebula-forge-addons-for-elementor'),
                    'badge'       => esc_html__('High Yield', 'nebula-forge-addons-for-elementor'),
                    'description' => esc_html__('The top-performing area for affordable luxury with rental yields reaching up to 12%.', 'nebula-forge-addons-for-elementor'),
                ],
                [
                    'title'       => esc_html__('Dubai Hills Estate', 'nebula-forge-addons-for-elementor'),
                    'badge'       => esc_html__('Family', 'nebula-forge-addons-for-elementor'),
                    'description' => esc_html__('A family-focused master community providing stable long-term returns on villas.', 'nebula-forge-addons-for-elementor'),
                ],
                [
                    'title'       => esc_html__('Dubai Creek Harbour', 'nebula-forge-addons-for-elementor'),
                    'badge'       => esc_html__('Future Growth', 'nebula-forge-addons-for-elementor'),
                    'description' => esc_html__('Massive capital appreciation potential in the next five years.', 'nebula-forge-addons-for-elementor'),
                ],
            ],
            'title_field' => '{{{ title }}}',
        ]);

        $this->end_controls_section();
    }

    /* ── Grid Settings ───────────────────────────────────────────────── */
    private function register_grid_controls(): void
    {
        $this->start_controls_section('section_grid', [
            'label' => esc_html__('Grid Settings', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_responsive_control('columns', [
            'label'          => esc_html__('Columns', 'nebula-forge-addons-for-elementor'),
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
            'selectors' => [
                '{{WRAPPER}} .nfa-areas__grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
            ],
        ]);

        $this->add_control('grid_gap', [
            'label'   => esc_html__('Gap (px)', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 0, 'max' => 60]],
            'default' => ['size' => 24, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-areas__grid' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('featured_first', [
            'label'        => esc_html__('Feature First Card', 'nebula-forge-addons-for-elementor'),
            'description'  => esc_html__('First card spans two rows with a full-cover overlay.', 'nebula-forge-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'nebula-forge-addons-for-elementor'),
            'label_off'    => esc_html__('No', 'nebula-forge-addons-for-elementor'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('title_tag', [
            'label'   => esc_html__('Title HTML Tag', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'h4',
            'options' => [
                'h2'  => 'H2',
                'h3'  => 'H3',
                'h4'  => 'H4',
                'h5'  => 'H5',
                'h6'  => 'H6',
                'div' => 'div',
                'p'   => 'p',
            ],
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

        $this->add_control('card_bg', [
            'label'     => esc_html__('Background', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .nfa-areas__card' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('card_border_radius', [
            'label'      => esc_html__('Border Radius', 'nebula-forge-addons-for-elementor'),
            'type'       => Controls_Manager::SLIDER,
            'range'      => ['px' => ['min' => 0, 'max' => 40]],
            'default'    => ['size' => 18, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .nfa-areas__card' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('card_shadow', [
            'label'   => esc_html__('Box Shadow', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'small',
            'options' => [
                'none'   => esc_html__('None', 'nebula-forge-addons-for-elementor'),
                'small'  => esc_html__('Small', 'nebula-forge-addons-for-elementor'),
                'medium' => esc_html__('Medium', 'nebula-forge-addons-for-elementor'),
                'large'  => esc_html__('Large', 'nebula-forge-addons-for-elementor'),
            ],
        ]);

        $this->add_control('card_hover_lift', [
            'label'   => esc_html__('Hover Lift (px)', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 0, 'max' => 20]],
            'default' => ['size' => 6, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-areas__card:hover' => 'transform: translateY(-{{SIZE}}{{UNIT}});',
            ],
        ]);

        $this->end_controls_section();
    }

    /* ── Style: Image ────────────────────────────────────────────────── */
    private function register_style_image_controls(): void
    {
        $this->start_controls_section('section_style_image', [
            'label' => esc_html__('Image', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('image_height', [
            'label'   => esc_html__('Image Height', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 100, 'max' => 500]],
            'default' => ['size' => 200, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-areas__card:not(.nfa-areas__card--featured) .nfa-areas__img' => 'height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('image_zoom', [
            'label'        => esc_html__('Zoom on Hover', 'nebula-forge-addons-for-elementor'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'nebula-forge-addons-for-elementor'),
            'label_off'    => esc_html__('No', 'nebula-forge-addons-for-elementor'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('image_zoom_scale', [
            'label'     => esc_html__('Zoom Scale', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 1, 'max' => 1.5, 'step' => 0.01]],
            'default'   => ['size' => 1.06],
            'condition' => ['image_zoom' => 'yes'],
            'selectors' => [
                '{{WRAPPER}} .nfa-areas__card:hover .nfa-areas__img img' => 'transform: scale({{SIZE}});',
            ],
        ]);

        $this->end_controls_section();
    }

    /* ── Style: Badge ────────────────────────────────────────────────── */
    private function register_style_badge_controls(): void
    {
        $this->start_controls_section('section_style_badge', [
            'label' => esc_html__('Badge', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('badge_bg', [
            'label'     => esc_html__('Background Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#131313',
            'selectors' => [
                '{{WRAPPER}} .nfa-areas__badge' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('badge_color', [
            'label'     => esc_html__('Text Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#F4F4F4',
            'selectors' => [
                '{{WRAPPER}} .nfa-areas__badge' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'badge_typography',
            'selector' => '{{WRAPPER}} .nfa-areas__badge',
        ]);

        $this->add_control('badge_border_radius', [
            'label'     => esc_html__('Border Radius', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 50]],
            'default'   => ['size' => 50, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-areas__badge' => 'border-radius: {{SIZE}}{{UNIT}};',
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

        $this->add_control('heading_title_style', [
            'label'     => esc_html__('Title', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::HEADING,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'title_typography',
            'selector' => '{{WRAPPER}} .nfa-areas__title',
        ]);

        $this->add_control('title_color', [
            'label'     => esc_html__('Title Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#131313',
            'selectors' => [
                '{{WRAPPER}} .nfa-areas__card:not(.nfa-areas__card--featured) .nfa-areas__title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('heading_desc_style', [
            'label'     => esc_html__('Description', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'desc_typography',
            'selector' => '{{WRAPPER}} .nfa-areas__desc',
        ]);

        $this->add_control('desc_color', [
            'label'     => esc_html__('Description Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(19,19,19,0.55)',
            'selectors' => [
                '{{WRAPPER}} .nfa-areas__card:not(.nfa-areas__card--featured) .nfa-areas__desc' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('body_padding', [
            'label'      => esc_html__('Content Padding', 'nebula-forge-addons-for-elementor'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => [
                'top'    => '24',
                'right'  => '24',
                'bottom' => '24',
                'left'   => '24',
                'unit'   => 'px',
            ],
            'separator'  => 'before',
            'selectors'  => [
                '{{WRAPPER}} .nfa-areas__card:not(.nfa-areas__card--featured) .nfa-areas__body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
    }

    /* ── Style: Featured Card ────────────────────────────────────────── */
    private function register_style_featured_controls(): void
    {
        $this->start_controls_section('section_style_featured', [
            'label'     => esc_html__('Featured Card', 'nebula-forge-addons-for-elementor'),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => ['featured_first' => 'yes'],
        ]);

        $this->add_control('featured_overlay_start', [
            'label'   => esc_html__('Overlay Gradient Start', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::COLOR,
            'default' => 'rgba(19,19,19,0.9)',
        ]);

        $this->add_control('featured_overlay_mid', [
            'label'   => esc_html__('Overlay Gradient Mid', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::COLOR,
            'default' => 'rgba(19,19,19,0.4)',
        ]);

        $this->add_control('featured_overlay_end', [
            'label'   => esc_html__('Overlay Gradient End', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::COLOR,
            'default' => 'rgba(19,19,19,0.1)',
        ]);

        $this->add_control('featured_title_color', [
            'label'     => esc_html__('Title Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#F4F4F4',
            'separator' => 'before',
            'selectors' => [
                '{{WRAPPER}} .nfa-areas__card--featured .nfa-areas__title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('featured_title_size', [
            'label'     => esc_html__('Title Font Size', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 14, 'max' => 48]],
            'default'   => ['size' => 24, 'unit' => 'px'],
            'selectors' => [
                '{{WRAPPER}} .nfa-areas__card--featured .nfa-areas__title' => 'font-size: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('featured_desc_color', [
            'label'     => esc_html__('Description Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(244,244,244,0.65)',
            'selectors' => [
                '{{WRAPPER}} .nfa-areas__card--featured .nfa-areas__desc' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('featured_badge_bg', [
            'label'     => esc_html__('Badge Background', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(244,244,244,0.15)',
            'separator' => 'before',
            'selectors' => [
                '{{WRAPPER}} .nfa-areas__card--featured .nfa-areas__badge' => 'background: {{VALUE}}; backdrop-filter: blur(10px); border: 1px solid rgba(244,244,244,0.1);',
            ],
        ]);

        $this->add_control('featured_body_padding', [
            'label'      => esc_html__('Content Padding', 'nebula-forge-addons-for-elementor'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => [
                'top'    => '32',
                'right'  => '28',
                'bottom' => '32',
                'left'   => '28',
                'unit'   => 'px',
            ],
            'separator'  => 'before',
            'selectors'  => [
                '{{WRAPPER}} .nfa-areas__card--featured .nfa-areas__body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();
    }

    /* ------------------------------------------------------------------
     * Render
     * ----------------------------------------------------------------*/
    protected function render(): void
    {
        $settings     = $this->get_settings_for_display();
        $cards        = $settings['cards'] ?? [];
        $featured     = $settings['featured_first'] === 'yes';
        $tag          = $settings['title_tag'] ?? 'h4';
        $shadow_class = '';
        $shadow_val   = $settings['card_shadow'] ?? 'small';

        if ($shadow_val === 'small') {
            $shadow_class = ' nfa-areas__card--shadow-sm';
        } elseif ($shadow_val === 'medium') {
            $shadow_class = ' nfa-areas__card--shadow-md';
        } elseif ($shadow_val === 'large') {
            $shadow_class = ' nfa-areas__card--shadow-lg';
        }

        // Build overlay gradient CSS for featured card.
        $overlay_css = '';
        if ($featured) {
            $start = $settings['featured_overlay_start'] ?? 'rgba(19,19,19,0.9)';
            $mid   = $settings['featured_overlay_mid'] ?? 'rgba(19,19,19,0.4)';
            $end   = $settings['featured_overlay_end'] ?? 'rgba(19,19,19,0.1)';
            $overlay_css = 'background:linear-gradient(to top,' . esc_attr($start) . ' 0%,' . esc_attr($mid) . ' 45%,' . esc_attr($end) . ' 100%)';
        }

        if (empty($cards)) {
            return;
        }
        ?>
        <div class="nfa-areas">
            <div class="nfa-areas__grid<?php echo $featured ? ' nfa-areas__grid--featured' : ''; ?>">
                <?php foreach ($cards as $index => $card) :
                    $is_featured = $featured && $index === 0;
                    $card_class  = 'nfa-areas__card' . $shadow_class;
                    if ($is_featured) {
                        $card_class .= ' nfa-areas__card--featured';
                    }

                    $link    = $card['link'] ?? [];
                    $has_link = !empty($link['url']);
                    $link_tag = $has_link ? 'a' : 'div';
                    $link_attrs = '';
                    if ($has_link) {
                        $link_attrs .= ' href="' . esc_url($link['url']) . '"';
                        if (!empty($link['is_external'])) {
                            $link_attrs .= ' target="_blank"';
                        }
                        $rel_parts = [];
                        if (!empty($link['is_external'])) {
                            $rel_parts[] = 'noopener';
                            $rel_parts[] = 'noreferrer';
                        }
                        if (!empty($link['nofollow'])) {
                            $rel_parts[] = 'nofollow';
                        }
                        if ($rel_parts) {
                            $link_attrs .= ' rel="' . esc_attr(implode(' ', $rel_parts)) . '"';
                        }
                    }
                ?>
                <<?php echo esc_html($link_tag); ?> class="<?php echo esc_attr($card_class); ?>"<?php echo $link_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                    <div class="nfa-areas__img">
                        <?php if (!empty($card['image']['url'])) : ?>
                            <img src="<?php echo esc_url($card['image']['url']); ?>" alt="<?php echo esc_attr($card['title'] ?? ''); ?>" loading="lazy">
                        <?php endif; ?>
                        <?php if ($is_featured && $overlay_css) : ?>
                            <span class="nfa-areas__overlay" style="<?php echo esc_attr($overlay_css); ?>"></span>
                        <?php endif; ?>
                        <?php if (!empty($card['badge'])) : ?>
                            <span class="nfa-areas__badge"><?php echo esc_html($card['badge']); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="nfa-areas__body">
                        <?php if (!empty($card['title'])) : ?>
                            <<?php echo esc_attr($tag); ?> class="nfa-areas__title"><?php echo esc_html($card['title']); ?></<?php echo esc_attr($tag); ?>>
                        <?php endif; ?>
                        <?php if (!empty($card['description'])) : ?>
                            <p class="nfa-areas__desc"><?php echo esc_html($card['description']); ?></p>
                        <?php endif; ?>
                    </div>
                </<?php echo esc_html($link_tag); ?>>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    protected function content_template(): void
    {
        ?>
        <#
        var cards    = settings.cards || [];
        var featured = settings.featured_first === 'yes';
        var tag      = settings.title_tag || 'h4';
        var shadowCls = '';
        if (settings.card_shadow === 'small') shadowCls = ' nfa-areas__card--shadow-sm';
        else if (settings.card_shadow === 'medium') shadowCls = ' nfa-areas__card--shadow-md';
        else if (settings.card_shadow === 'large') shadowCls = ' nfa-areas__card--shadow-lg';

        var overlayCss = '';
        if (featured) {
            var s = settings.featured_overlay_start || 'rgba(19,19,19,0.9)';
            var m = settings.featured_overlay_mid   || 'rgba(19,19,19,0.4)';
            var e = settings.featured_overlay_end   || 'rgba(19,19,19,0.1)';
            overlayCss = 'background:linear-gradient(to top,' + s + ' 0%,' + m + ' 45%,' + e + ' 100%)';
        }

        if (cards.length === 0) return;
        #>
        <div class="nfa-areas">
            <div class="nfa-areas__grid<# if (featured) { #> nfa-areas__grid--featured<# } #>">
                <# _.each(cards, function(card, index) {
                    var isFeatured = featured && index === 0;
                    var cls = 'nfa-areas__card' + shadowCls;
                    if (isFeatured) cls += ' nfa-areas__card--featured';

                    var linkUrl = card.link ? card.link.url : '';
                    var linkTag = linkUrl ? 'a' : 'div';
                    var linkAttr = linkUrl ? ' href="' + linkUrl + '"' : '';
                #>
                <{{{ linkTag }}} class="{{ cls }}"{{{ linkAttr }}}>
                    <div class="nfa-areas__img">
                        <# if (card.image && card.image.url) { #>
                            <img src="{{ card.image.url }}" alt="{{ card.title }}" loading="lazy">
                        <# } #>
                        <# if (isFeatured && overlayCss) { #>
                            <span class="nfa-areas__overlay" style="{{ overlayCss }}"></span>
                        <# } #>
                        <# if (card.badge) { #>
                            <span class="nfa-areas__badge">{{ card.badge }}</span>
                        <# } #>
                    </div>
                    <div class="nfa-areas__body">
                        <# if (card.title) { #>
                            <{{{ tag }}} class="nfa-areas__title">{{ card.title }}</{{{ tag }}}>
                        <# } #>
                        <# if (card.description) { #>
                            <p class="nfa-areas__desc">{{ card.description }}</p>
                        <# } #>
                    </div>
                </{{{ linkTag }}}>
                <# }); #>
            </div>
        </div>
        <?php
    }
}
