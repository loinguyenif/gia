<?php
get_header();

global $wp_query;

$is_search_results = is_search();
$search_query      = get_search_query( false );
?>

<main id="site-content" class="gia-search" role="main">
	<section class="gia-search__hero">
		<div class="gia-search__container">
			<p class="gia-search__eyebrow"><?php esc_html_e( 'Search', 'gia' ); ?></p>
			<h1 class="gia-search__title">
				<?php
				if ( $is_search_results ) {
					printf(
						/* translators: %s: search query. */
						esc_html__( 'Search results for “%s”', 'gia' ),
						esc_html( $search_query )
					);
				} else {
					esc_html_e( 'What are you looking for?', 'gia' );
				}
				?>
			</h1>

			<form class="gia-search__form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader-text" for="gia-search-input"><?php esc_html_e( 'Search for:', 'gia' ); ?></label>
				<input
					id="gia-search-input"
					class="gia-search__input"
					type="search"
					name="s"
					value="<?php echo esc_attr( $search_query ); ?>"
					placeholder="<?php esc_attr_e( 'Enter a keyword', 'gia' ); ?>"
					required
				>
				<button class="gia-search__submit" type="submit">
					<i class="fal fa-search" aria-hidden="true"></i>
					<span><?php esc_html_e( 'Search', 'gia' ); ?></span>
				</button>
			</form>
		</div>
	</section>

	<?php if ( $is_search_results ) : ?>
		<section class="gia-search__results" aria-label="<?php esc_attr_e( 'Search results', 'gia' ); ?>">
			<div class="gia-search__container">
				<?php if ( have_posts() ) : ?>
					<p class="gia-search__result-count">
						<?php
						printf(
							esc_html(
								_n(
									'%s result found',
									'%s results found',
									(int) $wp_query->found_posts,
									'gia'
								)
							),
							esc_html( number_format_i18n( (int) $wp_query->found_posts ) )
						);
						?>
					</p>

					<div class="gia-search__list">
						<?php
						while ( have_posts() ) :
							the_post();
							?>
							<article <?php post_class( 'gia-search__result' ); ?>>
								<p class="gia-search__date">
									<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
										<?php echo esc_html( get_the_date() ); ?>
									</time>
								</p>
								<h2 class="gia-search__result-title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h2>
								<p class="gia-search__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
								<a class="gia-search__read-more" href="<?php the_permalink(); ?>">
									<?php esc_html_e( 'Read more', 'gia' ); ?>
									<span aria-hidden="true">&rarr;</span>
								</a>
							</article>
						<?php endwhile; ?>
					</div>

					<?php
					the_posts_pagination(
						array(
							'mid_size'           => 1,
							'prev_text'          => esc_html__( 'Previous', 'gia' ),
							'next_text'          => esc_html__( 'Next', 'gia' ),
							'screen_reader_text' => esc_html__( 'Search results pages', 'gia' ),
							'class'              => 'gia-search__pagination',
						)
					);
					?>
				<?php else : ?>
					<div class="gia-search__empty">
						<h2><?php esc_html_e( 'No results found', 'gia' ); ?></h2>
						<p><?php esc_html_e( 'Try another keyword or use fewer words.', 'gia' ); ?></p>
					</div>
				<?php endif; ?>
			</div>
		</section>
	<?php endif; ?>
</main>

<?php get_footer(); ?>
