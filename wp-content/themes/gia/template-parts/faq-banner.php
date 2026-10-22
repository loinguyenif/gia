<?php
$gia_faq_uploads = wp_upload_dir();
$gia_faq_banner_url = trailingslashit( $gia_faq_uploads['baseurl'] ) . '2026/10/faq-1.jpg';
?>
<section class="gia-faq-hero" style="background-image: linear-gradient(rgba(21, 25, 32, 0.66), rgba(21, 25, 32, 0.66)), url('<?php echo esc_url( $gia_faq_banner_url ); ?>');">
	<div class="gia-faq-hero__inner">
		<h1 class="gia-faq-hero__title"><?php esc_html_e( 'How can we help you?', 'gia' ); ?></h1>
		<form class="gia-faq-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label class="screen-reader-text" for="gia-faq-search-input"><?php esc_html_e( 'Search FAQs', 'gia' ); ?></label>
			<input
				id="gia-faq-search-input"
				type="search"
				name="s"
				value="<?php echo esc_attr( is_search() ? get_search_query( false ) : '' ); ?>"
				placeholder="<?php esc_attr_e( 'Start typing to see instant results', 'gia' ); ?>"
				required
			>
			<input type="hidden" name="post_type" value="faq">
			<button type="submit" aria-label="<?php esc_attr_e( 'Search FAQs', 'gia' ); ?>">
				<i class="fal fa-search" aria-hidden="true"></i>
			</button>
		</form>
	</div>
</section>
