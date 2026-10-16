		<footer id="site-footer" role="contentinfo" class="header-footer-group">
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
