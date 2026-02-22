<?php
/**
 * Hamburger Menu Widget
 *
 * Mobile-friendly off-canvas navigation menu with a customisable
 * hamburger icon, slide-in panel, and full style controls.
 *
 * @package NebulaForgeAddon
 * @since   0.7.0
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

/**
 * Class Hamburger_Menu_Widget
 *
 * @package NebulaForgeAddon\Widgets
 * @since   0.7.0
 */
class Hamburger_Menu_Widget extends Widget_Base
{
    /* ───────────────── Meta ───────────────── */

    public function get_name(): string
    {
        return 'nfa-hamburger-menu';
    }

    public function get_title(): string
    {
        return esc_html__('Hamburger Menu', 'nebula-forge-addons-for-elementor');
    }

    public function get_icon(): string
    {
        return 'eicon-menu-bar';
    }

    public function get_categories(): array
    {
        return ['nebula-forge'];
    }

    public function get_keywords(): array
    {
        return ['menu', 'hamburger', 'nav', 'navigation', 'mobile', 'off-canvas', 'sidebar'];
    }

    public function get_style_depends(): array
    {
        return ['nebula-forge-elementor-addon-frontend'];
    }

    public function get_script_depends(): array
    {
        return ['nebula-forge-elementor-addon-frontend'];
    }

    /* ───────────────── Controls ───────────────── */

