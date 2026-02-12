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

class Logo_Grid_Widget extends Widget_Base
{
    public function get_name(): string
    {
        return 'nfa-logo-grid';
    }

    public function get_title(): string
    {
        return esc_html__('Logo Grid', 'nebula-forge-addons-for-elementor');
    }

    public function get_icon(): string
    {
        return 'eicon-logo';
    }

    public function get_categories(): array
    {
        return ['nebula-forge'];
    }

    public function get_keywords(): array
    {
        return ['logo', 'clients', 'brands', 'partners'];
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
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Content', 'nebula-forge-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => esc_html__('Layout', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid' => esc_html__('Grid', 'nebula-forge-addons-for-elementor'),
                    'slider' => esc_html__('Slider', 'nebula-forge-addons-for-elementor'),
                ],
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'logo_image',
            [
                'label' => esc_html__('Logo', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $repeater->add_control(
            'logo_name',
            [
                'label' => esc_html__('Brand Name', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Brand', 'nebula-forge-addons-for-elementor'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'logo_link',
            [
                'label' => esc_html__('Link', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );

        $this->add_control(
            'logos',
            [
                'label' => esc_html__('Logos', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'logo_name' => esc_html__('Lumen', 'nebula-forge-addons-for-elementor'),
                    ],
                    [
                        'logo_name' => esc_html__('Pulse', 'nebula-forge-addons-for-elementor'),
                    ],
                    [
                        'logo_name' => esc_html__('Vertex', 'nebula-forge-addons-for-elementor'),
                    ],
                    [
                        'logo_name' => esc_html__('Summit', 'nebula-forge-addons-for-elementor'),
                    ],
                ],
                'title_field' => '{{{ logo_name }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_logos',
            [
                'label' => esc_html__('Logos', 'nebula-forge-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'logo_columns',
            [
                'label' => esc_html__('Columns', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['col'],
                'range' => [
                    'col' => [
                        'min' => 2,
                        'max' => 6,
                    ],
                ],
                'default' => [
                    'size' => 4,
                    'unit' => 'col',
                ],
                'tablet_default' => [
                    'size' => 3,
                    'unit' => 'col',
                ],
                'mobile_default' => [
                    'size' => 2,
                    'unit' => 'col',
                ],
                'condition' => [
                    'layout' => 'grid',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-logo-grid__items' => 'grid-template-columns: repeat({{SIZE}}, minmax(0, 1fr));',
                ],
            ]
        );

        $this->add_responsive_control(
            'logo_gap',
            [
                'label' => esc_html__('Gap', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'default' => [
                    'size' => 24,
                    'unit' => 'px',
                ],
                'condition' => [
                    'layout' => 'grid',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-logo-grid__items' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'slider_arrows',
            [
                'label' => esc_html__('Show Arrows', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'layout' => 'slider',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_slides_per_view',
            [
                'label' => esc_html__('Slides Per View', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['col'],
                'range' => [
                    'col' => [
                        'min' => 2,
                        'max' => 6,
                    ],
                ],
                'default' => [
                    'size' => 4,
                    'unit' => 'col',
                ],
                'condition' => [
                    'layout' => 'slider',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_gap',
            [
                'label' => esc_html__('Slide Gap', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'default' => [
                    'size' => 24,
                    'unit' => 'px',
                ],
                'condition' => [
                    'layout' => 'slider',
                ],
            ]
        );

        $this->add_control(
            'logo_max_width',
            [
                'label' => esc_html__('Max Width', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 40,
                        'max' => 240,
                    ],
                ],
                'default' => [
                    'size' => 140,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-logo-grid__logo img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'logo_grayscale',
            [
                'label' => esc_html__('Grayscale Logos', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} .nfa-logo-grid__logo img' => 'filter: grayscale(100%); opacity: 0.75;',
                ],
            ]
        );

        $this->add_control(
            'logo_hover_opacity',
            [
                'label' => esc_html__('Hover Opacity', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    '%' => [
                        'min' => 30,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-logo-grid__logo:hover img' => 'opacity: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /* ── Style: Item Card ────────────────────────────────────────── */
        $this->start_controls_section(
            'section_style_item',
            [
                'label' => esc_html__('Item Card', 'nebula-forge-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'item_bg',
            [
                'label' => esc_html__('Background', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .nfa-logo-grid__logo' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => esc_html__('Padding', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .nfa-logo-grid__logo' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'item_border_radius',
            [
                'label' => esc_html__('Border Radius', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .nfa-logo-grid__logo' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .nfa-logo-grid__logo',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_shadow',
                'selector' => '{{WRAPPER}} .nfa-logo-grid__logo',
            ]
        );

        $this->end_controls_section();

        /* ── Style: Text ─────────────────────────────────────────────── */
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
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .nfa-logo-grid__text',
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => esc_html__('Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#131313',
                'selectors' => [
                    '{{WRAPPER}} .nfa-logo-grid__text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $logos = isset($settings['logos']) ? $settings['logos'] : [];
        $layout = isset($settings['layout']) ? $settings['layout'] : 'grid';
        $show_arrows = isset($settings['slider_arrows']) && $settings['slider_arrows'] === 'yes';
        $slides_per_view = isset($settings['slider_slides_per_view']['size']) ? (int) $settings['slider_slides_per_view']['size'] : 4;
        $slides_per_view = max(2, min(6, $slides_per_view));
        $slide_gap = isset($settings['slider_gap']['size']) ? (int) $settings['slider_gap']['size'] : 24;
        $slide_gap = max(0, $slide_gap);
        ?>
        <div class="nfa-logo-grid">
            <?php if (!empty($logos)) : ?>
                <?php if ($layout === 'slider') : ?>
                    <div class="nfa-logo-grid__slider nfa-slider" data-slider-per-view="<?php echo esc_attr((string) $slides_per_view); ?>" data-slider-gap="<?php echo esc_attr((string) $slide_gap); ?>">
                        <div class="nfa-slider__track">
                            <?php foreach ($logos as $logo) : ?>
                                <?php
                                $logo_name = isset($logo['logo_name']) ? $logo['logo_name'] : '';
                                $logo_url = isset($logo['logo_link']['url']) ? $logo['logo_link']['url'] : '';
                                $logo_target = isset($logo['logo_link']['is_external']) && $logo['logo_link']['is_external'] ? '_blank' : '';
                                $logo_rel = isset($logo['logo_link']['nofollow']) && $logo['logo_link']['nofollow'] ? 'nofollow noopener' : 'noopener';
                                ?>
                                <div class="nfa-slider__item">
                                    <div class="nfa-logo-grid__logo">
                                        <?php if (!empty($logo_url)) : ?>
                                            <a href="<?php echo esc_url($logo_url); ?>" target="<?php echo esc_attr($logo_target); ?>" rel="<?php echo esc_attr($logo_rel); ?>">
                                                <?php if (!empty($logo['logo_image']['url'])) : ?>
                                                    <img src="<?php echo esc_url($logo['logo_image']['url']); ?>" alt="<?php echo esc_attr($logo_name); ?>">
                                                <?php else : ?>
                                                    <span class="nfa-logo-grid__text"><?php echo esc_html($logo_name); ?></span>
                                                <?php endif; ?>
                                            </a>
                                        <?php else : ?>
                                            <?php if (!empty($logo['logo_image']['url'])) : ?>
                                                <img src="<?php echo esc_url($logo['logo_image']['url']); ?>" alt="<?php echo esc_attr($logo_name); ?>">
                                            <?php else : ?>
                                                <span class="nfa-logo-grid__text"><?php echo esc_html($logo_name); ?></span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if ($show_arrows) : ?>
                            <button class="nfa-slider__btn nfa-slider__btn--prev" type="button" aria-label="<?php echo esc_attr__('Previous slide', 'nebula-forge-addons-for-elementor'); ?>">
                                &#8592;
                            </button>
                            <button class="nfa-slider__btn nfa-slider__btn--next" type="button" aria-label="<?php echo esc_attr__('Next slide', 'nebula-forge-addons-for-elementor'); ?>">
                                &#8594;
                            </button>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <div class="nfa-logo-grid__items">
                        <?php foreach ($logos as $logo) : ?>
                            <?php
                            $logo_name = isset($logo['logo_name']) ? $logo['logo_name'] : '';
                            $logo_url = isset($logo['logo_link']['url']) ? $logo['logo_link']['url'] : '';
                            $logo_target = isset($logo['logo_link']['is_external']) && $logo['logo_link']['is_external'] ? '_blank' : '';
                            $logo_rel = isset($logo['logo_link']['nofollow']) && $logo['logo_link']['nofollow'] ? 'nofollow noopener' : 'noopener';
                            ?>
                            <div class="nfa-logo-grid__logo">
                                <?php if (!empty($logo_url)) : ?>
                                    <a href="<?php echo esc_url($logo_url); ?>" target="<?php echo esc_attr($logo_target); ?>" rel="<?php echo esc_attr($logo_rel); ?>">
                                        <?php if (!empty($logo['logo_image']['url'])) : ?>
                                            <img src="<?php echo esc_url($logo['logo_image']['url']); ?>" alt="<?php echo esc_attr($logo_name); ?>">
                                        <?php else : ?>
                                            <span class="nfa-logo-grid__text"><?php echo esc_html($logo_name); ?></span>
                                        <?php endif; ?>
                                    </a>
                                <?php else : ?>
                                    <?php if (!empty($logo['logo_image']['url'])) : ?>
                                        <img src="<?php echo esc_url($logo['logo_image']['url']); ?>" alt="<?php echo esc_attr($logo_name); ?>">
                                    <?php else : ?>
                                        <span class="nfa-logo-grid__text"><?php echo esc_html($logo_name); ?></span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php
    }
}
