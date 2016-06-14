<?php

/**
 * Title: WordPress Hippomundo settings class
 * Description:
 * Copyright: Copyright (c) 2016
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0.0
 * @since 1.0.0
 */
class Pronamic_WP_Hippomundo_Settings {
	/**
	 * Plugin.
	 *
	 * @var Pronamic_WP_Hippomundo_Plugin
	 */
	private $plugin;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes admin object.
	 *
	 * @param Pronamic_WP_Hippomundo_Plugin $plugin
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;

		// Actions
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Admin initialize
	 */
	public function admin_init() {
		add_settings_section(
			'hippomundo-general',
			__( 'General', 'hippomundo' ),
			'__return_false',
			'hippomundo'
		);

		// API Key
		register_setting( 'hippomundo', 'hippomundo_api_key' );

		add_settings_field(
			'hippomundo_api_key',
			__( 'API Key', 'hippomundo' ),
			array( $this, 'input_text' ),
			'hippomundo',
			'hippomundo-general',
			array( 'label_for' => 'hippomundo_api_key' )
		);

		// Studbook
		register_setting( 'hippomundo', 'hippomundo_studbook' );

		add_settings_field(
			'hippomundo_studbook',
			__( 'Studbook', 'hippomundo' ),
			array( $this, 'input_text' ),
			'hippomundo',
			'hippomundo-general',
			array( 'label_for' => 'hippomundo_studbook' )
		);

		// Title
		register_setting( 'hippomundo', 'hippomundo_title' );

		add_settings_field(
			'hippomundo_title',
			__( 'Title', 'hippomundo' ),
			array( $this, 'input_text' ),
			'hippomundo',
			'hippomundo-general',
			array( 'label_for' => 'hippomundo_title' )
		);

		// Subtitle
		register_setting( 'hippomundo', 'hippomundo_subtitle' );

		add_settings_field(
			'hippomundo_subtitle',
			__( 'Subtitle', 'hippomundo' ),
			array( $this, 'input_text' ),
			'hippomundo',
			'hippomundo-general',
			array( 'label_for' => 'hippomundo_subtitle' )
		);
	}

	/**
	 * Input text
	 */
	public function input_text( $args ) {
		$id    = $args['label_for'];
		$value = get_option( $id );

		printf(
			'<input id="%s" name="%s" value="%s" type="text" class="regular-text" />',
			esc_attr( $id ),
			esc_attr( $id ),
			esc_attr( $value )
		);
	}
}
