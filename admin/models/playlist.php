<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: playlist model file.
Version: 2.3.1
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/

if (class_exists('PlaylistModel') != true) {//checks the VideoadModel class has been defined if starts

    class PlaylistModel {//PlaylistModel class starts

        public $_playListId;

        public function __construct() {//contructor starts
            global $wpdb;
            $this->_wpdb = $wpdb;
            $this->_playlisttable = $this->_wpdb->prefix . 'hdflvvideoshare_playlist';
            $this->_playListId = filter_input(INPUT_GET, 'playlistId');
        }

//contructor ends

        public function insert_playlist($playlsitData, $playlistDataformat) {//function for inserting playlist starts
            if ($this->_wpdb->insert($this->_playlisttable, $playlsitData, $playlistDataformat)) {
                return $this->_wpdb->insert_id;
            }
        }

//function for inserting playlist ends

        public function playlist_update($playlsitData, $playlistDataformat, $playlistId) {//function for updating playlist starts
            return $this->_wpdb->update($this->_playlisttable, $playlsitData, array('pid' => $playlistId), $playlistDataformat);
        }

//function for updating playlist ends

        public function status_update($playlistId, $status) {//function for updating status of playlist starts
            return $this->_wpdb->update($this->_playlisttable, array('is_publish' => $status), array('pid' => $playlistId));
        }

//function for updating status of playlist ends

        public function get_playlsitdata($searchValue, $searchBtn, $order, $orderDirection) {//function for getting search playlist starts
            $where = '';
            $pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
            $limit = 20;
            $offset = ( $pagenum - 1 ) * $limit;
            if (isset($searchBtn)) {
                $where = " WHERE playlist_name LIKE '%" . $searchValue . "%' || playlist_desc LIKE '%" . $searchValue . "%'";
            }
            if (!isset($orderDirection)) {
                $orderDirection = 'DESC';
            }
            $query = "SELECT * FROM " . $this->_playlisttable . $where . " ORDER BY " . $order . ' ' . $orderDirection . " LIMIT $offset, $limit";
            return $this->_wpdb->get_results($query);
        }


//function for getting search playlist ends

        public function playlist_edit($playlistId) {//function for getting single playlist starts
            return $this->_wpdb->get_row("SELECT * FROM " . $this->_playlisttable . " WHERE pid ='$playlistId'");
        }

//function for getting single playlist ends

        public function Playlist_count($searchValue, $searchBtn) {//function for getting single video starts
            $where = '';
            if (isset($searchBtn)) {
                $where = " WHERE playlist_name LIKE '%" . $searchValue . "%' || playlist_desc LIKE '%" . $searchValue . "%'";
            }
            return $this->_wpdb->get_var("SELECT COUNT(`pid`) FROM " . $this->_playlisttable. $where);
        }

//function for getting single video ends

        public function playlist_delete($playListId) {//function for deleting playlist starts
            $query = "DELETE FROM " . $this->_playlisttable . "  WHERE pid IN (" . "$playListId" . ")";
            return $this->_wpdb->query($query);
        }

//function for deleting playlist starts
    }

    //PlaylistModel class ends
}//checks the PlaylistModel class has been defined if ends
?>