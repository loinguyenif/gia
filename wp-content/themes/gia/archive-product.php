<?php
get_header();
global $post;

//link
$cate = '';
if(isset($_GET['cate']) && $_GET['cate']){
    $cate = $_GET['cate'];
}
$categorie = get_term_by('id', $cate, 'product-cat' );
?>

<div class="banner-product" style="background-image: url('http://appdev.ifdemo.com/atap/wp-content/uploads/banner-product.jpg');">
    <div class="container">
        <h1><?php echo $categorie->name; ?></h1>
        <p>Managed switches to help build the network you need to meet the demands of high performance, reliability and security, combined with a low cost of ownership.</p>
     </div>
</div>

<main id="site-content" role="main" class="news-page products">    
    <div class="container">
        <div class="row searchform">
        	<div class="col-md-1">
                <label class="control"><?php echo __('Sort By:', 'atap' )?></label>
            </div>
         	<div class="col-md-2">
	            <select name="orderby" id="lastest" class="control selectpicker">
	                <option disabled selected><?php echo __('Select Type', 'atap' )?></option>
	               	<option value="date_desc"><?php echo __('Latest Date', 'atap' )?></option>
	               	<option value="date_asc"><?php echo __('Oldest Date', 'atap' )?></option>
	            </select>
	        </div>
	        <div class="col-md-5"></div>
            <div class="col-md-4 search-box">
                <input type="search" id="search" class="search-field" placeholder="<?php echo __('Enter keywords…', 'atap' )?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s"/>
                <button type="submit" class="s"  value="<?php the_search_query(); ?>" ><i class="fas fa-search"></i></button>	
        	</div>
        </div>
       
        <div class="row">
        	<div class="col-md-12 product-list">
        		<!-- <div class="iconload col-12">
        			<img src="http://localhost/atap/wp-content/uploads/loader.gif">
        		</div> -->
        		<div class="row">
					
				</div>
				<div class="button-more position-relative text-center">
		            <div class="iconload"><img src="<?php echo site_url()?>/wp-content/uploads/loader.gif"/></div>
		            <div class="btn" id="more_posts"><?php echo __('Load More', 'atap' )?></div>
				</div>
			</div>
        </div>
     
    </div>
</main>
<script type="text/javascript">
	
	jQuery(document).ready( function() {
    	jQuery(".iconload").hide();
    	
    	function loadpage(action=false, pageNumber, keyword, orderby){

    		var id = <?php echo $cate?>;

	        jQuery.ajax({ 
	           type : "post", 
	           dataType : "json", 
	           url : '<?php echo admin_url('admin-ajax.php');?>', 
	           data : {
	                action: "filter_product", 
	                id: id,
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
	                console.log(data);
	                if(data.result > pageNumber){
	                	jQuery('.button-more').show();
	                	jQuery(".iconload").hide();
	                }
	                else{
	                	jQuery('.button-more').hide();
	                }
	                
                    var newProducts = jQuery('.product-list').find('[data-mh]'); // this finds all the correct elements
        			jQuery.fn.matchHeight._maintainScroll = true;
        			newProducts.matchHeight({ remove: true }); // this works, removes the height match from all elements
        			jQuery.fn.matchHeight._apply(newProducts); // this doesn't do anything
	           },
	           error: function( jqXHR, textStatus, errorThrown ){
	                console.log( 'The following error occured: ' + textStatus, errorThrown );
	        	}	
       		});
    	}
        
        
    	loadpage(false,1);
	    // Search form
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
