<?php
/**
 * The site footer and the mobile action bar.
 *
 * Same markup as the original HTML build; the content now comes from menus
 * and Customizer settings.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$nyc_footer_from_elementor = function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'footer' );

if ( ! $nyc_footer_from_elementor ) :

	$nyc_phone_display = nyc_opt( 'nyc_phone_display' );
	$nyc_email         = nyc_opt( 'nyc_email' );
	?>

	<footer class="site-footer">
		<span class="deco-dots" aria-hidden="true"></span>
		<div class="shell">
			<div class="footer__top">

				<div>
					<?php nyc_brand( 'brand--invert brand--lg' ); ?>

					<?php if ( nyc_opt( 'nyc_footer_intro' ) ) : ?>
						<p class="footer__intro"><?php echo wp_kses_post( nyc_opt( 'nyc_footer_intro' ) ); ?></p>
					<?php endif; ?>

					<?php nyc_social_links(); ?>
				</div>

				<div>
					<?php if ( nyc_opt( 'nyc_footer_col1_heading' ) ) : ?>
						<h2 class="footer__heading"><?php echo esc_html( nyc_opt( 'nyc_footer_col1_heading' ) ); ?></h2>
					<?php endif; ?>
					<div class="footer__links">
						<?php
						nyc_nav_menu(
							array(
								'theme_location' => 'nyc-footer-1',
								'container'      => false,
								'items_wrap'     => '%3$s',
								'depth'          => 1,
								'walker'         => new NYC_Flat_Walker(),
							)
						);
						?>
					</div>
				</div>

				<div>
					<?php if ( nyc_opt( 'nyc_footer_col2_heading' ) ) : ?>
						<h2 class="footer__heading"><?php echo esc_html( nyc_opt( 'nyc_footer_col2_heading' ) ); ?></h2>
					<?php endif; ?>
					<div class="footer__links">
						<?php
						nyc_nav_menu(
							array(
								'theme_location' => 'nyc-footer-2',
								'container'      => false,
								'items_wrap'     => '%3$s',
								'depth'          => 1,
								'walker'         => new NYC_Flat_Walker(),
							)
						);
						?>
					</div>
				</div>

				<div>
					<?php if ( nyc_opt( 'nyc_footer_col3_heading' ) ) : ?>
						<h2 class="footer__heading"><?php echo esc_html( nyc_opt( 'nyc_footer_col3_heading' ) ); ?></h2>
					<?php endif; ?>

					<div class="footer__contact">
						<?php if ( $nyc_phone_display ) : ?>
							<div class="footer__contact-row">
								<?php nyc_icon( 'phone' ); ?>
								<div>
									<a href="<?php echo esc_url( nyc_tel_href() ); ?>" style="font-weight:800; color:#fff;"><?php echo esc_html( $nyc_phone_display ); ?></a>
									<?php if ( nyc_opt( 'nyc_footer_phone_note' ) ) : ?>
										<p style="font-size:var(--fs-xs);"><?php echo esc_html( nyc_opt( 'nyc_footer_phone_note' ) ); ?></p>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( $nyc_email ) : ?>
							<div class="footer__contact-row">
								<?php nyc_icon( 'mail' ); ?>
								<div>
									<a href="<?php echo esc_url( 'mailto:' . $nyc_email ); ?>"><?php echo esc_html( $nyc_email ); ?></a>
									<?php if ( nyc_opt( 'nyc_footer_email_note' ) ) : ?>
										<p style="font-size:var(--fs-xs);"><?php echo esc_html( nyc_opt( 'nyc_footer_email_note' ) ); ?></p>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( nyc_opt( 'nyc_footer_area_title' ) || nyc_opt( 'nyc_footer_area_detail' ) ) : ?>
							<div class="footer__contact-row">
								<?php nyc_icon( 'pin' ); ?>
								<div>
									<?php if ( nyc_opt( 'nyc_footer_area_title' ) ) : ?>
										<p style="color:#fff; font-weight:700;"><?php echo esc_html( nyc_opt( 'nyc_footer_area_title' ) ); ?></p>
									<?php endif; ?>
									<?php if ( nyc_opt( 'nyc_footer_area_detail' ) ) : ?>
										<p style="font-size:var(--fs-xs);"><?php echo esc_html( nyc_opt( 'nyc_footer_area_detail' ) ); ?></p>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
					</div>

					<?php if ( nyc_opt( 'nyc_footer_cta_text' ) ) : ?>
						<a href="<?php echo esc_url( nyc_link( nyc_opt( 'nyc_footer_cta_url' ) ) ); ?>" class="btn btn--primary btn--sm" style="margin-top:var(--space-5);">
							<?php echo esc_html( nyc_opt( 'nyc_footer_cta_text' ) ); ?>
						</a>
					<?php endif; ?>
				</div>

			</div>

			<div class="footer__bottom">
				<p><?php echo esc_html( nyc_copyright_text() ); ?></p>
				<div class="footer__legal">
					<?php
					nyc_nav_menu(
						array(
							'theme_location' => 'nyc-footer-legal',
							'container'      => false,
							'items_wrap'     => '%3$s',
							'depth'          => 1,
							'walker'         => new NYC_Flat_Walker(),
						)
					);
					?>
				</div>
			</div>
		</div>
	</footer>

<?php endif; ?>

<?php if ( nyc_opt( 'nyc_mobilebar_show' ) ) : ?>
	<!-- Mobile sticky actions -->
	<div class="mobile-actions">
		<?php if ( nyc_opt( 'nyc_mobilebar_call' ) ) : ?>
			<a class="mobile-actions__call" href="<?php echo esc_url( nyc_tel_href() ); ?>">
				<?php nyc_icon( 'phone' ); ?>
				<?php echo esc_html( nyc_opt( 'nyc_mobilebar_call' ) ); ?>
			</a>
		<?php endif; ?>

		<?php if ( nyc_opt( 'nyc_mobilebar_book' ) ) : ?>
			<a class="mobile-actions__book" href="<?php echo esc_url( nyc_link( nyc_opt( 'nyc_mobilebar_book_url' ) ) ); ?>">
				<?php nyc_icon( 'calendar' ); ?>
				<?php echo esc_html( nyc_opt( 'nyc_mobilebar_book' ) ); ?>
			</a>
		<?php endif; ?>
	</div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
