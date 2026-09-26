<?php
/**
 * Fleet — a dashboard-managed vehicle list rendered by the [fleet] shortcode.
 *
 * The markup this produces is the same .vcard grid the original HTML build
 * used, so the existing component CSS styles it with no changes. What differs
 * is where the content comes from: a Vehicles post type instead of hand-typed
 * HTML.
 *
 * Deliberately self-contained. It registers nothing global, enqueues nothing,
 * and outputs only inside its own shortcode — so it cannot reach Elementor's
 * sections, containers or widgets.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const NYC_VEHICLE_CPT = 'nyc_vehicle';
const NYC_VEHICLE_TAX = 'nyc_vehicle_type';

/**
 * The meta fields a vehicle carries, with their sanitisers.
 *
 * @return array<string, array{label:string, type:string, sanitize:string, help:string, placeholder:string}>
 */
function nyc_vehicle_fields() {
	return array(
		'_nyc_v_class'      => array(
			'label'       => __( 'Class badge', 'hello-elementor-child' ),
			'type'        => 'text',
			'sanitize'    => 'sanitize_text_field',
			'help'        => __( 'The gold pill on the photo, e.g. Business, First Class, Premium.', 'hello-elementor-child' ),
			'placeholder' => 'Business',
		),
		'_nyc_v_model'      => array(
			'label'       => __( 'Model line', 'hello-elementor-child' ),
			'type'        => 'text',
			'sanitize'    => 'sanitize_text_field',
			'help'        => __( 'Shown under the vehicle name.', 'hello-elementor-child' ),
			'placeholder' => 'Cadillac XTS · Lincoln Continental or similar',
		),
		'_nyc_v_desc'       => array(
			'label'       => __( 'Short description', 'hello-elementor-child' ),
			'type'        => 'textarea',
			'sanitize'    => 'sanitize_textarea_field',
			'help'        => __( 'One or two sentences. Leave empty to hide it on the card.', 'hello-elementor-child' ),
			'placeholder' => '',
		),
		'_nyc_v_passengers' => array(
			'label'       => __( 'Passengers', 'hello-elementor-child' ),
			'type'        => 'number',
			'sanitize'    => 'absint',
			'help'        => __( 'Comfortable maximum. Leave empty to hide.', 'hello-elementor-child' ),
			'placeholder' => '3',
		),
		'_nyc_v_bags'       => array(
			'label'       => __( 'Bags', 'hello-elementor-child' ),
			'type'        => 'number',
			'sanitize'    => 'absint',
			'help'        => __( 'Comfortable maximum. Leave empty to hide.', 'hello-elementor-child' ),
			'placeholder' => '2',
		),
		'_nyc_v_features'   => array(
			'label'       => __( 'Extra features', 'hello-elementor-child' ),
			'type'        => 'textarea',
			'sanitize'    => 'sanitize_textarea_field',
			'help'        => __( 'One per line, shown beside passengers and bags. Prefix with an icon name and a colon to change the icon — e.g. <code>shield:Insured</code> or <code>clock:24/7</code>. Available icons: shield, clock, users, car, plane, star, pin, calendar.', 'hello-elementor-child' ),
			'placeholder' => "shield:Insured\nclock:24/7",
		),
		'_nyc_v_price'      => array(
			'label'       => __( 'Price', 'hello-elementor-child' ),
			'type'        => 'text',
			'sanitize'    => 'sanitize_text_field',
			'help'        => __( 'Free text, e.g. "Request a quote" or "$120".', 'hello-elementor-child' ),
			'placeholder' => 'Request a quote',
		),
		'_nyc_v_price_note' => array(
			'label'       => __( 'Price label', 'hello-elementor-child' ),
			'type'        => 'text',
			'sanitize'    => 'sanitize_text_field',
			'help'        => __( 'The small word above the price.', 'hello-elementor-child' ),
			'placeholder' => 'From',
		),
		'_nyc_v_cta_text'   => array(
			'label'       => __( 'Button text', 'hello-elementor-child' ),
			'type'        => 'text',
			'sanitize'    => 'sanitize_text_field',
			'help'        => __( 'Leave empty to hide the button.', 'hello-elementor-child' ),
			'placeholder' => 'Reserve',
		),
		'_nyc_v_cta_url'    => array(
			'label'       => __( 'Button link', 'hello-elementor-child' ),
			'type'        => 'text',
			'sanitize'    => 'sanitize_text_field',
			'help'        => __( 'Optional. Leave empty to use the booking page set on the shortcode, with this vehicle name added to the link.', 'hello-elementor-child' ),
			'placeholder' => '',
		),
	);
}

