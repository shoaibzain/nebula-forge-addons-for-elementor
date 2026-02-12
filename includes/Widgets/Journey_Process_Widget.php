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
use function esc_attr;
use function esc_html;
use function esc_html__;

class Journey_Process_Widget extends Widget_Base
{
    public function get_name(): string
    {
        return 'nfa-journey-process';
    }

    public function get_title(): string
    {
        return esc_html__('Journey Process', 'nebula-forge-addons-for-elementor');
    }

    public function get_icon(): string
    {
        return 'eicon-flow';
    }

    public function get_categories(): array
    {
        return ['nebula-forge'];
    }

    public function get_keywords(): array
    {
        return ['journey', 'process', 'steps', 'investment'];
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
            'section_label',
            [
                'label' => esc_html__('Section Label', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Our Process', 'nebula-forge-addons-for-elementor'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => esc_html__('Heading', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Your End-to-End Property Investment Journey', 'nebula-forge-addons-for-elementor'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => esc_html__('Description', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('We eliminate the complexities of international property acquisition. Our advisors manage every phase to ensure your capital is deployed effectively.', 'nebula-forge-addons-for-elementor'),
                'rows' => 4,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'step_number',
            [
                'label' => esc_html__('Step Number', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('01', 'nebula-forge-addons-for-elementor'),
            ]
        );

        $repeater->add_control(
            'step_title',
            [
                'label' => esc_html__('Step Title', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Portfolio Selection', 'nebula-forge-addons-for-elementor'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'step_description',
            [
                'label' => esc_html__('Step Description', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('We analyse market data to identify properties that match your yield and appreciation goals.', 'nebula-forge-addons-for-elementor'),
                'rows' => 3,
            ]
        );

        $this->add_control(
            'steps',
            [
                'label' => esc_html__('Steps', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'step_number' => '01',
                        'step_title' => esc_html__('Portfolio Selection', 'nebula-forge-addons-for-elementor'),
                        'step_description' => esc_html__('We analyse market data to identify properties that match your yield and appreciation goals.', 'nebula-forge-addons-for-elementor'),
                    ],
                    [
                        'step_number' => '02',
                        'step_title' => esc_html__('Legal Compliance', 'nebula-forge-addons-for-elementor'),
                        'step_description' => esc_html__('Our team manages the SPA and ensures all DLD registrations are completed accurately.', 'nebula-forge-addons-for-elementor'),
                    ],
                    [
                        'step_number' => '03',
                        'step_title' => esc_html__('Financial Strategy', 'nebula-forge-addons-for-elementor'),
                        'step_description' => esc_html__('We advise on payment plans and structure your financial outlay efficiently.', 'nebula-forge-addons-for-elementor'),
                    ],
                    [
                        'step_number' => '04',
                        'step_title' => esc_html__('Residency', 'nebula-forge-addons-for-elementor'),
                        'step_description' => esc_html__('We guide you through the Golden Visa application, securing long-term UAE residency.', 'nebula-forge-addons-for-elementor'),
                    ],
                    [
                        'step_number' => '05',
                        'step_title' => esc_html__('Asset Management', 'nebula-forge-addons-for-elementor'),
                        'step_description' => esc_html__('Tenant procurement and maintenance to ensure passive income, fully managed.', 'nebula-forge-addons-for-elementor'),
                    ],
                ],
                'title_field' => '{{{ step_number }}} - {{{ step_title }}}',
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
                'selector' => '{{WRAPPER}} .nfa-journey',
                'fields_options' => [
                    'color' => [
                        'default' => '#f4f4f4',
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
                    'right' => '32',
                    'bottom' => '48',
                    'left' => '32',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-journey' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        'max' => 120,
                    ],
                ],
                'default' => [
                    'size' => 0,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-journey' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .nfa-journey',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_shadow',
                'selector' => '{{WRAPPER}} .nfa-journey',
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

        $this->add_control(
            'header_alignment',
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
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .nfa-journey__header' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_spacing',
            [
                'label' => esc_html__('Bottom Spacing', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 180,
                    ],
                ],
                'default' => [
                    'size' => 70,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-journey__header' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .nfa-journey__label',
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => esc_html__('Label Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(19, 19, 19, 0.45)',
                'selectors' => [
                    '{{WRAPPER}} .nfa-journey__label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'selector' => '{{WRAPPER}} .nfa-journey__heading',
            ]
        );

        $this->add_control(
            'heading_color',
            [
                'label' => esc_html__('Heading Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#131313',
                'selectors' => [
                    '{{WRAPPER}} .nfa-journey__heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .nfa-journey__description',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__('Description Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(19, 19, 19, 0.65)',
                'selectors' => [
                    '{{WRAPPER}} .nfa-journey__description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_steps',
            [
                'label' => esc_html__('Steps Grid', 'nebula-forge-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'steps_columns',
            [
                'label' => esc_html__('Columns', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['col'],
                'range' => [
                    'col' => [
                        'min' => 1,
                        'max' => 6,
                    ],
                ],
                'default' => [
                    'size' => 5,
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
                'selectors' => [
                    '{{WRAPPER}} .nfa-journey__steps' => 'grid-template-columns: repeat({{SIZE}}, minmax(0, 1fr));',
                ],
            ]
        );

        $this->add_responsive_control(
            'steps_gap',
            [
                'label' => esc_html__('Gap', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 24,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-journey__steps' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'connector_line',
            [
                'label' => esc_html__('Connector Line', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'nebula-forge-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'nebula-forge-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'connector_color',
            [
                'label' => esc_html__('Connector Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#131313',
                'condition' => [
                    'connector_line' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-journey__steps--line::before' => 'background: linear-gradient(90deg, {{VALUE}}, rgba(19,19,19,0.1));',
                ],
            ]
        );

        $this->add_responsive_control(
            'connector_height',
            [
                'label' => esc_html__('Connector Height', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 12,
                    ],
                ],
                'default' => [
                    'size' => 2,
                    'unit' => 'px',
                ],
                'condition' => [
                    'connector_line' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-journey__steps--line::before' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'connector_offset',
            [
                'label' => esc_html__('Connector Vertical Position', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 120,
                    ],
                ],
                'default' => [
                    'size' => 36,
                    'unit' => 'px',
                ],
                'condition' => [
                    'connector_line' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-journey__steps--line::before' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_step_card',
            [
                'label' => esc_html__('Step Card', 'nebula-forge-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'step_align',
            [
                'label' => esc_html__('Text Alignment', 'nebula-forge-addons-for-elementor'),
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
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .nfa-journey__step' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'step_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .nfa-journey__step',
            ]
        );

        $this->add_responsive_control(
            'step_padding',
            [
                'label' => esc_html__('Padding', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '0',
                    'right' => '0',
                    'bottom' => '0',
                    'left' => '0',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-journey__step' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'step_radius',
            [
                'label' => esc_html__('Border Radius', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 80,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-journey__step' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'step_border',
                'selector' => '{{WRAPPER}} .nfa-journey__step',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'step_shadow',
                'selector' => '{{WRAPPER}} .nfa-journey__step',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_number',
            [
                'label' => esc_html__('Step Number', 'nebula-forge-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'number_size',
            [
                'label' => esc_html__('Circle Size', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 36,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => 72,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-journey__number' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'number_typography',
                'selector' => '{{WRAPPER}} .nfa-journey__number',
            ]
        );

        $this->add_control(
            'number_color',
            [
                'label' => esc_html__('Text Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#131313',
                'selectors' => [
                    '{{WRAPPER}} .nfa-journey__number' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'number_bg_color',
            [
                'label' => esc_html__('Background Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .nfa-journey__number' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'number_border',
                'selector' => '{{WRAPPER}} .nfa-journey__number',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top' => 2,
                            'right' => 2,
                            'bottom' => 2,
                            'left' => 2,
                            'isLinked' => true,
                        ],
                    ],
                    'color' => [
                        'default' => '#131313',
                    ],
                ],
            ]
        );

        $this->add_control(
            'number_hover_bg_color',
            [
                'label' => esc_html__('Hover Background', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#131313',
                'selectors' => [
                    '{{WRAPPER}} .nfa-journey__step:hover .nfa-journey__number' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'number_hover_color',
            [
                'label' => esc_html__('Hover Text Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f4f4f4',
                'selectors' => [
                    '{{WRAPPER}} .nfa-journey__step:hover .nfa-journey__number' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'number_spacing',
            [
                'label' => esc_html__('Bottom Spacing', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 80,
                    ],
                ],
                'default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-journey__number' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_step_title',
            [
                'label' => esc_html__('Step Title', 'nebula-forge-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'step_title_typography',
                'selector' => '{{WRAPPER}} .nfa-journey__step-title',
            ]
        );

        $this->add_control(
            'step_title_color',
            [
                'label' => esc_html__('Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#131313',
                'selectors' => [
                    '{{WRAPPER}} .nfa-journey__step-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'step_title_spacing',
            [
                'label' => esc_html__('Bottom Spacing', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-journey__step-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_step_description',
            [
                'label' => esc_html__('Step Description', 'nebula-forge-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'step_description_typography',
                'selector' => '{{WRAPPER}} .nfa-journey__step-description',
            ]
        );

        $this->add_control(
            'step_description_color',
            [
                'label' => esc_html__('Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(19, 19, 19, 0.45)',
                'selectors' => [
                    '{{WRAPPER}} .nfa-journey__step-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $steps = !empty($settings['steps']) && is_array($settings['steps']) ? $settings['steps'] : [];
        $line_class = !empty($settings['connector_line']) && $settings['connector_line'] === 'yes' ? ' nfa-journey__steps--line' : '';
        ?>
        <section class="nfa-journey">
            <div class="nfa-journey__header">
                <?php if (!empty($settings['section_label'])) : ?>
                    <div class="nfa-journey__label"><?php echo esc_html($settings['section_label']); ?></div>
                <?php endif; ?>

                <?php if (!empty($settings['heading'])) : ?>
                    <h2 class="nfa-journey__heading"><?php echo esc_html($settings['heading']); ?></h2>
                <?php endif; ?>

                <?php if (!empty($settings['description'])) : ?>
                    <p class="nfa-journey__description"><?php echo esc_html($settings['description']); ?></p>
                <?php endif; ?>
            </div>

            <?php if (!empty($steps)) : ?>
                <div class="nfa-journey__steps<?php echo esc_attr($line_class); ?>">
                    <?php foreach ($steps as $step) : ?>
                        <div class="nfa-journey__step">
                            <?php if (!empty($step['step_number'])) : ?>
                                <div class="nfa-journey__number"><?php echo esc_html($step['step_number']); ?></div>
                            <?php endif; ?>

                            <?php if (!empty($step['step_title'])) : ?>
                                <h4 class="nfa-journey__step-title"><?php echo esc_html($step['step_title']); ?></h4>
                            <?php endif; ?>

                            <?php if (!empty($step['step_description'])) : ?>
                                <p class="nfa-journey__step-description"><?php echo esc_html($step['step_description']); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
        <?php
    }
}
