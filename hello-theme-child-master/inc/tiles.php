<?php
/**
 * Services and Events — the image-led .svc tiles.
 *
 * Two post types, each with its own dashboard menu and its own shortcode, so
 * the two grids never share a list. They draw the same tile, so the markup,
 * the fields and the edit screen live here once and both types use them.
 *
 * Registers nothing global, enqueues nothing, and outputs only inside its own
 * shortcodes.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const NYC_SERVICE_CPT = 'nyc_service';
const NYC_EVENT_CPT   = 'nyc_event';

/**
 * Legacy taxonomy from when both grids shared one post type.
 *
 * Still registered, hidden, so the one-time migration in tiles-import.php can
 * read the old Group assignments. Nothing writes to it any more.
 */
const NYC_TILE_LEGACY_TAX = 'nyc_service_group';

/**
 * The two tile types, and everything that differs between them.
 *
 * @return array<string, array<string, mixed>>
 */
function nyc_tile_types() {
	return array(
		NYC_SERVICE_CPT => array(
			'shortcode'     => 'services',
			'menu_icon'     => 'dashicons-screenoptions',
			'menu_position' => 27,
			'option'        => 'nyc_services_demo_imported',
			'singular'      => __( 'Service', 'hello-elementor-child' ),
			'plural'        => __( 'Services', 'hello-elementor-child' ),
			'heading'       => __( 'Choose the service that fits the day', 'hello-elementor-child' ),
			'eyebrow'       => __( 'Our Services', 'hello-elementor-child' ),
		),
		NYC_EVENT_CPT   => array(
			'shortcode'     => 'events',
			'menu_icon'     => 'dashicons-star-filled',
			'menu_position' => 28,
			'option'        => 'nyc_events_demo_imported',
			'singular'      => __( 'Event', 'hello-elementor-child' ),
			'plural'        => __( 'Events', 'hello-elementor-child' ),
			'heading'       => __( 'Every occasion New York throws at you', 'hello-elementor-child' ),
			'eyebrow'       => __( 'Occasions', 'hello-elementor-child' ),
		),
	);
}

/**
 * The meta fields a tile carries. Shared by both types.
 *
 * @return array<string, array{label:string, type:string, sanitize:string, help:string, placeholder:string}>
 */
function nyc_tile_fields() {
	return array(
		'_nyc_s_label'     => array(
			'label'       => __( 'Label', 'hello-elementor-child' ),
			'type'        => 'text',
			'sanitize'    => 'sanitize_text_field',
			'help'        => __( 'The small gold pill above the title, e.g. Most Requested, Black Tie. Leave empty to hide it.', 'hello-elementor-child' ),
			'placeholder' => 'Most Requested',
		),
		'_nyc_s_desc'      => array(
			'label'       => __( 'Description', 'hello-elementor-child' ),
			'type'        => 'textarea',
			'sanitize'    => 'sanitize_textarea_field',
			'help'        => __( 'One or two sentences under the title.', 'hello-elementor-child' ),
			'placeholder' => '',
		),
		'_nyc_s_link_text' => array(
			'label'       => __( 'Link text', 'hello-elementor-child' ),
			'type'        => 'text',
			'sanitize'    => 'sanitize_text_field',
			'help'        => __( 'The gold arrow link at the bottom. Leave empty to hide it.', 'hello-elementor-child' ),
			'placeholder' => 'Airport Transfers',
		),
		'_nyc_s_link_url'  => array(
			'label'       => __( 'Link URL', 'hello-elementor-child' ),
			'type'        => 'text',
			'sanitize'    => 'sanitize_text_field',
			'help'        => __( 'A page path such as <code>/airport-transfers/</code>, a full URL, or an anchor such as <code>#weddings</code>.', 'hello-elementor-child' ),
			'placeholder' => '/airport-transfers/',
		),
		'_nyc_s_wide'      => array(
			'label'       => __( 'Double width', 'hello-elementor-child' ),
			'type'        => 'checkbox',
			'sanitize'    => 'nyc_sanitize_checkbox',
			'help'        => __( 'Span two columns, the way Airport Transportation did at the top of the Services page. Drops back to one column under 900px.', 'hello-elementor-child' ),
			'placeholder' => '',
		),
	);
}

/* ==========================================================================
   Post types
   ========================================================================== */

/**
 * Register both tile post types.
 *
 * Neither is public: no single pages, no archive, nothing added to the site's
 * URL structure that could collide with a page built in Elementor.
 *
 * @return void
 */
