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

class Faq_Accordion_Widget extends Widget_Base
{
    public function get_name(): string
    {
        return 'nfa-faq-accordion';
    }

    public function get_title(): string
    {
        return esc_html__('FAQ Accordion', 'nebula-forge-addons-for-elementor');
    }

    public function get_icon(): string
    {
        return 'eicon-accordion';
    }

    public function get_categories(): array
    {
        return ['nebula-forge'];
    }

    public function get_keywords(): array
    {
        return ['faq', 'accordion', 'questions', 'answers'];
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
            'heading',
            [
                'label' => esc_html__('Heading', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Frequently asked questions', 'nebula-forge-addons-for-elementor'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'heading_tag',
            [
                'label' => esc_html__('Heading HTML Tag', 'nebula-forge-addons-for-elementor'),
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
            'subheading',
            [
                'label' => esc_html__('Subheading', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('Answer the questions customers ask before they reach out.', 'nebula-forge-addons-for-elementor'),
                'rows' => 3,
            ]
        );

        $this->add_control(
            'open_first',
            [
                'label' => esc_html__('Open First Item', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'question',
            [
                'label' => esc_html__('Question', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Is Elementor Pro required?', 'nebula-forge-addons-for-elementor'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'answer',
            [
                'label' => esc_html__('Answer', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('No. All widgets are built for the free Elementor plugin.', 'nebula-forge-addons-for-elementor'),
                'rows' => 3,
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => esc_html__('FAQ Items', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'question' => esc_html__('Is Elementor Pro required?', 'nebula-forge-addons-for-elementor'),
                        'answer' => esc_html__('No. All widgets are built for the free Elementor plugin.', 'nebula-forge-addons-for-elementor'),
                    ],
                    [
                        'question' => esc_html__('Can I disable unused widgets?', 'nebula-forge-addons-for-elementor'),
                        'answer' => esc_html__('Yes. Use the Widget Settings screen in wp-admin to toggle widgets on or off.', 'nebula-forge-addons-for-elementor'),
                    ],
                    [
                        'question' => esc_html__('Will this slow down my site?', 'nebula-forge-addons-for-elementor'),
                        'answer' => esc_html__('Assets load only when the widgets are used, keeping pages fast.', 'nebula-forge-addons-for-elementor'),
                    ],
                ],
                'title_field' => '{{{ question }}}',
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
                'selector' => '{{WRAPPER}} .nfa-faq',
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
                    'top' => '48',
                    'right' => '48',
                    'bottom' => '48',
                    'left' => '48',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-faq' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .nfa-faq' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_shadow',
                'selector' => '{{WRAPPER}} .nfa-faq',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .nfa-faq',
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
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .nfa-faq__heading',
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => esc_html__('Heading Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f8fafc',
                'selectors' => [
                    '{{WRAPPER}} .nfa-faq__heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subheading_typography',
                'selector' => '{{WRAPPER}} .nfa-faq__subheading',
            ]
        );

        $this->add_control(
            'subheading_color',
            [
                'label' => esc_html__('Subheading Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#cbd5e1',
                'selectors' => [
                    '{{WRAPPER}} .nfa-faq__subheading' => 'color: {{VALUE}};',
                ],
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
                'label' => esc_html__('Item Gap', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 40,
                    ],
                ],
                'default' => [
                    'size' => 14,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-faq__items' => 'row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'item_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .nfa-faq__item',
                'fields_options' => [
                    'color' => [
                        'default' => 'rgba(255, 255, 255, 0.03)',
                    ],
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
                    'right' => '18',
                    'bottom' => '16',
                    'left' => '18',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-faq__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        'max' => 60,
                    ],
                ],
                'default' => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-faq__item' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .nfa-faq__item',
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
                'name' => 'question_typography',
                'selector' => '{{WRAPPER}} .nfa-faq__question',
            ]
        );

        $this->add_control(
            'question_color',
            [
                'label' => esc_html__('Question Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f8fafc',
                'selectors' => [
                    '{{WRAPPER}} .nfa-faq__question' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'answer_typography',
                'selector' => '{{WRAPPER}} .nfa-faq__answer',
            ]
        );

        $this->add_control(
            'answer_color',
            [
                'label' => esc_html__('Answer Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#cbd5e1',
                'selectors' => [
                    '{{WRAPPER}} .nfa-faq__answer' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $items = isset($settings['items']) ? $settings['items'] : [];
        $open_first = isset($settings['open_first']) && $settings['open_first'] === 'yes';
        ?>
        <div class="nfa-faq">
            <div class="nfa-faq__header">
                <?php if (!empty($settings['heading'])) : ?>
                    <<?php echo esc_attr($settings['heading_tag']); ?> class="nfa-faq__heading"><?php echo esc_html($settings['heading']); ?></<?php echo esc_attr($settings['heading_tag']); ?>>
                <?php endif; ?>
                <?php if (!empty($settings['subheading'])) : ?>
                    <p class="nfa-faq__subheading"><?php echo esc_html($settings['subheading']); ?></p>
                <?php endif; ?>
            </div>

            <?php if (!empty($items)) : ?>
                <div class="nfa-faq__items">
                    <?php foreach ($items as $index => $item) : ?>
                        <?php
                        $is_open = $open_first && $index === 0;
                        ?>
                        <details class="nfa-faq__item" <?php echo $is_open ? 'open' : ''; ?>>
                            <?php if (!empty($item['question'])) : ?>
                                <summary class="nfa-faq__question"><?php echo esc_html($item['question']); ?></summary>
                            <?php endif; ?>
                            <?php if (!empty($item['answer'])) : ?>
                                <div class="nfa-faq__answer">
                                    <?php echo esc_html($item['answer']); ?>
                                </div>
                            <?php endif; ?>
                        </details>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
