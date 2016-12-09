<?php
/**
 * Shapely Theme Customizer.
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function shapely_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}

add_action( 'customize_register', 'shapely_customize_register' );

/**
 * Options for WordPress Theme Customizer.
 */
function shapely_customizer( $wp_customize ) {
	/* Main option Settings Panel */
	$wp_customize->add_panel( 'shapely_main_options', array(
		'capability'     => 'edit_theme_options',
		'theme_supports' => '',
		'title'          => __( 'Shapely Options', 'shapely' ),
		'description'    => __( 'Panel to update shapely theme options', 'shapely' ), // Include html tags such as <p>.
		'priority'       => 10, // Mixed with top-level-section hierarchy.
	) );

	// add "Sidebar" section
	$wp_customize->add_section( 'shapely_layout_section', array(
		'title'       => __( 'Layout options', 'shapely' ),
		'description' => '',
		'priority'    => 31,
		'panel'       => 'shapely_main_options',
	) );
	// Layout options
	global $shapely_site_layout;
	$wp_customize->add_setting( 'shapely_sidebar_position', array(
		'default'           => 'side-right',
		'sanitize_callback' => 'shapely_sanitize_layout',
	) );
	$wp_customize->add_control( 'shapely_sidebar_position', array(
		'label'       => __( 'Website Layout Options', 'shapely' ),
		'section'     => 'shapely_layout_section',
		'type'        => 'select',
		'description' => __( 'Choose between different layout options to be used as default', 'shapely' ),
		'choices'     => $shapely_site_layout,
	) );

	$wp_customize->add_setting( 'link_color', array(
		'default'           => '',
		'sanitize_callback' => 'shapely_sanitize_hexcolor',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
		'label'       => __( 'Link Color', 'shapely' ),
		'description' => __( 'Default used if no color is selected', 'shapely' ),
		'section'     => 'shapely_layout_section',
	) ) );
	$wp_customize->add_setting( 'link_hover_color', array(
		'default'           => '',
		'sanitize_callback' => 'shapely_sanitize_hexcolor',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_hover_color', array(
		'label'       => __( 'Link Hover Color', 'shapely' ),
		'description' => __( 'Default used if no color is selected', 'shapely' ),
		'section'     => 'shapely_layout_section',
	) ) );
	$wp_customize->add_setting( 'button_color', array(
		'default'           => '',
		'sanitize_callback' => 'shapely_sanitize_hexcolor',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'button_color', array(
		'label'       => __( 'Button Color', 'shapely' ),
		'description' => __( 'Default used if no color is selected', 'shapely' ),
		'section'     => 'shapely_layout_section',
	) ) );
	$wp_customize->add_setting( 'button_hover_color', array(
		'default'           => '',
		'sanitize_callback' => 'shapely_sanitize_hexcolor',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'button_hover_color', array(
		'label'       => __( 'Button Hover Color', 'shapely' ),
		'description' => __( 'Default used if no color is selected', 'shapely' ),
		'section'     => 'shapely_layout_section',
	) ) );

	$wp_customize->add_setting( 'social_color', array(
		'default'           => '',
		'sanitize_callback' => 'shapely_sanitize_hexcolor',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'social_color', array(
		'label'       => __( 'Social Icon Color', 'shapely' ),
		'description' => __( 'Default used if no color is selected', 'shapely' ),
		'section'     => 'shapely_layout_section',
	) ) );

	// add "Sidebar" section
	$wp_customize->add_section( 'shapely_main_section', array(
		'title'    => __( 'Main options', 'shapely' ),
		'priority' => 11,
		'panel'    => 'shapely_main_options',
	) );

	$wp_customize->add_setting( 'top_callout', array(
		'default'           => 1,
		'sanitize_callback' => 'shapely_sanitize_checkbox',
	) );

	if(class_exists('Epsilon_Control_Toggle')){
		$wp_customize->add_control( new Epsilon_Control_Toggle(
			                            $wp_customize,
			                            'top_callout',
			                            array(
				                            'type'     => 'mte-toggle',
				                            'label'    => esc_html__( 'Show title in top call out box', 'newsmag' ),
				                            'section'  => 'shapely_main_section',
				                            'priority' => 20
			                            )
		                            )
		);
	} else {
		$wp_customize->add_control( 'top_callout', array(
			'label'    => esc_html__( 'check to show title in top call out box', 'shapely' ),
			'section'  => 'shapely_main_section',
			'priority' => 20,
			'type'     => 'checkbox',
		) );
	}

	$wp_customize->add_setting( 'blog_name', array(
		'default'           => '',
		'sanitize_callback' => 'shapely_sanitize_strip_slashes',
	) );
	$wp_customize->add_control( 'blog_name', array(
		'label'       => __( 'Blog Name in top callout', 'shapely' ),
		'description' => __( 'Heading for the Blog page', 'shapely' ),
		'section'     => 'shapely_main_section',
	) );

	if ( post_type_exists( 'jetpack-portfolio' ) ) {
		$wp_customize->add_setting( 'portfolio_name', array(
			'default'           => '',
			'sanitize_callback' => 'shapely_sanitize_strip_slashes',
		) );
		$wp_customize->add_control( 'portfolio_name', array(
			'label'   => __( 'Portfolio Archive Title', 'shapely' ),
			'section' => 'shapely_main_section',
		) );

		$wp_customize->add_setting( 'portfolio_description', array(
			'default'           => '',
			'sanitize_callback' => 'shapely_sanitize_strip_slashes',
		) );
		$wp_customize->add_control( 'portfolio_description', array(
			'type'    => 'textarea',
			'label'   => __( 'Portfolio Archive Description', 'shapely' ),
			'section' => 'shapely_main_section',
		) );
	}

	$wp_customize->add_setting( 'footer_callout_text', array(
		'default'           => '',
		'sanitize_callback' => 'shapely_sanitize_strip_slashes',
	) );
	$wp_customize->add_control( 'footer_callout_text', array(
		'label'       => __( 'Text for footer callout', 'shapely' ),
		'description' => __( 'Footer Callout', 'shapely' ),
		'section'     => 'shapely_main_section',
	) );

	$wp_customize->add_setting( 'footer_callout_btntext', array(
		'default'           => '',
		'sanitize_callback' => 'shapely_sanitize_strip_slashes',
	) );
	$wp_customize->add_control( 'footer_callout_btntext', array(
		'label'   => __( 'Text for footer callout button', 'shapely' ),
		'section' => 'shapely_main_section',
	) );
	$wp_customize->add_setting( 'footer_callout_link', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'footer_callout_link', array(
		'label'       => __( 'CFA button link', 'shapely' ),
		'section'     => 'shapely_main_section',
		'description' => __( 'Enter the link for Call For Action button in footer', 'shapely' ),
		'type'        => 'text',
	) );

	// add "Footer" section
	$wp_customize->add_section( 'shapely_footer_section', array(
		'title'    => esc_html__( 'Footer', 'shapely' ),
		'priority' => 90,
	) );

	$wp_customize->add_setting( 'shapely_footer_copyright', array(
		'default'           => '',
		'transport'         => 'refresh',
		'sanitize_callback' => 'shapely_sanitize_strip_slashes',
	) );

	$wp_customize->add_control( 'shapely_footer_copyright', array(
		'type'    => 'textarea',
		'label'   => __( 'Copyright Text', 'shapely' ),
		'section' => 'shapely_footer_section',
	) );

}

