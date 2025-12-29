<?php
/**
 * Widget Registry - Central registry for all available widgets.
 *
 * @package NebulaForgeAddon
 * @since   0.2.0
 */

namespace NebulaForgeAddon\Admin;

use NebulaForgeAddon\Widgets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Widget_Registry
 *
 * Provides widget metadata and instantiation.
 */
final class Widget_Registry
{
    /**
     * Get all available widgets with metadata.
     *
     * @return array<string, array{label: string, description: string, class: string, icon: string}>
     */
    public static function get_available_widgets(): array
    {
        return [
            'hero_cta' => [
                'label'       => __('Hero CTA', 'nebula-forge-elementor-addons'),
                'description' => __('Expressive hero banner with kicker, headline, supporting copy, and a stylable primary button.', 'nebula-forge-elementor-addons'),
                'class'       => Widgets\Hero_Cta_Widget::class,
                'icon'        => 'eicon-single-page',
            ],
            'feature_list' => [
                'label'       => __('Feature List', 'nebula-forge-elementor-addons'),
                'description' => __('Grid/list of benefit items with icons, headings, and descriptions using a repeater field.', 'nebula-forge-elementor-addons'),
                'class'       => Widgets\Feature_List_Widget::class,
                'icon'        => 'eicon-bullet-list',
            ],
            'spotlight_card' => [
                'label'       => __('Spotlight Card', 'nebula-forge-elementor-addons'),
                'description' => __('Media-forward card with eyebrow, title, description, meta text, CTA button, and image support.', 'nebula-forge-elementor-addons'),
                'class'       => Widgets\Spotlight_Card_Widget::class,
                'icon'        => 'eicon-image-box',
            ],
            'stats_grid' => [
                'label'       => __('Stats Grid', 'nebula-forge-elementor-addons'),
                'description' => __('KPI grid with value/label/helper text per item to showcase performance metrics.', 'nebula-forge-elementor-addons'),
                'class'       => Widgets\Stats_Grid_Widget::class,
                'icon'        => 'eicon-number-field',
            ],
        ];
    }

    /**
     * Create widget instance by key.
     *
     * @param string $widget_key Widget identifier.
     * @return object|null Widget instance or null if not found.
     */
    public static function create_widget(string $widget_key): ?object
    {
        $widgets = self::get_available_widgets();

        if (!isset($widgets[$widget_key])) {
            return null;
        }

        $class = $widgets[$widget_key]['class'];

        return new $class();
    }
}
