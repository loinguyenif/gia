<?php
/**
 * Helpers for the FAQ archive and category navigation.
 */

/**
 * Return the top-level FAQ category for a category term.
 *
 * @param WP_Term $term FAQ category.
 * @return WP_Term
 */
function gia_faq_get_root_term( $term ) {
	if ( ! $term instanceof WP_Term || 'faqcat' !== $term->taxonomy ) {
		return $term;
	}

	$ancestors = get_ancestors( $term->term_id, 'faqcat', 'taxonomy' );

	if ( empty( $ancestors ) ) {
		return $term;
	}

	$root_term = get_term( end( $ancestors ), 'faqcat' );

	return $root_term instanceof WP_Term ? $root_term : $term;
}

/**
 * Return the most specific FAQ category assigned to a post.
 *
 * @param int $post_id FAQ post ID.
 * @return WP_Term|null
 */
function gia_faq_get_post_term( $post_id ) {
	$terms = get_the_terms( $post_id, 'faqcat' );

	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return null;
	}

	usort(
		$terms,
		function ( $first, $second ) {
			return count( get_ancestors( $second->term_id, 'faqcat', 'taxonomy' ) ) - count( get_ancestors( $first->term_id, 'faqcat', 'taxonomy' ) );
		}
	);

	return $terms[0];
}

/**
 * Query published FAQs assigned directly to a category.
 *
 * @param int $term_id FAQ category ID.
 * @return WP_Query
 */
function gia_faq_get_posts_for_term( $term_id ) {
	return new WP_Query(
		array(
			'post_type'              => 'faq',
			'post_status'            => 'publish',
			'posts_per_page'         => -1,
			'orderby'                => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'tax_query'              => array(
				array(
					'taxonomy'         => 'faqcat',
					'field'            => 'term_id',
					'terms'            => (int) $term_id,
					'include_children' => false,
				),
			),
		)
	);
}

/**
 * Load FAQ categories directly below a root category.
 *
 * @param int $root_id Root category ID.
 * @return WP_Term[]
 */
function gia_faq_get_child_terms( $root_id ) {
	$terms = get_terms(
		array(
			'taxonomy'   => 'faqcat',
			'parent'     => (int) $root_id,
			'hide_empty' => false,
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);

	return is_wp_error( $terms ) ? array() : $terms;
}
