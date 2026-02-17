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
                'label'       => __('Hero Section', 'nebula-forge-addons-for-elementor'),
                'description' => __('Full-width hero banner with kicker text, headline, sub-copy, and a powerful CTA button.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Use above the fold on any page — landing pages, homepages, blog headers, portfolios.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('HERO', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#f97316',
                'category'    => 'layout',
                'class'       => Widgets\Hero_Cta_Widget::class,
                'icon'        => 'eicon-call-to-action',
            ],
            'feature_list' => [
                'label'       => __('Feature Showcase', 'nebula-forge-addons-for-elementor'),
                'description' => __('Responsive grid of feature items with icons, titles, and descriptions.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Perfect for highlighting services, benefits, or product features on any page.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('GRID', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#38bdf8',
                'category'    => 'content',
                'class'       => Widgets\Feature_List_Widget::class,
                'icon'        => 'eicon-posts-grid',
            ],
            'spotlight_card' => [
                'label'       => __('Content Spotlight', 'nebula-forge-addons-for-elementor'),
                'description' => __('Rich content card with eyebrow, title, body text, featured image, and CTA button.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Great for blog post highlights, portfolio pieces, or product showcases.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('CARD', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#a78bfa',
                'category'    => 'content',
                'class'       => Widgets\Spotlight_Card_Widget::class,
                'icon'        => 'eicon-info-box',
            ],
            'stats_grid' => [
                'label'       => __('Stats Counter', 'nebula-forge-addons-for-elementor'),
                'description' => __('Eye-catching number grid to showcase KPIs, metrics, and achievements.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Display company stats, project numbers, or any quantifiable achievements.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('DATA', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#34d399',
                'category'    => 'data',
                'class'       => Widgets\Stats_Grid_Widget::class,
                'icon'        => 'eicon-number-field',
            ],
            'pricing_table' => [
                'label'       => __('Pricing Plans', 'nebula-forge-addons-for-elementor'),
                'description' => __('Professional pricing card with plan name, price, features checklist, and CTA button.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Highlight your recommended plan to guide visitors toward conversion.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('PRICE', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#fb7185',
                'category'    => 'conversion',
                'class'       => Widgets\Pricing_Table_Widget::class,
                'icon'        => 'eicon-price-table',
            ],
            'testimonials_grid' => [
                'label'       => __('Reviews Showcase', 'nebula-forge-addons-for-elementor'),
                'description' => __('Social-proof grid or slider with quotes, avatars, roles, and star ratings.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Add client testimonials, product reviews, or team endorsements.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('SOCIAL', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#fbbf24',
                'category'    => 'social',
                'class'       => Widgets\Testimonial_Grid_Widget::class,
                'icon'        => 'eicon-review',
            ],
            'logo_grid' => [
                'label'       => __('Brand Showcase', 'nebula-forge-addons-for-elementor'),
                'description' => __('Clean, responsive grid or carousel of partner, client, or sponsor logos.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Show trust signals with 6–12 brand logos on any page type.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('BRAND', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#60a5fa',
                'category'    => 'social',
                'class'       => Widgets\Logo_Grid_Widget::class,
                'icon'        => 'eicon-gallery-grid',
            ],
            'faq_accordion' => [
                'label'       => __('FAQ Section', 'nebula-forge-addons-for-elementor'),
                'description' => __('Collapsible accordion for frequently asked questions, support docs, or knowledge base.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Use on service pages, product pages, blog posts, or dedicated FAQ pages.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('FAQ', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#22c55e',
                'category'    => 'content',
                'class'       => Widgets\Faq_Accordion_Widget::class,
                'icon'        => 'eicon-help-o',
            ],
            'steps_timeline' => [
                'label'       => __('Process Timeline', 'nebula-forge-addons-for-elementor'),
                'description' => __('Visual step-by-step timeline for workflows, onboarding, or project phases.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Explain any multi-step process — from order flow to project delivery.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('STEPS', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#818cf8',
                'category'    => 'data',
                'class'       => Widgets\Steps_Timeline_Widget::class,
                'icon'        => 'eicon-time-line',
            ],
            'journey_process' => [
                'label'       => __('Journey Map', 'nebula-forge-addons-for-elementor'),
                'description' => __('End-to-end journey section with numbered phases, descriptions, and full style controls.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Map out customer journeys, service workflows, or investment processes.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('FLOW', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#6366f1',
                'category'    => 'data',
                'class'       => Widgets\Journey_Process_Widget::class,
                'icon'        => 'eicon-flow',
            ],
            'showcase_carousel' => [
                'label'       => __('Showcase Carousel', 'nebula-forge-addons-for-elementor'),
                'description' => __('Image card carousel with badges, titles, descriptions, tags, and full navigation controls.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Showcase developers, projects, products, or team members in a sleek sliding carousel.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('SLIDE', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#ec4899',
                'category'    => 'content',
                'class'       => Widgets\Showcase_Carousel_Widget::class,
                'icon'        => 'eicon-media-carousel',
            ],
            'areas_grid' => [
                'label'       => __('Areas Grid', 'nebula-forge-addons-for-elementor'),
                'description' => __('Responsive image-card grid with badges, titles, and a featured first-card overlay.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Highlight locations, areas, or categories with an eye-catching featured card layout.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('GRID', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#14b8a6',
                'category'    => 'content',
                'class'       => Widgets\Areas_Grid_Widget::class,
                'icon'        => 'eicon-gallery-grid',
            ],
            'team_member' => [
                'label'       => __('Team Member', 'nebula-forge-addons-for-elementor'),
                'description' => __('Professional team/staff profile cards with photo, name, role, bio, and social links.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Showcase your team, staff, or contributors with elegant profile cards and social media icons.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('TEAM', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#f472b6',
                'category'    => 'social',
                'class'       => Widgets\Team_Member_Widget::class,
                'icon'        => 'eicon-person',
            ],
            'cta_banner' => [
                'label'       => __('CTA Banner', 'nebula-forge-addons-for-elementor'),
                'description' => __('Conversion-focused call-to-action banner with gradient backgrounds, icon, and dual buttons.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Drive conversions with eye-catching banners — perfect for promotions, signups, or announcements.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('CTA', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#ef4444',
                'category'    => 'conversion',
                'class'       => Widgets\Cta_Banner_Widget::class,
                'icon'        => 'eicon-call-to-action',
            ],
            'content_tabs' => [
                'label'       => __('Content Tabs', 'nebula-forge-addons-for-elementor'),
                'description' => __('Tabbed content sections with horizontal or vertical tabs, icons, and rich WYSIWYG panels.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Organize page content into switchable tabs — ideal for features, plans, or categorized info.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('TABS', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#8b5cf6',
                'category'    => 'content',
                'class'       => Widgets\Content_Tabs_Widget::class,
                'icon'        => 'eicon-tabs',
            ],
            'image_comparison' => [
                'label'       => __('Image Comparison', 'nebula-forge-addons-for-elementor'),
                'description' => __('Before / after image comparison slider with draggable handle and label overlays.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Compare two images interactively — great for makeovers, edits, design revisions, or results.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('COMPARE', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#06b6d4',
                'category'    => 'content',
                'class'       => Widgets\Image_Comparison_Widget::class,
                'icon'        => 'eicon-image-before-after',
            ],
            'countdown_timer' => [
                'label'       => __('Countdown Timer', 'nebula-forge-addons-for-elementor'),
                'description' => __('Countdown to a target date with blocks, inline, or circle styles and expiry actions.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Create urgency for launches, sales, events, or deadlines with a live countdown clock.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('TIMER', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#f59e0b',
                'category'    => 'conversion',
                'class'       => Widgets\Countdown_Timer_Widget::class,
                'icon'        => 'eicon-countdown',
            ],
            'hamburger_menu' => [
                'label'       => __('Hamburger Menu', 'nebula-forge-addons-for-elementor'),
                'description' => __('Off-canvas mobile navigation with slide panel, WP menus or custom items, and submenu support.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Add a responsive hamburger menu with off-canvas navigation, ideal for mobile-first designs.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('MENU', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#8b5cf6',
                'category'    => 'layout',
                'class'       => Widgets\Hamburger_Menu_Widget::class,
                'icon'        => 'eicon-menu-bar',
            ],
            'advanced_form' => [
                'label'       => __('Advanced Form', 'nebula-forge-addons-for-elementor'),
                'description' => __('Drag-and-drop form builder with email notifications, database submissions, and field validation.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Build contact, lead, or registration forms with AJAX submission, validation, and saved entries.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('FORM', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#10b981',
                'category'    => 'conversion',
                'class'       => Widgets\Advanced_Form_Widget::class,
                'icon'        => 'eicon-form-horizontal',
            ],
            'numbered_cards' => [
                'label'       => __('Numbered Cards', 'nebula-forge-addons-for-elementor'),
                'description' => __('Numbered feature/process cards with Grid and Carousel layout modes, icons or images.', 'nebula-forge-addons-for-elementor'),
                'tooltip'     => __('Display services, features, or process steps as numbered cards in a grid or sliding carousel.', 'nebula-forge-addons-for-elementor'),
                'badge'       => __('CARDS', 'nebula-forge-addons-for-elementor'),
                'badge_color' => '#84cc16',
                'category'    => 'content',
                'class'       => Widgets\Numbered_Cards_Widget::class,
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

        if (!class_exists($class)) {
            return null;
        }

        return new $class();
    }
}
