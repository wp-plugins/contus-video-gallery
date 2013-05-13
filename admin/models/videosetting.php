<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: video setting model file.
Version: 2.0
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/


if(class_exists('SettingsModel') != true)
{//checks the SettingsModel class has been defined if starts
    class SettingsModel
    {//SettingsModel class starts
        
        public function __construct()
        {//contructor starts
            global $wpdb;
            $this->_wpdb = $wpdb;
            $this->_settingstable = $this->_wpdb->prefix.'hdflvvideoshare_settings';
        }//contructor ends
        
        public function  update_settings($settingsdata, $settingsdataformat)
        {//function for updating settings starts
            return $this->_wpdb->update($this->_settingstable, $settingsdata, array( 'settings_id' => 1 ),$settingsdataformat);
        }//function for updating settings ends
        
        public function get_settingsdata()
        {//function for getting settings starts
            $query = "SELECT * FROM ".$this->_settingstable." WHERE settings_id = 1";
            return $this->_wpdb->get_row($query);
        }//function for getting settings ends
        
    }//SettingsModel class ends
}//checks the SettingsModel class has been defined if ends
?>
