<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: Video detail and short tags controller file.
Version: 2.3.1.0.1
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/
include_once($frontModelPath . 'videoshortcode.php');//including ContusVideo model file for get database information.
if(class_exists('ContusVideoShortcodeController') != true)
    {
    class ContusVideoShortcodeController extends  ContusShortcode
    {
        public function __construct()
        {//contructor starts
            parent::__construct();
        }//contructor ends
        function More_pageid()
        { //getting more page ID function starts
            return $this->get_more_pageid();
        } //getting more page ID function ends

         function video_detail($vid)
        { //getting video detail function starts
            return $this->get_video_detail($vid);
        } //getting video detail function ends

         function playlist_detail($vid)
        { //getting video detail function starts
            return $this->get_playlist_detail($vid);
        } //getting video detail function ends
    }//class over
  }
  else
  {
    echo 'class contusVideo already exists';
  }
 include_once($frontViewPath . 'videoshortcode.php');//including ContusVideo model file for get database information.
?>