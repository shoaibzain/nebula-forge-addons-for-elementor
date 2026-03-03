<?php
/**
 * Services Showcase Widget
 *
 * Split layout with a changing left image and interactive service cards.
 *
 * @package NebulaForgeAddon
 * @since   0.9.5
 */

namespace NebulaForgeAddon\Widgets;

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;

class Services_Showcase_Widget extends Widget_Base
{
    public function get_name(): string
    {
        return 'nfa-services-showcase';
    }

    public function get_title(): string
    {
        return esc_html__('Services Showcase', 'nebula-forge-addons-for-elementor');
    }

    public function get_icon(): string
    {
        return 'eicon-flip-box';
    }

    public function get_categories(): array
    {
        return ['nebula-forge'];
    }

    public function get_keywords(): array
    {
        return ['services', 'showcase', 'hover', 'cards', 'image swap'];
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
            'section_layout',
            [
                'label' => esc_html__('Layout', 'nebula-forge-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'section_min_height',
            [
                'label' => esc_html__('Minimum Height', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 420,
                        'max' => 1200,
                        'step' => 10,
                    ],
                ],
                'default' => [
                    'size' => 720,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-services-showcase' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'accent_color',
            [
                'label' => esc_html__('Accent Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#2146f3',
                'selectors' => [
                    '{{WRAPPER}} .nfa-services-showcase' => '--nfa-services-accent: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => esc_html__('Background Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f2f2f2',
                'selectors' => [
                    '{{WRAPPER}} .nfa-services-showcase' => '--nfa-services-surface: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_items',
            [
                'label' => esc_html__('Items', 'nebula-forge-addons-for-elementor'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 2,
                'default' => esc_html__('Lorem ipsum dolor', 'nebula-forge-addons-for-elementor'),
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label' => esc_html__('Description', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 4,
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.', 'nebula-forge-addons-for-elementor'),
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label' => esc_html__('Left Image', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'show_external' => true,
            ]
        );

        $repeater->add_control(
            'link_mode',
            [
                'label' => esc_html__('Link Mode', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'box',
                'options' => [
                    'box' => esc_html__('Full Box Link', 'nebula-forge-addons-for-elementor'),
                    'arrow' => esc_html__('Arrow Link Only', 'nebula-forge-addons-for-elementor'),
                    'title' => esc_html__('Title Link Only', 'nebula-forge-addons-for-elementor'),
                ],
            ]
        );

        $repeater->add_control(
            'highlighted',
            [
                'label' => esc_html__('Highlighted By Default', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => esc_html__('Service Cards', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'default' => [
                    [
                        'title' => esc_html__('Lorem ipsum dolor', 'nebula-forge-addons-for-elementor'),
                        'description' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.', 'nebula-forge-addons-for-elementor'),
                        'link_mode' => 'title',
                    ],
                    [
                        'title' => esc_html__('Sit amet elit', 'nebula-forge-addons-for-elementor'),
                        'description' => esc_html__('Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi.', 'nebula-forge-addons-for-elementor'),
                        'link_mode' => 'box',
                        'highlighted' => 'yes',
                    ],
                    [
                        'title' => esc_html__('Consectetur adipiscing', 'nebula-forge-addons-for-elementor'),
                        'description' => esc_html__('Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.', 'nebula-forge-addons-for-elementor'),
                        'link_mode' => 'arrow',
                    ],
                    [
                        'title' => esc_html__('Eiusmod tempor', 'nebula-forge-addons-for-elementor'),
                        'description' => esc_html__('Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia.', 'nebula-forge-addons-for-elementor'),
                        'link_mode' => 'title',
                    ],
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

        $this->add_responsive_control(
            'card_padding',
            [
                'label' => esc_html__('Box Padding', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => 42,
                    'right' => 30,
                    'bottom' => 28,
                    'left' => 30,
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-services-showcase__card-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'copy_top_offset',
            [
                'label' => esc_html__('Content Top Offset', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 220,
                    ],
                ],
                'default' => [
                    'size' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-services-showcase__copy' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'copy_bottom_offset',
            [
                'label' => esc_html__('Content Bottom Offset', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 220,
                    ],
                ],
                'default' => [
                    'size' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-services-showcase__copy' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'card_text_color',
            [
                'label' => esc_html__('Text Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#171717',
                'selectors' => [
                    '{{WRAPPER}} .nfa-services-showcase' => '--nfa-services-text: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'card_active_text_color',
            [
                'label' => esc_html__('Active Text Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .nfa-services-showcase' => '--nfa-services-active-text: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => esc_html__('Title', 'nebula-forge-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .nfa-services-showcase__title',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_content_position',
            [
                'label' => esc_html__('Content Position', 'nebula-forge-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_vertical_align',
            [
                'label' => esc_html__('Vertical Align', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'flex-end',
                'options' => [
                    'flex-start' => esc_html__('Top', 'nebula-forge-addons-for-elementor'),
                    'center' => esc_html__('Center', 'nebula-forge-addons-for-elementor'),
                    'flex-end' => esc_html__('Bottom', 'nebula-forge-addons-for-elementor'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-services-showcase__card-inner' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_max_width',
            [
                'label' => esc_html__('Text Width', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 180,
                        'max' => 700,
                    ],
                    '%' => [
                        'min' => 30,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 460,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-services-showcase__copy' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_description_spacing',
            [
                'label' => esc_html__('Title / Description Gap', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
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
                    '{{WRAPPER}} .nfa-services-showcase__description' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_description',
            [
                'label' => esc_html__('Description', 'nebula-forge-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .nfa-services-showcase__description',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__('Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(23, 23, 23, 0.85)',
                'selectors' => [
                    '{{WRAPPER}} .nfa-services-showcase' => '--nfa-services-description: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'description_active_color',
            [
                'label' => esc_html__('Active Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(255, 255, 255, 0.94)',
                'selectors' => [
                    '{{WRAPPER}} .nfa-services-showcase' => '--nfa-services-description-active: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_arrow',
            [
                'label' => esc_html__('Top Icon', 'nebula-forge-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'top_icon_type',
            [
                'label' => esc_html__('Icon Type', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'arrow',
                'options' => [
                    'none' => esc_html__('Hide', 'nebula-forge-addons-for-elementor'),
                    'arrow' => esc_html__('Arrow', 'nebula-forge-addons-for-elementor'),
                    'image' => esc_html__('Image Icon', 'nebula-forge-addons-for-elementor'),
                ],
            ]
        );

        $this->add_control(
            'top_icon_image',
            [
                'label' => esc_html__('Icon Image', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::MEDIA,
                'condition' => [
                    'top_icon_type' => 'image',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'top_icon_image',
                'default' => 'full',
                'separator' => 'none',
                'condition' => [
                    'top_icon_type' => 'image',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_box_size',
            [
                'label' => esc_html__('Icon Box Size', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 24,
                        'max' => 120,
                    ],
                ],
                'default' => [
                    'size' => 54,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-services-showcase' => '--nfa-services-arrow-box: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_icon_size',
            [
                'label' => esc_html__('Arrow Size', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 12,
                        'max' => 64,
                    ],
                ],
                'default' => [
                    'size' => 28,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-services-showcase' => '--nfa-services-arrow-icon: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_image_width',
            [
                'label' => esc_html__('Image Width', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 12,
                        'max' => 200,
                    ],
                    '%' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-services-showcase' => '--nfa-services-icon-image-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'top_icon_type' => 'image',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_top_offset',
            [
                'label' => esc_html__('Top Offset', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 18,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-services-showcase' => '--nfa-services-arrow-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'arrow_right_offset',
            [
                'label' => esc_html__('Right Offset', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 18,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-services-showcase' => '--nfa-services-arrow-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => esc_html__('Arrow Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#171717',
                'selectors' => [
                    '{{WRAPPER}} .nfa-services-showcase' => '--nfa-services-arrow-color: {{VALUE}};',
                ],
                'condition' => [
                    'top_icon_type' => 'arrow',
                ],
            ]
        );

        $this->add_control(
            'arrow_active_color',
            [
                'label' => esc_html__('Active Arrow Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .nfa-services-showcase' => '--nfa-services-arrow-active: {{VALUE}};',
                ],
                'condition' => [
                    'top_icon_type' => 'arrow',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $items = $settings['items'] ?? [];
        $top_icon_type = isset($settings['top_icon_type']) ? (string) $settings['top_icon_type'] : 'arrow';

        if (empty($items) || !is_array($items)) {
            return;
        }

        $active_index = 0;

        foreach ($items as $index => $item) {
            if (($item['highlighted'] ?? '') === 'yes') {
                $active_index = (int) $index;
                break;
            }
        }

        ?>
        <section class="nfa-services-showcase" data-active-index="<?php echo esc_attr((string) $active_index); ?>">
            <div class="nfa-services-showcase__media">
                <?php foreach ($items as $index => $item) : ?>
                    <?php
                    $media_class = 'nfa-services-showcase__media-item';
                    if ($index === $active_index) {
                        $media_class .= ' is-active';
                    }
                    $image_url = '';
                    if (!empty($item['image']['url'])) {
                        $image_url = (string) $item['image']['url'];
                    }
                    ?>
                    <div class="<?php echo esc_attr($media_class); ?>" data-service-index="<?php echo esc_attr((string) $index); ?>">
                        <?php if ($image_url !== '') : ?>
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr(wp_strip_all_tags($item['title'] ?? '')); ?>">
                        <?php else : ?>
                            <div class="nfa-services-showcase__media-placeholder"></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="nfa-services-showcase__cards">
                <?php foreach ($items as $index => $item) : ?>
                    <?php
                    $title = isset($item['title']) ? (string) $item['title'] : '';
                    $description = isset($item['description']) ? (string) $item['description'] : '';
                    $link = is_array($item['link'] ?? null) ? $item['link'] : [];
                    $link_url = isset($link['url']) ? (string) $link['url'] : '';
                    $link_mode = isset($item['link_mode']) ? (string) $item['link_mode'] : 'box';
                    $is_active = $index === $active_index;
                    $card_class = 'nfa-services-showcase__card';
                    $title_markup = wp_kses($title, ['br' => []]);

                    if ($is_active) {
                        $card_class .= ' is-active';
                    }

                    if (($item['highlighted'] ?? '') === 'yes') {
                        $card_class .= ' is-highlighted';
                    }

                    $link_attrs = '';
                    if ($link_url !== '') {
                        if (!empty($link['is_external'])) {
                            $link_attrs .= ' target="_blank" rel="noopener noreferrer"';
                        }

                        if (!empty($link['nofollow'])) {
                            if (strpos($link_attrs, 'rel="') !== false) {
                                $link_attrs = str_replace('rel="', 'rel="nofollow ', $link_attrs);
                            } else {
                                $link_attrs .= ' rel="nofollow"';
                            }
                        }
                    }
                    ?>
                    <article class="<?php echo esc_attr($card_class); ?>" data-service-index="<?php echo esc_attr((string) $index); ?>">
                        <?php if ($link_url !== '' && $link_mode === 'box') : ?>
                            <a class="nfa-services-showcase__card-link" href="<?php echo esc_url($link_url); ?>"<?php echo $link_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                        <?php endif; ?>

                        <div class="nfa-services-showcase__card-inner">
                            <div class="nfa-services-showcase__copy">
                                <?php if ($title !== '') : ?>
                                    <h3 class="nfa-services-showcase__title">
                                        <?php if ($link_url !== '' && $link_mode === 'title') : ?>
                                            <a href="<?php echo esc_url($link_url); ?>"<?php echo $link_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo $title_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
                                        <?php else : ?>
                                            <?php echo $title_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                        <?php endif; ?>
                                    </h3>
                                <?php endif; ?>

                                <?php if ($description !== '') : ?>
                                    <p class="nfa-services-showcase__description"><?php echo esc_html($description); ?></p>
                                <?php endif; ?>
                            </div>

                            <?php if ($top_icon_type !== 'none') : ?>
                                <div class="nfa-services-showcase__meta">
                                    <?php if ($link_url !== '' && $link_mode === 'arrow') : ?>
                                        <a class="nfa-services-showcase__arrow" href="<?php echo esc_url($link_url); ?>"<?php echo $link_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> aria-label="<?php echo esc_attr($title); ?>">
                                            <?php $this->render_top_icon($top_icon_type, $settings); ?>
                                        </a>
                                    <?php else : ?>
                                        <span class="nfa-services-showcase__arrow" aria-hidden="true">
                                            <?php $this->render_top_icon($top_icon_type, $settings); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if ($link_url !== '' && $link_mode === 'box') : ?>
                            </a>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
    }

    private function render_top_icon(string $top_icon_type, array $settings): void
    {
        if ($top_icon_type === 'image') {
            $image_html = Group_Control_Image_Size::get_attachment_image_html($settings, 'top_icon_image', 'top_icon_image');

            if ($image_html) {
                echo '<span class="nfa-services-showcase__icon-image">' . $image_html . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                return;
            }
        }

        echo '<span class="nfa-services-showcase__arrow-icon" aria-hidden="true"><svg viewBox="0 0 28 28" focusable="false"><path d="M8 20L20 8M12 8H20V16" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="square" stroke-linejoin="miter"></path></svg></span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}
