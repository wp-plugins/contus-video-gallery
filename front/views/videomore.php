<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: Video more page view file.
Version: 2.5
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/

if (class_exists('ContusMoreView') != true) {

    class ContusMoreView extends ContusMoreController { //CLASS FOR HOME PAGE STARTS

        public $_settingsData;
        public $_vId;
        public $_playid;
        public $_pagenum;

        public function __construct() {                                             ## contructor starts
            parent::__construct();
            global $wp_query;
            $video_search = '';
            $this->_settingsData    = $this->settings_data();                       ## Get player settings
            $this->_mPageid         = $this->More_pageid();                         ## Get more page id
            $this->_feaMore         = $this->Video_count();                         ## Get featured videos count
            $this->_vId             = filter_input(INPUT_GET, 'vid');               ## Get vid from URL
            $this->_pagenum         = filter_input(INPUT_GET, 'pagenum');           ## Get current page number
            $this->_playid          = &$wp_query->query_vars["playid"];   
            $this->_userid          = &$wp_query->query_vars["userid"];   
            
            ## Get pid from URL
            $this->_viewslang = __('Views', 'video_gallery');
            $this->_viewlang = __('View', 'video_gallery');
            ## Get search keyword
            $searchVal = str_replace(" ", "%20",__('Video Search ...', 'video_gallery'));
            if(isset($wp_query->query_vars['video_search']) && $wp_query->query_vars['video_search'] !== $searchVal){
            $video_search    = $wp_query->query_vars['video_search'];
            }
            $this->_video_search = $video_search;

            $this->_showF           = 5;
            $this->_colF            = $this->_settingsData->colMore;                ## get row of more page
            $this->_colCat          = $this->_settingsData->colCat;                 ## get column of more page
            $this->_rowCat          = $this->_settingsData->rowCat;                 ## get row of category videos
            $this->_perCat          = $this->_colCat * $this->_rowCat;              ## get column of category videos
            $dir                    = dirname(plugin_basename(__FILE__));
            $dirExp                 = explode('/', $dir);
            $this->_folder          = $dirExp[0];                                   ## Get plugin folder name
            $this->_site_url        = get_bloginfo('url');                          ## Get base url
            $this->_imagePath       = APPTHA_VGALLERY_BASEURL . 'images' . DS;      ## Declare image path
        } //contructor ends

        function video_more_pages($type) {                                          ## More PAGE FEATURED VIDEOS STARTS
            if (function_exists('homeVideo') != true) {
            $type_name='';
                switch ($type) {
                    case 'popular':                                                     ## GETTING POPULAR VIDEOS STARTS
                        $rowF           = $this->_settingsData->rowMore;            ## row field of popular videos
                        $colF           = $this->_settingsData->colMore;            ## column field of popular videos
                        $dataLimit      = $rowF * $colF;
                        $where          = '';
                        $thumImageorder = 'w.hitcount DESC';
                        $TypeOFvideos   = $this->home_thumbdata($thumImageorder, $where,$this->_pagenum, $dataLimit);
                        $CountOFVideos  = $this->Countof_Videos('','',$thumImageorder,$where);
                        $typename       = __('Popular', 'video_gallery');
                        $type_name      = 'popular';
                        $morePage       = '&more=pop';
                        break;                                                      ## GETTING POPULAR VIDEOS ENDS

                    case 'recent':
                        $rowF           = $this->_settingsData->rowMore;
                        $where          = '';
                        $colF           = $this->_settingsData->colMore;
                        $dataLimit      = $rowF * $colF;
                        $thumImageorder = 'w.vid DESC';
                        $TypeOFvideos   = $this->home_thumbdata($thumImageorder, $where,$this->_pagenum, $dataLimit);
                        $CountOFVideos  = $this->Countof_Videos('','',$thumImageorder,$where);
                        $typename       = __('Recent', 'video_gallery');
                        $type_name      = 'recent';
                        $morePage       = '&more=rec';
                        break;

                    case 'featured':
                        $thumImageorder = 'w.ordering ASC';
                        $where          = 'AND w.featured=1';
                        $rowF           = $this->_settingsData->rowMore;
                        $colF           = $this->_settingsData->colMore;
                        $dataLimit      = $rowF * $colF;
                        $TypeOFvideos   = $this->home_thumbdata($thumImageorder, $where,$this->_pagenum, $dataLimit);
                        $CountOFVideos  = $this->Countof_Videos('','',$thumImageorder,$where);
                        $typename       = __('Featured', 'video_gallery');
                        $type_name      = 'featured';
                        $morePage       = '&more=fea';
                        break;
                    case 'cat':
                        $thumImageorder = $this->_playid;
                        $where          = '';
                        $rowF           = $this->_settingsData->rowCat;
                        $colF           = $this->_settingsData->colCat;
                        $dataLimit      = $rowF * $colF;
                        $TypeOFvideos   = $this->home_catthumbdata($thumImageorder, $this->_pagenum, $dataLimit);
                        $CountOFVideos  = $this->Countof_Videos($this->_playid,'',$thumImageorder,$where);
                        $typename       = __('Category', 'video_gallery');
                        $morePage       = '&playid=' . $thumImageorder;
                        break;
                    case 'user':
                        $thumImageorder = $this->_userid;
                        $where          = '';
                        $rowF           = $this->_settingsData->rowCat;
                        $colF           = $this->_settingsData->colCat;
                        $dataLimit      = $rowF * $colF;
                        $TypeOFvideos   = $this->home_userthumbdata($thumImageorder, $this->_pagenum, $dataLimit);
                        $CountOFVideos  = $this->Countof_Videos('',$this->_userid,$thumImageorder,$where);
                        $typename       = __('User', 'video_gallery');
                        $morePage       = '&userid=' . $thumImageorder;
                        break;
                    case 'search':
                        $video_search   = str_replace("%20", " ", $this->_video_search);
                        $searchname     = explode(" ", $video_search);
                        $likequery      = '';
                        for ($i = 0; $i < count($searchname); $i++) {
                            $likequery.="( t4.tags_name LIKE '%" . $searchname[$i] . "%' || t1.description LIKE '%" . $searchname[$i] . "%' || t1.name LIKE '%" . $searchname[$i] . "%')";
                            if (($i + 1) != count($searchname)) {
                                $likequery.=" OR ";
                            }
                        }
                        $thumImageorder = $likequery;
                        $rowF           = $this->_settingsData->rowMore;
                        $colF           = $this->_settingsData->colMore;
                        $dataLimit      = $rowF * $colF;
                        $TypeOFvideos   = $this->home_searchthumbdata($thumImageorder,$this->_pagenum, $dataLimit);
                        $CountOFVideos  = $this->Countof_Videosearch($thumImageorder);
                        return $this->searchList($video_search,$CountOFVideos, $TypeOFvideos, $this->_pagenum, $dataLimit);
                        break;
                    case 'categories':
                    default:
                        $rowF           = $this->_settingsData->rowCat;
                        $colF           = $this->_settingsData->colCat;
                        $dataLimit      = $rowF * $colF;
                        $TypeOFvideos   = $this->home_categoriesthumbdata($this->_pagenum, $dataLimit);
                        $CountOFVideos  = $this->Countof_Videocategories();
                        $typename       = __('Video Categories', 'video_gallery');
                        return $this->categoryList($CountOFVideos, $TypeOFvideos, $this->_pagenum, $dataLimit);
                        break;
                    
                }

                $class = $div = '';
                $ratearray = array("nopos1", "onepos1", "twopos1", "threepos1", "fourpos1", "fivepos1");
?>

<?php
                $pagenum            = isset($this->_pagenum) ? absint($this->_pagenum) : 1;
                $div                = '<div class="video_wrapper" id="'. $type_name.'_video">';
                $div               .= '<style type="text/css"> .video-block {  margin-left:' . $this->_settingsData->gutterspace . 'px !important; } </style>';
                    if($typename=='Category'){
                        $playlist_name      = get_playlist_name(intval($this->_playid));
                    $div            .='<h2 >'.$playlist_name.' </h2>';
                    } else if($typename=='User'){
                        $user_name      = get_user_name(intval($this->_userid));
                        $div            .='<h2 >'.$user_name.' </h2>';
                    } else {
                    $div            .='<h2 >' . $typename . ' '.__('Videos', 'video_gallery').' </h2>';
                    }
                if (!empty($TypeOFvideos)) {
                    $j                  = 0;
                    $clearwidth         = 0;
                    $clear              = $fetched[$j] = '';
                    $image_path         = str_replace('plugins/'.$this->_folder.'/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL);
                    foreach ($TypeOFvideos as $video) {
                        $duration[$j]   = $video->duration;         ## VIDEO DURATION
                        $imageFea[$j]   = $video->image;            ## VIDEO IMAGE
                        $file_type      = $video->file_type;        ## Video Type
                        $guid[$j]       = get_video_permalink($video->slug);             ## guid
                        if ($imageFea[$j] == '') {                  ## If there is no thumb image for video
                            $imageFea[$j] = $this->_imagePath . 'nothumbimage.jpg';
                        } else {
                            if ($file_type == 2 || $file_type == 5 ) {          //For uploaded image
                                $imageFea[$j] = $image_path . $imageFea[$j];
                            }
                        }
                        $vidF[$j]        = $video->vid;              ## VIDEO ID
                        $nameF[$j]       = $video->name;             ## VIDEI NAME
                        $hitcount[$j]    = $video->hitcount;         ## VIDEO HITCOUNT
                        $ratecount[$j]   = $video->ratecount;        ## VIDEO RATECOUNT
                        $rate[$j]        = $video->rate;             ## VIDEO RATE
                        if (!empty($this->_playid)) {
                            $fetched[$j] = $video->playlist_name;
                            $fetched_pslug[$j] = $video->playlist_slugname;
                            $playlist_id = $this->_playid;
                        } else {
                            $getPlaylist     = $this->_wpdb->get_row("SELECT playlist_id FROM " . $this->_wpdb->prefix . "hdflvvideoshare_med2play WHERE media_id='".intval($vidF[$j])."'");
                            if (isset($getPlaylist->playlist_id)) {
                                $playlist_id = $getPlaylist->playlist_id;       ## VIDEO CATEGORY ID
                                $fetPlay[$j] = $this->_wpdb->get_row("SELECT playlist_name,playlist_slugname FROM " . $this->_wpdb->prefix . "hdflvvideoshare_playlist WHERE pid='".intval($playlist_id)."'");
                                $fetched[$j] = $fetPlay[$j]->playlist_name;     ## CATEOGORY NAME
                                $fetched_pslug[$j] = $fetPlay[$j]->playlist_slugname;     ## CATEOGORY NAME
                            }
                        }
                        $j++;
                    }
                    $div .= '<div>';
                    $div .= '<ul class="video-block-container">';
                    for ($j = 0; $j < count($TypeOFvideos); $j++) {
                        if (strlen($nameF[$j]) > 30) { ## Displaying Video Title
                                $videoname = substr($nameF[$j], 0, 30) . '..';
                            }
                            else {
                                $videoname = $nameF[$j];
                            }
                        if (($j % $colF) == 0 && $j!=0) { ## COLUMN COUNT
                                $div        .= '</ul><div class="clear"></div><ul class="video-block-container">';
                            }
                            $div            .= '<li class="video-block">';
                            $div            .= '<div  class="video-thumbimg"><a href="' . $guid[$j] . '"><img src="' . $imageFea[$j] . '" alt="' . $nameF[$j] . '" class="imgHome" title="' . $nameF[$j] . '" /></a>';
                            if ($duration[$j] != 0.00) {
                                $div        .= '<span class="video_duration">'.$duration[$j] . '</span>';
                            }
                            $div            .= '</div>';
                            $div            .= '<div class="vid_info"><a href="' . $guid[$j] . '" class="videoHname"><span>';
                            $div            .= $videoname;
                            $div            .= '</span></a>';
                            if (!empty($fetched[$j])) {
                                $playlist_url = get_playlist_permalink($this->_mPageid,$playlist_id,$fetched_pslug[$j]);
                                $div        .= '<a  class="playlistName" href="' . $playlist_url . '"><span>' . $fetched[$j] . '</span></a>';
                            }
                            ## Rating starts here
                            if ($this->_settingsData->ratingscontrol == 1) {
                                if (isset($ratecount[$j]) && $ratecount[$j] != 0) {
                                    $ratestar    = round($rate[$j] / $ratecount[$j]);
                                } else {
                                    $ratestar    = 0;
                                }
                                $div             .= '<span class="ratethis1 '.$ratearray[$ratestar].'"></span>';
                            } 
                            ## Rating ends and views starts here
                            if ($this->_settingsData->view_visible == 1) {
                            $div            .= '<span class="video_views">';
                                if($hitcount[$j]>1){
                                    $viewlang   = $this->_viewslang;
                                } else {
                                       $viewlang = $this->_viewlang;
                                }
                            $div            .= $hitcount[$j] . '&nbsp;'.$viewlang;
                            $div            .= '</span>';
                            }
                            $div            .= '</div>';
                            $div            .= '</li>';
                        ## ELSE ENDS
                    } ## FOR EACH ENDS
                    $div                    .= '</ul>';
                    $div                    .= '</div>';
                    $div                    .= '<div class="clear"></div>';
                }
                else{
                 if($typename=='Category'){
                    $div                    .= __('No', 'video_gallery').'&nbsp;' .__('Videos', 'video_gallery'). '&nbsp;'.__('Under&nbsp;this&nbsp;Category', 'video_gallery');
                    } else {
                    $div                    .= __('No', 'video_gallery').'&nbsp;' . $typename . '&nbsp;'.__('Videos', 'video_gallery');
                    }
                }
                $div                        .= '</div>';

                ## PAGINATION STARTS
                $total          = $CountOFVideos;
                $num_of_pages   = ceil($total / $dataLimit);
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
                ## PAGINATION ENDS
                return $div;
            }
        }

        function categoryList($CountOFVideos, $TypeOFvideos, $pagenum, $dataLimit) {

            global $wpdb;
            $div        = '';
            $pagenum    = isset($pagenum) ? absint($pagenum) : 1;       ## Calculating page number
            $start      = ( $pagenum - 1 ) * $dataLimit;                ## Video starting from
            $ratearray = array("nopos1", "onepos1", "twopos1", "threepos1", "fourpos1", "fivepos1");
?>

<?php

            $div .= '<style> .video-block { margin-left:' . $this->_settingsData->gutterspace . 'px !important; } </style>';
            foreach ($TypeOFvideos as $catList) {
            ## Fetch videos for every category
               $sql            = "SELECT s.guid,w.* FROM " . $wpdb->prefix . "hdflvvideoshare AS w
                                INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play AS m ON m.media_id = w.vid
                                INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_playlist AS p on m.playlist_id = p.pid
                                INNER JOIN " . $this->_wpdb->prefix . "posts s ON s.ID=w.slug
                                WHERE w.publish='1' AND p.is_publish='1' AND m.playlist_id=" . intval($catList->pid) . " GROUP BY w.vid";
                $playLists      = $wpdb->get_results($sql);
                $playlistCount  = count($playLists);

                $div .='<div> <h4 class="clear more_title">' . $catList->playlist_name . '</h4></div>';
                if (!empty($playlistCount)) {
                    $i          = 0;
                    $inc        = 1;
                    $image_path = str_replace('plugins/'.$this->_folder.'/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL);
                    $div        .= '<ul class="video-block-container">';
                    foreach ($playLists as $playList) {

                        $duration   = $playList->duration;
                        $imageFea   = $playList->image;             ## VIDEO IMAGE
                        $file_type  = $playList->file_type;         ## Video Type
                        $guid       = get_video_permalink($playList->slug);              ## guid
                        if ($imageFea == '') {                      ## If there is no thumb image for video
                            $imageFea = $this->_imagePath . 'nothumbimage.jpg';
                        } else {
                            if ($file_type == 2 || $file_type == 5 ) {                  ## For uploaded image
                                $imageFea = $image_path . $imageFea;
                            }
                        }
                        if (strlen($playList->name) > 30) {
                            $playListName = substr($playList->name, 0, 30) . "..";
                        } else {
                            $playListName = $playList->name;
                        }

                        $div        .= '<li class="video-block"><div class="video-thumbimg"><a href="' . $guid . '"><img src="' . $imageFea . '" alt="" class="imgHome" title="" /></a>';
                        if ($duration != 0.00) {
                            $div    .= '<span class="video_duration">' . $duration . '</span>';
                        }
                        $div        .= '</div><div class="vid_info"><h5><a href="' . $guid . '" class="videoHname">' . $playListName . '</a></h5>';
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
                                if($playList->hitcount>1){
                                        $viewlang = $this->_viewslang;
                                } else {
                                           $viewlang = $this->_viewlang;
                                }
                                $div    .= '<span class="video_views">' . $playList->hitcount . '&nbsp;'.$viewlang . '</span>';
                            }
                        $div        .= '</div></li>';

                        if ($i > ($this->_perCat-2)) {
                            break;
                        } else {
                            $i = $i + 1;
                        }
                    if (($inc % $this->_colCat ) == 0 && $inc!=0) {             ## COLUMN COUNT
                                $div .= '</ul><div class="clear"></div><ul class="video-block-container">';
                            }
                        $inc++;
                    }
                    $div            .= '</ul>';
                    if (($playlistCount > 8)) {

                        $div        .= '<a class="video-more" href="' . $this->_site_url . '/?page_id=' .  $this->_mPageid . '&playid=' . $catList->pid . '">'.__('More&nbsp;Videos', 'video_gallery').'</a>';
                    } else {
                        $div        .= '<div align="right"> </div>';
                    }
                } else {                                                        ## If there is no video for category
                    $div            .= '<div>'.__('No&nbsp;Videos&nbsp;for&nbsp;this&nbsp;Category', 'video_gallery').'</div>';
                }
            }

            $div                    .= '<div class="clear"></div>';

            ## PAGINATION STARTS
            $total          = $CountOFVideos;
            $num_of_pages   = ceil($total / $dataLimit);
            $page_links     = paginate_links(array(
                        'base'      => add_query_arg('pagenum', '%#%'),
                        'format'    => '',
                        'prev_text' => __('&laquo;', 'aag'),
                        'next_text' => __('&raquo;', 'aag'),
                        'total'     => $num_of_pages,
                        'current'   => $pagenum
                    ));

            if ($page_links) {
                $div        .= '<div class="tablenav"><div class="tablenav-pages" >' . $page_links . '</div></div>';
            }

            ## PAGINATION ENDS
            return $div;
        }

        function searchList($video_search,$CountOFVideos, $TypeOFvideos, $pagenum, $dataLimit) {

            global $wpdb;
            $div        = '';
            $pagenum    = isset($pagenum) ? absint($pagenum) : 1;   ## Calculating page number
            $start      = ( $pagenum - 1 ) * $dataLimit;            ## Video starting from
            $limit      = $dataLimit;                               ## Video Limit
            $ratearray = array("nopos1", "onepos1", "twopos1", "threepos1", "fourpos1", "fivepos1");
?>


<?php
            $div .='<div class="video_wrapper" id="video_search_result"><h3 class="entry-title">'.__('Search Results', 'video_gallery').' - '.$video_search.'</h3>';
            $div .= '<style> .video-block { margin-left:' . $this->_settingsData->gutterspace . 'px !important; } </style>';

                ## Fetch videos for every category
                if (!empty($TypeOFvideos)) {
                    $i          = 0;
                    $inc        = 0;
                    $image_path = str_replace('plugins/'.$this->_folder.'/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL);
                    $div        .= '<ul class="video-block-container">';

                    foreach ($TypeOFvideos as $playList) {

                        $duration   = $playList->duration;
                        $imageFea   = $playList->image;         ## VIDEO IMAGE
                        $file_type  = $playList->file_type;     ## Video Type
                        $guid       = get_video_permalink($playList->slug);         ## guid
                        if ($imageFea == '') {                  ## If there is no thumb image for video
                            $imageFea = $this->_imagePath . 'nothumbimage.jpg';
                        } else {
                            if ($file_type == 2 || $file_type == 5 ) {              ## For uploaded image
                                $imageFea = $image_path . $imageFea;
                            }
                        }
                        if (strlen($playList->name) > 30) {
                            $playListName = substr($playList->name, 0, 30) . "..";
                        } else {
                            $playListName = $playList->name;
                        }
                    if (($inc % $this->_colF ) == 0 && $inc!=0) { ## COLUMN COUNT
                                $div .= '</ul><div class="clear"></div><ul class="video-block-container">';
                            }
                        $div        .= '<li class="video-block"><div class="video-thumbimg"><a href="' . $guid . '"><img src="' . $imageFea . '" alt="" class="imgHome" title="" /></a>';
                        if ($duration != 0.00) {
                            $div    .= '<span class="video_duration">' . $duration. '</span>';
                        }
                        $div        .= '</div><h5><a href="' . $guid . '" class="videoHname">' . $playListName . '</a></h5><div class="vid_info">';
                        if (!empty($playList->playlist_name)) {
                            $playlist_url = get_playlist_permalink($this->_mPageid,$playList->pid,$playList->playlist_slugname);
                                $div .= '<h6 class="playlistName"><a href="' . $playlist_url . '">' . $playList->playlist_name . '</a></h6>';
                            }
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
                            if($playList->hitcount>1){
                                    $viewlang = $this->_viewslang;
                            } else {
                                       $viewlang = $this->_viewlang;
                            }
                            $div        .= '<span class="video_views">' . $playList->hitcount . '&nbsp;'.$viewlang . '</span>';
                        }
                        $div        .= '</div></li>';

                        $inc++;
                    }
                    $div            .= '</ul>';

                } else { ## If there is no video for category
                    $div            .= '<div>'.__('No&nbsp;Videos&nbsp;Found', 'video_gallery').'</div>';
                }
            $div                    .= '</div>';

            $div                    .= '<div class="clear"></div>';

            ## PAGINATION STARTS
            $total          = $CountOFVideos;
            $num_of_pages   = ceil($total / $dataLimit);
            $video_search   = str_replace(" ", "%20", $video_search);
            $arr_params     = array ( 'pagenum' => '%#%');
            $page_links     = paginate_links(array(
                        'base'      => add_query_arg($arr_params),
                        'format'    => '',
                        'prev_text' => __('&laquo;', 'aag'),
                        'next_text' => __('&raquo;', 'aag'),
                        'total'     => $num_of_pages,
                        'current'   => $pagenum
                    ));

            if ($page_links) {
                $div    .= '<div class="tablenav"><div class="tablenav-pages" >' . $page_links . '</div></div>';
            }

            ## PAGINATION ENDS
            return $div;
        }
        ## CATEGORY FUNCTION ENDS
    }
    ## class over
} else {
    echo 'class contusMore already exists';
}
?>