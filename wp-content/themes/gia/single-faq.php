<?php
get_header();
?>

<?php get_template_part( 'template-parts/faq-banner' ); ?>

<main id="site-content" class="gia-faq-page" role="main">
	<?php
	while ( have_posts() ) :
		the_post();
		$gia_faq_term          = gia_faq_get_post_term( get_the_ID() );
		$gia_faq_root_term     = $gia_faq_term ? gia_faq_get_root_term( $gia_faq_term ) : null;
		$gia_faq_active_term_id = $gia_faq_term ? (int) $gia_faq_term->term_id : 0;
		$gia_faq_active_post_id = get_the_ID();
		?>
		<div class="container gia-faq-layout">
			<?php
			get_template_part(
				'template-parts/faq-sidebar',
				null,
				array(
					'root_term'       => $gia_faq_root_term,
					'active_term_id'  => $gia_faq_active_term_id,
					'active_post_id'  => $gia_faq_active_post_id,
				)
			);
			?>

			<article <?php post_class( 'gia-faq-content gia-faq-content--single' ); ?>>
				<h1 class="gia-faq-content__title"><?php the_title(); ?></h1>
				<div class="gia-faq-answer__content">
					<?php the_content(); ?>
				</div>
			</article>
		</div>
	<?php endwhile; ?>
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
