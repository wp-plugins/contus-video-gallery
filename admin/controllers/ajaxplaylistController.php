<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: Ajax Playlist Controller.
Version: 2.0
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/

include_once($adminModelPath . 'ajaxplaylist.php');//including Playlist model file for get database information.
if(class_exists('AjaxPlaylistController') != true)
{//checks if the PlaylistController class has been defined starts
    class AjaxPlaylistController extends AjaxPlaylistModel
    {//VideoadController class starts
        public $_status;
        public $_msg;
        public $_search;
        public $_playlistsearchQuery;
        public $_addnewPlaylist;
        public $_searchBtn;
        public $_update;
        public $_add;
        public $_del;
        public $_orderDirection;
        public $_orderBy;

        public function __construct()
        {//contructor starts
            parent::__construct();
            $this->_playlistsearchQuery = filter_input(INPUT_POST, 'PlaylistssearchQuery');
            $this->_addnewPlaylist = filter_input(INPUT_POST, 'playlistadd');
            $this->_status = filter_input(INPUT_GET, 'status');
            $this->_searchBtn = filter_input(INPUT_POST, 'playlistsearchbtn');
            $this->_update = filter_input(INPUT_GET, 'update');
            $this->_add = filter_input(INPUT_GET, 'add');
            $this->_del = filter_input(INPUT_GET, 'del');
            $this->_orderDirection = filter_input(INPUT_GET, 'order');
            $this->_orderBy = filter_input(INPUT_GET, 'orderby');
        }//contructor ends

 public function hd_ajax_add_playlist($name, $media) {
    global $wpdb;
    // Get input informations from POST
    $p_name = addslashes(trim($name));
    $p_description = '';
    $p_playlistorder = 0;
    if (empty($p_playlistorder))
        $p_playlistorder = "ASC";
    $playlistname1 = "select playlist_name from " . $wpdb->prefix . "hdflvvideoshare_playlist where playlist_name='" . $p_name . "'";
    $planame1 = mysql_query($playlistname1);
    if (mysql_fetch_array($planame1, MYSQL_NUM)) {
        $this->render_error(__('Failed, Playlist name already exist', 'hdflvvideoshare')) . $this->get_playlist_for_dbx($media);
        return;
    }
    // Add playlist in db
    if (!empty($p_name)) {
        $insert_plist = mysql_query(" INSERT INTO " . $wpdb->prefix . "hdflvvideoshare_playlist (playlist_name, playlist_desc,is_publish, playlist_order) VALUES ('$p_name', '$p_description', '1', '$p_playlistorder')");
        if ($insert_plist != 0) {
            $pid = $wpdb->insert_id;  // get index_id
            $this->render_message(__('Playlist', 'hdflvvideoshare') . ' ' . substr($name, 0, 10) . __(' added successfully', 'hdflvvideoshare')) . $this->get_playlist_for_dbx($media);
        }
    }
    return;
}

 public function render_message($message, $timeout = 0) {
?>
    <div class="wrap"><h2>&nbsp;</h2>
        <div class="fade updated" id="message" onclick="this.parentNode.removeChild (this)">
            <p><strong><?php echo $message ?></strong></p>
        </div></div>
<?php
}

 public function render_error($message) {
?>
    <div class="wrap"><h2>&nbsp;</h2>
        <div class="error" id="error">
            <p><strong><?php echo $message ?></strong></p>
        </div></div>
<?php
}
      public function get_playlist() {

            global $wpdb;
            // get playlist ID's
            $playids = $wpdb->get_col("SELECT pid FROM " . $wpdb->prefix . "hdflvvideoshare_playlist");
            // to be sure
            $mediaid = '';
            $videoId = filter_input(INPUT_GET, 'videoId');
            if (isset($videoId))
                $mediaid = $videoId;

            $checked_playlist = $wpdb->get_col("
		SELECT playlist_id,sorder
		FROM " . $wpdb->prefix . "hdflvvideoshare_playlist," . $wpdb->prefix . "hdflvvideoshare_med2play
		WHERE " . $wpdb->prefix . "hdflvvideoshare_med2play.playlist_id = pid AND " . $wpdb->prefix . "hdflvvideoshare_med2play.media_id = '$mediaid'");
            if (count($checked_playlist) == 0)
                $checked_playlist[] = 0;

            $result = array();
            // create an array with playid, checked status and name
            if (is_array($playids)) {
                foreach ($playids as $playid) {
                    $result[$playid]['playid'] = $playid;
                    $result[$playid]['checked'] = in_array($playid, $checked_playlist);
                    $result[$playid]['name'] = $this->get_playlistname_by_ID($playid);
                    $result[$playid]['sorder'] = $this->get_sortorder($mediaid, $playid);
                }
            }


            $hiddenarray = array();

            echo "<table>";
            foreach ($result as $playlist) {

                $hiddenarray[] = $playlist['playid'];
                echo '<tr><td style="font-size:11px"><label for="playlist-' . $playlist['playid']
                . '" class="selectit"><input value="' . $playlist['playid']
                . '" type="checkbox" name="playlist[]" id="playlist-' . $playlist['playid']
                . '"' . ($playlist['checked'] ? ' checked="checked"' : "") . '/> ' . esc_html($playlist['name']) . "</label></td >&nbsp;</tr>
            ";
            }
            echo "</table>";
            $comma_separated = implode(",", $hiddenarray);
            echo "<input type=hidden name=hid value = $comma_separated >";
        }

       public function get_playlist_for_dbx($mediaid) {
    global $wpdb;
    // get playlist ID's
    $playids = $wpdb->get_col("SELECT pid FROM " . $wpdb->prefix . "hdflvvideoshare_playlist");
    // echo "SELECT pid FROM $wpdb->hdflv_playlist";
    // to be sure
    $mediaid = (int) $mediaid;
    // get checked ID's'
    $checked_playlist = $wpdb->get_col("
		SELECT playlist_id,sorder
		FROM " . $wpdb->prefix . "hdflvvideoshare_playlist," . $wpdb->prefix . "hdflvvideoshare_med2play
		WHERE " . $wpdb->prefix . "hdflvvideoshare_med2play.playlist_id = pid AND " . $wpdb->prefix . "hdflvvideoshare_med2play.media_id = '$mediaid'");
    if (count($checked_playlist) == 0)
        $checked_playlist[] = 0;
    $result = array();
    //print_r($playids);
    // create an array with playid, checked status and name
    if (is_array($playids)) {
        foreach ($playids as $playid) {
            $result[$playid]['playid'] = $playid;
            $result[$playid]['checked'] = in_array($playid, $checked_playlist);
            $result[$playid]['name'] = $this->get_playlistname_by_ID($playid);
            $result[$playid]['sorder'] = $this->get_sortorder($mediaid, $playid);
        }
    }
    $hiddenarray = array();
    echo "<table>";
    foreach ($result as $playlist) {
        $hiddenarray[] = $playlist['playid'];
        echo '<tr><td style="font-size:11px"><label for="playlist-' . $playlist['playid']
        . '" class="selectit"><input value="' . $playlist['playid']
        . '" type="checkbox" name="playlist[]" id="playlist-' . $playlist['playid']
        . '"' . ($playlist['checked'] ? ' checked="checked"' : "") . '/> ' . esc_html($playlist['name']) . "</label></td >&nbsp;</tr>
            ";
    }
    echo "</table>";
    $comma_separated = implode(",", $hiddenarray);
    echo "<input type=hidden name=hid value = $comma_separated >";
}



    public function get_sortorder($mediaid = 0, $pid) {
            global $wpdb;

            $mediaid = (int) $mediaid;
            $result = $wpdb->get_var("SELECT sorder FROM " . $wpdb->prefix . "hdflvvideoshare_med2play WHERE media_id = $mediaid and playlist_id= $pid");

            return $result;
        }

        public function get_playlistname_by_ID($pid = 0) {
            global $wpdb;

            $pid = (int) $pid;
            $result = $wpdb->get_var("SELECT playlist_name FROM " . $wpdb->prefix . "hdflvvideoshare_playlist WHERE pid = $pid ");

            return $result;
        }
    }
}
        $ajaxplaylistOBJ = new AjaxPlaylistController();
        $playlist_name=filter_input(INPUT_GET, 'name');
if (isset($playlist_name)) {
    return $ajaxplaylistOBJ->hd_ajax_add_playlist(filter_input(INPUT_GET, 'name'), filter_input(INPUT_GET, 'media'));
}
?>
