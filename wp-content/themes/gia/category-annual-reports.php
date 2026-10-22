<?php
get_header();

$category_id         = get_queried_object_id();
$resources_category  = get_category_by_slug( 'media-center' );
$banner_image        = '';

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
                    <?php $is_active = (int) $child_category->term_id === $category_id; ?>
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

$news_query = new WP_Query(
    array(
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => 9,
        'paged'               => 1,
        'cat'                 => $category_id,
        'orderby'             => 'date',
        'order'               => 'DESC',
        'ignore_sticky_posts' => true,
    )
);

if ( $news_query->have_posts() ) :
    ?>
    <section class="container gia-annual-reports" data-category="<?php echo esc_attr( $category_id ); ?>" data-page="1">
        <div class="gia-annual-reports__items">
            <?php echo gia_render_annual_report_items( $news_query ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>

        <?php if ( $news_query->max_num_pages > 1 ) : ?>
            <button class="gia-annual-reports__load-more" type="button">
                <?php esc_html_e( 'Load More', 'gia' ); ?>
            </button>
        <?php endif; ?>
    </section>
    <?php
else :
    ?>
    <p class="gia-annual-reports__empty"><?php esc_html_e( 'No annual reports found.', 'gia' ); ?></p>
    <?php
endif;

wp_reset_postdata();
get_footer();