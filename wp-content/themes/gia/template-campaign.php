<?php
/**
* Template Name: Campaign
*
* @package WordPress
* @subpackage GIA
* @since GIA
*/


get_header();
 
global $post;
?>

<div class="banner-top" style="background: #2E4B89 url('http://appdev.ifdemo.com/atap/wp-content/uploads/2020/11/banner-top-campain.png');">
    <div class="container">
        <?php echo apat_breadcrumbs(); ?>
        <h2 class="page-title"><?php echo get_the_title(); ?></h2>
     </div>
</div>

<main id="site-content" role="main" class="page page-campaign mb-5 pb-5">    
    <div class="container">
    	<div class="product-top mb-5 mt-5 pt-5">
    		<div class="row">
    				<?php
					$loop = new WP_Query(array(
						'post_type'=>'product',
						'posts_per_page'=> 1,
						'meta_query' => array(
					        array(
					            'key'   => 'campaign_featured',
					            'value' => '1',
					        )
					    )
					));
					?>
					<?php while ($loop->have_posts()) : $loop->the_post(); ?>
						<div class="col-12">
	                		<div class="product-campaign">
	                			<div class="product-image">
	                				<a href="<?php the_permalink();?>">
	                					<?php 
	                					$image_url = (get_the_post_thumbnail_url()) ?get_the_post_thumbnail_url():site_url().'/wp-content/uploads/no-image.jpg';
	                					?>
	                					<!-- <img src="<?php echo $image_url;?>"> -->
	                					<img src="http://appdev.ifdemo.com/atap/wp-content/uploads/2020/11/gs910-5e_angle_reduced_640px.png">
	                					<span class="product-cat"><?php echo __('Campaign', 'atap' );?></span>    
	                				</a>
	                			</div>
	                			<div class="product-meta">
	                				<?php 
	                                    $categories = get_the_terms( $post->ID, 'product-cat' );
	                                    foreach ($categories as $categorie) {
	                                        if($categorie->parent == 0){
	                                           echo '<div class="product-cate mb-2">'.$categorie->name.'</div>';
	                                        }
	                                    }
	                                ?> 
	                                <?php if( get_field('campaign_duration') ): ?>
										 <div class="date-stock mb-2"><?php the_field('campaign_duration');?></div> 
									<?php endif; ?>
			                           
	                				<h3 class="product-title mb-3"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
	                				<div class="product-excerpt">
	                					<p><?php 
                                        $content = wp_strip_all_tags(get_field('description') , true );
                                        echo wp_trim_words($content, 30);
                                        ?>
                                        </p>
	                				</div>
	                			</div>
	                		</div>
	                	</div>
	        		<?php endwhile;  ?>
    			
    		</div>
    	</div>
    
        <div class="row searchform">
        		<div class="col-md-1">
        			<label class="control"><?php echo __('Sort By:', 'atap' )?></label>
        		</div>
		        <div class="col-md-2 form-group">
		            <select name="orderby" id="lastest" class="control selectpicker">
		               	<option value="date_desc"><?php echo __('Lastest', 'atap' );?></option>
		               	<option value="date_asc"><?php echo __('Oldest', 'atap' );?></option>
		            </select>
		           
		        </div>
		        <div class="col-md-5"></div>
		        <div class="col-md-4 form-group search-box">
		            <input type="search" id="search" class="search-field" placeholder="<?php echo __('Enter keywords…', 'atap' );?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s"/>
		            <button type="submit" class="s"  value="<?php the_search_query(); ?>" ><i class="fas fa-search"></i></button>	
		        </div>
		    </div>

        <div class="product-list">
        	
        		<div class="row">
        			
					
				</div>
				<div class="button-more position-relative text-center">
		            <div class="iconload"><img src="<?php echo site_url()?>/wp-content/uploads/loader.gif"/></div>
		            <div class="btn" id="more_posts"><?php echo __('Load More', 'atap' );?></div>
				</div>
			</div>
     
    </div>
</main>

<script type="text/javascript">
	
	jQuery(document).ready( function() {
    	
    	jQuery(".iconload").hide();

	    function loadpage(action=false, pageNumber, keyword, orderby){

	        jQuery.ajax({ 
	           type : "post", 
	           dataType : "json", 
	           url : '<?php echo admin_url('admin-ajax.php');?>', 
	           data : {
	                action: "campaign_filter_product", 
	                pageNumber: pageNumber,
	                keyword: keyword,
	                orderby: orderby
	           },
	           beforeSend: function(){
	                jQuery(".iconload").show();
	           },
	           success: function(data) {
	           		if(action){
	           			jQuery('.product-list .row').html(data.out);
	           		}
	                else{
	                	jQuery('.product-list .row').append(data.out);
	                }
	                if(data.result > pageNumber){
	                	jQuery('.button-more').show();
	                	jQuery(".iconload").hide();
	                }
	                else{
	                	jQuery('.button-more').hide();
	                }
	                
	           },
	           error: function( jqXHR, textStatus, errorThrown ){
	                console.log( 'The following error occured: ' + textStatus, errorThrown );
	        	}	
       		});
    	}
    	loadpage(false,1);

    	jQuery('.s').click(function(e){
    		e.preventDefault();
	   		var keyword=jQuery('#search').val();
	   		var orderby=jQuery('#lastest').val();
	   		loadpage(true,1,keyword,orderby);
    	});
        
        // selectpicker form
    	$('.selectpicker').on('changed.bs.select', function (e, clickedIndex, newValue, oldValue) {
    		var orderby = $(e.currentTarget).val();
	   		var keyword=jQuery('#search').val();;
	   		loadpage(true,1,keyword,orderby);
    	});
    	
    	var pageNumber=2;
    	jQuery('#more_posts').click(function() {
	    	loadpage(false,pageNumber);
	    	pageNumber++;
	    });
 	});


</script>
<?php
get_footer();
