<main id="site-content" role="main" class="careers-single">
    <?php the_content( __( 'Continue reading', 'gia' ) ); ?>
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
