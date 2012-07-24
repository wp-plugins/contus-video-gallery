<?php
/*
Plugin URI: http://www.hdflvplayer.net/wordpress-video-gallery/
Description: Contus Install with the standard system of wordpress.
Edited By: Saranya
wp-content\plugins\contus-hd-flv-player\install.php
Version : 1.0
Date : 21/2/2011
*/
/****************************************************************/
/* Install routine for hdflvplayer
/****************************************************************/
function hdflv_install()
{
    global $wpdb;

    // set tablename
    $table_name 		= $wpdb->prefix . 'hdflvvideoshare';
    $table_playlist		= $wpdb->prefix . 'hdflvvideoshare_playlist';
    $table_med2play		= $wpdb->prefix . 'hdflvvideoshare_med2play';
    $table_settings		= $wpdb->prefix . 'hdflvvideoshare_settings';
    $table_language             = $wpdb->prefix . 'hdflvvideoshare_language';
    $table_vgads                = $wpdb->prefix . 'hdflvvideoshare_vgads';
    $table_tags                 = $wpdb->prefix . 'hdflvvideoshare_tags';
    
    $wfound = false;
    $pfound = false;
    $mfound = false;
    $cfound = false;
    $lfound = false;
    $rollfound = false;
    $tags      = false;
    $found = true;
    $settingsFound = false;

    foreach ($wpdb->get_results("SHOW TABLES;", ARRAY_N) as $row)
    {

        if ($row[0] == $table_name) 	$wfound = true;
        if ($row[0] == $table_playlist) $pfound = true;
        if ($row[0] == $table_med2play) $mfound = true;
        if ($row[0] == $table_comments) $cfound = true;
        if ($row[0] == $table_language) $lfound = true;
        if ($row[0] == $table_vgads) $rollfound = true;
        if ($row[0] == $table_tags) $tags = true;
        if ($row[0] == $table_settings) $settingsFound = true;
    }

    // add charset & collate like wp core
    $charset_collate = '';

    if ( version_compare(mysql_get_server_info(), '4.1.0', '>=') )
    {
        if ( ! empty($wpdb->charset) )
        $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        if ( ! empty($wpdb->collate) )
        $charset_collate .= " COLLATE $wpdb->collate";
    }

    if (!$wfound)
    {

        $sql = "CREATE TABLE ".$table_name." (
                vid MEDIUMINT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    name MEDIUMTEXT NULL,
                    file MEDIUMTEXT NULL,
                    hdfile MEDIUMTEXT NULL,
                    image MEDIUMTEXT NULL,
                    opimage MEDIUMTEXT NULL,
                    download tinyint(1) NOT NULL,
                    link MEDIUMTEXT NULL,
                    featured varchar(25) NOT NULL,
                    hitcount int(25) NOT NULL,
                    post_date datetime NOT NULL,
                    postrollads VARCHAR(25) NOT NULL,
                    prerollads VARCHAR(25) NOT NULL
                    ) $charset_collate;";

        $res = $wpdb->get_results($sql);
    }
 else {
     $sql = "ALTER TABLE ".$table_name." ADD `postrollads` VARCHAR(25) NOT NULL, ADD `prerollads` VARCHAR(25) NOT NULL";
     $res = $wpdb->get_results($sql);

     $sql_down = "ALTER TABLE ".$table_name." ADD `download` tinyint(1) NOT NULL";
     $res_down = $wpdb->get_results($sql_down);

 }

    if (!$pfound)
    {
        $sql = "CREATE TABLE ".$table_playlist." (
                pid BIGINT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                playlist_name VARCHAR(200) NOT NULL ,
                playlist_desc LONGTEXT NULL,
                playlist_order VARCHAR(50) NOT NULL DEFAULT 'ASC'
                ) $charset_collate;";

        $res = $wpdb->get_results($sql);
    }

    if (!$mfound)
    {
        $sql = "CREATE TABLE ".$table_med2play." (
                rel_id BIGINT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                media_id BIGINT(10) NOT NULL DEFAULT '0',
                playlist_id BIGINT(10) NOT NULL DEFAULT '0',
                porder MEDIUMINT(10) NOT NULL DEFAULT '0',
                sorder INT(3) NOT NULL DEFAULT '0'
                ) $charset_collate;";

        $res = $wpdb->get_results($sql);
    }

