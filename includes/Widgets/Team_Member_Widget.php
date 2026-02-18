<?php
/**
 * Team Member Widget
 *
 * Professional team/staff profile cards with photo, name, role,
 * bio, social media links, and hover effects.
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
use Elementor\Repeater;
use Elementor\Widget_Base;

class Team_Member_Widget extends Widget_Base
{
    public function get_name(): string
    {
        return 'nfa-team-member';
    }

    public function get_title(): string
    {
        return esc_html__('Team Member', 'nebula-forge-addons-for-elementor');
    }

    public function get_icon(): string
    {
        return 'eicon-person';
    }

    public function get_categories(): array
    {
        return ['nebula-forge'];
    }

    public function get_keywords(): array
    {
        return ['team', 'member', 'staff', 'profile', 'people'];
    }

    public function get_style_depends(): array
    {
        return ['nebula-forge-elementor-addon-frontend'];
    }

    protected function register_controls(): void
    {
        /* ── Content ───────────────────────────────────── */
        $this->start_controls_section('section_content', [
            'label' => esc_html__('Content', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_responsive_control('columns', [
            'label'   => esc_html__('Columns', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SLIDER,
            'size_units' => ['col'],
            'range'   => ['col' => ['min' => 1, 'max' => 6]],
            'default' => ['size' => 3, 'unit' => 'col'],
            'tablet_default' => ['size' => 2, 'unit' => 'col'],
            'mobile_default' => ['size' => 1, 'unit' => 'col'],
            'selectors' => [
                '{{WRAPPER}} .nfa-team__grid' => 'grid-template-columns: repeat({{SIZE}}, minmax(0, 1fr));',
            ],
        ]);

        $this->add_control('image_position', [
            'label'   => esc_html__('Image Position', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'top',
            'options' => [
                'top'    => esc_html__('Top', 'nebula-forge-addons-for-elementor'),
                'left'   => esc_html__('Left', 'nebula-forge-addons-for-elementor'),
                'circle' => esc_html__('Rounded', 'nebula-forge-addons-for-elementor'),
            ],
        ]);

        $repeater = new Repeater();

        $repeater->add_control('photo', [
            'label' => esc_html__('Photo', 'nebula-forge-addons-for-elementor'),
            'type'  => Controls_Manager::MEDIA,
        ]);

        $repeater->add_control('name', [
            'label'       => esc_html__('Name', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Jane Doe', 'nebula-forge-addons-for-elementor'),
            'label_block' => true,
        ]);

        $repeater->add_control('role', [
            'label'       => esc_html__('Role / Position', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('CEO & Founder', 'nebula-forge-addons-for-elementor'),
            'label_block' => true,
        ]);

        $repeater->add_control('bio', [
            'label'   => esc_html__('Short Bio', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => esc_html__('Passionate about building products that make a difference.', 'nebula-forge-addons-for-elementor'),
            'rows'    => 3,
        ]);

        $repeater->add_control('social_facebook', [
            'label'       => esc_html__('Facebook URL', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::URL,
            'placeholder' => 'https://facebook.com/...',
        ]);

        $repeater->add_control('social_twitter', [
            'label'       => esc_html__('X (Twitter) URL', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::URL,
            'placeholder' => 'https://x.com/...',
        ]);

        $repeater->add_control('social_linkedin', [
            'label'       => esc_html__('LinkedIn URL', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::URL,
            'placeholder' => 'https://linkedin.com/in/...',
        ]);

        $repeater->add_control('social_instagram', [
            'label'       => esc_html__('Instagram URL', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::URL,
            'placeholder' => 'https://instagram.com/...',
        ]);

        $this->add_control('members', [
            'label'   => esc_html__('Members', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::REPEATER,
            'fields'  => $repeater->get_controls(),
            'default' => [
                [
                    'name' => esc_html__('Jane Doe', 'nebula-forge-addons-for-elementor'),
                    'role' => esc_html__('CEO & Founder', 'nebula-forge-addons-for-elementor'),
                    'bio'  => esc_html__('Passionate about building products that make a difference.', 'nebula-forge-addons-for-elementor'),
                ],
                [
                    'name' => esc_html__('John Smith', 'nebula-forge-addons-for-elementor'),
                    'role' => esc_html__('Lead Developer', 'nebula-forge-addons-for-elementor'),
                    'bio'  => esc_html__('Full-stack engineer with a focus on scalable architectures.', 'nebula-forge-addons-for-elementor'),
                ],
                [
                    'name' => esc_html__('Sarah Chen', 'nebula-forge-addons-for-elementor'),
                    'role' => esc_html__('Design Director', 'nebula-forge-addons-for-elementor'),
                    'bio'  => esc_html__('Crafting beautiful experiences that users love.', 'nebula-forge-addons-for-elementor'),
                ],
            ],
            'title_field' => '{{{ name }}}',
        ]);

        $this->end_controls_section();

        /* ── Heading Tag ──────────────────────────────── */
        $this->start_controls_section('section_heading', [
            'label' => esc_html__('Heading', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('heading_tag', [
            'label'   => esc_html__('Name HTML Tag', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'h3',
            'options' => [
                'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3',
                'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6',
                'div' => 'div', 'p' => 'p',
            ],
        ]);

        $this->end_controls_section();

        /* ── Style: Card ──────────────────────────────── */
        $this->start_controls_section('section_style_card', [
            'label' => esc_html__('Card', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'card_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .nfa-team__card',
        ]);

        $this->add_responsive_control('card_padding', [
            'label'      => esc_html__('Padding', 'nebula-forge-addons-for-elementor'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'default'    => ['top' => '0', 'right' => '0', 'bottom' => '24', 'left' => '0', 'unit' => 'px', 'isLinked' => false],
            'selectors'  => ['{{WRAPPER}} .nfa-team__card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('card_radius', [
            'label'     => esc_html__('Border Radius', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 80]],
            'default'   => ['size' => 18, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .nfa-team__card' => 'border-radius: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'card_border',
            'selector' => '{{WRAPPER}} .nfa-team__card',
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'     => 'card_shadow',
            'selector' => '{{WRAPPER}} .nfa-team__card',
        ]);

        $this->add_responsive_control('grid_gap', [
            'label'     => esc_html__('Gap', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 60]],
            'default'   => ['size' => 24, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .nfa-team__grid' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        /* ── Style: Image ─────────────────────────────── */
        $this->start_controls_section('section_style_image', [
            'label' => esc_html__('Image', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('image_height', [
            'label'     => esc_html__('Height', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 100, 'max' => 500]],
            'default'   => ['size' => 280, 'unit' => 'px'],
            'condition' => ['image_position!' => 'circle'],
            'selectors' => ['{{WRAPPER}} .nfa-team__photo' => 'height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('image_circle_size', [
            'label'     => esc_html__('Size', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 60, 'max' => 300]],
            'default'   => ['size' => 120, 'unit' => 'px'],
            'condition' => ['image_position' => 'circle'],
            'selectors' => [
                '{{WRAPPER}} .nfa-team__photo--circle' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        /* ── Style: Text ──────────────────────────────── */
        $this->start_controls_section('section_style_text', [
            'label' => esc_html__('Text', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'name_typography',
            'selector' => '{{WRAPPER}} .nfa-team__name',
        ]);

        $this->add_control('name_color', [
            'label'     => esc_html__('Name Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#131313',
            'selectors' => ['{{WRAPPER}} .nfa-team__name' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'role_typography',
            'selector' => '{{WRAPPER}} .nfa-team__role',
        ]);

        $this->add_control('role_color', [
            'label'     => esc_html__('Role Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#0ea5e9',
            'selectors' => ['{{WRAPPER}} .nfa-team__role' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'bio_typography',
            'selector' => '{{WRAPPER}} .nfa-team__bio',
        ]);

        $this->add_control('bio_color', [
            'label'     => esc_html__('Bio Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(19,19,19,0.55)',
            'selectors' => ['{{WRAPPER}} .nfa-team__bio' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        /* ── Style: Social Icons ──────────────────────── */
        $this->start_controls_section('section_style_social', [
            'label' => esc_html__('Social Icons', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('social_color', [
            'label'     => esc_html__('Icon Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(19,19,19,0.4)',
            'selectors' => ['{{WRAPPER}} .nfa-team__social a' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('social_hover_color', [
            'label'     => esc_html__('Icon Hover Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#0ea5e9',
            'selectors' => ['{{WRAPPER}} .nfa-team__social a:hover' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('social_size', [
            'label'     => esc_html__('Icon Size', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 12, 'max' => 40]],
            'default'   => ['size' => 18, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .nfa-team__social a' => 'font-size: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings  = $this->get_settings_for_display();
        $members   = $settings['members'] ?? [];
        $position  = $settings['image_position'] ?? 'top';
        $allowed_tags = ['h1','h2','h3','h4','h5','h6','div','p'];
        $tag       = in_array($settings['heading_tag'] ?? 'h3', $allowed_tags, true) ? $settings['heading_tag'] : 'h3';

        if (empty($members)) {
            return;
        }
        ?>
        <div class="nfa-team">
            <div class="nfa-team__grid">
                <?php foreach ($members as $member) : ?>
                    <div class="nfa-team__card nfa-team__card--<?php echo esc_attr($position); ?>">
                        <?php if (!empty($member['photo']['url'])) : ?>
                            <div class="nfa-team__photo<?php echo $position === 'circle' ? ' nfa-team__photo--circle' : ''; ?>">
                                <img src="<?php echo esc_url($member['photo']['url']); ?>"
                                     alt="<?php echo esc_attr($member['name'] ?? ''); ?>"
                                     loading="lazy">
                            </div>
                        <?php endif; ?>

                        <div class="nfa-team__body">
                            <?php if (!empty($member['name'])) : ?>
                                <<?php echo esc_attr($tag); ?> class="nfa-team__name"><?php echo esc_html($member['name']); ?></<?php echo esc_attr($tag); ?>>
                            <?php endif; ?>

                            <?php if (!empty($member['role'])) : ?>
                                <div class="nfa-team__role"><?php echo esc_html($member['role']); ?></div>
                            <?php endif; ?>

                            <?php if (!empty($member['bio'])) : ?>
                                <p class="nfa-team__bio"><?php echo esc_html($member['bio']); ?></p>
                            <?php endif; ?>

                            <?php
                            $socials = [
                                'facebook'  => ['url' => $member['social_facebook']['url'] ?? '', 'icon' => 'fab fa-facebook-f',  'label' => __('Facebook', 'nebula-forge-addons-for-elementor')],
                                'twitter'   => ['url' => $member['social_twitter']['url'] ?? '',  'icon' => 'fab fa-x-twitter',   'label' => __('X (Twitter)', 'nebula-forge-addons-for-elementor')],
                                'linkedin'  => ['url' => $member['social_linkedin']['url'] ?? '', 'icon' => 'fab fa-linkedin-in', 'label' => __('LinkedIn', 'nebula-forge-addons-for-elementor')],
                                'instagram' => ['url' => $member['social_instagram']['url'] ?? '','icon' => 'fab fa-instagram',   'label' => __('Instagram', 'nebula-forge-addons-for-elementor')],
                            ];
                            $has_socials = false;
                            foreach ($socials as $s) {
                                if (!empty($s['url'])) { $has_socials = true; break; }
                            }
                            ?>
                            <?php if ($has_socials) : ?>
                                <div class="nfa-team__social">
                                    <?php foreach ($socials as $key => $social) : ?>
                                        <?php if (!empty($social['url'])) : ?>
                                            <a href="<?php echo esc_url($social['url']); ?>"
                                               target="_blank"
                                               rel="noopener noreferrer"
                                               aria-label="<?php echo esc_attr($social['label']); ?>">
                                                <i class="<?php echo esc_attr($social['icon']); ?>"></i>
                                            </a>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
