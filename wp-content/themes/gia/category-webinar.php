<?php
get_header();

// get the current taxonomy term
$term = get_queried_object();
$full_image = get_field('full_image', $term);
?>
<div class="banner-top" style="background-image: url('<?php echo $full_image; ?>');">
    <div class="container">
        <ul id="breadcrumbs">
            <a href="<?php echo site_url()?>"><?php echo __('Home', 'atap' );?></a> <span class="delimiter"> &nbsp; / &nbsp; </span> 
            <span><?php echo __('Event & Training', 'atap' );?></span> <span class="delimiter"> &nbsp; / &nbsp; </span> 
            <span class="current"><?php echo get_the_archive_title(); ?></span></ul>
        <h2 class="page-title"><?php echo get_the_archive_title(); ?></h2>
     </div>
</div>

<main id="site-content" role="main" class="news-page">    
    <div class="container">
        
        <form role="search" method="post" class="searchform" action="<?php echo home_url( $wp->request ); ?>">
            <div class="row">
                <div class="col-md-1"><label class="control"><?php echo __('Sort By:', 'atap' )?></label></div>
                
                <div class="col-md-2">
                    <select name="orderby" id="lastest" class="control selectpicker">
                        <option value="date_desc" <?php if($_POST['orderby'] == 'date_desc') echo 'selected' ?>><?php echo __('Latest Date', 'atap' );?></option>
                        <option value="title_asc" <?php if($_POST['orderby'] == 'title_asc') echo 'selected' ?>><?php echo __('Title', 'atap' );?></option>
                    </select>
                </div>
                
                <div class="col-md-5"></div>
                <div class="search-box col-md-4">
                    <input type="search" class="search-field" placeholder="<?php echo __('Enter keywords…', 'atap' );?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s"/>
                    <button type="submit" class="s"  value="<?php the_search_query(); ?>" ><i class="fas fa-search"></i></button>
                </div>
            </div>
            <input type="hidden" name="search-type" value="category-webinar" />
        </form>

        <div id="main-posts">
            <?php
            $cat_id = get_query_var('cat');            
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
            ?>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs cus-tabs">
                <li class="nav-item">
                    <a class="cuslink active" data-toggle="tab" href="#on-demand"><?php echo __('On – Demand', 'atap' );?></a>
                </li>
                <li class="nav-item">
                    <a class="cuslink" data-toggle="tab" href="#upcoming"><?php echo __('Upcoming', 'atap' );?></a>
                </li>
            </ul>
            
            <!-- Tab panes -->
            <div class="tab-content">
                <div id="on-demand" class="tab-pane active">
                    <div id="ajax-on-demand">
                        <?php 
                        $postsPerPage = 10;
                        $args = array(
                                's' => $_POST['s'],  
                                'post_status' => 'publish',          
                                'post_type' => 'post',
                                'tag' => 'on-demand',
                                'posts_per_page' => $postsPerPage,
                                'cat' => $cat_id
                        );
                        $loop = new WP_Query($args+$orderby);
                        while ($loop->have_posts()) : $loop->the_post(); 
                            $youtube_embeb = get_field('youtube_embeb', get_the_ID());   
                        ?>
                         <div class="item">  
                             <div class="row">
                                 <div class="col-md-9" >
                    	            <h4 class="post-title"><?php echo get_the_title(); ?></h4>
                    	            <div class="post-date"><?php echo get_the_date('l, F d, Y') ?></div>        
                                 </div>
                                 <div class="col-md-3" >
                                    <a class="btn btn-fxwidth" data-toggle="modal" data-target="#videoModal<?php echo get_the_ID(); ?>"><?php echo __('Watch', 'atap' );?></a>
                                 </div>
                             </div>
                         </div> 
                         <!-- Modal -->
                        <div class="modal fade cus-modal" id="videoModal<?php echo get_the_ID(); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <a class="close" data-dismiss="modal" aria-label="Close"><i class="eicon-close"></i></a>
                                        <iframe width="100%" height="500" src="<?php echo $youtube_embeb ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <?php
                         endwhile;
                         wp_reset_postdata();
                         $max_num_page_ondemand = $loop->max_num_pages;
                         ?>
                    </div>
                     
                     <?php if($max_num_page_ondemand > 1):?>
                    <div id="more_ondemand" class="bottom-more">
                        <div class="iconload"><img src="<?php echo site_url()?>/wp-content/uploads/loader.gif"/></div>
                        <div class="btn"><?php echo __('Load More', 'atap' );?></div>
                    </div>
                    <?php endif;?>
                    
                </div>
                <div id="upcoming" class="tab-pane fade">
                    <div id="ajax-upcoming">
                        <?php 
                        $args = array(
                                's' => $_POST['s'],            
                                'post_type' => 'post',
                                'tag' => 'upcoming',
                                'posts_per_page' => $postsPerPage,
                                'cat' => $cat_id
                        );
                        $loop = new WP_Query($args+$orderby);
                        if($loop->have_posts()){
                            while ($loop->have_posts()) : $loop->the_post();    
                                $date_event = get_field('date_event', get_the_ID()); 
                            ?>
                            <div class="item">     
                                <div class="row">
                                    <div class="col-md-9" >
                                        <h4 class="post-title"><?php echo get_the_title(); ?></h4>
                                        <div class="post-date"><?php echo $date_event ?></div>        
                                    </div>
                                    <div class="col-md-3" >
                                        <a class="btn btn-fxwidth" data-toggle="modal" data-target="#detailModal<?php echo get_the_ID(); ?>" data-event_name="<?php echo get_the_title(); ?>" ><?php echo __('View Detail', 'atap' );?></a>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade cus-modal" id="detailModal<?php echo get_the_ID(); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <a class="close" data-dismiss="modal" aria-label="Close"><i class="eicon-close"></i></a>
                                            <h4 class="post-title"><?php echo get_the_title(); ?></h4>
                                            <?php the_content( __( 'Continue reading', 'atap' ) );?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        
                            <?php
                            endwhile;    
                        }else{
                            echo __('Coming Soon', 'atap' );
                        }
                        wp_reset_postdata();
                        $max_num_page_upcoming = $loop->max_num_pages;
                        ?>
                    </div>
          
                    <?php if($max_num_page_upcoming > 1):?>
                    <div id="more_upcoming" class="bottom-more">
                        <div class="iconload"><img src="<?php echo site_url()?>/wp-content/uploads/loader.gif"/></div>
                        <div class="btn"><?php echo __('Load More', 'atap' ) ;?></div>
                    </div>
                    <?php endif;?>
                </div>
            </div>
        
        </div>
    </div>
