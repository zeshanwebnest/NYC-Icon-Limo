<?php
/**
 * Option defaults and the template helpers that read them.
 *
 * Every default lives in nyc_defaults() so the Customizer controls and the
 * templates can never drift apart: both call nyc_opt() and get the same
 * value. The defaults are the exact strings from the original HTML build, so
 * a fresh install renders identically before anything is edited.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Default value for every theme option.
 *
 * @return array<string, mixed>
 */
function nyc_defaults() {
	$defaults = array(
		// Contact details, shared by the top bar, header, footer and mobile bar.
		'nyc_phone_display'     => '(917) 952-4031',
		'nyc_phone_number'      => '+19179524031',
		'nyc_email'             => 'reservations@nyciconlimo.com',

		// Top bar.
		'nyc_topbar_show'       => true,
		'nyc_topbar_badge'      => 'Open 24/7',
		'nyc_topbar_location'   => 'All five boroughs · JFK · LGA · EWR',
		'nyc_topbar_show_email' => true,
		'nyc_topbar_show_phone' => true,

		// Header.
		'nyc_brand_name'        => 'NYC Icon Limo',
		'nyc_brand_sub'         => 'New York',
		'nyc_header_phone_label' => 'Reservations',
		'nyc_header_cta_text'   => 'Book Now',
		'nyc_header_cta_url'    => '#book',

		// Mobile drawer.
		'nyc_drawer_label'      => 'Menu',
		'nyc_drawer_cta_text'   => 'Book Your Ride',
		'nyc_drawer_cta_url'    => '#book',
		'nyc_drawer_meta'       => '24/7 reservations · All five boroughs · JFK · LGA · EWR',

		// Footer brand column.
		'nyc_footer_intro'      => 'Premium chauffeur and limousine transportation throughout New York City — airport transfers, corporate travel, hourly hire and special occasions.',

		// Social links. Empty means the icon is not rendered.
		'nyc_social_instagram'  => '',
		'nyc_social_facebook'   => '',
		'nyc_social_linkedin'   => '',
		'nyc_social_whatsapp'   => '',
		'nyc_social_x'          => '',
		'nyc_social_youtube'    => '',
		'nyc_social_tiktok'     => '',

		// Footer column headings.
		'nyc_footer_col1_heading' => 'Company',
		'nyc_footer_col2_heading' => 'Services',
		'nyc_footer_col3_heading' => 'Reservations',

		// Footer reservations column.
		'nyc_footer_phone_note' => 'Answered 24 hours a day',
		'nyc_footer_email_note' => 'Quotes returned same day',
		'nyc_footer_area_title' => 'All five boroughs',
		'nyc_footer_area_detail' => 'JFK · LGA · EWR · Long Island · Westchester · New Jersey',
		'nyc_footer_cta_text'   => 'Book Your Ride',
		'nyc_footer_cta_url'    => '#book',

		// Footer bottom.
		'nyc_copyright'         => '© %year% NYC Icon Limo. All rights reserved.',

		// Mobile action bar.
		'nyc_mobilebar_show'    => true,
		'nyc_mobilebar_call'    => 'Call Now',
		'nyc_mobilebar_book'    => 'Book Now',
		'nyc_mobilebar_book_url' => '#book',
	);

	/**
	 * Filter the theme option defaults.
	 *
	 * @param array $defaults Default values keyed by setting id.
	 */
	return apply_filters( 'nyc_option_defaults', $defaults );
}

/**
 * Read a theme option, falling back to its registered default.
 *
 * @param string $key Setting id.
 * @return mixed
 */
function nyc_opt( $key ) {
	$defaults = nyc_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';

	return get_theme_mod( $key, $default );
}

/**
 * The social networks the footer can display, in render order.
 *
 * @return array<string, array{icon:string, label:string}>
 */
