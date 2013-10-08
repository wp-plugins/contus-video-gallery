<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: Video home page controller file.
Version: 2.3.1
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/

include_once($frontModelPath . 'videohome.php');//including ContusVideo model file for get database information.
if(class_exists('ContusVideoController') != true)
    {
    class ContusVideoController extends  ContusVideo
    { 
        public function __construct()
        {//contructor starts
            parent::__construct();
        }//contructor ends

        function settings_data()
        { //getting settings data function starts
            return $this->get_settingsdata();
        } //getting settings data function ends
        function videos_data()
        { //getting videos data function starts
            return $this->get_videosdata();
        } //getting videos data function ends
        function More_pageid()
        { //getting more page ID function starts
            return $this->get_more_pageid();
        } //getting more page ID function ends
        function Video_count()
        { //getting Video count function starts
            return $this->get_video_count();
        } //getting Video count function ends
        function Tag_detail($vid)
        { //getting tag detail function starts
            return $this->get_tag_name($vid);
        } //getting tag detail function ends

         function home_thumbdata($thumImageorder,$where,$dataLimit)
         {// HOME PAGE FEATURED VIDEOS STARTS
            return $this->get_thumdata($thumImageorder,$where,$dataLimit);
         }
         function home_playxmldata($getVid,$thumImageorder,$where,$dataLimit)
         {// HOME PAGE FEATURED VIDEOS STARTS
            return $this->get_playxmldata($getVid,$thumImageorder,$where,$dataLimit);
         }
         function home_categoriesthumbdata($pagenum,$dataLimit)
         {// HOME PAGE FEATURED VIDEOS STARTS
            return $this->get_categoriesthumdata($pagenum,$dataLimit);
         }
         function Countof_Videocategories()
         {// HOME PAGE FEATURED VIDEOS STARTS
            return $this->get_Countof_Videocategories();
         }
         function home_playerdata()
         {// HOME PAGE player VIDEOS STARTS
            return $this->get_singlevideodata();
         } 
         function home_featuredvideodata()
         {// HOME PAGE player VIDEOS STARTS
            return $this->get_featuredvideodata();
         }
         function home_featuredvideodata_banner()
         {// HOME PAGE player VIDEOS STARTS
            return $this->get_featuredvideodata_banner();
         }
         function video_detail($vid)
         {// HOME PAGE player VIDEOS STARTS
            return $this->get_video_detail($vid);
         }
    }//class over
  }
  else
  {
    echo 'class contusVideo already exists';
  }
 include_once($frontViewPath . 'videohome.php');//including ContusVideo model file for get database information.
?>