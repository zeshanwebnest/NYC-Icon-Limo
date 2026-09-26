<?php
/**
 * Customizer: every piece of header and footer text, in one panel.
 *
 * Defaults are not repeated here — they come from nyc_defaults() so the
 * controls and the templates cannot drift apart.
 *
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Checkbox sanitiser.
 *
 * @param mixed $value Raw value.
 * @return bool
 */
function nyc_sanitize_checkbox( $value ) {
	return (bool) $value;
}

/**
 * Register one setting plus its control.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 * @param string               $id           Setting id, matching nyc_defaults().
 * @param string               $section      Section id.
 * @param string               $label        Control label.
 * @param string               $type         Control type: text, url, email, checkbox, textarea.
 * @param string               $description  Optional help text.
 * @return void
 */
function nyc_customize_field( $wp_customize, $id, $section, $label, $type = 'text', $description = '' ) {
	$defaults = nyc_defaults();
	$default  = isset( $defaults[ $id ] ) ? $defaults[ $id ] : '';

	switch ( $type ) {
		case 'url':
			$sanitize = 'esc_url_raw';
			break;
		case 'email':
			$sanitize = 'sanitize_email';
			break;
		case 'checkbox':
			$sanitize = 'nyc_sanitize_checkbox';
			break;
		case 'textarea':
			$sanitize = 'wp_kses_post';
			break;
		default:
			$sanitize = 'sanitize_text_field';
	}

	$wp_customize->add_setting(
		$id,
		array(
			'default'           => $default,
			'sanitize_callback' => $sanitize,
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		$id,
		array(
			'label'       => $label,
			'section'     => $section,
			'type'        => $type,
			'description' => $description,
		)
	);
}

/**
 * Build the panel.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 * @return void
 */
function nyc_customize_register( $wp_customize ) {

	$wp_customize->add_panel(
		'nyc_panel',
		array(
			'title'       => __( 'NYC Icon Limo', 'hello-elementor-child' ),
			'description' => __( 'Header and footer content. Menus live under Appearance → Menus; the logo lives under Site Identity.', 'hello-elementor-child' ),
			'priority'    => 20,
		)
	);

	/* ------------------------------------------------------------------
	   Contact details — read by the top bar, header, footer and mobile bar
	   ------------------------------------------------------------------ */

	$wp_customize->add_section(
		'nyc_section_contact',
		array(
			'title'       => __( 'Contact Details', 'hello-elementor-child' ),
			'panel'       => 'nyc_panel',
			'priority'    => 10,
			'description' => __( 'Entered once and used everywhere: top bar, header button, footer and the mobile action bar.', 'hello-elementor-child' ),
		)
	);

	nyc_customize_field( $wp_customize, 'nyc_phone_display', 'nyc_section_contact', __( 'Phone number (as displayed)', 'hello-elementor-child' ), 'text' );
	nyc_customize_field( $wp_customize, 'nyc_phone_number', 'nyc_section_contact', __( 'Phone number (for dialling)', 'hello-elementor-child' ), 'text', __( 'Digits and + only, e.g. +19179524031. This is what a phone actually dials.', 'hello-elementor-child' ) );
	nyc_customize_field( $wp_customize, 'nyc_email', 'nyc_section_contact', __( 'Reservations email', 'hello-elementor-child' ), 'email' );

	/* ------------------------------------------------------------------
	   Top bar
	   ------------------------------------------------------------------ */

	$wp_customize->add_section(
		'nyc_section_topbar',
		array(
			'title'    => __( 'Top Bar', 'hello-elementor-child' ),
			'panel'    => 'nyc_panel',
			'priority' => 20,
		)
	);

	nyc_customize_field( $wp_customize, 'nyc_topbar_show', 'nyc_section_topbar', __( 'Show the top bar', 'hello-elementor-child' ), 'checkbox' );
	nyc_customize_field( $wp_customize, 'nyc_topbar_badge', 'nyc_section_topbar', __( 'Badge text', 'hello-elementor-child' ), 'text', __( 'The small pill on the left. Leave empty to hide it.', 'hello-elementor-child' ) );
	nyc_customize_field( $wp_customize, 'nyc_topbar_location', 'nyc_section_topbar', __( 'Service area line', 'hello-elementor-child' ), 'text' );
	nyc_customize_field( $wp_customize, 'nyc_topbar_show_email', 'nyc_section_topbar', __( 'Show email address', 'hello-elementor-child' ), 'checkbox' );
	nyc_customize_field( $wp_customize, 'nyc_topbar_show_phone', 'nyc_section_topbar', __( 'Show phone number', 'hello-elementor-child' ), 'checkbox' );

	/* ------------------------------------------------------------------
	   Header
	   ------------------------------------------------------------------ */

	$wp_customize->add_section(
		'nyc_section_header',
		array(
			'title'       => __( 'Header', 'hello-elementor-child' ),
			'panel'       => 'nyc_panel',
			'priority'    => 30,
			'description' => __( 'Upload a logo under Site Identity. With no logo set, the monument mark and the wordmark below are used instead.', 'hello-elementor-child' ),
		)
	);

	nyc_customize_field( $wp_customize, 'nyc_brand_name', 'nyc_section_header', __( 'Brand name', 'hello-elementor-child' ), 'text' );
	nyc_customize_field( $wp_customize, 'nyc_brand_sub', 'nyc_section_header', __( 'Brand strapline', 'hello-elementor-child' ), 'text', __( 'The small gold line under the name.', 'hello-elementor-child' ) );
	nyc_customize_field( $wp_customize, 'nyc_header_phone_label', 'nyc_section_header', __( 'Label above the phone number', 'hello-elementor-child' ), 'text' );
	nyc_customize_field( $wp_customize, 'nyc_header_cta_text', 'nyc_section_header', __( 'Button text', 'hello-elementor-child' ), 'text', __( 'Leave empty to hide the button.', 'hello-elementor-child' ) );
	nyc_customize_field( $wp_customize, 'nyc_header_cta_url', 'nyc_section_header', __( 'Button link', 'hello-elementor-child' ), 'url' );

	/* ------------------------------------------------------------------
	   Mobile drawer
	   ------------------------------------------------------------------ */

	$wp_customize->add_section(
		'nyc_section_drawer',
		array(
			'title'       => __( 'Mobile Menu Drawer', 'hello-elementor-child' ),
			'panel'       => 'nyc_panel',
			'priority'    => 40,
			'description' => __( 'The full-screen menu below 1100px. Its links come from the Mobile Drawer Menu location.', 'hello-elementor-child' ),
		)
	);

	nyc_customize_field( $wp_customize, 'nyc_drawer_label', 'nyc_section_drawer', __( 'Heading', 'hello-elementor-child' ), 'text' );
	nyc_customize_field( $wp_customize, 'nyc_drawer_cta_text', 'nyc_section_drawer', __( 'Button text', 'hello-elementor-child' ), 'text' );
	nyc_customize_field( $wp_customize, 'nyc_drawer_cta_url', 'nyc_section_drawer', __( 'Button link', 'hello-elementor-child' ), 'url' );
	nyc_customize_field( $wp_customize, 'nyc_drawer_meta', 'nyc_section_drawer', __( 'Small print at the bottom', 'hello-elementor-child' ), 'text' );

	/* ------------------------------------------------------------------
	   Footer: brand column and social links
	   ------------------------------------------------------------------ */

	$wp_customize->add_section(
		'nyc_section_footer_brand',
		array(
			'title'       => __( 'Footer: Brand & Social', 'hello-elementor-child' ),
			'panel'       => 'nyc_panel',
			'priority'    => 50,
			'description' => __( 'Leave a social URL empty and that icon is not rendered at all.', 'hello-elementor-child' ),
		)
	);

	nyc_customize_field( $wp_customize, 'nyc_footer_intro', 'nyc_section_footer_brand', __( 'Intro paragraph', 'hello-elementor-child' ), 'textarea' );

	foreach ( nyc_social_networks() as $key => $network ) {
		nyc_customize_field(
			$wp_customize,
			'nyc_social_' . $key,
			'nyc_section_footer_brand',
			/* translators: %s: social network name. */
			sprintf( __( '%s URL', 'hello-elementor-child' ), $network['label'] ),
			'url'
		);
	}

	/* ------------------------------------------------------------------
	   Footer: column headings
	   ------------------------------------------------------------------ */

	$wp_customize->add_section(
		'nyc_section_footer_columns',
		array(
			'title'       => __( 'Footer: Column Headings', 'hello-elementor-child' ),
			'panel'       => 'nyc_panel',
			'priority'    => 60,
			'description' => __( 'The links under the first two headings come from the Footer Column menu locations under Appearance → Menus.', 'hello-elementor-child' ),
		)
	);

	nyc_customize_field( $wp_customize, 'nyc_footer_col1_heading', 'nyc_section_footer_columns', __( 'Column 1 heading', 'hello-elementor-child' ), 'text' );
	nyc_customize_field( $wp_customize, 'nyc_footer_col2_heading', 'nyc_section_footer_columns', __( 'Column 2 heading', 'hello-elementor-child' ), 'text' );
	nyc_customize_field( $wp_customize, 'nyc_footer_col3_heading', 'nyc_section_footer_columns', __( 'Column 3 heading', 'hello-elementor-child' ), 'text' );

	/* ------------------------------------------------------------------
	   Footer: reservations column
	   ------------------------------------------------------------------ */

	$wp_customize->add_section(
		'nyc_section_footer_contact',
		array(
			'title'       => __( 'Footer: Reservations Column', 'hello-elementor-child' ),
			'panel'       => 'nyc_panel',
			'priority'    => 70,
			'description' => __( 'The phone number and email come from Contact Details; these are the notes beside them.', 'hello-elementor-child' ),
		)
	);

	nyc_customize_field( $wp_customize, 'nyc_footer_phone_note', 'nyc_section_footer_contact', __( 'Note under the phone number', 'hello-elementor-child' ), 'text' );
	nyc_customize_field( $wp_customize, 'nyc_footer_email_note', 'nyc_section_footer_contact', __( 'Note under the email address', 'hello-elementor-child' ), 'text' );
	nyc_customize_field( $wp_customize, 'nyc_footer_area_title', 'nyc_section_footer_contact', __( 'Service area heading', 'hello-elementor-child' ), 'text' );
	nyc_customize_field( $wp_customize, 'nyc_footer_area_detail', 'nyc_section_footer_contact', __( 'Service area detail', 'hello-elementor-child' ), 'text' );
	nyc_customize_field( $wp_customize, 'nyc_footer_cta_text', 'nyc_section_footer_contact', __( 'Button text', 'hello-elementor-child' ), 'text', __( 'Leave empty to hide the button.', 'hello-elementor-child' ) );
	nyc_customize_field( $wp_customize, 'nyc_footer_cta_url', 'nyc_section_footer_contact', __( 'Button link', 'hello-elementor-child' ), 'url' );

	/* ------------------------------------------------------------------
	   Footer: bottom bar
	   ------------------------------------------------------------------ */

	$wp_customize->add_section(
		'nyc_section_footer_bottom',
		array(
			'title'       => __( 'Footer: Bottom Bar', 'hello-elementor-child' ),
			'panel'       => 'nyc_panel',
			'priority'    => 80,
			'description' => __( 'Privacy and terms links come from the Footer Legal Links menu location.', 'hello-elementor-child' ),
		)
	);

	nyc_customize_field( $wp_customize, 'nyc_copyright', 'nyc_section_footer_bottom', __( 'Copyright line', 'hello-elementor-child' ), 'text', __( 'Write %year% where the current year should appear — it updates itself every January.', 'hello-elementor-child' ) );

	/* ------------------------------------------------------------------
	   Mobile action bar
	   ------------------------------------------------------------------ */

	$wp_customize->add_section(
		'nyc_section_mobilebar',
		array(
			'title'       => __( 'Mobile Action Bar', 'hello-elementor-child' ),
			'panel'       => 'nyc_panel',
			'priority'    => 90,
			'description' => __( 'The fixed Call / Book bar pinned to the bottom of the screen on phones.', 'hello-elementor-child' ),
		)
	);

	nyc_customize_field( $wp_customize, 'nyc_mobilebar_show', 'nyc_section_mobilebar', __( 'Show the action bar', 'hello-elementor-child' ), 'checkbox' );
	nyc_customize_field( $wp_customize, 'nyc_mobilebar_call', 'nyc_section_mobilebar', __( 'Call button text', 'hello-elementor-child' ), 'text' );
	nyc_customize_field( $wp_customize, 'nyc_mobilebar_book', 'nyc_section_mobilebar', __( 'Book button text', 'hello-elementor-child' ), 'text' );
	nyc_customize_field( $wp_customize, 'nyc_mobilebar_book_url', 'nyc_section_mobilebar', __( 'Book button link', 'hello-elementor-child' ), 'url' );

	/* ------------------------------------------------------------------
	   Live preview for the pieces that are pure text
	   ------------------------------------------------------------------ */


	if ( isset( $wp_customize->selective_refresh ) ) {
		$partials = array(
			'nyc_brand_name'   => '.site-header .brand__name',
			'nyc_brand_sub'    => '.site-header .brand__sub',
			'nyc_topbar_badge' => '.topbar__badge',
			'nyc_footer_intro' => '.footer__intro',
		);

		foreach ( $partials as $setting => $selector ) {
			$wp_customize->selective_refresh->add_partial(
				$setting,
				array(
					'selector'        => $selector,
					'render_callback' => static function () use ( $setting ) {
						return nyc_opt( $setting );
					},
				)
			);
		}
	}
}
add_action( 'customize_register', 'nyc_customize_register' );

/**
 * Put the menu pickers inside this panel as well.
 *
 * WordPress already exposes every registered location under Customizer →
 * Menus → View All Locations, but that is two panels away from the footer
 * settings and easy to miss entirely. These are the stock WordPress location
 * controls bound to the stock `nav_menu_locations[...]` settings, so choosing
 * a menu here and choosing it under Menus are the same action — there is no
 * second copy of the value to fall out of step.
 *
 * Priority 20 because core does not register those settings until
 * customize_register priority 11.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 * @return void
 */
function nyc_customize_menu_locations( $wp_customize ) {
	$locations = array(
		'nyc-primary'      => array(
			'section' => 'nyc_section_header',
			'label'   => __( 'Header menu', 'hello-elementor-child' ),
		),
		'nyc-mobile'       => array(
			'section' => 'nyc_section_drawer',
			'label'   => __( 'Drawer menu', 'hello-elementor-child' ),
		),
		'nyc-footer-1'     => array(
			'section' => 'nyc_section_footer_columns',
			'label'   => __( 'Column 1 menu', 'hello-elementor-child' ),
		),
		'nyc-footer-2'     => array(
			'section' => 'nyc_section_footer_columns',
			'label'   => __( 'Column 2 menu', 'hello-elementor-child' ),
		),
		'nyc-footer-legal' => array(
			'section' => 'nyc_section_footer_bottom',
			'label'   => __( 'Legal links menu', 'hello-elementor-child' ),
		),
	);

	// Build the menu list once.
	$choices = array( 0 => __( '— Select a menu —', 'hello-elementor-child' ) );

	foreach ( wp_get_nav_menus() as $menu ) {
		$choices[ $menu->term_id ] = $menu->name;
	}

	$priority = 5;

	foreach ( $locations as $location => $info ) {
		$setting = 'nav_menu_locations[' . $location . ']';

		// Core owns this setting. If it is not there, the menus component is
		// disabled and there is nothing sensible to attach a control to.
		if ( ! $wp_customize->get_setting( $setting ) ) {
			continue;
		}

		// A plain select, deliberately. The dedicated nav-menu location
		// control carries its own JavaScript that only initialises inside
		// core's Menus panel — dropped into a section elsewhere it throws,
		// and a thrown control takes the whole Customizer pane down with it.
		$wp_customize->add_control(
			'nyc_menu_location_' . $location,
			array(
				'label'    => $info['label'],
				'section'  => $info['section'],
				'settings' => $setting,
				'type'     => 'select',
				'choices'  => $choices,
				'priority' => $priority,
			)
		);

		++$priority;
	}
}
add_action( 'customize_register', 'nyc_customize_menu_locations', 20 );
