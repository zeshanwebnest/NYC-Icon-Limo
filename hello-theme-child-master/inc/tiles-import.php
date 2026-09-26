<?php
/**
 * Demo import, preview and migration for the Services and Events tiles.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The tiles from the original build, keyed by post type.
 *
 * Link targets are the original paths rewritten as WordPress permalinks —
 * adjust them once your pages exist.
 *
 * @param string $type Post type.
 * @return array<int, array<string, mixed>>
 */
function nyc_tiles_seed_data( $type ) {
	$services = array(
		array(
			'slug'      => 'airport-transportation',
			'title'     => 'Airport Transportation',
			'image'     => 'airport-meet-and-greet.png',
			'alt'       => 'Chauffeur meeting an arriving traveller with luggage at the terminal',
			'label'     => 'Most Requested',
			'desc'      => 'JFK, LaGuardia and Newark in both directions, with live flight tracking, meet-and-greet arrivals and pickup times that move with your flight.',
			'link_text' => 'Airport Transfers',
			'link_url'  => '/airport-transfers/',
			'wide'      => 1,
		),
		array(
			'slug'      => 'corporate-transportation',
			'title'     => 'Corporate Transportation',
			'image'     => 'executive-arrival.jpg',
			'alt'       => 'Executive arriving by chauffeured sedan outside a Manhattan office',
			'label'     => 'For Business',
			'desc'      => 'Executive travel, client pickups and roadshows on one account with consolidated billing.',
			'link_text' => 'Corporate Travel',
			'link_url'  => '/corporate-travel/',
		),
		array(
			'slug'      => 'hourly-chauffeur-service',
			'title'     => 'Hourly Chauffeur',
			'image'     => 'chauffeur-driving.png',
			'alt'       => 'Chauffeur driving a luxury vehicle through New York City',
			'label'     => 'By the Hour',
			'desc'      => 'A car and driver held for you across a full day of meetings, appointments or errands.',
			'link_text' => 'Hourly Chauffeur',
			'link_url'  => '/hourly-chauffeur/',
		),
		array(
			'slug'      => 'events-and-weddings',
			'title'     => 'Events & Weddings',
			'image'     => 'wedding-limo-interior.png',
			'alt'       => 'Limousine interior prepared for a wedding party',
			'label'     => 'Occasions',
			'desc'      => 'Multi-vehicle schedules for weddings, galas, premieres and private celebrations.',
			'link_text' => 'Events & Weddings',
			'link_url'  => '/events/',
		),
		array(
			'slug'      => 'point-to-point',
			'title'     => 'Point-to-Point',
			'image'     => 'black-on-black.jpeg',
			'alt'       => 'Detail of a black luxury sedan at night',
			'label'     => 'City Travel',
			'desc'      => 'One clean route between any two addresses across the five boroughs, at a fare agreed before you travel.',
			'link_text' => 'How It Works',
			'link_url'  => '#point-to-point',
		),
		array(
			'slug'      => 'private-transportation',
			'title'     => 'Private Transportation',
			'image'     => 'cabin-interior.jpg',
			'alt'       => 'Rear cabin of a luxury vehicle with leather seating',
			'label'     => 'Discretion',
			'desc'      => 'Standing arrangements and personal chauffeur service for clients who travel the same routes every week.',
			'link_text' => 'Speak to Us',
			'link_url'  => '/contact/#book',
		),
	);

	$events = array(
		array(
			'slug'      => 'weddings',
			'title'     => 'Weddings',
			'image'     => 'wedding-limo-interior.png',
			'alt'       => 'Wedding limousine interior prepared for the bridal party',
			'label'     => 'The Big One',
			'desc'      => 'Bridal party, family and guest shuttles — timed to the ceremony rather than to the traffic.',
			'link_text' => 'Wedding Transport',
			'link_url'  => '#weddings',
		),
		array(
			'slug'      => 'galas-and-benefits',
			'title'     => 'Galas & Benefits',
			'image'     => 'hotel-arrival-nyc.jpg',
			'alt'       => 'Guests arriving by chauffeured SUV at an evening venue in Manhattan',
			'label'     => 'Black Tie',
			'desc'      => 'Coordinated arrivals and a chauffeur waiting when the evening ends, however late that turns out to be.',
			'link_text' => 'Reserve',
			'link_url'  => '/contact/?service=Event+or+Wedding#book',
		),
		array(
			'slug'      => 'concerts-and-premieres',
			'title'     => 'Concerts & Premieres',
			'image'     => 'escalade-nyc-street.png',
			'alt'       => 'Black SUV waiting outside a New York venue at night',
			'label'     => 'Nights Out',
			'desc'      => 'Door-to-door for the show, with a pickup point agreed in advance so nobody hunts for a car afterwards.',
			'link_text' => 'Reserve',
			'link_url'  => '/contact/?service=Event+or+Wedding#book',
		),
		array(
			'slug'      => 'private-parties',
			'title'     => 'Private Parties',
			'image'     => 'limo-interior-celebration.png',
			'alt'       => 'Stretch limousine interior set for a private celebration',
			'label'     => 'Celebrations',
			'desc'      => 'Birthdays, anniversaries, proms and the evenings that deserve more than a rideshare.',
			'link_text' => 'Reserve',
			'link_url'  => '/contact/?service=Event+or+Wedding#book',
		),
		array(
			'slug'      => 'corporate-events',
			'title'     => 'Corporate Events',
			'image'     => 'fleet-lineup.jpeg',
			'alt'       => 'Line of black vehicles ready for a corporate event transfer',
			'label'     => 'Business',
			'desc'      => 'Conferences, offsites and client dinners with multi-vehicle schedules run from one plan.',
			'link_text' => 'Corporate Travel',
			'link_url'  => '/corporate-travel/',
		),
		array(
			'slug'      => 'nyc-nightlife',
			'title'     => 'NYC Nightlife',
			'image'     => 'black-on-black.jpeg',
			'alt'       => 'Black luxury vehicle at night in New York City',
			'label'     => 'After Dark',
			'desc'      => 'A car and chauffeur held for the night, so the evening ends the way it started.',
			'link_text' => 'Hourly Chauffeur',
			'link_url'  => '/hourly-chauffeur/',
		),
	);

	return NYC_EVENT_CPT === $type ? $events : $services;
}

