<?php
function zordform_admin_css(){
	wp_enqueue_style( 'zordform-admin', ZORDFORM_URL .'/css/zordform-admin.css');
}

function zordform_admin_js(){
	wp_enqueue_script('zordform-admin', ZORDFORM_URL .'/js/zordform-admin.js', array('jquery'));
}
