<?php
/*
Plugin Name: 	irgCustomImportJson
Plugin URI: 	irgCustomImportJson
Description: 	WordPress plugin which requires importing JSON feed and generate product list page off it. 
Version:     	1.0.0
Author:     	Isaac Rojas Garcia (irg)

*/

/* Define path, include and initialise plugin class */
define("irgCustomImportJson_DIR", WP_PLUGIN_DIR . '/irgcustomimportjson/');
define("irgCustomImportJson_TABLE", "irgCustomImportJson");

/* Core Start */
function irgCustomImportJson_activation()
{
    global $wpdb;

    $db_table_name = $wpdb->prefix . irgCustomImportJson_TABLE;

    if (!empty($wpdb->charset))
        $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";

    if (!empty($wpdb->collate))
        $charset_collate .= " COLLATE $wpdb->collate";

    $sql = "CREATE TABLE " . $db_table_name . " (
			`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			`feedurl` text NOT NULL DEFAULT '',		  
			PRIMARY KEY (`id`)
		) $charset_collate;";

    $wpdb->query($sql);
}

function irgCustomImportJson_deactivation()
{
    global $wpdb;

}

/* Hooks to activate and desactive */
register_activation_hook(__FILE__, 'irgCustomImportJson_activation');
register_deactivation_hook(__FILE__, 'irgCustomImportJson_deactivation');

/* Class start */
include(irgCustomImportJson_DIR . 'classes/irgcustomimportjson.class.php');

$myirgCustomImportJson = new customJsonImportPlugin(irgCustomImportJson_DIR, irgCustomImportJson_TABLE);