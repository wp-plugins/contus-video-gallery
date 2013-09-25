<?php
/*
  Name: Wordpress Video Gallery
  URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
  Description: Wordpress Video Gallery Installation file.
  Version: 2.3
  Author: Apptha
  Author URI: http://www.apptha.com
  License: GPL2
 */

## Function to alter table while upgrade plugin
function AddColumnIfNotExists($errorMsg, $table, $column, $attributes = "INT( 11 ) NOT NULL DEFAULT '0'") {
    global $wpdb;
    $columnExists           = false;
    $upgra                  = 'upgrade';
    $query                  = 'SHOW COLUMNS FROM ' . $table;


    if (!$result = $wpdb->query($query)) {
        return false;
    }
    $columnData             = $wpdb->get_results($query);
    foreach ($columnData as $valueColumn) {
        if ($valueColumn->Field == $column) {
            $columnExists   = true;
            break;
        }
    }
    ## Alter table if column not exist
    if (!$columnExists) {
        $query              = "ALTER TABLE `$table` ADD `$column` $attributes";
        $wpdb->query($query);
        if (!$result = $wpdb->query($query)) {
            return false;
        }
        $errorMsg           = 'notexistcreated';
    }
    return true;
}

## Delete Unwanted columns from the table
function delete_video_column($table, $column) {
    global $wpdb;
    $columnExists           = false;
    $upgra                  = 'upgrade';
    $query                  = 'SHOW COLUMNS FROM ' . $table;


    if (!$result = $wpdb->query($query)) {
        return false;
    }
    $columnData             = $wpdb->get_results($query);
    foreach ($columnData as $valueColumn) {
        if ($valueColumn->Field == $column) {
            $columnExists   = true;
            break;
        }
    }
    ## Delet column if it exist
    if ($columnExists) {
        $query              = "ALTER TABLE `$table` DROP `$column`;";
        $wpdb->query($query);
        if (!$result = $wpdb->query($query)) {
            return false;
        }
        $errorMsg           = 'notdeleted';
    }
    return true;
}

