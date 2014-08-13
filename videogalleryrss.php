<?php
/*
  Name: Wordpress Video Gallery
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: RSS Feed file for Videos.
  Version: 2.5
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */

require_once( dirname(__FILE__) . '/hdflv-config.php');
global $site_url;

$site_url               = get_site_url();
$dir                    = dirname(plugin_basename(__FILE__));
$dirExp                 = explode('/', $dir);
$dirPage                = $dirExp[0];

$image_path             = str_replace('plugins/'.$dirPage.'/', 'uploads/videogallery/', APPTHA_VGALLERY_BASEURL);
$_imagePath             = APPTHA_VGALLERY_BASEURL . 'images' . DS;     ## declare image path
$siteName               = get_bloginfo('name');
$type                   = filter_input(INPUT_GET, 'type');
$where                  = $tag_name = '';
$dataLimit              = 1000;
$contusOBJ              = new ContusVideoController();

        switch ($type) {
            case 'popular':                                                     ## GETTING POPULAR VIDEOS STARTS
                default:
                $thumImageorder = 'w.hitcount DESC';
                $TypeOFvideos   = $contusOBJ->home_thumbdata($thumImageorder, $where, $dataLimit);
                break;                                                      ## GETTING POPULAR VIDEOS ENDS

            case 'recent':
                $thumImageorder = 'w.vid DESC';
                $TypeOFvideos   = $contusOBJ->home_thumbdata($thumImageorder, $where, $dataLimit);
                break;

            case 'featured':
                $thumImageorder = 'w.ordering ASC';
                $where = 'AND w.featured=1';
                $TypeOFvideos   = $contusOBJ->home_thumbdata($thumImageorder, $where, $dataLimit);
                break;
            case 'category':
                $thumImageorder = intval(filter_input(INPUT_GET, 'playid'));
                $TypeOFvideos   = $contusOBJ->home_catthumbdata($thumImageorder, $dataLimit);
                break;
        }
        
        ob_clean();
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("content-type: text/xml");
        echo '<?xml version="1.0" encoding="utf-8"?>';
        echo '<rss xmlns:content="http://purl.org/rss/1.0/modules/content/" version="2.0">';
        echo '<title>'.$siteName.'</title>';
        echo '<link>'.get_site_url().'</link>';
        if (count($TypeOFvideos) > 0) {
            foreach ($TypeOFvideos as $media) {
                
                $file_type          = $media->file_type;
                $videoUrl           = $media->file;
                $video_title        = $media->name;
                $description        = $media->description;
                if (!empty($media->tags_name)){
                    $tag_name           = $media->tags_name;
                }
                $views              = $media->hitcount;
                $fbPath             = str_replace('&#038;', '&', $media->guid);
                $opimage            = $media->opimage;
                $image              = $media->image;
                $vidoeId            = $media->vid;
                $post_date          = $media->post_date;
                ## Get thumb image detail
                if ($image == '') {
                    $image          = $_imagePath . 'nothumbimage.jpg';
                } else {
                    if ($file_type == 2) {
                        $image      = $image_path . $image;
                    }
                }
                ## Get preview image detail
                if ($opimage == '') {
                    $opimage        = $_imagePath . 'noimage.jpg';
                } else {
                    if ($file_type == 2) {
                        $opimage    = $image_path . $opimage;
                    }
                }
                ## Get video url detail
                if ($videoUrl != '') {

                    if ($file_type == 2) {
                        $videoUrl   = $image_path . $videoUrl;
                    }
                }
    
                echo '<item>';
                echo '<videoId>' . $vidoeId . '</videoId>';
                echo '<videoUrl>' . $videoUrl . '</videoUrl>';
                echo '<thumbImage>' . $image . '</thumbImage>';
                echo '<previewImage>' . $opimage . '</previewImage>';
                echo '<views>' . $views . '</views>';
                echo '<createdDate>' . $post_date . '</createdDate>';
                echo '<title>';
                echo '<![CDATA[' . $video_title . ']]>';
                echo '</title>';
                echo '<description>';
                echo '<![CDATA[' . $description . ']]>';
                echo '</description>';
                echo '<tags>';
                echo '<![CDATA[' . $tag_name . ']]>';
                echo '</tags>';
                echo '<link>' . $fbPath . '</link>';
                echo '<generator>Video_Share_Feed</generator>';
                echo '<docs>http://blogs.law.harvard.edu/tech/rss</docs>';
                echo '</item>';
            }
        }
        echo '</rss>';
        exit();      
?>