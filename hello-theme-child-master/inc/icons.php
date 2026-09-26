<?php
/**
 * Inline SVG icon library.
 *
 * The icons are the ones drawn in the original HTML build, kept as markup
 * rather than an icon font or sprite file: they recolour with currentColor,
 * stay sharp at any size, and cost no extra request.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The icon set.
 *
 * Each entry is [ viewBox, inner markup ]. The token {S} is replaced with the
 * stroke width so the same path can be drawn heavier in the footer than in
 * the top bar, exactly as the original CSS did.
 *
 * @return array<string, array{0:string, 1:string}>
 */
function nyc_icon_set() {
	return array(
		'pin' => array(
			'0 0 24 24',
			'<path d="M12 21s7-5.6 7-11a7 7 0 1 0-14 0c0 5.4 7 11 7 11Z" stroke="currentColor" stroke-width="{S}" stroke-linejoin="round"/><circle cx="12" cy="10" r="2.5" stroke="currentColor" stroke-width="{S}"/>',
		),
		'mail' => array(
			'0 0 24 24',
			'<rect x="3.2" y="5.4" width="17.6" height="13.2" rx="2" stroke="currentColor" stroke-width="{S}"/><path d="m3.8 6.6 8.2 6 8.2-6" stroke="currentColor" stroke-width="{S}" stroke-linecap="round" stroke-linejoin="round"/>',
		),
		'phone' => array(
			'0 0 24 24',
			'<path d="M5 3.5h3l1.6 4-2 1.4a12 12 0 0 0 5.5 5.5l1.4-2 4 1.6v3a2 2 0 0 1-2.2 2A16.5 16.5 0 0 1 3 5.7 2 2 0 0 1 5 3.5Z" stroke="currentColor" stroke-width="{S}" stroke-linejoin="round"/>',
		),
		'caret' => array(
			'0 0 12 8',
			'<path d="M1 1.5 6 6.5l5-5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>',
		),
		'plane' => array(
			'0 0 24 24',
			'<path d="M3.6 12.4 20 4.2l-7.4 15.6-2-6.6-7-.8Z" stroke="currentColor" stroke-width="{S}" stroke-linejoin="round"/>',
		),
		'briefcase' => array(
			'0 0 24 24',
			'<rect x="3.4" y="7.6" width="17.2" height="11.4" rx="2" stroke="currentColor" stroke-width="{S}"/><path d="M8.8 7.6V6a2 2 0 0 1 2-2h2.4a2 2 0 0 1 2 2v1.6" stroke="currentColor" stroke-width="{S}"/>',
		),
		'clock' => array(
			'0 0 24 24',
			'<circle cx="12" cy="12" r="8.4" stroke="currentColor" stroke-width="{S}"/><path d="M12 7.4V12l3.2 1.9" stroke="currentColor" stroke-width="{S}" stroke-linecap="round"/>',
		),
		'star' => array(
			'0 0 24 24',
			'<path d="m12 3.6 2.6 5.4 5.9.8-4.3 4.1 1 5.9-5.2-2.8-5.2 2.8 1-5.9L3.5 9.8l5.9-.8L12 3.6Z" stroke="currentColor" stroke-width="{S}" stroke-linejoin="round"/>',
		),
		'route' => array(
			'0 0 24 24',
			'<circle cx="6" cy="17.5" r="2.2" stroke="currentColor" stroke-width="{S}"/><circle cx="18" cy="6.5" r="2.2" stroke="currentColor" stroke-width="{S}"/><path d="M7.8 16.2 16.2 7.8" stroke="currentColor" stroke-width="{S}" stroke-linecap="round" stroke-dasharray="2.6 2.6"/>',
		),
		'calendar' => array(
			'0 0 24 24',
			'<rect x="3.6" y="5" width="16.8" height="15" rx="2.4" stroke="currentColor" stroke-width="{S}"/><path d="M3.6 9.5h16.8M8 3.5v3M16 3.5v3" stroke="currentColor" stroke-width="{S}" stroke-linecap="round"/>',
		),
		'car' => array(
			'0 0 24 24',
			'<path d="M4 16.5v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-2M16 16.5v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-2" stroke="currentColor" stroke-width="{S}" stroke-linecap="round"/><path d="M3.4 16.5h17.2v-4l-1.8-4.6a2 2 0 0 0-1.9-1.3H7.1a2 2 0 0 0-1.9 1.3L3.4 12.5v4Z" stroke="currentColor" stroke-width="{S}" stroke-linejoin="round"/><path d="M3.4 12.5h17.2" stroke="currentColor" stroke-width="{S}"/>',
		),
		'shield' => array(
			'0 0 24 24',
			'<path d="M12 3.4l7 2.6v5.4c0 4.3-2.9 7.6-7 9.2-4.1-1.6-7-4.9-7-9.2V6l7-2.6Z" stroke="currentColor" stroke-width="{S}" stroke-linejoin="round"/><path d="m9 12 2.2 2.2L15.4 10" stroke="currentColor" stroke-width="{S}" stroke-linecap="round" stroke-linejoin="round"/>',
		),
		'users' => array(
			'0 0 24 24',
			'<circle cx="9.5" cy="9" r="3.2" stroke="currentColor" stroke-width="{S}"/><path d="M3.8 19.2a5.8 5.8 0 0 1 11.4 0M16.2 6.2a3.2 3.2 0 0 1 0 5.9M17.4 14.4a5.8 5.8 0 0 1 2.9 4.8" stroke="currentColor" stroke-width="{S}" stroke-linecap="round"/>',
		),
		'arrow-right' => array(
			'0 0 24 24',
			'<path d="M5 12h14m-6-6 6 6-6 6" stroke="currentColor" stroke-width="{S}" stroke-linecap="round" stroke-linejoin="round"/>',
		),
		'instagram' => array(
			'0 0 24 24',
			'<rect x="3.5" y="3.5" width="17" height="17" rx="5" stroke="currentColor" stroke-width="{S}"/><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="{S}"/><circle cx="17" cy="7" r="1.2" fill="currentColor"/>',
		),
		'facebook' => array(
			'0 0 24 24',
			'<path d="M14.5 8.5h2.2V5.6h-2.4c-2.2 0-3.6 1.4-3.6 3.7v1.6H8.4v3h2.3V21h3.1v-7.1h2.3l.4-3h-2.7V9.6c0-.7.3-1.1.7-1.1Z" fill="currentColor"/>',
		),
		'linkedin' => array(
			'0 0 24 24',
			'<rect x="3.5" y="3.5" width="17" height="17" rx="3" stroke="currentColor" stroke-width="{S}"/><path d="M8 10.5V17M8 7.6v.1M12 17v-3.6a2 2 0 0 1 4 0V17" stroke="currentColor" stroke-width="{S}" stroke-linecap="round"/>',
		),
		'whatsapp' => array(
			'0 0 24 24',
			'<path d="M4 20l1.2-4a7.6 7.6 0 1 1 2.9 2.9L4 20Z" stroke="currentColor" stroke-width="{S}" stroke-linejoin="round"/><path d="M9.4 9.2c.3 2.2 2.1 4 4.3 4.3l.8-1.2 1.7.7v1.3a.9.9 0 0 1-1 .9 7 7 0 0 1-6.7-6.7.9.9 0 0 1 .9-1h1.3l.7 1.7-1 .9" fill="currentColor"/>',
		),
		'x' => array(
			'0 0 24 24',
			'<path d="M4 4l7.3 9.6L4.4 20h1.8l5.9-5.6L16.6 20H20l-7.6-10 6.5-6h-1.8l-5.5 5.2L8 4H4Z" fill="currentColor"/>',
		),
		'youtube' => array(
			'0 0 24 24',
			'<rect x="3" y="6" width="18" height="12" rx="3.4" stroke="currentColor" stroke-width="{S}"/><path d="m10.4 9.6 4.4 2.4-4.4 2.4V9.6Z" fill="currentColor"/>',
		),
		'tiktok' => array(
			'0 0 24 24',
			'<path d="M14 3.5h2.6a4.9 4.9 0 0 0 4 4v2.6a7.4 7.4 0 0 1-4-1.3v5.6a5.9 5.9 0 1 1-5.9-5.9c.3 0 .6 0 .9.1v2.7a3.2 3.2 0 1 0 2.4 3.1V3.5Z" fill="currentColor"/>',
		),
	);
}

