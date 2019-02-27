<?php
/**
 * Class WidgetTest
 *
 * @package Shortcode_Widget
 */

/**
 * Sample test case.
 */
class Test_Shortcode_Widget extends WP_UnitTestCase {

	/**
	 * Args passed to the widget_text filter.
	 *
	 * @var array
	 */
	protected $shortcode_widget_args;

	/**
	 * Args passed to the widget_title filter.
	 *
	 * @var array
	 */
	protected $shortcode_widget_title_args;

	/**
	 * Clean up global scope.
	 *
	 * @global WP_Scripts $wp_scripts
	 * @global WP_Styles  $wp_style
	 */
	public function clean_up_global_scope() {
		global $wp_scripts, $wp_styles;
		parent::clean_up_global_scope();
		$wp_scripts = null;
		$wp_styles  = null;
	}

	/**
	 * Test construct.
	 *
	 * @access public
	 *
	 * @covers Shortcode_Widget::__construct
	 */
	public function test_construct() {
		$widget = new Shortcode_Widget();
		$this->assertEquals( 'shortcode-widget', $widget->id_base );
		$this->assertEquals( 'shortcode_widget', $widget->widget_options['classname'] );
		$this->assertEquals( 400, $widget->control_options['width'] );
		$this->assertEquals( 350, $widget->control_options['height'] );
	}

