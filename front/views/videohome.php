<?php
/*
  Name: Wordpress Video Gallery
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: Video home page view file
  Version: 2.5
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */

if (class_exists('ContusVideoView') != true) {

    class ContusVideoView extends ContusVideoController {       ##CLASS FOR HOME PAGE STARTS

        public $_settingsData;
        public $_videosData;
        public $_swfPath;
        public $_singlevideoData;
        public $_videoDetail;
        public $_vId;

        public function __construct() {                                             ##contructor starts
            parent::__construct();
            $this->_settingsData            = $this->settings_data();               ## Get player settings
            $this->_videosData              = $this->videos_data();                 ## Get particular video data
            $this->_mPageid                 = $this->More_pageid();                 ## Get more page id
            $this->_feaMore                 = $this->Video_count();                 ## Get featured videos count
            $this->_vId                     = filter_input(INPUT_GET, 'vid');       ## Get vid from URL
            $this->_pId                     = filter_input(INPUT_GET, 'pid');       ## Get pid from URL
            $this->_tagname                 = $this->Tag_detail($this->_vId);       ## Get tag detail for the current video
            $this->_pagenum                 = filter_input(INPUT_GET, 'pagenum');   ## Get current page number
            $this->_showF                   = 5;
            $this->_colCat                  = $this->_settingsData->colCat;
            $this->_site_url                = get_site_url();
            $this->_singlevideoData         = $this->home_playerdata();
            $this->_featuredvideodata       = $this->home_featuredvideodata();      ## Get featured videos data
            $this->_viewslang               = __('Views', 'video_gallery');
            $this->_viewlang                = __('View', 'video_gallery');
            $dir                            = dirname(plugin_basename(__FILE__));
            $dirExp                         = explode('/', $dir);
            $this->_plugin_name             = $dirExp[0];                           ## Get plugin folder name
            $this->_bannerswfPath           = APPTHA_VGALLERY_BASEURL . 'hdflvplayer' . DS . 'hdplayer_banner.swf';     ## Declare banner swf path
            $this->_swfPath                 = APPTHA_VGALLERY_BASEURL . 'hdflvplayer' . DS . 'hdplayer.swf';            ## Declare swf path
            $this->_imagePath               = APPTHA_VGALLERY_BASEURL . 'images' . DS;                                  ## Declare image path
        }
        ##contructor ends
        function home_player() {                ## FUNCTION FOR HOME PAGE STARTS
            $settingsData                   = $this->_settingsData;
            $videoUrl = $videoId = $thumb_image = $homeplayerData = $file_type = '';
            $mobile = vgallery_detect_mobile();
            if (!empty($this->_featuredvideodata[0])){
                $homeplayerData             = $this->_featuredvideodata[0];
            }
            $image_path                     = str_replace('plugins/'.$this->_plugin_name.'/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL);
            $_imagePath                     = APPTHA_VGALLERY_BASEURL . 'images' . DS;
            if (!empty($homeplayerData)) {
                $videoUrl                   = $homeplayerData->file;                ## Get video URL
                $videoId                    = $homeplayerData->vid;                 ## Get Video ID
                $thumb_image                = $homeplayerData->image;               ## Get thumb image
                $file_type                  = $homeplayerData->file_type;           ## Get file type of a video
                if ($thumb_image == '') {       ## If there is no thumb image for video
                    $thumb_image            = $_imagePath . 'nothumbimage.jpg';
                } else {
                    if ($file_type == 2 || $file_type == 5) {      ## For uploaded image
                        $thumb_image        = $image_path . $thumb_image;
                    }
                }
            }

            $moduleName                     = "playerModule";
            $div                            = '<div>'; ## video player starts
            ## To increase hit count of a video
            $div                            .= '<script type="text/javascript" src="' . APPTHA_VGALLERY_BASEURL . 'js/script.js"></script>';
            $div                            .= '<style type="text/css" scoped> .video-block {margin-left:' . $settingsData->gutterspace . 'px !important; float:left;} </style>';
            $div                            .=' <script>
                                            var baseurl,folder,videoPage;
                                            baseurl = "' . $this->_site_url . '";
                                            folder  = "' . $this->_plugin_name . '";
                                            videoPage = "' . $this->_mPageid . '"; </script>';
            $baseref = '';
            if (!empty($this->_vId)) {
                $baseref                    .= '&amp;vid=' . $this->_vId;
            } else {
                $baseref                    .= '&amp;featured=true';
            }
            $div                            .='<div id="mediaspace" class="mediaspace" style="color: #666;">';
            $div                            .='<h3 id="video_title" style="width:' . $settingsData->width . ';text-align: left;"  class="more_title"></h3>';
            ## FLASH PLAYER STARTS HERE
            $div                            .='<div id="flashplayer">';
            if ($settingsData->default_player == 1) {
                $swf                        = $this->_bannerswfPath;
                $showplaylist               = "&amp;showPlaylist=true";
            } else {
                $swf                        = $this->_swfPath;
                $showplaylist               = '';
            }
            ## Embed player code
            if($homeplayerData->file_type == 5 && !empty($homeplayerData->embedcode)){
            $playerembedcode                 = stripslashes($homeplayerData->embedcode);
            $div                            .=  str_replace('width=', 'width="'.$settingsData->width.'"', $playerembedcode);
            $div                            .= '<script> current_video('.$homeplayerData->vid.',"'.$homeplayerData->name.'"); </script>';
            } else{
                if($mobile === true){
                    if ((preg_match('/vimeo/', $videoUrl)) && ($videoUrl != '')) { ##IF VIDEO IS YOUTUBE
                $vresult                    = explode("/", $videoUrl);
                $div                  .="<iframe  type='text/html' src='http://player.vimeo.com/video/" . $vresult[3] . "' frameborder='0'></iframe>";
            } elseif (strpos($videoUrl, 'youtube') > 0) {
                $imgstr                     = explode("v=", $videoUrl);
                $imgval                     = explode("&", $imgstr[1]);
                $videoId1                   = $imgval[0];
                $div                  .="<iframe  type='text/html' src='http://www.youtube.com/embed/" . $videoId1 . "' frameborder='0'></iframe>";
            } else {    ##IF VIDEO IS UPLOAD OR DIRECT PATH
                if ($file_type == 2) {          ##For uploaded image
                    $videoUrl               = $image_path . $videoUrl;
                } else if ($file_type == 4) {          ##For RTMP videos
                    $streamer               = str_replace("rtmp://", "http://", $homeplayerData->streamer_path);
                    $videoUrl               = $streamer . '_definst_/mp4:' . $videoUrl . '/playlist.m3u8';
                }
                $div                  .="<video id='video' poster='" . $thumb_image . "'   src='" . $videoUrl . "' autobuffer controls onerror='failed(event)'>" . __('Html5 Not support This video Format.', 'video_gallery') . "</video>";
            }
                } else {
                    ## Flash player code
                $div                        .= '<embed id="player" src="' . $swf . '"  flashvars="baserefW=' . APPTHA_VGALLERY_BASEURL . $baseref . $showplaylist . '&amp;mtype=' . $moduleName . '" width="' . $settingsData->width . '" height="' . $settingsData->height . '"   allowFullScreen="true" allowScriptAccess="always" type="application/x-shockwave-flash" wmode="transparent" />';
                }
            }
            $div                            .='</div>';
            ## FLASH PLAYER ENDS AND HTML5 PLAYER STARTS HERE
            $htmlvideo = '';

            $windo                          = '';
            $useragent                      = $_SERVER['HTTP_USER_AGENT'];
            if (strpos($useragent, 'Windows Phone') > 0)
                $windo                      = 'Windows Phone';
            ##SCRIPT FOR CHECKING PLATFORM
            $div                            .= '<script>
                                            var txt =  navigator.platform ;
                                            var windo = "' . $windo . '";
                                            function failed(e)
                                            {
                                            if(txt =="iPod"|| txt =="iPad" || txt == "iPhone" || windo=="Windows Phone" || txt == "Linux armv7l" || txt == "Linux armv6l")
                                            {
                                            alert("' . __('Player doesnot support this video.', 'video_gallery') . '");
                                            }
                                            }
                                            </script>';
            ## ERROR MESSAGE FOR VIDEO NOT SUPPORTED TO PLAYER ENDS
            ## HTML5 ENDS
            $div                        .= '<div id="video_tag" class="views"></div>';
            $div                        .= '</div>';
            $div                        .='</div>';
            return $div;
        }

            ##FUNCTION FOR HOME PAGE PLAYER ENDS

        function home_thumb($type) {    ## HOME PAGE FEATURED VIDEOS STARTS
            if (function_exists('homeVideo') != true) {
                $TypeSet                = '';
                switch ($type) {
                    case 'popular':         ##GETTING POPULAR VIDEOS STARTS
                        $TypeSet        = $this->_settingsData->popular;            ## Popular Videos
                        $rowF           = $this->_settingsData->rowsPop;            ## get row of popular videos
                        $colF           = $this->_settingsData->colPop;             ## get column of popular videos
                        $dataLimit      = $rowF * $colF;
                        $thumImageorder = 'w.hitcount DESC';
                        $where          = '';
                        $TypeOFvideos   = $this->home_thumbdata($thumImageorder, $where, $dataLimit);
                        $CountOFVideos  = $this->countof_home_thumbdata($thumImageorder, $where);
                        $typename       = __('Popular', 'video_gallery');
                        $type_name      = 'popular';
                        $morePage       = 'popular';
                        break;          ##GETTING POPULAR VIDEOS ENDS

                    case 'recent':
                        $TypeSet        = $this->_settingsData->recent;             ## Recent Videos
                        $rowF           = $this->_settingsData->rowsRec;            ## get row of Recent videos
                        $colF           = $this->_settingsData->colRec;             ## get column of Recent videos
                        $dataLimit      = $rowF * $colF;
                        $thumImageorder = 'w.vid DESC';
                        $where          = '';
                        $TypeOFvideos   = $this->home_thumbdata($thumImageorder, $where, $dataLimit);
                        $CountOFVideos  = $this->countof_home_thumbdata($thumImageorder, $where);
                        $typename       = __('Recent', 'video_gallery');
                        $type_name      = 'recent';
                        $morePage       = 'recent';
                        break;

                    case 'featured':
                        $thumImageorder = 'w.ordering ASC';
                        $where          = 'AND w.featured=1';
                        $TypeSet        = $this->_settingsData->feature;            ## feature Videos
                        $rowF           = $this->_settingsData->rowsFea;            ## get row of feature videos
                        $colF           = $this->_settingsData->colFea;             ## get column of feature videos
                        $dataLimit      = $rowF * $colF;
                        $TypeOFvideos   = $this->home_thumbdata($thumImageorder, $where, $dataLimit);
                        $CountOFVideos  = $this->countof_home_thumbdata($thumImageorder, $where);
                        $typename       = __('Featured', 'video_gallery');
                        $type_name      = 'featured';
                        $morePage       = 'featured';
                        break;

                    case 'cat':
                        if ($this->_settingsData->homecategory == 1) {
                            $rowF       = $this->_settingsData->rowCat;             ## category Videos
                            $colF       = $this->_settingsData->colCat;             ## get row of category videos
                            $category_page = $this->_settingsData->category_page;   ## get column of category videos
                            $dataLimit  = $rowF * $colF;
                            $TypeOFvideos = $this->home_categoriesthumbdata($this->_pagenum, $category_page);
                            $CountOFVideos = $this->Countof_Videocategories();
                            $typename   = __('Video Categories', 'video_gallery');
                            return $this->categoryList($CountOFVideos, $TypeOFvideos, $this->_pagenum, $dataLimit, $category_page);
                        }
                        break;
                }

                $class = $div = '';
                $ratearray = array("nopos1", "onepos1", "twopos1", "threepos1", "fourpos1", "fivepos1");
                $image_path             = str_replace('plugins/'.$this->_plugin_name.'/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL);
                if ($TypeSet) {                                             ## CHECKING FAETURED VIDEOS ENABLE STARTS
                    $div                = '<div class="video_wrapper" id="' . $type_name . '_video">';
                    $div               .= '<style type="text/css" scoped> .video-block {margin-left:' . $this->_settingsData->gutterspace . 'px !important;float:left;}  </style>';

                    if (!empty($TypeOFvideos)) {
                        $div           .= '<h2 class="video_header">' . $typename . ' ' . __('Videos', 'video_gallery') . '</h2>';
                        $j              = 0;
                        foreach ($TypeOFvideos as $video) {
                            $duration[$j]       = $video->duration;         ## VIDEO DURATION
                            $imageFea[$j]       = $video->image;            ## VIDEO IMAGE
                            $file_type          = $video->file_type;        ## Video Type
                            $playlist_id[$j]    = $video->pid;              ## VIDEO CATEGORY ID
                            $fetched[$j]        = $video->playlist_name;    ## CATEOGORY NAME
                            $fetched_pslug[$j]  = $video->playlist_slugname;    ## CATEOGORY slug NAME
                            $guid[$j]           = get_video_permalink($video->slug);            ## guid
                            if ($imageFea[$j] == '') {                      ## If there is no thumb image for video
                                $imageFea[$j] = $this->_imagePath . 'nothumbimage.jpg';
                            } else {
                                if ($file_type == 2 || $file_type == 5) {          ##For uploaded image
                                    $imageFea[$j] = $image_path . $imageFea[$j];
                                }
                            }
                            $vidF[$j]           = $video->vid;              ## VIDEO ID
                            $nameF[$j]          = $video->name;             ## VIDEI NAME
                            $hitcount[$j]       = $video->hitcount;         ## VIDEO HITCOUNT
                            $ratecount[$j]      = $video->ratecount;        ## VIDEO RATECOUNT
                            $rate[$j]           = $video->rate;             ## VIDEO RATE
                            $j++;
                        }

                        $div                    .= '<div class="video_thumb_content">';
                        $div                    .= '<ul class="video-block-container">';
                        for ($j = 0; $j < count($TypeOFvideos); $j++) {
                            $class              = '<div class="clear"></div>';
                            if (($j % $colF) == 0 && $j != 0) {##COLUMN COUNT
                                $div            .= '</ul><div class="clear"></div><ul class="video-block-container">';
                            }
                            $div                .= '<li class="video-block">';
                            $div                .='<div  class="video-thumbimg"><a href="' . $guid[$j] . '">
                                                <img src="' . $imageFea[$j] . '" alt="' . $nameF[$j] . '" class="imgHome" title="' . $nameF[$j] . '" /></a>';
                            if ($duration[$j] != 0.00) {
                                $div            .= '<span class="video_duration">' . $duration[$j] . '</span>';
                            }
                            $div                .= '</div>';
                            $div                .= '<div class="vid_info"><a href="' . $guid[$j] . '" class="videoHname"><span>';
                            if (strlen($nameF[$j]) > 30) {
                                $div            .= substr($nameF[$j], 0, 30) . '..';
                            } else {
                                $div            .= $nameF[$j];
                            }
                            $div                .= '</span></a>';
                            $div                .= '';
                            if ($fetched[$j] != '') {
                                $playlist_url = get_playlist_permalink($this->_mPageid,$playlist_id[$j],$fetched_pslug[$j]);
                                $div            .= '<a class="playlistName" href="'.$playlist_url.'"><span>' . $fetched[$j] . '</span></a>';
                            }
                            if ($this->_settingsData->ratingscontrol == 1) {
                                if (isset($ratecount[$j]) && $ratecount[$j] != 0) {
                                    $ratestar    = round($rate[$j] / $ratecount[$j]);
                                } else {
                                    $ratestar    = 0;
                                }
                                $div             .= '<span class="ratethis1 '.$ratearray[$ratestar].'"></span>';
                            }
                            if ($this->_settingsData->view_visible == 1) {
                            if ($hitcount[$j] > 1)
                                $viewlang       = $this->_viewslang;
                            else
                                $viewlang       = $this->_viewlang;
                            $div                .= '<span class="video_views">' . $hitcount[$j] . '&nbsp;' . $viewlang;
                            $div                .= '</span>';
                            }
                            $div                .= '</div>';
                            $div                .= '</li>';
                        }       ##FOR EACH ENDS
                        $div                    .= '</ul>';
                        $div                    .= '</div>';
                        $div                    .= '<div class="clear"></div>';


                        if (($dataLimit < $CountOFVideos)) {        ##PAGINATION STARTS
                            $more_videos_link = get_morepage_permalink($this->_mPageid,$morePage);
                            $div                .= '<span class="more_title" ><a class="video-more" href="' . $more_videos_link .'">' . __('More&nbsp;Videos', 'video_gallery') . '&nbsp;&#187;</a></span>';
                            $div                    .= '<div class="clear"></div>';
                        } else if (($dataLimit == $CountOFVideos)) {
                            $div                .= '<div style="float:right"></div>';
                        }       ##PAGINATION ENDS
                    }
                    else
                        $div                    .=__('No', 'video_gallery') . ' ' . $typename . ' ' . __('Videos', 'video_gallery');
                    $div                        .= '</div>';
                }       ##CHECKING FAETURED VIDEOS ENABLE ENDS
                return $div;
            }
        }

        function categoryList($CountOFVideos, $TypeOFvideos, $pagenum, $dataLimit, $category_page) {
            global $wpdb;
            $div                = '';
            $ratearray = array("nopos1", "onepos1", "twopos1", "threepos1", "fourpos1", "fivepos1");
            $pagenum            = isset($pagenum) ? absint($pagenum) : 1;               ## Calculating page number
            $div                .= '<style scoped> .video-block { margin-left:' . $this->_settingsData->gutterspace . 'px !important;float:left;} </style>';
            foreach ($TypeOFvideos as $catList) {
            ## Fetch videos for every category
                $sql            = "SELECT s.guid,w.* FROM " . $wpdb->prefix . "hdflvvideoshare as w
                                INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play as m ON m.media_id = w.vid
                                INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist as p on m.playlist_id = p.pid
                                INNER JOIN " . $this->_wpdb->prefix . "posts s ON s.ID=w.slug
                                WHERE w.publish='1' and p.is_publish='1' and m.playlist_id=" . intval($catList->pid) . " GROUP BY w.vid LIMIT " . $dataLimit;
                $playLists      = $wpdb->get_results($sql);
                $playlistCount  = count($playLists);

                $div            .='<div> <h4 class="clear more_title">' . $catList->playlist_name . '</h4></div>';
                if (!empty($playlistCount)) {
                    $inc        = 1;
                    $image_path = str_replace('plugins/'.$this->_plugin_name.'/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL);
                    $div        .= '<ul class="video-block-container">';
                    foreach ($playLists as $playList) {

                        $duration   = $playList->duration;
                        $imageFea   = $playList->image;         ## VIDEO IMAGE
                        $file_type  = $playList->file_type;     ## Video Type
                        $guid       = get_video_permalink($playList->slug);          ## guid - url for video detail page
                        if ($imageFea == '') {                  ## If there is no thumb image for video
                            $imageFea = $this->_imagePath . 'nothumbimage.jpg';
                        } else {
                            if ($file_type == 2 || $file_type == 5) {              ##For uploaded image
                                $imageFea = $image_path . $imageFea;
                            }
                        }
                        if (strlen($playList->name) > 30) {
                            $playListName = substr($playList->name, 0, 30) . "..";
                        } else {
                            $playListName = $playList->name;
                        }

                        $div        .= '<li class="video-block"><div class="video-thumbimg"><a href="' . $guid . '"><img src="' . $imageFea . '" alt="" class="imgHome" title=""></a>';
                        if ($duration != 0.00) {
                            $div    .= '<span class="video_duration">' . $duration . '</span>';
                        }
                        $div        .= '</div><div class="vid_info"><a href="' . $guid . '" class="videoHname"><span>' . $playListName . '</span></a>';
                        ## Rating starts here
                        if ($this->_settingsData->ratingscontrol == 1) {
                                if (isset($playList->ratecount) && $playList->ratecount != 0) {
                                    $ratestar    = round($playList->rate / $playList->ratecount);
                                } else {
                                    $ratestar    = 0;
                                }
                                $div             .= '<span class="ratethis1 '.$ratearray[$ratestar].'"></span>';
                            }
                        ## Rating ends and views starts here
                        if ($this->_settingsData->view_visible == 1) {
                        if ($playList->hitcount > 1)
                            $viewlang = $this->_viewslang;
                        else
                            $viewlang = $this->_viewlang;

                        $div         .= '<span class="video_views">' . $playList->hitcount . '&nbsp;' . $viewlang . '</span>';
                        }
                            
                        $div         .= '</div></li>';

                        if (($inc % $this->_colCat ) == 0 && $inc != 0) {##COLUMN COUNT
                            $div     .= '</ul><div class="clear"></div><ul class="video-block-container">';
                        }
                        $inc++;
                    }
                    $div             .= '</ul>';
                    if (($playlistCount > 8)) {
                        $more_playlist_link = get_playlist_permalink($this->_mPageid,$catList->pid,$catList->playlist_slugname);
                        $div         .= '<a class="video-more" href="' . $more_playlist_link .'">' . __('More&nbsp;Videos', 'video_gallery') . '</a>';
                    } else {
                        $div         .= '<div align="right"> </div>';
                    }
                } else {            ## If there is no video for category
                    $div             .='<div>' . __('No Videos for this Category', 'video_gallery') . '</div>';
                }
            }

            $div                     .='<div class="clear"></div>';

            if($category_page!=0){
            ##PAGINATION STARTS
            $total          = $CountOFVideos;
            $num_of_pages   = ceil($total / $category_page);
            $page_links     = paginate_links(array(
                            'base'      => add_query_arg('pagenum', '%#%'),
                            'format'    => '',
                            'prev_text' => __('&laquo;', 'aag'),
                            'next_text' => __('&raquo;', 'aag'),
                            'total'     => $num_of_pages,
                            'current'   => $pagenum
                    ));

            if ($page_links) {
                $div        .='<div class="tablenav"><div class="tablenav-pages" >' . $page_links . '</div></div>';
            }
            ##PAGINATION ENDS
        }
            return $div;
        }
##CATEGORY FUNCTION ENDS
    }
##class over
} else {
    echo 'class contusVideo already exists';
}
?>