function nyc_social_networks() {
	return array(
		'instagram' => array(
			'icon'  => 'instagram',
			'label' => __( 'Instagram', 'hello-elementor-child' ),
		),
		'facebook'  => array(
			'icon'  => 'facebook',
			'label' => __( 'Facebook', 'hello-elementor-child' ),
		),
		'linkedin'  => array(
			'icon'  => 'linkedin',
			'label' => __( 'LinkedIn', 'hello-elementor-child' ),
		),
		'whatsapp'  => array(
			'icon'  => 'whatsapp',
			'label' => __( 'WhatsApp', 'hello-elementor-child' ),
		),
		'x'         => array(
			'icon'  => 'x',
			'label' => __( 'X', 'hello-elementor-child' ),
		),
		'youtube'   => array(
			'icon'  => 'youtube',
			'label' => __( 'YouTube', 'hello-elementor-child' ),
		),
		'tiktok'    => array(
			'icon'  => 'tiktok',
			'label' => __( 'TikTok', 'hello-elementor-child' ),
		),
	);
}

/**
 * A tel: href built from the raw phone number.
 *
 * @return string
 */
function nyc_tel_href() {
	$raw = (string) nyc_opt( 'nyc_phone_number' );

	return 'tel:' . preg_replace( '/[^0-9+]/', '', $raw );
}

/**
 * Resolve a link that may be a full URL, a path, or a bare #anchor.
 *
 * Bare anchors are resolved against the home URL so "#book" keeps working
 * from every page, the way it did in the static build.
 *
 * @param string $url Stored URL.
 * @return string
 */
function nyc_link( $url ) {
	$url = trim( (string) $url );

	if ( '' === $url ) {
		return '#';
	}

	if ( 0 === strpos( $url, '#' ) ) {
		return home_url( '/' ) . $url;
	}

	return $url;
}

/**
 * The brand lockup.
 *
 * Uses the Site Logo from Customizer -> Site Identity when one is set, and
 * otherwise falls back to the original inline SVG monument mark plus the
 * wordmark, so the header is never empty on a fresh install.
 *
 * @param string $class Extra classes, e.g. 'brand--invert brand--lg'.
 * @return void
 */
function nyc_brand( $class = '' ) {
	$name  = nyc_opt( 'nyc_brand_name' );
	$sub   = nyc_opt( 'nyc_brand_sub' );
	$class = trim( 'brand ' . $class );
	?>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>"
		class="<?php echo esc_attr( $class ); ?>"
		aria-label="<?php echo esc_attr( sprintf( /* translators: %s: site name. */ __( '%s — home', 'hello-elementor-child' ), $name ) ); ?>">
		<?php if ( has_custom_logo() ) : ?>
			<?php
			$logo_id = get_theme_mod( 'custom_logo' );
			echo wp_get_attachment_image(
				$logo_id,
				'full',
				false,
				array(
					'class' => 'brand__logo',
					'alt'   => esc_attr( $name ),
				)
			);
			?>
		<?php else : ?>
			<span class="brand__mark">
				<svg viewBox="0 0 64 64" aria-hidden="true" focusable="false">
					<path d="M16 53 V35 H21 V25 H25.5 V16 H29 V14 L32 5 L35 14 V16 H38.5 V25 H43 V35 H48 V53 Z" fill="currentColor"/>
					<rect x="14" y="56" width="36" height="3" rx="1.5" fill="currentColor" opacity="0.6"/>
				</svg>
			</span>
		<?php endif; ?>

		<?php if ( $name || $sub ) : ?>
			<span class="brand__type">
				<?php if ( $name ) : ?>
					<span class="brand__name"><?php echo esc_html( $name ); ?></span>
				<?php endif; ?>
				<?php if ( $sub ) : ?>
					<span class="brand__sub"><?php echo esc_html( $sub ); ?></span>
				<?php endif; ?>
			</span>
		<?php endif; ?>
	</a>
	<?php
}

/**
 * The footer social row. Renders nothing when no URLs are set.
 *
 * @return void
 */