function nyc_register_tiles() {
	foreach ( nyc_tile_types() as $type => $config ) {
		register_post_type(
			$type,
			array(
				'labels'          => array(
					'name'               => $config['plural'],
					'singular_name'      => $config['singular'],
					'menu_name'          => $config['plural'],
					/* translators: %s: singular type name. */
					'add_new_item'       => sprintf( __( 'Add %s', 'hello-elementor-child' ), $config['singular'] ),
					'add_new'            => __( 'Add New', 'hello-elementor-child' ),
					/* translators: %s: singular type name. */
					'edit_item'          => sprintf( __( 'Edit %s', 'hello-elementor-child' ), $config['singular'] ),
					/* translators: %s: singular type name. */
					'new_item'           => sprintf( __( 'New %s', 'hello-elementor-child' ), $config['singular'] ),
					/* translators: %s: plural type name. */
					'search_items'       => sprintf( __( 'Search %s', 'hello-elementor-child' ), $config['plural'] ),
					'not_found'          => __( 'Nothing here yet.', 'hello-elementor-child' ),
					'not_found_in_trash' => __( 'Nothing in the bin.', 'hello-elementor-child' ),
					/* translators: %s: plural type name. */
					'all_items'          => sprintf( __( 'All %s', 'hello-elementor-child' ), $config['plural'] ),
				),
				'public'          => false,
				'show_ui'         => true,
				'show_in_menu'    => true,
				'menu_position'   => $config['menu_position'],
				'menu_icon'       => $config['menu_icon'],
				'supports'        => array( 'title', 'thumbnail', 'page-attributes' ),
				'has_archive'     => false,
				'rewrite'         => false,
				'query_var'       => false,
				'capability_type' => 'post',
				'map_meta_cap'    => true,
			)
		);
	}

	// Hidden, and only so the migration can read the old Group assignments.
	register_taxonomy(
		NYC_TILE_LEGACY_TAX,
		NYC_SERVICE_CPT,
		array(
			'label'             => __( 'Groups (legacy)', 'hello-elementor-child' ),
			'public'            => false,
			'show_ui'           => false,
			'show_admin_column' => false,
			'show_in_menu'      => false,
			'hierarchical'      => true,
			'rewrite'           => false,
			'query_var'         => false,
		)
	);
}
add_action( 'init', 'nyc_register_tiles' );

/* ==========================================================================
   Edit screen — shared by both types
   ========================================================================== */

/**
 * Add the tile meta box to both types.
 *
 * @return void
 */
