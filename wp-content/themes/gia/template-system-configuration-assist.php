<?php
/**
* Template Name: System Configuration Assist
*
* @package WordPress
* @subpackage GIA
* @since GIA
*/
get_header();
global $post;

//link
$cate = '';
if(isset($_GET['cate']) && $_GET['cate']){
    $cate = $_GET['cate'];
}
$subcate = '';
if(isset($_GET['subcate']) && $_GET['subcate']){
    $subcate = $_GET['subcate'];
}
$range = '';
if(isset($_GET['range']) && $_GET['range']){
    $range = $_GET['range'];
}
$series = '';
if(isset($_GET['series']) && $_GET['series']){
    $series = $_GET['series'];
}
?>

<div class="banner-top" style="background: #2E4B89 url('http://appdev.ifdemo.com/atap/wp-content/uploads/2020/11/banner-top-product.png');">
    <div class="container">
        <?php echo apat_breadcrumbs(); ?>
        <h2 class="page-title"><?php echo get_the_title(); ?></h2>
     </div>
</div>

<main id="site-content" role="main" class="news-page">    
    <div class="container">
        <div class="row searchform">
        	<div class="col-md-1">
                <label class="control">Sort By:</label>
            </div>
         	<div class="col-md-2">
	            <select name="orderby" id="lastest" class="control selectpicker">
	                <option disabled selected>Select Type</option>
	               	<option value="date_desc">Latest Date</option>
	               	<option value="date_asc">Oldest Date</option>
	            </select>
	        </div>
	        <div class="col-md-5"></div>
            <div class="col-md-4 search-box">
                <input type="search" id="search" class="search-field" placeholder="Enter keywords…" value="<?php echo esc_attr( get_search_query() ); ?>" name="s"/>
                <button type="submit" class="s"  value="<?php the_search_query(); ?>" ><i class="fas fa-search"></i></button>	
        	</div>
        </div>
       
        <div class="row">
        	<div class="col-md-3 product-sidebar pr-md-0">
        		<div class="wrapper-sidebar">
        			<div class="widget filter">
        				
    					<div class="form-group">
    						<h5 class="widget-title">Product Categories</h5>
    						<?php 
        						$terms = get_terms( array( 
								    'taxonomy' => 'product-cat',
								    'parent'   => 0
								) );
							    foreach ( $terms as $term ) :
                                   $attr = ($cate == $term->term_id)?'checked="true"':'';  
                            ?>
								<input <?php echo $attr ?> type="radio" id="<?php echo $term->slug;?>"  name="product-cat" class="radioCat" value="<?php echo $term->term_id;?>">
								<label for="<?php echo $term->slug;?>"><?php echo $term->name;?></label>
							
							<?php endforeach; ?>

    					</div>
			           	<div class="form-group">
    						<h5 class="widget-title">Subcategories</h5>
    						<select id="subcategories" class="select" >
							  <option value="" disabled selected>--- Subcategories ---</option>
                              <?php 
                              if($cate){
                                $args = array(
                                      'hide_empty' => 0,
                                      'orderby' => 'name',
                                      'depth' => 1,
                                      'parent' => $cate,
                                      'order' => 'ASC',
                                      'taxonomy' => 'product-cat'
                                    );
                                $term_children = get_categories($args);
                                foreach ( $term_children as $child ) {
                                    $attr = ($subcate == $child->term_id)?'selected="selected"':'';
                                    echo '<option value="'.$child->term_id.'" '.$attr.'>'.$child->name.'</option>';
                                } 
                              }
                              ?>
							</select> 
    					</div>
    					<div class="form-group">
    						<h5 class="widget-title">Range</h5>
    						<select id="range" class="select" >
							  <option value="" disabled selected>--- Range ---</option>
                              <?php 
                              if($subcate){
                                $args = array(
                                      'hide_empty' => 0,
                                      'orderby' => 'name',
                                      'depth' => 1,
                                      'parent' => $subcate,
                                      'order' => 'ASC',
                                      'taxonomy' => 'product-cat'
                                    );
                                $term_children = get_categories($args);
                                foreach ( $term_children as $child ) {
                                    $attr = ($range == $child->term_id)?'selected="selected"':'';
                                    echo '<option value="'.$child->term_id.'" '.$attr.'>'.$child->name.'</option>';
                                } 
                              }
                              ?>
							</select> 
    					</div>
    					<div class="form-group">
    						<h5 class="widget-title">Series</h5>
    						<select id="series" class="select" >
							  <option value="" disabled selected>--- Series ---</option>
                              <?php 
                              if($range){
                                $args = array(
                                      'hide_empty' => 0,
                                      'orderby' => 'name',
                                      'depth' => 1,
                                      'parent' => $range,
                                      'order' => 'ASC',
                                      'taxonomy' => 'product-cat'
                                    );
                                $term_children = get_categories($args);
                                foreach ( $term_children as $child ) {
                                    $attr = ($series == $child->term_id)?'selected="selected"':'';
                                    echo '<option value="'.$child->term_id.'" '.$attr.'>'.$child->name.'</option>';
                                } 
                              }
                              ?>
							</select> 
    					</div>

  						<div class="form-group d-flex mb-0">
								<button class="btn-reset">Reset</button>	
								<button class="btn-submit">Submit</button>
							</div>	
				      
        			</div>
        		</div>
        	</div>
        	<div class="col-md-9 product-list pl-lg-5">
        		<!-- <div class="iconload col-12">
        			<img src="http://localhost/atap/wp-content/uploads/loader.gif">
        		</div> -->
        		<div class="row pl-lg-4 ">
					
				</div>
				<div class="button-more position-relative text-center">
		            <div class="iconload"><img src="<?php echo site_url()?>/wp-content/uploads/loader.gif"/></div>
		            <div class="btn" id="more_posts">Load More</div>
				</div>
			</div>
        </div>
     
    </div>