	/**
	 * Test widget method.
	 *
	 * @covers Shortcode_Widget::widget
	 */
	public function test_widget() {
		$widget = new Shortcode_Widget();
		$text   = "Lorem ipsum dolor sit amet, consectetur adipiscing elit.\n Praesent ut turpis consequat lorem volutpat bibendum vitae vitae ante.";
		$args   = array(
			'before_title'  => '<h2>',
			'after_title'   => "</h2>\n",
			'before_widget' => '<section>',
			'after_widget'  => "</section>\n",
		);
		add_filter( 'widget_title', array( $this, 'filter_shortcode_widget_title' ), 5, 3 );
		add_filter( 'widget_text', array( $this, 'filter_shortcode_widget' ), 5, 3 );

		// Test with filter=false, implicit legacy mode.
		$this->shortcode_widget_args       = null;
		$this->shortcode_widget_title_args = null;
		$instance                          = array(
			'title'  => 'Foo',
			'text'   => $text,
			'filter' => false,
		);
		ob_start();
		$widget->widget( $args, $instance );
		$output = ob_get_clean();
		$this->assertNotContains( '<p>', $output );
		$this->assertNotContains( '<br />', $output );
		$this->assertNotNull( $this->shortcode_widget_args );
		$this->assertNotEmpty( $this->shortcode_widget_args );
		$this->assertCount( 3, $this->shortcode_widget_args );
		$this->assertContains( '[filter:shortcode_widget]', $output );
		$this->assertNotNull( $this->shortcode_widget_title_args );
		$this->assertNotEmpty( $this->shortcode_widget_title_args );
		$this->assertCount( 3, $this->shortcode_widget_title_args );
		$this->assertEquals( $instance['title'], $this->shortcode_widget_title_args[0] );
		$this->assertEquals( $instance, $this->shortcode_widget_title_args[1] );
		$this->assertEquals( 'shortcode-widget', $this->shortcode_widget_title_args[2] );
		$this->assertContains( '[filter:shortcode_widget_title]', $output );

		// Test with filter=true, implicit legacy mode.
		$this->shortcode_widget_args       = null;
		$this->shortcode_widget_title_args = null;
		$instance                          = array(
			'title'  => 'Foo',
			'text'   => $text,
			'filter' => true,
		);
		ob_start();
		$widget->widget( $args, $instance );
		$output = ob_get_clean();
		$this->assertContains( '<p>', $output );
		$this->assertContains( '<br />', $output );
		$this->assertNotNull( $this->shortcode_widget_args );
		$this->assertNotEmpty( $this->shortcode_widget_args );
		$this->assertCount( 3, $this->shortcode_widget_args );
		$this->assertEquals( $instance['text'], $this->shortcode_widget_args[0] );
		$this->assertEquals( $instance, $this->shortcode_widget_args[1] );
		$this->assertEquals( $widget, $this->shortcode_widget_args[2] );
		$this->assertContains( '[filter:shortcode_widget]', $output );
		$this->assertNotNull( $this->shortcode_widget_title_args );
		$this->assertNotEmpty( $this->shortcode_widget_title_args );
		$this->assertCount( 3, $this->shortcode_widget_title_args );
		$this->assertEquals( $instance['title'], $this->shortcode_widget_title_args[0] );
		$this->assertEquals( $instance, $this->shortcode_widget_title_args[1] );
		$this->assertEquals( 'shortcode-widget', $this->shortcode_widget_title_args[2] );
		$this->assertContains( '[filter:shortcode_widget_title]', $output );

		// Test with filter=content, the upgraded widget, in 4.8.0 only.
		$this->shortcode_widget_args       = null;
		$this->shortcode_widget_title_args = null;
		$instance                          = array(
			'title'  => 'Foo',
			'text'   => $text,
			'filter' => 'content',
		);
		$expected_instance                 = array_merge(
			$instance,
			array(
				'filter' => true,
			)
		);
		ob_start();
		$widget->widget( $args, $instance );
		$output = ob_get_clean();
		$this->assertContains( '<p>', $output );
		$this->assertContains( '<br />', $output );
		$this->assertCount( 3, $this->shortcode_widget_args );
		$this->assertEquals( $expected_instance['text'], $this->shortcode_widget_args[0] );
		$this->assertEquals( $expected_instance, $this->shortcode_widget_args[1] );
		$this->assertEquals( $widget, $this->shortcode_widget_args[2] );
		$this->assertContains( wpautop( $expected_instance['text'] . '[filter:shortcode_widget]' ), $output );
		$this->assertNotNull( $this->shortcode_widget_title_args );
		$this->assertNotEmpty( $this->shortcode_widget_title_args );
		$this->assertCount( 3, $this->shortcode_widget_title_args );
		$this->assertEquals( $instance['title'], $this->shortcode_widget_title_args[0] );
		$this->assertEquals( $expected_instance, $this->shortcode_widget_title_args[1] );
		$this->assertEquals( 'shortcode-widget', $this->shortcode_widget_title_args[2] );
		$this->assertContains( '[filter:shortcode_widget_title]', $output );

		// Test with test shortcode [shortcode_widget_test].
		$this->shortcode_widget_args       = null;
		$this->shortcode_widget_title_args = null;
		$instance                          = array(
			'title'  => 'Foo',
			'text'   => '[shortcode_widget_test]',
			'filter' => false,
		);
		ob_start();
		$widget->widget( $args, $instance );
		$output = ob_get_clean();
		$this->assertNotContains( '<p>', $output );
		$this->assertNotContains( '<br />', $output );
		$this->assertNotNull( $this->shortcode_widget_args );
		$this->assertNotEmpty( $this->shortcode_widget_args );
		$this->assertCount( 3, $this->shortcode_widget_args );
		$this->assertContains( 'It works[filter:shortcode_widget]', $output );
		$this->assertNotNull( $this->shortcode_widget_title_args );
		$this->assertNotEmpty( $this->shortcode_widget_title_args );
		$this->assertCount( 3, $this->shortcode_widget_title_args );
		$this->assertEquals( $instance['title'], $this->shortcode_widget_title_args[0] );
		$this->assertEquals( $instance, $this->shortcode_widget_title_args[1] );
		$this->assertEquals( 'shortcode-widget', $this->shortcode_widget_title_args[2] );
		$this->assertContains( '[filter:shortcode_widget_title]', $output );
	}

