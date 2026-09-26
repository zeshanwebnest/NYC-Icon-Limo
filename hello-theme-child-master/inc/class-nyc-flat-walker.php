<?php
/**
 * Flat link-list walker.
 *
 * Emits bare <a> elements with no <ul> or <li> around them, which is what
 * three places in the original markup expect: the mobile drawer
 * (.drawer__nav), the footer link columns (.footer__links) and the legal row
 * (.footer__legal).
 *
 * Sub-items are flattened into the same list rather than nesting, matching
 * the drawer in the static build — it listed the individual service pages
 * inline rather than behind a second tap.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class NYC_Flat_Walker
 */
class NYC_Flat_Walker extends Walker_Nav_Menu {

	/**
	 * No submenu wrapper: children join the same flat list.
	 *
	 * @param string   $output Walker output, passed by reference.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   Menu arguments.
	 * @return void
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {}

	/**
	 * No submenu wrapper.
	 *
	 * @param string   $output Walker output, passed by reference.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   Menu arguments.
	 * @return void
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {}

	/**
	 * Render one link.
	 *
	 * @param string   $output            Walker output, passed by reference.
	 * @param WP_Post  $data_object       Menu item.
	 * @param int      $depth             Current depth.
	 * @param stdClass $args              Menu arguments.
	 * @param int      $current_object_id Current object ID.
	 * @return void
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		$item  = $data_object;
		$url   = ! empty( $item->url ) ? $item->url : '#';
		$title = apply_filters( 'the_title', $item->title, $item->ID );
		$attrs = '';

		if ( ! empty( $item->target ) ) {
			$attrs .= ' target="' . esc_attr( $item->target ) . '"';
		}

		$rel = ! empty( $item->xfn ) ? $item->xfn : '';

		if ( '_blank' === $item->target && false === strpos( $rel, 'noopener' ) ) {
			$rel = trim( $rel . ' noopener noreferrer' );
		}

		if ( $rel ) {
			$attrs .= ' rel="' . esc_attr( $rel ) . '"';
		}

		if ( ! empty( $item->current ) ) {
			$attrs .= ' aria-current="page"';
		}

		$output .= '<a href="' . esc_url( $url ) . '"' . $attrs . '>' . wp_kses_post( $title ) . '</a>';
	}

	/**
	 * Links close themselves.
	 *
	 * @param string   $output      Walker output, passed by reference.
	 * @param WP_Post  $data_object Menu item.
	 * @param int      $depth       Current depth.
	 * @param stdClass $args        Menu arguments.
	 * @return void
	 */
	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {}
}
