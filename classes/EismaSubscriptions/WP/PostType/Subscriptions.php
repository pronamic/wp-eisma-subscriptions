<?php

class EismaSubscriptions_WP_PostType_Subscriptions
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
    public static $post_type = 'eisma-subscription';

    /**
     * @var string
     */
    protected $meta_nonce_name = 'eisma-subscriptions-meta-nonce-name';

    /**
     * @var string
     */
    protected $meta_nonce_action = 'eisma-subscriptions-meta-nonce-action';

    /**
     * @param EismaSubscriptions_WP_Plugin $plugin
     */
    public function __construct( EismaSubscriptions_WP_Plugin $plugin )
    {
        $this->plugin = $plugin;

        add_action( 'init'     , array( $this, 'init' ) );
        add_action( 'save_post', array( $this, 'save' ) );
    }

    /**
     * Init
     */
    public function init()
    {
        register_post_type( self::$post_type, array(
            'labels'               => array(
                'name'               => __( 'Subscriptions'                  , 'eisma_subscriptions' ),
                'singular_name'      => __( 'Subscription'                   , 'eisma_subscriptions' ),
                'add_new'            => __( 'Add New'                        , 'eisma_subscriptions' ),
                'add_new_item'       => __( 'Add New Subscription'           , 'eisma_subscriptions' ),
                'edit_item'          => __( 'Edit Subscription'              , 'eisma_subscriptions' ),
                'new_item'           => __( 'New Subscription'               , 'eisma_subscriptions' ),
                'all_items'          => __( 'All Subscriptions'              , 'eisma_subscriptions' ),
                'view_item'          => __( 'View Subscription'              , 'eisma_subscriptions' ),
                'search_items'       => __( 'Search Subscriptions'           , 'eisma_subscriptions' ),
                'not_found'          => __( 'No subscriptions found'         , 'eisma_subscriptions' ),
                'not_found_in_trash' => __( 'No subscriptions found in Trash', 'eisma_subscriptions' ),
                'parent_item_colon'  => '',
                'menu_name'          => __( 'Subscriptions'                  , 'eisma_subscriptions' ),
            ),
            'public'               => false,
            'publicly_queryable'   => false,
            'show_ui'              => true,
            'show_in_menu'         => true,
            'query_var'            => true,
            'rewrite'              => true,
            'capability_type'      => 'post',
            'has_archive'          => true,
            'hierarchical'         => false,
            'menu_position'        => null,
            'supports'             => array( 'title', 'editor', 'thumbnail' ),
            'register_meta_box_cb' => array( $this, 'register_meta_boxes' ),
        ) );
    }

    /**
     * Register meta boxes
     */
    public function register_meta_boxes()
    {
        add_meta_box(
            'price',
            __( 'Price', 'eisma_subscriptions' ),
            array( $this, 'price_meta_box' ),
            self::$post_type,
            'normal',
            'high'
        );
    }

    /**
     * Price meta box
     *
     * Also contains the meta nonce field
     */
    public function price_meta_box()
    {
        global $post;

        wp_nonce_field( $this->meta_nonce_action, $this->meta_nonce_name );

        $data = new stdClass();

        $data->price = get_post_meta( $post->ID, '_price', true );

        include $this->plugin->path . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'subscriptions-price-meta-box.php';
    }

    /**
     * Save post meta
     *
     * @param int $post_id
     *
     * @return int $post_id
     */
    public function save( $post_id )
    {
        // Verify nonce, check if user has sufficient rights and return on auto-save.
        if ( get_post_type( $post_id ) !== self::$post_type ||
             ( !isset( $_POST[ $this->meta_nonce_name ] ) || ! wp_verify_nonce( $_POST[ $this->meta_nonce_name ], $this->meta_nonce_action ) ) ||
             ! current_user_can( 'edit_post', $post_id ) ||
             ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) )
        {
            return $post_id;
        }

        $new_data = filter_var_array(
            $_POST,
            array(
                '_price' => FILTER_SANITIZE_NUMBER_FLOAT,
            ),
            false
        );

        $old_data = array(
            '_price' => get_post_meta( $post_id, '_price', true ),
        );

        $data = array_merge( $old_data, $new_data );

        foreach ( $data as $data_key => $data_value )
        {
            update_post_meta( $post_id, $data_key, $data_value );
        }

        return $post_id;
    }
}