/* ==========================================================================
   Migration from the single mixed list
   ========================================================================== */

/**
 * Move tiles that were filed under the old Events group to the Events type.
 *
 * Earlier versions kept both grids in one post type separated by a Group
 * taxonomy, which made for one confusing list. Anything already filed as an
 * event is moved across here, keeping its content, photo and order — nothing
 * is recreated and nothing is lost.
 *
 * @return void
 */
function nyc_tiles_migrate_split() {
	if ( get_option( 'nyc_tiles_split_migrated' ) ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// One attempt, whatever happens, so a hiccup cannot turn into a loop on
	// every admin page load.
	update_option( 'nyc_tiles_split_migrated', 1 );

	$term = get_term_by( 'slug', 'events', NYC_TILE_LEGACY_TAX );

	if ( ! $term || is_wp_error( $term ) ) {
		return;
	}

	$ids = get_posts(
		array(
			'post_type'        => NYC_SERVICE_CPT,
			'post_status'      => 'any',
			'numberposts'      => -1,
			'fields'           => 'ids',
			'suppress_filters' => false,
			'tax_query'        => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- One-time migration.
				array(
					'taxonomy' => NYC_TILE_LEGACY_TAX,
					'field'    => 'term_id',
					'terms'    => $term->term_id,
				),
			),
		)
	);

	foreach ( $ids as $id ) {
		set_post_type( $id, NYC_EVENT_CPT );
		wp_delete_object_term_relationships( $id, NYC_TILE_LEGACY_TAX );
	}

	if ( ! empty( $ids ) ) {
		// They exist now, so the demo seeder must not add a second set.
		update_option( 'nyc_events_demo_imported', 1 );
	}

	// The remaining tiles no longer need a group.
	$leftovers = get_posts(
		array(
			'post_type'        => NYC_SERVICE_CPT,
			'post_status'      => 'any',
			'numberposts'      => -1,
			'fields'           => 'ids',
			'suppress_filters' => false,
		)
	);

	foreach ( $leftovers as $id ) {
		wp_delete_object_term_relationships( $id, NYC_TILE_LEGACY_TAX );
	}
}
add_action( 'admin_init', 'nyc_tiles_migrate_split', 5 );