    protected function register_controls(): void
    {
        /* ── Menu Items ────────────────────────── */
        $this->start_controls_section('section_menu_items', [
            'label' => esc_html__('Menu Items', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('menu_source', [
            'label'   => esc_html__('Source', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'custom',
            'options' => $this->get_menu_source_options(),
        ]);

        $repeater = new Repeater();

        $repeater->add_control('item_label', [
            'label'       => esc_html__('Label', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Menu Item', 'nebula-forge-addons-for-elementor'),
            'label_block' => true,
        ]);

        $repeater->add_control('item_link', [
            'label'       => esc_html__('Link', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::URL,
            'placeholder' => 'https://example.com',
            'default'     => ['url' => '#'],
        ]);

        $repeater->add_control('item_icon', [
            'label' => esc_html__('Icon', 'nebula-forge-addons-for-elementor'),
            'type'  => Controls_Manager::ICONS,
        ]);

        $repeater->add_control('has_submenu', [
            'label'   => esc_html__('Has Submenu', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SWITCHER,
            'default' => '',
        ]);

        $repeater->add_control('submenu_items', [
            'label'       => esc_html__('Submenu Items (one per line: Label | URL)', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::TEXTAREA,
            'rows'        => 4,
            'placeholder' => "Sub Item 1 | https://example.com\nSub Item 2 | #",
            'condition'   => ['has_submenu' => 'yes'],
        ]);

        $this->add_control('menu_items', [
            'label'       => esc_html__('Items', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                ['item_label' => esc_html__('Home', 'nebula-forge-addons-for-elementor'), 'item_link' => ['url' => '#']],
                ['item_label' => esc_html__('About', 'nebula-forge-addons-for-elementor'), 'item_link' => ['url' => '#']],
                ['item_label' => esc_html__('Services', 'nebula-forge-addons-for-elementor'), 'item_link' => ['url' => '#']],
                ['item_label' => esc_html__('Contact', 'nebula-forge-addons-for-elementor'), 'item_link' => ['url' => '#']],
            ],
            'title_field' => '{{{ item_label }}}',
            'condition'   => ['menu_source' => 'custom'],
        ]);

        $this->end_controls_section();

        /* ── Layout ────────────────────────── */
        $this->start_controls_section('section_layout', [
            'label' => esc_html__('Layout', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('panel_position', [
            'label'   => esc_html__('Panel Position', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'left',
            'options' => [
                'left'   => esc_html__('Left', 'nebula-forge-addons-for-elementor'),
                'right'  => esc_html__('Right', 'nebula-forge-addons-for-elementor'),
                'top'    => esc_html__('Top (Full Width)', 'nebula-forge-addons-for-elementor'),
            ],
        ]);

        $this->add_responsive_control('panel_width', [
            'label'      => esc_html__('Panel Width', 'nebula-forge-addons-for-elementor'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'vw'],
            'range'      => [
                'px' => ['min' => 200, 'max' => 600],
                '%'  => ['min' => 20, 'max' => 100],
                'vw' => ['min' => 20, 'max' => 100],
            ],
            'default'    => ['size' => 300, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .nfa-hamburger__panel--left, {{WRAPPER}} .nfa-hamburger__panel--right' => 'width: {{SIZE}}{{UNIT}};',
            ],
            'condition' => ['panel_position!' => 'top'],
        ]);

        $this->add_control('show_logo', [
            'label'   => esc_html__('Show Logo', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SWITCHER,
            'default' => '',
        ]);

        $this->add_control('logo_image', [
            'label'     => esc_html__('Logo', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::MEDIA,
            'condition' => ['show_logo' => 'yes'],
        ]);

        $this->add_control('show_overlay', [
            'label'   => esc_html__('Show Overlay', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('close_on_link', [
            'label'   => esc_html__('Close on Link Click', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('close_on_esc', [
            'label'   => esc_html__('Close on Escape Key', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->end_controls_section();

        /* ── Toggle Button Style ───────────── */
        $this->start_controls_section('section_toggle_style', [
            'label' => esc_html__('Toggle Button', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('toggle_icon_type', [
            'label'   => esc_html__('Icon Style', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'bars',
            'options' => [
                'bars'  => esc_html__('Three Bars', 'nebula-forge-addons-for-elementor'),
                'dots'  => esc_html__('Three Dots', 'nebula-forge-addons-for-elementor'),
                'grid'  => esc_html__('Grid', 'nebula-forge-addons-for-elementor'),
            ],
        ]);

        $this->add_responsive_control('toggle_size', [
            'label'      => esc_html__('Icon Size', 'nebula-forge-addons-for-elementor'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 16, 'max' => 60]],
            'default'    => ['size' => 24, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .nfa-hamburger__toggle' => 'font-size: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('toggle_padding', [
            'label'      => esc_html__('Padding', 'nebula-forge-addons-for-elementor'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .nfa-hamburger__toggle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('toggle_color', [
            'label'     => esc_html__('Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1e293b',
            'selectors' => [
                '{{WRAPPER}} .nfa-hamburger__toggle' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('toggle_bg', [
            'label'     => esc_html__('Background', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'transparent',
            'selectors' => [
                '{{WRAPPER}} .nfa-hamburger__toggle' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('toggle_border_radius', [
            'label'      => esc_html__('Border Radius', 'nebula-forge-addons-for-elementor'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => [
                '{{WRAPPER}} .nfa-hamburger__toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'toggle_border',
            'selector' => '{{WRAPPER}} .nfa-hamburger__toggle',
        ]);

        $this->add_responsive_control('toggle_align', [
            'label'   => esc_html__('Alignment', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => ['title' => esc_html__('Left', 'nebula-forge-addons-for-elementor'), 'icon' => 'eicon-h-align-left'],
                'center'     => ['title' => esc_html__('Center', 'nebula-forge-addons-for-elementor'), 'icon' => 'eicon-h-align-center'],
                'flex-end'   => ['title' => esc_html__('Right', 'nebula-forge-addons-for-elementor'), 'icon' => 'eicon-h-align-right'],
            ],
            'default'   => 'flex-start',
            'selectors' => [
                '{{WRAPPER}} .nfa-hamburger__toggle-wrap' => 'justify-content: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        /* ── Panel Style ───────────────────── */
        $this->start_controls_section('section_panel_style', [
            'label' => esc_html__('Panel', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'panel_bg',
            'selector' => '{{WRAPPER}} .nfa-hamburger__panel',
        ]);

        $this->add_responsive_control('panel_padding', [
            'label'      => esc_html__('Padding', 'nebula-forge-addons-for-elementor'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => ['top' => '24', 'right' => '24', 'bottom' => '24', 'left' => '24', 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .nfa-hamburger__panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'     => 'panel_shadow',
            'selector' => '{{WRAPPER}} .nfa-hamburger__panel',
        ]);

        $this->add_control('overlay_color', [
            'label'     => esc_html__('Overlay Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(0, 0, 0, 0.5)',
            'selectors' => [
                '{{WRAPPER}} .nfa-hamburger__overlay' => 'background: {{VALUE}};',
            ],
            'condition' => ['show_overlay' => 'yes'],
        ]);

        $this->add_control('close_btn_color', [
            'label'     => esc_html__('Close Button Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#64748b',
            'selectors' => [
                '{{WRAPPER}} .nfa-hamburger__close' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        /* ── Menu Item Style ───────────────── */
        $this->start_controls_section('section_menu_style', [
            'label' => esc_html__('Menu Items', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'menu_typography',
            'selector' => '{{WRAPPER}} .nfa-hamburger__link',
        ]);

        $this->add_control('menu_color', [
            'label'     => esc_html__('Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1e293b',
            'selectors' => [
                '{{WRAPPER}} .nfa-hamburger__link' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('menu_hover_color', [
            'label'     => esc_html__('Hover Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#6366f1',
            'selectors' => [
                '{{WRAPPER}} .nfa-hamburger__link:hover, {{WRAPPER}} .nfa-hamburger__link:focus' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('menu_item_padding', [
            'label'      => esc_html__('Item Padding', 'nebula-forge-addons-for-elementor'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => ['top' => '10', 'right' => '0', 'bottom' => '10', 'left' => '0', 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .nfa-hamburger__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('menu_separator', [
            'label'     => esc_html__('Separator', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SWITCHER,
            'default'   => 'yes',
        ]);

        $this->add_control('separator_color', [
            'label'     => esc_html__('Separator Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#e2e8f0',
            'selectors' => [
                '{{WRAPPER}} .nfa-hamburger__item--separated' => 'border-bottom-color: {{VALUE}};',
            ],
            'condition' => ['menu_separator' => 'yes'],
        ]);

        $this->add_control('menu_icon_color', [
            'label'     => esc_html__('Icon Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#6366f1',
            'selectors' => [
                '{{WRAPPER}} .nfa-hamburger__link-icon' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'submenu_typography',
            'label'    => esc_html__('Submenu Typography', 'nebula-forge-addons-for-elementor'),
            'selector' => '{{WRAPPER}} .nfa-hamburger__sub-link',
        ]);

        $this->add_control('submenu_color', [
            'label'     => esc_html__('Submenu Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#64748b',
            'selectors' => [
                '{{WRAPPER}} .nfa-hamburger__sub-link' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    /* ───────────────── Helpers ───────────────── */

    /**
     * Gather WP menu sources + "custom" option.
     */
    private function get_menu_source_options(): array
    {
        $options = ['custom' => esc_html__('Custom', 'nebula-forge-addons-for-elementor')];

        $menus = wp_get_nav_menus();
        if (!empty($menus)) {
            foreach ($menus as $menu) {
                $options['wp_menu_' . $menu->term_id] = $menu->name;
            }
        }

        return $options;
    }

    /**
     * Parse submenu text into item arrays.
     */
    private function parse_submenu(string $raw): array
    {
        $items = [];
        $lines = preg_split('/\r?\n/', trim($raw));

        foreach ($lines as $line) {
            $parts = array_map('trim', explode('|', $line, 2));
            if (empty($parts[0])) {
                continue;
            }
            $items[] = [
                'label' => $parts[0],
                'url'   => $parts[1] ?? '#',
            ];
        }

        return $items;
    }

    /* ───────────────── Render ───────────────── */

    protected function render(): void
    {
        $s = $this->get_settings_for_display();
        $position      = $s['panel_position'];
        $show_overlay  = $s['show_overlay'] === 'yes';
        $close_on_link = $s['close_on_link'] === 'yes';
        $close_on_esc  = $s['close_on_esc'] === 'yes';
        $separator     = $s['menu_separator'] === 'yes';
        $icon_type     = $s['toggle_icon_type'];
        $uid           = 'nfa-hm-' . $this->get_id();

        // Build menu items.
        $menu_items = [];

        if ($s['menu_source'] === 'custom') {
            if (!empty($s['menu_items'])) {
                foreach ($s['menu_items'] as $item) {
                    $entry = [
                        'label' => $item['item_label'] ?? '',
                        'url'   => $item['item_link']['url'] ?? '#',
                        'external' => !empty($item['item_link']['is_external']),
                        'nofollow' => !empty($item['item_link']['nofollow']),
                        'icon'  => $item['item_icon'] ?? [],
                        'sub'   => [],
                    ];

                    if (!empty($item['has_submenu']) && $item['has_submenu'] === 'yes' && !empty($item['submenu_items'])) {
                        $entry['sub'] = $this->parse_submenu($item['submenu_items']);
                    }

                    $menu_items[] = $entry;
                }
            }
        } else {
            $menu_id = (int) str_replace('wp_menu_', '', $s['menu_source']);
            $nav_items = wp_get_nav_menu_items($menu_id);

            if (!empty($nav_items)) {
                $parents = [];
                $children = [];

                foreach ($nav_items as $nav) {
                    if ($nav->menu_item_parent == 0) {
                        $parents[$nav->ID] = [
                            'label'    => $nav->title,
                            'url'      => $nav->url,
                            'external' => $nav->target === '_blank',
                            'nofollow' => false,
                            'icon'     => [],
                            'sub'      => [],
                        ];
                    } else {
                        $children[$nav->menu_item_parent][] = [
                            'label' => $nav->title,
                            'url'   => $nav->url,
                        ];
                    }
                }

                foreach ($parents as $pid => &$parent) {
                    if (isset($children[$pid])) {
                        $parent['sub'] = $children[$pid];
                    }
                }
                unset($parent);
                $menu_items = array_values($parents);
            }
        }

        // Toggle icon markup.
        $toggle_html = '';
        if ($icon_type === 'dots') {
            $toggle_html = '<span class="nfa-hamburger__icon nfa-hamburger__icon--dots"><span></span><span></span><span></span></span>';
        } elseif ($icon_type === 'grid') {
            $toggle_html = '<span class="nfa-hamburger__icon nfa-hamburger__icon--grid"><span></span><span></span><span></span><span></span></span>';
        } else {
            $toggle_html = '<span class="nfa-hamburger__icon nfa-hamburger__icon--bars"><span></span><span></span><span></span></span>';
        }
        ?>
        <div class="nfa-hamburger"
             id="<?php echo esc_attr($uid); ?>"
             data-close-on-link="<?php echo $close_on_link ? '1' : '0'; ?>"
             data-close-on-esc="<?php echo $close_on_esc ? '1' : '0'; ?>">

            <!-- Toggle Button -->
            <div class="nfa-hamburger__toggle-wrap">
                <button class="nfa-hamburger__toggle" type="button"
                        aria-label="<?php esc_attr_e('Toggle menu', 'nebula-forge-addons-for-elementor'); ?>"
                        aria-expanded="false"
                        aria-controls="<?php echo esc_attr($uid); ?>-panel">
                    <?php echo $toggle_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG/spans ?>
                </button>
            </div>

            <?php if ($show_overlay) : ?>
                <div class="nfa-hamburger__overlay" aria-hidden="true"></div>
            <?php endif; ?>

            <!-- Panel -->
            <nav class="nfa-hamburger__panel nfa-hamburger__panel--<?php echo esc_attr($position); ?>"
                 id="<?php echo esc_attr($uid); ?>-panel"
                 aria-hidden="true"
                 role="navigation"
                 aria-label="<?php esc_attr_e('Off-canvas navigation', 'nebula-forge-addons-for-elementor'); ?>">

                <div class="nfa-hamburger__panel-header">
                    <?php if (!empty($s['show_logo']) && $s['show_logo'] === 'yes' && !empty($s['logo_image']['url'])) : ?>
                        <img class="nfa-hamburger__logo" src="<?php echo esc_url($s['logo_image']['url']); ?>"
                             alt="<?php esc_attr_e('Logo', 'nebula-forge-addons-for-elementor'); ?>">
                    <?php endif; ?>
                    <button class="nfa-hamburger__close" type="button"
                            aria-label="<?php esc_attr_e('Close menu', 'nebula-forge-addons-for-elementor'); ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <ul class="nfa-hamburger__list">
                    <?php foreach ($menu_items as $mi) : ?>
                        <?php
                        $item_class = 'nfa-hamburger__item';
                        if ($separator) {
                            $item_class .= ' nfa-hamburger__item--separated';
                        }
                        if (!empty($mi['sub'])) {
                            $item_class .= ' nfa-hamburger__item--has-sub';
                        }

                        $link_attrs = '';
                        if ($mi['external']) {
                            $link_attrs .= ' target="_blank" rel="noopener noreferrer';
                            if ($mi['nofollow']) {
                                $link_attrs .= ' nofollow';
                            }
                            $link_attrs .= '"';
                        } elseif ($mi['nofollow']) {
                            $link_attrs .= ' rel="nofollow"';
                        }
                        ?>
                        <li class="<?php echo esc_attr($item_class); ?>">
                            <a class="nfa-hamburger__link" href="<?php echo esc_url($mi['url']); ?>"<?php echo $link_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                                <?php if (!empty($mi['icon']['value'])) : ?>
                                    <span class="nfa-hamburger__link-icon">
                                        <i class="<?php echo esc_attr($mi['icon']['value']); ?>"></i>
                                    </span>
                                <?php endif; ?>
                                <span><?php echo esc_html($mi['label']); ?></span>
                                <?php if (!empty($mi['sub'])) : ?>
                                    <span class="nfa-hamburger__arrow" aria-hidden="true">&#x25BE;</span>
                                <?php endif; ?>
                            </a>
                            <?php if (!empty($mi['sub'])) : ?>
                                <ul class="nfa-hamburger__sub-list">
                                    <?php foreach ($mi['sub'] as $sub) : ?>
                                        <li class="nfa-hamburger__sub-item">
                                            <a class="nfa-hamburger__sub-link" href="<?php echo esc_url($sub['url']); ?>">
                                                <?php echo esc_html($sub['label']); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
        <?php
    }
}
