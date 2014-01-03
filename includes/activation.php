<?php

function zordform_activation(){
	global $wpdb;

	wp_schedule_event( time(), 'daily', 'zordform_daily_action' );

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	$plugin_settings = get_option( 'zordform_settings' );

	if( isset( $plugin_settings['version'] ) ){
		$current_version = $plugin_settings['version'];
	}else{
		$current_version = '';
	}

	if ($current_version == ''){

        $sql = "CREATE TABLE IF NOT EXISTS ".ZORDFORM_TABLE_NAME." (
          `id` int(11) NOT NULL auto_increment,
          `name` varchar(255) NOT NULL,
          `formula` varchar(255) NOT NULL,
          `comment` varchar(255) NULL,
          PRIMARY KEY (`id`)
        ) DEFAULT CHARACTER SET utf8 ;";

        dbDelta($sql);

        $sql = "CREATE TABLE IF NOT EXISTS ".ZORDFORM_FIELDS_TABLE_NAME." (
        `id` int(11) NOT NULL auto_increment,
        `form_id` int(11) NOT NULL,
        `type` varchar(255) NOT NULL,
        `name` varchar(255) NOT NULL,
        `setted` varchar(50) NOT NULL,
        PRIMARY KEY (`id`)
        ) DEFAULT CHARACTER SET utf8;";

        dbDelta($sql);

        $sql = "CREATE TABLE IF NOT EXISTS ".ZORDFORM_OPTIONS_TABLE_NAME." (
          `id` int(11) NOT NULL auto_increment,
          `field_id` int(11) NOT NULL,
          `key` varchar(50) NOT NULL,
          `value` varchar(50) NOT NULL,
          PRIMARY KEY (`id`)
        ) DEFAULT CHARACTER SET utf8 ;";

        dbDelta($sql);

        $sql = "CREATE TABLE IF NOT EXISTS ".ZORDFORM_RESULTS_TABLE_NAME." (
          `id` int(11) NOT NULL auto_increment,
          `form_id` int(11) NOT NULL,
          `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `result` varchar(50) NOT NULL,
          `ip` varchar(20) NOT NULL,
          PRIMARY KEY (`id`)
        ) DEFAULT CHARACTER SET utf8 ;";

        dbDelta($sql);

        $sql = "CREATE TABLE IF NOT EXISTS ".ZORDFORM_RESULT_VALUES_TABLE_NAME." (
          `id` int(11) NOT NULL auto_increment,
          `result_id` int(11) NOT NULL,
          `field_id` int(11) NOT NULL,
          `value` varchar(50) NOT NULL,
          PRIMARY KEY (`id`)
        ) DEFAULT CHARACTER SET utf8 ;";

        dbDelta($sql);

    }
//	if( ( $current_version != '' ) AND version_compare( $current_version, '2.0' , '<' ) ){
//        // some update stuff
//	}

 	$plugin_settings['version'] = ZORDFORM_VERSION;
 	update_option("zordform_settings", $plugin_settings);
}