/* ==========================================================================
   Seeding
   ========================================================================== */

/**
 * Seed each tile type once, on the first admin load.
 *
 * @return void
 */
function nyc_tiles_maybe_seed() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	foreach ( nyc_tile_types() as $type => $config ) {
		if ( get_option( $config['option'] ) ) {
			continue;
		}

		$existing = get_posts(
			array(
				'post_type'        => $type,
				'post_status'      => 'any',
				'numberposts'      => 1,
				'fields'           => 'ids',
				'suppress_filters' => false,
			)
		);

		if ( ! empty( $existing ) ) {
			update_option( $config['option'], 1 );
			continue;
		}

		// Flag first, import second — one attempt, never a retry loop.
		update_option( $config['option'], 1 );

		nyc_tiles_run_import( $type );
	}
}
add_action( 'admin_init', 'nyc_tiles_maybe_seed', 10 );

/**
 * Create the tiles for one type.
 *
 * @param string $type Post type.
 * @return array{created:int, skipped:int, errors:array<int,string>}
 */
function nyc_tiles_run_import( $type ) {
	$created = 0;
	$skipped = 0;
	$errors  = array();
	$order   = 0;

	foreach ( nyc_tiles_seed_data( $type ) as $seed ) {
		++$order;

		$existing = get_posts(
			array(
				'post_type'        => $type,
				'name'             => $seed['slug'],
				'post_status'      => 'any',
				'numberposts'      => 1,
				'fields'           => 'ids',
				'suppress_filters' => false,
			)
		);

		if ( ! empty( $existing ) ) {
			++$skipped;
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'   => $type,
				'post_title'  => $seed['title'],
				'post_name'   => $seed['slug'],
				'post_status' => 'publish',
				'menu_order'  => $order,
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			$errors[] = $seed['title'];
			continue;
		}

		update_post_meta( $post_id, '_nyc_s_label', $seed['label'] );
		update_post_meta( $post_id, '_nyc_s_desc', $seed['desc'] );
		update_post_meta( $post_id, '_nyc_s_link_text', $seed['link_text'] );
		update_post_meta( $post_id, '_nyc_s_link_url', $seed['link_url'] );

		if ( ! empty( $seed['wide'] ) ) {
			update_post_meta( $post_id, '_nyc_s_wide', 1 );
		}

		$attachment_id = nyc_sideload_theme_image( 'scenes/' . $seed['image'], $seed['alt'], $post_id );

		if ( is_wp_error( $attachment_id ) ) {
			$errors[] = $seed['title'];
		} else {
			set_post_thumbnail( $post_id, $attachment_id );
		}

		++$created;
	}

	return array(
		'created' => $created,
		'skipped' => $skipped,
		'errors'  => $errors,
	);
}

/* ==========================================================================
   Admin screens — one Preview and one Import under each menu
   ========================================================================== */

/**
 * Add the screens.
 *
 * @return void
 */
function nyc_tiles_admin_menu() {
	foreach ( nyc_tile_types() as $type => $config ) {
		add_submenu_page(
			'edit.php?post_type=' . $type,
			/* translators: %s: plural type name. */
			sprintf( __( 'Preview %s', 'hello-elementor-child' ), $config['plural'] ),
			__( 'Preview', 'hello-elementor-child' ),
			'edit_posts',
			'nyc-tiles-preview-' . $type,
			'nyc_tiles_preview_screen'
		);

		add_submenu_page(
			'edit.php?post_type=' . $type,
			__( 'Import Demo Content', 'hello-elementor-child' ),
			__( 'Import Demo Content', 'hello-elementor-child' ),
			'manage_options',
			'nyc-tiles-import-' . $type,
			'nyc_tiles_import_screen'
		);
	}
}
add_action( 'admin_menu', 'nyc_tiles_admin_menu' );

/**
 * Which tile type the current admin screen belongs to.
 *
 * @return string
 */
