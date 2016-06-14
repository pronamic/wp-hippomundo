<?php

/**
 * Title: WordPress Hippomundo admin class
 * Description:
 * Copyright: Copyright (c) 2016
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0.0
 * @since 1.0.0
 */
class Pronamic_WP_Hippomundo_Admin {
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
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		// Settings
		$this->settings = new Pronamic_WP_Hippomundo_Settings( $plugin );
	}

	//////////////////////////////////////////////////

	/**
	 * Admin menu.
	 */
	public function admin_menu() {
		add_options_page(
			__( 'Hippomundo Settings', 'hippomundo' ),
			__( 'Hippomundo', 'hippomundo' ),
			'manage_options',
			'hippomundo',
			array( $this, 'page_options' )
		);
	}

	/**
	 * Page options.
	 */
	public function page_options() {
		include plugin_dir_path( $this->plugin->file ) . 'admin/page-options.php';
	}
}
