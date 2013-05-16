<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: video home page model file
Version: 2.1
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/

if(class_exists('ContusVideo') != true)
{//checks the ContusVideo class has been defined if starts
    class ContusVideo
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

        public function get_videosdata()
        {//function for getting settings data starts
            $query = "SELECT * FROM " . $this->_videoinfotable;
            return $this->_wpdb->get_results($query);
        }//function for getting settings data ends

        public function get_Countof_Videocategories()
        {//function for getting settings data starts
            global $wpdb;
             $query = "SELECT count(*) FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE is_publish='1'";
            return $this->_wpdb->get_var($query);
        }//function for getting settings data ends

        public function get_categoriesthumdata($pagenum,$dataLimit)
        {//function for getting settings data starts
            global $wpdb;
            $pagenum = isset($pagenum ) ? absint($pagenum ) : 1;
            $offset = ( $pagenum - 1 ) * $dataLimit;
            $query = "SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE is_publish='1' LIMIT " . $offset . "," . $dataLimit ;
            $result = $wpdb->get_results($query);
            return $result;
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

        public function get_tag_name($vid)
        {//function for getting Tag name starts
           $video_count = $this->_wpdb->get_var("SELECT tags_name from " . $this->_wpdb->prefix . "hdflvvideoshare_tags WHERE media_id='".intval($vid)."'");
           return $video_count;
        }//function for getting Tag name ends
        public function get_video_detail($vid)
        {//function for getting Tag name starts
            global $wpdb;
           $select = "SELECT distinct w.vid,w.*,s.guid FROM " . $wpdb->prefix ."hdflvvideoshare w
               INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play m ON m.media_id = w.vid 
               INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist p ON p.pid=m.playlist_id 
               INNER JOIN " . $wpdb->prefix  . "posts s ON s.ID=w.slug
               where w.vid='$vid' and w.publish='1' AND p.is_publish='1' GROUP BY w.vid";

        $themediafiles = $wpdb->get_results($select);
        $getPlaylist   = $wpdb->get_results("SELECT playlist_id FROM ".$wpdb->prefix."hdflvvideoshare_med2play WHERE media_id='".intval($vid)."' LIMIT 1");
         foreach ($getPlaylist as $getPlaylists)
        {
            if ($getPlaylists->playlist_id != '')
            {
              $playlist_id = $getPlaylists->playlist_id;
            }
            else
            {
                echo "<script>alert('No videos is  here');</script>";
            }


         $fetch_video   = "SELECT distinct w.vid FROM " . $wpdb->prefix . "hdflvvideoshare w
        INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play m
        INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist p
        WHERE (m.playlist_id = '$playlist_id'
        AND m.media_id = w.vid AND p.pid=m.playlist_id AND m.media_id != '".intval($vid)."' AND w.publish='1' AND p.is_publish='1' ) GROUP BY w.vid";
        $fetched       = $wpdb->get_results($fetch_video);




        // Array rotation to autoplay the videos correctly
                                $arr1 = array();
                                $arr2 = array();
                                if(count($fetched) > 0){
                                    foreach($fetched as $r):
                                        if($r->vid > $themediafiles[0]->vid){      //Storing greater values in an array

                                            $query = "SELECT distinct w.vid,w.*,s.guid FROM " . $wpdb->prefix . "hdflvvideoshare w
                                                            INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play m
                                                            INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist p
                                                    INNER JOIN " . $wpdb->prefix  . "posts s ON s.ID=w.slug
                                                            WHERE (w.vid=$r->vid AND m.media_id != '".intval($vid)."' AND w.publish='1' AND p.is_publish='1' ) GROUP BY w.vid";

                                            $arrGreat       = $wpdb->get_row($query);
                                            $arr1[] = $arrGreat;
                                        }else{                          //Storing lesser values in an array
                                            $query = "SELECT distinct w.vid,w.*,s.guid FROM " . $wpdb->prefix . "hdflvvideoshare w
                                                            INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play m
                                                            INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist p
                                                    INNER JOIN " . $wpdb->prefix  . "posts s ON s.ID=w.slug
                                                            WHERE (w.vid=$r->vid AND m.media_id != '".intval($vid)."' AND w.publish='1' AND p.is_publish='1' ) GROUP BY w.vid";

                                            $arrLess       = $wpdb->get_row($query);
                                            $arr2[] = $arrLess;
                                        }
                                    endforeach;
                                }
 
        $themediafiles = array_merge($themediafiles,$arr1,$arr2);

        }

           return $themediafiles;
        }//function for getting Tag name ends

        public function video_Pid_detail($pid)
        {//function for getting Tag name starts
            global $wpdb;
       $playlist_id     =$pid;
            $playlist = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE pid = '".intval($playlist_id)."'");
    if ($playlist)
    {
        $select = " SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare w";
        $select .= " INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play m";
        $select .= " WHERE (m.playlist_id = '".intval($playlist_id)."'";
        $select .= " AND m.media_id = w.vid) GROUP BY w.vid ";
        $select .= " ORDER BY m.sorder ASC , m.porder " . $playlist->playlist_order . " ,w.vid " . $playlist->playlist_order;
        $themediafiles = $wpdb->get_results($wpdb->prepare($select));
    }

           return $themediafiles;
        }//function for getting Tag name ends

        public function get_singlevideodata()
        {
          $query = "SELECT * FROM " . $this->_videoinfotable . " WHERE featured='1' and publish='1' ORDER BY vid DESC LIMIT 0,1";
          return  $this->_wpdb->get_row($query);
        }//function for getting settings data ends

        public function get_featuredvideodata()
        {
          $query = "SELECT distinct w.* FROM " . $this->_videoinfotable . " w
              INNER JOIN " . $this->_wpdb->prefix . "hdflvvideoshare_med2play m ON m.media_id = w.vid
              INNER JOIN " . $this->_wpdb->prefix . "hdflvvideoshare_playlist p ON p.pid=m.playlist_id
              WHERE featured='1' and publish='1' AND p.is_publish='1' GROUP BY w.vid ORDER BY ordering ASC";
          return  $this->_wpdb->get_results($query);
        }//function for getting settings data ends

        public function get_featuredvideodata_banner()
        {
          $query = "SELECT distinct w.* FROM " . $this->_videoinfotable . " w
              INNER JOIN " . $this->_wpdb->prefix . "hdflvvideoshare_med2play m ON m.media_id = w.vid
              INNER JOIN " . $this->_wpdb->prefix . "hdflvvideoshare_playlist p ON p.pid=m.playlist_id
                  INNER JOIN " . $this->_wpdb->prefix . "posts s ON s.ID=w.slug
              WHERE featured='1' and publish='1' AND p.is_publish='1' GROUP BY w.vid ORDER BY vid ASC LIMIT 0,4";
          return  $this->_wpdb->get_results($query);
        }//function for getting settings data ends
        
        
         public function get_thumdata($thumImageorder,$where,$dataLimit)
        {//function for getting settings data starts
                  $query = "SELECT distinct w.*,s.guid,p.playlist_name,p.pid FROM " . $this->_videoinfotable. " w
                INNER JOIN " . $this->_wpdb->prefix . "hdflvvideoshare_med2play m ON m.media_id = w.vid
              INNER JOIN " . $this->_wpdb->prefix . "hdflvvideoshare_playlist p ON p.pid=m.playlist_id
              INNER JOIN " . $this->_wpdb->prefix . "posts s ON s.ID=w.slug
                WHERE w.publish='1' AND p.is_publish='1' $where GROUP BY w.vid ORDER BY ".$thumImageorder." LIMIT ".$dataLimit;
            return $this->_wpdb->get_results($query);
        }//function for getting settings data ends

        
    }//ContusVideo class ends
}//checks the ContusVideo class has been defined if ends