function nyc_tiles_current_type() {
	$type = isset( $_GET['post_type'] ) ? sanitize_key( wp_unslash( $_GET['post_type'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reading the screen context, not acting on it.

	return array_key_exists( $type, nyc_tile_types() ) ? $type : NYC_SERVICE_CPT;
}

/**
 * Render the import screen and handle the submission.
 *
 * @return void
 */
function nyc_tiles_import_screen() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to do that.', 'hello-elementor-child' ) );
	}

	$type   = nyc_tiles_current_type();
	$types  = nyc_tile_types();
	$config = $types[ $type ];
	$result = null;

	if ( isset( $_POST['nyc_tiles_import'] ) && check_admin_referer( 'nyc_tiles_import' ) ) {
		$result = nyc_tiles_run_import( $type );
	}
	?>
	<div class="wrap">
		<h1>
			<?php
			/* translators: %s: plural type name. */
			printf( esc_html__( 'Import Demo %s', 'hello-elementor-child' ), esc_html( $config['plural'] ) );
			?>
		</h1>

		<?php if ( $result ) : ?>
			<div class="notice notice-success">
				<p>
					<?php
					printf(
						/* translators: 1: number created, 2: number skipped. */
						esc_html__( 'Done. %1$d created, %2$d already existed and were left alone.', 'hello-elementor-child' ),
						(int) $result['created'],
						(int) $result['skipped']
					);
					?>
				</p>
				<?php if ( ! empty( $result['errors'] ) ) : ?>
					<p><strong><?php esc_html_e( 'Photos that could not be imported:', 'hello-elementor-child' ); ?></strong>
						<?php echo esc_html( implode( ', ', $result['errors'] ) ); ?>
					</p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<p>
			<?php
			printf(
				/* translators: %d: number of tiles. */
				esc_html__( 'Creates the %d tiles from the original site, photos and all.', 'hello-elementor-child' ),
				count( nyc_tiles_seed_data( $type ) )
			);
			?>
			<?php esc_html_e( 'This normally runs by itself the first time you open the dashboard after activating the theme.', 'hello-elementor-child' ); ?>
		</p>
		<p>
			<?php esc_html_e( 'Safe to run more than once: anything that already exists is skipped, never duplicated or overwritten.', 'hello-elementor-child' ); ?>
			<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . $type . '&page=nyc-tiles-preview-' . $type ) ); ?>">
				<?php esc_html_e( 'See how it looks', 'hello-elementor-child' ); ?>
			</a>
		</p>

		<form method="post">
			<?php wp_nonce_field( 'nyc_tiles_import' ); ?>
			<p>
				<button type="submit" name="nyc_tiles_import" value="1" class="button button-primary">
					<?php
					/* translators: %s: plural type name. */
					printf( esc_html__( 'Import the original %s', 'hello-elementor-child' ), esc_html( strtolower( $config['plural'] ) ) );
					?>
				</button>
			</p>
		</form>

		<h2><?php esc_html_e( 'Showing the grid on a page', 'hello-elementor-child' ); ?></h2>
		<p><?php esc_html_e( 'Drop a Shortcode widget into any Elementor page:', 'hello-elementor-child' ); ?></p>
		<p><code>[<?php echo esc_html( $config['shortcode'] ); ?>]</code></p>
		<table class="widefat striped" style="max-width:820px">
			<tbody>
				<tr><td><code>columns="2"</code></td><td><?php esc_html_e( 'Tiles per row, 1 to 4. Default 3.', 'hello-elementor-child' ); ?></td></tr>
				<tr><td><code>limit="3"</code></td><td><?php esc_html_e( 'Show only the first few.', 'hello-elementor-child' ); ?></td></tr>
				<tr><td><code>heading="yes"</code></td><td><?php esc_html_e( 'Add the eyebrow and title above the grid. Off by default, so your Elementor heading widget stays in charge.', 'hello-elementor-child' ); ?></td></tr>
			</tbody>
		</table>
	</div>
	<?php
}

/**
 * The preview screen.
 *
 * @return void
 */
