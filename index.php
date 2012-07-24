<?php

/**
 * @name          : Wordpress VideoGallery.
 * @version	      : 1.5
 * @package       : apptha
 * @subpackage    : contus-video-galleryversion-10
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license	      : GNU General Public License version 2 or later; see LICENSE.txt
 * @Purpose       : Create the tables for the installation.
 * @Creation Date : Fev 21 2011
 * @Modified Date : Jul 19, 2012
 * */

/* * ************************************************************* */
/* Install routine for hdflvplayer
  /*************************************************************** */

function hdflv_install() {
    global $wpdb;

    // set tablename
    $table_name = $wpdb->prefix . 'hdflvvideoshare';
    $table_playlist = $wpdb->prefix . 'hdflvvideoshare_playlist';
    $table_med2play = $wpdb->prefix . 'hdflvvideoshare_med2play';
    $table_settings = $wpdb->prefix . 'hdflvvideoshare_settings';
    $table_language = $wpdb->prefix . 'hdflvvideoshare_language';
    $table_vgads = $wpdb->prefix . 'hdflvvideoshare_vgads';
    $table_tags = $wpdb->prefix . 'hdflvvideoshare_tags';

    $wfound = false;
    $pfound = false;
    $mfound = false;
    $cfound = false;
    $lfound = false;
    $rollfound = false;
    $tags = false;
    $found = true;
    $settingsFound = false;

    foreach ($wpdb->get_results("SHOW TABLES;", ARRAY_N) as $row) {

        if ($row[0] == $table_name)
            $wfound = true;
        if ($row[0] == $table_playlist)
            $pfound = true;
        if ($row[0] == $table_med2play)
            $mfound = true;
        if ($row[0] == $table_comments)
            $cfound = true;
        if ($row[0] == $table_language)
            $lfound = true;
        if ($row[0] == $table_vgads)
            $rollfound = true;
        if ($row[0] == $table_tags)
            $tags = true;
        if ($row[0] == $table_settings)
            $settingsFound = true;
    }

    // add charset & collate like wp core
    $charset_collate = '';

    if (version_compare(mysql_get_server_info(), '4.1.0', '>=')) {
        if (!empty($wpdb->charset))
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        if (!empty($wpdb->collate))
            $charset_collate .= " COLLATE $wpdb->collate";
    }
    if (!$wfound) {
        $sql = "CREATE TABLE " . $table_name . " (
                vid MEDIUMINT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    name MEDIUMTEXT NULL,
                    description carchar(255) NOT NULL,
                    file MEDIUMTEXT NULL,
                    hdfile MEDIUMTEXT NULL,
                    file_type TINYINT(25) NOT NULL,
                    duration varchar(255) NOT NULL,
                    image MEDIUMTEXT NULL,
                    opimage MEDIUMTEXT NULL,
                    download varchar(10) NOT NULL,
                    link MEDIUMTEXT NULL,
                    featured varchar(25) NOT NULL,
                    hitcount int(25) NOT NULL,
                    post_date datetime NOT NULL,
                    postrollads VARCHAR(25) NOT NULL,
                    prerollads VARCHAR(25) NOT NULL
                    ) $charset_collate;";

        $res = $wpdb->get_results($sql);
    }else {
        $sql = "ALTER TABLE " . $table_name . " ADD `postrollads` VARCHAR(25) NOT NULL, ADD `prerollads` VARCHAR(25) NOT NULL, ADD `description` VARCHAR(255) NOT NULL";
        $res = $wpdb->get_results($sql);
    }
    if (!$pfound) {
        $sql = "CREATE TABLE " . $table_playlist . " (
                pid BIGINT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                playlist_name VARCHAR(200) NOT NULL ,
                playlist_desc LONGTEXT NULL,
                playlist_order VARCHAR(50) NOT NULL DEFAULT 'ASC'
                ) $charset_collate;";

        $res = $wpdb->get_results($sql);
    }

    if (!$mfound) {
        $sql = "CREATE TABLE " . $table_med2play . " (
                rel_id BIGINT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                media_id BIGINT(10) NOT NULL DEFAULT '0',
                playlist_id BIGINT(10) NOT NULL DEFAULT '0',
                porder MEDIUMINT(10) NOT NULL DEFAULT '0',
                sorder INT(3) NOT NULL DEFAULT '0'
                ) $charset_collate;";

        $res = $wpdb->get_results($sql);
    }

    if (!$settingsFound) {
        $sql = "CREATE TABLE " . $table_settings . " (
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
                category_page varchar(25) NOT NULL,
                ffmpeg_path varchar(255) NOT NULL,
                stylesheet varchar(50) NOT NULL,
                comment_option TINYINT(1) NOT NULL,
                rowCat varchar(25) NOT NULLL,
                colCat varchar(25) NOT NULLL,
                homecategory varchar(25) NOT NULLL,
                bannercategory varchar(25) NOT NULLL,
                banner_categorylist INT(3) NOT NULL DEFAULT '1',
                hbannercategory varchar(25) NOT NULLL,
                hbanner_categorylist INT(3) NOT NULL DEFAULT '1',
                vbannercategory varchar(25) NOT NULLL,
                vbanner_categorylist INT(3) NOT NULL DEFAULT '1',
                bannerw varchar(25) NOT NULLL,
                playerw varchar(25) NOT NULLL,
                numvideos varchar(25) NOT NULLL
                ) $charset_collate;";
        $res = $wpdb->get_results($sql);
    } else {
        $sql = "ALTER TABLE " . $table_settings . " ADD preroll VARCHAR(25) NOT NULL AFTER keyApps,
        		ADD rowCat varchar(25) NOT NULLL, ADD colCat varchar(25) NOT NULLL,ADD category_page varchar(25) NOT NULL,
                        ADD homecategory varchar(25) NOT NULL,
                        ADD bannercategory varchar(25) NOT NULL, ADD banner_categorylist INT(3) NOT NULL DEFAULT '1',
                        ADD hbannercategory varchar(25) NOT NULL, ADD hbanner_categorylist INT(3) NOT NULL DEFAULT '1',
                        ADD vbannercategory varchar(25) NOT NULL, ADD vbanner_categorylist INT(3) NOT NULL DEFAULT '1',
                        ADD bannerw varchar(25) NOT NULLL, ADD playerw varchar(25) NOT NULLL, ADD numvideos varchar(25) NOT NULLL,
                ADD postroll VARCHAR(25) NOT NULL AFTER preroll, ADD comment_option TINYINT(1) NOT NULL";
        $res = $wpdb->get_results($sql);
    }
    if (!$rollfound) {
        $sqlRoll = "CREATE TABLE IF NOT EXISTS " . $table_vgads . " (
     `ads_id` bigint(10) NOT NULL AUTO_INCREMENT,
     `file_path` varchar(200) NOT NULL,
     `title` varchar(200) NOT NULL,
      PRIMARY KEY (`ads_id`)
      ) $charset_collate;";
        $res = $wpdb->get_results($sqlRoll);
    }
    if (!$lfound) {
        $sqlLang = "CREATE TABLE IF NOT EXISTS " . $table_language . " (
  `lang_id` int(50) NOT NULL AUTO_INCREMENT,
  `play` varchar(50) NOT NULL,
  `pause` varchar(50) NOT NULL,
  `hdison` varchar(50) NOT NULL,
  `hdisoff` varchar(50) NOT NULL,
  `zoom` varchar(50) NOT NULL,
  `share` varchar(50) NOT NULL,
  `lang_fullscreen` varchar(50) NOT NULL,
  `relatedvideos` varchar(50) NOT NULL,
  `sharetheword` varchar(50) NOT NULL,
  `sendanemail` varchar(50) NOT NULL,
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
    } else {
        $fullscrAlt = "ALTER TABLE " . $table_language . " CHANGE `fullscreen` `lang_fullscreen` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
        $zoomAlt = "ALTER TABLE " . $table_language . " CHANGE `zoom` `zoom` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
        $shareAlt = "ALTER TABLE " . $table_language . " CHANGE `share` `share` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL";
        $resF = $wpdb->get_results($fullscrAlt);
        $resZ = $wpdb->get_results($zoomAlt);
        $resS = $wpdb->get_results($shareAlt);
    }
    if (!$tags) {
        $sqlTags = "CREATE TABLE IF NOT EXISTS $table_tags  (
  `vtag_id` int(25) NOT NULL AUTO_INCREMENT,
  `tags_name` varchar(50) NOT NULL,
        `seo_name` text NOT NULL,
  `media_id` varchar(50) NOT NULL,
  PRIMARY KEY (`vtag_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
        $resTags = $wpdb->get_results($sqlTags);
    }

    $site_url = get_option('siteurl');
    // Creating the pages for the contus-more,contus-home and contus- video pages
    $postM = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "posts where post_content='[videomore]'");
    if (empty($postM)) {
        $contus_more = "INSERT INTO " . $wpdb->prefix . "posts(`post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`)
        VALUES
                  (1, NOW(), NOW(), '[videomore]', '', '', 'publish', 'closed', 'open', '', 'Video More', '', '', '2011-01-10 10:42:23',
                   '2011-01-10 10:42:23', '','', '$site_url/?page_id=',0, 'page', '', 0)";

        $resMore = $wpdb->get_results($contus_more);
        $moreId = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts ORDER BY ID DESC LIMIT 0,1");
        $moreUpd = "UPDATE " . $wpdb->prefix . "posts SET post_parent='$moreId',guid='$site_url/?page_id=$moreId' WHERE ID='$moreId'";
        $moreUpdate = $wpdb->get_results($moreUpd);
    }

//echo '--------------------------------------------------------------------------------------------------------------------------------------';
//echo '--------------------------------------------------------------------------------------------------------------------------------------';


    $postV = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "posts where post_content='[video]'");
    if (empty($postV)) {

        $contus_video = "INSERT INTO " . $wpdb->prefix . "posts(`post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`)
        VALUES
                    (1, NOW(), NOW(), '[video]', '', '', 'publish', 'open', 'open', '', 'Video', '', '', '2011-01-10 10:42:43',
                    '2011-01-10 10:42:43', '','', '$site_url/?page_id=',0, 'page', '', 0)";

        $resVideo = $wpdb->get_results($contus_video);
        $videoId = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts ORDER BY ID DESC LIMIT 0,1");
        $videoUpd = "UPDATE " . $wpdb->prefix . "posts SET post_parent='$videoId',guid='$site_url/?page_id=$videoId' WHERE ID='$videoId'";
        $videoUpdate = $wpdb->get_results($videoUpd);
    }

//echo '--------------------------------------------------------------------------------------------------------------------------------------';
//echo '--------------------------------------------------------------------------------------------------------------------------------------';

    $postH = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "posts where post_content='[videohome]'");
    if (empty($postH)) {

        $contus_home = "INSERT INTO " . $wpdb->prefix . "posts(`post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`)
        VALUES
                 (1, NOW(), NOW(), '[videohome]', 'Video Home', '', 'publish', 'closed', 'open', '', 'Video Home', '', '', '2011-01-10 10:42:06',
                 '2011-01-10 10:42:06', '','', '$site_url/?page_id=',0, 'page', '', 0)";

        $resHome = $wpdb->get_results($contus_home);
        $homeId = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts ORDER BY ID DESC LIMIT 0,1");
        $homeUpd = "UPDATE " . $wpdb->prefix . "posts SET guid='$site_url/?page_id=$homeId' WHERE ID='$homeId'";
        $homeUpdate = $wpdb->get_results($homeUpd);


        $post_meta = "INSERT INTO " . $wpdb->prefix . "postmeta (`post_id`, `meta_key`, `meta_value`) VALUES
('$homeId', '_edit_last', '1'),
('$homeId', '_edit_lock', ''),
('$homeId', '_wp_page_template', 'home.php')";
        $postmetaIns = $wpdb->get_results($post_meta);
    }
// For the postmeta table

     //------------Video Categories-----------------

         $videoCategories = $wpdb->get_results("SELECT * FROM " . $table_name);
    if (empty($videoCategories)) {

        $contus_videoCategories = $wpdb->query("INSERT INTO ".$wpdb->prefix."hdflvvideoshare (`vid`, `name`, `description`, `file`, `hdfile`, `file_type`, `duration`, `image`, `opimage`, `download`, `link`, `featured`, `hitcount`, `post_date`, `postrollads`, `prerollads`) VALUES
('', 'Fast And Furious 5 (Official Trailer) HD', '', 'www.youtube.com/watch?v=4PspF_GA-9U', '', 1, '2:27', 'http://img.youtube.com/vi/4PspF_GA-9U/1.jpg', 'http://img.youtube.com/vi/4PspF_GA-9U/0.jpg', '', 'http://www.youtube.com/watch?v=4PspF_GA-9U', 'ON', 2, '2011-11-15 07:22:39', '0', '0'),
('', 'Im Legend Trailer 1080p', '', 'www.youtube.com/watch?v=kAxppVB3SHo', '', 1, '2:04', 'http://img.youtube.com/vi/kAxppVB3SHo/1.jpg', 'http://img.youtube.com/vi/kAxppVB3SHo/0.jpg', '', 'http://www.youtube.com/watch?v=kAxppVB3SHo', 'ON', 1, '2011-11-15 07:23:32', '0', '0'),
('', 'Mission Impossible III Trailer 1', 'trailer of mission impossible 3 with tom cruise', 'www.youtube.com/watch?v=1E-9XIJzQdc&feature=related', '', 1, '1:36', 'http://img.youtube.com/vi/1E-9XIJzQdc/1.jpg', 'http://img.youtube.com/vi/1E-9XIJzQdc/0.jpg', '', 'http://www.youtube.com/watch?v=1E-9XIJzQdc&feature=related', 'ON', 1, '2011-11-15 07:23:51', '0', '0'),
('', 'Titanic - Official Trailer [1997]', 'Deep-sea explorer Brock Lovett has reached the most famous shipwreck of all - the Titanic. Emerging with a safe believed to contain a diamond called ', 'www.youtube.com/watch?v=zCy5WQ9S4c0', '', 1, '4:09', 'http://img.youtube.com/vi/zCy5WQ9S4c0/1.jpg', 'http://img.youtube.com/vi/zCy5WQ9S4c0/0.jpg', '', 'http://www.youtube.com/watch?v=zCy5WQ9S4c0', 'ON', 1, '2011-11-15 07:24:11', '0', '0'),
('', 'Harry Potter and the Deathly Hallows Trailer Official HD', '', 'www.youtube.com/watch?v=_EC2tmFVNNE', '', 1, '2:30', 'http://img.youtube.com/vi/_EC2tmFVNNE/1.jpg', 'http://img.youtube.com/vi/_EC2tmFVNNE/0.jpg', '', 'http://www.youtube.com/watch?v=_EC2tmFVNNE', 'ON', 1, '2011-11-15 07:24:34', '0', '0'),
('', 'Into the Wild -  Trailer', 'into the wild - trailer', 'www.youtube.com/watch?v=2LAuzT_x8Ek&feature=related', '', 1, '2:24', 'http://img.youtube.com/vi/2LAuzT_x8Ek/1.jpg', 'http://img.youtube.com/vi/2LAuzT_x8Ek/0.jpg', '', 'http://www.youtube.com/watch?v=2LAuzT_x8Ek&feature=related', 'ON', 1, '2011-11-15 07:24:49', '0', '0'),
('', 'Cecelia - The Balcony Girl - Dilsukhnagar Arena - 3D Animation Short Film', '', 'www.youtube.com/watch?v=JhKQz2TwSAE', '', 1, '4:04', 'http://img.youtube.com/vi/JhKQz2TwSAE/1.jpg', 'http://img.youtube.com/vi/JhKQz2TwSAE/0.jpg', '', 'http://www.youtube.com/watch?v=JhKQz2TwSAE', 'ON', 4, '2011-11-15 07:25:19', '0', '0'),
('', '3D Animation Short - Bolt [2009] - I Found Myself [HD]', 'Bolt finally realises what it', 'www.youtube.com/watch?v=aKFKixs3IkU', '', 1, '2:20', 'http://img.youtube.com/vi/aKFKixs3IkU/1.jpg', 'http://img.youtube.com/vi/aKFKixs3IkU/0.jpg', '', 'http://www.youtube.com/watch?v=aKFKixs3IkU', 'ON', 3, '2011-11-15 07:25:52', '0', '0'),
('', 'Feel  the Punch - Dilsukhnagar Arena - Award-Winning 3D Animation Short Film', 'A mind-game between a beggar and a commuter waiting to board a bus takes a startling turn as the commuter realizes he is outsmarted.', 'www.youtube.com/watch?v=BO3N6VdYCjY', '', 1, '3:20', 'http://img.youtube.com/vi/BO3N6VdYCjY/1.jpg', 'http://img.youtube.com/vi/BO3N6VdYCjY/0.jpg', '0', 'http://www.youtube.com/watch?v=BO3N6VdYCjY', 'ON', 2, '2011-11-15 07:26:12', '0', '0'),
('', 'Short Animation -ALARM- HD720', 'Moo-hyun Jang, a director of  independent Animation team, MESAI. Let me introduce the second short animation film, ALARM.', 'www.youtube.com/watch?v=vN83DfmH9Tw', '', 1, '8:50', 'http://img.youtube.com/vi/vN83DfmH9Tw/1.jpg', 'http://img.youtube.com/vi/vN83DfmH9Tw/0.jpg', '', 'http://www.youtube.com/watch?v=vN83DfmH9Tw', 'ON', 3, '2011-11-15 07:26:36', '0', '0'),
('', 'Lufuno the White Lion - HD 720p', 'Lufuno is one of the few male White Lions in North America and the only one of his kind to be professionally trained for film and commercial work. ', 'www.youtube.com/watch?v=gyE4wyqvOU4', '', 1, '2:43', 'http://img.youtube.com/vi/gyE4wyqvOU4/1.jpg', 'http://img.youtube.com/vi/gyE4wyqvOU4/0.jpg', '', 'http://www.youtube.com/watch?v=gyE4wyqvOU4', 'ON', 3, '2011-11-15 07:27:22', '0', '0'),
('', 'White Lion Cubs birth part 2 - eating meat', 'Four week old white lion cubs eating meat', 'www.youtube.com/watch?v=pogrcbuybRY', '', 1, '9:53', 'http://img.youtube.com/vi/pogrcbuybRY/1.jpg', 'http://img.youtube.com/vi/pogrcbuybRY/0.jpg', '', 'http://www.youtube.com/watch?v=pogrcbuybRY', 'ON', 1, '2011-11-15 07:27:39', '0', '0'),
('', 'HD: Lioness Hunts Zebra The Great Migration - BBC One', '', 'www.youtube.com/watch?v=INcW26-iyqU', '', 1, '0:38', 'http://img.youtube.com/vi/INcW26-iyqU/1.jpg', 'http://img.youtube.com/vi/INcW26-iyqU/0.jpg', '', 'http://www.youtube.com/watch?v=INcW26-iyqU', 'ON', 2, '2011-11-15 07:27:56', '0', '0'),
('', 'Longest Six by Sachin', 'Sachin Tendulkar Best and Longest Six', 'www.youtube.com/watch?v=uPxQLRJOMZ4', '', 1, '0:42', 'http://img.youtube.com/vi/uPxQLRJOMZ4/1.jpg', 'http://img.youtube.com/vi/uPxQLRJOMZ4/0.jpg', '', 'http://www.youtube.com/watch?v=uPxQLRJOMZ4', 'ON', 3, '2011-11-15 07:30:19', '0', '0'),
('', 'Quick Recap of Cricket World Cup 2011 Highlights HD HQ....', 'Cricket World Cup 2011 best ever highlights HD HQ....', 'www.youtube.com/watch?v=q76yIVfVBaI', '', 1, '4:52', 'http://img.youtube.com/vi/q76yIVfVBaI/1.jpg', 'http://img.youtube.com/vi/q76yIVfVBaI/0.jpg', '', 'http://www.youtube.com/watch?v=q76yIVfVBaI', 'ON', 2, '2011-11-15 07:30:35', '0', '0'),
('', 'Grand Theft Auto 5 Trailer (GTA V)', 'Check out GTA5 @ Rockstar Games ', 'www.youtube.com/watch?v=MXRqUjGmA7A', '', 1, '1:25', 'http://img.youtube.com/vi/MXRqUjGmA7A/1.jpg', 'http://img.youtube.com/vi/MXRqUjGmA7A/0.jpg', '', 'http://www.youtube.com/watch?v=MXRqUjGmA7A', 'ON', 3, '2011-11-15 07:32:10', '0', '0'),
('', 'BrainShake 2 the Hot New iPad Game', '', 'www.youtube.com/watch?v=5PX1PnfvzHs', '', 1, '1:18', 'http://img.youtube.com/vi/5PX1PnfvzHs/1.jpg', 'http://img.youtube.com/vi/5PX1PnfvzHs/0.jpg', '', 'http://www.youtube.com/watch?v=5PX1PnfvzHs', 'ON', 3, '2011-11-15 07:32:31', '0', '0')
");

	}

     //------------Movie Trailer -----------------
         $movieTrailer = $wpdb->get_results("SELECT * FROM " . $table_playlist);
  	if (empty($movieTrailer)) {

		$contus_movieTrailer = $wpdb->query("INSERT INTO " . $table_playlist . "(`pid`, `playlist_name`, `playlist_desc`, `playlist_order`)
        VALUES
        (2, 'Movie Trailer', '', 'ASC'),
        (3, 'Animation', '', 'ASC'),
        (4, 'Animals', '', 'ASC'),
        (5, 'Cricket', '', 'ASC'),
        (6, 'Video Game', '', 'ASC') ");
	}
    //------------video share settings -----------------
         $videoSettings = $wpdb->get_results("SELECT * FROM " . $table_settings);
    if (empty($videoSettings)) {

        $contus_videoSettings = $wpdb->query("INSERT INTO " .$table_settings . "(`settings_id`, `autoplay`, `playlist`, `playlistauto`, `buffer`, `normalscale`, `fullscreenscale`, `logopath`, `logo_target`, `volume`, `logoalign`, `hdflvplayer_ads`, `HD_default`, `download`, `logoalpha`, `skin_autohide`, `stagecolor`, `skin`, `embed_visible`, `shareURL`, `playlistXML`, `debug`, `timer`, `zoom`, `email`, `fullscreen`, `width`, `height`, `display_logo`, `configXML`, `uploads`, `license`, `hideLogo`, `keyApps`, `preroll`, `postroll`, `feature`, `rowsFea`, `colFea`, `recent`, `rowsRec`, `colRec`, `popular`, `rowsPop`, `colPop`, `page`, `category_page`, `ffmpeg_path`, `stylesheet`, `comment_option`, `rowCat`, `colCat`,`homecategory`,`bannercategory`,` banner_categorylist`
,`hbannercategory`,`hbanner_categorylist`,`vbannercategory`,`vbanner_categorylist`
,`bannerw`,`playerw`,`numvideos`)
        VALUES
                    (1, 1, 0, 1, 100, 0, 0, 'platoon.jpg', '', 50, 'TL', 0, 0, 1, 0, 0, '', 'skin_black', 0, '', '', 0, 0, 0, 0, 0, 620, 400, 0, '0', '', '', 'true', '', '0', '0', 'on', '2', '5', 'on', '2', '5', 'on', '2', '5', '20', '4', '', '','1','2','5','on','popular','1','hpopular','1','vpopular','1','650','450','5')");
    }
    //------------Media to play -----------------
	$media2Play = $wpdb->get_results("SELECT * FROM " . $table_med2play . "where post_content='[videofeatured]'");
	if (empty($media2Play)) {
   $insertLanguage = $wpdb->query("INSERT INTO ".$wpdb->prefix."hdflvvideoshare_med2play (`rel_id`, `media_id`, `playlist_id`, `porder`, `sorder`) VALUES
(6, 27, 3, 0, 0),
(7, 1, 2, 0, 0),
(8, 2, 2, 0, 0),
(9, 3, 2, 0, 0),
(10, 4, 2, 0, 0),
(11, 5, 2, 0, 0),
(12, 6, 2, 0, 0),
(13, 7, 3, 0, 0),
(14, 8, 3, 0, 0),
(15, 9, 3, 0, 0),
(16, 10, 3, 0, 0),
(17, 12, 4, 0, 0),
(18, 13, 4, 0, 0),
(19, 14, 5, 0, 0),
(20, 15, 5, 0, 0),
(21, 16, 6, 0, 0),
(22, 17, 6, 0, 0),
(23, 11, 4, 0, 0)");
	}


        	$table_tagsdata = $wpdb->get_results("SELECT * FROM " . $table_tags );
                    if (empty($table_tagsdata)) {

		$table_tagsdatainsert = $wpdb->query("INSERT INTO ". $wpdb->prefix . "hdflvvideoshare_tags (`vtag_id`, `tags_name`, `seo_name`, `media_id`) VALUES
                    ('', 'The\, Fast\, And\, Furious\, (Official\, Trailer)\, velo', 'The-Fast-And-Furious-(Official-Trailer)-velo', '1'),
                    ('', 'am\, Legend\, Movie\, Will Smith\, Richard\, Matheson', 'am,-Legend,-Movie,-Will-Smith,-Richard,-Matheson,-', '2'),
                    ('', 'Mission\, Impossible\, III\, Trailer', 'Mission,-Impossible,-III,-Trailer,-1', '3'),
                    ('', 'James\, Cameron\, Leonardo\, DiCaprio\, Kate\, Winslet', 'James,-Cameron,-Leonardo,-DiCaprio,-Kate,-Winslet,', '4'),
                    ('', 'Harry\, Potter\, and\, the\, Deathly\, Hallows\, Trailer', 'Harry,-Potter,-and,-the,-Deathly,-Hallows,-Trailer', '5'),
                    ('', 'into\, the\, wild\, kristen\, stewart\, Emile\, Hirsch ', 'into,-the,-wild,-kristen,-stewart,-Emile,-Hirsch,-', '6'),
                    ('', 'DNA Incubation\, Cecelia\, The Balcony GirlDNA Incub', 'DNA-Incubation,-Cecelia,-The-Balcony-GirlDNA-Incub', '7'),
                    ('', 'Bolt\, Found\, Myself\, Puppy\, Dog\, American\, White ', 'Bolt,-Found,-Myself,-Puppy,-Dog,-American,-White,-', '8'),
                    ('', 'DNA Incubation\, Dilsukhnagar Arena Animation\, Stud', 'DNA-Incubation,-Dilsukhnagar-Arena-Animation,-Stud', '9'),
                    ('', 'Animation\, 3d\, CG\, HD\, ALARM\, movie\, mentalray', 'Animation,-3d,-CG,-HD,-ALARM,-movie,-mentalray,-ma', '10'),
                    ('', 'white lion\, way west media\, lion hd\, lio', 'white-lion,-way-west-media,-lion-hd,-lions-hd,-lio', '11'),
                    ('', 'White\, lion\, cubs\, safari\, park\, feed\, cute\, eatin', 'White,-lion,-cubs,-safari,-park,-feed,-cute,-eatin', '12'),
                    ('', 'lion\, chase\, hunt\, wildebeest\, baby\, calf\, zebra ', 'lion,-chase,-hunt,-wildebeest,-baby,-calf,-zebra,-', '13'),
                    ('', 'Sachin\, Tendulkar\, Best\, Six\, Cricket', 'Sachin,-Tendulkar,-Best,-Six,-Cricket', '14'),
                    ('', 'Cricket\, WC\, 2011\, Recap....', 'Cricket,-WC,-2011,-Recap....', '15'),
                    ('', 'Grand\, Theft\, Auto\, gta\, gtav\, five\, GTA V\, GTA 5', 'Grand,-Theft,-Auto,-gta,-gtav,-five,-GTA-V,-GTA-5,', '16'),
                    ('', 'iPad\, iPhone\, iPod touch\, game\, games', 'iPad,-iPhone,-iPod-touch,-game,-games,mobile', '17')
");
	}
}
// get the default options after reset or installation
?>