if (!$settingsFound)
    {
        $sql = "CREATE TABLE ".$table_settings." (
                settings_id BIGINT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                autoplay BIGINT(10) NOT NULL DEFAULT '0',
                playlist BIGINT(10) NOT NULL DEFAULT '0',
                playlistauto BIGINT(10) NOT NULL DEFAULT '0',
                buffer MEDIUMINT(10) NOT NULL DEFAULT '0',
                normalscale INT(3) NOT NULL DEFAULT '0',
                fullscreenscale INT(3) NOT NULL DEFAULT '0',
                logopath VARCHAR(200) NOT NULL DEFAULT '0',
                logo_target VARCHAR(200) NOT NULL,
                volume INT(3) NOT NULL DEFAULT '0',
                logoalign VARCHAR(10) NOT NULL DEFAULT '0',
                hdflvplayer_ads INT(3) NOT NULL DEFAULT '0',
                HD_default INT(3) NOT NULL DEFAULT '0',
                download INT(3) NOT NULL DEFAULT '0',
                logoalpha  INT(3) NOT NULL DEFAULT '0',
                skin_autohide INT(3) NOT NULL DEFAULT '0',
                stagecolor VARCHAR(45) NOT NULL,
                skin VARCHAR(200) NOT NULL,
                embed_visible INT(3) NOT NULL DEFAULT '0',
                shareURL VARCHAR(200) NOT NULL,
                playlistXML VARCHAR(200) NOT NULL,
                debug INT(3) NOT NULL DEFAULT '0',
                timer INT(3) NOT NULL DEFAULT '0',
                zoom INT(3) NOT NULL DEFAULT '0',
                email INT(3) NOT NULL DEFAULT '0',
                fullscreen INT(3) NOT NULL DEFAULT '0',
                width INT(5) NOT NULL DEFAULT '0',
                height INT(5) NOT NULL DEFAULT '0',
                display_logo INT(3) NOT NULL DEFAULT '0',
                configXML VARCHAR(200) NOT NULL,
                uploads varchar(25) NOT NULL,
                license varchar(200) NOT NULL,
                hideLogo varchar(25) NOT NULL,
                keyApps varchar(50) NOT NULL,
                preroll varchar(10) NOT NULL,
                postroll varchar(10) NOT NULL,
                feature varchar(25) NOT NULL,
                rowsFea varchar(25) NOT NULL,
                colFea varchar(25) NOT NULL,
                recent varchar(25) NOT NULL,
                rowsRec varchar(25) NOT NULL,
                colRec varchar(25) NOT NULL,
                popular varchar(25) NOT NULL,
                rowsPop varchar(25) NOT NULL,
                colPop varchar(25) NOT NULL,
                page varchar(25) NOT NULL,
                stylesheet varchar(50) NOT NULL
                ) $charset_collate;";
        $res = $wpdb->get_results($sql);
    }
    else {
        $sql = "ALTER TABLE ".$table_settings." ADD preroll VARCHAR(25) NOT NULL AFTER keyApps,
            ADD postroll VARCHAR(25) NOT NULL AFTER preroll";
        $res = $wpdb->get_results($sql);
    }
     if(!$rollfound)
    {
     $sqlRoll ="CREATE TABLE IF NOT EXISTS ".$table_vgads." (
     `ads_id` bigint(10) NOT NULL AUTO_INCREMENT,
     `file_path` varchar(200) NOT NULL,
     `title` varchar(200) NOT NULL,
      PRIMARY KEY (`ads_id`)
      ) $charset_collate;";
      $res = $wpdb->get_results($sqlRoll);
    }
    if(!$lfound)
    {
       $sqlLang = "CREATE TABLE IF NOT EXISTS ".$table_language." (
  `lang_id` int(50) NOT NULL AUTO_INCREMENT,
  `play` varchar(50) NOT NULL,
  `pause` varchar(50) NOT NULL,
  `hdison` varchar(50) NOT NULL,
  `hdisoff` varchar(50) NOT NULL,
  `lang_zoom` varchar(50) NOT NULL,
  `lang_share` varchar(50) NOT NULL,
  `lang_fullscreen` varchar(50) NOT NULL,
  `relatedvideos` varchar(50) NOT NULL,
  `sharetheword` varchar(50) NOT NULL,
  `sendanemail` varchar(50) NOT NULL,
  `download` varchar(25) NOT NULL,
  `to` varchar(50) NOT NULL,
  `from` varchar(50) NOT NULL,
  `note` varchar(50) NOT NULL,
  `send` varchar(50) NOT NULL,
  `copylink` varchar(50) NOT NULL,
  `copyembed` varchar(50) NOT NULL,
  `facebook` varchar(50) NOT NULL,
  `reddit` varchar(50) NOT NULL,
  `friendfeed` varchar(50) NOT NULL,
  `slashdot` varchar(50) NOT NULL,
  `delicious` varchar(50) NOT NULL,
  `myspace` varchar(50) NOT NULL,
  `wong` varchar(50) NOT NULL,
  `digg` varchar(50) NOT NULL,
  `blinklist` varchar(50) NOT NULL,
  `bebo` varchar(50) NOT NULL,
  `fark` varchar(50) NOT NULL,
  `tweet` varchar(50) NOT NULL,
  `furl` varchar(50) NOT NULL,
  PRIMARY KEY (`lang_id`)
) $charset_collate;";
    $res = $wpdb->get_results($sqlLang);
    }
    else
   {
    $fullscrAlt = "ALTER TABLE ".$table_language." CHANGE `fullscreen` `lang_fullscreen` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
    $zoomAlt = "ALTER TABLE ".$table_language." CHANGE `zoom` `lang_zoom` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
    $shareAlt = "ALTER TABLE ".$table_language." CHANGE `share` `lang_share` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
    $sql_down = "ALTER TABLE ".$table_language." ADD download VARCHAR(25) NOT NULL AFTER sendanemail";
    $res_down = $wpdb->get_results($sql_down);
    $resF = $wpdb->get_results($fullscrAlt);
    $resZ = $wpdb->get_results($zoomAlt);
    $resS = $wpdb->get_results($shareAlt);
   }
    if(!$tags)
   {
    $sqlTags = "CREATE TABLE IF NOT EXISTS $table_tags  (
  `vtag_id` int(25) NOT NULL AUTO_INCREMENT,
  `tags_name` varchar(50) NOT NULL,
  `seo_name` varchar(100) NOT NULL,
  `media_id` varchar(50) NOT NULL,
  PRIMARY KEY (`vtag_id`)
  ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
    $resTags = $wpdb->get_results($sqlTags);
   }
   else
   {
      $sql = "ALTER TABLE ".$table_tags." ADD `seo_name` varchar(100) NOT NULL AFTER tags_name";
      $res = $wpdb->get_results($sql);
   }
   
