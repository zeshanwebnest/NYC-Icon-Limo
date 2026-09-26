<?php
/**
 * The site header: top bar, primary navigation and the mobile drawer.
 *
 * The markup mirrors the original HTML build element for element, so the
 * existing component CSS applies without a single selector change. Only the
 * content is different: it now comes from menus and Customizer settings.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#content"><?php esc_html_e( 'Skip to content', 'hello-elementor-child' ); ?></a>

<?php
/*
 * If a header has been published in Elementor's Theme Builder, it wins and
 * this one steps aside. That is the documented way to stay compatible with
 * Hello Elementor rather than fighting it.
 */
if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'header' ) ) {
	return;
}

$nyc_phone_display = nyc_opt( 'nyc_phone_display' );
$nyc_email         = nyc_opt( 'nyc_email' );
$nyc_cta_text      = nyc_opt( 'nyc_header_cta_text' );
?>

<header class="site-header">

	<?php if ( nyc_opt( 'nyc_topbar_show' ) ) : ?>
		<div class="topbar">
			<div class="shell topbar__inner">
				<div class="topbar__group">
					<?php if ( nyc_opt( 'nyc_topbar_badge' ) ) : ?>
						<span class="topbar__badge"><?php echo esc_html( nyc_opt( 'nyc_topbar_badge' ) ); ?></span>
					<?php endif; ?>

					<?php if ( nyc_opt( 'nyc_topbar_location' ) ) : ?>
						<span class="topbar__item">
							<?php nyc_icon( 'pin', '', '1.7' ); ?>
							<?php echo esc_html( nyc_opt( 'nyc_topbar_location' ) ); ?>
						</span>
					<?php endif; ?>
				</div>

				<div class="topbar__group">
					<?php if ( nyc_opt( 'nyc_topbar_show_email' ) && $nyc_email ) : ?>
						<a class="topbar__item" href="<?php echo esc_url( 'mailto:' . $nyc_email ); ?>">
							<?php nyc_icon( 'mail', '', '1.7' ); ?>
							<?php echo esc_html( $nyc_email ); ?>
						</a>
					<?php endif; ?>

					<?php if ( nyc_opt( 'nyc_topbar_show_phone' ) && $nyc_phone_display ) : ?>
						<a class="topbar__item" href="<?php echo esc_url( nyc_tel_href() ); ?>">
							<?php nyc_icon( 'phone', '', '1.7' ); ?>
							<?php echo esc_html( $nyc_phone_display ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<nav class="shell nav" aria-label="<?php esc_attr_e( 'Primary', 'hello-elementor-child' ); ?>">

		<?php nyc_brand(); ?>

		<?php
		nyc_nav_menu(
			array(
				'theme_location' => 'nyc-primary',
				'container'      => false,
				'items_wrap'     => '<ul class="nav__list">%3$s</ul>',
				'depth'          => 2,
				'walker'         => new NYC_Nav_Walker(),
			)
		);
		?>

		<div class="nav__actions">
			<?php if ( $nyc_phone_display ) : ?>
				<a class="nav__phone" href="<?php echo esc_url( nyc_tel_href() ); ?>">
					<span class="nav__phone-icon">
						<?php nyc_icon( 'phone' ); ?>
					</span>
					<span class="nav__phone-text">
						<span class="nav__phone-label"><?php echo esc_html( nyc_opt( 'nyc_header_phone_label' ) ); ?></span>
						<span class="nav__phone-num"><?php echo esc_html( $nyc_phone_display ); ?></span>
					</span>
				</a>
			<?php endif; ?>

			<?php if ( $nyc_cta_text ) : ?>
				<a href="<?php echo esc_url( nyc_link( nyc_opt( 'nyc_header_cta_url' ) ) ); ?>" class="btn btn--primary btn--sm">
					<?php echo esc_html( $nyc_cta_text ); ?>
				</a>
			<?php endif; ?>

			<button class="nav__toggle"
				aria-expanded="false"
				aria-controls="mobile-drawer"
				aria-label="<?php esc_attr_e( 'Open navigation menu', 'hello-elementor-child' ); ?>">
				<span></span><span></span><span></span>
			</button>
		</div>
	</nav>
</header>

<!-- Mobile drawer -->
<div class="drawer" id="mobile-drawer" aria-hidden="true">
	<?php if ( nyc_opt( 'nyc_drawer_label' ) ) : ?>
		<p class="drawer__label"><?php echo esc_html( nyc_opt( 'nyc_drawer_label' ) ); ?></p>
	<?php endif; ?>

	<nav class="drawer__nav" aria-label="<?php esc_attr_e( 'Mobile', 'hello-elementor-child' ); ?>">
		<?php
		nyc_nav_menu(
			array(
				'theme_location' => 'nyc-mobile',
				'container'      => false,
				'items_wrap'     => '%3$s',
				'depth'          => 0,
				'walker'         => new NYC_Flat_Walker(),
			)
		);
		?>
	</nav>

	<div class="drawer__foot">
		<?php if ( nyc_opt( 'nyc_drawer_cta_text' ) ) : ?>
			<a href="<?php echo esc_url( nyc_link( nyc_opt( 'nyc_drawer_cta_url' ) ) ); ?>" class="btn btn--primary btn--block btn--lg">
				<?php echo esc_html( nyc_opt( 'nyc_drawer_cta_text' ) ); ?>
			</a>
		<?php endif; ?>

		<?php if ( $nyc_phone_display ) : ?>
			<a class="drawer__phone" href="<?php echo esc_url( nyc_tel_href() ); ?>">
				<?php nyc_icon( 'phone' ); ?>
				<?php echo esc_html( $nyc_phone_display ); ?>
			</a>
		<?php endif; ?>

		<?php if ( nyc_opt( 'nyc_drawer_meta' ) ) : ?>
			<p class="drawer__meta"><?php echo esc_html( nyc_opt( 'nyc_drawer_meta' ) ); ?></p>
		<?php endif; ?>
	</div>
</div>
