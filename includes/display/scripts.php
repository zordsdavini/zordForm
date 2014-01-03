<?php
//require_once(dirname(__FILE__) . '/../../../../../wp-load.php');

function zordform_display_css(){
	wp_enqueue_style( 'zordform-display', ZORDFORM_URL .'/css/zordform-display.css');
}
add_action( 'init', 'zordform_display_css', 10, 2 );
