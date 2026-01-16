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
     * @return array<string, array{label: string, description: string, tooltip: string, badge: string, badge_color: string, class: string, icon: string}>
     */
    public static function get_available_widgets(): array
    {
        return [
            'hero_cta' => [
                'label'       => __('Hero CTA', 'nebula-forge-addons-for-elementor'),
                'description' => __('Expressive hero banner with kicker, headline, supporting copy, and a stylable primary button.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Best for above-the-fold sections with a single call-to-action button.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('HERO', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#f97316',
                'class'       => Widgets\Hero_Cta_Widget::class,
                'icon'        => 'eicon-single-page',
            ],
            'feature_list' => [
                'label'       => __('Feature List', 'nebula-forge-addons-for-elementor'),
                'description' => __('Grid/list of benefit items with icons, headings, and descriptions using a repeater field.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Use 3-6 items for the best scan-friendly layout.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('LIST', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#38bdf8',
                'class'       => Widgets\Feature_List_Widget::class,
                'icon'        => 'eicon-bullet-list',
            ],
            'spotlight_card' => [
                'label'       => __('Spotlight Card', 'nebula-forge-addons-for-elementor'),
                'description' => __('Media-forward card with eyebrow, title, description, meta text, CTA button, and image support.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Pair with a product image or illustration for storytelling blocks.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('CARD', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#a78bfa',
                'class'       => Widgets\Spotlight_Card_Widget::class,
                'icon'        => 'eicon-image-box',
            ],
            'stats_grid' => [
                'label'       => __('Stats Grid', 'nebula-forge-addons-for-elementor'),
                'description' => __('KPI grid with value/label/helper text per item to showcase performance metrics.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Keep values short to avoid wrapping on mobile.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('STATS', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#34d399',
                'class'       => Widgets\Stats_Grid_Widget::class,
                'icon'        => 'eicon-number-field',
            ],
            'pricing_table' => [
                'label'       => __('Pricing Table', 'nebula-forge-addons-for-elementor'),
                'description' => __('Plan card with pricing, features list, and a strong call-to-action button.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Highlight one plan to guide visitors toward your best offer.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('PRICE', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#fb7185',
                'class'       => Widgets\Pricing_Table_Widget::class,
                'icon'        => 'eicon-price-table',
            ],
            'testimonials_grid' => [
                'label'       => __('Testimonials Grid', 'nebula-forge-addons-for-elementor'),
                'description' => __('Social-proof quotes with avatars, roles, and star ratings.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Use 3-6 testimonials for a clean, balanced grid.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('TESTI', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#fbbf24',
                'class'       => Widgets\Testimonial_Grid_Widget::class,
                'icon'        => 'eicon-testimonial-carousel',
            ],
            'logo_grid' => [
                'label'       => __('Logo Grid', 'nebula-forge-addons-for-elementor'),
                'description' => __('Partner or client logos laid out in a clean, responsive grid.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Add 6-12 logos for the best visual rhythm.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('LOGO', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#60a5fa',
                'class'       => Widgets\Logo_Grid_Widget::class,
                'icon'        => 'eicon-logo',
            ],
            'faq_accordion' => [
                'label'       => __('FAQ Accordion', 'nebula-forge-addons-for-elementor'),
                'description' => __('Collapsible Q&A list to address objections and support questions.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Keep answers concise to avoid long scrolls.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('FAQ', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#22c55e',
                'class'       => Widgets\Faq_Accordion_Widget::class,
                'icon'        => 'eicon-accordion',
            ],
            'steps_timeline' => [
                'label'       => __('Steps Timeline', 'nebula-forge-addons-for-elementor'),
                'description' => __('Sequential steps to explain your workflow or onboarding process.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Use short labels to keep the timeline readable.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('STEPS', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#818cf8',
                'class'       => Widgets\Steps_Timeline_Widget::class,
                'icon'        => 'eicon-time-line',
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

        if (!class_exists($class)) {
            return null;
        }

        return new $class();
    }
}
