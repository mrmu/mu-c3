<?php

/**
 * Fired during plugin activation
 *
 * @link       https://audilu.com
 * @since      1.0.0
 *
 * @package    Mu_C3
 * @subpackage Mu_C3/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Mu_C3
 * @subpackage Mu_C3/includes
 * @author     Audi Lu <khl0327@gmail.com>
 */
class Mu_C3_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
// 		global $wpdb;
// 		$sql = <<<SQL
// CREATE TABLE `{$wpdb->prefix}custom_table` (
//   `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
//   `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
//   `nickname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
//   `views` bigint(20) unsigned DEFAULT 0,
//   `update_date` date DEFAULT NULL,
//   PRIMARY KEY (`ID`),
//   KEY `EMAIL` (`email`)
// ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;
// SQL;
// 		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
// 		dbDelta( $sql );
	}

}
