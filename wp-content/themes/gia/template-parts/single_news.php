<?php
get_header();
?>

<main id="site-content" role="main" class="news-page">
	<div class="container">
		
        <article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
        
            <header class="single-header">
                <div class="back">
                    <a href="javascript:history.back()" class="btn-back"><i class="fal fa-arrow-left"></i> &nbsp <?php echo __('Back', 'atap' );?></a>
                </div>
            </header>
            <div class="article-top">                     
                <?php 
                    foreach((get_the_category()) as $category){ 
                        if($category->parent != 0){
                            $color = get_field('color', $category);
                            ?> 
                            <span class="post-cate" style="background: <?php echo $color?>;"><?php echo esc_html( $category->name );?></span>
                            <?php   
                        }
                    }
                ?>
                <span class="post-date"><?php echo get_the_date( 'd M Y' );?></span>
            </div>   
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
            
            <div class="post-inner">
                <div class="entry-content">
                    <?php the_content( __( 'Continue reading', 'atap' ) );?>
                </div><!-- .entry-content -->
            </div><!-- .post-inner -->
        
            
        </article><!-- .post -->
	
	</div>
</main><!-- #site-content -->



<?php get_footer(); ?>