/**
 * The spec icons, drawn exactly as the original build drew them.
 *
 * @return array<string, string>
 */
function nyc_vehicle_spec_icons() {
	return array(
		'passenger' => '<circle cx="12" cy="8" r="3.4" stroke="currentColor" stroke-width="1.8"/><path d="M5 20a7 7 0 0 1 14 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>',
		'bag'       => '<rect x="4.5" y="8" width="15" height="12" rx="1.8" stroke="currentColor" stroke-width="1.8"/><path d="M9 8V5.6A1.6 1.6 0 0 1 10.6 4h2.8A1.6 1.6 0 0 1 15 5.6V8" stroke="currentColor" stroke-width="1.8"/>',
		'shield'    => '<path d="M12 3.4 19 6.3v5c0 4.9-3.3 8.4-7 9.8-3.7-1.4-7-4.9-7-9.8v-5l7-2.9Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>',
		'clock'     => '<circle cx="12" cy="12" r="8.6" stroke="currentColor" stroke-width="1.8"/><path d="M12 7.4V12l3.4 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>',
		'users'     => '<circle cx="9.5" cy="9" r="3.2" stroke="currentColor" stroke-width="1.8"/><path d="M3.8 19.2a5.8 5.8 0 0 1 11.4 0M16.2 6.2a3.2 3.2 0 0 1 0 5.9M17.4 14.4a5.8 5.8 0 0 1 2.9 4.8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>',
		'car'       => '<path d="M4 16.5v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-2M16 16.5v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M3.4 16.5h17.2v-4l-1.8-4.6a2 2 0 0 0-1.9-1.3H7.1a2 2 0 0 0-1.9 1.3L3.4 12.5v4Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>',
		'plane'     => '<path d="M3.6 12.4 20 4.2l-7.4 15.6-2-6.6-7-.8Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>',
		'star'      => '<path d="m12 3.6 2.6 5.4 5.9.8-4.3 4.1 1 5.9-5.2-2.8-5.2 2.8 1-5.9L3.5 9.8l5.9-.8L12 3.6Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>',
		'pin'       => '<path d="M12 21s7-5.6 7-11a7 7 0 1 0-14 0c0 5.4 7 11 7 11Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><circle cx="12" cy="10" r="2.5" stroke="currentColor" stroke-width="1.8"/>',
		'calendar'  => '<rect x="3.6" y="5" width="16.8" height="15" rx="2.4" stroke="currentColor" stroke-width="1.8"/><path d="M3.6 9.5h16.8M8 3.5v3M16 3.5v3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>',
	);
}

/**
 * One spec pill.
 *
 * @param string $icon  Icon key.
 * @param string $label Text.
 * @return string
 */
function nyc_vehicle_spec( $icon, $label ) {
	$icons = nyc_vehicle_spec_icons();
	$inner = isset( $icons[ $icon ] ) ? $icons[ $icon ] : $icons['shield'];

	return '<span class="vcard__spec"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false">'
		. $inner . '</svg>' . esc_html( $label ) . '</span>';
}

/* ==========================================================================
   Post type and taxonomy
   ========================================================================== */

/**
 * Register the Vehicles post type and its Vehicle Type taxonomy.
 *
 * Not public: there are no single-vehicle pages and no archive, so nothing is
 * added to the site's URL structure and nothing can collide with a page built
 * in Elementor. It exists to be managed in the dashboard and read by the
 * shortcode.
 *
 * @return void
 */
