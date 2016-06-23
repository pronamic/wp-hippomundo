<?php

/**
 * Title: WordPress Hippomundo results shortcode class
 * Description:
 * Copyright: Copyright (c) 2016
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0.0
 * @since 1.0.0
 */
class Pronamic_WP_Hippomundo_ResultsShortcode {
	/**
	 * Plugin.
	 *
	 * @var Pronamic_WP_Hippomundo_Plugin
	 */
	private $plugin;

	//////////////////////////////////////////////////

	/**
	 * Constructs and intializes an products shortcode.
	 *
	 * @param Pronamic_WP_Hippomundo_Plugin $plugin
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;

		add_shortcode( 'hippomundo_results', array( $this, 'render' ) );

		add_action( 'register_shortcode_ui', array( $this, 'register_shortcode_ui' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Register shortcode UI.
	 *
	 * @see https://github.com/wp-shortcake/shortcake/wiki/Registering-Shortcode-UI
	 */
	public function register_shortcode_ui() {
		shortcode_ui_register_for_shortcode( 'hippomundo_results', array(
			'label'         => __( 'Hippomundo Results', 'hippomundo' ),
			'listItemImage' => 'dashicons-clipboard',
			'attrs'         => array(
				array(
					'label'       => __( 'API Key', 'hippomundo' ),
					'description' => __( 'Your API token, received from Hippomundo personnel.', 'hippomundo' ),
					'attr'        => 'api_key',
					'type'        => 'text',
					'meta'        => array(
						'placeholder' => get_option( 'hippomundo_api_key' ),
					),
				),
				array(
					'label'       => __( 'Studbook', 'hippomundo' ),
					'description' => __( 'The abbreviated name of your studbook. Your API token will be checked against for permissions on this studbook.', 'hippomundo' ),
					'attr'        => 'Studbook',
					'type'        => 'text',
					'meta'        => array(
						'placeholder' => get_option( 'hippomundo_studbook' ),
					),
				),
				array(
					'label'       => __( 'Title', 'hippomundo' ),
					'description' => __( 'Title displayed above the results. Available tags: <code>{days}</code>, <code>{place}</code>.', 'hippomundo' ),
					'attr'        => 'title',
					'type'        => 'text',
					'meta'        => array(
						'placeholder' => get_option( 'hippomundo_title' ),
					),
				),
				array(
					'label'       => __( 'Subtitle', 'hippomundo' ),
					'description' => __( 'Subtitle displayed above the results. Available tags: <code>{days}</code>, <code>{place}</code>.', 'hippomundo' ),
					'attr'        => 'subtitle',
					'type'        => 'text',
					'meta'        => array(
						'placeholder' => get_option( 'hippomundo_subtitle' ),
					),
				),
				array(
					'label'       => __( 'Days', 'hippomundo' ),
					'description' => __( 'Amount of days of sport results to fetch.', 'hippomundo' ),
					'attr'        => 'days',
					'type'        => 'number',
					'meta'        => array(
						'placeholder' => 10,
						'min'         => 1,
					),
				),
				array(
					'label'       => __( 'Discipline', 'hippomundo' ),
					'description' => __( 'The discipline of the results to fetch (all, jumping, eventing, dressage).', 'hippomundo' ),
					'attr'        => 'discipline',
					'type'        => 'text',
					'meta'        => array(
						'placeholder' => 'all'
					),
				),
			),
		) );
	}

	/**
	 * Render the shortcode.
	 *
	 * @param array $atts
	 */
	public function render( $atts ) {
		// Shortcode attributes
		$atts = shortcode_atts( array(
			'api_key'    => get_option( 'hippomundo_api_key' ),
			'studbook'   => get_option( 'hippomundo_studbook' ),
			'title'      => get_option( 'hippomundo_title' ),
			'subtitle'   => get_option( 'hippomundo_subtitle' ),
			'days'       => 10,
			'place'      => 10,
			'discipline' => get_option( 'hippomundo_discipline' ),
		), $atts, 'hippomundo_results' );

		$this->title      = $atts['title'];
		$this->subtitle   = $atts['subtitle'];
		$this->days       = $atts['days'];
		$this->place      = $atts['place'];
		$this->discipline = $atts['discipline'];

		// Results
		$results = $this->plugin->get_results(
			$atts['api_key'],
			$atts['studbook'],
			$this->days,
			$this->place,
			$this->discipline
		);

		$this->editions = $this->plugin->get_editions_from_competitions( $results );

		$this->plugin->sort_editions( $this->editions );

		// Output
		ob_start();

		include $this->locate_template( 'results' );

		return ob_get_clean();
	}

	/**
	 * Format string.
	 *
	 * @param string $string
	 * @return string
	 */
	private function format_string( $string ) {
		$replace = array(
			'{days}'       => $this->days,
			'{place}'      => $this->place,
			'{discipline}' => $this->discipline,
		);

		$string = str_replace(
			array_keys( $replace ),
			array_values( $replace ),
			$string
		);

		return $string;
	}

	/**
	 * Has this shortcode a title.
	 *
	 * @return boolean true if has title, false otherwise
	 */
	public function has_title() {
		return ! empty( $this->title );
	}

	/**
	 * Get the title of this shortcode.
	 *
	 * @return string
	 */
	public function get_title() {
		return $this->format_string( $this->title );
	}

	/**
	 * Has this shortcode a subtitle.
	 *
	 * @return boolean true if has subtitle, false otherwise
	 */
	public function has_subtitle() {
		return ! empty( $this->subtitle );
	}

	/**
	 * Get the subtitle of this shortcode.
	 *
	 * @return string
	 */
	public function get_subtitle() {
		return $this->format_string( $this->subtitle );
	}

	/**
	 * Get the editions results.
	 *
	 * @return array
	 */
	public function get_editions() {
		return $this->editions;
	}

	/**
	 * Format rank.
	 *
	 * @param int $place
	 * @return string
	 */
	public function format_rank( $place ) {
		if ( $place >= 900 && $place <= 999 ) {
			return '';
		}

		return $place;
	}

	/**
	 * Get rider URL by the specified rider ID.
	 *
	 * @param int $id
	 * @return string
	 */
	public function get_rider_url( $id ) {
		return sprintf(
			'http://www.hippomundo.com/nl/competitions/rider/%s',
			$id
		);
	}

	/**
	 * Get pedigree URL by the specified pedigree ID.
	 *
	 * @param int $id
	 * @return string
	 */
	public function get_pedigree_url( $id ) {
		return sprintf(
			'http://www.hippomundo.com/nl/horses/pedigree/line/%s',
			$id
		);
	}

	/**
	 * Locate the specified template in the templates folder.
	 *
	 * @param string $name
	 * @return string
	 */
	private function locate_template( $name ) {
		$file = plugin_dir_path( $this->plugin->file ) . 'templates/' . $name . '.php';

		if ( is_readable( $file ) ) {
			return $file;
		}
	}
}