</main>

<style type="text/css">
	h5.widget-title {
        font-weight: bold;
        font-size: 17px;
    }
    .btn-reset,.btn-submit{
    	width: 50%;
	    text-align: center;
	    padding: 13px 0!important;
	    flex: 0 50%;
    }
   .btn-reset {
	    background: #fff!important;
	    color: #da1025!important;
	    border: 1px solid;
	}

    select.select {
        border: 1px solid #EEEEEE;
        width: 100%;
        padding: 7px;
        margin-top: 15px;
        margin-bottom: 10px;
    }
    select.select option {
        color: #000;
    }
    input[type=radio] + label {
        display: flex;
        cursor: pointer;
        align-items: center;
        padding-top: 10px;
        font-weight: 300;
    }
    input[type=radio] {
      display: none;
    }
    input[type=radio] + label:before {
        content: "\f00c";
        border-radius: 0.2em;
        display: inline-block;
        vertical-align: bottom;
        color: transparent;
        transition: .4s;
        position: absolute;
        right: 15px;
        font-family: 'Font Awesome 5 Pro';
        font-weight: bold;
    }
    input[type=radio] + label:active:before {
      transform: scale(0);
    }
    input[type=radio]:checked + label:before {
        color: #DA1025;
    }
    input[type=radio]:disabled + label:before {
      transform: scale(0);
    }
    input[type=radio]:checked:disabled + label:before {
      transform: scale(0);
    }
    input[type=radio]:checked + label {
        color: #da1025;
        text-decoration: underline;
    }

