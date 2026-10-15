jQuery(document).ready(function(){

    var isMulti = (jQuery('.owl-carousel .thumb-item').length > 1) ? true : false;
    
    jQuery(".slider-thumb").owlCarousel({
        loop: isMulti,
        items:1,
        nav:isMulti,
        navText:["<i class='fal fa-arrow-left'></i>","<i class='fal fa-arrow-right'></i>"]
    });
    
    jQuery("#owl-related").owlCarousel({
        loop:true,
        nav: true,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:2
            },
            1000:{
                items:3
            }
        },
        navText:["<i class='fal fa-arrow-left'></i>","<i class='fal fa-arrow-right'></i>"]
    });
    
    // Menu fixed hash default
    jQuery('#menu-hash .elementor-icon-list-items').before('<div class="menu-toggle"></div>');
    jQuery('#menu-hash .elementor-icon-list-items a').eq(0).addClass('active'); 
    
    jQuery(".menu-toggle").on("click",function(){
        jQuery('#menu-hash .elementor-icon-list-items').toggleClass('hideList');
    });
    

    // Header search
    if (jQuery('#open_search').length) {
        jQuery('#open_search').click(function(){
            if( jQuery('#search_box').is(':hidden') ){
                jQuery('#search_box').stop(true, true).slideDown(200);
                jQuery('body,html').stop().animate({'scrollTop': 0 }, 600);
            }else{
                jQuery('#search_box').stop(true, true).slideUp(200);

            }
            return false;

        })

        jQuery('#close_menu_search').click(function(){
            jQuery('#search_box').stop(true, true).slideUp(200);
            return false;

        })

        jQuery(document).keyup(function(e) {
              //if (e.keyCode === 13) jQuery('.save').click();     // enter
              if (e.keyCode === 27) jQuery('#search_box').stop(true, true).slideUp(200);
        });

        // REGEX NUMBER
        function setInputFilter(textbox, inputFilter) {
          ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function(event) {
            textbox.addEventListener(event, function() {
              if (inputFilter(this.value)) {
                this.oldValue = this.value;
                this.oldSelectionStart = this.selectionStart;
                this.oldSelectionEnd = this.selectionEnd;
              } else if (this.hasOwnProperty("oldValue")) {
                this.value = this.oldValue;
                this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
              } else {
                this.value = "";
              }
            });
          });
        }
        var input=document.getElementById("intTextBox");
        if(input) {
            setInputFilter(input, function(value) {
                return /^-?\d*$/.test(value);
            });
        }
    }
    
    //solutions_hover
    jQuery( ".hover_title" ).mouseover(function() {
        jQuery( ".box-hover" ).removeClass('show');
        var id = jQuery(this).data('id');
        jQuery( "#box-hover"+id ).addClass('show');
    });   
    
    jQuery(".clickhere").click(function(){
        jQuery(this).parent().parent().parent().parent().parent().parent().parent().find('.hiddenContent').show();
        jQuery(this).hide();
        jQuery(this).parent().find('.clickclose').show();
    });
    jQuery(".clickclose").click(function(){
        jQuery(this).parent().parent().parent().parent().parent().parent().parent().find('.hiddenContent').hide();
        jQuery(this).hide();
        jQuery(this).parent().find('.clickhere').show();
    });  
});

jQuery( window ).load(function() {
    setTimeout(function() {
        //fiter category
        var html = '<select id="filter_cate" name="filter_cate">';
        jQuery('#productsServicesFilterOptions').find(":radio").each(function () {
            html +='<option value="'+this.value+'">'+$(this).closest("label").text()+'</option>';
        });
        html +='</select>';           
        jQuery('#html_filter_cate').html(html);
                
        jQuery('#filter_cate').selectpicker();
        jQuery('#filter_cate').on('change', function(e){
            jQuery('#productsServicesFilterOptions').find("input[value='"+this.value+"']").prop("checked", true);
            jQuery('#applyFilterOptions').click();
        });          
        
        
        //fiter country
        var html = '<select id="filter_country" name="filter_country">';
        jQuery('#filter__country').find(":radio").each(function () {
            html +='<option value="'+this.value+'">'+$(this).closest("label").text()+'</option>';
        });
        html +='</select>';
                        
        jQuery('#html_filter_country').html(html);
        jQuery('#filter_country').selectpicker();     
        jQuery('#filter_country').on('change', function(e){
            jQuery('#filter__country').find("input[value='"+this.value+"']").prop("checked", true);
            jQuery('#applyFilterOptions').click();
        }); 
                         
    }, 2000);
    
    
                   
});    

jQuery(window).scroll(function() {
    // Menu fixed hash
	var scrollDistance = jQuery(window).scrollTop();
	// Assign active class to nav links while scolling
	jQuery('.page-section').each(function(i) {
		if ((jQuery(this).position().top - 250) <= scrollDistance) {
			jQuery('#menu-hash .elementor-icon-list-items a.active').removeClass('active');
			jQuery('#menu-hash .elementor-icon-list-items a').eq(i).addClass('active');
		}
	});
}).scroll();
