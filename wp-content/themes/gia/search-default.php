<?php get_header(); ?>


<div class="banner-top" style="background: #2E4B89 url('http://appdev.ifdemo.com/gia/wp-content/uploads/2020/10/banner_news.jpg');">
    <div class="container">
        <ul id="breadcrumbs">
        	<a href="<?php echo get_bloginfo('url')?>">Home</a> <span class="delimiter"> &nbsp; / &nbsp; </span> <span class="current">Search results</span>
        </ul>
        <h2 class="page-title">SEARCH RESULTS FOR "<?php echo get_search_query();?>"</h2>
     </div>
</div>

<main id="site-content" role="main" class="search-page my-5 pt-5 pb-5">
    <div class="container">
        <div class="row">
        	<?php if ( have_posts() ) : 
				while ( have_posts() ) : the_post(); ?>
		        	<div class="col-6 col-md-4">
		                <div class="newbox" data-mh="news-group">
		                    <div class="post-date"><?php echo get_the_date('d F Y') ?></div>
		                    <h4 class="post-title"><?php echo get_the_title(); ?></h4>
		                    <div class="post-bottom">
		                        <a class="more-link" href="<?php echo get_permalink(); ?>">Read More &nbsp <i class="fal fa-arrow-right"></i></a>
		                    </div>
		                </div>
		             </div>
		    	<?php
            	 endwhile;
             	wp_reset_postdata();
             	else : ?>
             		 <h2><?php _e('Sorry no results found!'); ?></h2>
             	<?php endif; ?>	
             	        
        </div>
    </div>
</main>

<?php get_footer(); ?>