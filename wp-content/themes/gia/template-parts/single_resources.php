<?php
get_header();
$term = $cat_name = '';
foreach((get_the_category()) as $category){
    $term = $category;
    $cat_name = $term->name;
}
$full_image = get_field('full_image', $term);
?>
<style>
    .current{display: none;}
    #breadcrumbs span:nth-child(6){display: none;}
</style>
<div class="banner-top" style="background-image: url('<?php echo $full_image; ?>');">
    <div class="container">
        <?php echo apat_breadcrumbs(); ?>
        <h2 class="page-title"><?php echo $cat_name;?></h2>
     </div>
</div>

<div id="site-content-resources" role="main" style="margin-top: 90px;">
	<div class="container">
		
        <article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
        
            <header class="single-header">
                <div class="back">
                    <a href="javascript:history.back()" class="btn-back"><i class="fal fa-arrow-left"></i> &nbsp <?php echo __('Back', 'atap' ) ;?></a>
                </div>
            </header>
            <br />
            <h3 class="post-title"><?php the_title();?></h3>
            
            <ul class="social-share">
                <li><a><?php echo __('Share:', 'atap' );?> </a></li>
                <li>
                    <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                </li>
                <li>
                    <a target="_blank" href="https://twitter.com/intent/tweet?text=<?php the_title(); ?>&url=<?php the_permalink(); ?>&via=<?php echo get_the_author_meta( 'twitter', 1 ); ?>">
                        <i class="fab fa-twitter"></i>
                    </a>
                </li>
                <li>
                    <a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>&title=<?php the_title(); ?>&source=<?php the_title(); ?>">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </li>
            </ul><!-- share post-->
            
        </article><!-- .post -->
	
	</div>
</div><!-- #site-content -->

<?php the_content( __( 'Continue reading', 'atap' ) );?>


<?php get_footer(); ?>