function nyc_tiles_preview_screen() {
	$type   = nyc_tiles_current_type();
	$types  = nyc_tile_types();
	$config = $types[ $type ];

	$src = wp_nonce_url(
		add_query_arg(
			array(
				'action' => 'nyc_tiles_preview',
				'type'   => $type,
			),
			admin_url( 'admin-post.php' )
		),
		'nyc_tiles_preview'
	);
	?>
	<div class="wrap">
		<h1>
			<?php
			/* translators: %s: plural type name. */
			printf( esc_html__( 'Preview %s', 'hello-elementor-child' ), esc_html( $config['plural'] ) );
			?>
		</h1>
		<p>
			<?php
			printf(
				/* translators: %s: shortcode name. */
				esc_html__( 'Exactly what [%s] renders, with your live tiles and the site\'s own stylesheets.', 'hello-elementor-child' ),
				esc_html( $config['shortcode'] )
			);
			?>
		</p>

		<p>
			<button type="button" class="button button-primary" data-nyc-width="100%"><?php esc_html_e( 'Desktop', 'hello-elementor-child' ); ?></button>
			<button type="button" class="button" data-nyc-width="900px"><?php esc_html_e( 'Tablet', 'hello-elementor-child' ); ?></button>
			<button type="button" class="button" data-nyc-width="390px"><?php esc_html_e( 'Mobile', 'hello-elementor-child' ); ?></button>
			<a class="button" href="<?php echo esc_url( $src ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Open in a new tab', 'hello-elementor-child' ); ?></a>
		</p>

		<div style="background:#f0f0f1;padding:16px;border:1px solid #c3c4c7;border-radius:4px;">
			<iframe id="nyc-tiles-preview"
				src="<?php echo esc_url( $src ); ?>"
				style="width:100%;max-width:100%;height:1500px;border:0;background:#fff;display:block;margin:0 auto;box-shadow:0 1px 4px rgba(0,0,0,.12);"
				title="<?php esc_attr_e( 'Tile preview', 'hello-elementor-child' ); ?>"></iframe>
		</div>
	</div>

	<script>
	( function () {
		var frame   = document.getElementById( 'nyc-tiles-preview' );
		var buttons = document.querySelectorAll( '[data-nyc-width]' );

		buttons.forEach( function ( button ) {
			button.addEventListener( 'click', function () {
				frame.style.width = button.getAttribute( 'data-nyc-width' );
				buttons.forEach( function ( other ) {
					other.classList.toggle( 'button-primary', other === button );
				} );
			} );
		} );
	}() );
	</script>
	<?php
}

/**
 * Render the preview document.
 *
 * @return void
 */
function nyc_tiles_preview_render() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_die( esc_html__( 'You do not have permission to do that.', 'hello-elementor-child' ) );
	}

	check_admin_referer( 'nyc_tiles_preview' );

	$types = nyc_tile_types();
	$type  = isset( $_GET['type'] ) ? sanitize_key( wp_unslash( $_GET['type'] ) ) : NYC_SERVICE_CPT;
	$type  = array_key_exists( $type, $types ) ? $type : NYC_SERVICE_CPT;

	$uri = get_stylesheet_directory_uri();
	$ver = defined( 'NYC_ICON_VERSION' ) ? NYC_ICON_VERSION : '1';

	nocache_headers();
	header( 'Content-Type: text/html; charset=utf-8' );
	?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php esc_html_e( 'Tile preview', 'hello-elementor-child' ); ?></title>
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet" />
	<link rel="stylesheet" href="<?php echo esc_url( $uri . '/assets/css/variables.css?v=' . $ver ); ?>" />
	<link rel="stylesheet" href="<?php echo esc_url( $uri . '/assets/css/base.css?v=' . $ver ); ?>" />
	<link rel="stylesheet" href="<?php echo esc_url( $uri . '/assets/css/components.css?v=' . $ver ); ?>" />
	<style>
		.nyc-preview-shell {
			max-width: var(--shell);
			margin-inline: auto;
			padding: var(--section-y) var(--gutter);
		}
	</style>
</head>
<body>
	<div class="nyc-preview-shell">
		<?php
		echo do_shortcode( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Shortcode output is escaped at source.
			sprintf( '[%s heading="yes"]', $types[ $type ]['shortcode'] )
		);
		?>
	</div>
</body>
</html>
	<?php
	exit;
}
add_action( 'admin_post_nyc_tiles_preview', 'nyc_tiles_preview_render' );