	/**
	 * Test update method.
	 *
	 * @covers Shortcode_Widget::update
	 */
	public function test_update() {
		$widget   = new Shortcode_Widget();
		$instance = array(
			'title' => "The\n<b>Title</b>",
			'text'  => "The\n\n<b>Code</b>",
		);
		wp_set_current_user(
			$this->factory()->user->create(
				array(
					'role' => 'administrator',
				)
			)
		);
		// Should return valid instance.
		$expected = array(
			'title'  => sanitize_text_field( $instance['title'] ),
			'text'   => $instance['text'],
			'filter' => false,
		);
		$result   = $widget->update( $instance, array() );
		$this->assertEquals( $result, $expected );
		// Make sure KSES is applying as expected.
		add_filter( 'map_meta_cap', array( $this, 'grant_unfiltered_html_cap' ), 10, 2 );
		$this->assertTrue( current_user_can( 'unfiltered_html' ) );
		$instance['text'] = '<script>alert( "Howdy!" );</script>';
		$expected['text'] = $instance['text'];
		$result           = $widget->update( $instance, array() );
		$this->assertEquals( $result, $expected );
		remove_filter( 'map_meta_cap', array( $this, 'grant_unfiltered_html_cap' ) );
		add_filter( 'map_meta_cap', array( $this, 'revoke_unfiltered_html_cap' ), 10, 2 );
		$this->assertFalse( current_user_can( 'unfiltered_html' ) );
		$instance['text'] = '<script>alert( "Howdy!" );</script>';
		$expected['text'] = wp_kses_post( $instance['text'] );
		$result           = $widget->update( $instance, array() );
		$this->assertEquals( $result, $expected );
		remove_filter( 'map_meta_cap', array( $this, 'revoke_unfiltered_html_cap' ), 10 );
	}

	/**
	 * Filters the content of the Custom HTML widget using the legacy shortcode_widget filter.
	 *
	 * @param string           $text     The widget content.
	 * @param array            $instance Array of settings for the current widget.
	 * @param Shortcode_Widget $widget   Current widget instance.
	 * @return string Widget content.
	 */
	public function filter_shortcode_widget( $text, $instance, $widget ) {
		$this->shortcode_widget_args = array( $text, $instance, $widget );
		$text                       .= '[filter:shortcode_widget]';
		return $text;
	}

	/**
	 * Filters the content of the Custom HTML widget using the legacy shortcode_widget filter.
	 *
	 * @param string           $text     The widget content.
	 * @param array            $instance Array of settings for the current widget.
	 * @param Shortcode_Widget $widget   Current widget instance.
	 * @return string Widget content.
	 */
	public function filter_shortcode_widget_title( $text, $instance, $widget ) {
		$this->shortcode_widget_title_args = array( $text, $instance, $widget );
		$text                             .= '[filter:shortcode_widget_title]';
		return $text;
	}

	/**
	 * Grant unfiltered_html cap via map_meta_cap.
	 *
	 * @param array  $caps    Returns the user's actual capabilities.
	 * @param string $cap     Capability name.
	 * @return array Caps.
	 */
	public function grant_unfiltered_html_cap( $caps, $cap ) {
		if ( 'unfiltered_html' === $cap ) {
			$caps   = array_diff( $caps, array( 'do_not_allow' ) );
			$caps[] = 'unfiltered_html';
		}
		return $caps;
	}

	/**
	 * Revoke unfiltered_html cap via map_meta_cap.
	 *
	 * @param array  $caps    Returns the user's actual capabilities.
	 * @param string $cap     Capability name.
	 * @return array Caps.
	 */
	public function revoke_unfiltered_html_cap( $caps, $cap ) {
		if ( 'unfiltered_html' === $cap ) {
			$caps   = array_diff( $caps, array( 'unfiltered_html' ) );
			$caps[] = 'do_not_allow';
		}
		return $caps;
	}
}
