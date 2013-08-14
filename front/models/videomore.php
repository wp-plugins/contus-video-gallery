<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: video more page model file
Version: 2.2
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/
if(class_exists('ContusMore') != true)
{//checks the ContusVideo class has been defined if starts
    class ContusMore
    {//ContusVideo class starts

        public function __construct()
        {//CONSTRUCTOR STARTS
            global $wpdb;
            $this->_wpdb = $wpdb;
            $this->_videosettingstable = $this->_wpdb->prefix.'hdflvvideoshare_settings';
            $this->_videoinfotable = $this->_wpdb->prefix.'hdflvvideoshare';
        }//CONSTRUCTOR ENDS

        public function get_settingsdata()
        {//function for getting settings data starts
            $query = "SELECT * FROM " . $this->_videosettingstable ." WHERE settings_id = 1";
            return $this->_wpdb->get_row($query);
        }//function for getting settings data ends

        public function get_more_pageid()
        {//function for getting more page ID starts
           $moreName = $this->_wpdb->get_var("select ID from " . $this->_wpdb->prefix . "posts WHERE post_content='[videomore]' and post_status='publish' and post_type='page' limit 1");
           return $moreName;
        }//function for getting more page ID ends
        public function get_video_count()
        {//function for getting more page ID starts
           $video_count = $this->_wpdb->get_var("SELECT count(*) FROM " . $this->_videoinfotable . " WHERE featured='1' and publish='1'");
           return $video_count;
        }//function for getting more page ID ends

        public function get_thumdata($thumImageorder,$where,$pagenum,$dataLimit)
        {//function for getting settings data starts
            $pagenum = isset($pagenum ) ? absint($pagenum ) : 1;
            $offset = ( $pagenum - 1 ) * $dataLimit;
            $query = "SELECT distinct w.*,s.guid FROM " . $this->_videoinfotable. " w
                      INNER JOIN " . $this->_wpdb->prefix . "hdflvvideoshare_med2play m ON m.media_id = w.vid
                      INNER JOIN " . $this->_wpdb->prefix . "hdflvvideoshare_playlist p ON p.pid=m.playlist_id
                      INNER JOIN " . $this->_wpdb->prefix . "posts s ON s.ID=w.slug
                      WHERE w.publish='1' AND p.is_publish='1' $where GROUP BY w.vid ORDER BY ".$thumImageorder." LIMIT ".$offset.','.$dataLimit;
            return $this->_wpdb->get_results($query);
        }//function for getting settings data ends

        public function get_categoriesthumdata($pagenum,$dataLimit)
        {//function for getting settings data starts
            $pagenum = isset($pagenum ) ? absint($pagenum ) : 1;
            $offset = ( $pagenum - 1 ) * $dataLimit;
            $query = "SELECT * FROM " . $this->_wpdb->prefix . "hdflvvideoshare_playlist WHERE is_publish='1' LIMIT " . $offset . "," . $dataLimit ;
            return $this->_wpdb->get_results($query);
        }//function for getting settings data ends

        public function get_searchthumbdata($thumImageorder,$pagenum,$dataLimit)
        {//function for getting settings data starts
            $pagenum = isset($pagenum ) ? absint($pagenum ) : 1;
            $offset = ( $pagenum - 1 ) * $dataLimit;
            $query = "SELECT t1.vid,t1.name,t1.description,s.guid,t3.pid,t3.playlist_name,t1.image,t1.file,t1.file_type,t1.duration,t1.hitcount,t2.playlist_id,t3.playlist_name FROM " . $this->_wpdb->prefix . "hdflvvideoshare AS t1
                        LEFT JOIN " . $this->_wpdb->prefix . "hdflvvideoshare_med2play AS t2 ON t2.media_id = t1.vid
                        LEFT JOIN " . $this->_wpdb->prefix . "hdflvvideoshare_playlist AS t3 ON t3.pid = t2.playlist_id
                        LEFT JOIN " . $this->_wpdb->prefix . "hdflvvideoshare_tags AS t4 ON t4.media_id = t1.vid
                        LEFT JOIN " . $this->_wpdb->prefix . "posts s ON s.ID=t1.slug
                        WHERE $thumImageorder AND t3.is_publish='1' AND t1.publish='1' GROUP BY t1.vid  LIMIT " . $offset . "," . $dataLimit ;
            return $this->_wpdb->get_results($query);
        }//function for getting settings data ends

        public function get_Countof_Videosearch($thumImageorder)
        {//function for getting settings data starts
            global $wpdb;
             $query = "SELECT t1.vid FROM " . $this->_wpdb->prefix . "hdflvvideoshare AS t1
                    LEFT JOIN " . $this->_wpdb->prefix . "hdflvvideoshare_med2play AS t2 ON t2.media_id = t1.vid
                    LEFT JOIN " . $this->_wpdb->prefix . "hdflvvideoshare_playlist AS t3 ON t3.pid = t2.playlist_id
                    LEFT JOIN " . $this->_wpdb->prefix . "hdflvvideoshare_tags AS t4 ON t4.media_id = t1.vid
                    LEFT JOIN " . $this->_wpdb->prefix . "posts s ON s.ID=t1.slug
                    WHERE $thumImageorder AND t3.is_publish='1' AND t1.publish='1' GROUP BY t1.vid ";
            $results = count($this->_wpdb->get_results($query));
             return $results;
        }//function for getting settings data ends

        public function get_Countof_Videos($thumImageorder)
        {//function for getting settings data starts
            global $wpdb;
            $playid=filter_input(INPUT_GET, 'playid');
            if(!empty($playid)){
             $query = "SELECT count(*) FROM " . $wpdb->prefix . "hdflvvideoshare as w INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play as m ON m.media_id = w.vid INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist as p on m.playlist_id = p.pid WHERE w.publish='1' and p.is_publish='1' and m.playlist_id=" . intval($thumImageorder);
             $result = $this->_wpdb->get_var($query);
            }else{
            $query = "SELECT w.vid FROM " . $this->_videoinfotable. " w
                        INNER JOIN " . $this->_wpdb->prefix . "hdflvvideoshare_med2play m ON m.media_id = w.vid
                        INNER JOIN " . $this->_wpdb->prefix . "hdflvvideoshare_playlist p ON p.pid=m.playlist_id
                        WHERE w.publish='1'  AND p.is_publish='1' GROUP BY w.vid ORDER BY ".$thumImageorder;
            $result = $this->_wpdb->get_results($query);
            $result= count($result);
            }
            return $result;
        }//function for getting settings data ends

        public function get_Countof_Videocategories()
        {//function for getting settings data starts
            global $wpdb;
             $query = "SELECT count(*) FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE is_publish='1'";
            return $this->_wpdb->get_var($query);
        }//function for getting settings data ends

         public function home_catthumbdata($thumImageorder,$pagenum,$dataLimit)
        {//function for getting settings data starts
              global $wpdb;
              $pagenum = isset($pagenum ) ? absint($pagenum ) : 1;
              $offset = ( $pagenum - 1 ) * $dataLimit;
           $query = "SELECT s.guid,w.*,p.playlist_name FROM " . $wpdb->prefix . "hdflvvideoshare as w
                    INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play as m ON m.media_id = w.vid
                    INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist as p on m.playlist_id = p.pid
                    INNER JOIN " . $wpdb->prefix . "posts s ON s.ID=w.slug
                    WHERE w.publish='1' AND p.is_publish='1' AND m.playlist_id=" . intval($thumImageorder) . "
                    GROUP BY w.vid ORDER BY w.ordering asc LIMIT ".$offset.','.$dataLimit;
            return $this->_wpdb->get_results($query);
        }//function for getting settings data ends

    }//ContusVideo class ends
}//checks the ContusVideo class has been defined if ends
?>