add_action( 'customize_register', 'shapely_customizer' );

/**
 * Adds sanitization callback function: Strip Slashes.
 */
function shapely_sanitize_strip_slashes( $input ) {
	return wp_kses_stripslashes( $input );
}

/**
 * Sanitize checkbox for WordPress customizer.
 */
function shapely_sanitize_checkbox( $input ) {
	if ( $input == 1 ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Adds sanitization callback function: Sidebar Layout.
 */
function shapely_sanitize_layout( $input ) {
	global $shapely_site_layout;
	if ( array_key_exists( $input, $shapely_site_layout ) ) {
		return $input;
	} else {
		return '';
	}
}

/**
 * Adds sanitization callback function: colors.
 */
function shapely_sanitize_hexcolor( $color ) {
	if ( $unhashed = sanitize_hex_color_no_hash( $color ) ) {
		return '#' . $unhashed;
	}

	return $color;
}


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function shapely_customize_preview_js() {
	wp_enqueue_script( 'shapely_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), false, true );
}

add_action( 'customize_preview_init', 'shapely_customize_preview_js' );

/**
 * Add CSS for custom controls.
 */
function shapely_customizer_custom_control_css() {
	?>
	<style>
		#customize-control-shapely-main_body_typography-size select, #customize-control-shapely-main_body_typography-face select, #customize-control-shapely-main_body_typography-style select {
			width: 60%;
		}
	</style><?php

}

add_action( 'customize_controls_print_styles', 'shapely_customizer_custom_control_css' );

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}
