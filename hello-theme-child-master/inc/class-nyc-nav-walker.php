<?php
/**
 * Desktop primary navigation walker.
 *
 * Reproduces the mega-panel markup from the original HTML build:
 *
 *   <ul class="nav__list">
 *     <li class="nav__item">
 *       <a class="nav__link" href="…">Services <svg class="nav__caret">…</svg></a>
 *       <div class="nav__panel">
 *         <a href="…"><svg>…</svg><div>Title<span>Description</span></div></a>
 *       </div>
 *     </li>
 *     <li><a class="nav__link" href="…">Fleet</a></li>
 *   </ul>
 *
 * A top-level item with children becomes a panel trigger. Its children are
 * rendered as bare links inside the panel, each optionally carrying the icon
 * chosen on the menu item and the menu item Description as its subtitle.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class NYC_Nav_Walker
 */
class NYC_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Open a submenu. Only the first level becomes a panel; anything deeper
	 * is flattened into the same panel rather than nesting further.
	 *
	 * @param string   $output Walker output, passed by reference.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   Menu arguments.
	 * @return void
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( 0 === $depth ) {
			$output .= '<div class="nav__panel">';
		}
	}

	/**
	 * Close a submenu.
	 *
	 * @param string   $output Walker output, passed by reference.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   Menu arguments.
	 * @return void
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( 0 === $depth ) {
			$output .= '</div>';
		}
	}

	/**
	 * Open a menu item.
	 *
	 * @param string   $output            Walker output, passed by reference.
	 * @param WP_Post  $data_object       Menu item.
	 * @param int      $depth             Current depth.
	 * @param stdClass $args              Menu arguments.
	 * @param int      $current_object_id Current object ID.
	 * @return void
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		$item = $data_object;

		$classes      = empty( $item->classes ) ? array() : (array) $item->classes;
		$has_children = in_array( 'menu-item-has-children', $classes, true );
		$url          = ! empty( $item->url ) ? $item->url : '#';
		$title        = apply_filters( 'the_title', $item->title, $item->ID );
		$attrs        = $this->link_attributes( $item );

		if ( 0 === $depth ) {
			$output .= $has_children ? '<li class="nav__item">' : '<li>';
			$output .= '<a class="nav__link" href="' . esc_url( $url ) . '"' . $attrs . '>';
			$output .= wp_kses_post( $title );

			if ( $has_children ) {
				$output .= nyc_get_icon( 'caret', 'nav__caret' );
			}

			$output .= '</a>';

			return;
		}

		// Panel link: icon, title, and the menu item Description as a subtitle.
		$icon        = get_post_meta( $item->ID, '_nyc_menu_icon', true );
		$description = ! empty( $item->description ) ? $item->description : '';

		$output .= '<a href="' . esc_url( $url ) . '"' . $attrs . '>';

		if ( $icon ) {
			$output .= nyc_get_icon( $icon );
		}

		$output .= '<div>' . wp_kses_post( $title );

		if ( $description ) {
			$output .= '<span>' . wp_kses_post( $description ) . '</span>';
		}

		$output .= '</div></a>';
	}

	/**
	 * Close a menu item. Panel links close themselves in start_el(), so only
	 * the top level emits a closing tag.
	 *
	 * @param string   $output      Walker output, passed by reference.
	 * @param WP_Post  $data_object Menu item.
	 * @param int      $depth       Current depth.
	 * @param stdClass $args        Menu arguments.
	 * @return void
	 */
	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {
		if ( 0 === $depth ) {
			$output .= '</li>';
		}
	}

	/**
	 * Build the target, rel and aria-current attributes for a link.
	 *
	 * @param WP_Post $item Menu item.
	 * @return string
	 */
	protected function link_attributes( $item ) {
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

		// WordPress knows which item is the current page; the static build
		// worked this out in JavaScript, which cannot work with permalinks.
		if ( ! empty( $item->current ) ) {
			$attrs .= ' aria-current="page"';
		}

		return $attrs;
	}
}
