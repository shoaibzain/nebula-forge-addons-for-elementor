<?php
/**
 * Advanced Form Widget
 *
 * Drag-and-drop form builder with configurable fields, validation,
 * email notifications, and database submission storage.
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
 * Class Advanced_Form_Widget
 *
 * @package NebulaForgeAddon\Widgets
 * @since   0.7.0
 */
class Advanced_Form_Widget extends Widget_Base
{
    /* ───────────────── Meta ───────────────── */

    public function get_name(): string
    {
        return 'nfa-advanced-form';
    }

    public function get_title(): string
    {
        return esc_html__('Advanced Form', 'nebula-forge-addons-for-elementor');
    }

    public function get_icon(): string
    {
        return 'eicon-form-horizontal';
    }

    public function get_categories(): array
    {
        return ['nebula-forge'];
    }

    public function get_keywords(): array
    {
        return ['form', 'contact', 'lead', 'submission', 'email', 'input'];
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
        /* ── Form Fields ────────────────────── */
        $this->start_controls_section('section_form_fields', [
            'label' => esc_html__('Form Fields', 'nebula-forge-addons-for-elementor'),
        ]);

        $repeater = new Repeater();

        $repeater->add_control('field_type', [
            'label'   => esc_html__('Type', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'text',
            'options' => [
                'text'     => esc_html__('Text', 'nebula-forge-addons-for-elementor'),
                'email'    => esc_html__('Email', 'nebula-forge-addons-for-elementor'),
                'tel'      => esc_html__('Phone', 'nebula-forge-addons-for-elementor'),
                'url'      => esc_html__('URL', 'nebula-forge-addons-for-elementor'),
                'number'   => esc_html__('Number', 'nebula-forge-addons-for-elementor'),
                'textarea' => esc_html__('Textarea', 'nebula-forge-addons-for-elementor'),
                'select'   => esc_html__('Select', 'nebula-forge-addons-for-elementor'),
                'radio'    => esc_html__('Radio', 'nebula-forge-addons-for-elementor'),
                'checkbox' => esc_html__('Checkbox', 'nebula-forge-addons-for-elementor'),
                'date'     => esc_html__('Date', 'nebula-forge-addons-for-elementor'),
                'file'     => esc_html__('File Upload', 'nebula-forge-addons-for-elementor'),
                'hidden'   => esc_html__('Hidden', 'nebula-forge-addons-for-elementor'),
            ],
        ]);

        $repeater->add_control('field_label', [
            'label'       => esc_html__('Label', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Field', 'nebula-forge-addons-for-elementor'),
            'label_block' => true,
        ]);

        $repeater->add_control('field_placeholder', [
            'label'     => esc_html__('Placeholder', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::TEXT,
            'condition' => ['field_type!' => ['checkbox', 'radio', 'select', 'file', 'hidden']],
        ]);

        $repeater->add_control('field_required', [
            'label'   => esc_html__('Required', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SWITCHER,
            'default' => '',
        ]);

        $repeater->add_responsive_control('field_width', [
            'label'   => esc_html__('Width', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SELECT,
            'default' => '100',
            'options' => [
                '100' => '100%',
                '75'  => '75%',
                '66'  => '66%',
                '50'  => '50%',
                '33'  => '33%',
                '25'  => '25%',
            ],
        ]);

        $repeater->add_control('field_options', [
            'label'       => esc_html__('Options (one per line)', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::TEXTAREA,
            'rows'        => 4,
            'placeholder' => "Option 1\nOption 2\nOption 3",
            'condition'   => ['field_type' => ['select', 'radio', 'checkbox']],
        ]);

        $repeater->add_control('field_default', [
            'label'     => esc_html__('Default Value', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::TEXT,
            'condition' => ['field_type' => ['text', 'email', 'tel', 'url', 'number', 'hidden']],
        ]);

        $repeater->add_control('field_max_length', [
            'label'     => esc_html__('Max Length', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::NUMBER,
            'condition' => ['field_type' => ['text', 'email', 'tel', 'url', 'textarea']],
        ]);

        $repeater->add_control('file_max_size', [
            'label'       => esc_html__('Max File Size (MB)', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::NUMBER,
            'default'     => 5,
            'condition'   => ['field_type' => 'file'],
        ]);

        $repeater->add_control('file_types', [
            'label'       => esc_html__('Allowed Types (comma-separated)', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => 'jpg,jpeg,png,pdf,doc,docx',
            'condition'   => ['field_type' => 'file'],
        ]);

        $this->add_control('form_fields', [
            'label'       => esc_html__('Fields', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                ['field_type' => 'text',     'field_label' => esc_html__('Name', 'nebula-forge-addons-for-elementor'),    'field_placeholder' => esc_html__('Your name', 'nebula-forge-addons-for-elementor'),    'field_required' => 'yes', 'field_width' => '50'],
                ['field_type' => 'email',    'field_label' => esc_html__('Email', 'nebula-forge-addons-for-elementor'),   'field_placeholder' => esc_html__('you@example.com', 'nebula-forge-addons-for-elementor'), 'field_required' => 'yes', 'field_width' => '50'],
                ['field_type' => 'text',     'field_label' => esc_html__('Subject', 'nebula-forge-addons-for-elementor'), 'field_placeholder' => esc_html__('Subject', 'nebula-forge-addons-for-elementor'),     'field_width' => '100'],
                ['field_type' => 'textarea', 'field_label' => esc_html__('Message', 'nebula-forge-addons-for-elementor'), 'field_placeholder' => esc_html__('Your message…', 'nebula-forge-addons-for-elementor'), 'field_required' => 'yes', 'field_width' => '100'],
            ],
            'title_field' => '{{{ field_label }}} ({{{ field_type }}})',
        ]);

        $this->end_controls_section();

        /* ── Form Settings ────────────────────── */
        $this->start_controls_section('section_form_settings', [
            'label' => esc_html__('Form Settings', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('form_name', [
            'label'       => esc_html__('Form Name', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Contact Form', 'nebula-forge-addons-for-elementor'),
            'description' => esc_html__('Used to identify submissions in the admin area.', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('submit_label', [
            'label'   => esc_html__('Submit Button Text', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::TEXT,
            'default' => esc_html__('Send Message', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('success_message', [
            'label'   => esc_html__('Success Message', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::TEXT,
            'default' => esc_html__('Thank you! Your message has been sent successfully.', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('error_message', [
            'label'   => esc_html__('Error Message', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::TEXT,
            'default' => esc_html__('Something went wrong. Please try again.', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('show_labels', [
            'label'   => esc_html__('Show Labels', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('show_required_mark', [
            'label'   => esc_html__('Required Mark (*)', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->end_controls_section();

        /* ── Actions After Submit ──────────────── */
        $this->start_controls_section('section_actions', [
            'label' => esc_html__('Actions After Submit', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('action_save', [
            'label'   => esc_html__('Save to Database', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
            'description' => esc_html__('Store every submission in the WordPress database. View them under Nebula Forge > Submissions.', 'nebula-forge-addons-for-elementor'),
        ]);

        $this->add_control('action_email', [
            'label'   => esc_html__('Send Email', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->add_control('email_to', [
            'label'       => esc_html__('To', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => get_option('admin_email'),
            'placeholder' => 'admin@example.com',
            'condition'   => ['action_email' => 'yes'],
        ]);

        $this->add_control('email_subject', [
            'label'     => esc_html__('Subject', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::TEXT,
            'default'   => esc_html__('New Form Submission: {form_name}', 'nebula-forge-addons-for-elementor'),
            'condition' => ['action_email' => 'yes'],
        ]);

        $this->add_control('email_from_name', [
            'label'     => esc_html__('From Name', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::TEXT,
            'default'   => get_option('blogname'),
            'condition' => ['action_email' => 'yes'],
        ]);

        $this->add_control('email_reply_to', [
            'label'       => esc_html__('Reply-To Field', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::TEXT,
            'default'     => 'email',
            'description' => esc_html__('Enter the field label whose value should be used as Reply-To address (e.g., "Email").', 'nebula-forge-addons-for-elementor'),
            'condition'   => ['action_email' => 'yes'],
        ]);

        $this->add_control('action_redirect', [
            'label'   => esc_html__('Redirect After Submit', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::SWITCHER,
            'default' => '',
        ]);

        $this->add_control('redirect_url', [
            'label'       => esc_html__('Redirect URL', 'nebula-forge-addons-for-elementor'),
            'type'        => Controls_Manager::URL,
            'condition'   => ['action_redirect' => 'yes'],
        ]);

        $this->end_controls_section();

        /* ── Button Style ──────────────────────── */
        $this->start_controls_section('section_button_style', [
            'label' => esc_html__('Submit Button', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'button_typography',
            'selector' => '{{WRAPPER}} .nfa-form__submit',
        ]);

        $this->add_control('button_color', [
            'label'     => esc_html__('Text Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .nfa-form__submit' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('button_bg', [
            'label'     => esc_html__('Background', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#6366f1',
            'selectors' => [
                '{{WRAPPER}} .nfa-form__submit' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('button_hover_bg', [
            'label'     => esc_html__('Hover Background', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#4f46e5',
            'selectors' => [
                '{{WRAPPER}} .nfa-form__submit:hover' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('button_padding', [
            'label'      => esc_html__('Padding', 'nebula-forge-addons-for-elementor'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => ['top' => '14', 'right' => '32', 'bottom' => '14', 'left' => '32', 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .nfa-form__submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('button_border_radius', [
            'label'      => esc_html__('Border Radius', 'nebula-forge-addons-for-elementor'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default'    => ['top' => '8', 'right' => '8', 'bottom' => '8', 'left' => '8', 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .nfa-form__submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('button_align', [
            'label'   => esc_html__('Alignment', 'nebula-forge-addons-for-elementor'),
            'type'    => Controls_Manager::CHOOSE,
            'options' => [
                'flex-start' => ['title' => esc_html__('Left', 'nebula-forge-addons-for-elementor'), 'icon' => 'eicon-h-align-left'],
                'center'     => ['title' => esc_html__('Center', 'nebula-forge-addons-for-elementor'), 'icon' => 'eicon-h-align-center'],
                'flex-end'   => ['title' => esc_html__('Right', 'nebula-forge-addons-for-elementor'), 'icon' => 'eicon-h-align-right'],
                'stretch'    => ['title' => esc_html__('Full Width', 'nebula-forge-addons-for-elementor'), 'icon' => 'eicon-h-align-stretch'],
            ],
            'default'   => 'flex-start',
            'selectors' => [
                '{{WRAPPER}} .nfa-form__actions' => 'justify-content: {{VALUE}};',
                '{{WRAPPER}} .nfa-form__submit'  => 'align-self: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        /* ── Field Style ───────────────────────── */
        $this->start_controls_section('section_field_style', [
            'label' => esc_html__('Fields', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'field_typography',
            'selector' => '{{WRAPPER}} .nfa-form__input, {{WRAPPER}} .nfa-form__select, {{WRAPPER}} .nfa-form__textarea',
        ]);

        $this->add_control('field_bg', [
            'label'     => esc_html__('Background', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .nfa-form__input, {{WRAPPER}} .nfa-form__select, {{WRAPPER}} .nfa-form__textarea' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('field_text_color', [
            'label'     => esc_html__('Text Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#1e293b',
            'selectors' => [
                '{{WRAPPER}} .nfa-form__input, {{WRAPPER}} .nfa-form__select, {{WRAPPER}} .nfa-form__textarea' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('field_border_color', [
            'label'     => esc_html__('Border Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#cbd5e1',
            'selectors' => [
                '{{WRAPPER}} .nfa-form__input, {{WRAPPER}} .nfa-form__select, {{WRAPPER}} .nfa-form__textarea' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('field_focus_color', [
            'label'     => esc_html__('Focus Border Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#6366f1',
            'selectors' => [
                '{{WRAPPER}} .nfa-form__input:focus, {{WRAPPER}} .nfa-form__select:focus, {{WRAPPER}} .nfa-form__textarea:focus' => 'border-color: {{VALUE}}; box-shadow: 0 0 0 3px {{VALUE}}22;',
            ],
        ]);

        $this->add_responsive_control('field_padding', [
            'label'      => esc_html__('Padding', 'nebula-forge-addons-for-elementor'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => ['top' => '10', 'right' => '14', 'bottom' => '10', 'left' => '14', 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .nfa-form__input, {{WRAPPER}} .nfa-form__select, {{WRAPPER}} .nfa-form__textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('field_border_radius', [
            'label'      => esc_html__('Border Radius', 'nebula-forge-addons-for-elementor'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px'],
            'default'    => ['top' => '8', 'right' => '8', 'bottom' => '8', 'left' => '8', 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .nfa-form__input, {{WRAPPER}} .nfa-form__select, {{WRAPPER}} .nfa-form__textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('row_gap', [
            'label'      => esc_html__('Row Gap', 'nebula-forge-addons-for-elementor'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 40]],
            'default'    => ['size' => 16, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .nfa-form__fields' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'label_typography',
            'label'    => esc_html__('Label Typography', 'nebula-forge-addons-for-elementor'),
            'selector' => '{{WRAPPER}} .nfa-form__label',
        ]);

        $this->add_control('label_color', [
            'label'     => esc_html__('Label Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#334155',
            'selectors' => [
                '{{WRAPPER}} .nfa-form__label' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        /* ── Messages Style ────────────────────── */
        $this->start_controls_section('section_message_style', [
            'label' => esc_html__('Messages', 'nebula-forge-addons-for-elementor'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('success_bg', [
            'label'     => esc_html__('Success Background', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#f0fdf4',
            'selectors' => [
                '{{WRAPPER}} .nfa-form__msg--success' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('success_color', [
            'label'     => esc_html__('Success Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#166534',
            'selectors' => [
                '{{WRAPPER}} .nfa-form__msg--success' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('error_bg', [
            'label'     => esc_html__('Error Background', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#fef2f2',
            'selectors' => [
                '{{WRAPPER}} .nfa-form__msg--error' => 'background: {{VALUE}};',
            ],
        ]);

        $this->add_control('error_color', [
            'label'     => esc_html__('Error Color', 'nebula-forge-addons-for-elementor'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#991b1b',
            'selectors' => [
                '{{WRAPPER}} .nfa-form__msg--error' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    /* ───────────────── Render ───────────────── */

    protected function render(): void
    {
        $s = $this->get_settings_for_display();
        $fields       = !empty($s['form_fields']) ? $s['form_fields'] : [];
        $show_labels  = $s['show_labels'] === 'yes';
        $show_req     = $s['show_required_mark'] === 'yes';
        $form_name    = !empty($s['form_name']) ? $s['form_name'] : 'Form';
        $uid          = 'nfa-form-' . $this->get_id();

        // Build data attributes for JS.
        $data = [
            'formName'       => $form_name,
            'successMessage' => $s['success_message'] ?? '',
            'errorMessage'   => $s['error_message'] ?? '',
            'actionSave'     => $s['action_save'] === 'yes',
            'actionEmail'    => $s['action_email'] === 'yes',
            'emailTo'        => $s['email_to'] ?? '',
            'emailSubject'   => $s['email_subject'] ?? '',
            'emailFromName'  => $s['email_from_name'] ?? '',
            'emailReplyTo'   => $s['email_reply_to'] ?? '',
            'actionRedirect' => ($s['action_redirect'] ?? '') === 'yes',
            'redirectUrl'    => $s['redirect_url']['url'] ?? '',
            'nonce'          => wp_create_nonce('nfa_form_submit'),
            'ajaxUrl'        => admin_url('admin-ajax.php'),
        ];
        ?>
        <div class="nfa-form" id="<?php echo esc_attr($uid); ?>"
             data-nfa-form='<?php echo esc_attr(wp_json_encode($data)); ?>'>
            <form class="nfa-form__el" novalidate>
                <div class="nfa-form__fields">
                    <?php foreach ($fields as $index => $field) :
                        $type        = $field['field_type'];
                        $label       = $field['field_label'];
                        $placeholder = $field['field_placeholder'] ?? '';
                        $required    = ($field['field_required'] ?? '') === 'yes';
                        $width       = $field['field_width'] ?? '100';
                        $default     = $field['field_default'] ?? '';
                        $max_len     = $field['field_max_length'] ?? '';
                        $field_id    = $uid . '-f' . $index;
                        $field_name  = 'nfa_field_' . $index;

                        $width_class = 'nfa-form__col nfa-form__col--' . esc_attr($width);
                        if ($type === 'hidden') {
                            $width_class .= ' nfa-form__col--hidden';
                        }
                    ?>
                        <div class="<?php echo esc_attr($width_class); ?>">
                            <?php if ($show_labels && $type !== 'hidden') : ?>
                                <label class="nfa-form__label" for="<?php echo esc_attr($field_id); ?>">
                                    <?php echo esc_html($label); ?>
                                    <?php if ($required && $show_req) : ?>
                                        <span class="nfa-form__required" aria-hidden="true">*</span>
                                    <?php endif; ?>
                                </label>
                            <?php endif; ?>

                            <?php
                            $shared_attrs = 'id="' . esc_attr($field_id) . '" name="' . esc_attr($field_name) . '" data-label="' . esc_attr($label) . '"';
                            if ($required) {
                                $shared_attrs .= ' required';
                            }
                            if ($max_len) {
                                $shared_attrs .= ' maxlength="' . esc_attr($max_len) . '"';
                            }

                            switch ($type) :
                                case 'textarea':
                                    ?>
                                    <textarea class="nfa-form__textarea" <?php echo $shared_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> placeholder="<?php echo esc_attr($placeholder); ?>" rows="5"><?php echo esc_textarea($default); ?></textarea>
                                    <?php
                                    break;

                                case 'select':
                                    $options = preg_split('/\r?\n/', trim($field['field_options'] ?? ''));
                                    ?>
                                    <select class="nfa-form__select" <?php echo $shared_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                                        <option value=""><?php echo esc_html($placeholder ?: __('Select…', 'nebula-forge-addons-for-elementor')); ?></option>
                                        <?php foreach ($options as $opt) : $opt = trim($opt); if ($opt === '') continue; ?>
                                            <option value="<?php echo esc_attr($opt); ?>"><?php echo esc_html($opt); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php
                                    break;

                                case 'radio':
                                case 'checkbox':
                                    $options = preg_split('/\r?\n/', trim($field['field_options'] ?? ''));
                                    ?>
                                    <div class="nfa-form__choice-group" role="group" aria-labelledby="<?php echo esc_attr($field_id); ?>-label">
                                        <?php foreach ($options as $oi => $opt) : $opt = trim($opt); if ($opt === '') continue; ?>
                                            <label class="nfa-form__choice">
                                                <input type="<?php echo esc_attr($type); ?>"
                                                       name="<?php echo esc_attr($field_name); ?><?php echo $type === 'checkbox' ? '[]' : ''; ?>"
                                                       value="<?php echo esc_attr($opt); ?>"
                                                       data-label="<?php echo esc_attr($label); ?>"
                                                       <?php echo $required && $oi === 0 ? 'required' : ''; ?>>
                                                <span><?php echo esc_html($opt); ?></span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php
                                    break;

                                case 'file':
                                    $accept = '';
                                    if (!empty($field['file_types'])) {
                                        $exts = array_map('trim', explode(',', $field['file_types']));
                                        $accept = implode(',', array_map(function ($e) { return '.' . $e; }, $exts));
                                    }
                                    ?>
                                    <input class="nfa-form__input nfa-form__input--file" type="file"
                                           <?php echo $shared_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                           <?php if ($accept) : ?>accept="<?php echo esc_attr($accept); ?>"<?php endif; ?>
                                           data-max-size="<?php echo esc_attr($field['file_max_size'] ?? 5); ?>">
                                    <?php
                                    break;

                                case 'hidden':
                                    ?>
                                    <input type="hidden" <?php echo $shared_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> value="<?php echo esc_attr($default); ?>">
                                    <?php
                                    break;

                                default: // text, email, tel, url, number, date
                                    ?>
                                    <input class="nfa-form__input" type="<?php echo esc_attr($type); ?>"
                                           <?php echo $shared_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                           placeholder="<?php echo esc_attr($placeholder); ?>"
                                           value="<?php echo esc_attr($default); ?>">
                                    <?php
                            endswitch;
                            ?>
                            <span class="nfa-form__field-error" role="alert"></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="nfa-form__actions">
                    <button type="submit" class="nfa-form__submit">
                        <span class="nfa-form__submit-text"><?php echo esc_html($s['submit_label']); ?></span>
                        <span class="nfa-form__spinner" style="display:none;" aria-hidden="true"></span>
                    </button>
                </div>

                <div class="nfa-form__msg" role="status" aria-live="polite" style="display:none;"></div>
            </form>
        </div>
        <?php
    }
}
