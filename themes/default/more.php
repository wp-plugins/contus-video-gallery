<?php
/*
Description:  Contus VideoGallery.
Edited By: Saranya
Version: 1.0
Plugin URI: http://www.hdflvplayer.net/wordpress-video-gallery/
wp-content\plugins\contus-hd-flv-player\themes\default\contusMore.php
Date : 21/2/2011
*/
$site_url = get_bloginfo('url');
$dir     = dirname(plugin_basename(__FILE__));
$dirExp  = explode('/',$dir);
$dirPage = $dirExp[0];
?>
<?php
$vPageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]'");
$moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]'");
$styleSheet = $wpdb->get_var("select stylesheet from " . $wpdb->prefix . "hdflvvideoshare_settings WHERE settings_id='1'");
?>
       <?php
 //Including Contus Style Sheet for the Share
        if($styleSheet == 'contus')
        { ?>
          <link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo dirname(plugin_basename(__FILE__))?>/css/contusStyle.css" />
       <?php  } ?>
<script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $dirPage  ?>/js/script.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $dirPage  ?>/css/style.css" />
<script type="text/javascript">
    var baseurl,folder;
    baseurl = '<?php echo $site_url; ?>';
    folder  = '<?php echo $dirPage; ?>';
</script>
<!--  Content For More Page -->
<?php
class default_more
{
function listPagesNoTitle($args) //Pagination
{
    if ($args)
    {
        $args .= '&echo=0';
    } else
    {
        $args = 'echo=0';
    }
    $pages = wp_list_pages($args);
    echo $pages;
}
function findStart($limit) { //Pagination
    if ((!isset($_GET['page'])) || ($_GET['page'] == "1")) {
        $start = 0;
        $_GET['page'] = 1;
    } else {
        $start = ($_GET['page'] - 1) * $limit;
    }
    return $start;
}
/*
 * int findPages (int count, int limit)
 * Returns the number of pages needed based on a count and a limit
 */
function findPages($count, $limit) { //Pagination

    $pages = (($count % $limit) == 0) ? $count / $limit : floor($count / $limit) + 1;
    if($pages == 1)
    {
        $pages = '';
    }
        return $pages;
}
/*
 * string pageList (int curpage, int pages)
 * Returns a list of pages in the format of "Â« < [pages] > Â»"
 * */
function pageList($curpage, $pages, $more, $page_id , $search = '')
{
    //Pagination
    $page_list = "";
    if($search != ''){
        $searchKey = urldecode('&search='.$search);
        $self = '?page_id=' . $page_id . '&more=' . $more . $searchKey;
    }else{
        $self = '?page_id=' . $page_id . '&more=' . $more;
    }

    /* Print the first and previous page links if necessary */
    if (($curpage != 1) && ($curpage)) {
        $page_list .= "  <a href=\"" . $self . "&page=1\" title=\"First Page\">Â«</a> ";
    }

    if (($curpage - 1) > 0) {
        $page_list .= "<a href=\"" . $self . "&page=" . ($curpage - 1) . "\" title=\"Previous Page\"><</a> ";
    }

    /* Print the numeric page list; make the current page unlinked and bold */
    for ($i = 1; $i <= $pages; $i++) {
        if ($i == $curpage) {
            $page_list .= "<b>" . $i . "</b>";
        } else {
            $page_list .= "<a href=\"" . $self . "&page=" . $i . "\" title=\"Page " . $i . "\">" . $i . "</a>";
        }
        $page_list .= " ";
    }

    /* Print the Next and Last page links if necessary */
    if (($curpage + 1) <= $pages) {
        $page_list .= "<a href=\"" . $self . "&page=" . ($curpage + 1) . "\" title=\"Next Page\">></a> ";
    }

    if (($curpage != $pages) && ($pages != 0)) {
        $page_list .= "<a href=\"" . $self . "&page=" . $pages . "\" title=\"Last Page\">Â»</a> ";
    }
    $page_list .= "</td>\n";

    return $page_list;
}

/*
 * string nextPrev (int curpage, int pages)
 * Returns "Previous | Next" string for individual pagination (it's a word!)
 */

function nextPrev($curpage, $pages) { //Pagination
    $next_prev = "";

    if (($curpage - 1) <= 0) {
        $next_prev .= "Previous";
    } else {
        $next_prev .= "<a href=\"" . $_SERVER['PHP_SELF'] . "&page=" . ($curpage - 1) . "\">Previous</a>";
    }

    $next_prev .= " | ";

    if (($curpage + 1) > $pages) {
        $next_prev .= "Next";
    } else {
        $next_prev .= "<a href=\"" . $_SERVER['PHP_SELF'] . "&page=" . ($curpage + 1) . "\">Next</a>";
    }
    return $next_prev;
}
function featureVideos()
{
global $wpdb;
$site_url = get_bloginfo('url');
$dir     = dirname(plugin_basename(__FILE__));
$dirExp  = explode('/',$dir);
$dirPage = $dirExp[0];
$vPageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]'");
$moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]'");
$pageFetch = $wpdb->get_row("SELECT page FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
$limit = $pageFetch->page;
$div = '<div style="float:left">';
    // Feature Videos listing Starts
    $options = get_option('HDFLVSettings');
    $more = $_REQUEST['more'];
    if ($more == 'fea')
    {
        $div .='<div><h3 class="more_title">Feature Videos</h3>';
           /* Find the start depending on $_GET['page'] (declared if it's null) */
        $start = self::findStart($limit);
        /* Find the number of rows returned from a query; Note: Do NOT use a LIMIT clause in this query */
        $sql = mysql_query("select * from " . $wpdb->prefix . "hdflvvideoshare WHERE featured='ON'");
        $count = mysql_num_rows($sql);

        /* Find the number of pages based on $count and $limit */
        $pages = self::findPages($count, $limit);

        /* Now we use the LIMIT clause to grab a range of rows */
        $result = mysql_query("select * from " . $wpdb->prefix . "hdflvvideoshare WHERE featured='ON'  LIMIT " . $start . "," . $limit . "");
        // were there any posts found?
        $features = $wpdb->get_results("select * from " . $wpdb->prefix . "hdflvvideoshare WHERE featured='ON'  LIMIT " . $start . "," . $limit . "");
        // were there any posts found?
        if (!empty($features))
        {
            // posts were found, loop through them
            $j = 0;
            foreach ($features as $feature)
            {
                $imageFea[$j] = $feature->image;
                $vidF[$j] = $feature->vid;
                $nameF[$j] = $feature->name;
                $hitcount[$j] = $feature->hitcount;
                $getPlaylist[$j]   = $wpdb->get_row("SELECT playlist_id FROM ".$wpdb->prefix."hdflvvideoshare_med2play WHERE media_id='$vidF[$j]'");
                $playlist_id[$j]   = $getPlaylist[$j]->playlist_id;
                $fetPlay[$j]       = $wpdb->get_row("SELECT playlist_name FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE pid='$playlist_id[$j]'");
                $fetched[$j]       = $fetPlay[$j]->playlist_name;
                $j++;
            }
            $colF = 4;
            $rowCount = 0;
            $div .= '<div>';
            for ($j = 0; $j < count($features); $j++) {
                if (($j % $colF) == 0)
                 {
                    $rowCount++;
                    $div .= '<div class="contusHome">';
                    if ($imageFea[$j] != '')
                     {
                        $div .='<div class="imageContus"><a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidF[$j].'">
                            <img src="' . $imageFea[$j] . '" alt="' . $nameF[$j] . '" class="imgHome" title="'.$nameF[$j].'" /></a></div>';
                        $div .='<a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidF[$j].'" class="videoHname">';
                        if (strlen($nameF[$j]) > 18)
                        {
                            $div .=substr($nameF[$j], 0, 18);
                        } else
                        {
                            $div .=$nameF[$j];
                        }
                       $div .='</a><div class="views">';
                        if ($hitcount[$j] != 0) {
                            $div .= $hitcount[$j] . ' views';
                        }
                        $div .='</div>';
                        $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id[$j].'">'.$fetched[$j].'</a></div>';
                    } else {

                        $div .='<div class="imageContus"><a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidF[$j].'">
                            <img src="' . $site_url . '/wp-content/plugins/'.dirname(plugin_basename(__FILE__)).'/images/hdflv.jpg" alt="' . $nameF[$j] . '" class="imgHome" title="'.$nameF[$j].'" /></a></div>';
                        $div .='<a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidF[$j].'" class="videoHname">';
                        if (strlen($nameF[$j]) > 18) {
                            $div .=substr($nameF[$j], 0, 18);
                        } else {
                            $div .=$nameF[$j];
                        }
                        $div .='</a><div class="views">';
                        if ($hitcount[$j] != 0) {
                            $div .= $hitcount[$j] . ' views';
                        }
                        $div .='</div>';
                        $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id[$j].'">'.$fetched[$j].'</a></div>';
                    }
                    $div .='</div>';
                } else { //$rowCount++;
                    $div .= '<div class="contusHome">';
                    if ($imageFea[$j] != '')
                    {

                        $div .='<div class="imageContus"><a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidF[$j].'">
                            <img src="' . $imageFea[$j] . '" alt="' . $nameF[$j] . '" class="imgHome" title="'.$nameF[$j].'" /></a></div>';
                       $div .='<a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidF[$j].'" class="videoHname">';
                        if (strlen($nameF[$j]) > 18)
                        {
                            $div .=substr($nameF[$j], 0, 18);
                        } else
                        {
                            $div .=$nameF[$j];
                        }
                        $div .='</a><div class="views">';
                        if ($hitcount[$j] != 0)
                        {
                            $div .= $hitcount[$j] . ' views';
                        }
                        $div .='</div>';
                        $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id[$j].'">'.$fetched[$j].'</a></div>';

                    } else {

                        $div .='<div class="imageContus"><a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidF[$j].'">
                            <img src="' . $site_url . '/wp-content/plugins/'.$dirPage .'/images/hdflv.jpg"
                         alt="' . $nameF[$j] . '" class="imgHome" title="'.$nameF[$j].'" /></a></div>';

                        $div .='<a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidF[$j].'" class="videoHname">';
                        if (strlen($nameF[$j]) > 18) {
                            $div .=substr($nameF[$j], 0, 18);
                        } else {
                            $div .=$nameF[$j];
                        }
                        $div .='</a><div class="views">';
                        if ($hitcount[$j] != 0) {
                            $div .= $hitcount[$j] . ' views';
                        }
                        $div .='</div>';
                        $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id[$j].'">'.$fetched[$j].'</a></div>';
                    }
                    $div .='</div>';
                }
            }
             $div.='</div>';
        } else
            $div .="No Feature videos";
        // end list
        $pagelist = self::pageList($_GET['page'], $pages, $more, $_GET['page_id']);
            $div .='<div class="clear"></div>';
            $div .='<div align="right">'.$pagelist.'</div>';
        $div .='</div>';
    }
  return $div;
}
function recentVideos()
{
global $wpdb;
$site_url = get_bloginfo('url');
$dir     = dirname(plugin_basename(__FILE__));
$dirExp  = explode('/',$dir);
$dirPage = $dirExp[0];
$vPageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]'");
$moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]'");
$pageFetch = $wpdb->get_row("SELECT page FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
$limit = $pageFetch->page;
$more = $_REQUEST['more'];
    if ($more == 'rec') {
      $div = '<div><h3 class="more_title">Recent Videos</h3>';
                   /* Find the start depending on $_GET['page'] (declared if it's null) */
            $start = self::findStart($limit);
            /* Find the number of rows returned from a query; Note: Do NOT use a LIMIT clause in this query */
            $sql = mysql_query("select * from " . $wpdb->prefix . "hdflvvideoshare
                                                    ORDER BY post_date DESC");
            $count = mysql_num_rows($sql);
            /* Find the number of pages based on $count and $limit */
            $pages = self::findPages($count, $limit);
            $more = $_REQUEST['more'];
            /* Now we use the LIMIT clause to grab a range of rows */
            $result = mysql_query("select * from " . $wpdb->prefix . "hdflvvideoshare
                                                    ORDER BY post_date DESC LIMIT " . $start . "," . $limit . "");
            $posts = $wpdb->get_results("select * from " . $wpdb->prefix . "hdflvvideoshare
                                                    ORDER BY post_date DESC LIMIT " . $start . "," . $limit . "");
            if (!empty($posts)) {
                // posts were found, loop through them
                $l = 0;
                foreach ($posts as $post) {
                    $imageRec[$l] = $post->image;
                    $vidR[$l] = $post->vid;
                    $nameR[$l] = $post->name;
                    $hitcount[$l] = $post->hitcount;
                    $getPlaylist[$l]   = $wpdb->get_row("SELECT playlist_id FROM ".$wpdb->prefix."hdflvvideoshare_med2play WHERE media_id='$vidR[$l]'");
                    $playlist_id[$l]   = $getPlaylist[$l]->playlist_id;
                    $fetPlay[$l]       = $wpdb->get_row("SELECT playlist_name FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE pid='$playlist_id[$l]'");
                    $fetched[$l]      = $fetPlay[$l]->playlist_name;
                    $l++;
                }
                $colR = 4;
                $rowCount = 0;
                $div .= '<div>';
                for ($l = 0; $l < count($posts); $l++) {
                    if (($l % $colR) == 0) {
                        $rowCount++;
                        $div .= '<div class="contusHome">';
                        if ($imageRec[$l] != '') {

                            $div .='<div  class="imageContus"><a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidR[$l].'">
                                <img src="' . $imageRec[$l] . '" alt="' . $nameR[$l] . '" class="imgHome" title="'. $nameR[$l].'" /></a></div>';
                            $div .='<a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidR[$l].'" class="videoHname">';
                            if (strlen($nameR[$l]) > 18) {
                                $div .=substr($nameR[$l], 0, 18);
                            } else {
                                $div .=$nameR[$l];
                            }
                            $div .='</a><div class="views">';
                            if ($hitcount[$l] != 0) {
                                $div .= $hitcount[$l] . ' views';
                            }
                            $div .='</div>';
                             $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id[$l].'">'.$fetched[$l].'</a></div>';

                        } else {

                            $div .='<div  class="imageContus"><a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidR[$l].'">
                                <img src="' . $site_url . '/wp-content/plugins/'.$dirPage .'/images/hdflv.jpg" alt="' . $nameR[$l] . '"
                            class="imgHome" title="'. $nameR[$l].'"  /></a></div>';
                            $div .='<a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidR[$l].'" class="videoHname">';
                            if (strlen($nameR[$l]) > 18) {
                                $div .=substr($nameR[$l], 0, 18);
                            } else {
                                $div .=$nameR[$l];
                            }
                            $div .='</a><div class="views">';
                            if ($hitcount[$l] != 0) {
                                $div .= $hitcount[$l] . ' views';
                            }
                            $div .='</div>';
                             $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id[$l].'">'.$fetched[$l].'</a></div>';
                        }
                        $div .='</div>';
                    } else {
                        $div .= '<div class="contusHome">';
                        if ($imageRec[$l] != '') {

                            $div .='<div  class="imageContus"><a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidR[$l].'">
                                <img src="' . $imageRec[$l] . '" alt="' . $nameR[$l] . '" class="imgHome" title="'. $nameR[$l].'"/></a></div>';
                           $div .='<a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidR[$l].'" class="videoHname">';
                            if (strlen($nameR[$l]) > 18) {
                                $div .=substr($nameR[$l], 0, 18);
                            } else {
                                $div .=$nameR[$l];
                            }
                           $div .='</a><div class="views">';
                            if ($hitcount[$l] != 0) {
                                $div .= $hitcount[$l] . ' views';
                            }
                            $div .='</div>';
                             $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id[$l].'">'.$fetched[$l].'</a></div>';

                        } else {

                            $div .='<div  class="imageContus"><a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidR[$l].'">
                                <img src="' . $site_url . '/wp-content/plugins/'.$dirPage .'/images/hdflv.jpg" alt="' . $nameR[$l] . '"
                            class="imgHome" title="'. $nameR[$l].'"></a></div>';
                           $div .='<a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidR[$l].'" class="videoHname">';
                            if (strlen($nameR[$l]) > 18) {
                                $div .=substr($nameR[$l], 0, 18);
                            } else {
                                $div .=$nameR[$l];
                            }
                            $div .='</a><div class="views">';
                            if ($hitcount[$l] != 0) {
                                $div .= $hitcount[$l] . ' views';
                            }
                            $div .='</div>';
                             $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id[$l].'">'.$fetched[$l].'</a></div>';
                        }
                        $div .='</div>';
                    }
                }
               $div.='</div>';
            } else
            $div .="No recent Videos";
            $pagelist = self::pageList($_GET['page'], $pages, $more, $_GET['page_id']);
            $div .='<div class="clear"></div>';
            $div .='<div align="right">'.$pagelist.'</div>';
            $div .='</div>';
        }

 return $div;
}
function popularVideos()
{
global $wpdb;
$site_url = get_bloginfo('url');
$dir     = dirname(plugin_basename(__FILE__));
$dirExp  = explode('/',$dir);
$dirPage = $dirExp[0];
$vPageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]'");
$moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]'");
$pageFetch = $wpdb->get_row("SELECT page FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
$limit = $pageFetch->page;
$more = $_REQUEST['more'];

if ($more == 'pop') {

        $div = '<div> <h3 class="more_title">Popular Videos</h3>';

            /* Find the start depending on $_GET['page'] (declared if it's null) */
            $start = self::findStart($limit);
            /* Find the number of rows returned from a query; Note: Do NOT use a LIMIT clause in this query */
            $sql = mysql_query("select * from " . $wpdb->prefix . "hdflvvideoshare ORDER BY hitcount DESC");
            $count = mysql_num_rows($sql);

            /* Find the number of pages based on $count and $limit */
            $pages = self::findPages($count, $limit);

            /* Now we use the LIMIT clause to grab a range of rows */
            $result = mysql_query("select * from " . $wpdb->prefix . "hdflvvideoshare ORDER BY hitcount DESC LIMIT " . $start . "," . $limit . "");

            $populars = $wpdb->get_results("select * from " . $wpdb->prefix . "hdflvvideoshare ORDER BY hitcount DESC LIMIT " . $start . "," . $limit . "");

            // were there any posts found?
            if (!empty($populars)) {
                // posts were found, loop through them
                $k = 0;

                foreach ($populars as $popular) {

                    $imagePop[$k] = $popular->image;
                    $vidP[$k] = $popular->vid;
                    $nameP[$k] = $popular->name;
                    $hitcount[$k] = $popular->hitcount;
                    $getPlaylist[$k]   = $wpdb->get_row("SELECT playlist_id FROM ".$wpdb->prefix."hdflvvideoshare_med2play WHERE media_id='$vidP[$k]'");
                    $playlist_id[$k]   = $getPlaylist[$k]->playlist_id;
                    $fetPlay[$k]       = $wpdb->get_row("SELECT playlist_name FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE pid='$playlist_id[$k]'");
                    $fetched[$k]       = $fetPlay[$k]->playlist_name;
                    $k++;
                }
                $rowCount = 0;
                $colP = 4;
                $div .= '<div>';
                for ($k = 0; $k < count($populars); $k++) {
                    if (($k % $colP) == 0) {
                        $rowCount++;
                        $div .= '<div class="contusHome">';
                        if ($imagePop[$k] != '') {

                            $div .='<div  class="imageContus"><a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidP[$k].'">
                                <img src="' . $imagePop[$k] . '" alt="' . $nameP[$k] . '" class="imgHome" title="'.$nameP[$k].'" /></a></div>';
                            $div .='<a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidP[$k].'" class="videoHname">';
                            if (strlen($nameP[$k]) > 18) {
                                $div.=substr($nameP[$k], 0, 18);
                            } else {
                                $div.=$nameP[$k];
                            }
                            $div .='</a><div class="views">';
                            if ($hitcount[$k] != 0) {
                                $div .= $hitcount[$k] . ' views';
                            }
                             $div .='</div>';
                              $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id[$k].'">'.$fetched[$k].'</a></div>';

                        } else {

                            $div .='<div  class="imageContus"><a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidP[$k].'">
                                <img src="' . $site_url . '/wp-content/plugins/'.$dirPage .'/images/hdflv.jpg" alt="' . $nameP[$k] . '"
                            class="imgHome" title="'.$nameP[$k].'" /></a></div>';
                            $div .='<a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidP[$k].'" class="videoHname">';
                            if (strlen($nameP[$k]) > 18) {
                                $div.=substr($nameP[$k], 0, 18);
                            } else {
                                $div.=$nameP[$k];
                            }
                            $div .='</a><div class="views">';
                            if ($hitcount[$k] != 0) {
                                $div .= $hitcount[$k] . ' views';
                            }
                             $div .='</div>';
                              $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id[$k].'">'.$fetched[$k].'</a></div>';

                        }
                        $div .='</div>';
                    } else { //$rowCount++;
                        $div .= '<div class="contusHome">';
                        if ($imagePop[$k] != '') {

                            $div .='<div  class="imageContus"><a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidP[$k].'">
                                <img src="' . $imagePop[$k] . '" alt="' . $nameP[$k] . '" class="imgHome" title="'.$nameP[$k].'" /></a></div>';
                            $div .='<a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidP[$k].'" class="videoHname">';
                            if (strlen($nameP[$k]) > 18) {
                                $div.=substr($nameP[$k], 0, 18);
                            } else {
                                $div.=$nameP[$k];
                            }
                            $div .='</a><div class="views">';
                            if ($hitcount[$k] != 0) {
                                $div .= $hitcount[$k] . ' views';
                            }
                             $div .='</div>';
                              $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id[$k].'">'.$fetched[$k].'</a></div>';

                        } else {

                            $div .='<div  class="imageContus"><a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidP[$k].'">
                                <img src="' . $site_url . '/wp-content/plugins/'.$dirPage .'/images/hdflv.jpg" alt="' . $nameP[$k] . '"
                            class="imgHome" title="'.$nameP[$k].'" ></a></div>';
                           $div .='<a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidP[$k].'" class="videoHname">';
                            if (strlen($nameP[$k]) > 18) {
                                $div.=substr($nameP[$k], 0, 18);
                            } else {
                                $div.=$nameP[$k];
                            }
                            $div .='</a><div class="views">';
                            if ($hitcount[$k] != 0) {
                                $div .= $hitcount[$k] . ' views';
                            }
                             $div .='</div>';
                              $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$playlist_id[$k].'">'.$fetched[$k].'</a></div>';

                        }
                        $div .='</div>';
                    }
                }
                $div.='</div>';
            } else
               $div .= "No Popular videos";
            // end list

    $pagelist =  self::pageList($_GET['page'], $pages, $more, $_GET['page_id']);
                   $div .='<div class="clear"></div>';
                   $div .='<div align="right">'.$pagelist.'</div>';
                   $div .='</div>';
 }
  $div .='</div>';
  return $div;
}

function relatedPlaylist()
{
global $wpdb;
$site_url = get_bloginfo('url');
$dir     = dirname(plugin_basename(__FILE__));
$dirExp  = explode('/',$dir);
$dirPage = $dirExp[0];
$vPageID = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[video]'");
$moreName = $wpdb->get_var("select ID from " . $wpdb->prefix . "posts WHERE post_content='[videomore]'");
$pageFetch = $wpdb->get_row("SELECT page FROM " . $wpdb->prefix . "hdflvvideoshare_settings");
$limit = $pageFetch->page;
$more = $_REQUEST['more'];
    //Search Videos listing Starts
        if (isset($_REQUEST['playid'])) {

             $start = self::findStart($limit);
             $pages = self::findPages($count, $limit);
             $getPlaylist  = $_REQUEST['playid'];

 $countCheck = mysql_query("SELECT count(*) FROM " . $wpdb->prefix . "hdflvvideoshare w
        INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play m
        WHERE (m.playlist_id = '$getPlaylist'
        AND m.media_id = w.vid) GROUP BY w.vid");
 $count = mysql_num_rows($countCheck);

            /* Find the number of pages based on $count and $limit */
            $pages = self::findPages($count , $limit);


            /* Now we use the LIMIT clause to grab a range of rows */
              $fetch_video   = "SELECT * FROM " . $wpdb->prefix . "hdflvvideoshare w
        INNER JOIN " . $wpdb->prefix . "hdflvvideoshare_med2play m
        WHERE (m.playlist_id = '$getPlaylist'
        AND m.media_id = w.vid) GROUP BY w.vid";

            $relatedSearch = $wpdb->get_results($fetch_video ." LIMIT " . $start . "," . $limit . "");
            $playlist   =$wpdb->get_var("SELECT playlist_name FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE pid = '$getPlaylist'");
$div ='<div> <h3> '.$playlist.' Videos</h3>';
            //print_r($relatedSearch);
            if (!empty($relatedSearch))
                {
                // posts were found, loop through them
                $p = 0;

                foreach ($relatedSearch as $playlistVideo) {
                    $image[$p] = $playlistVideo->image;
                    $vidS[$p] = $playlistVideo->vid;
                    $nameS[$p] = $playlistVideo->name;
                    $hitcount[$p] = $playlistVideo->hitcount;
                    $p++;
                }


                $rowCount = 0;
                $colPlay = 4;
                $div .= '<div>';
                for ($p = 0; $p < count($relatedSearch); $p++) {
                    if (($p % $colPlay) == 0) {
                        $rowCount++;
                        $div .= '<div  class="contusHome">';
                        if ($image[$p] != '') {

                            $div .='<div  class="imageContus">
                                <a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidS[$p].'">
                                <img src="' . $image[$p] . '" alt="' . $nameS[$p] . '" class="imgHome" title="'.$nameS[$p].'"></a></div>';
                            $div .=' <a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidS[$p].'" class="videoHname">';
                            if (strlen($nameS[$p]) > 30) {
                                $div.=substr($nameS[$p], 0, 30);
                            } else {
                                $div.=$nameS[$p];
                            }
                            $div .='</a>';
                            if ($hitcount[$p] != 0) {
                                $div .= '<div class="views">';
                                $div .= $hitcount[$p] . ' views';
                                $div .= '</div>';
                              }
                             $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$getPlaylist.'">'.$playlist.'</a></div>';

                        } else {
                            $div .='<div  class="imageContus">
                                     <a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidS[$p].'">
                                    <img src="' . $site_url . '/wp-content/plugins/'.$dirPage .'/images/hdflv.jpg" alt="' . $nameS[$p] . '" class="imgHome"
                            title="'.$nameS[$p].'"></a></div>';
                            $div .=' <a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidS[$p].'" class="videoHname">';
                            if (strlen($nameS[$p]) > 30) {
                                $div.=substr($nameS[$p], 0, 30);
                            } else {
                                $div.=$nameS[$p];
                            }
                            $div .='</a>';
                            if ($hitcount[$p] != 0) {
                                $div .= '<div class="views">';
                                $div .= $hitcount[$p] . ' views';
                                $div .= '</div>';
                              }
                               $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$getPlaylist.'">'.$playlist.'</a></div>';

                        }
                        $div .='</div>';
                    } else {
                        $div .= '<div  class="contusHome">';
                        if ($image[$p] != '') {

                            $div .='<div  class="imageContus">
                                 <a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidS[$p].'">
                                <img src="' . $image[$p] . '" alt="' . $nameS[$p] . '" class="imgHome" title="'.$nameS[$p].'"></a></div>';
                            $div .=' <a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidS[$p].'" class="videoHname">';
                            if (strlen($nameS[$p]) > 30) {
                                $div.=substr($nameS[$p], 0, 30).'<br>';
                            } else {
                                $div.=$nameS[$p];
                            }
                            $div .='</a>';
                            if ($hitcount[$p] != 0) {
                                $div .= '<div class="views">';
                                $div .= $hitcount[$p] . ' views';
                                $div .= '</div>';
                              }
                               $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$getPlaylist.'">'.$playlist.'</a></div>';

                        } else {

                            $div .='<div  class="imageContus">
                                     <a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidS[$p].'">
                                    <img src="' . $site_url . '/wp-content/plugins/'.$dirPage .'/images/hdflv.jpg" alt="' . $nameS[$p] . '"
                            class="imgHome" title="'.$nameS[$p].'"></a></div>';
                           $div .=' <a href="'.$site_url.'/?page_id='.$vPageID.'&vid='.$vidS[$p].'" class="videoHname">';
                            if (strlen($nameS[$p]) > 30) {
                                $div.=substr($nameS[$p], 0, 30);
                            } else {
                                $div.=$nameS[$p];
                            }
                            $div .='</a>';
                            if ($hitcount[$p] != 0) {
                                $div .= '<div class="views">';
                                $div .= $hitcount[$p] . ' views';
                                $div .= '</div>';
                            }
                             $div .='<div class="playlistName"><a href="'.$site_url.'/?page_id='.$moreName.'&playid='.$getPlaylist.'">'.$playlist.'</a></div>';
                        }
                        $div .='</div>';
                    }
                }
                $div.='</div>';
            } else
                $div .="No Playlist videos";


        if ($more == 'playlist') {
            $pagelist = self::pageList($_GET['page'], $pages, $more, $_GET['page_id']);
        }else{
            $pagelist = self::pageList($_GET['page'], $pages, $more, $_GET['page_id']);
        }

                    $div .='<div class="clear"></div>';
                    $div .='<div align="right">'.$pagelist.'</div>';
  $div .='</div>';
      }
return $div;
}
}
?>