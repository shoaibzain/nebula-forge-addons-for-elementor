<?php
/**
 * Testimonial Grid Widget
 *
 * Social-proof grid or slider with quotes, avatars, roles, and star ratings.
 *
 * @package NebulaForgeAddon
 * @since   0.1.4
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
use Elementor\Repeater;
use Elementor\Widget_Base;

class Testimonial_Grid_Widget extends Widget_Base
{
    public function get_name(): string
    {
        return 'nfa-testimonial-grid';
    }

    public function get_title(): string
    {
        return esc_html__('Testimonials Grid', 'nebula-forge-addons-for-elementor');
    }

    public function get_icon(): string
    {
        return 'eicon-testimonial-carousel';
    }

    public function get_categories(): array
    {
        return ['nebula-forge'];
    }

    public function get_keywords(): array
    {
        return ['testimonials', 'reviews', 'quotes', 'social proof'];
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
            'quote',
            [
                'label' => esc_html__('Quote', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('We shipped our landing page in a day and the layout feels premium.', 'nebula-forge-addons-for-elementor'),
                'rows' => 4,
            ]
        );

        $repeater->add_control(
            'name',
            [
                'label' => esc_html__('Name', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Jordan Lee', 'nebula-forge-addons-for-elementor'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'role',
            [
                'label' => esc_html__('Role', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Growth Lead, Atlas', 'nebula-forge-addons-for-elementor'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'avatar',
            [
                'label' => esc_html__('Avatar', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $repeater->add_control(
            'rating',
            [
                'label' => esc_html__('Rating (0-5)', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 5,
                'step' => 1,
                'default' => 5,
            ]
        );

        $this->add_control(
            'testimonials',
            [
                'label' => esc_html__('Testimonials', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'quote' => esc_html__('We shipped our landing page in a day and the layout feels premium.', 'nebula-forge-addons-for-elementor'),
                        'name' => esc_html__('Jordan Lee', 'nebula-forge-addons-for-elementor'),
                        'role' => esc_html__('Growth Lead, Atlas', 'nebula-forge-addons-for-elementor'),
                        'rating' => 5,
                    ],
                    [
                        'quote' => esc_html__('The widgets are clean, focused, and simple to customize.', 'nebula-forge-addons-for-elementor'),
                        'name' => esc_html__('Priya Shah', 'nebula-forge-addons-for-elementor'),
                        'role' => esc_html__('Marketing Manager, Nova', 'nebula-forge-addons-for-elementor'),
                        'rating' => 5,
                    ],
                    [
                        'quote' => esc_html__('Perfect for quick experiments and growth pages.', 'nebula-forge-addons-for-elementor'),
                        'name' => esc_html__('Alex Kim', 'nebula-forge-addons-for-elementor'),
                        'role' => esc_html__('Founder, Brightside', 'nebula-forge-addons-for-elementor'),
                        'rating' => 4,
                    ],
                ],
                'title_field' => '{{{ name }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_grid',
            [
                'label' => esc_html__('Grid', 'nebula-forge-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'grid_columns',
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
                    'size' => 3,
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
                'condition' => [
                    'layout' => 'grid',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-testimonials__grid' => 'grid-template-columns: repeat({{SIZE}}, minmax(0, 1fr));',
                ],
            ]
        );

        $this->add_responsive_control(
            'grid_gap',
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
                    'size' => 20,
                    'unit' => 'px',
                ],
                'condition' => [
                    'layout' => 'grid',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-testimonials__grid' => 'gap: {{SIZE}}{{UNIT}};',
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
                        'min' => 1,
                        'max' => 4,
                    ],
                ],
                'default' => [
                    'size' => 3,
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
                    'size' => 20,
                    'unit' => 'px',
                ],
                'condition' => [
                    'layout' => 'slider',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_card',
            [
                'label' => esc_html__('Card', 'nebula-forge-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'card_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .nfa-testimonials__card',
                'fields_options' => [
                    'color' => [
                        'default' => 'rgba(255, 255, 255, 0.02)',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => esc_html__('Padding', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '20',
                    'right' => '20',
                    'bottom' => '20',
                    'left' => '20',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-testimonials__card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'card_radius',
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
                    'size' => 18,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-testimonials__card' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_shadow',
                'selector' => '{{WRAPPER}} .nfa-testimonials__card',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .nfa-testimonials__card',
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
                'name' => 'quote_typography',
                'selector' => '{{WRAPPER}} .nfa-testimonials__quote',
            ]
        );

        $this->add_control(
            'quote_color',
            [
                'label' => esc_html__('Quote Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#131313',
                'selectors' => [
                    '{{WRAPPER}} .nfa-testimonials__quote' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'selector' => '{{WRAPPER}} .nfa-testimonials__name',
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => esc_html__('Name Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#131313',
                'selectors' => [
                    '{{WRAPPER}} .nfa-testimonials__name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'role_typography',
                'selector' => '{{WRAPPER}} .nfa-testimonials__role',
            ]
        );

        $this->add_control(
            'role_color',
            [
                'label' => esc_html__('Role Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(19,19,19,0.4)',
                'selectors' => [
                    '{{WRAPPER}} .nfa-testimonials__role' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'rating_color',
            [
                'label' => esc_html__('Rating Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fbbf24',
                'selectors' => [
                    '{{WRAPPER}} .nfa-testimonials__rating' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render a single testimonial card.
     *
     * @param array $testimonial Testimonial data.
     */
    private function render_testimonial_card(array $testimonial): void
    {
        ?>
        <div class="nfa-testimonials__card">
            <?php if (!empty($testimonial['avatar']['url'])) : ?>
                <div class="nfa-testimonials__avatar">
                    <img src="<?php echo esc_url($testimonial['avatar']['url']); ?>" alt="<?php echo esc_attr($testimonial['name'] ?? ''); ?>" loading="lazy" width="48" height="48">
                </div>
            <?php endif; ?>

            <?php if (!empty($testimonial['quote'])) : ?>
                <blockquote class="nfa-testimonials__quote"><?php echo esc_html($testimonial['quote']); ?></blockquote>
            <?php endif; ?>

            <div class="nfa-testimonials__footer">
                <div>
                    <?php if (!empty($testimonial['name'])) : ?>
                        <div class="nfa-testimonials__name"><?php echo esc_html($testimonial['name']); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($testimonial['role'])) : ?>
                        <div class="nfa-testimonials__role"><?php echo esc_html($testimonial['role']); ?></div>
                    <?php endif; ?>
                </div>
                <?php
                $rating = isset($testimonial['rating']) ? (int) $testimonial['rating'] : 0;
                $rating = max(0, min(5, $rating));
                ?>
                <?php if ($rating > 0) : ?>
                    <?php
                    // translators: %d: rating value.
                    $rating_label = sprintf(__('Rated %d out of 5', 'nebula-forge-addons-for-elementor'), $rating);
                    ?>
                    <div class="nfa-testimonials__rating" aria-label="<?php echo esc_attr($rating_label); ?>">
                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <?php if ($i <= $rating) : ?>
                                &#9733;
                            <?php else : ?>
                                &#9734;
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $testimonials = isset($settings['testimonials']) ? $settings['testimonials'] : [];
        $layout = isset($settings['layout']) ? $settings['layout'] : 'grid';
        $show_arrows = isset($settings['slider_arrows']) && $settings['slider_arrows'] === 'yes';
        $slides_per_view = isset($settings['slider_slides_per_view']['size']) ? (int) $settings['slider_slides_per_view']['size'] : 3;
        $slides_per_view = max(1, min(4, $slides_per_view));
        $slide_gap = isset($settings['slider_gap']['size']) ? (int) $settings['slider_gap']['size'] : 20;
        $slide_gap = max(0, $slide_gap);
        ?>
        <div class="nfa-testimonials">
            <?php if (!empty($testimonials)) : ?>
                <?php if ($layout === 'slider') : ?>
                    <div class="nfa-testimonials__slider nfa-slider" data-slider-per-view="<?php echo esc_attr((string) $slides_per_view); ?>" data-slider-gap="<?php echo esc_attr((string) $slide_gap); ?>">
                        <div class="nfa-slider__track">
                            <?php foreach ($testimonials as $testimonial) : ?>
                                <div class="nfa-slider__item">
                                    <?php $this->render_testimonial_card($testimonial); ?>
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
                    <div class="nfa-testimonials__grid">
                        <?php foreach ($testimonials as $testimonial) : ?>
                            <?php $this->render_testimonial_card($testimonial); ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php
    }
}