function nyc_register_fleet() {
	register_post_type(
		NYC_VEHICLE_CPT,
		array(
			'labels'          => array(
				'name'               => __( 'Fleet', 'hello-elementor-child' ),
				'singular_name'      => __( 'Vehicle', 'hello-elementor-child' ),
				'menu_name'          => __( 'Fleet', 'hello-elementor-child' ),
				'add_new'            => __( 'Add Vehicle', 'hello-elementor-child' ),
				'add_new_item'       => __( 'Add Vehicle', 'hello-elementor-child' ),
				'edit_item'          => __( 'Edit Vehicle', 'hello-elementor-child' ),
				'new_item'           => __( 'New Vehicle', 'hello-elementor-child' ),
				'view_item'          => __( 'View Vehicle', 'hello-elementor-child' ),
				'search_items'       => __( 'Search Fleet', 'hello-elementor-child' ),
				'not_found'          => __( 'No vehicles yet.', 'hello-elementor-child' ),
				'not_found_in_trash' => __( 'No vehicles in the bin.', 'hello-elementor-child' ),
				'all_items'          => __( 'All Vehicles', 'hello-elementor-child' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'menu_position'   => 26,
			'menu_icon'       => 'dashicons-car',
			'supports'        => array( 'title', 'thumbnail', 'page-attributes' ),
			'has_archive'     => false,
			'rewrite'         => false,
			'query_var'       => false,
			'capability_type' => 'post',
			'map_meta_cap'    => true,
		)
	);

	register_taxonomy(
		NYC_VEHICLE_TAX,
		NYC_VEHICLE_CPT,
		array(
			'labels'            => array(
				'name'          => __( 'Vehicle Types', 'hello-elementor-child' ),
				'singular_name' => __( 'Vehicle Type', 'hello-elementor-child' ),
				'menu_name'     => __( 'Vehicle Types', 'hello-elementor-child' ),
				'add_new_item'  => __( 'Add Vehicle Type', 'hello-elementor-child' ),
				'all_items'     => __( 'Vehicle Types', 'hello-elementor-child' ),
			),
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'hierarchical'      => true,
			'rewrite'           => false,
			'query_var'         => false,
		)
	);
}
add_action( 'init', 'nyc_register_fleet' );

/* ==========================================================================
   Edit screen
   ========================================================================== */

/**
 * Add the details meta box.
 *
 * @return void
 */
function nyc_vehicle_meta_box() {
	add_meta_box(
		'nyc_vehicle_details',
		__( 'Vehicle Details', 'hello-elementor-child' ),
		'nyc_vehicle_meta_box_render',
		NYC_VEHICLE_CPT,
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'nyc_vehicle_meta_box' );

/**
 * Render the details meta box.
 *
 * @param WP_Post $post Current post.
 * @return void
 */
function nyc_vehicle_meta_box_render( $post ) {
	wp_nonce_field( 'nyc_vehicle_save', 'nyc_vehicle_nonce' );
	?>
	<style>
		.nyc-fleet-fields { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
		.nyc-fleet-fields .nyc-wide { grid-column: 1 / -1; }
		.nyc-fleet-fields label { display: block; font-weight: 600; margin-bottom: 4px; }
		.nyc-fleet-fields input[type="text"],
		.nyc-fleet-fields input[type="number"],
		.nyc-fleet-fields textarea { width: 100%; }
		.nyc-fleet-fields textarea { min-height: 80px; }
		.nyc-fleet-fields p.description { margin-top: 4px; }
		@media (max-width: 782px) { .nyc-fleet-fields { grid-template-columns: minmax(0, 1fr); } }
	</style>
	<div class="nyc-fleet-fields">
		<?php foreach ( nyc_vehicle_fields() as $key => $field ) : ?>
			<?php
			$value = get_post_meta( $post->ID, $key, true );
			$wide  = in_array( $field['type'], array( 'textarea' ), true ) || '_nyc_v_model' === $key;
			?>
			<div class="<?php echo $wide ? 'nyc-wide' : ''; ?>">
				<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ); ?></label>

				<?php if ( 'textarea' === $field['type'] ) : ?>
					<textarea id="<?php echo esc_attr( $key ); ?>"
						name="<?php echo esc_attr( $key ); ?>"
						placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>"><?php echo esc_textarea( $value ); ?></textarea>
				<?php else : ?>
					<input type="<?php echo esc_attr( $field['type'] ); ?>"
						id="<?php echo esc_attr( $key ); ?>"
						name="<?php echo esc_attr( $key ); ?>"
						value="<?php echo esc_attr( $value ); ?>"
						placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" />
				<?php endif; ?>

				<?php if ( $field['help'] ) : ?>
					<p class="description"><?php echo wp_kses( $field['help'], array( 'code' => array() ) ); ?></p>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
	<p class="description" style="margin-top:16px;">
		<?php esc_html_e( 'The vehicle photo is the Featured Image. Card order follows the Order field under Page Attributes, lowest first.', 'hello-elementor-child' ); ?>
	</p>
	<?php
}

/**
 * Save the details meta box.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function nyc_vehicle_save( $post_id ) {
	if ( ! isset( $_POST['nyc_vehicle_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['nyc_vehicle_nonce'] ) ), 'nyc_vehicle_save' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( nyc_vehicle_fields() as $key => $field ) {
		if ( ! isset( $_POST[ $key ] ) ) {
			continue;
		}

		$raw   = wp_unslash( $_POST[ $key ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitised on the next line.
		$value = call_user_func( $field['sanitize'], $raw );

		if ( '' === $value || ( 'absint' === $field['sanitize'] && '' === trim( (string) $raw ) ) ) {
			delete_post_meta( $post_id, $key );
			continue;
		}

		update_post_meta( $post_id, $key, $value );
	}
}
add_action( 'save_post_' . NYC_VEHICLE_CPT, 'nyc_vehicle_save' );

/**
 * Useful columns on the Fleet list table.
 *
 * @param array $columns Existing columns.
 * @return array
 */
function nyc_vehicle_columns( $columns ) {
	$new = array();

	foreach ( $columns as $key => $label ) {
		if ( 'title' === $key ) {
			$new['nyc_thumb'] = __( 'Photo', 'hello-elementor-child' );
		}

		$new[ $key ] = $label;

		if ( 'title' === $key ) {
			$new['nyc_class'] = __( 'Class', 'hello-elementor-child' );
			$new['nyc_seats'] = __( 'Seats / Bags', 'hello-elementor-child' );
		}
	}

	return $new;
}
add_filter( 'manage_' . NYC_VEHICLE_CPT . '_posts_columns', 'nyc_vehicle_columns' );

/**
 * Fill the custom columns.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 * @return void
 */
function nyc_vehicle_column_content( $column, $post_id ) {
	if ( 'nyc_thumb' === $column ) {
		echo has_post_thumbnail( $post_id )
			? get_the_post_thumbnail( $post_id, array( 70, 48 ), array( 'style' => 'width:70px;height:auto;border-radius:4px;' ) )
			: '&mdash;';
		return;
	}

	if ( 'nyc_class' === $column ) {
		$class = get_post_meta( $post_id, '_nyc_v_class', true );
		echo $class ? esc_html( $class ) : '&mdash;';
		return;
	}

	if ( 'nyc_seats' === $column ) {
		$p = get_post_meta( $post_id, '_nyc_v_passengers', true );
		$b = get_post_meta( $post_id, '_nyc_v_bags', true );
		echo esc_html( ( $p ? $p : '–' ) . ' / ' . ( $b ? $b : '–' ) );
	}
}
add_action( 'manage_' . NYC_VEHICLE_CPT . '_posts_custom_column', 'nyc_vehicle_column_content', 10, 2 );

/**
 * Order the Fleet list table by the Order field, like the front end does.
 *
 * @param WP_Query $query Current query.
 * @return void
 */
function nyc_vehicle_admin_order( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( NYC_VEHICLE_CPT !== $query->get( 'post_type' ) || $query->get( 'orderby' ) ) {
		return;
	}

	$query->set( 'orderby', 'menu_order title' );
	$query->set( 'order', 'ASC' );
}
add_action( 'pre_get_posts', 'nyc_vehicle_admin_order' );

/* ==========================================================================
   The [fleet] shortcode
   ========================================================================== */

/**
 * Render the fleet grid.
 *
 * Outputs the cards and, optionally, the filter tabs — and nothing else. No
 * section padding, no width wrapper: the Elementor container it sits in owns
 * those, so the shortcode cannot fight the page layout.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function nyc_fleet_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'heading'  => 'no',
			'eyebrow'  => __( 'Our Fleet', 'hello-elementor-child' ),
			'title'    => __( 'Choose the car that fits the occasion', 'hello-elementor-child' ),
			'intro'    => '',
			'filter'   => 'yes',
			'columns'  => '3',
			'limit'    => '-1',
			'type'     => '',
			'book_url' => '',
			'all_text' => __( 'All Vehicles', 'hello-elementor-child' ),
		),
		$atts,
		'fleet'
	);

	$args = array(
		'post_type'      => NYC_VEHICLE_CPT,
		'post_status'    => 'publish',
		'posts_per_page' => (int) $atts['limit'],
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	);

	if ( $atts['type'] ) {
		$args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- A handful of terms on a small post type.
			array(
				'taxonomy' => NYC_VEHICLE_TAX,
				'field'    => 'slug',
				'terms'    => array_map( 'sanitize_title', explode( ',', $atts['type'] ) ),
			),
		);
	}

	$vehicles = new WP_Query( $args );

	if ( ! $vehicles->have_posts() ) {
		if ( current_user_can( 'edit_posts' ) ) {
			return '<p>' . sprintf(
				/* translators: %s: link to the Fleet screen. */
				esc_html__( 'No vehicles published yet — %s.', 'hello-elementor-child' ),
				'<a href="' . esc_url( admin_url( 'edit.php?post_type=' . NYC_VEHICLE_CPT ) ) . '">' . esc_html__( 'add some under Fleet', 'hello-elementor-child' ) . '</a>'
			) . '</p>';
		}
		return '';
	}

	// Only offer the tabs for types that actually have a vehicle in them.
	$terms = 'yes' === $atts['filter'] && ! $atts['type']
		? get_terms(
			array(
				'taxonomy'   => NYC_VEHICLE_TAX,
				'hide_empty' => true,
			)
		)
		: array();

	$show_tabs = ! is_wp_error( $terms ) && count( $terms ) > 1;
	$columns   = max( 1, min( 4, (int) $atts['columns'] ) );

	ob_start();
	?>
	<div class="nyc-fleet"<?php echo $show_tabs ? ' data-filter' : ''; ?>>

		<?php if ( 'yes' === $atts['heading'] && ( $atts['title'] || $atts['eyebrow'] ) ) : ?>
			<div class="head head--center">
				<?php if ( $atts['eyebrow'] ) : ?>
					<p class="eyebrow"><?php echo esc_html( $atts['eyebrow'] ); ?></p>
				<?php endif; ?>
				<?php if ( $atts['title'] ) : ?>
					<h2><?php echo esc_html( $atts['title'] ); ?></h2>
				<?php endif; ?>
				<?php if ( $atts['intro'] ) : ?>
					<p><?php echo esc_html( $atts['intro'] ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $show_tabs ) : ?>
			<div class="cluster cluster--center" style="margin-bottom: var(--space-7);">
				<div class="tabs" role="group" aria-label="<?php esc_attr_e( 'Filter vehicles by type', 'hello-elementor-child' ); ?>">
					<button type="button" data-filter-btn="all" class="is-active" aria-pressed="true">
						<?php echo esc_html( $atts['all_text'] ); ?>
					</button>
					<?php foreach ( $terms as $term ) : ?>
						<button type="button" data-filter-btn="<?php echo esc_attr( $term->slug ); ?>" aria-pressed="false">
							<?php echo esc_html( $term->name ); ?>
						</button>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="grid grid--<?php echo (int) $columns; ?>">
			<?php
			$index = 0;
			while ( $vehicles->have_posts() ) :
				$vehicles->the_post();
				nyc_fleet_card( get_post(), $atts['book_url'], $index );
				++$index;
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'fleet', 'nyc_fleet_shortcode' );

