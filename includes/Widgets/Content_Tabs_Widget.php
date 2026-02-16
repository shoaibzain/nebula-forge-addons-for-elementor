<?php
/**
 * Content Tabs Widget
 *
 * Tabbed content sections with horizontal or vertical tabs,
 * icon support, and full style controls.
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
use Elementor\Repeater;
use Elementor\Widget_Base;

class Content_Tabs_Widget extends Widget_Base
{
    public function get_name(): string
    {
        return 'nfa-content-tabs';
    }

    public function get_title(): string
    {
        return esc_html__('Content Tabs', 'nebula-forge-addons-for-elementor');
    }

    public function get_icon(): string
    {
        return 'eicon-tabs';
    }

    public function get_categories(): array
    {
        return ['nebula-forge'];
    }

    public function get_keywords(): array
    {
        return ['tabs', 'content', 'toggle', 'sections', 'tabbed'];
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
        /* ── Content ───────────────────────────────────── */
        $this->start_controls_section('section_content', [
            'label' => esc_html__('Tabs', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('tab_direction', [
            'label'   => esc_html__('Direction', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'horizontal',
            'options' => [
                'horizontal' => esc_html__('Horizontal', 'nebula-forge-addons-for-elementor'),
                'vertical'   => esc_html__('Vertical', 'nebula-forge-addons-for-elementor'),
            ],
        ]);

        $this->add_control('icon_position', [
            'label'   => esc_html__('Icon Position', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'left',
            'options' => [
                'left'  => esc_html__('Before Text', 'nebula-forge-addons-for-elementor'),
                'top'   => esc_html__('Above Text', 'nebula-forge-addons-for-elementor'),
                'none'  => esc_html__('No Icons', 'nebula-forge-addons-for-elementor'),
            ],
        ]);

        $repeater = new Repeater();

        $repeater->add_control('tab_title', [
            'label'       => esc_html__('Tab Title', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Tab Title', 'nebula-forge-addons-for-elementor'),
            'label_block' => true,
        ]);

        $repeater->add_control('tab_icon', [
            'label' => esc_html__('Tab Icon', 'nebula-forge-addons-for-elementor'),
            'type'  => Controls_Manager::ICONS,
        ]);

        $repeater->add_control('tab_content', [
            'label'   => esc_html__('Content', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('Tab content goes here. Click the edit button to change this text.', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('tabs', [
            'label'   => esc_html__('Tab Items', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::REPEATER,
            'fields'  => $repeater->get_controls(),
            'default' => [
                [
                    'tab_title'   => esc_html__('Features', 'nebula-forge-addons-for-elementor'),
                    'tab_content' => esc_html__('Discover our powerful features designed to help you build beautiful pages faster than ever.', 'nebula-forge-addons-for-elementor'),
                ],
                [
                    'tab_title'   => esc_html__('How It Works', 'nebula-forge-addons-for-elementor'),
                    'tab_content' => esc_html__('Simply drag and drop our widgets into your Elementor editor, customize the settings, and publish.', 'nebula-forge-addons-for-elementor'),
                ],
                [
                    'tab_title'   => esc_html__('Pricing', 'nebula-forge-addons-for-elementor'),
                    'tab_content' => esc_html__('All widgets are completely free. No hidden fees, no premium locks.', 'nebula-forge-addons-for-elementor'),
                ],
            ],
            'title_field' => '{{{ tab_title }}}',
        ]);

        $this->end_controls_section();

        /* ── Style: Tab Buttons ────────────────────────── */
        $this->start_controls_section('section_style_tabs', [
            'label' => esc_html__('Tab Buttons', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'tab_typography',
            'selector' => '{{WRAPPER}} .nfa-tabs__btn',
        ]);

        $this->add_control('tab_color', [
            'label'     => esc_html__('Text Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(19,19,19,0.55)',
            'selectors' => ['{{WRAPPER}} .nfa-tabs__btn' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('tab_active_color', [
            'label'     => esc_html__('Active Text Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#0ea5e9',
            'selectors' => ['{{WRAPPER}} .nfa-tabs__btn--active' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('tab_bg', [
            'label'     => esc_html__('Background', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'transparent',
            'selectors' => ['{{WRAPPER}} .nfa-tabs__btn' => 'background: {{VALUE}};'],
        ]);

        $this->add_control('tab_active_bg', [
            'label'     => esc_html__('Active Background', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(14,165,233,0.08)',
            'selectors' => ['{{WRAPPER}} .nfa-tabs__btn--active' => 'background: {{VALUE}};'],
        ]);

        $this->add_control('tab_indicator_color', [
            'label'     => esc_html__('Active Indicator', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#0ea5e9',
            'selectors' => ['{{WRAPPER}} .nfa-tabs__btn--active::after' => 'background: {{VALUE}};'],
        ]);

        $this->add_responsive_control('tab_padding', [
            'label'      => esc_html__('Padding', 'nebula-forge-addons-for-elementor'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => ['top' => '12', 'right' => '24', 'bottom' => '12', 'left' => '24', 'unit' => 'px', 'isLinked' => false],
            'selectors'  => ['{{WRAPPER}} .nfa-tabs__btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('tab_radius', [
            'label'     => esc_html__('Border Radius', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 30]],
            'default'   => ['size' => 8, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .nfa-tabs__btn' => 'border-radius: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_control('tab_icon_size', [
            'label'     => esc_html__('Icon Size', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 10, 'max' => 40]],
            'default'   => ['size' => 18, 'unit' => 'px'],
            'condition' => ['icon_position!' => 'none'],
            'selectors' => ['{{WRAPPER}} .nfa-tabs__btn-icon' => 'font-size: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        /* ── Style: Content Panel ──────────────────────── */
        $this->start_controls_section('section_style_content', [
            'label' => esc_html__('Content Panel', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'panel_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .nfa-tabs__panel',
        ]);

        $this->add_responsive_control('panel_padding', [
            'label'      => esc_html__('Padding', 'nebula-forge-addons-for-elementor'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => ['top' => '24', 'right' => '24', 'bottom' => '24', 'left' => '24', 'unit' => 'px', 'isLinked' => true],
            'selectors'  => ['{{WRAPPER}} .nfa-tabs__panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'panel_border',
            'selector' => '{{WRAPPER}} .nfa-tabs__panel',
        ]);

        $this->add_control('panel_radius', [
            'label'     => esc_html__('Border Radius', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 40]],
            'default'   => ['size' => 12, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .nfa-tabs__panel' => 'border-radius: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'content_typography',
            'selector' => '{{WRAPPER}} .nfa-tabs__panel',
        ]);

        $this->add_control('content_color', [
            'label'     => esc_html__('Text Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(19,19,19,0.7)',
            'selectors' => ['{{WRAPPER}} .nfa-tabs__panel' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings  = $this->get_settings_for_display();
        $tabs      = $settings['tabs'] ?? [];
        $direction = $settings['tab_direction'] ?? 'horizontal';
        $icon_pos  = $settings['icon_position'] ?? 'left';
        $widget_id = $this->get_id();

        if (empty($tabs)) {
            return;
        }
        ?>
        <div class="nfa-tabs nfa-tabs--<?php echo esc_attr($direction); ?>" data-tabs="true">
            <div class="nfa-tabs__nav" role="tablist">
                <?php foreach ($tabs as $index => $tab) :
                    $tab_id    = 'nfa-tab-' . esc_attr($widget_id) . '-' . $index;
                    $panel_id  = 'nfa-panel-' . esc_attr($widget_id) . '-' . $index;
                    $is_active = $index === 0;
                ?>
                    <button class="nfa-tabs__btn<?php echo $is_active ? ' nfa-tabs__btn--active' : ''; ?> nfa-tabs__btn--icon-<?php echo esc_attr($icon_pos); ?>"
                            role="tab"
                            id="<?php echo esc_attr($tab_id); ?>"
                            aria-controls="<?php echo esc_attr($panel_id); ?>"
                            aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
                            type="button">
                        <?php if ($icon_pos !== 'none' && !empty($tab['tab_icon']['value'])) : ?>
                            <span class="nfa-tabs__btn-icon">
                                <?php Icons_Manager::render_icon($tab['tab_icon'], ['aria-hidden' => 'true']); ?>
                            </span>
                        <?php endif; ?>
                        <span class="nfa-tabs__btn-text"><?php echo esc_html($tab['tab_title']); ?></span>
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="nfa-tabs__panels">
                <?php foreach ($tabs as $index => $tab) :
                    $tab_id   = 'nfa-tab-' . esc_attr($widget_id) . '-' . $index;
                    $panel_id = 'nfa-panel-' . esc_attr($widget_id) . '-' . $index;
                    $is_active = $index === 0;
                ?>
                    <div class="nfa-tabs__panel<?php echo $is_active ? ' nfa-tabs__panel--active' : ''; ?>"
                         role="tabpanel"
                         id="<?php echo esc_attr($panel_id); ?>"
                         aria-labelledby="<?php echo esc_attr($tab_id); ?>"
                         <?php if (!$is_active) : ?>hidden<?php endif; ?>>
                        <?php echo wp_kses_post($tab['tab_content']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
