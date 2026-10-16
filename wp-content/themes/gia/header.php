<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="profile" href="https://gmpg.org/xfn/11">
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>
		<?php wp_body_open(); ?>

		<?php if ( //! is_front_page() && 
		( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) ) : ?>
			<header class="gia-header">
				<div class="gia-header__audience-bar">
					<nav class="gia-header__container gia-header__audience-nav" aria-label="<?php esc_attr_e( 'Audience navigation', 'gia' ); ?>">
						<?php if ( has_nav_menu( 'primary' ) ) : ?>
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'primary',
									'container'      => false,
									'menu_class'     => 'gia-header__audience-menu',
									'menu_id'        => 'gia-header-audience-menu',
									'fallback_cb'    => false,
									'depth'          => 1,
								)
							);
							?>
						<?php endif; ?>
					</nav>
				</div>

				<div class="gia-header__main">
					<div class="gia-header__container gia-header__main-inner">
						<div class="gia-header__branding">
							<?php if ( has_custom_logo() ) : ?>
								<?php the_custom_logo(); ?>
							<?php else : ?>
								<a class="gia-header__site-title" href="<?php echo esc_url( home_url( '/' ) ); ?>">
									<?php bloginfo( 'name' ); ?>
								</a>
							<?php endif; ?>
						</div>

						<?php if ( has_nav_menu( 'expanded' ) ) : ?>
							<nav class="gia-nav gia-nav--desktop" aria-label="<?php esc_attr_e( 'Main navigation', 'gia' ); ?>">
								<?php
								wp_nav_menu(
									array(
										'theme_location' => 'expanded',
										'container'      => false,
										'menu_class'     => 'gia-nav__list gia-nav__list--desktop',
										'menu_id'        => 'gia-nav-desktop-menu',
										'depth'          => 0,
										'fallback_cb'    => false,
										'walker'         => new Gia_Header_Nav_Walker(),
										'gia_header_mega' => true,
										'gia_header_filter_active_parent' => true,
									)
								);
								?>
							</nav>

							<button class="gia-header__toggle" type="button" aria-controls="gia-nav-mobile-panel" aria-expanded="false" aria-label="<?php esc_attr_e( 'Open menu', 'gia' ); ?>">
								<span></span>
								<span></span>
								<span></span>
							</button>

							<button class="gia-header__backdrop" type="button" aria-label="<?php esc_attr_e( 'Close menu', 'gia' ); ?>" hidden></button>

							<nav id="gia-nav-mobile-panel" class="gia-nav gia-nav--mobile" aria-label="<?php esc_attr_e( 'Mobile navigation', 'gia' ); ?>" hidden>
								<button class="gia-nav__close" type="button" aria-label="<?php esc_attr_e( 'Close menu', 'gia' ); ?>">
									<span aria-hidden="true">&times;</span>
								</button>
								<?php
								wp_nav_menu(
									array(
										'theme_location' => 'expanded',
										'container'      => false,
										'menu_class'     => 'gia-nav__list gia-nav__list--mobile',
										'menu_id'        => 'gia-nav-mobile-menu',
										'depth'          => 0,
										'fallback_cb'    => false,
										'walker'         => new Gia_Header_Nav_Walker(),
										'gia_header_mega' => false,
										'gia_header_filter_active_parent' => true,
									)
								);
							?>
							</nav>
						<?php endif; ?>
					</div>
				</div>
			</header>
		<?php endif; ?>
