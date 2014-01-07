<?php

class EismaSubscriptions_WP_Shortcode_Subscriptions
{
    /**
     * The plugin
     *
     * @var EismaSubscriptions_WP_Plugin $plugin
     */
    private $plugin;

    /**
     * @var string
     */
    public static $shortcode = 'eisma-subscriptions';

    /**
     * @param EismaSubscriptions_WP_Plugin $plugin
     */
    public function __construct( EismaSubscriptions_WP_Plugin $plugin )
    {
        $this->plugin = $plugin;

        add_shortcode( self::$shortcode, array( $this, 'handle_shortcode' ) );
    }

    /**
     * @param mixed $arguments
     *
     * @return string $output
     */
    public function handle_shortcode( $arguments )
    {
        return '';
    }
}