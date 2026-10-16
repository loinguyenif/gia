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

			$output .= '<div class="gia-nav-mega' . esc_attr( $panel_class ) . '"><div class="gia-nav-mega__inner">';
			$output .= '<div class="gia-nav-mega__intro"><h2>' . esc_html__( 'GIA Singapore', 'gia' ) . '</h2>';
			if ( $linkedin_url ) {
				$output .= '<a href="' . esc_url( $linkedin_url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Visit GIA on LinkedIn', 'gia' ) . '</a>';
			}
			$output .= '</div>';
			$output .= '<ul class="sub-menu gia-nav-mega__links">';
		} else {
			$output .= '<ul class="sub-menu">';
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
}
