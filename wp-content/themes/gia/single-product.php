<?php
get_header();
?>

<main id="site-content" role="main" class="news-page">
    <div class="container">
        
        <article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
        
            <header class="single-header">
                <div class="back">
                    <a href="javascript:history.back()" class="btn-back"><i class="fal fa-arrow-left"></i> &nbsp Back</a>
                </div>
            </header>
            
            <div class="row mb-5 align-items-center">
                <div class="col-md-6 col-lg-8">
                    
                    <?php 
                        $images = get_field('product_thumbnail');
                        if( $images ): ?>
                            <div class="product-thumb">
                                <div class="slider-thumb owl-carousel owl-theme">
                                    <?php foreach( $images as $image ): ?>
                                        <div class="thumb-item">
                                            <img src="<?php echo $image['url']; ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                               
                                <?php 
                                if(isset($product->ID)){
                                    $categories = get_the_terms( $product->ID, 'product-cat' );

                                    foreach ($categories as $categorie) {
                                        if($categorie->parent == 0){
                                           echo '<span class="parent-cat">'.$categorie->name.'</span>';
                                        }
                                    }
                                }
                                ?>
                               
                            </div>
                    <?php endif; ?>
          
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="product-details">
                         <ul class="social-share">
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
                        </ul>
                        <?php 

                           $categories = get_the_terms( $post->ID, 'product-cat' );

                            $top_level_ids = [];
                           
                            foreach( $categories as $category ) {
                                if( ! $category->parent ) {
                                    $top_level_ids[] = $category->term_id;
                                }
                            }
                            $count=0;
                            foreach( $categories as $category ) {
                               
                                if(( in_array( $category->parent, $top_level_ids )) && $count==0) {

                                    echo '<div class="product-cate mb-4">' . $category->name . '</div>';
                                    $count=1;
                                }

                            }

                        ?>

                        <h3 class="product-title mb-4"><a href="javascript:void(0)"><?php the_title();?></a></h3>
                        <?php if( get_field('sku') ): ?>
                            <div class="product-sku mb-1"><span>Code</span> <?php the_field('sku'); ?></div>
                        <?php endif; ?>
                       <?php if( get_field('price') ): ?>
                            <div class="product-price mb-4"><span>Price</span> <?php the_field('price'); ?>SGD</div>
                        <?php endif; ?>
                       <?php 

                            $categories = get_the_terms( $post->ID, 'product-cat' );

                            $top_level_ids = [];
                           
                            foreach( $categories as $category ) {
                                if( ! $category->parent ) {
                                    $top_level_ids[] = $category->term_id;
                                }
                            }
                             $second_level_ids = [];
                            foreach( $categories as $category ) {
                                

                                if( in_array( $category->parent, $top_level_ids )) {
                                    $second_level_ids =$category->term_id;
                                }

                            }
                            // var_dump($second_level_ids);
                        ?>
                        <div class="campaign mb-4">
                            <?php if( get_field('price') ): ?>
                                <h4 class="campaign-text">Campaign Price</h4>
                                <h4 class="campaign-price"><?php the_field('campaign_price'); ?></h4>
                            <?php endif; ?>
                            <?php if( get_field('campaign_duration') ): ?>
                                <p class="campaign-duration"><?php the_field('campaign_duration'); ?></p>
                            <?php endif; ?>
                        </div>
                        <a href="#" class="btn-enquire mb-3" data-toggle="modal" data-target="#enquiryForm">Enquire Now</a>
                        <?php if( get_field('campaign_site') ): ?>
                             <a href="<?php the_field('campaign_site'); ?>" class="btn-campaign">Campaign Site <i class="fas fa-clone"></i></a>
                        <?php endif; ?>
                       
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 my-5">
                    <ul class="nav nav-pills product-tabs">
                        <li >
                            <a  href="#product-details" data-toggle="tab" class="active show">Product details
                            </a>
                        </li>
                        <?php if( get_field('description') ): ?>
                             <li>
                                <a href="#description" data-toggle="tab">Description</a>
                            </li>
                        <?php endif; ?>
                       <?php if( get_field('downloads') ): ?>
                             <li>
                                <a href="#downloads" data-toggle="tab">Downloads <i class="fal fa-download"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                       
                        
                    </ul>
                    <div class="tab-content clearfix">
                        <div class="tab-pane fade show active" id="product-details">
                            <?php if( have_rows('product_detail') ): ?>
                                <?php while( have_rows('product_detail') ): the_row(); 

                                    if( get_sub_field('specifications') ): ?>
                                        <div class="overview mb-5">
                                            <h4 class="mb-4">Overview</h4>
                                            <?php the_sub_field('specifications'); ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if( get_sub_field('key_features') ): ?>
                                        <div class="key-features">
                                            <h4 class="mb-4">Key Features</h4>
                                            <?php the_sub_field('key_features'); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                <?php endwhile; ?>
                            <?php endif; ?>
                           
                            
                        </div>
                        <?php if( get_field('description') ): ?>
                            <div class="tab-pane fade" id="description">
                                <?php the_field('description');?>
                            </div>
                        <?php endif; ?>
                       <?php

                        // Check rows exists.
                        if( have_rows('downloads') ): ?>
                            <div class="tab-pane fade" id="downloads">
                                <div class="d-flex">
                                    <span style="font-weight: bold;">Download List</span>
                                        <ul class="downloads-list">
                          
                                           <?php while( have_rows('downloads') ) : the_row(); ?>
                                                <li>
                                                    <a href="<?php the_sub_field('download_url');?>"><?php the_sub_field('download_tittle');?> <i class="fal fa-download"></i></a>
                                                </li>    
                                            <?php endwhile; ?>
                                        </ul>
                                    </div>
                                </div>
                       <?php else :
                            // Do something...
                        endif;
                        ?>
                        
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-12">
                    <div class=" related-product mb-4">
                        <h4 class="related-title">Related Products</h4>
                    </div>
                </div>
                <div id="owl-related" class="owl-carousel owl-theme cus-carousel">
                    <?php 

                    $terms = get_the_terms( $post->ID , 'product-cat', 'string');

                    $term_ids = wp_list_pluck($terms,'term_id');

                    $query = new WP_Query( array(
                          'post_type' => 'product',
                          'tax_query' => array(
                                array(
                                    'taxonomy' => 'product-cat',
                                    'field' => 'id',
                                    'terms' => $term_ids,
                                    'operator'=> 'IN' 
                                 )),
                          'posts_per_page' => 6,
                          'orderby' => 'rand',
                          'post__not_in'=>array($post->ID)
                    ) );

                    if($query->have_posts()) {
                         while ($query->have_posts() ) : $query->the_post(); 
                             get_template_part( 'template-parts/content', 'product' );
                        endwhile; wp_reset_query();
                    }?>
                </div>
            </div>
        </article><!-- .post -->
    
    </div>
</main><!-- #site-content -->
<?php
    get_template_part( 'template-parts/popup', 'enquiry' );

    get_footer(); ?>

