<?php
/*
Plugin Name: Shortcode Widget
Plugin URI: http://www.IaMmE.in
Description: Adds a text-like widget that allows you to write shortcode in it. (Just whats missing in the default text widget)
Author: gagan0123
Author URI: http://gagan.pro/
Version: 0.1
Text Domain: shortcode-widget
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

require_once('class-shortcode-widget.php');

function shortcode_widget_init(){
	register_widget('Shortcode_Widget');
}
add_action('widgets_init','shortcode_widget_init');

?>