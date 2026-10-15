<?php
/**
* Template Name: Thank you for quotation
*
* @package WordPress
* @subpackage GIA
* @since GIA
*/


get_header();
?>
<main id="site-content" role="main" class="news-page">
	<div class="container">
		
        <article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
            <div class="post-inner">
                <div class="entry-content">
                    <?php the_content( __( 'Continue reading', 'atap' ) );?>
                    <?php 
                    if(isset($_SESSION['submission_pdf'])){
                        echo $_SESSION['submission_pdf'];
                    }
                    ?>
                </div><!-- .entry-content -->
            </div><!-- .post-inner -->
        </article><!-- .post -->
	
	</div>
</main><!-- #site-content -->
<?php
get_footer();
