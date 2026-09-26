<?php
/**
 * One-click import of the six vehicles from the original HTML build.
 *
 * Copies the photos out of the theme into the Media Library and creates the
 * vehicles with their exact text, so a fresh install matches the static site
 * before anything is edited. Idempotent: a vehicle whose slug already exists
 * is skipped, never duplicated.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The fleet exactly as the static build had it.
 *
 * @return array<int, array<string, mixed>>
 */
function nyc_fleet_seed_data() {
	return array(
		array(
			'slug'       => 'business-sedan',
			'title'      => 'Business Sedan',
			'type'       => 'Sedans',
			'image'      => 'business-sedan.png',
			'alt'        => 'Black business-class sedan for NYC airport transfers',
			'class'      => 'Business',
			'model'      => 'Cadillac XTS · Lincoln Continental or similar',
			'desc'       => 'The everyday standard for airport runs and single-passenger meetings across Manhattan.',
			'passengers' => 3,
			'bags'       => 2,
		),
		array(
			'slug'       => 'first-class-sedan',
			'title'      => 'First Class Sedan',
			'type'       => 'Sedans',
			'image'      => 'premium-sedan.png',
			'alt'        => 'Black first-class sedan for premium New York chauffeur service',
			'class'      => 'First Class',
			'model'      => 'Mercedes-Benz E-Class · S-Class or similar',
			'desc'       => 'Extended rear legroom and a quieter cabin — for the flight you need to arrive from rested.',
			'passengers' => 3,
			'bags'       => 3,
		),
		array(
			'slug'       => 'luxury-suv',
			'title'      => 'Luxury SUV',
			'type'       => 'SUVs',
			'image'      => 'premium-suv.png',
			'alt'        => 'Black luxury SUV for New York City chauffeur service',
			'class'      => 'Premium',
			'model'      => 'Cadillac Escalade ESV · Lincoln Navigator or similar',
			'desc'       => 'Extra luggage room and ride height for family travel, ski season and weekends out of the city.',
			'passengers' => 5,
			'bags'       => 4,
		),
		array(
			'slug'       => 'executive-limousine',
			'title'      => 'Executive Limousine',
			'type'       => 'Limousines',
			'image'      => 'stretch-limo.png',
			'alt'        => 'Black executive stretch limousine',
			'class'      => 'Signature',
			'model'      => 'Lincoln Continental · MKT Stretch or similar',
			'desc'       => 'Our signature car for board members, red-carpet arrivals and moments meant to be seen.',
			'passengers' => 3,
			'bags'       => 3,
		),
		array(
			'slug'       => 'stretch-limousine',
			'title'      => 'Stretch Limousine',
			'type'       => 'Limousines',
			'image'      => 'suv-stretch-limo.png',
			'alt'        => 'Black SUV stretch limousine for weddings and events',
			'class'      => 'Occasion',
			'model'      => 'Lincoln Navigator · Ford Expedition Stretch or similar',
			'desc'       => 'Reserved for weddings, galas, proms and the arrivals that get photographed.',
			'passengers' => 8,
			'bags'       => 6,
		),
		array(
			'slug'       => 'executive-sprinter',
			'title'      => 'Executive Sprinter',
			'type'       => 'Vans',
			'image'      => 'sprinter-van.png',
			'alt'        => 'Black executive Sprinter van for group transportation in New York',
			'class'      => 'Group',
			'model'      => 'Mercedes-Benz Sprinter · Ford Transit or similar',
			'desc'       => 'Conference-style seating for teams moving between Midtown, downtown and the airports together.',
			'passengers' => 12,
			'bags'       => 10,
		),
	);
}

/**
 * Add the import and preview screens under Fleet.
 *
 * @return void
 */
function nyc_fleet_import_menu() {
	add_submenu_page(
		'edit.php?post_type=' . NYC_VEHICLE_CPT,
		__( 'Preview Fleet', 'hello-elementor-child' ),
		__( 'Preview', 'hello-elementor-child' ),
		'edit_posts',
		'nyc-fleet-preview',
		'nyc_fleet_preview_screen'
	);

	add_submenu_page(
		'edit.php?post_type=' . NYC_VEHICLE_CPT,
		__( 'Import Original Fleet', 'hello-elementor-child' ),
		__( 'Import Original Fleet', 'hello-elementor-child' ),
		'manage_options',
		'nyc-fleet-import',
		'nyc_fleet_import_screen'
	);
}
add_action( 'admin_menu', 'nyc_fleet_import_menu' );

