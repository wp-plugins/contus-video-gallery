<?php
/*
  Name: Wordpress Video Gallery
  Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: Wordpress video gallery helper file.
  Version: 2.5
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */

## Get playlist ID from slug name
function get_playlist_id($play_name) {
    
    global $wpdb;
    
    $playlist_id = $wpdb->get_var("SELECT pid FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE playlist_slugname='" . $play_name . "' LIMIT 1");
    return $playlist_id;
}

## Get playlist Name from ID
function get_playlist_name($play_id) {
    
    global $wpdb;
    
    $playlist_name = $wpdb->get_var("SELECT playlist_name FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE pid='" . $play_id . "' LIMIT 1");
    return $playlist_name;
}

## Get user ID from name
function get_user_id($user_name) {
    
    global $wpdb;
    
    $user_id = $wpdb->get_var("SELECT ID FROM " . $wpdb->prefix . "users WHERE display_name='" . $user_name . "' LIMIT 1");
    return $user_id;
}

## Get user name from ID
function get_user_name($user_id) {
    
    global $wpdb;
    
    $user_name = $wpdb->get_var("SELECT display_name FROM " . $wpdb->prefix . "users WHERE ID='" . $user_id . "' LIMIT 1");
    return $user_name;
}

## Get video permalink
function get_video_permalink($postid) {

    global $wp_rewrite;
    
    $link = $wp_rewrite->get_page_permastruct();                    ## check whether permalink enabled or not
    $video_details = get_post($postid);                             ## Get post detail from post id

    if (!empty($link)) {        ## Return SEO video URL if permalink enabled
        return get_site_url() . "/" . $video_details->post_type . "/" . $video_details->post_name . "/";
    } else {                    ## Return Non SEO video URL if permalink disabled
        return $video_details->guid;
    }
}

## Get playlist permalink
function get_playlist_permalink($morepageid, $playlist_id, $slug_name) {
    
    global $wp_rewrite;
    
    $link = $wp_rewrite->get_page_permastruct();            ## check whether permalink enabled or not
    if (!empty($link)) {        ## Return SEO playlist URL if permalink enabled
        return get_site_url() . "/category/" . $slug_name . "/";
    } else {                    ## Return Non SEO playlist URL if permalink disabled
        return get_site_url() . '/?page_id=' . $morepageid . '&amp;playid=' . $playlist_id;
    }
}

## Get playlist permalink
function get_user_permalink($morepageid, $userid, $username) {
    
    global $wp_rewrite;
    
    $link = $wp_rewrite->get_page_permastruct();            ## check whether permalink enabled or not
    if (!empty($link)) {        ## Return SEO playlist URL if permalink enabled
        return get_site_url() . "/user/" . $username . "/";
    } else {                    ## Return Non SEO playlist URL if permalink disabled
        return get_site_url() . '/?page_id=' . $morepageid . '&amp;userid=' . $userid;
    }
}

## Get more page permalink
function get_morepage_permalink($morepageid, $morePage) {
    
    global $wp_rewrite;
    
    $link = $wp_rewrite->get_page_permastruct();            ## check whether permalink enabled or not
    if (!empty($link)) {                ## Return SEO more page URL if permalink enabled
        if (isset($morePage)) {
            $type = $morePage;
            switch ($type) {
                case 'popular':
                    $location = get_site_url() . "/popular-videos/";
                    break;
                case 'recent':
                    $location = get_site_url() . "/recent-videos/";
                    break;
                case 'featured':
                    $location = get_site_url() . "/featured-videos/";
                    break;
                case 'categories':
                    $location = get_site_url() . "/all-category-videos/";
                    break;
            }
        }
        return $location;
    } else {                            ## Return Non SEO more page URL if permalink disabled
        return get_site_url() . '/?page_id=' . $morepageid . '&amp;more=' . $morePage;
    }
}

## Detect mobile device
function vgallery_detect_mobile()
{
    $_SERVER['ALL_HTTP']    = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';
    $mobile_browser         = '0';
    $agent                  = strtolower($_SERVER['HTTP_USER_AGENT']);
    if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', $agent)){
        $mobile_browser++;
    }
    if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false)){
        $mobile_browser++;
    }
    if(isset($_SERVER['HTTP_X_WAP_PROFILE'])){
        $mobile_browser++;
    }
    if(isset($_SERVER['HTTP_PROFILE'])){
        $mobile_browser++;
    }
    $mobile_ua              = substr($agent,0,4);
    $mobile_agents          = array(
                            'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
                            'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
                            'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
                            'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
                            'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
                            'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
                            'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
                            'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
                            'wapr','webc','winw','xda','xda-'
                            );

    if(in_array($mobile_ua, $mobile_agents)){
        $mobile_browser++;
    }
    if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false){
        $mobile_browser++;
    }
    ## Pre-final check to reset everything if the user is on Windows
    if(strpos($agent, 'windows') !== false){
        $mobile_browser=0;
    }
    ## But WP7 is also Windows, with a slightly different characteristic
    if(strpos($agent, 'windows phone') !== false){
        $mobile_browser++;
    }
    if($mobile_browser>0){
        return true;
    } else {
        return false;
    }
}
?>