<?php
get_header();
  
global $post;
?>

<div class="banner-top" style="background: #2E4B89 url('<?php echo site_url()?>/wp-content/uploads/2020/11/banner-top-product.png');">
    <div class="container">
        <ul id="breadcrumbs">
            <a href="<?php echo site_url()?>"><?php echo __('Home', 'atap' );?></a> <span class="delimiter"> &nbsp; / &nbsp; </span> 
            <span><?php echo __('Products', 'atap' );?></span> <span class="delimiter"> &nbsp; / &nbsp; </span> 
            <span class="current"><?php echo __('Price List', 'atap' );?></span></ul>
        <h2 class="page-title"><?php echo __('Price List', 'atap' );?></h2>
     </div>
</div>

<main id="site-content" role="main" class="price-list-page">    
    <div class="container">
        <div class="page-content pt-5 pb-5 my-5">

			<div class="tab-content" id="price-list-content">
			  	<div class="tab-pane fade show active" id="telesis" role="tabpanel" aria-labelledby="home-tab">
			  		<div class="row">
			  			<?php
							$loop = new WP_Query(array(
								'post_type'=>'price_list'
							));
							?>
							<?php while ($loop->have_posts()) : $loop->the_post(); ?>
                                <?php 
                                    $image_url = (has_post_thumbnail())?get_the_post_thumbnail_url():site_url().'/wp-content/uploads/no-image.jpg';
                                ?>
								<div class="col-md-4">
								  	<div class="product-item">
										<div class="product-image">
											<img src="<?php echo $image_url ?>"/>         
										</div>
										<div class="product-meta" data-mh="product-meta">
											<h3 class="product-title mb-3" data-mh="product-title"><a><?php the_title();?></a></h3>                                      
											<?php if( get_field('url_download') ): ?>
												<p><a href="<?php the_field('url_download') ?>"><?php echo __('Download PDF', 'atap' );?> <i class="fal fa-download"></i></a></p>
											<?php endif; ?>
											<span class="last-update">
												<?php echo __('Updated On', 'atap' );?> <?php echo get_the_modified_date("d M Y");?>
											</span>
										</div>
									</div>
								</div>
							<?php endwhile ; wp_reset_query() ;?>
			  			
					</div>
				</div>


			</div>
        </div>
    </div>
</main>
<?php
get_footer();
