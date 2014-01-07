<?php

class EismaSubscriptions_WP_Plugin
{
	/**
	 * The main plugin file
	 *
	 * @var string
	 */
	public $file;
	
	/**
	 * The plugin's version number
	 * 
	 * @var string
	 */
	public $version = "1.0.0";

	/**
	 * Constructs and initializes the plugin
	 *
	 * @param string $file
	 */
	public function __construct( $file )
	{
		$this->file = $file;
		$this->path = plugin_dir_path( $file );

		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );

        // Post types
        new EismaSubscriptions_WP_PostType_Subscriptions( $this );
        new EismaSubscriptions_WP_PostType_Slides( $this );

        // Shortcodes
        new EismaSubscriptions_WP_Shortcode_Subscriptions( $this );
        new EismaSubscriptions_WP_Shortcode_Slides( $this );
	}
	
	//////////////////////////////////////////////////

	/**
	 * Plugins loaded
	 */
	public function plugins_loaded()
	{
		load_plugin_textdomain( 'pronamic_feed_reader', false, dirname( plugin_basename( $this->file ) ) . '/languages/' );
	}
}
