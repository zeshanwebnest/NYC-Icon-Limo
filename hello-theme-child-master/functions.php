<?php
/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_CHILD_VERSION', '2.4.0' );

/**
 * Bumped whenever the CSS or JS in /assets changes, so browsers pick up the
 * new files instead of serving a stale cache.
 */
define( 'NYC_ICON_VERSION', '1.0.1' );

require_once get_stylesheet_directory() . '/inc/icons.php';
require_once get_stylesheet_directory() . '/inc/template-tags.php';
require_once get_stylesheet_directory() . '/inc/class-nyc-nav-walker.php';
require_once get_stylesheet_directory() . '/inc/class-nyc-flat-walker.php';
require_once get_stylesheet_directory() . '/inc/customizer.php';
require_once get_stylesheet_directory() . '/inc/fleet.php';
require_once get_stylesheet_directory() . '/inc/tiles.php';

if ( is_admin() ) {
	require_once get_stylesheet_directory() . '/inc/fleet-import.php';
	require_once get_stylesheet_directory() . '/inc/tiles-import.php';
}

/**
 * Theme supports and menu locations.
 *
 * @return void
 */
function nyc_after_setup_theme() {
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 64,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'nyc-primary'      => __( 'Primary Menu (header)', 'hello-elementor-child' ),
			'nyc-mobile'       => __( 'Mobile Drawer Menu', 'hello-elementor-child' ),
			'nyc-footer-1'     => __( 'Footer Column 1', 'hello-elementor-child' ),
			'nyc-footer-2'     => __( 'Footer Column 2', 'hello-elementor-child' ),
			'nyc-footer-legal' => __( 'Footer Legal Links', 'hello-elementor-child' ),
		)
	);
}
add_action( 'after_setup_theme', 'nyc_after_setup_theme' );

/**
 * Load child theme scripts & styles.
 *
 * Runs at priority 20 so everything here prints after Hello Elementor's own
 * stylesheets and therefore wins on equal specificity. The site CSS is loaded
 * in the same order as the original HTML build — variables, base, components,
 * animations — with style.css last so child-theme overrides always land on
 * top.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles() {

	$uri = get_stylesheet_directory_uri();

	wp_enqueue_style(
		'nyc-icon-fonts',
		'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap',
		array(),
		null // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- Google serves its own versioned CSS.
	);

	wp_enqueue_style( 'nyc-icon-variables', $uri . '/assets/css/variables.css', array(), NYC_ICON_VERSION );
	wp_enqueue_style( 'nyc-icon-base', $uri . '/assets/css/base.css', array( 'nyc-icon-variables' ), NYC_ICON_VERSION );
	wp_enqueue_style( 'nyc-icon-components', $uri . '/assets/css/components.css', array( 'nyc-icon-base' ), NYC_ICON_VERSION );
	wp_enqueue_style( 'nyc-icon-animations', $uri . '/assets/css/animations.css', array( 'nyc-icon-components' ), NYC_ICON_VERSION );

	wp_enqueue_style(
		'hello-elementor-child-style',
		$uri . '/style.css',
		array(
			'hello-elementor-theme-style',
			'nyc-icon-animations',
		),
		HELLO_ELEMENTOR_CHILD_VERSION
	);

	wp_enqueue_script( 'nyc-icon-main', $uri . '/assets/js/main.js', array(), NYC_ICON_VERSION, true );
	wp_enqueue_script( 'nyc-icon-animations', $uri . '/assets/js/animations.js', array(), NYC_ICON_VERSION, true );
	wp_enqueue_script( 'nyc-icon-components', $uri . '/assets/js/components.js', array(), NYC_ICON_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20 );

/**
 * Drop Hello Elementor's header/footer stylesheet.
 *
 * Hello ships a separate stylesheet for its own header and footer markup,
 * and it is enqueued after this theme's. It styles `.site-header` and
 * `.site-footer` — the exact class names this design uses — so its flex
 * layout and max-width were overriding ours and boxing both in.
 *
 * header.php and footer.php replace Hello's chrome outright, so nothing on
 * the page needs that file. Removing it is cleaner than out-specifying it,
 * and it saves a request.
 *
 * @return void
 */
function nyc_dequeue_hello_chrome_styles() {
	foreach ( array( 'hello-elementor-header-footer' ) as $handle ) {
		if ( wp_style_is( $handle, 'enqueued' ) ) {
			wp_dequeue_style( $handle );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'nyc_dequeue_hello_chrome_styles', 100 );

/**
 * Flag scripting as available before the first paint.
 *
 * animations.js only hides reveal elements while this class is present, so a
 * script failure can never leave a section invisible. It has to run in the
 * head — after the stylesheets, before the body renders.
 *
 * @return void
 */
function nyc_js_anim_flag() {
	echo '<script>document.documentElement.classList.add("js-anim");</script>' . "\n";
}
add_action( 'wp_head', 'nyc_js_anim_flag', 20 );

/**
 * Show the menu item Description field out of the box.
 *
 * WordPress hides Description behind Screen Options by default. The header
 * dropdown panel uses it as the subtitle under each link, so on a site that
 * has never touched Screen Options the panel would look unfinished with no
 * clue why.
 *
 * Only the untouched default is changed: once the user sets their own
 * preference, WordPress stores it and this leaves it alone.
 *
 * @param mixed  $result Saved value, or false when the user has no preference.
 * @param string $option Option name.
 * @param object $user   User object.
 * @return mixed
 */
function nyc_show_menu_description_field( $result, $option, $user ) {
	if ( false !== $result ) {
		return $result;
	}

	// The WordPress default, minus 'description'.
	return array( 'title-attribute', 'xfn' );
}
add_filter( 'get_user_option_managenav-menuscolumnshidden', 'nyc_show_menu_description_field', 10, 3 );
