<?php
/**
* Template Name: Web Quotation
*
* @package WordPress
* @subpackage GIA
* @since GIA
*/


get_header();
 
global $post;
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="banner-top" style="background: #2E4B89 url('http://appdev.ifdemo.com/atap/wp-content/uploads/2020/11/Group-3295-1.png');">
    <div class="container">
        <ul id="breadcrumbs">
        	<a href="<?php bloginfo('url')?>"><?php echo __('Home', 'atap' );?></a> 
        	<span class="delimiter"> &nbsp; / &nbsp; </span>
        	<a href="#">  SERVICE & SUPPORT </a>
        	<span class="delimiter"> &nbsp; / &nbsp; </span>
        	<a href="#">  NET.PRO </a>
        	<span class="delimiter"> &nbsp; / &nbsp; </span>
        	<span class="current"><?php echo get_the_title();?></span>
        </ul>
        <h2 class="page-title"><?php echo get_the_title(); ?></h2>
     </div>
</div>

<main id="site-content" role="main" class="page page-web-quotation my-5 pt-5 pb-5">    
  	<div class="container">
  		<div class="quotation-header text-center">
  			<div class="row">
  				<div class="col-12">
  					<span class="text-uppercase mb-4 d-block"><?php echo get_the_title(); ?></span>
  					<h2 class="text-uppercase mb-4">For Configuration Service</h2>
  					<p class="mb-5">How can we help you? Tell us your issue so we can get you to the right team ASAP.</p>
  				</div>
  			</div>
  		</div>
  		<div class="quotation-form box-form">
  			<?php echo do_shortcode('[contact-form-7 id="3802" title="Quotation Form"]');?>
  		</div>
     
 	</div>
</main>
<script type="text/javascript">
	
jQuery(document).ready( function() {
	jQuery('#brand-filter').each(function () {
        var availableTags = jQuery('#' + jQuery(this).attr("data-list")).find('option').map(function () {
           return this.value;
        }).get();
        jQuery(this).autocomplete({
          source: availableTags
        }).on('focus', function () {
          jQuery(this).autocomplete('search', ' ');
        }).on('search', function () {
          if (jQuery(this).val() === '') {
            jQuery(this).autocomplete('search', ' ');
          }
        });
    });
    
    var stt = 1;
    jQuery('.btn-additem').click(function(e){
      e.preventDefault();
      var item = '<div id="item-'+ stt +'" class="row">' + 
                    '<div class="col-12 col-md-4">' + 
                        '<div class="position-relative select-box">' + 
                            '<input type="search" id="brand-filter'+ stt +'" placeholder="Please select" name="product_name['+ stt +']" data-list="product-list" autocomplete="off" required="" class="ui-autocomplete-input">' + 
                            '<label for="brand-filter'+ stt +'" data-icon=""></label>' + 
                        '</div>' + 
                    '</div>' + 
                    '<div class="col-12 col-md-4">' + 
                        '<span class="wpcf7-form-control-wrap number-of-product">' + 
                            '<select name="number_product['+ stt +']" class="wpcf7-form-control wpcf7-select wpcf7-validates-as-required" aria-required="true" aria-invalid="false"><option value="From 1 to 10" selected="selected">From 1 to 10</option><option value="From 11 to 30">From 11 to 30</option><option value="Over 31">Over 31</option></select>' + 
                        '</span>' + 
                    '</div>' + 
                    '<div class="col-12 col-md-4">' + 
                        '<a class="btn-removeitem" onclick="removeItem('+ stt +')"><i class="fas fa-minus"></i></a>' +
                    '</div>' + 
                  '</div>';
        
      jQuery('.box-item').append(item);
      jQuery('#brand-filter'+stt).each(function () {
          var availableTags = jQuery('#' + jQuery(this).attr("data-list")).find('option').map(function () {
             return this.value;
          }).get();
          jQuery(this).autocomplete({
            source: availableTags
          }).on('focus', function () {
            jQuery(this).autocomplete('search', ' ');
          }).on('search', function () {
            if (jQuery(this).val() === '') {
              jQuery(this).autocomplete('search', ' ');
            }
          });
      });
      stt++;
      
    });


    jQuery(".quotation-form .wpcf7-form").on('submit', function(){

      var productName = jQuery('input[data-list]').map(function(idx, elem) {
        return jQuery(elem).val();
      }).get();
      var productNumber = jQuery('.number-of-product .wpcf7-select').map(function(idx, elem) {
        return jQuery(elem).val();
      }).get();
      
      var productList = [];
      for(var i=0; i < productName.length;i++){
          var item = " - Product: "+productName[i]+" - Number of Units: "+productNumber[i]+" units - Quantity: 1\n";
         productList.push(item);
      }
      var output=jQuery('input[name="your-products"]').val(productList);
      // output.push(productList);
      console.log(output);
    });
 
});
function removeItem(id){
   jQuery("#item-"+ id).remove();
}
    
</script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php
get_footer();