/**
 * Return one icon as an inline SVG string.
 *
 * @param string $name   Icon key from nyc_icon_set().
 * @param string $class  Optional class attribute.
 * @param string $stroke Stroke width for the stroked icons.
 * @return string Escaped-safe SVG markup, or '' when the icon is unknown.
 */
function nyc_get_icon( $name, $class = '', $stroke = '1.8' ) {
	$icons = nyc_icon_set();

	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}

	list( $viewbox, $inner ) = $icons[ $name ];

	return sprintf(
		'<svg%s viewBox="%s" fill="none" aria-hidden="true" focusable="false">%s</svg>',
		$class ? ' class="' . esc_attr( $class ) . '"' : '',
		esc_attr( $viewbox ),
		str_replace( '{S}', $stroke, $inner )
	);
}

/**
 * Echo one icon.
 *
 * @param string $name   Icon key.
 * @param string $class  Optional class attribute.
 * @param string $stroke Stroke width.
 * @return void
 */
function nyc_icon( $name, $class = '', $stroke = '1.8' ) {
	echo nyc_get_icon( $name, $class, $stroke ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted static markup, values escaped in nyc_get_icon().
}

/**
 * Icon choices for the menu-item picker, labelled for a human.
 *
 * @return array<string, string>
 */
function nyc_icon_choices() {
	return array(
		''            => __( '— None —', 'hello-elementor-child' ),
		'plane'       => __( 'Plane (airport)', 'hello-elementor-child' ),
		'briefcase'   => __( 'Briefcase (corporate)', 'hello-elementor-child' ),
		'clock'       => __( 'Clock (hourly)', 'hello-elementor-child' ),
		'star'        => __( 'Star (events)', 'hello-elementor-child' ),
		'route'       => __( 'Route (point to point)', 'hello-elementor-child' ),
		'car'         => __( 'Car (fleet)', 'hello-elementor-child' ),
		'users'       => __( 'People (group travel)', 'hello-elementor-child' ),
		'shield'      => __( 'Shield (safety)', 'hello-elementor-child' ),
		'calendar'    => __( 'Calendar (booking)', 'hello-elementor-child' ),
		'pin'         => __( 'Map pin (areas)', 'hello-elementor-child' ),
		'phone'       => __( 'Phone', 'hello-elementor-child' ),
		'mail'        => __( 'Envelope', 'hello-elementor-child' ),
		'arrow-right' => __( 'Arrow', 'hello-elementor-child' ),
	);
}

/**
 * Add an Icon select to every menu item in Appearance -> Menus.
 *
 * Uses the core hook added in WordPress 5.4, so no admin JS is needed.
 *
 * @param int    $item_id Menu item ID.
 * @param object $item    Menu item object.
 * @param int    $depth   Depth of the item.
 * @param array  $args    Menu arguments.
 * @return void
 */
function nyc_menu_item_icon_field( $item_id, $item, $depth, $args ) {
	$value   = get_post_meta( $item_id, '_nyc_menu_icon', true );
	$choices = nyc_icon_choices();
	?>
	<p class="field-nyc-icon description description-wide">
		<label for="nyc-menu-icon-<?php echo esc_attr( $item_id ); ?>">
			<?php esc_html_e( 'Icon (shown in the header dropdown panel)', 'hello-elementor-child' ); ?><br />
			<select id="nyc-menu-icon-<?php echo esc_attr( $item_id ); ?>"
				name="nyc_menu_icon[<?php echo esc_attr( $item_id ); ?>]"
				class="widefat">
				<?php foreach ( $choices as $key => $label ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $value, $key ); ?>>
						<?php echo esc_html( $label ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</label>
	</p>
	<?php
}
add_action( 'wp_nav_menu_item_custom_fields', 'nyc_menu_item_icon_field', 10, 4 );

/**
 * Save the menu item icon.
 *
 * @param int $menu_id         Menu ID.
 * @param int $menu_item_db_id Menu item ID.
 * @return void
 */
function nyc_save_menu_item_icon( $menu_id, $menu_item_db_id ) {
	if ( ! isset( $_POST['nyc_menu_icon'][ $menu_item_db_id ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Core verifies the nav-menu nonce before firing this hook.
		return;
	}

	$value  = sanitize_key( wp_unslash( $_POST['nyc_menu_icon'][ $menu_item_db_id ] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
	$valid  = array_keys( nyc_icon_choices() );

	if ( '' === $value || ! in_array( $value, $valid, true ) ) {
		delete_post_meta( $menu_item_db_id, '_nyc_menu_icon' );
		return;
	}

	update_post_meta( $menu_item_db_id, '_nyc_menu_icon', $value );
}
add_action( 'wp_update_nav_menu_item', 'nyc_save_menu_item_icon', 10, 2 );