</main>
<script>
$( document ).ready(function() {
    var ppp = <?php echo $postsPerPage ?>; // Post per page
    var cat = <?php echo $cat_id; ?>;
    var pageNumber = 1;
    
    $("#more_ondemand .btn").on("click",function(){ // When btn is pressed.
        $("#more_ondemand .iconload").show(); 
        $("#more_ondemand .btn").attr("disabled",true); // Disable the button, temp.
        
        var totalpage = '<?php echo $max_num_page_ondemand; ?>';
        var orderby = $("#lastest").val();
        var s = $(".search-field").val();
        pageNumber++;
        var str = '&cat=' + cat + '&pageNumber=' + pageNumber + '&ppp=' + ppp + '&action=more_ondemand_ajax&orderby=' + orderby + '&s='+ s;
        $.ajax({
            type: "POST",
            dataType: "html",
            url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
            data: str,
            success: function(data){
                var $data = $(data);
                if($data.length){
                    $("#ajax-on-demand").append($data);
                    
                    if(pageNumber < totalpage){
                        $("#more_ondemand .btn").attr("disabled",false);
                        $("#more_ondemand .iconload").hide();
                    }else{
                        $("#more_ondemand .iconload").hide();
                        $("#more_ondemand .btn").hide();
                    }
                } else{
                    $("#more_ondemand .iconload").hide();
                    $("#more_ondemand .btn").hide();
                }
            },
            error : function(jqXHR, textStatus, errorThrown) {
                $loader.html(jqXHR + " :: " + textStatus + " :: " + errorThrown);
            }
    
        });
        return false;
    });
    
    
    var pageNo = 1;
    $("#more_upcoming .btn").on("click",function(){ // When btn is pressed.
        $("#more_upcoming .iconload").show(); 
        $("#more_upcoming .btn").attr("disabled",true); // Disable the button, temp.
        
        var totalpage = '<?php echo $max_num_page_upcoming; ?>';
        var orderby = $("#lastest").val();
        var s = $(".search-field").val();
        pageNo++;
        var str = '&cat=' + cat + '&pageNumber=' + pageNo + '&ppp=' + ppp + '&action=more_upcoming_ajax&orderby=' + orderby + '&s='+ s;
        $.ajax({
            type: "POST",
            dataType: "html",
            url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
            data: str,
            success: function(data){
                var $data = $(data);
                if($data.length){
                    $("#ajax-upcoming").append($data);
                    
                    if(pageNo < totalpage){
                        $("#more_upcoming .btn").attr("disabled",false);
                        $("#more_upcoming .iconload").hide();
                    }else{
                        $("#more_upcoming .iconload").hide();
                        $("#more_upcoming .btn").hide();
                    }
                } else{
                    $("#more_upcoming .iconload").hide();
                    $("#more_upcoming .btn").hide();
                }
            },
            error : function(jqXHR, textStatus, errorThrown) {
                $loader.html(jqXHR + " :: " + textStatus + " :: " + errorThrown);
            }
    
        });
        return false;
    });
    
    $("#lastest").on("change",function(){ // When btn is pressed.
        $( ".searchform" ).submit();
    });
    

    // Javascript to enable link to tab
    var url = document.location.toString();
    if (url.match('#')) {
        $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
    } 
    
    // Change hash for page-reload
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        window.location.hash = e.target.hash;
    });

});
</script>
<?php
get_footer();