## Upgrade post table
function upgrade_videos() {
    global $wpdb;
    $posttable              = $wpdb->prefix . 'posts';
    $slugID                 = $wpdb->get_results("SELECT slug FROM " . $wpdb->prefix . "hdflvvideoshare ORDER BY vid DESC LIMIT 1");
    if(empty($slugID)){
    $videoID                = $wpdb->get_results("SELECT vid,name FROM " . $wpdb->prefix . "hdflvvideoshare");
    for ($i = 0; $i < count($videoID); $i++) {
        $slug               = sanitize_title($videoID[$i]->name);
        $name               = $videoID[$i]->name;
        $vid                = $videoID[$i]->vid;
        $post_content       = "[hdvideo id=" . $vid . "]";
        ## Insert into post table for already existing videos
        $contus_videoposts = $wpdb->query("INSERT INTO " . $posttable . " (`post_author`,`post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
                                    ('1','2011-11-15 07:22:39', '2011-11-15 07:22:39', '$post_content', '$name', '', 'publish', 'open', 'closed', '', '$slug', '', '', '2011-11-15 07:22:39', '2011-11-15 07:22:39', '', '0', '', '0','videogallery', '', '0')");
        $post_id            = $wpdb->insert_id;
        $guid               = get_bloginfo('url') . "/?post_type=videogallery&#038;p=" . $post_id;
        $wpdb->query("UPDATE " . $wpdb->prefix . "hdflvvideoshare SET slug = $post_id WHERE vid = $vid");       ## Update slug id in plugin's video table
        $wpdb->query("UPDATE " . $posttable . " SET guid = '$guid' WHERE ID = $post_id");                       ## Update guid id in post table
    }

    $featuredID = $wpdb->get_results("select vid from " . $wpdb->prefix . "hdflvvideoshare where featured='ON'");
    for ($i = 0; $i < count($featuredID); $i++) {
        $vid                = $featuredID[$i]->vid;
        $wpdb->query("UPDATE " . $wpdb->prefix . "hdflvvideoshare SET featured = 1 WHERE vid = $vid");
    }
    }
}

## Install sample videos
function videogallery_install() {
    global $wpdb;
    $table_name             = $wpdb->prefix . 'hdflvvideoshare';
    $table_playlist         = $wpdb->prefix . 'hdflvvideoshare_playlist';
    $table_med2play         = $wpdb->prefix . 'hdflvvideoshare_med2play';
    $table_settings         = $wpdb->prefix . 'hdflvvideoshare_settings';
    $table_vgads            = $wpdb->prefix . 'hdflvvideoshare_vgads';
    $table_tags             = $wpdb->prefix . 'hdflvvideoshare_tags';
    $posttable              = $wpdb->prefix . 'posts';

    $wfound                 = false;
    $pfound                 = false;
    $mfound                 = false;
    $cfound                 = false;
    $lfound                 = false;
    $rollfound              = false;
    $tags                   = false;
    $found                  = true;
    $settingsFound          = false;

    foreach ($wpdb->get_results("SHOW TABLES;", ARRAY_N) as $row) {

        if ($row[0] == $table_name)
            $wfound         = true;
        if ($row[0] == $table_playlist)
            $pfound         = true;
        if ($row[0] == $table_med2play)
            $mfound         = true;
        if ($row[0] == $table_comments)
            $cfound         = true;
        if ($row[0] == $table_vgads)
            $rollfound      = true;
        if ($row[0] == $table_tags)
            $tags           = true;
        if ($row[0] == $table_settings)
            $settingsFound  = true;
    }

    ## get default collate
    $charset_collate = '';

    if (version_compare(mysql_get_server_info(), '4.1.0', '>=')) {
        if (!empty($wpdb->charset))
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        if (!empty($wpdb->collate))
            $charset_collate .= " COLLATE $wpdb->collate";
    }
    ## Create wp_hdflvvideoshare table
    if (!$wfound) {
        $sql        = "CREATE TABLE " . $table_name . " (
                    vid MEDIUMINT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    name MEDIUMTEXT NULL,
                    description MEDIUMTEXT NOT NULL,
                    embedcode LONGTEXT NOT NULL,
                    file MEDIUMTEXT NULL,
                    streamer_path MEDIUMTEXT NULL,
                    hdfile MEDIUMTEXT NULL,
                    slug TEXT NULL,
                    file_type TINYINT(25) NOT NULL,
                    duration varchar(255) NOT NULL,
                    image MEDIUMTEXT NULL,
                    opimage MEDIUMTEXT NULL,
                    download varchar(10) NOT NULL,
                    link MEDIUMTEXT NULL,
                    featured varchar(25) NOT NULL,
                    hitcount int(25) NOT NULL DEFAULT '0',
                    ratecount int(25) NOT NULL DEFAULT '0',
                    rate int(25) NOT NULL DEFAULT '0',
                    post_date datetime NOT NULL,
                    postrollads VARCHAR(25) NOT NULL,
                    prerollads VARCHAR(25) NOT NULL,
                    midrollads INT NOT NULL DEFAULT '0',
                    imaad INT NOT NULL DEFAULT '0',
                    publish INT NOT NULL,
                    islive INT NOT NULL,
                    ordering INT NOT NULL DEFAULT '0'
                    ) $charset_collate;";

        $res        = $wpdb->get_results($sql);
    }
    ## Create wp_hdflvvideoshare_playlist table
    if (!$pfound) {
        $sql        = "CREATE TABLE " . $table_playlist . " (
                    pid BIGINT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                    playlist_name VARCHAR(200) NOT NULL ,
                    playlist_desc LONGTEXT NULL,
                    is_publish INT NOT NULL,
                    playlist_order INT NOT NULL
                    ) $charset_collate;";

        $res        = $wpdb->get_results($sql);
    }
    ## Create wp_hdflvvideoshare_med2play table
    if (!$mfound) {
        $sql        = "CREATE TABLE " . $table_med2play . " (
                    rel_id BIGINT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                    media_id BIGINT(10) NOT NULL DEFAULT '0',
                    playlist_id BIGINT(10) NOT NULL DEFAULT '0',
                    porder MEDIUMINT(10) NOT NULL DEFAULT '0',
                    sorder INT(3) NOT NULL DEFAULT '0'
                    ) $charset_collate;";
        $res        = $wpdb->get_results($sql);
    }
    ## Create wp_hdflvvideoshare_settings table
    if (!$settingsFound) {
        $sql        = "CREATE TABLE " . $table_settings . " (
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
                    logoalpha  INT(3) NOT NULL DEFAULT '100',
                    skin_autohide INT(3) NOT NULL DEFAULT '0',
                    stagecolor VARCHAR(45) NOT NULL,
                    embed_visible INT(3) NOT NULL DEFAULT '0',
                    ratingscontrol INT(3) NOT NULL DEFAULT '0',
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
                    uploads varchar(200) NOT NULL,
                    license varchar(200) NOT NULL,
                    keyApps varchar(50) NOT NULL,
                    keydisqusApps varchar(50) NOT NULL,
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
                    rowCat varchar(25) NOT NULL,
                    colCat varchar(25) NOT NULL,
                    rowMore varchar(25) NOT NULL,
                    colMore varchar(25) NOT NULL,
                    homecategory varchar(25) NOT NULL,
                    bannercategory varchar(25) NOT NULL,
                    banner_categorylist INT(3) NOT NULL DEFAULT '1',
                    hbannercategory varchar(25) NOT NULL,
                    hbanner_categorylist INT(3) NOT NULL DEFAULT '1',
                    vbannercategory varchar(25) NOT NULL,
                    vbanner_categorylist INT(3) NOT NULL DEFAULT '1',
                    bannerw varchar(25) NOT NULL,
                    playerw varchar(25) NOT NULL,
                    numvideos varchar(25) NOT NULL,
                    gutterspace INT(3) NOT NULL,
                    default_player INT(11) NOT NULL,
                    player_colors LONGTEXT  NOT NULL,
                    playlist_open INT(3) NOT NULL,
                    showPlaylist INT(3) NOT NULL,
                    midroll_ads INT(3) NOT NULL,
                    adsSkip INT(3) NOT NULL,
                    adsSkipDuration VARCHAR(45) NOT NULL,
                    relatedVideoView VARCHAR(45) NOT NULL,
                    imaAds INT(3) NOT NULL,
                    trackCode TEXT NOT NULL,
                    showTag INT(3) NOT NULL,
                    shareIcon INT(3) NOT NULL,
                    volumecontrol INT(3) NOT NULL,
                    playlist_auto INT(3) NOT NULL,
                    progressControl INT(3) NOT NULL,
                    imageDefault INT(3) NOT NULL
                    ) $charset_collate;";
        $res        = $wpdb->get_results($sql);
    }
    ## Create wp_hdflvvideoshare_vgads table
    if (!$rollfound) {
        $sqlRoll    = "CREATE TABLE IF NOT EXISTS " . $table_vgads . " (
                    `ads_id` bigint(10) NOT NULL AUTO_INCREMENT,
                    `file_path` varchar(200) NOT NULL,
                    `title` varchar(200) NOT NULL,
                    `description` text NOT NULL,
                    `targeturl` text NOT NULL,
                    `clickurl` text NOT NULL,
                    `adtype` text NOT NULL,
                    `admethod` text NOT NULL,
                    `imaadwidth` INT(11) NOT NULL,
                    `imaadheight` INT(11) NOT NULL,
                    `imaadpath` text NOT NULL,
                    `publisherId` text NOT NULL,
                    `contentId` text NOT NULL,
                    `imaadType` INT( 11 ) NOT NULL,
                    `channels` varchar(200) NOT NULL,
                    `impressionurl` text NOT NULL,
                    `publish` INT( 10 ) NOT NULL,
                    PRIMARY KEY (`ads_id`)
                    ) $charset_collate;";
        $res        = $wpdb->get_results($sqlRoll);
    }
    ## Create wp_hdflvvideoshare_tags table
    if (!$tags) {
        $sqlTags    = "CREATE TABLE IF NOT EXISTS $table_tags  (
                    `vtag_id` int(25) NOT NULL AUTO_INCREMENT,
                    `tags_name` MEDIUMTEXT NOT NULL,
                    `seo_name` MEDIUMTEXT NOT NULL,
                    `media_id` varchar(50) NOT NULL,
                    PRIMARY KEY (`vtag_id`)
                    ) $charset_collate;";
        $resTags    = $wpdb->get_results($sqlTags);
    }

    $site_url       = get_option('siteurl');

    ## Creating the pages for the videomore
    $postM = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "posts where post_content='[videomore]'");
    if (empty($postM)) {
        $contus_more = "INSERT INTO " . $wpdb->prefix . "posts(`post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`)
                        VALUES
                        (1, NOW(), NOW(), '[videomore]', 'Video Categories', '', 'publish', 'open', 'open', '', 'video-more', '', '', '2011-01-10 10:42:23',
                        '2011-01-10 10:42:23', '',0, '$site_url/?page_id=',0, 'page', '', 0)";

        $resMore     = $wpdb->get_results($contus_more);
        $moreId      = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts ORDER BY ID DESC LIMIT 0,1");
        $moreUpd     = "UPDATE " . $wpdb->prefix . "posts SET guid='$site_url/?page_id=$moreId' WHERE ID='$moreId'";
        $moreUpdate  = $wpdb->get_results($moreUpd);
    }

    ## Creating the pages for the videohome

    $postH           = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "posts where post_content='[videohome]'");
    if (empty($postH)) {

        $contus_home = "INSERT INTO " . $wpdb->prefix . "posts(`post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`)
                        VALUES
                        (1, NOW(), NOW(), '[videohome]', 'Videos', '', 'publish', 'open', 'open', '', 'video-home', '', '', '2011-01-10 10:42:06',
                        '2011-01-10 10:42:06', '',0, '$site_url/?page_id=',0, 'page', '', 0)";

        $resHome     = $wpdb->get_results($contus_home);
        $homeId      = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts ORDER BY ID DESC LIMIT 0,1");
        $homeUpd     = "UPDATE " . $wpdb->prefix . "posts SET guid='$site_url/?page_id=$homeId' WHERE ID='$homeId'";
        $homeUpdate  = $wpdb->get_results($homeUpd);
        $post_meta   = "INSERT INTO " . $wpdb->prefix . "postmeta (`post_id`, `meta_key`, `meta_value`) VALUES
                        ('$homeId', '_edit_last', '1'),
                        ('$homeId', '_edit_lock', ''),
                        ('$homeId', '_wp_page_template', 'home.php')";
    }

    ## Insert sample videos

    $videoCategories = $wpdb->get_results("SELECT * FROM " . $table_name);
    $post_id         = $wpdb->get_var("SELECT ID FROM " . $posttable . " order by ID desc");
    $postid          = array();
    for ($i = 0; $i < 17; $i++) {
        $postid[$i]  = $post_id + 1;
        $post_id++;
    }
    if (empty($videoCategories)) {

        $contus_videoCategories = $wpdb->query("INSERT INTO " . $table_name . " (`slug`, `name`, `description`, `embedcode`, `file`, `hdfile`, `file_type`, `duration`, `image`, `opimage`, `download`, `link`, `featured`, `hitcount`, `post_date`, `postrollads`, `prerollads`, `publish`,`ordering`,`streamer_path`,`islive`, `ratecount`, `rate`) VALUES
                                ($postid[0],'Pacific Rim Official Wondercon Trailer (2013) - Guillermo del Toro Movie HD', '','', 'http://www.youtube.com/watch?v=Ef6vQBGqLW8', '', 1, '2:38', 'http://i3.ytimg.com/vi/Ef6vQBGqLW8/mqdefault.jpg', 'http://i3.ytimg.com/vi/Ef6vQBGqLW8/maxresdefault.jpg', '', 'http://www.youtube.com/watch?v=Ef6vQBGqLW8', '1', 1, '2013-08-06 13:54:39', '0', '0', '1','0','','0','0','0'),
                                ($postid[1],'GI JOE 2 Retaliation Trailer 2 - 2013 Movie - Official [HD]', 'G I Joe Retaliation Trailer 2 - 2013 movie - official movie trailer in HD - sequel of the 2009 \'s GI Joe film - starring Channing Tatum, Adrianne Palicki, Dwayne Johnson, Bruce Willis - directed by Jon Chu.', '','http://www.youtube.com/watch?v=mKNpy-tGwxE', '', 1, '2:31', 'http://i3.ytimg.com/vi/mKNpy-tGwxE/mqdefault.jpg', 'http://i3.ytimg.com/vi/mKNpy-tGwxE/maxresdefault.jpg', '', 'http://www.youtube.com/watch?v=mKNpy-tGwxE', '1', 2, '2013-08-06 13:46:43', '0', '0', '1','1','','0','0','0'),
                                ($postid[2],'2012 - Full HD trailer - At UK Cinemas November 13', 'Never before has a date in history been so significant to so many cultures, so many religions, scientists, and governments.  2012 is an epic adventure about a global cataclysm that brings an end to the world and tells of the heroic struggle of the survivo','', 'http://www.youtube.com/watch?v=rvI66Xaj9-o', '', 1, '2:22', 'http://i3.ytimg.com/vi/rvI66Xaj9-o/mqdefault.jpg', 'http://i3.ytimg.com/vi/rvI66Xaj9-o/maxresdefault.jpg', '', 'http://www.youtube.com/watch?v=rvI66Xaj9-o', '1', 1, '2013-08-06 13:47:15', '0', '0', '1','2','','0','0','0'),
                                ($postid[3],'Iron Man - Trailer [HD]', 'Paramount Pictures and Marvel Studios\' big screen adaptation of Marvel\'s legendary Super Hero Iron Man will launch into theaters on May 2, 2008. Oscar nominee Robert Downey Jr. stars as Tony Stark/Iron Man in the story of a billionaire industrialist and','', 'http://www.youtube.com/watch?v=8hYlB38asDY', '', 1, '2:30', 'http://i3.ytimg.com/vi/8hYlB38asDY/mqdefault.jpg', 'http://i3.ytimg.com/vi/8hYlB38asDY/maxresdefault.jpg', '', 'http://www.youtube.com/watch?v=8hYlB38asDY', '1', 1, '2013-08-06 13:50:52', '0', '0', '1','3','','0','0','0'),
                                ($postid[4],'THE AVENGERS Trailer 2012 Movie - Official [HD]', 'The Avengers Trailer 2012 - Official movie teaser trailer in HD - starring Robert Downey Jr., Chris Evans, Mark Ruffalo, Chris Hemsworth, Scarlett Johansson.Joss Whedon brings together the ultimate team of superheroes in the first official trailer for','', 'http://www.youtube.com/watch?v=orfMJJEd0wk', '', 1, '1:47', 'http://i3.ytimg.com/vi/orfMJJEd0wk/mqdefault.jpg', 'http://i3.ytimg.com/vi/orfMJJEd0wk/maxresdefault.jpg', '', 'http://www.youtube.com/watch?v=orfMJJEd0wk', '1', 1, '2013-08-06 13:53:12', '0', '0', '1','4','','0','0','0'),
                                ($postid[5],'Cronicles of Narnia :Prince Caspian Trailer HD 720p', 'Cronicles of Narnia :Prince Caspian Trailer High Definition 720p','', 'http://www.youtube.com/watch?v=yfX1S-ifI3E', '', 1, '2:31', 'http://i3.ytimg.com/vi/yfX1S-ifI3E/mqdefault.jpg', 'http://i3.ytimg.com/vi/yfX1S-ifI3E/maxresdefault.jpg', '', 'http://www.youtube.com/watch?v=yfX1S-ifI3E', '1', 1, '2013-08-06 13:53:58', '0', '0', '1','5','','0','0','0'),
                                ($postid[6],'The Hobbit: The Desolation of Smaug International Trailer (2013) - Lord of the Rings Movie HD', '','', 'http://www.youtube.com/watch?v=TeGb5XGk2U0', '', 1, '1:57', 'http://i3.ytimg.com/vi/TeGb5XGk2U0/mqdefault.jpg', 'http://i3.ytimg.com/vi/TeGb5XGk2U0/maxresdefault.jpg', '', 'http://www.youtube.com/watch?v=TeGb5XGk2U0', '1', 4, '2013-08-06 14:00:39', '0', '0', '1','6','','0','0','0'),
                                ($postid[7],'Pirates of the Caribbean: On Stranger Tides Trailer HD', '','', 'http://www.youtube.com/watch?v=egoQRNKeYxw', '', 1, '2:29', 'http://i3.ytimg.com/vi/egoQRNKeYxw/mqdefault.jpg', 'http://i3.ytimg.com/vi/egoQRNKeYxw/maxresdefault.jpg', '', 'http://www.youtube.com/watch?v=egoQRNKeYxw', '1', 3, '2013-08-06 14:01:58', '0', '0', '1','7','','0','0','0'),
                                ($postid[8],'Fast & Furious 6 - Official Trailer [HD]', 'Since Dom (Diesel) and Brians (Walker) Rio heist toppled a kingpins empire and left their crew with $100 million, our heroes have scattered across the globe. But their inability to return home and living forever on the lam have left their lives incomplete','', 'http://www.youtube.com/watch?v=PP7pH4pqC5A', '', 1, '2:35', 'http://i3.ytimg.com/vi/PP7pH4pqC5A/mqdefault.jpg', 'http://i3.ytimg.com/vi/PP7pH4pqC5A/maxresdefault.jpg', '0', 'http://www.youtube.com/watch?v=PP7pH4pqC5A', '1', 2, '2013-08-06 14:04:38', '0', '0', '1','8','','0','0','0'),
                                ($postid[9],'Samsung Demo HD - Blu-Ray Sound 7.1 ch', 'En el video se muestra el audio 7.1 de Samsung en sus equipos Blu-Ray como el HT-BD2 y el BD-P3600, este ultimo con salida de 8 canales discretos','', 'http://www.youtube.com/watch?v=UJ1MOWg15Ec', '', 1, '1:40', 'http://i3.ytimg.com/vi/UJ1MOWg15Ec/mqdefault.jpg', 'http://i3.ytimg.com/vi/UJ1MOWg15Ec/maxresdefault.jpg', '', 'http://www.youtube.com/watch?v=UJ1MOWg15Ec', '1', 3, '2013-08-06 14:04:52', '0', '0', '1','9','','0','0','0'),
                                ($postid[10],'White House Down Trailer #2 2013 Jamie Foxx Movie - Official [HD]', 'White House Down Trailer #2 2013 - Official movie trailer 2 in HD - starring Channing Tatum, Jamie Foxx, Maggie Gyllenhaal - directed by Roland Emmerich - a Washington, D.C. police officer is on a tour of the presidential mansion when a heavily armed grou','', 'http://www.youtube.com/watch?v=Kkoor7Z6aeE', '', 1, '2:35', 'http://i3.ytimg.com/vi/Kkoor7Z6aeE/mqdefault.jpg', 'http://i3.ytimg.com/vi/Kkoor7Z6aeE/maxresdefault.jpg', '', 'http://www.youtube.com/watch?v=Kkoor7Z6aeE', '1', 3, '2013-08-06 14:08:59', '0', '0', '1','10','','0','0','0'),
                                ($postid[11],'Landscapes: Volume 2', '','', 'http://www.youtube.com/watch?v=DaYx4XmWEoI', '', 1, '3:31', 'http://i3.ytimg.com/vi/DaYx4XmWEoI/mqdefault.jpg', 'http://i3.ytimg.com/vi/DaYx4XmWEoI/maxresdefault.jpg', '', 'http://www.youtube.com/watch?v=DaYx4XmWEoI', '1', 1, '2013-08-06 14:09:48', '0', '0', '1','11','','0','0','0'),
                                ($postid[12],'Krrish 3 - Official Theatrical Trailer (Exclusive)', 'Watch the Exclusive Official Theatrical Trailer of Krrish 3, the most awaited movie of the year starring Hrithik Roshan, Priyanka Chopra, Kangna Ranaut, Vivek Oberoi & Shaurya Chauhan. Releasing this Diwali...!Directed & Produced by - Rakesh Roshan','', 'http://www.youtube.com/watch?v=MCCVVgtI5xU', '', 1, '2:16', 'http://i3.ytimg.com/vi/MCCVVgtI5xU/mqdefault.jpg', 'http://i3.ytimg.com/vi/MCCVVgtI5xU/maxresdefault.jpg', '', 'http://www.youtube.com/watch?v=MCCVVgtI5xU', '1', 2, '2013-08-06 14:10:54', '0', '0', '1','12','','0','0','0'),
                                ($postid[13],'THE TWILIGHT SAGA: BREAKING DAWN PART 2 - TV Spot Generation', '','', 'http://www.youtube.com/watch?v=ey0aA3YY0Mo', '', 1, '0:32', 'http://i3.ytimg.com/vi/ey0aA3YY0Mo/mqdefault.jpg', 'http://i3.ytimg.com/vi/ey0aA3YY0Mo/maxresdefault.jpg', '', 'http://www.youtube.com/watch?v=ey0aA3YY0Mo', '1', 3, 'http://www.youtube.com/watch?v=ey0aA3YY0Mo', '0', '0', '1','13','','0','0','0'),
                                ($postid[14],'Journey To The Center Of The Earth HD Trailer', '','', 'http://www.youtube.com/watch?v=iJkspWwwZLM', '', 1, '2:30', 'http://i3.ytimg.com/vi/iJkspWwwZLM/mqdefault.jpg', 'http://i3.ytimg.com/vi/iJkspWwwZLM/maxresdefault.jpg', '', 'http://www.youtube.com/watch?v=iJkspWwwZLM', '1', 2, '2013-08-06 14:12:07', '0', '0', '1','14','','0','0','0'),
                                ($postid[15],'ICE AGE 4 Trailer 2012 Movie - Continental Drift - Official [HD]', 'Ice Age 4: Continental Drift Trailer 2012 Movie - Official Ice Age 4 trailer in [HD] - Scrat accidentally triggers the breakup of Pangea and thus the splitting of the continents.','', 'http://www.youtube.com/watch?v=ja-qjGeDBZQ', '', 1, '2:33', 'http://i3.ytimg.com/vi/ja-qjGeDBZQ/mqdefault.jpg', 'http://i3.ytimg.com/vi/ja-qjGeDBZQ/maxresdefault.jpg', '', 'http://www.youtube.com/watch?v=ja-qjGeDBZQ', '1', 3, '2013-08-06 13:47:15', '0', '0', '1','15','','0','0','0'),
                                ($postid[16],'Big Buck Bunny', 'Big Buck Bunny was the first project in the Blender Institute Amsterdam. This 10 minute movie has been made inspired by the best cartoon tradition.','', 'http://www.youtube.com/watch?v=Vpg9yizPP_g', '', 1, '1:47', 'http://i3.ytimg.com/vi/Vpg9yizPP_g/mqdefault.jpg', 'http://i3.ytimg.com/vi/Vpg9yizPP_g/maxresdefault.jpg', '', 'http://www.youtube.com/watch?v=Vpg9yizPP_g', '1', 3, '2013-08-06 13:53:12', '0', '0', '1','16','','0','0','0')
                                ");
        ## video title array
        $videoName              = array(
                                0 => 'Pacific Rim Official Wondercon Trailer (2013) - Guillermo del Toro Movie HD',
                                1 => 'GI JOE 2 Retaliation Trailer 2 - 2013 Movie - Official [HD]',
                                2 => '2012 - Full HD trailer - At UK Cinemas November 13',
                                3 => 'Iron Man - Trailer [HD]',
                                4 => 'THE AVENGERS Trailer 2012 Movie - Official [HD]',
                                5 => 'Cronicles of Narnia :Prince Caspian Trailer HD 720p',
                                6 => 'The Hobbit: The Desolation of Smaug International Trailer (2013) - Lord of the Rings Movie HD',
                                7 => 'Pirates of the Caribbean: On Stranger Tides Trailer HD',
                                8 => 'Fast & Furious 6 - Official Trailer [HD]',
                                9 => 'Samsung Demo HD - Blu-Ray Sound 7.1 ch',
                                10 => 'White House Down Trailer #2 2013 Jamie Foxx Movie - Official [HD]',
                                11 => 'Landscapes: Volume 2',
                                12 => 'Krrish 3 - Official Theatrical Trailer (Exclusive)',
                                13 => 'THE TWILIGHT SAGA: BREAKING DAWN PART 2 - TV Spot Generation',
                                14 => 'Journey To The Center Of The Earth HD Trailer',
                                15 => 'ICE AGE 4 Trailer 2012 Movie - Continental Drift - Official [HD]',
                                16 => 'Big Buck Bunny'
                                );
        ## insert all sample video in to post table
        for ($i = 1; $i <= 17; $i++) {
            $j                  = $i - 1;
            $slug               = sanitize_title($videoName[$j]);
            $post_content       = "[hdvideo id=" . $i . "]";
            $postID             = $postid[$j];
            $guid               = get_bloginfo('url') . "/?post_type=videogallery&#038;p=" . $postID;
            $contus_videoposts  = $wpdb->query("INSERT INTO " . $posttable . " (`post_author`,`post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
                                ('1','2011-11-15 07:22:39', '2011-11-15 07:22:39', '$post_content', '$videoName[$j]', '', 'publish', 'open', 'closed', '', '$slug', '', '', '2011-11-15 07:22:39', '2011-11-15 07:22:39', '', 0, '$guid', '0','videogallery', '', '0')
                                ");
        }
    }
    ## Insert sample categories
    $movieTrailer               = $wpdb->get_results("SELECT * FROM " . $table_playlist);
    if (empty($movieTrailer)) {

        $contus_movieTrailer    = $wpdb->query("INSERT INTO " . $table_playlist . "(`pid`, `playlist_name`, `playlist_desc`, `playlist_order`, `is_publish`)
                                VALUES
                                (1, 'Movie Trailer', '', '1','1'),
                                (2, 'Animation', '', '2','1'),
                                (3, 'Animals', '', '3','1'),
                                (4, 'Cricket', '', '4','1'),
                                (5, 'Video Game', '', '5','1') ");
    }
    ## Update settings
    $videoSettings              = $wpdb->get_results("SELECT * FROM " . $table_settings);
    if (empty($videoSettings)) {
        $contus_videoSettings   = $wpdb->query("INSERT INTO " . $table_settings . "(`default_player`,`settings_id`, `autoplay`, `playlist`,`playlistauto`,
                                `buffer`, `normalscale`, `fullscreenscale`, `logopath`, `logo_target`,
                                `volume`, `logoalign`, `hdflvplayer_ads`, `HD_default`, `download`,
                                `logoalpha`,`skin_autohide`, `stagecolor`, `embed_visible`, `ratingscontrol`, 
                                `shareURL`,`playlistXML`,`debug`, `timer`, `zoom`,
                                `email`,`fullscreen`, `width`, `height`, `display_logo`,
                                `configXML`,`uploads`, `license`, `keyApps`,`keydisqusApps`,
                                `preroll`,`postroll`,`feature`, `rowsFea`, `colFea`,
                                `recent`,`rowsRec`, `colRec`, `popular`, `rowsPop`,
                                `colPop`,`page`, `category_page`, `ffmpeg_path`, `stylesheet`,
                                `comment_option`,`rowCat`, `colCat`,`homecategory`,`bannercategory`,
                                `banner_categorylist`,`hbannercategory`,`hbanner_categorylist`,`vbannercategory`,`vbanner_categorylist`,
                                `bannerw`,`playerw`,`numvideos`,`gutterspace`,`colMore`,
                                `rowMore`,`player_colors`,`playlist_open`,`showPlaylist`,`midroll_ads`,
                                `adsSkip`,`adsSkipDuration`,`relatedVideoView`,`imaAds`,`trackCode`,
                                `showTag`,`shareIcon`,`volumecontrol`,`playlist_auto`,`progressControl`,
                                `imageDefault`)
                                VALUES
                                (0,1, 1, 1, 1,
                                3, 2, 2, 'platoon.jpg', '' ,
                                50, 'BL', 0, 1, 1,
                                100, 1, '', 1, 1, 
                                '','', 0, 1, 1,
                                1,1, 620, 350, 0,
                                '0' ,'wp-content/uploads/videogallery', '', '','',
                                '0','0', '1', '2', '4',
                                '1','2', '4', '1', '2',
                                '4','20', '4', '', '',
                                '1','2','4','off','popular',
                                '1','hpopular','1','vpopular','1',
                                '650','450','5','20','4',
                                '2','','1','1','0',
                                '1','5','center','0','',
                                '1','1','1','1', '1',
                                '1')");
    }

    ## Update video and category details in med2play table
    $media2Play             = $wpdb->get_results("SELECT * FROM " . $table_med2play . "where post_content='[videofeatured]'");
    if (empty($media2Play)) {
        $insertLanguage     = $wpdb->query("INSERT INTO " . $wpdb->prefix . "hdflvvideoshare_med2play (`rel_id`, `media_id`, `playlist_id`, `porder`, `sorder`) VALUES
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
                            (21, 16, 2, 0, 0),
                            (22, 17, 2, 0, 0),
                            (23, 11, 4, 0, 0)");
    }
    flush_rewrite_rules();
}
?>