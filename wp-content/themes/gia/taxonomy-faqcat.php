<?php
get_header();

$gia_faq_term      = get_queried_object();
$gia_faq_root_term = gia_faq_get_root_term( $gia_faq_term );
$gia_faq_active_term_id = (int) $gia_faq_term->term_id;
$gia_faq_child_terms = gia_faq_get_child_terms( $gia_faq_root_term->term_id );
?>

<?php get_template_part( 'template-parts/faq-banner' ); ?>

<main id="site-content" class="gia-faq-page" role="main">
	<div class="container gia-faq-layout">
		<?php
		get_template_part(
			'template-parts/faq-sidebar',
			null,
			array(
				'root_term'       => $gia_faq_root_term,
				'active_term_id'  => $gia_faq_active_term_id,
			)
		);
		?>

		<section class="gia-faq-content" aria-labelledby="gia-faq-page-title">
			<h1 id="gia-faq-page-title" class="gia-faq-content__title">
				<?php echo esc_html( $gia_faq_term->name ); ?>
			</h1>

			<?php
			$gia_faq_posts = gia_faq_get_posts_for_term( $gia_faq_term->term_id );
			if ( $gia_faq_posts->have_posts() ) :
				?>
				<p class="gia-faq-content__intro"><?php esc_html_e( 'Choose a topic to browse frequently asked questions.', 'gia' ); ?></p>
				<ul class="gia-faq-topic-list">
					<?php
					while ( $gia_faq_posts->have_posts() ) :
						$gia_faq_posts->the_post();
						?>
						<li>
							<a href="<?php the_permalink(); ?>">
								<?php the_title(); ?>
								<span aria-hidden="true">&rarr;</span>
							</a>
						</li>
					<?php endwhile; ?>
				</ul>
				<?php
				wp_reset_postdata();
			elseif ( $gia_faq_child_terms ) :
				?>
				<p class="gia-faq-content__intro"><?php esc_html_e( 'Choose a topic to browse frequently asked questions.', 'gia' ); ?></p>
				<ul class="gia-faq-topic-list">
					<?php foreach ( $gia_faq_child_terms as $gia_faq_child_term ) : ?>
						<li>
							<a href="<?php echo esc_url( get_term_link( $gia_faq_child_term ) ); ?>">
								<?php echo esc_html( $gia_faq_child_term->name ); ?>
								<span aria-hidden="true">&rarr;</span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php else : ?>
				<p class="gia-faq-content__intro"><?php esc_html_e( 'No FAQs are available in this category yet.', 'gia' ); ?></p>
			<?php endif; ?>
		</section>
	</div>
</main>

<div class="gia-insurance-single__breadcrumbs">
    <div class="container">
        <?php
        if ( shortcode_exists( 'apat_breadcrumbs' ) ) {
            echo do_shortcode( '[apat_breadcrumbs]' ); 
        }
        ?>
    </div>
    
</div>
<?php get_footer(); ?>
