<?php
$post_id             = get_the_ID();
$post_category_ids   = wp_get_post_categories( $post_id );
$resources_category  = get_category_by_slug( 'media-center' );
$banner_image        = '';
$pdf_id              = absint( get_post_meta( $post_id, '_post_pdf_file_id', true ) );
$pdf_url             = $pdf_id ? wp_get_attachment_url( $pdf_id ) : '';

$child_categories = array();

if ( $resources_category instanceof WP_Term ) {
    $banner_id = get_term_meta( $resources_category->term_id, 'category_banner_id', true );
    $banner_image = $banner_id ? wp_get_attachment_image_url( $banner_id, 'full' ) : '';

    $child_categories = get_categories(
        array(
            'taxonomy'   => 'category',
            'parent'     => $resources_category->term_id,
            'hide_empty' => false,
            'orderby'    => 'id',
            'order'      => 'ASC',
        )
    );
}

if ( $banner_image ) :
    ?>
    <div class="gia-resources-banner" style="background-image: url('<?php echo esc_url( $banner_image ); ?>');">
        <h1 style="color: black;">Media center</h1>
    </div>
    <?php
endif;

if ( $child_categories ) :
    ?>
    <nav class="small-menu" aria-label="<?php esc_attr_e( 'Child categories', 'gia' ); ?>">
        <div class="container">
            <ul>
                <?php foreach ( $child_categories as $child_category ) : ?>
                    <?php $is_active = in_array( (int) $child_category->term_id, $post_category_ids, true ); ?>
                    <li>
                        <a
                            href="<?php echo esc_url( get_category_link( $child_category->term_id ) ); ?>"
                            class="<?php echo $is_active ? 'is-active' : ''; ?>"
                            <?php if ( $is_active ) : ?>aria-current="page"<?php endif; ?>
                        >
                            <?php echo esc_html( $child_category->name ); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </nav>
    <?php
endif;
?>

<main id="site-content" role="main" class="container gia-company-news-single">
    <article <?php post_class( 'gia-company-news-single__article' ); ?> id="post-<?php the_ID(); ?>">
        <h1 class="gia-company-news-single__title"><?php the_title(); ?></h1>

        <?php if ( $pdf_url ) : ?>
            <div class="gia-company-news-single__pdf">
                <a href="<?php echo esc_url( $pdf_url ); ?>" target="_blank" rel="noopener">
                    <?php esc_html_e( 'View PDF', 'gia' ); ?>
                </a>
            </div>
        <?php endif; ?>

        <div class="gia-company-news-single__content">
            <?php the_content( __( 'Continue reading', 'gia' ) ); ?>
        </div>
    </article>
</main>

<div class="gia-insurance-single__breadcrumbs">
    <div class="container">
        <?php
        if ( shortcode_exists( 'apat_breadcrumbs' ) ) {
            echo do_shortcode( '[apat_breadcrumbs]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
        ?>
    </div> 
</div>
