=== Nebula Forge Addons for Elementor ===
Contributors: shoaibzain
Tags: elementor, elementor widgets, elementor addons, page builder, blog widgets
Requires at least: 6.2
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 0.4.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Professional Elementor widgets for any WordPress page — blogs, portfolios, landing pages, WooCommerce, and beyond.

== Description ==
Nebula Forge Addons for Elementor delivers professional, conversion-focused widgets for the free Elementor editor. Build beautiful sections on any WordPress page — landing pages, blog posts, portfolios, WooCommerce stores, and more — with clean defaults and powerful styling controls.

= Highlights =
* Built for the free Elementor plugin. No Elementor Pro required.
* Focused widgets for hero sections, feature lists, spotlight cards, stats grids, pricing tables, testimonials, FAQs, logo grids, and steps.
* Detailed style controls for typography, spacing, colors, borders, and hover states.
* Lightweight assets that only load when the widgets are used.
* Admin settings with tabs, tooltips, and inline guidance for a faster setup.
* Built with capability checks, nonces, and sanitized settings.
* Layout controls to switch between grid and slider displays.

= Widgets =
Nebula Forge Addons for Elementor delivers a curated set of professional widgets for any WordPress page:

* Hero Section — full-width hero banner with kicker text, headline, sub-copy, and a powerful CTA button.
* Feature Showcase — responsive grid of feature items with icons, titles, and descriptions.
* Content Spotlight — rich content card with eyebrow, title, body text, featured image, and CTA button.
* Stats Counter — eye-catching number grid to showcase KPIs, metrics, and achievements.
* Pricing Plans — professional pricing card with plan name, price, features checklist, and CTA button.
* Reviews Showcase — social-proof grid or slider with quotes, avatars, roles, and star ratings.
* Brand Showcase — clean, responsive grid or carousel of partner, client, or sponsor logos.
* FAQ Section — collapsible accordion for frequently asked questions, support docs, or knowledge base.
* Process Timeline — visual step-by-step timeline for workflows, onboarding, or project phases.
* Journey Map — end-to-end journey section with numbered phases, descriptions, and full style controls.

= Great for =
* Landing pages and product launches
* Blog posts and article layouts
* Portfolio and case study pages
* WooCommerce store pages
* Marketing call-to-action sections
* Feature and benefit highlights
* KPI and social-proof blocks
* Pricing and plan comparisons
* Customer testimonials and trust signals
* FAQ and onboarding sections

== Installation ==
1. Upload the `nebula-forge-addons-for-elementor` folder to the `/wp-content/plugins/` directory, or install the ZIP via the WordPress Plugins page.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Ensure **Elementor** is active. Open the Elementor editor and look for the **Nebula Forge** category to find the widgets.

== Frequently Asked Questions ==
= Does this require Elementor Pro? =
No. These widgets work with the free Elementor plugin.

= Where do the widgets appear? =
In the Elementor editor sidebar under the **Nebula Forge** category.

= Can I change colors and typography? =
Yes. Each widget exposes granular style controls for backgrounds, typography, spacing, borders, and hover states.

= What are the minimum requirements? =
WordPress 6.2+, PHP 7.4+, and Elementor 3.20+.

= How do I disable widgets I do not need? =
Go to **Nebula Forge > Settings** in wp-admin and toggle off unused widgets.

= Will this slow down my site? =
The plugin only registers assets for its widgets and loads them when those widgets are used.

= Is it translation-ready? =
Yes. All user-facing strings are translatable. Includes Arabic, Urdu, French, and Spanish translations.

= How can I support the plugin? =
Leaving a review on WordPress.org helps more users find the plugin.

= Does it include demo pages? =
Use the Welcome screen checklist to build a quick demo page in Elementor. Start with a Hero CTA, add a Feature List, then layer in Testimonials, a Pricing Table, and an FAQ.

== Screenshots ==
1. Welcome screen with setup checklist and tabs.
2. Widget settings with enable/disable toggles and tooltips.
3. Hero CTA widget example.
4. Feature List widget example.
5. Spotlight Card widget example.
6. Stats Grid widget example.
7. Pricing Table widget example.
8. Testimonials Grid widget example.
9. Logo Grid widget example.
10. FAQ Accordion widget example.
11. Steps Timeline widget example.

