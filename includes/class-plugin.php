<?php

/**
 * Title: WordPress Hippomundo plugin class
 * Description:
 * Copyright: Copyright (c) 2016
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0.0
 * @since 1.0.0
 */
class Pronamic_WP_Hippomundo_Plugin  {
	/**
	 * Constructs and initalizes Hippomundo plugin.
	 *
	 * @param string $file
	 */
	public function __construct( $file ) {
		$this->file = $file;

		// Actions
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Admin
		if ( is_admin() ) {
			$this->admin = new Pronamic_WP_Hippomundo_Admin( $this );
		}

		// Shortcodes
		$this->results_shortcode = new Pronamic_WP_Hippomundo_ResultsShortcode( $this );
	}

	/**
	 * Plugins loaded.
	 */
	public function plugins_loaded() {
		load_plugin_textdomain( 'hippomundo', false, dirname( plugin_basename( $this->file ) ) . '/languages' );
	}

	/**
	 * Register scripts.
	 *
	 * @see https://codex.wordpress.org/Function_Reference/wp_register_style
	 */
	public function register_scripts() {
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_style(
			'hippomundo',
			plugins_url( 'css/style' . $min . '.css', $this->file ),
			array(),
			'1.0.0'
		);
	}

	/**
	 * Enqueue scripts.
	 *
	 * @see https://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts
	 */
	public function enqueue_scripts() {
		$post = get_post();

		// @see https://codex.wordpress.org/Function_Reference/has_shortcode
		if ( is_a( $post, 'WP_Post' ) &&  has_shortcode( $post->post_content, 'hippomundo_results' ) ) {
			wp_enqueue_style( 'hippomundo' );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Get editions from competitions.
	 *
	 * @param array $competitions
	 * @return array
	 */
	public function get_editions_from_competitions( $competitions ) {
		$editions = array();

		foreach ( $competitions as $competition ) {
			foreach ( $competition['editions'] as $edition ) {
				$edition['competition_name'] = $competition['name'];
				$edition['discipline']       = $competition['discipline'];

				$editions[] = $edition;
			}
		}

		return $editions;
	}

	/**
	 * Sort editions.
	 *
	 * @param array $editions
	 * @return array
	 */
	public function sort_editions( $editions ) {
		usort( $editions, array( $this, 'sort_edition' ) );

		return $editions;
	}

	/**
	 * Sort the specified editions by date/name.
	 *
	 * @param array $a edition A
	 * @param array $b edition B
	 * @return int
	 */
	public function sort_edition( $a, $b ) {
		$class_a = $a['classes'][0];
		$class_b = $b['classes'][0];

		$datetime1 = date_create_from_format( 'Y-m-d', $class_a['date'] );
		$datetime2 = date_create_from_format( 'Y-m-d', $class_b['date'] );

		if ( false === $datetime1 ) {
			return 0;
		}

		if ( false === $datetime2 ) {
			return 0;
		}

		$interval = date_diff( $datetime1, $datetime2 );

		$r = (int) $interval->format( '%R%a' );

		if ( $r ) {
			return $r / abs( $r );
		}

		return strcasecmp( $a['competition_name'], $b['competition_name'] );
	}

	/**
	 * Get the Hippomundo results.
	 *
	 * @param string $api_key
	 * @param string $studbook
	 * @param int $days
	 * @param int $place
	 * @return arrray
	 */
	public function get_results( $api_key, $studbook, $days = 10, $place = 10 ) {
		$url = sprintf(
			'http://www.hippomundo.com/webservice/api_results_tools/studbook/name/%s/apikey/%s/days/%s/place/%s',
			$studbook,
			$api_key,
			$days,
			$place
		);

		// Transient
		$transient = 'hippomundo_' . md5( $url );

		if ( false !== ( $results = get_transient( $transient ) ) ) {
			return $results;
		}

		// Request
		$response = wp_remote_get( $url );

		// Response Code
		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return false;
		}

		// Body
		$body = wp_remote_retrieve_body( $response );

		$data = json_decode( $body, true );

		if ( false === $data ) {
			return false;
		}

		// Transient
		set_transient( $transient, $data, HOUR_IN_SECONDS );

		// OK
		return $data;
	}
}
