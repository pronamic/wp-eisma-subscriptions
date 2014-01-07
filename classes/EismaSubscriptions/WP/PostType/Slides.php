<?php

class EismaSubscriptions_WP_PostType_Slides
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
    public static $post_type = 'eisma-slide';

    /**
     * @var string
     */
    protected $meta_nonce_name = 'eisma-slides-meta-nonce-name';

    /**
     * @var string
     */
    protected $meta_nonce_action = 'eisma-slides-meta-nonce-action';

    /**
     * @param EismaSubscriptions_WP_Plugin $plugin
     */
    public function __construct( EismaSubscriptions_WP_Plugin $plugin )
    {
        $this->plugin = $plugin;

        add_action( 'init'                 , array( $this, 'init' ) );
        add_action( 'save_post'            , array( $this, 'save' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
    }

    /**
     * Init
     */
    public function init()
    {
        register_post_type( self::$post_type, array(
            'labels'               => array(
                'name'               => __( 'Slides'                  , 'eisma_subscriptions' ),
                'singular_name'      => __( 'Slide'                   , 'eisma_subscriptions' ),
                'add_new'            => __( 'Add New'                 , 'eisma_subscriptions' ),
                'add_new_item'       => __( 'Add New Slide'           , 'eisma_subscriptions' ),
                'edit_item'          => __( 'Edit Slide'              , 'eisma_subscriptions' ),
                'new_item'           => __( 'New Slide'               , 'eisma_subscriptions' ),
                'all_items'          => __( 'All Slides'              , 'eisma_subscriptions' ),
                'view_item'          => __( 'View Slide'              , 'eisma_subscriptions' ),
                'search_items'       => __( 'Search Slides'           , 'eisma_subscriptions' ),
                'not_found'          => __( 'No slides found'         , 'eisma_subscriptions' ),
                'not_found_in_trash' => __( 'No slides found in Trash', 'eisma_subscriptions' ),
                'parent_item_colon'  => '',
                'menu_name'          => __( 'Slides'                  , 'eisma_subscriptions' ),
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
            'supports'             => array( 'title', 'thumbnail' ),
            'register_meta_box_cb' => array( $this, 'register_meta_boxes' ),
        ) );
    }

    /**
     * Register meta boxes
     */
    public function register_meta_boxes()
    {
        add_meta_box(
            'template',
            __( 'Template', 'eisma_subscriptions' ),
            array( $this, 'template_meta_box' ),
            self::$post_type,
            'normal',
            'high'
        );
    }

    /**
     * Template meta box
     *
     * Also contains the meta nonce field
     */
    public function template_meta_box()
    {
        global $post;

        wp_nonce_field( $this->meta_nonce_action, $this->meta_nonce_name );

        $template   = get_post_meta( $post->ID, '_template'  , true );
        $text_color = get_post_meta( $post->ID, '_text_color', true );
        $text       = get_post_meta( $post->ID, '_text'      , true );
        $video_url  = get_post_meta( $post->ID, '_video_url' , true );

        if ( strlen( $template ) <= 0 )
        {
            $template = 'image';
        }

        if ( strlen( $text_color ) <= 0 )
        {
            $text_color = '000000';
        }

        $data = new stdClass();

        $data->template   = $template;
        $data->text_color = $text_color;
        $data->text       = $text;
        $data->video_url  = $video_url;

        include $this->plugin->path . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'slides-template-meta-box.php';
    }

    /**
     * Enqueues styles and scripts
     */
    public function enqueue()
    {
        if ( ! function_exists( 'get_current_screen' ) )
        {
            return;
        }

        $currentScreen = get_current_screen();

        if ( $currentScreen->post_type != self::$post_type )
        {
            return;
        }

        wp_enqueue_script(
            'eisma_subscriptions_slides_script',
            plugins_url( 'admin/js/slides-script.js', $this->plugin->file ),
            array( 'jquery' ),
            $this->plugin->version
        );
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

        $data = filter_var_array(
            $_POST,
            array(
                '_template'  => FILTER_SANITIZE_SPECIAL_CHARS,
                '_text'      => FILTER_SANITIZE_SPECIAL_CHARS,
                '_video_url' => FILTER_SANITIZE_SPECIAL_CHARS,
            ),
            false
        );

        foreach ( $data as $data_key => $data_value )
        {
            update_post_meta( $post_id, $data_key, $data_value );
        }

        return $post_id;
    }
}