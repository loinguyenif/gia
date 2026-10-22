<?php
/**
 * Menu walker for the global header navigation.
 */
class Gia_Header_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Store menu items by depth so start_lvl can identify its parent.
	 *
	 * @var array
	 */
	private $items_by_depth = array();

	/**
	 * Store the current parent menu item.
	 *
	 * @param string   $output Used to append additional content.
	 * @param WP_Post  $data_object Menu item data.
	 * @param int      $depth Depth of the item.
	 * @param stdClass $args Menu arguments.
	 * @param int      $current_object_id Current object ID.
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		$this->items_by_depth[ $depth ] = $data_object;

		if ( $this->is_hidden_top_level( $depth, $args ) ) {
			return;
		}

		parent::start_el( $output, $data_object, $depth, $args, $current_object_id );

		if ( in_array( 'menu-item-has-children', (array) $data_object->classes, true ) ) {
			$is_mobile = isset( $args->menu_id ) && 'gia-nav-mobile-menu' === $args->menu_id;
			$button_class = 'gia-nav__submenu-toggle' . ( $is_mobile ? ' gia-nav__submenu-toggle--mobile' : '' );
			$button_icon = $is_mobile
				? '<span aria-hidden="true">&#8964;</span>'
				: '<i class="fal fa-angle-down" aria-hidden="true"></i>';
			$button_label = sprintf(
				/* translators: %s: menu item title. */
				__( 'Toggle submenu for %s', 'gia' ),
				wp_strip_all_tags( $data_object->title )
			);
			$submenu_id = $this->get_submenu_id( $args, $data_object );

			$output .= sprintf(
				'<button class="%1$s" type="button" aria-expanded="false" aria-label="%2$s" aria-controls="%3$s">%4$s</button>',
				esc_attr( $button_class ),
				esc_attr( $button_label ),
				esc_attr( $submenu_id ),
				$button_icon
			);
		}
	}

	/**
	 * Close a menu item unless its top-level wrapper is intentionally hidden.
	 *
	 * @param string   $output Used to append additional content.
	 * @param WP_Post  $data_object Menu item data.
	 * @param int      $depth Depth of the item.
	 * @param stdClass $args Menu arguments.
	 */
	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {
		if ( ! $this->is_hidden_top_level( $depth, $args ) ) {
			parent::end_el( $output, $data_object, $depth, $args );
		}
	}

	/**
	 * Start a submenu, optionally wrapping the top level in a mega panel.
	 *
	 * @param string   $output Used to append additional content.
	 * @param int      $depth Depth of the submenu.
	 * @param stdClass $args Menu arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( $this->is_hidden_top_level( $depth, $args ) ) {
			return;
		}

		if ( 0 === $depth && ! empty( $args->gia_header_mega ) ) {
			$parent = isset( $this->items_by_depth[ $depth ] ) ? $this->items_by_depth[ $depth ] : null;
			$parent_slug = $parent ? sanitize_title( $parent->title ) : '';
			$panel_class = in_array( $parent_slug, array( 'insurance', 'about' ), true ) ? ' gia-nav-mega--' . $parent_slug : '';
			$linkedin_url = get_theme_mod( 'gia_header_linkedin_url', 'https://www.linkedin.com/company/giasingapore' );
			$submenu_id = $parent ? $this->get_submenu_id( $args, $parent ) : '';

			$output .= '<div id="' . esc_attr( $submenu_id ) . '" class="gia-nav-mega' . esc_attr( $panel_class ) . '"><div class="gia-nav-mega__inner">';
			$output .= '<div class="gia-nav-mega__intro"><h2>' . esc_html__( 'GIA Singapore', 'gia' ) . '</h2>';
			if ( $linkedin_url ) {
				$output .= '<a href="' . esc_url( $linkedin_url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Visit GIA on LinkedIn', 'gia' ) . '</a>';
			}
			$output .= '</div>';
			$output .= '<ul class="sub-menu gia-nav-mega__links">';
		} else {
			$parent = isset( $this->items_by_depth[ $depth ] ) ? $this->items_by_depth[ $depth ] : null;
			$submenu_id = $parent ? $this->get_submenu_id( $args, $parent ) : '';

			$output .= '<ul id="' . esc_attr( $submenu_id ) . '" class="sub-menu">';
		}
	}

	/**
	 * Close the submenu and optional mega panel wrapper.
	 *
	 * @param string   $output Used to append additional content.
	 * @param int      $depth Depth of the submenu.
	 * @param stdClass $args Menu arguments.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( $this->is_hidden_top_level( $depth, $args ) ) {
			return;
		}

		$output .= '</ul>';

		if ( 0 === $depth && ! empty( $args->gia_header_mega ) ) {
			$output .= '</div></div>';
		}
	}

	/**
	 * Check whether a top-level menu item should be omitted from the output.
	 *
	 * @param int      $depth Menu item depth.
	 * @param stdClass $args Menu arguments.
	 * @return bool
	 */
	private function is_hidden_top_level( $depth, $args ) {
		return 0 === $depth && ! empty( $args->gia_header_skip_top_level );
	}

	/**
	 * Create a stable submenu ID tied to its menu and parent item.
	 *
	 * @param stdClass $args Menu arguments.
	 * @param WP_Post  $item Parent menu item.
	 * @return string
	 */
	private function get_submenu_id( $args, $item ) {
		$menu_id = ! empty( $args->menu_id ) ? sanitize_html_class( $args->menu_id ) : 'gia-nav-menu';

		return $menu_id . '-submenu-' . absint( $item->ID );
	}
}
