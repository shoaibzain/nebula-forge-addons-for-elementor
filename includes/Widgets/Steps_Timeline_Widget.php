<?php
/**
 * Steps Timeline Widget
 *
 * Visual step-by-step timeline for workflows, onboarding,
 * or project phases.
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

class Steps_Timeline_Widget extends Widget_Base
{
    public function get_name(): string
    {
        return 'nfa-steps-timeline';
    }

    public function get_title(): string
    {
        return esc_html__('Steps Timeline', 'nebula-forge-addons-for-elementor');
    }

    public function get_icon(): string
    {
        return 'eicon-time-line';
    }

    public function get_categories(): array
    {
        return ['nebula-forge'];
    }

    public function get_keywords(): array
    {
        return ['steps', 'timeline', 'process', 'workflow'];
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
            'layout',
            [
                'label' => esc_html__('Layout', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal' => esc_html__('Horizontal', 'nebula-forge-addons-for-elementor'),
                    'vertical' => esc_html__('Vertical', 'nebula-forge-addons-for-elementor'),
                ],
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'step_label',
            [
                'label' => esc_html__('Step Label', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Step 01', 'nebula-forge-addons-for-elementor'),
            ]
        );

        $repeater->add_control(
            'step_title',
            [
                'label' => esc_html__('Title', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Plan the section', 'nebula-forge-addons-for-elementor'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'step_description',
            [
                'label' => esc_html__('Description', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('Pick a widget, add content, and define the layout structure.', 'nebula-forge-addons-for-elementor'),
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
                        'step_label' => esc_html__('Step 01', 'nebula-forge-addons-for-elementor'),
                        'step_title' => esc_html__('Plan the section', 'nebula-forge-addons-for-elementor'),
                        'step_description' => esc_html__('Pick a widget, add content, and define the layout structure.', 'nebula-forge-addons-for-elementor'),
                    ],
                    [
                        'step_label' => esc_html__('Step 02', 'nebula-forge-addons-for-elementor'),
                        'step_title' => esc_html__('Customize styling', 'nebula-forge-addons-for-elementor'),
                        'step_description' => esc_html__('Adjust colors, spacing, and typography to match your brand.', 'nebula-forge-addons-for-elementor'),
                    ],
                    [
                        'step_label' => esc_html__('Step 03', 'nebula-forge-addons-for-elementor'),
                        'step_title' => esc_html__('Publish and test', 'nebula-forge-addons-for-elementor'),
                        'step_description' => esc_html__('Launch your page and measure engagement with confidence.', 'nebula-forge-addons-for-elementor'),
                    ],
                ],
                'title_field' => '{{{ step_title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_steps',
            [
                'label' => esc_html__('Steps', 'nebula-forge-addons-for-elementor'),
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
                    'layout' => 'horizontal',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-steps--horizontal .nfa-steps__list' => 'grid-template-columns: repeat({{SIZE}}, minmax(0, 1fr));',
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
                        'max' => 60,
                    ],
                ],
                'default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-steps__list' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'step_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .nfa-steps__item',
                'fields_options' => [
                    'color' => [
                        'default' => 'rgba(255, 255, 255, 0.02)',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'step_padding',
            [
                'label' => esc_html__('Padding', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '18',
                    'right' => '18',
                    'bottom' => '18',
                    'left' => '18',
                    'unit' => 'px',
                    'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-steps__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'step_radius',
            [
                'label' => esc_html__('Radius', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'default' => [
                    'size' => 18,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .nfa-steps__item' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'step_border',
                'selector' => '{{WRAPPER}} .nfa-steps__item',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'step_shadow',
                'selector' => '{{WRAPPER}} .nfa-steps__item',
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
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .nfa-steps__label',
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => esc_html__('Label Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#0ea5e9',
                'selectors' => [
                    '{{WRAPPER}} .nfa-steps__label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .nfa-steps__title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Title Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#131313',
                'selectors' => [
                    '{{WRAPPER}} .nfa-steps__title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .nfa-steps__description',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__('Description Color', 'nebula-forge-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(19,19,19,0.55)',
                'selectors' => [
                    '{{WRAPPER}} .nfa-steps__description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $steps = isset($settings['steps']) ? $settings['steps'] : [];
        $layout = isset($settings['layout']) ? $settings['layout'] : 'horizontal';
        $wrapper_class = $layout === 'vertical' ? 'nfa-steps nfa-steps--vertical' : 'nfa-steps nfa-steps--horizontal';
        ?>
        <div class="<?php echo esc_attr($wrapper_class); ?>">
            <?php if (!empty($steps)) : ?>
                <div class="nfa-steps__list">
                    <?php foreach ($steps as $step) : ?>
                        <div class="nfa-steps__item">
                            <?php if (!empty($step['step_label'])) : ?>
                                <div class="nfa-steps__label"><?php echo esc_html($step['step_label']); ?></div>
                            <?php endif; ?>
                            <?php if (!empty($step['step_title'])) : ?>
                                <h4 class="nfa-steps__title"><?php echo esc_html($step['step_title']); ?></h4>
                            <?php endif; ?>
                            <?php if (!empty($step['step_description'])) : ?>
                                <p class="nfa-steps__description"><?php echo esc_html($step['step_description']); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
