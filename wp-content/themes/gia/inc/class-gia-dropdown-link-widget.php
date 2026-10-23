<?php
/**
 * Elementor widget for a button that reveals a list of links.
 */
class Gia_Dropdown_Link_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'gia-dropdown-link';
    }

    public function get_title() {
        return esc_html__( 'Dropdown Link', 'gia' );
    }

    public function get_icon() {
        return 'eicon-editor-list-ul';
    }

    public function get_categories() {
        return array( 'basic' );
    }

    public function get_keywords() {
        return array( 'dropdown', 'links', 'menu' );
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            array(
                'label' => esc_html__( 'Dropdown Link', 'gia' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'button_text',
            array(
                'label'       => esc_html__( 'Button Text', 'gia' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => esc_html__( 'I would like to', 'gia' ),
                'placeholder' => esc_html__( 'Enter button text', 'gia' ),
                'label_block' => true,
            )
        );

        $links = new \Elementor\Repeater();
        $links->add_control(
            'text',
            array(
                'label'       => esc_html__( 'Link Text', 'gia' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => esc_html__( 'Link', 'gia' ),
                'label_block' => true,
            )
        );
        $links->add_control(
            'link',
            array(
                'label'       => esc_html__( 'Link URL', 'gia' ),
                'type'        => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
                'default'     => array(
                    'url' => '',
                ),
                'show_external' => true,
            )
        );

        $this->add_control(
            'links',
            array(
                'label'       => esc_html__( 'Links', 'gia' ),
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $links->get_controls(),
                'title_field' => '{{{ text }}}',
            )
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings    = $this->get_settings_for_display();
        $button_text = isset( $settings['button_text'] ) ? trim( $settings['button_text'] ) : '';
        $link_items  = isset( $settings['links'] ) && is_array( $settings['links'] ) ? $settings['links'] : array();
        $links_html  = '';

        foreach ( $link_items as $item ) {
            $text = isset( $item['text'] ) ? trim( wp_strip_all_tags( $item['text'] ) ) : '';
            $link = isset( $item['link'] ) && is_array( $item['link'] ) ? $item['link'] : array();
            $url  = isset( $link['url'] ) ? trim( $link['url'] ) : '';

            if ( '' === $text || '' === $url ) {
                continue;
            }

            $target = ! empty( $link['is_external'] ) ? ' target="_blank"' : '';
            $rel    = array();

            if ( ! empty( $link['is_external'] ) ) {
                $rel[] = 'noopener';
                $rel[] = 'noreferrer';
            }

            if ( ! empty( $link['nofollow'] ) ) {
                $rel[] = 'nofollow';
            }

            $rel_attribute = ! empty( $rel ) ? ' rel="' . esc_attr( implode( ' ', $rel ) ) . '"' : '';
            $links_html   .= '<li class="gia-dropdown-link__item"><a href="' . esc_url( $url ) . '"'
                . $target . $rel_attribute . '>' . esc_html( $text ) . '</a></li>';
        }

        if ( '' === $links_html ) {
            return;
        }

        if ( '' === $button_text ) {
            $button_text = esc_html__( 'I would like to', 'gia' );
        }

        $widget_id = 'gia-dropdown-link-' . sanitize_html_class( $this->get_id() );
        $menu_id   = $widget_id . '-menu';

        echo '<div class="gia-dropdown-link" data-gia-dropdown-link>'
            . '<button class="gia-dropdown-link__toggle" type="button" id="' . esc_attr( $widget_id ) . '"'
            . ' aria-expanded="false" aria-controls="' . esc_attr( $menu_id ) . '">'
            . '<span class="gia-dropdown-link__label">' . esc_html( $button_text ) . '</span>'
            . '<span class="gia-dropdown-link__chevron" aria-hidden="true"></span>'
            . '</button>'
            . '<ul class="gia-dropdown-link__menu" id="' . esc_attr( $menu_id ) . '" hidden>'
            . $links_html
            . '</ul></div>';
    }
}
