<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: Video Controller.
Version: 2.5
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/
include_once($adminModelPath . 'video.php'); //including video model file for get database information.

if (class_exists('VideoController') != true) {//checks if the VideoController class has been defined start

    class VideoController extends VideoModel {//VideoController starts

        public $_status;
        public $_msg;
        public $_search;
        public $_videosearchQuery;
        public $_settingsData;
        public $_addnewVideo;
        public $_searchBtn;
        public $_update;
        public $_add;
        public $_del;
        public $_featured;
        public $_orderDirection;
        public $_orderBy;

        public function __construct() {//contructor starts
            parent::__construct();
            $this->_videosearchQuery = filter_input(INPUT_POST, 'videosearchQuery');
            $this->_addnewVideo = filter_input(INPUT_POST, 'add_video');
            $this->_status = filter_input(INPUT_GET, 'status');
            $this->_searchBtn = filter_input(INPUT_POST, 'videosearchbtn');
            $this->_update = filter_input(INPUT_GET, 'update');
            $this->_add = filter_input(INPUT_GET, 'add');
            $this->_del = filter_input(INPUT_GET, 'del');
            $this->_featured = filter_input(INPUT_GET, 'featured');
            $this->_orderDirection = filter_input(INPUT_GET, 'order');
            $this->_orderBy = filter_input(INPUT_GET, 'orderby');
            $this->_settingsData = $this->get_settingsdata();
        }
        public function add_newvideo() {//function for adding video starts
            global $wpdb;
            if (isset($this->_status) || isset($this->_featured)) {//updating status of video starts
                $this->status_update($this->_videoId, $this->_status, $this->_featured);
            }//updating status of video ends

            if (isset($this->_addnewVideo)) {
                $videoName = $videoName1 = filter_input(INPUT_POST, 'name');
                $titlequery         = $this->_wpdb->get_var($wpdb->prepare("SELECT count(vid) FROM ".$wpdb->prefix . "hdflvvideoshare where name=%s",$videoName1));
                if(!empty($titlequery) || $titlequery>0){
                    $videoName1       = $videoName1.rand();
                }
                $slug = sanitize_title($videoName1);
                $videoDescription = filter_input(INPUT_POST, 'description');
                $embedcode = filter_input(INPUT_POST, 'embed_code');
                $tags_name = filter_input(INPUT_POST, 'tags_name');
                $seo_tags_name=stripslashes($tags_name);
                $seo_tags_name=strtolower($seo_tags_name);
		$seo_tags_name = preg_replace('/[&:\s]+/i', '-', $seo_tags_name);
		$seo_tags_name = preg_replace('/[#!@$%^.,:;\/&*(){}\"\'\[\]<>|?]+/i', '', $seo_tags_name);
		$seo_tags_name = preg_replace('/---|--+/i', '-', $seo_tags_name);
                $streamname = filter_input(INPUT_POST, 'streamerpath-value');
                $videoLinkurl = filter_input(INPUT_POST, 'youtube-value');
                $sorder=$act_playlist='';
                if(!empty($_POST['playlist']))
                $act_playlist = $_POST['playlist'];
                $pieces = explode(",", $_POST['hid']);
                if(!empty($_POST['sorder']))
                $sorder = $_POST['sorder'];
                $videoFeatured = filter_input(INPUT_POST, 'feature');
                $videoDownload = filter_input(INPUT_POST, 'download');
                $videomidrollads = filter_input(INPUT_POST, 'midrollads');
                $videoimaad = filter_input(INPUT_POST, 'imaad');
                $videoPostrollads = filter_input(INPUT_POST, 'postrollads');
                $videoPrerollads = filter_input(INPUT_POST, 'prerollads');
                $videoDate = date('Y-m-d H:i:s');
                
                $dir                    = dirname(plugin_basename(__FILE__));
                $dirExp                 = explode('/', $dir);
                $dirPage                = $dirExp[0];
                $video_path             = str_replace('plugins/'.$dirPage.'/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL);
                $srt_path1               = str_replace('plugins', 'uploads/videogallery/', APPTHA_VGALLERY_BASEDIR);
                $srt_path               = str_replace($dirPage, '', $srt_path1);
                    
                $ordering    = $this->_wpdb->get_var("SELECT count(ordering) FROM ".$wpdb->prefix . "hdflvvideoshare");
                $videoPublish = filter_input(INPUT_POST, 'publish');
                $islive = filter_input(INPUT_POST, 'islive-value');
                $video1 = filter_input(INPUT_POST, 'normalvideoform-value');
                $video2 = filter_input(INPUT_POST, 'hdvideoform-value');
                $img1 = filter_input(INPUT_POST, 'thumbimageform-value');
                $img2 = filter_input(INPUT_POST, 'previewimageform-value');
                $subtitle1 = filter_input(INPUT_POST, 'subtitle1form-value');
                $subtitle2 = filter_input(INPUT_POST, 'subtitle2form-value');
                $subtitle_lang1 = filter_input(INPUT_POST, 'subtitle_lang1');
                $subtitle_lang2 = filter_input(INPUT_POST, 'subtitle_lang2');
                $member_id = filter_input(INPUT_POST, 'member_id');
                 $img3 = $_POST['customimage'];
                $pre_image = $_POST['custompreimage'];

                    if ($videoLinkurl != '') {
                        if (preg_match("#https?://#", $videoLinkurl) === 0) {
                            $videoLinkurl = 'http://' . $videoLinkurl;
                        }
                    $act_filepath = addslashes(trim($videoLinkurl));
                    $file_type = '1';

                    if (strpos($act_filepath, 'youtube') > 0) {
                        $imgstr = explode("v=", $act_filepath);
                        $imgval = explode("&", $imgstr[1]);
                        $match = $imgval[0];
                        $previewurl = "http://img.youtube.com/vi/" . $imgval[0] . "/maxresdefault.jpg";
                        $img = "http://img.youtube.com/vi/" . $imgval[0] . "/mqdefault.jpg";
                    } else if (strpos($act_filepath, 'youtu.be') > 0) {
                        $imgstr = explode("/", $act_filepath);
                        $match = $imgstr[3];
                        $previewurl = "http://img.youtube.com/vi/" . $imgstr[3] . "/maxresdefault.jpg";
                        $img = "http://img.youtube.com/vi/" . $imgstr[3] . "/mqdefault.jpg";
                        $act_filepath = "http://www.youtube.com/watch?v=" . $imgstr[3];
                    } else if(strpos($act_filepath,'dailymotion') > 0){                 ## check video url is dailymotion
                        $split      = explode("/",$act_filepath);
                        $split_id   = explode("_",$split[4]);
                        $img        = $previewurl = 'http://www.dailymotion.com/thumbnail/video/'.$split_id[0];
                        $file_type  = '1';
                    } else if(strpos($act_filepath,'viddler') > 0) {                    ## check video url is viddler
                            $imgstr = explode("/", $act_filepath);
                            $img    = $previewurl = "http://cdn-thumbs.viddler.com/thumbnail_2_" . $imgstr[4] . "_v1.jpg";
                            $file_type = '1';
                    }

                    $youtube_data = $this->hd_GetSingleYoutubeVideo($match);
                    $sec = $youtube_data['duration']['SECONDS'];
                    
                    $duration = $this->convertTime($sec);
                } else {
                    $act_filepath1 = $_REQUEST['normalvideoform-value'];
                    $act_filepath1=$srt_path.$act_filepath1;
                    $act_filepath = addslashes(trim($_POST['customurl']));
                    $ffmpeg_path = $this->_settingsData->ffmpeg_path;
                    $file_type = '2';
                    ob_start();
                    passthru("$ffmpeg_path -i \"{$act_filepath1}\" 2>&1");
                    $duration = ob_get_contents();
                    ob_end_clean();

                    $search='/Duration: (.*?),/';
                    $duration=preg_match($search, $duration, $matches, PREG_OFFSET_CAPTURE, 3);
                     if (!empty($duration)) {
                        $duration_array = split(':', $matches[1][0]);
                        $sec = ceil($duration_array[0] * 3600 + $duration_array[1] * 60 + $duration_array[2]);
                        $duration = $this->convertTime($sec);
                    }
                }

                $act_filepath2 = trim($_POST['customhd']);
                $act_image = addslashes(trim($_POST['customurl']));
                $act_link = $act_hdpath = $act_name = $act_opimage = '';
                if (!empty($act_filepath)) {
                    if (strpos($act_filepath, 'youtube') > 0 || strpos($act_filepath, 'youtu.be') > 0) {
                        if (strpos($act_filepath, 'youtube') > 0) {
                            $imgstr = explode("v=", $act_filepath);
                            $imgval = explode("&", $imgstr[1]);
                            $match = $imgval[0];
                        } else if (strpos($act_filepath, 'youtu.be') > 0) {
                            $imgstr = explode("/", $act_filepath);
                            $match = $imgstr[3];
                            $act_filepath = "http://www.youtube.com/watch?v=" . $imgstr[3];
                        }
                        $act_image = "http://i3.ytimg.com/vi/" . $match . "/mqdefault.jpg";
                        $act_opimage = "http://i3.ytimg.com/vi/" . $match . "/maxresdefault.jpg";
                        $youtube_data = $this->hd_GetSingleYoutubeVideo($match);
                        if ($youtube_data) {
                            if ($act_name == '')
                                $act_name = addslashes($youtube_data['title']);
                            if ($act_image == '')
                                $act_image = "http://i3.ytimg.com/vi/" . $youtube_data['id'] . "/mqdefault.jpg";
                            if ($act_link == '')
                                $act_link = $act_filepath;
                            $file_type = '1';
                        } else
                            $this->render_error(__('Could not retrieve Youtube video information', 'hdflvvideoshare'));
                    }else if(strpos($act_filepath,'dailymotion') > 0){              ## check video url is dailymotion
                        $split      = explode("/",$act_filepath);
                        $split_id   = explode("_",$split[4]);
                        $act_image        = $act_opimage = 'http://www.dailymotion.com/thumbnail/video/'.$split_id[0];
                        $file_type  = '1';
                    } else if(strpos($act_filepath,'viddler') > 0) {                    ## check video url is viddler
                            $imgstr = explode("/", $act_filepath);
                            $act_image    = $act_opimage = "http://cdn-thumbs.viddler.com/thumbnail_2_" . $imgstr[4] . "_v1.jpg";
                            $file_type = '1';
                    }else {
                        $act_hdpath = $act_filepath2;
                        $act_image = $img3;
                        $act_opimage = $pre_image;
                        $file_type = '3';
                    }
                } else {
                    if ($video1 != '')
                        $act_filepath = "$video1";
                    if ($video2 != '')
                        $act_hdpath = "$video2";
                    if ($img1 != '')
                        $act_image = "$img1";
                    if ($img2 != '')
                        $act_opimage = "$img2";
                }

                if(!empty($streamname)){
                    $file_type = '4';
                    $act_opimage = $pre_image;
                    }
                if(!empty($embedcode)){
                    $file_type = '5';
                    }
                if(empty($member_id)){    
                    $current_user = wp_get_current_user();
                    $member_id = $current_user->ID;
                }
                $videoData = array(
                    'name' => $videoName,
                    'description' => $videoDescription,
                    'embedcode' => $embedcode,
                    'file' => $act_filepath,
                    'file_type' => $file_type,
                    'member_id' => $member_id,
                    'duration' => $duration,
                    'hdfile' => $act_hdpath,
                    'streamer_path' => $streamname,
                    'islive' => $islive,
                    'image' => $act_image,
                    'opimage' => $act_opimage,
                    'srtfile1' => $subtitle1,
                    'srtfile2' => $subtitle2,
                    'subtitle_lang1' => $subtitle_lang1,
                    'subtitle_lang2' => $subtitle_lang2,
                    'link' => $videoLinkurl,
                    'featured' => $videoFeatured,
                    'download' => $videoDownload,
                    'postrollads' => $videoPostrollads,
                    'midrollads' => $videomidrollads,
                    'imaad' => $videoimaad,
                    'prerollads' => $videoPrerollads,
                    'publish' => $videoPublish
                );

                if (!isset($this->_videoId)) {
                    $videoData['post_date'] = $videoDate;
                    $videoData['ordering'] = $ordering;
                    $videoData['slug'] = '';
                }
                
                if (isset($this->_videoId)) {   //update for video if starts
                    $slug_id=$this->_wpdb->get_var("SELECT slug FROM ".$wpdb->prefix . "hdflvvideoshare WHERE vid =$this->_videoId");
                    $videoData['slug'] = $slug_id;
                   $updateflag = $this->video_update($videoData, $this->_videoId,$slug);

                        if(!empty($subtitle1)){
                            $sub_title1 = $srt_path.$subtitle1;
                            $new_subtitle1 = $this->_videoId.'_'.$subtitle_lang1.'.srt';
                            rename($sub_title1,$srt_path.$new_subtitle1);
                        } else {
                            $new_subtitle1 = '';
                        }
                        
                        if(!empty($subtitle2)){
                            $sub_title2 = $srt_path.$subtitle2;
                            $new_subtitle2 = $this->_videoId.'_'.$subtitle_lang2.'.srt';
                            rename($sub_title2,$srt_path.$new_subtitle2);
                        } else {
                            $new_subtitle2 = '';
                        }
                        $wpdb->query(" UPDATE " . $wpdb->prefix . "hdflvvideoshare SET srtfile1= '$new_subtitle1',srtfile2= '$new_subtitle2' WHERE vid = '$this->_videoId'");

//                    if ($updateflag) {
                        if ($this->_videoId && is_array($act_playlist)) {
                            $old_playlist = $wpdb->get_col(" SELECT playlist_id FROM " . $wpdb->prefix . "hdflvvideoshare_med2play WHERE media_id = $this->_videoId");
    if (!$old_playlist) {
        $old_playlist = array();
    } else {
        $old_playlist = array_unique($old_playlist);
    }

    // Delete any ?
    $delete_list = array_diff($old_playlist, $act_playlist);
    if ($delete_list) {
        foreach ($delete_list as $del) {
            $wpdb->query(" DELETE FROM " . $wpdb->prefix . "hdflvvideoshare_med2play WHERE playlist_id = $del AND media_id = $this->_videoId");
        }
    }

    $add_list = array_diff($act_playlist, $old_playlist);
                            if ($add_list) {
                                foreach ($add_list as $new_list) {
                                    $new_list1 = $new_list - 1;
                                    if ($sorder[$new_list1] == '')
                                        $sorder[$new_list1] = '0';
                                    $wpdb->query(" INSERT INTO " . $wpdb->prefix . "hdflvvideoshare_med2play (media_id,playlist_id,sorder) VALUES ($this->_videoId, $new_list, '0')");
                                }
                            }
                            $i = 0;
                            foreach ($pieces as $new_list) {
                                $wpdb->query(" UPDATE " . $wpdb->prefix . "hdflvvideoshare_med2play SET sorder= '0' WHERE media_id = '$this->_videoId' and playlist_id = '$new_list'");
                                $i++;
                            }
                        }
                         $insert_tags_name=$this->_wpdb->get_var("SELECT media_id FROM ".$wpdb->prefix . "hdflvvideoshare_tags WHERE media_id = '$this->_videoId'");
                         if(empty($insert_tags_name)){
                             $wpdb->query("INSERT INTO " . $wpdb->prefix . "hdflvvideoshare_tags (media_id,tags_name,seo_name) VALUES ($this->_videoId, '$tags_name', '$seo_tags_name')");
                         } else {
                             $wpdb->query("UPDATE " . $wpdb->prefix . "hdflvvideoshare_tags SET tags_name='$tags_name',seo_name='$seo_tags_name' WHERE media_id = '$this->_videoId'");
                         }
                        $this->admin_redirect("admin.php?page=newvideo&videoId=" . $this->_videoId . "&update=1");
//                    } else {
//                        $this->admin_redirect("admin.php?page=newvideo&videoId=" . $this->_videoId . "&update=0");
//                    }
                } //update for video if ends
                else {//adding video else starts
                    $insertflag = $this->insert_video($videoData,$slug);
                    if ($insertflag != 0) {
                         
                        if(!empty($subtitle1)){
                            $sub_title1 = $srt_path.$subtitle1;
                            $new_subtitle1 = $insertflag.'_'.$subtitle_lang1.'.srt';
                            rename($sub_title1,$srt_path.$new_subtitle1);
                        } else {
                            $new_subtitle1 = '';
                        }
                        
                        if(!empty($subtitle2)){
                            $sub_title2 = $srt_path.$subtitle2;
                            $new_subtitle2 = $insertflag.'_'.$subtitle_lang2.'.srt';
                            rename($sub_title2,$srt_path.$new_subtitle2);
                        } else {
                            $new_subtitle2 = '';
                        }
                        $wpdb->query(" UPDATE " . $wpdb->prefix . "hdflvvideoshare SET srtfile1= '$new_subtitle1',srtfile2= '$new_subtitle2' WHERE vid = '$insertflag'");
                        
                        if(!empty($tags_name)){
                            $wpdb->query("INSERT INTO " . $wpdb->prefix . "hdflvvideoshare_tags (media_id,tags_name,seo_name) VALUES ($insertflag, '$tags_name', '$seo_tags_name')");
                        }

                        $video_aid = $insertflag;
                        if ($video_aid && is_array($act_playlist)) {
                            $add_list = array_diff($act_playlist, array());

                            if ($add_list) {
                                foreach ($add_list as $new_list) {
                                    $new_list1 = $new_list - 1;
//                                    if ($sorder[$new_list1] == '')
                                        $sorder[$new_list1] = '0';
                                    $wpdb->query(" INSERT INTO " . $wpdb->prefix . "hdflvvideoshare_med2play (media_id,playlist_id,sorder) VALUES ($video_aid, $new_list, '0')");
                                }
                            }
                            $i = 0;
                            foreach ($pieces as $new_list) {
                                $wpdb->query(" UPDATE " . $wpdb->prefix . "hdflvvideoshare_med2play SET sorder= '0' WHERE media_id = '$video_aid' and playlist_id = '$new_list'");
                                $i++;
                            }
                        }
                    }

                    if (!$insertflag) {
                        $this->admin_redirect("admin.php?page=video&add=0");
                    } else {
                        $this->admin_redirect("admin.php?page=video&add=1");
                    }
                }//adding video else ends
            }
        }

        public function render_error($message) {
?>
            <div class="wrap"><h2>&nbsp;</h2>
                <div class="error" id="error">
                    <p><strong><?php echo $message ?></strong></p>
                </div></div>
<?php
        }

        //youtube function

        function youtubeurl() {
            $act_filepath = addslashes(trim($_POST['filepath']));
            if (!empty($act_filepath)) {
                 if (strpos($act_filepath, 'youtube') > 0 || strpos($act_filepath, 'youtu.be') > 0) {
                        if (strpos($act_filepath, 'youtube') > 0) {
                            $imgstr = explode("v=", $act_filepath);
                            $imgval = explode("&", $imgstr[1]);
                            $match = $imgval[0];
                        } else if (strpos($act_filepath, 'youtu.be') > 0) {
                            $imgstr = explode("/", $act_filepath);
                            $match = $imgstr[3];
                            $act_filepath = "http://www.youtube.com/watch?v=" . $imgstr[3];
                        }
                    //print_r($match);
                    $youtube_data = $this->hd_GetSingleYoutubeVideo($match);
                    if ($youtube_data) {
                        $act[0] = addslashes($youtube_data['title']);
                        if (isset($youtube_data['thumbnail_url']))
                            $act[3] = $youtube_data['thumbnail_url'];
                        $act[4] = $act_filepath;
                        if (isset($youtube_data['description']))
                            $act[5] = addslashes($youtube_data['description']);
                        if (isset($youtube_data['tags']))
                            $act[6] = addslashes($youtube_data['tags']);
                    } else
                        $this->render_error(__('Could not retrieve Youtube video information', 'hdflvvideoshare'));
                }else {
                    $act[4] = $act_filepath;
                    $this->render_error(__('URL entered is not a valid Youtube Url', 'hdflvvideoshare'));
                }
                return $act;
            }
        }

//function for adding video ends

        public function hd_GetSingleYoutubeVideo($youtube_media) {

            if ($youtube_media == '')
                return;
            $url = 'http://gdata.youtube.com/feeds/api/videos/' . $youtube_media;
            $ytb = $this->hd_ParseYoutubeDetails($this->hd_GetYoutubePage($url));
            return $ytb[0];
        }

        public function hd_ParseYoutubeDetails($ytVideoXML) {

            // Create parser, fill it with xml then delete it
            $yt_xml_parser = xml_parser_create();
            xml_parse_into_struct($yt_xml_parser, $ytVideoXML, $yt_vals);
            xml_parser_free($yt_xml_parser);
            // Init individual entry array and list array
            $yt_video = array();
            $yt_vidlist = array();

            // is_entry tests if an entry is processing
            $is_entry = true;
            // is_author tests if an author tag is processing
            $is_author = false;
            foreach ($yt_vals as $yt_elem) {

                // If no entry is being processed and tag is not start of entry, skip tag
                if (!$is_entry && $yt_elem['tag'] != 'ENTRY')
                    continue;

                // Processed tag
                switch ($yt_elem['tag']) {
                    case 'ENTRY' :
                        if ($yt_elem['type'] == 'open') {
                            $is_entry = true;
                            $yt_video = array();
                        } else {
                            $yt_vidlist[] = $yt_video;
                            $is_entry = false;
                        }
                        break;
                    case 'ID' :
                        $yt_video['id'] = substr($yt_elem['value'], -11);
                        $yt_video['link'] = $yt_elem['value'];
                        break;
                    case 'PUBLISHED' :
                        $yt_video['published'] = substr($yt_elem['value'], 0, 10) . ' ' . substr($yt_elem['value'], 11, 8);
                        break;
                    case 'UPDATED' :
                        $yt_video['updated'] = substr($yt_elem['value'], 0, 10) . ' ' . substr($yt_elem['value'], 11, 8);
                        break;
                    case 'MEDIA:TITLE' :
                        $yt_video['title'] = $yt_elem['value'];
                        break;
                    case 'MEDIA:KEYWORDS' :
                        if (isset($yt_elem['value']))
                            $yt_video['tags'] = $yt_elem['value'];
                        break;
                    case 'MEDIA:DESCRIPTION' :
                        if (isset($yt_elem['value']))
                            $yt_video['description'] = $yt_elem['value'];
                        break;
                    case 'MEDIA:CATEGORY' :
                        $yt_video['category'] = $yt_elem['value'];
                        break;
                    case 'YT:DURATION' :
                        $yt_video['duration'] = $yt_elem['attributes'];
                        break;
                    case 'MEDIA:THUMBNAIL' :
                        if ($yt_elem['attributes']['HEIGHT'] == 240) {
                            $yt_video['thumbnail'] = $yt_elem['attributes'];
                            $yt_video['thumbnail_url'] = $yt_elem['attributes']['URL'];
                        }
                        break;
                    case 'YT:STATISTICS' :
                        $yt_video['viewed'] = $yt_elem['attributes']['VIEWCOUNT'];
                        break;
                    case 'GD:RATING' :
                        $yt_video['rating'] = $yt_elem['attributes'];
                        break;
                    case 'AUTHOR' :
                        $is_author = ($yt_elem['type'] == 'open');
                        break;
                    case 'NAME' :
                        if ($is_author)
                            $yt_video['author_name'] = $yt_elem['value'];
                        break;
                    case 'URI' :
                        if ($is_author)
                            $yt_video['author_uri'] = $yt_elem['value'];
                        break;
                    default :
                }
            }

            unset($yt_vals);

            return $yt_vidlist;
        }

        public function hd_GetYoutubePage($url) {

            // Try to use curl first
            if (function_exists('curl_init')) {

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $xml = curl_exec($ch);
                curl_close($ch);
            }
            // If not found, try to use file_get_contents (requires php > 4.3.0 and allow_url_fopen)
            else {
                $xml = @file_get_contents($url);
            }

            return $xml;
        }

        public function admin_redirect($url) {//admin redirection url function starts
            echo "<script>window.open('" . $url . "','_top',false)</script>";
        }

//admin redirection url function ends

        public function video_data() {//getting video data function starts
            $orderBy = array('id', 'title', 'author','category', 'fea', 'publish', 'date','ordering');
            $order='';
            if (isset($this->_orderBy) && in_array($this->_orderBy, $orderBy)) {
                $order = $this->_orderBy;
            }

            switch ($order) {
                case 'id':
                    $order = 'vid';
                    break;

                case 'title':
                    $order = 'name';
                    break;

                case 'author':
                    $order = 'u.display_name';
                    break;
                case 'category':
                    $order = 'pl.playlist_name';
                    break;

                case 'fea':
                    $order = 'featured';
                    break;

                case 'date':
                    $order = 'post_date';
                    break;

                case 'publish':
                    $order = 'publish';
                    break;

               case 'ordering':
                    $order = 'ordering';
                    break;

                default:
                    $order = 'ordering';
                    $this->_orderDirection = 'asc';
            }
            return $this->get_videodata($this->_videosearchQuery, $this->_searchBtn, $order, $this->_orderDirection);
        }

//getting video data function ends

        function convertTime($sec) {
            $hms = $padHours = "";
            $hours = intval(intval($sec) / 3600);
            $hms .= ( $padHours) ? str_pad($hours, 2, "0", STR_PAD_LEFT) . ":" : $hours . ":";
            if ($hms == '0:'

                )$hms = '';
            $minutes = intval(($sec / 60) % 60);
            $hms .= str_pad($minutes, 1, "0", STR_PAD_LEFT) . ":";
            $seconds = intval($sec % 60);
            $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);
            // done!
            return $hms;
        }

        public function get_message() {//displaying database message function starts
            if (isset($this->_update) && $this->_update == '1') {
                $this->_msg = 'Video Updated Successfully ...';
            } else if ($this->_update == '0') {
                $this->_msg = 'Video Not Updated  Successfully ...';
            }

            if (isset($this->_add) && $this->_add == '1') {
                $this->_msg = 'Video Added Successfully ...';
            }

            if (isset($this->_del) && $this->_del == '1') {
                $this->_msg = 'Video Deleted Successfully ...';
            }
            if (isset($this->_status) && $this->_status == '1') {
                $this->_msg = 'Video Published Successfully ...';
            } else if ($this->_status == '0') {
                $this->_msg = 'Video UnPublished Successfully ...';
            }
            if (isset($this->_featured) && $this->_featured == '1') {
                $this->_msg = 'Video set as Featured video Successfully ...';
            } else if ($this->_featured == '0') {
                $this->_msg = 'Video set as UnFeatured video Successfully...';
            }

            return $this->_msg;
        }

//displaying database message function ends

        public function get_delete() {//deleting video data function starts
            $videoApply = filter_input(INPUT_POST, 'videoapply');
            $videoActionup = filter_input(INPUT_POST, 'videoactionup');
            $videoActiondown = filter_input(INPUT_POST, 'videoactiondown');
            $videocheckId = filter_input(INPUT_POST, 'video_id', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);

            if (isset($videoApply)) {//apply button if starts
                if ($videoActionup || $videoActiondown == 'videodelete') {//delete button if starts
                    if (is_array($videocheckId)) {
                        $videoId = implode(",", $videocheckId);
                        $deleteflag = $this->video_delete($videoId);
                        if (!$deleteflag) {
                            $this->admin_redirect("admin.php?page=video&del=0");
                        } else {
                            $this->admin_redirect("admin.php?page=video&del=1");
                        }
                    }
                }//delete button if ends
            }//apply button if ends
        }

//deleting playlist data function ends
    }

    //VideoController ends
}//checks if the VideoController class has been defined start

$videoOBJ = new VideoController();

$videoOBJ->add_newvideo();
$videoId = $videoOBJ->_videoId;
$videoOBJ->get_delete();
$gridVideo = $videoOBJ->video_data();
$videosearchQuery =$videoOBJ->_videosearchQuery;
$searchBtn = $videoOBJ->_searchBtn;
$Video_count = $videoOBJ->video_count($videosearchQuery, $searchBtn);
$videoEdit = $videoOBJ->video_edit($videoId);
$displayMsg = $videoOBJ->get_message();
$searchMsg = $videoOBJ->_videosearchQuery;
$settingsGrid = $videoOBJ->_settingsData;
$adminPage = filter_input(INPUT_GET, 'page');
if ($adminPage == 'video') {//including video form if starts
    require_once(APPTHA_VGALLERY_BASEDIR . DS . 'admin/views/video/video.php');
}//including video form if starts
else if ($adminPage == 'newvideo') {//including newvideo ad form if starts
    require_once(APPTHA_VGALLERY_BASEDIR . DS . 'admin/views/video/addvideo.php');
}//including newvideo ad form if ends