<?php
/**
 * Image Accordion Elementor Widget
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Image_Accordion_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        // Used by JS hook: frontend/element_ready/image-accordion.default
        return 'image-accordion';
    }

    public function get_title() {
        return __( 'Image Accordion', 'image-accordion' );
    }

    public function get_icon() {
        return 'eicon-gallery-accordion';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    public function get_keywords() {
        return [ 'image', 'accordion', 'gallery', 'hover', 'jeremy' ];
    }

    /**
     * Register controls
     */
    protected function register_controls() {

        // ---------------------------------------------------------------------
        // CONTENT – Layout
        // ---------------------------------------------------------------------
        $this->start_controls_section(
            'section_layout',
            [
                'label' => __( 'Layout', 'image-accordion' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // Direction (responsive)
        $this->add_responsive_control(
            'direction',
            [
                'label'   => __( 'Direction', 'image-accordion' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'horizontal',
                'tablet_default' => 'horizontal',
                'mobile_default' => 'vertical',
                'options' => [
                    'horizontal' => __( 'Horizontal', 'image-accordion' ),
                    'vertical'   => __( 'Vertical', 'image-accordion' ),
                ],
            ]
        );

        // Accordion Height (responsive)
        $this->add_responsive_control(
            'accordion_height',
            [
                'label' => __( 'Accordion Height', 'image-accordion' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh', '%' ],
                'range' => [
                    'px' => [ 'min' => 100, 'max' => 1200 ],
                    'vh' => [ 'min' => 10, 'max' => 100 ],
                    '%'  => [ 'min' => 10, 'max' => 100 ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 400,
                ],
                'selectors' => [
                    '{{WRAPPER}} .ia-accordion' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Desktop trigger
        $this->add_control(
            'trigger_desktop',
            [
                'label'   => __( 'Desktop Trigger', 'image-accordion' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'hover',
                'options' => [
                    'hover' => __( 'Hover (desktop)', 'image-accordion' ),
                    'click' => __( 'Click (desktop)', 'image-accordion' ),
                ],
                'description' => __(
                    'On mobile, panels always use click. This only affects desktop.',
                    'image-accordion'
                ),
            ]
        );

        // Default active item index
        $this->add_control(
            'default_active_index',
            [
                'label'       => __( 'Default Active Item Index', 'image-accordion' ),
                'type'        => \Elementor\Controls_Manager::NUMBER,
                'min'         => 0,
                'step'        => 1,
                'default'     => 0,
                'description' => __(
                    'Use 0 to keep all collapsed. Use 1 for the first image, 2 for the second, etc.',
                    'image-accordion'
                ),
            ]
        );

        $this->end_controls_section();

        // ---------------------------------------------------------------------
        // CONTENT – Items (Repeater)
        // ---------------------------------------------------------------------
        $this->start_controls_section(
            'section_items',
            [
                'label' => __( 'Images', 'image-accordion' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        // Image
        $repeater->add_control(
            'item_image',
            [
                'label'   => __( 'Image', 'image-accordion' ),
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        // Title (normal)
        $repeater->add_control(
            'item_title',
            [
                'label'       => __( 'Title', 'image-accordion' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => __( 'Accordion Title', 'image-accordion' ),
                'label_block' => true,
            ]
        );

        // Title (hover)
        $repeater->add_control(
            'item_title_hover',
            [
                'label'       => __( 'Title (Hover)', 'image-accordion' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => '',
                'label_block' => true,
            ]
        );

        // Title margin
        $repeater->add_control(
            'item_title_margin',
            [
                'label'      => __( 'Title Margin', 'image-accordion' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default'    => [
                    'top'    => 0,
                    'right'  => 0,
                    'bottom' => 6,
                    'left'   => 0,
                    'unit'   => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ia-title-normal' =>
                        'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Description (normal)
        $repeater->add_control(
            'item_description',
            [
                'label'       => __( 'Description', 'image-accordion' ),
                'type'        => \Elementor\Controls_Manager::TEXTAREA,
                'default'     => __( 'Short description for this image.', 'image-accordion' ),
                'rows'        => 3,
                'label_block' => true,
            ]
        );

        // Description (hover)
        $repeater->add_control(
            'item_description_hover',
            [
                'label'       => __( 'Description (Hover)', 'image-accordion' ),
                'type'        => \Elementor\Controls_Manager::TEXTAREA,
                'default'     => '',
                'rows'        => 2,
                'label_block' => true,
            ]
        );

        // Image link per item
        $repeater->add_control(
            'item_link',
            [
                'label'       => __( 'Image Link', 'image-accordion' ),
                'type'        => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://your-link.com',
                'show_external' => true,
                'default'     => [
                    'url'         => '',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
            ]
        );

        // ---------------------------
        // Per-item state tabs: Normal / Hover
        // ---------------------------
        $repeater->start_controls_tabs( 'ia_item_state_tabs' );

        // ===== Normal tab =====
        $repeater->start_controls_tab(
            'ia_item_state_normal',
            [
                'label' => __( 'Normal', 'image-accordion' ),
            ]
        );

        // Per-item image background color (normal)
        $repeater->add_control(
            'item_image_bg_color',
            [
                'label' => __( 'Image Background Color', 'image-accordion' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ia-image' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Per-item image opacity (0–1) normal
        $repeater->add_control(
            'image_opacity',
            [
                'label'   => __( 'Image Opacity', 'image-accordion' ),
                'type'    => \Elementor\Controls_Manager::SLIDER,
                'range'   => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1,
                        'step' => 0.05,
                    ],
                ],
                'default' => [
                    'size' => 1,
                ],
            ]
        );

        // Per-item vertical alignment (normal)
        $repeater->add_control(
            'item_text_align',
            [
                'label'   => __( 'Text Vertical Alignment', 'image-accordion' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'flex-end',
                'options' => [
                    'flex-start'    => __( 'Start (Top)', 'image-accordion' ),
                    'center'        => __( 'Center', 'image-accordion' ),
                    'flex-end'      => __( 'End (Bottom)', 'image-accordion' ),
                    'space-between' => __( 'Space Between', 'image-accordion' ),
                    'space-around'  => __( 'Space Around', 'image-accordion' ),
                    'space-evenly'  => __( 'Space Evenly', 'image-accordion' ),
                ],
            ]
        );

        // Horizontal alignment (normal)
        $repeater->add_control(
            'item_horizontal_align',
            [
                'label'   => __( 'Text Horizontal Alignment', 'image-accordion' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'center',
                'options' => [
                    'left'   => __( 'Left', 'image-accordion' ),
                    'center' => __( 'Center', 'image-accordion' ),
                    'right'  => __( 'Right', 'image-accordion' ),
                ],
            ]
        );

        // Overlay color (normal)
        $repeater->add_control(
            'item_overlay_color',
            [
                'label'   => __( 'Overlay Color', 'image-accordion' ),
                'type'    => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.55)',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ia-overlay' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $repeater->end_controls_tab();

        // ===== Hover tab =====
        $repeater->start_controls_tab(
            'ia_item_state_hover',
            [
                'label' => __( 'Hover', 'image-accordion' ),
            ]
        );

        // Per-item image background color (hover)
        $repeater->add_control(
            'item_image_bg_color_hover',
            [
                'label' => __( 'Image Background Color (Hover)', 'image-accordion' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}:hover .ia-image' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Per-item image opacity (hover)
        $repeater->add_control(
            'image_opacity_hover',
            [
                'label'   => __( 'Image Opacity (Hover)', 'image-accordion' ),
                'type'    => \Elementor\Controls_Manager::SLIDER,
                'range'   => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1,
                        'step' => 0.05,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}:hover .ia-image' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        // Per-item vertical alignment (hover)
        $repeater->add_control(
            'item_text_align_hover',
            [
                'label'   => __( 'Text Vertical Alignment (Hover)', 'image-accordion' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    ''             => __( 'Inherit', 'image-accordion' ),
                    'flex-start'   => __( 'Start (Top)', 'image-accordion' ),
                    'center'       => __( 'Center', 'image-accordion' ),
                    'flex-end'     => __( 'End (Bottom)', 'image-accordion' ),
                    'space-between'=> __( 'Space Between', 'image-accordion' ),
                    'space-around' => __( 'Space Around', 'image-accordion' ),
                    'space-evenly' => __( 'Space Evenly', 'image-accordion' ),
                ],
            ]
        );

        // Horizontal alignment (hover)
        $repeater->add_control(
            'item_horizontal_align_hover',
            [
                'label'   => __( 'Text Horizontal Alignment (Hover)', 'image-accordion' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    ''       => __( 'Inherit', 'image-accordion' ),
                    'left'   => __( 'Left', 'image-accordion' ),
                    'center' => __( 'Center', 'image-accordion' ),
                    'right'  => __( 'Right', 'image-accordion' ),
                ],
            ]
        );

        // Overlay color (hover)
        $repeater->add_control(
            'item_overlay_color_hover',
            [
                'label' => __( 'Overlay Color (Hover)', 'image-accordion' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}:hover .ia-overlay' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Transition time
        $repeater->add_control(
            'hover_transition_time',
            [
                'label'   => __( 'Transition Time (ms)', 'image-accordion' ),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'default' => 300,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ia-image'   => 'transition: all {{VALUE}}ms ease;',
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ia-overlay' => 'transition: all {{VALUE}}ms ease;',
                ],
            ]
        );

        $repeater->end_controls_tab();
        $repeater->end_controls_tabs();

        // Focus area
        $repeater->add_control(
            'item_focus',
            [
                'label'   => __( 'Image Focus Area', 'image-accordion' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'center',
                'options' => [
                    'center'       => __( 'Center', 'image-accordion' ),
                    'top'          => __( 'Top', 'image-accordion' ),
                    'bottom'       => __( 'Bottom', 'image-accordion' ),
                    'left'         => __( 'Left', 'image-accordion' ),
                    'right'        => __( 'Right', 'image-accordion' ),
                    'top_left'     => __( 'Top Left', 'image-accordion' ),
                    'top_right'    => __( 'Top Right', 'image-accordion' ),
                    'bottom_left'  => __( 'Bottom Left', 'image-accordion' ),
                    'bottom_right' => __( 'Bottom Right', 'image-accordion' ),
                ],
            ]
        );

        // Repeater field
        $this->add_control(
            'items',
            [
                'label'       => __( 'Image Items', 'image-accordion' ),
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'item_title'       => __( 'Item #1', 'image-accordion' ),
                        'item_description' => __( 'Description for item #1', 'image-accordion' ),
                    ],
                    [
                        'item_title'       => __( 'Item #2', 'image-accordion' ),
                        'item_description' => __( 'Description for item #2', 'image-accordion' ),
                    ],
                    [
                        'item_title'       => __( 'Item #3', 'image-accordion' ),
                        'item_description' => __( 'Description for item #3', 'image-accordion' ),
                    ],
                ],
                'title_field' => '{{{ item_title }}}',
            ]
        );

        $this->end_controls_section();

        // ---------------------------------------------------------------------
        // STYLE – Accordion Layout
        // ---------------------------------------------------------------------
        $this->start_controls_section(
            'section_style_layout',
            [
                'label' => __( 'Accordion Layout', 'image-accordion' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Image background color (global)
        $this->add_control(
            'image_bg_color',
            [
                'label' => __( 'Image Background Color', 'image-accordion' ),
                'type'  => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .ia-accordion .ia-image' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Gap
        $this->add_control(
            'accordion_gap',
            [
                'label' => __( 'Gap Between Items', 'image-accordion' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 40,
                    ],
                ],
                'default' => [
                    'size' => 8,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ia-accordion' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Border radius
        $this->add_control(
            'accordion_radius',
            [
                'label' => __( 'Border Radius', 'image-accordion' ),
                'type'  => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ia-accordion .ia-item-inner' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ia-accordion .ia-image'       => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ---------------------------------------------------------------------
        // STYLE – Title
        // ---------------------------------------------------------------------
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __( 'Title', 'image-accordion' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __( 'Color', 'image-accordion' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ia-accordion .ia-title' => 'color: {{VALUE}};',
                ],
                'default' => '#ffffff',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .ia-accordion .ia-title',
            ]
        );

        $this->end_controls_section();

        // ---------------------------------------------------------------------
        // STYLE – Description
        // ---------------------------------------------------------------------
        $this->start_controls_section(
            'section_style_desc',
            [
                'label' => __( 'Description', 'image-accordion' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label'     => __( 'Color', 'image-accordion' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ia-accordion .ia-desc' => 'color: {{VALUE}};',
                ],
                'default' => '#f0f0f0',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'desc_typography',
                'selector' => '{{WRAPPER}} .ia-accordion .ia-desc',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Convert hex or rgba string + opacity (0–100) to rgba string.
     * (Kept for backward compatibility, currently not used.)
     */
    protected function build_overlay_rgba( $color, $opacity_slider ) {
        if ( empty( $color ) ) {
            return 'rgba(0,0,0,0.55)';
        }

        $opacity = 0.55;
        if ( is_array( $opacity_slider ) && isset( $opacity_slider['size'] ) ) {
            $opacity = max( 0, min( 100, floatval( $opacity_slider['size'] ) ) ) / 100;
        }

        // If already rgba, just replace alpha
        if ( stripos( $color, 'rgba' ) === 0 ) {
            return preg_replace(
                '/rgba\s*\((\s*\d+\s*,\s*\d+\s*,\s*\d+\s*,\s*)([0-9.]+)\s*\)/i',
                'rgba($1' . $opacity . ')',
                $color
            );
        }

        // If rgb(...)
        if ( stripos( $color, 'rgb' ) === 0 ) {
            return preg_replace(
                '/rgb\s*\((.*?)\)/i',
                'rgba($1,' . $opacity . ')',
                $color
            );
        }

        // Assume hex
        $color = ltrim( $color, '#' );
        if ( strlen( $color ) === 3 ) {
            $r = hexdec( str_repeat( substr( $color, 0, 1 ), 2 ) );
            $g = hexdec( str_repeat( substr( $color, 1, 1 ), 2 ) );
            $b = hexdec( str_repeat( substr( $color, 2, 1 ), 2 ) );
        } elseif ( strlen( $color ) >= 6 ) {
            $r = hexdec( substr( $color, 0, 2 ) );
            $g = hexdec( substr( $color, 2, 2 ) );
            $b = hexdec( substr( $color, 4, 2 ) );
        } else {
            $r = $g = $b = 0;
        }

        return sprintf( 'rgba(%d,%d,%d,%.2f)', $r, $g, $b, $opacity );
    }

    /**
     * Frontend HTML
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $items = $settings['items'] ?? [];

        $direction_desktop = $settings['direction'] ?? 'horizontal';
        $direction_tablet  = $settings['direction_tablet'] ?? $direction_desktop;
        $direction_mobile  = $settings['direction_mobile'] ?? $direction_tablet;

        $trigger_desktop = $settings['trigger_desktop'] ?? 'hover';
        $default_index   = isset( $settings['default_active_index'] ) ? intval( $settings['default_active_index'] ) : 0;

        if ( empty( $items ) ) {
            echo '<div class="ia-accordion ia-empty">No items defined.</div>';
            if ( function_exists( 'ia_log' ) ) {
                ia_log( 'Image Accordion rendered with no items.' );
            }
            return;
        }

        // Base direction class (for backward compatibility with existing CSS)
        $base_direction_class = ( $direction_desktop === 'vertical' ) ? 'ia-vertical' : 'ia-horizontal';

        $wrapper_classes = [
            'ia-accordion',
            $base_direction_class,
            'ia-desktop-' . $direction_desktop,
            'ia-tablet-'  . $direction_tablet,
            'ia-mobile-'  . $direction_mobile,
        ];

        $this->add_render_attribute( 'ia-wrapper', 'class', implode( ' ', $wrapper_classes ) );
        $this->add_render_attribute( 'ia-wrapper', 'data-direction', esc_attr( $direction_desktop ) );
        $this->add_render_attribute( 'ia-wrapper', 'data-trigger-desktop', esc_attr( $trigger_desktop ) );
        $this->add_render_attribute( 'ia-wrapper', 'data-default-index', esc_attr( $default_index ) );

        ?>
        <div <?php echo $this->get_render_attribute_string( 'ia-wrapper' ); ?>>

            <?php
            $i = 0;
            foreach ( $items as $item ) {
                $i++;
                $is_active = ( $default_index > 0 && $default_index === $i );

                $item_classes   = [ 'ia-item' ];
                if ( $is_active ) {
                    $item_classes[] = 'is-active';
                }

                // Per-item vertical alignment class (normal)
                $vertical_align = ! empty( $item['item_text_align'] ) ? $item['item_text_align'] : 'flex-end';
                $item_classes[] = 'ia-align-' . $vertical_align;

                // Per-item vertical alignment class (hover)
                if ( ! empty( $item['item_text_align_hover'] ) ) {
                    $item_classes[] = 'ia-align-hover-' . $item['item_text_align_hover'];
                }

                // Per-item horizontal alignment class (normal)
                $horizontal_align = ! empty( $item['item_horizontal_align'] ) ? $item['item_horizontal_align'] : 'center';
                $item_classes[]   = 'ia-text-' . $horizontal_align;

                // Per-item horizontal alignment class (hover)
                if ( ! empty( $item['item_horizontal_align_hover'] ) ) {
                    $item_classes[] = 'ia-text-hover-' . $item['item_horizontal_align_hover'];
                }

                // Has hover title/description flags
                $has_title_hover = ! empty( $item['item_title_hover'] );
                $has_desc_hover  = ! empty( $item['item_description_hover'] );

                if ( $has_title_hover ) {
                    $item_classes[] = 'has-title-hover';
                }
                if ( $has_desc_hover ) {
                    $item_classes[] = 'has-desc-hover';
                }

                // Elementor repeater class for selectors (title margin etc.)
                if ( ! empty( $item['_id'] ) ) {
                    $item_classes[] = 'elementor-repeater-item-' . $item['_id'];
                }

                // Focus class
                $focus          = ! empty( $item['item_focus'] ) ? $item['item_focus'] : 'center';
                $item_classes[] = 'ia-focus-' . $focus;

                $image_url = '';
                if ( ! empty( $item['item_image']['url'] ) ) {
                    $image_url = $item['item_image']['url'];
                }

                $image_opacity = isset( $item['image_opacity']['size'] ) ? $item['image_opacity']['size'] : 1;

                // Link attributes per item
                $link_attributes = '';
                if ( ! empty( $item['item_link']['url'] ) ) {
                    $link_key = 'ia-link-' . $i;
                    $this->add_link_attributes( $link_key, $item['item_link'] );
                    $link_attributes = $this->get_render_attribute_string( $link_key );
                }
                ?>
                <div class="<?php echo esc_attr( implode( ' ', $item_classes ) ); ?>" data-index="<?php echo esc_attr( $i ); ?>">
                    <?php if ( $link_attributes ) : ?>
                        <a <?php echo $link_attributes; ?> class="ia-item-link">
                    <?php endif; ?>

                    <div class="ia-item-inner">
                        <?php if ( $image_url ) : ?>
                            <div class="ia-image"
                                 style="
                                    --ia-image-opacity: <?php echo esc_attr( $image_opacity ); ?>;
                                    background-image:url('<?php echo esc_url( $image_url ); ?>');
                                 ">
                                <div class="ia-overlay">

                                    <?php if ( ! empty( $item['item_title'] ) ) : ?>
                                        <div class="ia-title ia-title-normal"><?php echo esc_html( $item['item_title'] ); ?></div>
                                    <?php endif; ?>

                                    <?php if ( $has_title_hover ) : ?>
                                        <div class="ia-title ia-title-hover"><?php echo esc_html( $item['item_title_hover'] ); ?></div>
                                    <?php endif; ?>

                                    <?php if ( ! empty( $item['item_description'] ) ) : ?>
                                        <div class="ia-desc ia-desc-normal"><?php echo esc_html( $item['item_description'] ); ?></div>
                                    <?php endif; ?>

                                    <?php if ( $has_desc_hover ) : ?>
                                        <div class="ia-desc ia-desc-hover"><?php echo esc_html( $item['item_description_hover'] ); ?></div>
                                    <?php endif; ?>

                                </div>
                            </div>
                        <?php else : ?>
                            <div class="ia-image">
                                <div class="ia-overlay">

                                    <?php if ( ! empty( $item['item_title'] ) ) : ?>
                                        <div class="ia-title ia-title-normal"><?php echo esc_html( $item['item_title'] ); ?></div>
                                    <?php endif; ?>

                                    <?php if ( $has_title_hover ) : ?>
                                        <div class="ia-title ia-title-hover"><?php echo esc_html( $item['item_title_hover'] ); ?></div>
                                    <?php endif; ?>

                                    <?php if ( ! empty( $item['item_description'] ) ) : ?>
                                        <div class="ia-desc ia-desc-normal"><?php echo esc_html( $item['item_description'] ); ?></div>
                                    <?php endif; ?>

                                    <?php if ( $has_desc_hover ) : ?>
                                        <div class="ia-desc ia-desc-hover"><?php echo esc_html( $item['item_description_hover'] ); ?></div>
                                    <?php endif; ?>

                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if ( $link_attributes ) : ?>
                        </a>
                    <?php endif; ?>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
    }
}
