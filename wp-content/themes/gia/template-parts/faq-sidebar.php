<?php
$gia_faq_template_args = isset( $args ) && is_array( $args ) ? $args : array();
$gia_faq_root_term = isset( $gia_faq_template_args['root_term'] ) ? $gia_faq_template_args['root_term'] : null;
$gia_faq_active_term_id = isset( $gia_faq_template_args['active_term_id'] ) ? (int) $gia_faq_template_args['active_term_id'] : 0;
$gia_faq_active_post_id = isset( $gia_faq_template_args['active_post_id'] ) ? (int) $gia_faq_template_args['active_post_id'] : 0;

if ( ! $gia_faq_root_term instanceof WP_Term ) {
	return;
}

$gia_faq_child_terms = gia_faq_get_child_terms( $gia_faq_root_term->term_id );
?>
<aside class="gia-faq-sidebar" aria-label="<?php esc_attr_e( 'FAQ categories', 'gia' ); ?>">
	<a class="gia-faq-sidebar__root" href="<?php echo esc_url( get_term_link( $gia_faq_root_term ) ); ?>">
		<?php echo esc_html( $gia_faq_root_term->name ); ?>
	</a>

	<?php if ( $gia_faq_child_terms ) : ?>
		<?php foreach ( $gia_faq_child_terms as $gia_faq_child_term ) : ?>
			<?php $gia_faq_posts = gia_faq_get_posts_for_term( $gia_faq_child_term->term_id ); ?>
			<section class="gia-faq-sidebar__group">
				<a class="<?php echo $gia_faq_active_term_id === (int) $gia_faq_child_term->term_id ? ' is-active' : ''; ?>" href="<?php echo esc_url( get_term_link( $gia_faq_child_term ) ); ?>">
					<strong class="gia-faq-sidebar__group-title">
						<?php echo esc_html( $gia_faq_child_term->name ); ?>
					</strong>
				</a>

				<?php if ( $gia_faq_posts->have_posts() ) : ?>
					<ul class="gia-faq-sidebar__items">
						<?php
						while ( $gia_faq_posts->have_posts() ) :
							$gia_faq_posts->the_post();
							$is_current = $gia_faq_active_post_id === get_the_ID();
							?>
							<li>
								<a href="<?php the_permalink(); ?>"<?php echo $is_current ? ' class="is-active" aria-current="page"' : ''; ?>>
									<?php the_title(); ?>
								</a>
							</li>
						<?php endwhile; ?>
					</ul>
				<?php endif; ?>
				<?php wp_reset_postdata(); ?>
			</section>
		<?php endforeach; ?>
	<?php else : ?>
		<?php $gia_faq_posts = gia_faq_get_posts_for_term( $gia_faq_root_term->term_id ); ?>
		<?php if ( $gia_faq_posts->have_posts() ) : ?>
			<ul class="gia-faq-sidebar__items gia-faq-sidebar__items--root">
				<?php
				while ( $gia_faq_posts->have_posts() ) :
					$gia_faq_posts->the_post();
					$is_current = $gia_faq_active_post_id === get_the_ID();
					?>
					<li>
						<a href="<?php the_permalink(); ?>"<?php echo $is_current ? ' class="is-active" aria-current="page"' : ''; ?>>
							<?php the_title(); ?>
						</a>
					</li>
				<?php endwhile; ?>
			</ul>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
	<?php endif; ?>
</aside>
