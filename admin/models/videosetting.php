<?php
/**
 * video setting model file to update the  setting for  video gallery.
 *  
 * @category Apptha
 * @package Contus video Gallery 
 * @version 2.7 
 * @author Apptha Team <developers@contus.in> 
 * @copyright Copyright (C) 2014 Apptha. All rights reserved. @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
if (class_exists ( 'SettingsModel' ) != true) {
	class SettingsModel {
		public function __construct() {
			global $wpdb;
			$this->_wpdb = $wpdb;
			$this->_settingstable = $this->_wpdb->prefix . 'hdflvvideoshare_settings';
		}
		/**
		 * function for store setting data into database
		 *
		 * @param type $settingsdata        	
		 * @param type $settingsdataformat        	
		 */
		public function update_settings($settingsdata, $settingsdataformat) {
			return $this->_wpdb->update ( $this->_settingstable, $settingsdata, array (
					'settings_id' => 1 
			), $settingsdataformat );
		}
		/**
		 * function for get setting value for settings page
		 */
		public function get_settingsdata() {
			$query = 'SELECT * FROM ' . $this->_settingstable . ' WHERE settings_id = 1';
			return $this->_wpdb->get_row ( $query );
		}
	}
}
?>