/**
 * One vehicle card. Same markup as the original .vcard.
 *
 * @param WP_Post $post     Vehicle.
 * @param string  $book_url Fallback booking URL from the shortcode.
 * @param int     $index    Position in the grid, for the reveal stagger.
 * @return void
 */
function nyc_fleet_card( $post, $book_url = '', $index = 0 ) {
	$id         = $post->ID;
	$name       = get_the_title( $id );
	$class      = get_post_meta( $id, '_nyc_v_class', true );
	$model      = get_post_meta( $id, '_nyc_v_model', true );
	$desc       = get_post_meta( $id, '_nyc_v_desc', true );
	$passengers = get_post_meta( $id, '_nyc_v_passengers', true );
	$bags       = get_post_meta( $id, '_nyc_v_bags', true );
	$features   = get_post_meta( $id, '_nyc_v_features', true );
	$price      = get_post_meta( $id, '_nyc_v_price', true );
	$price_note = get_post_meta( $id, '_nyc_v_price_note', true );
	$cta_text   = get_post_meta( $id, '_nyc_v_cta_text', true );
	$cta_url    = get_post_meta( $id, '_nyc_v_cta_url', true );

	// Categories drive the filter tabs.
	$slugs = wp_get_object_terms( $id, NYC_VEHICLE_TAX, array( 'fields' => 'slugs' ) );
	$slug  = ( ! is_wp_error( $slugs ) && ! empty( $slugs ) ) ? $slugs[0] : '';

	// A per-vehicle link wins; otherwise the shortcode's booking page with the
	// vehicle name attached, which is what the static build did.
	if ( ! $cta_url ) {
		$base    = $book_url ? $book_url : nyc_link( nyc_opt( 'nyc_header_cta_url' ) );
		$parts   = explode( '#', $base, 2 );
		$hash    = isset( $parts[1] ) ? '#' . $parts[1] : '';
		$cta_url = add_query_arg( 'vehicle', rawurlencode( $name ), $parts[0] ) . $hash;
	}
	?>
	<?php
	// The stagger the original grid used: cards fade in 1, 2, 3 across a row.
	$delay = ( $index % 3 ) + 1;
	?>
	<article class="vcard"<?php echo $slug ? ' data-category="' . esc_attr( $slug ) . '"' : ''; ?> data-reveal data-reveal-delay="<?php echo (int) $delay; ?>">
		<div class="vcard__media">
			<?php if ( $class ) : ?>
				<span class="tag vcard__class"><?php echo esc_html( $class ); ?></span>
			<?php endif; ?>
			<?php
			if ( has_post_thumbnail( $id ) ) {
				echo get_the_post_thumbnail(
					$id,
					'large',
					array(
						'loading' => 'lazy',
						'alt'     => esc_attr( $name ),
					)
				);
			}
			?>
		</div>

		<div class="vcard__body">
			<h3 class="vcard__name"><?php echo esc_html( $name ); ?></h3>

			<?php if ( $model ) : ?>
				<p class="vcard__model"><?php echo esc_html( $model ); ?></p>
			<?php endif; ?>

			<?php if ( $desc ) : ?>
				<p class="fs-sm" style="margin-top: var(--space-4);"><?php echo esc_html( $desc ); ?></p>
			<?php endif; ?>

			<?php if ( $passengers || $bags || $features ) : ?>
				<div class="vcard__specs">
					<?php
					if ( $passengers ) {
						/* translators: %d: number of passengers. */
						echo nyc_vehicle_spec( 'passenger', sprintf( _n( '%d Passenger', '%d Passengers', (int) $passengers, 'hello-elementor-child' ), (int) $passengers ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped inside nyc_vehicle_spec().
					}

					if ( $bags ) {
						/* translators: %d: number of bags. */
						echo nyc_vehicle_spec( 'bag', sprintf( _n( '%d Bag', '%d Bags', (int) $bags, 'hello-elementor-child' ), (int) $bags ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped inside nyc_vehicle_spec().
					}

					foreach ( preg_split( '/\R/', (string) $features, -1, PREG_SPLIT_NO_EMPTY ) as $line ) {
						$line = trim( $line );
						if ( '' === $line ) {
							continue;
						}

						$icon = 'shield';
						if ( false !== strpos( $line, ':' ) ) {
							list( $maybe_icon, $rest ) = explode( ':', $line, 2 );
							$maybe_icon                = sanitize_key( trim( $maybe_icon ) );

							if ( isset( nyc_vehicle_spec_icons()[ $maybe_icon ] ) ) {
								$icon = $maybe_icon;
								$line = trim( $rest );
							}
						}

						echo nyc_vehicle_spec( $icon, $line ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped inside nyc_vehicle_spec().
					}
					?>
				</div>
			<?php endif; ?>

			<?php if ( $price || $cta_text ) : ?>
				<div class="vcard__foot">
					<?php if ( $price ) : ?>
						<span class="vcard__quote"><?php echo esc_html( $price_note ? $price_note : __( 'From', 'hello-elementor-child' ) ); ?><b><?php echo esc_html( $price ); ?></b></span>
					<?php endif; ?>

					<?php if ( $cta_text ) : ?>
						<a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn--primary btn--sm"><?php echo esc_html( $cta_text ); ?></a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</article>
	<?php
}
