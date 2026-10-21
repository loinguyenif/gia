<?php
$post_id      = get_the_ID();
$banner_image = get_the_post_thumbnail_url( $post_id, 'full' );
$resources_category = get_category_by_slug( 'resources' );
$resource_posts = array();

if ( $resources_category instanceof WP_Term ) {
    $resource_posts = get_posts(
        array(
            'category'       => $resources_category->term_id,
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'id',
            'order'          => 'ASC',
        )
    );
}
?>

<?php if ( $banner_image ) : ?>
    <div class="gia-resources-banner" style="background-image: url('<?php echo esc_url( $banner_image ); ?>');">
        <h1>Resources</h1>
    </div>
<?php endif; ?>

<?php if ( $resource_posts ) : ?>
    <nav class="small-menu" aria-label="<?php esc_attr_e( 'Resource posts', 'gia' ); ?>">
        <div class="container">
            <ul>
                <?php foreach ( $resource_posts as $resource_post ) : ?>
                    <?php $is_active = (int) $resource_post->ID === $post_id; ?>
                    <li>
                        <a
                            href="<?php echo esc_url( get_permalink( $resource_post->ID ) ); ?>"
                            class="<?php echo $is_active ? 'is-active' : ''; ?>"
                            <?php if ( $is_active ) : ?>aria-current="page"<?php endif; ?>
                        >
                            <?php echo esc_html( get_the_title( $resource_post->ID ) ); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </nav>
<?php endif; ?>

<main id="site-content" role="main" class="gia-resources-single">
    <?php the_content( __( 'Continue reading', 'gia' ) ); ?>
</main>

<div class="gia-insurance-single__breadcrumbs">
    <div class="container">
        <?php
        if ( shortcode_exists( 'apat_breadcrumbs' ) ) {
            echo do_shortcode( '[apat_breadcrumbs]' ); 
        }
        ?>
    </div>
    
</div>
