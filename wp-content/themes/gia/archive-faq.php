<?php
get_header();

$gia_faq_root_terms = get_terms(
	array(
		'taxonomy'   => 'faqcat',
		'parent'     => 0,
		'hide_empty' => false,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);
?>

<?php get_template_part( 'template-parts/faq-banner' ); ?>

<main id="site-content" class="gia-faq-page" role="main">
	<div class="container gia-faq-layout gia-faq-layout--archive">
		<section class="gia-faq-content" aria-labelledby="gia-faq-page-title">
			<h1 id="gia-faq-page-title" class="gia-faq-content__title"><?php esc_html_e( 'Frequently Asked Questions', 'gia' ); ?></h1>
			<?php if ( ! is_wp_error( $gia_faq_root_terms ) && $gia_faq_root_terms ) : ?>
				<ul class="gia-faq-topic-list">
					<?php foreach ( $gia_faq_root_terms as $gia_faq_root_term ) : ?>
						<li>
							<a href="<?php echo esc_url( get_term_link( $gia_faq_root_term ) ); ?>">
								<?php echo esc_html( $gia_faq_root_term->name ); ?>
								<span aria-hidden="true">&rarr;</span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php else : ?>
				<p class="gia-faq-content__intro"><?php esc_html_e( 'No FAQ categories are available yet.', 'gia' ); ?></p>
			<?php endif; ?>
		</section>
	</div>
</main>

<?php get_footer(); ?>
