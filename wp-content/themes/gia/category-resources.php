<?php
get_header();

// get the current taxonomy term
$term = get_queried_object();
$full_image = get_field('full_image', $term);
$curent_id = get_query_var('cat');     
?>
<div class="banner-top" style="background-image: url('<?php echo $full_image; ?>');">
    <div class="container">
        <?php echo apat_breadcrumbs(); ?>
        <h2 class="page-title"><?php echo get_the_archive_title(); ?></h2>
     </div>
</div>

<main id="site-content" role="main" class="news-page">    
    <div class="container">
        <?php 
        $args = array('hide_empty' => 0,'parent' => $curent_id);
        $categories = get_categories( $args );
        if($categories){    
        ?>
        <ul class="tabmenus">
            <?php foreach($categories as $category) {
                if($curent_id == $category->term_id) continue;
            ?>
            <li class="<?php if($_POST['subcat'] == $category->term_id ) echo 'active' ?>">
                <a href="#" class="clickSubmit" data-id="<?php echo $category->term_id  ?>">
                    <span><?php echo esc_html( $category->name );?></span>
                </a>
            </li>
            <?php } ?>
        </ul>
        <?php }?>
                        
        <form role="search" method="post" class="searchform" action="<?php echo home_url( $wp->request ); ?>">
            <div class="row">
                <div class="col-md-1"><label class="control"><?php echo __('Sort By:', 'gia' )?></label></div>

                <div class="col-md-2">
                    <select name="orderby" id="lastest" class="control selectpicker">
                        <option value="date_desc" <?php if($_POST['orderby'] == 'date_desc') echo 'selected' ?>><?php echo __('Latest Date, 'gia' )?></option>
                        <option value="title_asc" <?php if($_POST['orderby'] == 'title_asc') echo 'selected' ?>><?php echo __('Title, 'gia' )?></option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <?php 
                    $args = array('hide_empty' => 0,'parent' => $curent_id);
                    $categories = get_categories( $args );
                    if($categories){    
                    ?>
                    <select name="subcat" id="topics" class="control selectpicker">
                        <option value=""><?php echo __('Select Type', 'gia' )?></option>
                        <?php foreach($categories as $category) {?>
                        <option value="<?php echo $category->term_id  ?>" <?php if($_POST['subcat'] == $category->term_id ) echo 'selected' ?>><?php echo $category->name ?></option>
                        <?php } ?>
                    </select>
                    <?php } ?>
                </div>
                
                <div class="col-md-3"></div>
                <div class="search-box col-md-4">
                    <input type="search" class="search-field" placeholder="<?php echo __('Enter keywords…', 'gia' )?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s"/>
                    <button type="submit" class="s"  value="<?php the_search_query(); ?>" ><i class="fas fa-search"></i></button>
                </div>
            </div>
            <input type="hidden" name="search-type" value="category-resources" />
        </form>

        <div id="ajax-posts" class="row">
            <?php
            $postsPerPage = 9;
            $cat_id = $curent_id;
            if($_POST['subcat']){
                $cat_id = $_POST['subcat'];
            }
            $args = array(
                    's' => $_POST['s'], 
                    'post_status' => 'publish',           
                    'post_type' => 'post',
                    'posts_per_page' => $postsPerPage,
                    'cat' => $cat_id
            );
            
            switch ($_POST['orderby']) {
                case 'date_desc':
                    $orderby = array(
                        'orderby' => 'date',
                        'order' => 'DESC',
                    );
                    break;
                    
                case 'title_asc':
                    $orderby = array(
                        'orderby' => 'title',
                        'order' => 'ASC',
                    );
                    break;
                
                default:
                    $orderby = array(
                        'orderby' => 'date',
                        'order' => 'DESC',
                    );
            }

            $loop = new WP_Query($args+$orderby);

            while ($loop->have_posts()) : $loop->the_post();
            
                $categories = get_the_category();
                $image_url = (get_the_post_thumbnail_url())?get_the_post_thumbnail_url():site_url().'/wp-content/uploads/no-image.jpg';
            ?>
    
             <div class="col-md-4" >
                <div class="carousel-box" data-mh="news-group">
                    <a href="<?php echo get_permalink(); ?>" class="boxImage" style="background-image: url('<?php echo $image_url ?>');"></a>
                    <div class="post-content" data-mh="carousel-group">
                        <?php if ( ! empty( $categories ) ) {?>
                            <?php foreach($categories as $category) { 
                                if($curent_id == $category->term_id) continue;
                            ?>
                            <div class="post-tags"><?php echo esc_html( $category->name );?></div>
                            <?php } ?>
                        <?php }?>
    		            <div class="post-meta">
    		                <h5><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></h5>
    		            </div>
                    </div>
                    
                </div>
             </div>
    
             <?php
             endwhile;
             wp_reset_postdata();
             ?>
        </div>
        <?php if($loop->max_num_pages > 1):?>
        <div class="bottom-more">
            <div class="iconload"><img src="<?php echo site_url()?>/wp-content/uploads/loader.gif"/></div>
            <div class="btn" id="more_posts"><?php echo __('Load More', 'gia' )?></div>
        </div>
        <?php endif;?>
    </div>
</main>
<script>
$( document ).ready(function() {
    
    $(".featured_carousel").owlCarousel({
        nav: true,
        dots: false,
        items: 1,
        margin:30,
        navText: ['<i class="fal fa-arrow-left"></i>', '<i class="fal fa-arrow-right"></i>'],
    });
    
    // Post per page
    var ppp = 9; 
    var cat = <?php echo $curent_id; ?>;
    var pageNumber = 1;
    
    
    function load_posts(){
        
        var totalpage = '<?php echo $loop->max_num_pages; ?>';
        var orderby = $("#lastest").val();
        var subcat = $("#topics").val();
        var s = $(".search-field").val();
        pageNumber++;
        var str = '&cat=' + cat + '&pageNumber=' + pageNumber + '&ppp=' + ppp + '&action=event_post_ajax&orderby=' + orderby + '&subcat='+ subcat +'&s='+ s;
        $.ajax({
            type: "POST",
            dataType: "html",
            url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
            data: str,
            success: function(data){
                var $data = $(data);
                if($data.length){
                    $("#ajax-posts").append($data);
                    
                    if(pageNumber < totalpage){
                        $("#more_posts").attr("disabled",false);
                        $(".iconload").hide();
                    }else{
                        $(".iconload").hide();
                        $("#more_posts").hide();
                    }
                    
                    var newProducts = $('#ajax-posts').find('[data-mh]'); // this finds all the correct elements
        			$.fn.matchHeight._maintainScroll = true;
        			newProducts.matchHeight({ remove: true }); // this works, removes the height match from all elements
        			$.fn.matchHeight._apply(newProducts); // this doesn't do anything
                } else{
                    $(".iconload").hide();
                    $("#more_posts").hide();
                }
            },
            error : function(jqXHR, textStatus, errorThrown) {
                $loader.html(jqXHR + " :: " + textStatus + " :: " + errorThrown);
            }
    
        });
        return false;
    }
    
    $("#more_posts").on("click",function(){ // When btn is pressed.
        $(".iconload").show(); 
        $("#more_posts").attr("disabled",true); // Disable the button, temp.
        load_posts();
    });
    
    $(".clickSubmit").on("click",function(){ // When btn is pressed.
        var catid = $(this).data('id');
        $('#topics').val(catid).trigger('change');
        $(this).parent().addClass('active');
        $( ".searchform" ).submit();
    });
    
    $("#lastest").on("change",function(){ // When btn is pressed.
        $( ".searchform" ).submit();
    });
    $("#topics").on("change",function(){ // When btn is pressed.
        $( ".searchform" ).submit();
    });
    
});
</script>
<?php
get_footer();
