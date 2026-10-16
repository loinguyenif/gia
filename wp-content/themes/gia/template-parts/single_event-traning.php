<?php
get_header();
// get the current taxonomy term
$term = $cat_name = '';
foreach((get_the_category()) as $category){
    $term = $category;
    $cat_name = $term->name;
}
$full_image = get_field('full_image', $term);
?>
<div class="banner-top" style="background-image: url('<?php echo $full_image; ?>');">
    <div class="container">
        <?php echo apat_breadcrumbs(); ?>
        <h2 class="page-title"><?php echo $cat_name;?></h2>
     </div>
</div>

<?php 
$full_images = get_field('full_images', get_the_ID()); 
$date_event = get_field('date_event', get_the_ID()); 
$time_event = get_field('time_event', get_the_ID()); 

if($full_images){
?>
<div class="banner-event" style="background-image: url('<?php echo $full_images['url']; ?>');"></div>
<?php }?>

<main id="site-content" role="main" class="event-page">
	<div class="container">
		
        <article <?php post_class(); ?> id="post-<?php the_ID(); ?>"> 
            <header class="single-header">
                <div class="back">
                    <a href="javascript:history.back()" class="btn-back"><i class="fal fa-arrow-left"></i> &nbsp Back</a>
                </div>
            </header>
            <br />
            <h3 class="event-title"><?php the_title();?></h3>
            <div class="event-heading">
                <?php if($date_event){?>
                <strong>Date</strong> &nbsp; <?php echo $date_event;?> &nbsp; &nbsp; &nbsp; 
                <?php }?>
                <?php if($time_event){?>
                <strong>Time</strong> &nbsp; <?php echo $time_event;?>
                <?php }?>
            </div>
            <div class="post-inner">
                <div class="entry-content">
                    <?php the_content( __( 'Continue reading', 'gia' ) );?>
                </div><!-- .entry-content -->
            </div><!-- .post-inner -->
            
            <div class="event-heading">
                <button class="btn registerModal" data-toggle="modal" data-target="#registerModal"  data-event_name="<?php echo get_the_title(); ?>" >REGISTER</button>
            </div>
        </article><!-- .post -->
	
	</div>
</main><!-- #site-content -->

<!-- Modal -->
<div class="modal fade cus-modal" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xlg">
        <div class="modal-content">
            <div class="modal-body">    
                <a class="close" data-dismiss="modal" aria-label="Close"><i class="eicon-close"></i></a>  
                <div class="box-form">
                    <?php echo do_shortcode( '[contact-form-7 id="2098" title="quote"]' );?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
jQuery( document ).ready(function($) {    
    $(".registerModal").on("click",function(){
        var event_name = $(this).data('event_name');
        $("#registerModal input[name='event_name']").val(event_name);
        $("#registerModal input[name='event_name']").prop('readonly', true);
    });
    
});
</script>

<?php get_footer(); ?>
