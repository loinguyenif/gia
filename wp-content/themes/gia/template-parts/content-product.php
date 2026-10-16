<div class="product-item">
	<div class="product-image">
		<a href="<?php the_permalink();?>">
			<!-- <img src="<?php echo get_the_post_thumbnail_url();?>"> -->
			<img src="http://appdev.ifdemo.com/gia/wp-content/uploads/2020/10/EnterpriseCampus.png">
			<?php 
				$key=0;
				$term_list = get_the_terms($post->ID, 'product-cat');
				if($term_list) : ?>
				<?php foreach($term_list as $term) {if($key==0){?>
				   	<span class="product-cat"><?php echo $term->name;?></span>
				<?php } $key++;
				}
				endif;
			?>
		</a>
	</div>
	<div class="product-meta">
		<div class="product-cate mb-2">Enterprise Campus</div>
		<h3 class="product-title mb-2"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
		<div class="product-sku mb-2">
			<span>Code</span> 4054R
		</div>
		<div class="product-price">
			<span>Price</span> 250SGD
		</div>
	</div>
</div>