function nyc_social_links() {
	$networks = nyc_social_networks();
	$links    = array();

	foreach ( $networks as $key => $network ) {
		$url = nyc_opt( 'nyc_social_' . $key );
		if ( $url ) {
			$links[ $key ] = array_merge( $network, array( 'url' => $url ) );
		}
	}

	if ( empty( $links ) ) {
		return;
	}
	?>
	<div class="footer__social">
		<?php foreach ( $links as $link ) : ?>
			<a href="<?php echo esc_url( $link['url'] ); ?>"
				target="_blank"
				rel="noopener noreferrer"
				aria-label="<?php echo esc_attr( sprintf( /* translators: 1: site name, 2: network name. */ __( '%1$s on %2$s', 'hello-elementor-child' ), nyc_opt( 'nyc_brand_name' ), $link['label'] ) ); ?>">
				<?php nyc_icon( $link['icon'] ); ?>
			</a>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * The copyright line, with %year% replaced by the current year.
 *
 * @return string
 */
function nyc_copyright_text() {
	$text = (string) nyc_opt( 'nyc_copyright' );

	return str_replace( '%year%', gmdate( 'Y' ), $text );
}

/**
 * Render a nav menu, or a prompt for administrators when none is assigned.
 *
 * Visitors never see the prompt — an unassigned menu simply renders nothing,
 * so a half-configured site still looks intact.
 *
 * @param array $args Arguments for wp_nav_menu().
 * @return void
 */
function nyc_nav_menu( array $args ) {
	$location = isset( $args['theme_location'] ) ? $args['theme_location'] : '';

	if ( $location && ! has_nav_menu( $location ) ) {
		if ( current_user_can( 'edit_theme_options' ) ) {
			$wrap = isset( $args['items_wrap'] ) ? $args['items_wrap'] : '%3$s';

			$link = sprintf(
				'<a class="nav__link" href="%s">%s</a>',
				esc_url( admin_url( 'nav-menus.php' ) ),
				esc_html__( 'Assign a menu', 'hello-elementor-child' )
			);

			// The prompt has to sit inside whatever wrapper the caller asked
			// for, or it lands outside the <ul> and breaks the header layout.
			if ( false !== strpos( $wrap, '<ul' ) ) {
				$link = '<li>' . $link . '</li>';
			}

			echo str_replace( array( '%1$s', '%2$s', '%3$s' ), array( '', '', $link ), $wrap ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Wrapper is theme markup, link is escaped above.
		}
		return;
	}

	$args['fallback_cb'] = '__return_empty_string';

	wp_nav_menu( $args );
}

/**
 * Copy an image shipped with the theme into the Media Library.
 *
 * Used by the demo importers. The file is copied to a temp path first because
 * media_handle_sideload() moves what it is given, and moving the theme's own
 * asset out of the theme would be a poor trade.
 *
 * @param string $relative Path under /assets/img/, e.g. 'scenes/cabin.jpg'.
 * @param string $alt      Alt text.
 * @param int    $post_id  Post to attach it to.
 * @return int|WP_Error Attachment ID.
 */
function nyc_sideload_theme_image( $relative, $alt, $post_id ) {
	$source = get_stylesheet_directory() . '/assets/img/' . ltrim( $relative, '/' );

	if ( ! file_exists( $source ) ) {
		return new WP_Error( 'missing', __( 'Image not found in the theme.', 'hello-elementor-child' ) );
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$filename = basename( $source );
	$temp     = wp_tempnam( $filename );

	if ( ! $temp || ! copy( $source, $temp ) ) {
		return new WP_Error( 'copy_failed', __( 'Could not copy the image.', 'hello-elementor-child' ) );
	}

	$attachment_id = media_handle_sideload(
		array(
			'name'     => $filename,
			'tmp_name' => $temp,
		),
		$post_id,
		$alt
	);

	if ( is_wp_error( $attachment_id ) ) {
		if ( file_exists( $temp ) ) {
			wp_delete_file( $temp );
		}
		return $attachment_id;
	}

	update_post_meta( $attachment_id, '_wp_attachment_image_alt', $alt );

	return $attachment_id;
}

/**
 * Allow the SVG markup the icon library produces through wp_kses.
 *
 * @return array<string, array<string, bool>>
 */
function nyc_svg_kses() {
	$attrs = array(
		'class'            => true,
		'viewbox'          => true,
		'fill'             => true,
		'stroke'           => true,
		'stroke-width'     => true,
		'stroke-linecap'   => true,
		'stroke-linejoin'  => true,
		'stroke-dasharray' => true,
		'aria-hidden'      => true,
		'focusable'        => true,
		'd'                => true,
		'x'                => true,
		'y'                => true,
		'rx'               => true,
		'cx'               => true,
		'cy'               => true,
		'r'                => true,
		'width'            => true,
		'height'           => true,
		'opacity'          => true,
	);

	return array(
		'svg'    => $attrs,
		'path'   => $attrs,
		'rect'   => $attrs,
		'circle' => $attrs,
		'g'      => $attrs,
	);
}