/**
 * Put the demo fleet in once, on the first admin load after the theme goes
 * live, so there is something real on screen instead of an empty grid.
 *
 * Runs at most once ever: the flag is set either way, and it bails the moment
 * it finds a vehicle already there, so it can never overwrite real content or
 * run a second time.
 *
 * @return void
 */
function nyc_fleet_maybe_seed() {
	if ( get_option( 'nyc_fleet_demo_imported' ) ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$existing = get_posts(
		array(
			'post_type'        => NYC_VEHICLE_CPT,
			'post_status'      => 'any',
			'numberposts'      => 1,
			'fields'           => 'ids',
			'suppress_filters' => false,
		)
	);

	// Someone has already added vehicles — leave them completely alone.
	if ( ! empty( $existing ) ) {
		update_option( 'nyc_fleet_demo_imported', 1 );
		return;
	}

	// Flag first, import second. If the import hits trouble halfway, this has
	// still had its one attempt and will not retry on every admin page load —
	// the button on the import screen is there to finish the job.
	update_option( 'nyc_fleet_demo_imported', 1 );

	nyc_fleet_run_import();
}
add_action( 'admin_init', 'nyc_fleet_maybe_seed' );

/* ==========================================================================
   Preview
   ========================================================================== */

/**
 * The preview screen: the real shortcode output in an iframe, at three widths.
 *
 * An iframe rather than rendering inline, because the front-end stylesheets
 * would otherwise land in wp-admin and restyle the dashboard around it.
 *
 * @return void
 */
function nyc_fleet_preview_screen() {
	$src = wp_nonce_url(
		admin_url( 'admin-post.php?action=nyc_fleet_preview' ),
		'nyc_fleet_preview'
	);
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Preview Fleet', 'hello-elementor-child' ); ?></h1>
		<p>
			<?php esc_html_e( 'Exactly what [fleet] renders, with your live vehicles and the site\'s own stylesheets. Change a vehicle, reload this page, see it.', 'hello-elementor-child' ); ?>
		</p>

		<p>
			<button type="button" class="button button-primary" data-nyc-width="100%"><?php esc_html_e( 'Desktop', 'hello-elementor-child' ); ?></button>
			<button type="button" class="button" data-nyc-width="900px"><?php esc_html_e( 'Tablet', 'hello-elementor-child' ); ?></button>
			<button type="button" class="button" data-nyc-width="390px"><?php esc_html_e( 'Mobile', 'hello-elementor-child' ); ?></button>
			<a class="button" href="<?php echo esc_url( $src ); ?>" target="_blank" rel="noopener">
				<?php esc_html_e( 'Open in a new tab', 'hello-elementor-child' ); ?>
			</a>
		</p>

		<div style="background:#f0f0f1;padding:16px;border:1px solid #c3c4c7;border-radius:4px;">
			<iframe id="nyc-fleet-preview"
				src="<?php echo esc_url( $src ); ?>"
				style="width:100%;max-width:100%;height:1400px;border:0;background:#fff;display:block;margin:0 auto;box-shadow:0 1px 4px rgba(0,0,0,.12);"
				title="<?php esc_attr_e( 'Fleet preview', 'hello-elementor-child' ); ?>"></iframe>
		</div>

		<p class="description" style="margin-top:12px;">
			<?php esc_html_e( 'The filter tabs work in here too — the preview loads the same script the live site uses.', 'hello-elementor-child' ); ?>
		</p>
	</div>

	<script>
	( function () {
		var frame   = document.getElementById( 'nyc-fleet-preview' );
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
 * Render the preview document itself.
 *
 * A minimal standalone page: the project's stylesheets, the shortcode, and
 * the one script the filter tabs need. No wp_head, so nothing a plugin
 * enqueues can muddy what you are looking at.
 *
 * @return void
 */
function nyc_fleet_preview_render() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_die( esc_html__( 'You do not have permission to do that.', 'hello-elementor-child' ) );
	}

	check_admin_referer( 'nyc_fleet_preview' );

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
	<title><?php esc_html_e( 'Fleet preview', 'hello-elementor-child' ); ?></title>
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet" />
	<link rel="stylesheet" href="<?php echo esc_url( $uri . '/assets/css/variables.css?v=' . $ver ); ?>" />
	<link rel="stylesheet" href="<?php echo esc_url( $uri . '/assets/css/base.css?v=' . $ver ); ?>" />
	<link rel="stylesheet" href="<?php echo esc_url( $uri . '/assets/css/components.css?v=' . $ver ); ?>" />
	<style>
		/* Stands in for the Elementor container the shortcode will really sit
		   in, so the preview is framed the way the page will frame it. */
		body { background: var(--bg-soft); }
		.nyc-preview-shell {
			max-width: var(--shell);
			margin-inline: auto;
			padding: var(--section-y) var(--gutter);
		}
	</style>
</head>
<body>
	<div class="nyc-preview-shell">
		<?php echo do_shortcode( '[fleet heading="yes"]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Shortcode output is escaped at source. ?>
	</div>
	<script src="<?php echo esc_url( $uri . '/assets/js/components.js?v=' . $ver ); ?>"></script>
</body>
</html>
	<?php
	exit;
}
add_action( 'admin_post_nyc_fleet_preview', 'nyc_fleet_preview_render' );

/**
 * Render the import screen and handle the submission.
 *
 * @return void
 */
function nyc_fleet_import_screen() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to do that.', 'hello-elementor-child' ) );
	}

	$result = null;

	if ( isset( $_POST['nyc_fleet_import'] ) && check_admin_referer( 'nyc_fleet_import' ) ) {
		$result = nyc_fleet_run_import();
	}

	$existing = wp_count_posts( NYC_VEHICLE_CPT );
	$total    = (int) $existing->publish + (int) $existing->draft;
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Import Original Fleet', 'hello-elementor-child' ); ?></h1>

		<?php if ( $result ) : ?>
			<div class="notice notice-success">
				<p>
					<?php
					printf(
						/* translators: 1: number created, 2: number skipped. */
						esc_html__( 'Done. %1$d vehicles created, %2$d already existed and were left alone.', 'hello-elementor-child' ),
						(int) $result['created'],
						(int) $result['skipped']
					);
					?>
				</p>
				<?php if ( ! empty( $result['errors'] ) ) : ?>
					<p><strong><?php esc_html_e( 'Photos that could not be imported:', 'hello-elementor-child' ); ?></strong>
						<?php echo esc_html( implode( ', ', $result['errors'] ) ); ?>
						<?php esc_html_e( 'The vehicles were still created — set their Featured Image by hand.', 'hello-elementor-child' ); ?>
					</p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<p>
			<?php esc_html_e( 'Creates the six vehicles from the original site — names, models, descriptions, capacities and photos — so you have something real to edit instead of a blank screen.', 'hello-elementor-child' ); ?>
		</p>
		<p>
			<?php esc_html_e( 'This normally runs by itself the first time you open the dashboard after activating the theme, so the vehicles are usually already here. Use the button if that did not happen, or if you deleted them and want them back.', 'hello-elementor-child' ); ?>
		</p>
		<p>
			<?php esc_html_e( 'Safe to run more than once: a vehicle that already exists is skipped, never duplicated or overwritten.', 'hello-elementor-child' ); ?>
			<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=' . NYC_VEHICLE_CPT . '&page=nyc-fleet-preview' ) ); ?>">
				<?php esc_html_e( 'See how it looks', 'hello-elementor-child' ); ?>
			</a>
		</p>

		<?php if ( $total > 0 ) : ?>
			<p><em>
				<?php
				printf(
					/* translators: %d: number of vehicles. */
					esc_html__( 'You currently have %d vehicles.', 'hello-elementor-child' ),
					(int) $total
				);
				?>
			</em></p>
		<?php endif; ?>

		<form method="post">
			<?php wp_nonce_field( 'nyc_fleet_import' ); ?>
			<p>
				<button type="submit" name="nyc_fleet_import" value="1" class="button button-primary">
					<?php esc_html_e( 'Import the six original vehicles', 'hello-elementor-child' ); ?>
				</button>
			</p>
		</form>

		<h2><?php esc_html_e( 'Showing the fleet on a page', 'hello-elementor-child' ); ?></h2>
		<p><?php esc_html_e( 'Drop a Shortcode widget into any Elementor page and use:', 'hello-elementor-child' ); ?></p>
		<p><code>[fleet]</code></p>
		<p><?php esc_html_e( 'Attributes, all optional:', 'hello-elementor-child' ); ?></p>
		<table class="widefat striped" style="max-width:820px">
			<tbody>
				<tr><td><code>filter="no"</code></td><td><?php esc_html_e( 'Hide the type tabs.', 'hello-elementor-child' ); ?></td></tr>
				<tr><td><code>columns="2"</code></td><td><?php esc_html_e( 'Cards per row, 1 to 4. Default 3.', 'hello-elementor-child' ); ?></td></tr>
				<tr><td><code>type="sedans"</code></td><td><?php esc_html_e( 'Show one vehicle type only. Use the type slug; several can be comma separated.', 'hello-elementor-child' ); ?></td></tr>
				<tr><td><code>limit="3"</code></td><td><?php esc_html_e( 'Show only the first few vehicles.', 'hello-elementor-child' ); ?></td></tr>
				<tr><td><code>heading="yes"</code></td><td><?php esc_html_e( 'Add the eyebrow and title above the grid. Off by default, so your Elementor heading widget stays in charge.', 'hello-elementor-child' ); ?></td></tr>
				<tr><td><code>book_url="/contact/#book"</code></td><td><?php esc_html_e( 'Where the Reserve buttons point. The vehicle name is added to the link automatically.', 'hello-elementor-child' ); ?></td></tr>
			</tbody>
		</table>
	</div>
	<?php
}

/**
 * Create the vehicles.
 *
 * @return array{created:int, skipped:int, errors:array<int,string>}
 */
function nyc_fleet_run_import() {
	$created = 0;
	$skipped = 0;
	$errors  = array();

	foreach ( nyc_fleet_seed_data() as $seed ) {
		$existing = get_posts(
			array(
				'post_type'        => NYC_VEHICLE_CPT,
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
				'post_type'   => NYC_VEHICLE_CPT,
				'post_title'  => $seed['title'],
				'post_name'   => $seed['slug'],
				'post_status' => 'publish',
				'menu_order'  => $created + $skipped,
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			$errors[] = $seed['title'];
			continue;
		}

		update_post_meta( $post_id, '_nyc_v_class', $seed['class'] );
		update_post_meta( $post_id, '_nyc_v_model', $seed['model'] );
		update_post_meta( $post_id, '_nyc_v_desc', $seed['desc'] );
		update_post_meta( $post_id, '_nyc_v_passengers', (int) $seed['passengers'] );
		update_post_meta( $post_id, '_nyc_v_bags', (int) $seed['bags'] );
		update_post_meta( $post_id, '_nyc_v_features', "shield:Insured\nclock:24/7" );
		update_post_meta( $post_id, '_nyc_v_price', 'Request a quote' );
		update_post_meta( $post_id, '_nyc_v_price_note', 'From' );
		update_post_meta( $post_id, '_nyc_v_cta_text', 'Reserve' );

		wp_set_object_terms( $post_id, $seed['type'], NYC_VEHICLE_TAX );

		$attachment_id = nyc_fleet_import_image( $seed['image'], $seed['alt'], $post_id );

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

/**
 * Copy one vehicle photo from the theme into the Media Library.
 *
 * @param string $filename File inside /assets/img/fleet/.
 * @param string $alt      Alt text.
 * @param int    $post_id  Vehicle to attach it to.
 * @return int|WP_Error Attachment ID.
 */
function nyc_fleet_import_image( $filename, $alt, $post_id ) {
	return nyc_sideload_theme_image( "fleet/" . $filename, $alt, $post_id );
}