== Changelog ==
= 0.4.0 =
* Switched frontend styles from dark theme (CSS custom properties) to light-mode defaults for broader compatibility.
* Added mobile-responsive rules for Areas Grid featured card layout.
* Added Card style controls (background, padding, border radius, border, box shadow) to Spotlight Card, Pricing Table, Logo Grid, and Showcase Carousel widgets.
* Added Item Card and Text style sections to Logo Grid widget.
* Added toggle icon, open-state background, and open-state border color controls to FAQ Accordion widget.
* Added badge typography and border radius controls to Showcase Carousel widget.
* Added tag typography and border radius controls to Showcase Carousel widget.
* Updated default widget colors from dark-theme palette to neutral light-mode values across all widgets.
* Fixed slider arrow color default to white for better contrast.

= 0.3.0 =
* Redesigned admin Welcome page with widget stats, categorised showcase grid, and system status panel.
* Redesigned Settings page with Enable All / Disable All toolbar and real-time active counter.
* Redesigned Changelog page with vertical timeline and colour-coded change types.
* Updated plugin description to reflect support for blogs, portfolios, WooCommerce, and all page types.
* Renamed widgets for clarity: Hero Section, Feature Showcase, Content Spotlight, Stats Counter, Pricing Plans, Reviews Showcase, Brand Showcase, FAQ Section, Process Timeline, Journey Map.
* Updated all widget icons to a consistent Elementor icon set.
* Added category grouping (Layout, Content, Data & Process, Conversion, Social Proof) to widget registry.
* Rewrote admin CSS with modern design token system, glassmorphism header, and pro-level cards.
* Added onboarding 3-step flow to Welcome page.
* Added Pro Tip sidebar card with link to Settings.

= 0.2.0 =
* Fixed broken selectors_dictionary in Hero CTA and Pricing Table widgets.
* Replaced href="#" with role="button" tabindex="0" for accessibility.
* Removed Hero CTA JS dependency — hover effect now pure CSS.
* Conditional JS loading for Logo Grid and Testimonial Grid slider mode.
* Added responsive grid column defaults (tablet_default, mobile_default) to all grid widgets.
* Added HTML heading tag selector (H1-H6, div, p) to all 10 widgets.
* Refactored Testimonial Grid card template into reusable render method.
* Upgraded frontend CSS with design tokens, gradient backgrounds, and hover effects.
* Cleaned up frontend JS — removed wiggle handler, added debounced resize.

= 0.1.6 =
* Fixed fatal error when a widget class is missing by skipping registration.
* Fixed admin page fatal when Ui_Helper is unavailable.
* Documentation cleanup in readme.

= 0.1.5 =
* Added grid/slider layout option for Testimonials and Logo widgets.

= 0.1.4 =
* Added new widgets: Pricing Table, Testimonials Grid, Logo Grid, FAQ Accordion, Steps Timeline.
* Added widget badges and improved admin guidance for faster discovery.
* Expanded translations and refreshed listing metadata.

= 0.1.3 =
* Improved admin UI with tabs, tooltips, and inline guidance.
* Optimized asset loading to enqueue styles/scripts only when widgets are used.
* Refreshed readme content for clarity and discoverability.

= 0.1.2 =
* Bumped plugin version to `0.1.2`.
* Added translators comment for version placeholder in the admin Welcome page.
* Removed legacy `load_plugin_textdomain()` call.
* Fixed PHP parse error in `Admin_Manager.php` and improved request method checks.
* Added nonce verification and sanitization for settings save.

= 0.1.1 =
* Fix activation requirements check and harden security defaults.

= 0.1.0 =
* Initial release with four widgets: Hero CTA, Feature List, Spotlight Card, Stats Grid.

== Upgrade Notice ==
= 0.4.0 =
Light-mode frontend defaults, new card/badge/tag style controls, mobile-responsive fixes, and color updates across all widgets. Update recommended.

= 0.3.0 =
Major admin redesign with pro layout, updated widget names, category grouping, and broader page-type support. Update recommended.

= 0.2.0 =
Bug fixes, accessibility improvements, responsive defaults, heading tag selectors, and upgraded frontend design. Update recommended.

= 0.1.6 =
Fixes admin and widget registration fatals. Update recommended.

= 0.1.5 =
Grid/slider layout option for Testimonials and Logo widgets. Update recommended.

= 0.1.4 =
New widgets, enhanced admin experience, and expanded translations. Update recommended.

= 0.1.3 =
Admin UI improvements and performance tweaks. Update recommended.

= 0.1.2 =
Maintenance and security hardening. Update recommended.

= 0.1.1 =
Maintenance release.

= 0.1.0 =
First public release.