$site_url = get_option('siteurl');
    // Creating the pages for the contus-more,contus-home and contus- video pages
$postM = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."posts where post_content='[contusMore]'");
if (empty($postM)) {
$contus_more   =  "INSERT INTO ".$wpdb->prefix."posts(`post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`)
        VALUES
                  (1, NOW(), NOW(), '[contusMore]', '', '', 'publish', 'closed', 'open', '', 'contusMore', '', '', '2011-01-10 10:42:23',
                   '2011-01-10 10:42:23', '','', '$site_url/?page_id=',0, 'page', '', 0)";

$resMore       =  $wpdb->get_results($contus_more);
$moreId        =   $wpdb->get_var("select ID from ".$wpdb->prefix."posts ORDER BY ID DESC LIMIT 0,1");
$moreUpd       =  "UPDATE ".$wpdb->prefix."posts SET post_parent='$moreId',guid='$site_url/?page_id=$moreId' WHERE ID='$moreId'";
$moreUpdate    =  $wpdb->get_results($moreUpd);
}

//echo '--------------------------------------------------------------------------------------------------------------------------------------';
//echo '--------------------------------------------------------------------------------------------------------------------------------------';


$postV = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."posts where post_content='[contusVideo]'");
 if (empty($postV)) {

$contus_video    =  "INSERT INTO ".$wpdb->prefix."posts(`post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`)
        VALUES
                    (1, NOW(), NOW(), '[contusVideo]', '', '', 'publish', 'open', 'open', '', 'contusVideo', '', '', '2011-01-10 10:42:43',
                    '2011-01-10 10:42:43', '','', '$site_url/?page_id=',0, 'page', '', 0)";

$resVideo       =  $wpdb->get_results($contus_video);
$videoId        =  $wpdb->get_var("select ID from ".$wpdb->prefix."posts ORDER BY ID DESC LIMIT 0,1");
$videoUpd       =  "UPDATE ".$wpdb->prefix."posts SET post_parent='$videoId',guid='$site_url/?page_id=$videoId' WHERE ID='$videoId'";
$videoUpdate    =  $wpdb->get_results($videoUpd);
 }

//echo '--------------------------------------------------------------------------------------------------------------------------------------';
//echo '--------------------------------------------------------------------------------------------------------------------------------------';

$postH = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."posts where post_content='[contusHome]'");
if (empty($postH)) {

$contus_home   =  "INSERT INTO ".$wpdb->prefix."posts(`post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`)
        VALUES
                 (1, NOW(), NOW(), '[contusHome]', 'contusHome', '', 'publish', 'closed', 'open', '', 'contusHome', '', '', '2011-01-10 10:42:06',
                 '2011-01-10 10:42:06', '','', '$site_url/?page_id=',0, 'page', '', 0)";

$resHome       =  $wpdb->get_results($contus_home);
$homeId        =  $wpdb->get_var("select ID from ".$wpdb->prefix."posts ORDER BY ID DESC LIMIT 0,1");
$homeUpd       =  "UPDATE ".$wpdb->prefix."posts SET guid='$site_url/?page_id=$homeId' WHERE ID='$homeId'";
$homeUpdate    =  $wpdb->get_results($homeUpd);
$post_meta     =   "INSERT INTO ".$wpdb->prefix."postmeta (`post_id`, `meta_key`, `meta_value`) VALUES
('$homeId', '_edit_last', '1'),
('$homeId', '_edit_lock', ''),
('$homeId', '_wp_page_template', 'contusHome.php')";
$postmetaIns = $wpdb->get_results($post_meta);
}
// For the postmeta table
 }
// get the default options after reset or installation
?>