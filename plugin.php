<?php

if( ! array_key_exists( 'example-plugin', $GLOBALS ) ) { 

    /**
     * Dummy class
     */
    class ExamplePlugin {

        function __construct() {

        } // end constructor

        /**
         * Add some actions just so we can test that later
         * @return [type] [description]
         */
        function init() {
            add_action( 'admin_menu', array( $this, 'remove_sidemenu' ) );
            add_action( 'do_meta_boxes', array( $this, 'remove_plugin_metaboxes' ) );
        }

    } // end class


    /**
     * Retrieves a permalink and applies a dummy filter
     * @param  int $post_id
     * @return str          
     */
    function my_permalink_function( $post_id ) {

        $permalink = get_permalink( absint( $post_id ) );
        $permalink = apply_filters( 'special_filter', $permalink );

        do_action( 'special_action', $permalink );

        return $permalink;
    }


    /**
     * Just gets the post and returns special_meta property prepended by $content
     * @param  int $post_id
     * @param  str $content
     * @return str          
     */
    function special_the_content( $post_id, $content ) {

        $post = get_post( $post_id );
        return $content . $post->special_meta;
    }

    // Store a reference to the plugin in GLOBALS so that our unit tests can access it
    $GLOBALS['example-plugin'] = new ExamplePlugin();

} // end if

?>