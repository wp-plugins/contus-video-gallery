<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: Video home page view file
Version: 2.1
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/

if (class_exists('ContusVideoView') != true) {

    class ContusVideoView extends ContusVideoController { //CLASS FOR HOME PAGE STARTS

        public $_settingsData;
        public $_videosData;
        public $_swfPath;
        public $_singlevideoData;
        public $_videoDetail;
        public $_vId;

        public function __construct() {//contructor starts
            parent::__construct();
            $this->_settingsData = $this->settings_data();
            $this->_videosData = $this->videos_data();
            $this->_mPageid = $this->More_pageid();
            $this->_feaMore = $this->Video_count();
            $this->_vId = filter_input(INPUT_GET, 'vid');
            $this->_pId = filter_input(INPUT_GET, 'pid');
            $this->_tagname = $this->Tag_detail($this->_vId);
            $this->_pagenum         = filter_input(INPUT_GET, 'pagenum');
            $this->_showF = 5;
            $this->_colCat          = $this->_settingsData->colCat;
            $this->_site_url = get_bloginfo('url');
            $this->_singlevideoData = $this->home_playerdata();
            $this->_featuredvideodata = $this->home_featuredvideodata();

            $this->_bannerswfPath = APPTHA_VGALLERY_BASEURL . 'hdflvplayer' . DS . 'hdplayer_banner.swf';
            $this->_swfPath = APPTHA_VGALLERY_BASEURL . 'hdflvplayer' . DS . 'hdplayer.swf';
            $this->_imagePath = APPTHA_VGALLERY_BASEURL . 'images' . DS;
        }

//contructor ends

        function home_player() { //FUNCTION FOR HOME PAGE STARTS
            $fetchedVideosVideos = $this->_videosData;
            if(!empty($fetchedVideosVideos))
            $fetchedVideos = $fetchedVideosVideos[0];
            $settingsData = $this->_settingsData;
            $videoUrl=$videoId=$thumb_image='';
            $homeplayerData = $this->_singlevideoData;
            if(!empty($homeplayerData)){
            $videoUrl = $homeplayerData->file;
            $videoId = $homeplayerData->vid;
            $thumb_image = $homeplayerData->image;
            }
            $moduleName = "playerModule";
            $div = '<div align="center">'; //video player starts
            $div .= '<style type="text/css"> .video-block {padding-left:' . $settingsData->gutterspace . 'px} </style>';
            if (!empty($this->_vId)) {
                $baseref = '&amp;vid=' . $this->_vId;
            }else {
                $baseref = '&amp;featured=true';
            }
            $div .='<div name="mediaspace" id="mediaspace" class="mediaspace" style="color: #666;">';
            $div .='<h3 id="video_title" style="width:' . $settingsData->width . ';"  class="more_title" align="left"></h3>';
            //FLASH PLAYER STARTS HERE
            $div .='<div id="flashplayer">';
            if($settingsData->default_player==1){
                $swf=$this->_bannerswfPath;
            }else{
                $swf=$this->_swfPath;
            }
            //IF VIDEO IS Vimeo
            if ((preg_match('/vimeo/', $videoUrl)) && ($videoUrl != '')) {
                $vresult = explode("/", $videoUrl);
                $div .='<iframe  type="text/html" width="' . $settingsData->width . '" height="' . $settingsData->height . '"  src="http://player.vimeo.com/video/' . $vresult[3] . '" frameborder="0"></iframe>';
            } else {
                $div .= '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"  width="' . $settingsData->width . '" height="' . $settingsData->height . '" >';
                $div .= '<param name="movie" value="' . $swf . '" />';
                $div .= '<param name="flashvars" value="baserefW=' . APPTHA_VGALLERY_BASEURL . '&vid=' . $videoId . '&Preview=' . $thumb_image . '&mid=' . $moduleName. '" />';
                $div .= '<param name="allowFullScreen" value="true" />';
                $div .= '<param name="wmode" value="transparent" />';
                $div .= '<param name="allowscriptaccess" value="always" />';
                $div .= '<embed src="' . $swf . '"  flashvars="baserefW=' . APPTHA_VGALLERY_BASEURL . $baseref . '&showPlaylist=true&Preview=' . $thumb_image .'&mid=' . $moduleName. '   "';
                $div .= ' width="' . $settingsData->width . '" height="' . $settingsData->height . '"   allowFullScreen="true" allowScriptAccess="always" type="application/x-shockwave-flash" wmode="transparent"></embed>';
                $div .='</object>';
            }
            $div .='</div>';
            //FLASH PLAYER ENDS AND HTML5 PLAYER STARTS HERE

            $div .='<div id="player" style="display:none;">';
            if ((preg_match('/vimeo/', $videoUrl)) && ($videoUrl != '')) { //IF VIDEO IS YOUTUBE
                $vresult = explode("/", $videoUrl);
                $div .='<iframe  type="text/html" width="' . $settingsData->width . '" height="' . $settingsData->height . '"  src="http://player.vimeo.com/video/' . $vresult[3] . '" frameborder="0"></iframe>';
            } elseif (strpos($videoUrl, 'youtube') > 0) {
                $imgstr = explode("v=", $videoUrl);
                $imgval = explode("&", $imgstr[1]);
                $videoId1 = $imgval[0];
                $div .='<iframe  type="text/html" width="' . $settingsData->width . '" height="' . $settingsData->height . '"  src="http://www.youtube.com/embed/' . $videoId1 . '" frameborder="0"></iframe>';
            } else { //IF VIDEO IS UPLOAD OR DIRECT PATH
                $div .='<video id="video" poster="' . $thumb_image . '"   src="' . $videoUrl . '" width="' . $settingsData->width . '" height="' . $settingsData->height . '" autobuffer controls onerror="failed(event)">
                ' . __('Html5 Not support This video Format.', 'video_gallery') . '
                </video>';
            }
            $div .='</div>';
            //SCRIPT FOR CHECKING PLATFORM
            $div .=' <script>
            txt =  navigator.platform ;
            if(txt =="iPod"|| txt =="iPad" || txt == "iPhone" || txt == "Linux armv7I")
            {
                document.getElementById("player").style.display = "block";
                document.getElementById("flashplayer").style.display = "none";
            }
            else
            {
                document.getElementById("player").style.display = "none";
                document.getElementById("flashplayer").style.display = "block";
            }
            </script>';
            //ERROR MESSAGE FOR VIDEO NOT SUPPORTED TO PLAYER ENDS
            $div .= '<script type="text/javascript">
            function failed(e)
            {
                txt =  navigator.platform ;
                if(txt =="iPod"|| txt =="iPad" || txt == "iPhone" || txt == "Linux armv7I")
                {
                    alert("' . __('Player doesnot support this video.', 'video_gallery') . '");
                }
            }
            </script>';
            //HTML5 ENDS
//            $div .='</div>';
            $div .='<div id="video_tag" class="views"></div>';
            $div .='</div>';
            $div .='</div>';

            return $div;
        }

//FUNCTION FOR HOME PAGE PLAYER ENDS

        function home_thumb($type) {// HOME PAGE FEATURED VIDEOS STARTS
            if (function_exists('homeVideo') != true) {
//                echo "<pre>";print_r($this->_settingsData);exit;
                $TypeSet = '';
                switch ($type) {
                    case 'pop'://GETTING POPULAR VIDEOS STARTS
                        $TypeSet = $this->_settingsData->popular; //Popular Videos
                        $rowF = $this->_settingsData->rowsPop; //row field of popular videos
                        $colF = $this->_settingsData->colPop; //column field of popular videos
                        $dataLimit = $rowF * $colF;
                        $thumImageorder = 'w.hitcount DESC';
                        $where='';
                        $TypeOFvideos = $this->home_thumbdata($thumImageorder,$where, $dataLimit);
//                        echo "<pre>";print_r($TypeOFvideos);exit;
                        $typename = __('Popular', 'video_gallery');
                        $type_name='popular';
                        $morePage = 'pop';
                        break; //GETTING POPULAR VIDEOS ENDS

                    case 'rec':
                        $TypeSet = $this->_settingsData->recent;
                        $rowF = $this->_settingsData->rowsRec;
                        $colF = $this->_settingsData->colRec;
                        $dataLimit = $rowF * $colF;
                        $thumImageorder = 'w.vid DESC';
                        $where='';
                        $TypeOFvideos = $this->home_thumbdata($thumImageorder,$where, $dataLimit);
                        $typename = __('Recent', 'video_gallery');
                        $type_name='recent';
                        $morePage = 'rec';
                        break;

                    case 'fea':
                        $thumImageorder = 'w.ordering ASC';
                        $where = 'AND w.featured=1';
                        $TypeSet = $this->_settingsData->feature;
                        $rowF = $this->_settingsData->rowsFea;
                        $colF = $this->_settingsData->colFea;
                        $dataLimit = $rowF * $colF;
                        $TypeOFvideos = $this->home_thumbdata($thumImageorder,$where, $dataLimit);
                        $typename = __('Featured', 'video_gallery');
                        $type_name='featured';
                        $morePage = 'fea';
                        break;

                    case 'cat':
                        if($this->_settingsData->homecategory==1){
                        $rowF           = $this->_settingsData->rowCat;
                        $colF           = $this->_settingsData->colCat;
                        $category_page           = $this->_settingsData->category_page;
                        $dataLimit      = $rowF * $colF;
                        $TypeOFvideos   = $this->home_categoriesthumbdata($this->_pagenum, $category_page);
                        $CountOFVideos  = $this->Countof_Videocategories();
                        $typename       = __('Video Categories', 'video_gallery');
                        return $this->categoryList($CountOFVideos, $TypeOFvideos, $this->_pagenum, $dataLimit, $category_page);
                }
                        break;
                }

                $class = $div = '';
?>

<?php
                $image_path = str_replace('plugins/contus-video-gallery/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL);
                if ($TypeSet) { //CHECKING FAETURED VIDEOS ENABLE STARTS
                    $div = '<div class="video_wrapper" id="'.$type_name.'_video">';
                    $div .= '<style type="text/css"> .video-block {padding-left:' . $this->_settingsData->gutterspace . 'px} </style>';
//                    echo "<pre>";print_r($TypeOFvideos);
                    if (!empty($TypeOFvideos)) {
                        $div .='<h2 class="video_header">' . $typename . ' '.__('Videos', 'video_gallery').'</h2>';
                        $j = 0;
                        $clearwidth = 0;
                        $clear = '';
                        foreach ($TypeOFvideos as $video) {
                            $duration[$j] = $video->duration; //VIDEO DURATION
                            $imageFea[$j] = $video->image; //VIDEO IMAGE
                            $file_type = $video->file_type; // Video Type
                            $playlist_id[$j] = $video->pid; //VIDEO CATEGORY ID
                            $fetched[$j] = $video->playlist_name; //CATEOGORY NAME
                            $guid[$j] = $video->guid; //guid
                            if ($imageFea[$j] == '') {  //If there is no thumb image for video
                                $imageFea[$j] = $this->_imagePath . 'nothumbimage.jpg';
                            } else {
                                if ($file_type == 2) {          //For uploaded image
                                    $imageFea[$j] = $image_path . $imageFea[$j];
                                }
                            }
                            $vidF[$j] = $video->vid; //VIDEO ID
                            $nameF[$j] = $video->name; //VIDEI NAME
                            $hitcount[$j] = $video->hitcount; //VIDEO HITCOUNT
                            $j++;
                        }

                        $div .= '<div class="video_thumb_content">';
                        $div .= '<ul class="video-block-container">';
                        for ($j = 0; $j < count($TypeOFvideos); $j++) {
                            $class = '<div class="clear"></div>';
                            if (($j % $colF) == 0 && $j!=0) {//COLUMN COUNT
                                $div .= '</ul><ul class="video-block-container">';
                            }
                                $div .= '<li class="video-block">';
                                $div .='<div  class="video-thumbimg"><a href="' . $guid[$j] . '">
                        <img src="' . $imageFea[$j] . '" alt="' . $nameF[$j] . '" class="imgHome" title="' . $nameF[$j] . '" /></a>';
                                if ($duration[$j] != 0.00) {
                                    $div .= '<span class="video_duration">'.$duration[$j].'</span>';
                                }
                                $div .= '</div>';
                                $div .='<h5><a href="' . $guid[$j] . '" class="videoHname">';
                                if (strlen($nameF[$j]) > 30) {
                                    $div .=substr($nameF[$j], 0, 30) . '';
                                } else {
                                    $div .=$nameF[$j];
                                }
                                $div .='</a></h5>';
                                $div .='<div class="vid_info">';

                                $div .= '<span class="video_views">'. $hitcount[$j] . ' '.__('Views', 'video_gallery');
                                $div .= '</span>';

//                                echo $fetched[$j];exit;
                                if ($fetched[$j] != '') {
                                    $div .=' <a class="playlistName" href="' . $this->_site_url . '/?page_id=' . $this->_mPageid . '&playid=' . $playlist_id[$j] . '">' . $fetched[$j] . '</a>';
                                }
                                $div .= '</div>';
                                $div .='</li>';
                        }//FOR EACH ENDS
                        $div .='</ul>';
                        $div.='</div>';
                        $div .='<div class="clear"></div>';


                        if (($this->_showF < $this->_feaMore)) {//PAGINATION STARTS
                            $div .='<h5 class="more_title" ><a class="video-more" href="' . $this->_site_url . '/?page_id=' . $this->_mPageid . '&more=' . $morePage . '" class="more">'.__('More videos', 'video_gallery').' &#187;</a></h5>';
                        } else if (($this->_showF == $this->_feaMore)) {
                            $div .='<div style="float:right"> </div>';
                        }//PAGINATION ENDS
                    }
                    else
                        $div .=__('No', 'video_gallery').' ' . $typename . ' '.__('Videos', 'video_gallery');
                    $div .='</div>';
                } //CHECKING FAETURED VIDEOS ENABLE ENDS



                return $div;
            }
        }


        function categoryList($CountOFVideos, $TypeOFvideos, $pagenum, $dataLimit, $category_page) {
            global $wpdb;
            $div        = '';
            $pagenum    = isset($pagenum) ? absint($pagenum) : 1; // Calculating page number
            $start      = ( $pagenum - 1 ) * $dataLimit;     // Video starting from
?>

<?php
            $div .= '<style> .video-block { padding-left:' . $this->_settingsData->gutterspace . 'px } </style>';
            foreach ($TypeOFvideos as $catList) {
// Fetch videos for every category
               $sql            = "SELECT s.guid,w.* FROM " . $wpdb->prefix . "hdflvvideoshare as w
                    INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play as m ON m.media_id = w.vid
                    INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist as p on m.playlist_id = p.pid
                     INNER JOIN " . $this->_wpdb->prefix . "posts s ON s.ID=w.slug
WHERE w.publish='1' and p.is_publish='1' and m.playlist_id=" . intval($catList->pid) . " GROUP BY w.vid LIMIT ".$dataLimit;
                $playLists      = $wpdb->get_results($sql);
                $playlistCount  = count($playLists);
//echo "<pre>";print_r($playLists);
                $div .='<div> <h4 class="clear more_title">' . $catList->playlist_name . '</h4></div>';
                if (!empty($playlistCount)) {
                    $inc        = 1;
                    $image_path = str_replace('plugins/contus-video-gallery/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL);
                    $div .= '<ul class="video-block-container">';
                    foreach ($playLists as $playList) {

                        $duration   = $playList->duration;
                        $imageFea   = $playList->image; //VIDEO IMAGE
                        $file_type  = $playList->file_type; // Video Type
                        $guid = $playList->guid; //guid
                        if ($imageFea == '') {  //If there is no thumb image for video
                            $imageFea = $this->_imagePath . 'nothumbimage.jpg';
                        } else {
                            if ($file_type == 2) {          //For uploaded image
                                $imageFea = $image_path . $imageFea;
                            }
                        }
                        if (strlen($playList->name) > 30) {
                            $playListName = substr($playList->name, 0, 30) . "";
                        } else {
                            $playListName = $playList->name;
                        }

                        $div .='<li class="video-block"><div class="video-thumbimg"><a href="' . $guid . '"><img src="' . $imageFea . '" alt="" class="imgHome" title=""></a>';
                        if ($duration != 0.00) {
                            $div .='<span class="video_duration">' . $duration . '</span>';
                        }
                        $div .='</div><h5><a href="' . $guid . '" class="videoHname">' . $playListName . '</a></h5><div class="vid_info">';

                            $div .='<span class="video_views">' . $playList->hitcount . ' '.__('Views', 'video_gallery') . '</span>';

                        $div .='</div></li>';

if (($inc % $this->_colCat ) == 0 && $inc!=0) {//COLUMN COUNT
                                $div .= '</ul><div class="clear"></div><ul class="video-block-container">';
                            }
                        $inc++;
                    }
                    $div .='</ul>';
                    if (($playlistCount > 8)) {

                        $div .='<a class="video-more" href="' . $this->_site_url . '/?page_id=' .  $this->_mPageid . '&playid=' . $catList->pid . '">'.__('More Videos', 'video_gallery').'</a>';
                    } else {
                        $div .='<div align="right"> </div>';
                    }
                } else { // If there is no video for category
                    $div .='<div>'.__('No Videos For this Category', 'video_gallery').'</div>';
                }
            }

            $div .='<div class="clear"></div>';

            //PAGINATION STARTS
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
                $div .='<div class="tablenav"><div class="tablenav-pages" >' . $page_links . '</div></div>';
            }

            //PAGINATION ENDS
            return $div;
        }

        ////CATEGORY FUNCTION ENDS
    }

    //class over
}
else {
    echo 'class contusVideo already exists';
}
?>