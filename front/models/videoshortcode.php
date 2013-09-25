<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: Video detail and short tag page model file.
Version: 2.3
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/
if(class_exists('ContusShortcode') != true)
{//checks the ContusShortcode class has been defined if starts
    class ContusShortcode
    {//ContusShortcode class starts

        public function __construct()
        {//CONSTRUCTOR STARTS
            global $wpdb;
            $this->_wpdb = $wpdb;
            $this->_videosettingstable = $this->_wpdb->prefix.'hdflvvideoshare_settings';
            $this->_videoinfotable = $this->_wpdb->prefix.'hdflvvideoshare';
        }//CONSTRUCTOR ENDS
 public function get_more_pageid()
        {//function for getting more page ID starts
           $moreName = $this->_wpdb->get_var("select ID from " . $this->_wpdb->prefix . "posts WHERE post_content='[videomore]' and post_status='publish' and post_type='page' limit 1");
           return $moreName;
        }//function for getting more page ID ends
 public function get_video_detail($vid)
        {//function for getting Tag name starts
            global $wpdb;
           $video_count = $this->_wpdb->get_row("SELECT t1.vid,t1.description,t4.tags_name,t1.name,t1.post_date,t1.image,t1.file,t1.hitcount,t1.ratecount,t1.file_type,t1.embedcode,t1.rate,t2.playlist_id,t3.playlist_name"
                . " FROM " . $this->_videoinfotable . " AS t1"
                . " LEFT JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play AS t2"
                . " ON t2.media_id = t1.vid"
                . " LEFT JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist AS t3"
                . " ON t3.pid = t2.playlist_id"
                . " LEFT JOIN " . $wpdb->prefix . "hdflvvideoshare_tags AS t4"
                . " ON t1.vid = t4.media_id"
                . " WHERE t1.publish='1' AND t3.is_publish='1' AND t1.vid='" . intval($vid) . "' LIMIT 1");
           return $video_count;
        }//function for getting Tag name ends

 public function get_playlist_detail($vid)
        {//function for getting Tag name starts
            global $wpdb;
           $video_count = $this->_wpdb->get_results("SELECT t3.playlist_name,t3.pid"
                . " FROM " . $wpdb->prefix . "hdflvvideoshare_playlist AS t3"
                . " LEFT JOIN  ". $wpdb->prefix . "hdflvvideoshare_med2play AS t2"
                . " ON t3.pid = t2.playlist_id"
                . " WHERE t3.is_publish='1' AND t2.media_id='" . intval($vid) . "'");
           return $video_count;
        }//function for getting Tag name ends
        
    }//ContusVideo class ends
}//checks the ContusVideo class has been defined if ends
?>