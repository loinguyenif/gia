<?php
$post_id   = get_the_ID();
$categories = get_the_category( $post_id );
$category   = null;

if ( ! empty( $categories ) ) {
    foreach ( $categories as $post_category ) {
        if ( 'other-types-of-insurance' === $post_category->slug ) {
            $category = $post_category;
            break;
        }
    }

    if ( ! $category ) {
        $category = $categories[0];
    }
}

$banner_image = '';
if ( $category && function_exists( 'get_field' ) ) {
    $category_image = get_field( 'full_image', $category );

    if ( is_array( $category_image ) && ! empty( $category_image['url'] ) ) {
        $banner_image = $category_image['url'];
    } elseif ( is_numeric( $category_image ) ) {
        $banner_image = wp_get_attachment_image_url( (int) $category_image, 'full' );
    } elseif ( is_string( $category_image ) ) {
        $banner_image = $category_image;
    }
}

if ( ! $banner_image ) {
    $banner_image = get_the_post_thumbnail_url( $post_id, 'full' );
}

$faqs = function_exists( 'get_field' ) ? get_field( 'faqs', $post_id ) : array();
if ( empty( $faqs ) && function_exists( 'get_field' ) ) {
    $faqs = get_field( 'frequently_asked_questions', $post_id );
}
?>

<?php if ( $banner_image ) : ?>
    <div class="gia-insurance-hero" style="background-image: url('<?php echo esc_url( $banner_image ); ?>');" aria-hidden="true"></div>
<?php endif; ?>

<main id="site-content" role="main" class="gia-insurance-single">
    <div class="container">
        <article <?php post_class( 'gia-insurance-single__article' ); ?> id="post-<?php the_ID(); ?>">
            <div class="gia-insurance-single__intro">
                <h1 class="gia-insurance-single__title"><?php the_title(); ?></h1>
                <div class="gia-insurance-single__body entry-content">
                    <?php the_content( __( 'Continue reading', 'gia' ) ); ?>
                </div>
            </div>

            <?php if ( is_array( $faqs ) && ! empty( $faqs ) ) : ?>
                <section class="gia-insurance-faq" aria-labelledby="gia-insurance-faq-title">
                    <h2 id="gia-insurance-faq-title" class="gia-insurance-faq__heading">
                        <?php esc_html_e( 'Frequently Asked Questions', 'gia' ); ?>
                    </h2>

                    <div class="gia-insurance-faq__grid">
                        <?php foreach ( $faqs as $faq ) : ?>
                            <?php
                            $question = isset( $faq['question'] ) ? $faq['question'] : ( isset( $faq['faq_question'] ) ? $faq['faq_question'] : '' );
                            $answer   = isset( $faq['answer'] ) ? $faq['answer'] : ( isset( $faq['faq_answer'] ) ? $faq['faq_answer'] : '' );

                            if ( ! $question && ! $answer ) {
                                continue;
                            }
                            ?>
                            <article class="gia-insurance-faq__item">
                                <?php if ( $question ) : ?>
                                    <h3 class="gia-insurance-faq__question"><?php echo esc_html( $question ); ?></h3>
                                <?php endif; ?>
                                <?php if ( $answer ) : ?>
                                    <div class="gia-insurance-faq__answer"><?php echo wp_kses_post( $answer ); ?></div>
                                <?php endif; ?>
                                <?php if ( $category ) : ?>
                                    <a class="gia-insurance-faq__link" href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>">
                                        <?php esc_html_e( 'View more', 'gia' ); ?>
                                    </a>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <?php if ( $category ) : ?>
                        <div class="gia-insurance-faq__more">
                            <strong><?php esc_html_e( 'More Questions?', 'gia' ); ?></strong>
                            <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>">
                                <?php esc_html_e( 'View All Topics', 'gia' ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </section>
            <?php endif; ?>
        </article>
    </div>
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
