<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: ajax playlist model file.
Version: 2.5
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/

if(class_exists('AjaxPlaylistModel') != true)
{//checks the VideoadModel class has been defined if starts
    class AjaxPlaylistModel
    {//PlaylistModel class starts
        
        public $_playListId;
        public function __construct()
        {//contructor starts
            global $wpdb;
            $this->_wpdb = $wpdb;
            $this->_playlisttable = $this->_wpdb->prefix.'hdflvvideoshare_playlist';
            $this->_playListId = intval(filter_input(INPUT_GET, 'playlistId'));
        }//contructor ends
       
    }//PlaylistModel class ends
}//checks the PlaylistModel class has been defined if ends
?>