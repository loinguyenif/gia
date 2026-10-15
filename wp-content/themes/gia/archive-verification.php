<?php
get_header();
  
global $post;
?>

<div class="banner-top" style="background: #2E4B89 url('http://appdev.ifdemo.com/atap/wp-content/uploads/2020/11/banner-top-product.png');">
    <div class="container">
        <?php echo apat_breadcrumbs(); ?>
        <h2 class="page-title"><?php echo get_the_archive_title(); ?></h2>
     </div>
</div>

<main id="site-content" role="main" class="verification-page">    
    <div class="container">
        <div class="page-content pt-5 pb-5 my-5">
        	<div class="row">

        		<?php while ( have_posts() ) : the_post(); ?>
	                <div class="col-12 col-sm-8">
	                	<h5 class="verification"><?php the_title();?></h5>
	                </div>
	                <div class="col-12 col-sm-4 mb-4">
	                	<?php if( get_field('operation_verification_link') ): ?>
							<a class="btn-view" href="<?php the_field('operation_verification_link');?>"><?php echo __('View Details', 'atap' );?> <i class="fal fa-arrow-right"></i></a>
						<?php endif; ?>
	                </div>
				<?php endwhile; ?>	

        	</div>
        </div>
    </div>
</main>
<?php
get_footer();
