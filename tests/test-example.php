<?php
/**
 * Class WP_Mock_Tests
 *
 * @group wp-mock
 */
class WP_Mock_Tests extends PHPUnit_Framework_TestCase {


    /**
     * Setup WP_Mock for each test
     */
    function setUp() {
        \WP_Mock::setUp();
    }

    /**
     * Clean up after the test is run
     */
    function tearDown() {
        \WP_Mock::tearDown();
    }

    /**
     * special_the_content() uses get_post() - this test mocks up the get_post object and passes to special_the_content along with it's other test parameter
     */
    public function test_uses_get_post() {
        
        $post = new \stdClass;
        $post->ID = 42;
        $post->special_meta = '<p>I am on the end</p>';
        \WP_Mock::wpFunction( 'get_post', array(
            'times' => 1,
            'args' => array( $post->ID ),
            'return' => $post,
        ) );

        // Let's say our function gets the post and appends a value stored in 'special_meta' to the content
        $results = special_the_content( $post->ID, '<p>Some content</p>' );
        // In addition to failing if this assertion is false, the test will fail if get_post is not called with the arguments above
        $this->assertEquals( '<p>Some content</p><p>I am on the end</p>', $results );
    }

    /**
     * Assume that my_permalink_function() is meant to do all of the following:
     * - Run the given post ID through absint()
     * - Call get_permalink() on the $post_id
     * - Pass the permalink through the 'special_filter' filter
     * - Trigger the 'special_action' WordPress action
     */
    public function test_my_permalink_function() {
        \WP_Mock::wpFunction( 'get_permalink', array(
            'args' => 42,
            'times' => 1,
            'return' => 'http://example.com/foo'
        ) );

        \WP_Mock::wpPassthruFunction( 'absint', array( 'times' => 1 ) );

        \WP_Mock::onFilter( 'special_filter' )
            ->with( 'http://example.com/foo' )
            ->reply( 'https://example.com/bar' );

        \WP_Mock::expectAction( 'special_action', 'https://example.com/bar' );

        $result = my_permalink_function( 42 );

        $this->assertEquals( 'https://example.com/bar', $result );
    }


    /**
     * Test that an action is added within the init method
     */
    public function test_init() {

        $this->plugin = new ExamplePlugin;
        \WP_Mock::expectActionAdded( 'admin_menu', array ($this->plugin, 'remove_sidemenu' ) );
        $this->plugin->init();
  }





}