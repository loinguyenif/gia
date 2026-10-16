		<footer id="site-footer" role="contentinfo" class="header-footer-group">
			<?php
			$footer_menu_location = gia_footer_menu_location();
			$footer_menu_columns = gia_footer_render_menu_columns( $footer_menu_location );
			if ( $footer_menu_columns ) :
				?>
				<div class="gia-footer__menu-wrap">
					<div class="gia-footer__container">
						<nav class="gia-footer__nav" aria-label="<?php echo esc_attr__( 'Footer navigation', 'gia' ); ?>">
							<?php echo $footer_menu_columns; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</nav>
					</div>
				</div>
			<?php endif; ?>
		</footer><!-- #site-footer -->

		<?php wp_footer(); ?>
		<div class="copyright">
			<div class="container">
				<div class="row">
					<div class="col-md-7">
						<i class="fal fa-copyright"></i> <?php
						$copyright_text = get_theme_mod(
							'gia_footer_copyright',
							'General Insurance Association of Singapore {Y}. All rights reserved.'
						);
						echo esc_html( str_replace( '{Y}', wp_date( 'Y' ), $copyright_text ) );
						?>
					</div>
				</div>
            </div>
        </div>
	</body>
</html>