</style>
<script type="text/javascript">
	
	jQuery(document).ready( function() {
		// Chose Categogy
	    jQuery('.radioCat').change(function(e){
	    	var catId = e.target.value;
	        jQuery.ajax({ 
	           type : "post", 
	           url : '<?php echo admin_url('admin-ajax.php');?>', 
	           data : {
	                action: "show_subcat",
	                parentId: catId,
	                text: "Subcategories"
	           },
	           beforeSend: function(){
	              
	           },
	           success: function(response) {
	           		jQuery("#subcategories").html(response);
	           		jQuery("#range").html("<option value='' disabled selected>--- Range ---</option>");
	           		jQuery("#series").html("<option value='' disabled selected>--- Series ---</option>");
	           },
	           error: function( jqXHR, textStatus, errorThrown ){
	                console.log( 'The following error occured: ' + textStatus, errorThrown );
	        	}	
       		});
    	});
	    // Show sub category
    	jQuery('#subcategories').change(function(e){
    		var catId = e.target.value;
    		jQuery.ajax({ 
	           type : "post", 
	           url : '<?php echo admin_url('admin-ajax.php');?>', 
	           data : {
	                action: "show_subcat",
	                parentId: catId,
	                text: "Range"
	           },
	           beforeSend: function(){
	              
	           },
	           success: function(response) {
	           		jQuery("#range").html(response);
	           },
	           error: function( jqXHR, textStatus, errorThrown ){
	                console.log( 'The following error occured: ' + textStatus, errorThrown );
	        	}	
       		});
    	});
    	 // Show range category
    	jQuery('#range').change(function(e){
    		var catId = e.target.value;
    		jQuery.ajax({ 
	           type : "post", 
	           url : '<?php echo admin_url('admin-ajax.php');?>', 
	           data : {
	                action: "show_subcat",
	                parentId: catId,
	                text: "Series"
	           },
	           beforeSend: function(){
	              
	           },
	           success: function(response) {
	           		jQuery("#series").html(response);
	           },
	           error: function( jqXHR, textStatus, errorThrown ){
	                console.log( 'The following error occured: ' + textStatus, errorThrown );
	        	}	
       		});
    	});

    	

    	jQuery(".iconload").hide();
    	
    	function loadpage(action=false, pageNumber, keyword, orderby){

    		var productCat=jQuery('.radioCat:checked').val();
	   		var subCat=jQuery('#subcategories').val();
	   		var range=jQuery('#range').val();
	   		var series=jQuery('#series').val();

	   		if(series){
	   			var id = series;
	   		}
	   		else if(series == null && range != null){
	   			var id = range;
	   		}
	   		else if(series == null && range == null && subCat != null){
	   			var id = subCat;
	   		}
	   		else if(series == null && range == null && subCat == null){
	   			var id = productCat;
	   		}

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
	                
	           },
	           error: function( jqXHR, textStatus, errorThrown ){
	                console.log( 'The following error occured: ' + textStatus, errorThrown );
	        	}	
       		});
    	}
    	loadpage(false,1);

	    jQuery('.btn-submit').click(function(e){
	    	var productCat = jQuery('.radioCat:checked').val();
	   		var subCat = jQuery('#subcategories').val();
	   		var range = jQuery('#range').val();
            var series = jQuery('#series').val();
            
            var url_cate = url_subcate = url_range = url_series = '';
            if(productCat){
                url_cate = '?cate='+productCat;
            }
            if(subCat){
                url_subcate = '&subcate='+subCat;
            }
            if(range){
                url_range = '&range='+range;
            }
            if(series){
                url_series = '&series='+series;
            }
            window.location.href = '<?php echo site_url()?>/system-configuration-assist/'+url_cate+url_subcate+url_range+url_series;
    	});
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

	    jQuery('.btn-reset').click(function(e){
	    	e.preventDefault();
	    	jQuery('.radioCat:checked').attr('checked', false);
	    	jQuery('#subcategories').html("<option value='' disabled selected>--- Subcategories ---</option>");
	    	jQuery("#range").html("<option value='' disabled selected>--- Range ---</option>");
	        jQuery("#series").html("<option value='' disabled selected>--- Series ---</option>");
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