function nyc_tile_meta_box() {
	foreach ( nyc_tile_types() as $type => $config ) {
		add_meta_box(
			'nyc_tile_details',
			/* translators: %s: singular type name. */
			sprintf( __( '%s Tile', 'hello-elementor-child' ), $config['singular'] ),
			'nyc_tile_meta_box_render',
			$type,
			'normal',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'nyc_tile_meta_box' );

/**
 * Render the tile meta box.
 *
 * @param WP_Post $post Current post.
 * @return void
 */
function nyc_tile_meta_box_render( $post ) {
	wp_nonce_field( 'nyc_tile_save', 'nyc_tile_nonce' );
	?>
	<style>
		.nyc-tile-fields { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
		.nyc-tile-fields .nyc-wide { grid-column: 1 / -1; }
		.nyc-tile-fields label { display: block; font-weight: 600; margin-bottom: 4px; }
		.nyc-tile-fields input[type="text"],
		.nyc-tile-fields textarea { width: 100%; }
		.nyc-tile-fields textarea { min-height: 80px; }
		.nyc-tile-fields .nyc-check label { font-weight: 400; display: inline; }
		@media (max-width: 782px) { .nyc-tile-fields { grid-template-columns: minmax(0, 1fr); } }
	</style>
	<div class="nyc-tile-fields">
		<?php foreach ( nyc_tile_fields() as $key => $field ) : ?>
			<?php
			$value = get_post_meta( $post->ID, $key, true );
			$wide  = 'textarea' === $field['type'] || 'checkbox' === $field['type'];
			?>
			<div class="<?php echo $wide ? 'nyc-wide' : ''; ?> <?php echo 'checkbox' === $field['type'] ? 'nyc-check' : ''; ?>">
				<?php if ( 'checkbox' === $field['type'] ) : ?>
					<label for="<?php echo esc_attr( $key ); ?>">
						<input type="checkbox"
							id="<?php echo esc_attr( $key ); ?>"
							name="<?php echo esc_attr( $key ); ?>"
							value="1" <?php checked( $value, 1 ); ?> />
						<strong><?php echo esc_html( $field['label'] ); ?></strong>
					</label>
				<?php else : ?>
					<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ); ?></label>

					<?php if ( 'textarea' === $field['type'] ) : ?>
						<textarea id="<?php echo esc_attr( $key ); ?>"
							name="<?php echo esc_attr( $key ); ?>"
							placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>"><?php echo esc_textarea( $value ); ?></textarea>
					<?php else : ?>
						<input type="text"
							id="<?php echo esc_attr( $key ); ?>"
							name="<?php echo esc_attr( $key ); ?>"
							value="<?php echo esc_attr( $value ); ?>"
							placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" />
					<?php endif; ?>
				<?php endif; ?>

				<?php if ( $field['help'] ) : ?>
					<p class="description"><?php echo wp_kses( $field['help'], array( 'code' => array() ) ); ?></p>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
	<p class="description" style="margin-top:16px;">
		<?php esc_html_e( 'The background photo is the Featured Image. Use the Order field under Page Attributes to arrange the grid, lowest first.', 'hello-elementor-child' ); ?>
	</p>
	<?php
}

/**
 * Save the tile meta box.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function nyc_tile_save( $post_id ) {
	if ( ! isset( $_POST['nyc_tile_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['nyc_tile_nonce'] ) ), 'nyc_tile_save' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( nyc_tile_fields() as $key => $field ) {
		// An unticked checkbox posts nothing, so it has to be cleared rather
		// than skipped the way the text fields are.
		if ( 'checkbox' === $field['type'] ) {
			if ( empty( $_POST[ $key ] ) ) {
				delete_post_meta( $post_id, $key );
			} else {
				update_post_meta( $post_id, $key, 1 );
			}
			continue;
		}

		if ( ! isset( $_POST[ $key ] ) ) {
			continue;
		}

		$value = call_user_func( $field['sanitize'], wp_unslash( $_POST[ $key ] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitised on this line.

		if ( '' === $value ) {
			delete_post_meta( $post_id, $key );
			continue;
		}

		update_post_meta( $post_id, $key, $value );
	}
}
add_action( 'save_post_' . NYC_SERVICE_CPT, 'nyc_tile_save' );
add_action( 'save_post_' . NYC_EVENT_CPT, 'nyc_tile_save' );

/**
 * Photo and label columns on both list tables.
 *
 * @param array $columns Existing columns.
 * @return array
 */
function nyc_tile_columns( $columns ) {
	$new = array();

	foreach ( $columns as $key => $label ) {
		if ( 'title' === $key ) {
			$new['nyc_thumb'] = __( 'Photo', 'hello-elementor-child' );
		}

		$new[ $key ] = $label;

		if ( 'title' === $key ) {
			$new['nyc_label'] = __( 'Label', 'hello-elementor-child' );
		}
	}

	return $new;
}
add_filter( 'manage_' . NYC_SERVICE_CPT . '_posts_columns', 'nyc_tile_columns' );
add_filter( 'manage_' . NYC_EVENT_CPT . '_posts_columns', 'nyc_tile_columns' );

/**
 * Fill the custom columns.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 * @return void
 */
function nyc_tile_column_content( $column, $post_id ) {
	if ( 'nyc_thumb' === $column ) {
		echo has_post_thumbnail( $post_id )
			? get_the_post_thumbnail( $post_id, array( 70, 48 ), array( 'style' => 'width:70px;height:48px;object-fit:cover;border-radius:4px;' ) )
			: '&mdash;';
		return;
	}

	if ( 'nyc_label' === $column ) {
		$label = get_post_meta( $post_id, '_nyc_s_label', true );
		$wide  = get_post_meta( $post_id, '_nyc_s_wide', true );

		echo $label ? esc_html( $label ) : '&mdash;';

		if ( $wide ) {
			echo ' <span class="dashicons dashicons-editor-expand" title="' . esc_attr__( 'Double width', 'hello-elementor-child' ) . '"></span>';
		}
	}
}
add_action( 'manage_' . NYC_SERVICE_CPT . '_posts_custom_column', 'nyc_tile_column_content', 10, 2 );
add_action( 'manage_' . NYC_EVENT_CPT . '_posts_custom_column', 'nyc_tile_column_content', 10, 2 );

/**
 * Order both list tables the way the front end orders them.
 *
 * @param WP_Query $query Current query.
 * @return void
 */
function nyc_tile_admin_order( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( ! array_key_exists( (string) $query->get( 'post_type' ), nyc_tile_types() ) || $query->get( 'orderby' ) ) {
		return;
	}

	$query->set( 'orderby', 'menu_order title' );
	$query->set( 'order', 'ASC' );
}
add_action( 'pre_get_posts', 'nyc_tile_admin_order' );

/* ==========================================================================
   [services] and [events]
   ========================================================================== */

/**
 * Render a grid of tiles. One callback, both shortcodes.
 *
 * Outputs the grid and nothing else — no section padding, no width wrapper,
 * no background. The Elementor container it sits in owns those.
 *
 * @param array  $atts    Shortcode attributes.
 * @param string $content Enclosed content, unused.
 * @param string $tag     Which shortcode was called.
 * @return string
 */
function nyc_tiles_shortcode( $atts, $content = '', $tag = 'services' ) {
	$types = nyc_tile_types();
	$type  = NYC_SERVICE_CPT;

	foreach ( $types as $key => $config ) {
		if ( $config['shortcode'] === $tag ) {
			$type = $key;
			break;
		}
	}

	$config = $types[ $type ];

	$atts = shortcode_atts(
		array(
			'columns' => '3',
			'limit'   => '-1',
			'heading' => 'no',
			'eyebrow' => $config['eyebrow'],
			'title'   => $config['heading'],
			'intro'   => '',
		),
		$atts,
		$tag
	);

	$tiles = new WP_Query(
		array(
			'post_type'      => $type,
			'post_status'    => 'publish',
			'posts_per_page' => (int) $atts['limit'],
			'orderby'        => 'menu_order title',
			'order'          => 'ASC',
			'no_found_rows'  => true,
		)
	);

	if ( ! $tiles->have_posts() ) {
		if ( current_user_can( 'edit_posts' ) ) {
			return '<p>' . sprintf(
				/* translators: 1: type name, 2: admin link. */
				esc_html__( 'Nothing published under %1$s yet — %2$s.', 'hello-elementor-child' ),
				esc_html( $config['plural'] ),
				'<a href="' . esc_url( admin_url( 'edit.php?post_type=' . $type ) ) . '">' . esc_html__( 'add some', 'hello-elementor-child' ) . '</a>'
			) . '</p>';
		}
		return '';
	}

	$columns = max( 1, min( 4, (int) $atts['columns'] ) );

	ob_start();
	?>
	<div class="nyc-services">

		<?php if ( 'yes' === $atts['heading'] && ( $atts['title'] || $atts['eyebrow'] ) ) : ?>
			<div class="head">
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

		<div class="grid grid--<?php echo (int) $columns; ?>">
			<?php
			$index = 0;
			while ( $tiles->have_posts() ) :
				$tiles->the_post();
				nyc_tile_render( get_post(), $index );
				++$index;
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'services', 'nyc_tiles_shortcode' );
add_shortcode( 'events', 'nyc_tiles_shortcode' );

/**
 * One tile. Same markup as the original .svc.
 *
 * @param WP_Post $post  Tile.
 * @param int     $index Position in the grid, for the reveal stagger.
 * @return void
 */
function nyc_tile_render( $post, $index = 0 ) {
	$id        = $post->ID;
	$title     = get_the_title( $id );
	$label     = get_post_meta( $id, '_nyc_s_label', true );
	$desc      = get_post_meta( $id, '_nyc_s_desc', true );
	$link_text = get_post_meta( $id, '_nyc_s_link_text', true );
	$link_url  = get_post_meta( $id, '_nyc_s_link_url', true );
	$wide      = get_post_meta( $id, '_nyc_s_wide', true );

	$classes = 'svc' . ( $wide ? ' svc--wide' : '' );
	$delay   = ( $index % 3 ) + 1;
	?>
	<article class="<?php echo esc_attr( $classes ); ?>" data-reveal data-reveal-delay="<?php echo (int) $delay; ?>">
		<?php
		if ( has_post_thumbnail( $id ) ) {
			echo get_the_post_thumbnail(
				$id,
				'large',
				array(
					'loading' => 'lazy',
					'alt'     => esc_attr( $title ),
				)
			);
		}
		?>

		<?php if ( $label ) : ?>
			<p class="svc__label"><?php echo esc_html( $label ); ?></p>
		<?php endif; ?>

		<h3><?php echo esc_html( $title ); ?></h3>

		<?php if ( $desc ) : ?>
			<p><?php echo esc_html( $desc ); ?></p>
		<?php endif; ?>

		<?php if ( $link_text ) : ?>
			<a href="<?php echo esc_url( nyc_link( $link_url ) ); ?>" class="link link--light"><?php echo esc_html( $link_text ); ?></a>
		<?php endif; ?>
	</article>
	<?php
}
