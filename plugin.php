<?php

class BlogsMapPlugin {

    var $version = '0.0.1';

    // plugin general initialization

    private static $instance = null;

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance( $plugin_name = null ) {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        // register plugin
        if ( $plugin_name ) {
            register_activation_hook( $plugin_name, array( self::$instance, 'activate' ) ); // plugin activation actions
            register_deactivation_hook( $plugin_name, array( self::$instance, 'deactivate' ) );
        }

        return self::$instance;
    }

    function __construct() {
        // register shortcodes api
        self::register_shortcodes();

        // register callbacks
        self::register_callbacks();

        // register stylesheet
        self::register_stylesheet();
    }

    /**
     * Activation hook for the plugin.
     */
    function activate() {
        $this->includes();

        //verify user is running WP 3.0 or newer
        if ( version_compare( get_bloginfo( 'version' ), '3.0', '<' ) ) {
            deactivate_plugins( plugin_basename( __FILE__ ) ); // Deactivate our plugin
            wp_die( __( 'This plugin requires WordPress version 3.0 or higher.', 'grants-review' ) );
        }
        flush_rewrite_rules();
    }

    /**
     * Deactivation hook for the plugin.
     */
    function deactivate() {
        flush_rewrite_rules();
    }

    // plugin custom codes

    function register_shortcodes() {}

    function register_callbacks() {
        add_action( 'wpmu_new_blog', array( $this, 'map_blogs' ));
    }

    function register_stylesheet() {}

    function includes() {
        $this->map_blogs();
    }

    //function map_blogs( $blog_id = null, $user_id = null, $domain = null, $path = null, $site_id = null, $meta = null ) {
    function map_blogs() {
        global $wpdb;

        // get all the blogs info
        $query = sprintf( "SELECT blog_id, domain from %s", $wpdb->prefix .'blogs' );
        $blogs = $wpdb->get_results( $query );

        if ( empty( $blogs ) ) return;

        // update blog mapping
        // Open the text file in wordpress home directory
        $f = fopen( get_home_path() . 'map.conf', 'w' );

        foreach ( $blogs as $blog ) {
            fwrite( $f, "{$blog->domain} {$blog->blog_id};" );
        }

        fclose( $f );
    }
}

?>