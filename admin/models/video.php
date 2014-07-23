<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: video model file.
Version: 2.5
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/

if(class_exists('VideoModel') != true)
{//checks the VideoModel class has been defined if starts
    class VideoModel
    {//VideoModel class starts
        
        public $_videoId;
        
        public function __construct()
        {//contructor starts
            global $wpdb;
            $this->_wpdb = $wpdb;
            $this->_videotable = $this->_wpdb->prefix.'hdflvvideoshare';
            $this->_posttable = $this->_wpdb->prefix.'posts';
            $this->_videosettingstable = $this->_wpdb->prefix.'hdflvvideoshare_settings';
            $this->_videoId = intval(filter_input(INPUT_GET, 'videoId'));
            $current_user = wp_get_current_user();
            $this->member_id = $current_user->ID;
        }//contructor ends
        
        public function insert_video($videoData,$slug)
        {//function for inserting video starts
            $post_id=$this->_wpdb->get_var("SELECT ID FROM ".$this->_posttable." order by ID desc");
            if( $this->_wpdb->insert($this->_videotable, $videoData))
            {
                $last_insert_video_id=$this->_wpdb->insert_id;
                
                $post_content="[hdvideo id=".$this->_wpdb->insert_id."]";
                $post_id=$post_id+1;

                $postsData= array(
                    'post_author' => $this->member_id,
                    'post_date' => date('Y-m-d H:i:s'),
                    'post_date_gmt' => date('Y-m-d H:i:s'),
                    'post_content' => $post_content,
                    'post_title' => $videoData['name'],
                    'post_excerpt' => '',
                    'post_status' => 'publish',
                    'comment_status' => 'open',
                    'ping_status' => 'closed',
                    'post_password' => '',
                    'post_name' => $slug,
                    'to_ping' => '',
                    'pinged' => '',
                    'post_modified' => date('Y-m-d H:i:s'),
                    'post_modified_gmt' => date('Y-m-d H:i:s'),
                    'post_content_filtered' => '',
                    'post_parent' => 0,
                    'guid' => '',
                    'menu_order' => '0',
                    'post_type' => 'videogallery',
                    'post_mime_type' => '',
                    'comment_count' => '0',
                );
                $this->_wpdb->insert( $this->_posttable, $postsData);
                $guid=get_site_url()."/?post_type=videogallery&#038;p=".$this->_wpdb->insert_id;
                $this->_wpdb->update($this->_posttable, array('guid' =>$guid), array( 'ID' => $this->_wpdb->insert_id ));
                $this->_wpdb->update($this->_videotable, array('slug' =>$this->_wpdb->insert_id), array( 'vid' => $last_insert_video_id ));

                return  $last_insert_video_id;
            }
        }//function for inserting video ends

        public function  status_update($videoId,$status,$feaStatus)
        {//function for updating status of playlist starts
            if(isset($status))
            {
                $result = $this->_wpdb->update( $this->_videotable, array('publish' => $status), array( 'vid' => $videoId ));
            }
            if(isset($feaStatus))
            {
                $result = $this->_wpdb->update( $this->_videotable, array('featured' => $feaStatus), array( 'vid' => $videoId ));
            }
            return $result;
        }//function for updating status of playlist ends

        
        public function video_update($videoData,$videoId,$slug)
        {//function for updating video starts

            $this->_wpdb->update( $this->_videotable, $videoData, array( 'vid' => $videoId ));
            $slug_id=$this->_wpdb->get_var("SELECT slug FROM ".$this->_videotable." WHERE vid =$videoId");
              if(empty($slug_id)){
                $post_content="[hdvideo id=".$videoId."]";

                $postsData= array(
                    'post_author' => $this->member_id,
                    'post_date' => date('Y-m-d H:i:s'),
                    'post_date_gmt' => date('Y-m-d H:i:s'),
                    'post_content' => $post_content,
                    'post_title' => $videoData['name'],
                    'post_excerpt' => '',
                    'post_status' => 'publish',
                    'comment_status' => 'open',
                    'ping_status' => 'closed',
                    'post_password' => '',
                    'post_name' => $slug,
                    'to_ping' => '',
                    'pinged' => '',
                    'post_modified' => date('Y-m-d H:i:s'),
                    'post_modified_gmt' => date('Y-m-d H:i:s'),
                    'post_content_filtered' => '',
                    'post_parent' => 0,
                    'guid' => '',
                    'menu_order' => '0',
                    'post_type' => 'videogallery',
                    'post_mime_type' => '',
                    'comment_count' => '0',
                );
                $this->_wpdb->insert( $this->_posttable, $postsData);
                $guid=get_site_url()."/?post_type=videogallery&#038;p=".$this->_wpdb->insert_id;
                $this->_wpdb->update($this->_posttable, array('guid' =>$guid), array( 'ID' => $this->_wpdb->insert_id ));
               $this->_wpdb->update($this->_videotable, array('slug' =>$this->_wpdb->insert_id), array( 'vid' => $videoId ));
            }else{
                $this->_wpdb->update($this->_posttable, array('comment_status' => 'open','post_title' =>$videoData['name'],'post_name' => $slug,'post_modified' => date('Y-m-d H:i:s'),'post_modified_gmt' => date('Y-m-d H:i:s')), array( 'ID' => $slug_id ));
             }


            return ;
        }//function for updating video ends

         function get_current_user_role() {
        global $current_user;
        get_currentuserinfo();
        $user_roles = $current_user->roles;
        $user_role = array_shift($user_roles);
        return $user_role;
    }
    
        public function get_videodata($searchValue,$searchBtn,$order,$orderDirection)
        {//function for getting search videos starts
        global $wpdb;
            $where='';
            $user_role = $this->get_current_user_role();
            $current_user = wp_get_current_user();
            if($user_role!='administrator'){
                $where .=  " WHERE a.member_id=".$current_user->ID;
            }
            $pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
            $orderFilterlimit = filter_input(INPUT_GET, 'filter');
            if(isset($searchBtn))
            {
                if(empty($where)){
                    $where .= " WHERE";
                } else {
                    $where .= " AND";
                }
                $where .=  " (a.name LIKE '%" . $searchValue . "%' || a.description LIKE '%" . $searchValue . "%')";
            }
            if(!isset($orderDirection))
            {
                $orderDirection = 'DESC';
            }
           $query = "SELECT DISTINCT (a.vid) FROM ".$this->_videotable ." a 
                    LEFT JOIN $wpdb->users u 
                    ON u.ID=a.member_id 
                    LEFT JOIN ". $this->_wpdb->prefix ."hdflvvideoshare_med2play p 
                    ON p.media_id=a.vid 
                    LEFT JOIN ". $this->_wpdb->prefix ."hdflvvideoshare_playlist pl 
                    ON pl.pid=p.playlist_id 
                    ".$where ." 
                    ORDER BY ". $order . ' '.$orderDirection;
            $total = count($this->_wpdb->get_results($query));
            if(!empty($orderFilterlimit) && $orderFilterlimit !== 'all'){
                $limit = $orderFilterlimit;
            } else if($orderFilterlimit === 'all'){
                $limit = $total;
            } else {
                $limit = 20;
            }
            $offset = ( $pagenum - 1 ) * $limit;
           $query = "SELECT DISTINCT (a.vid),a.*,u.display_name FROM ".$this->_videotable ." a 
                    LEFT JOIN $wpdb->users u 
                    ON u.ID=a.member_id 
                    LEFT JOIN ". $this->_wpdb->prefix ."hdflvvideoshare_med2play p 
                    ON p.media_id=a.vid 
                    LEFT JOIN ". $this->_wpdb->prefix ."hdflvvideoshare_playlist pl 
                    ON pl.pid=p.playlist_id 
                    ".$where ." 
                    ORDER BY ". $order . ' '.$orderDirection." 
                    LIMIT $offset, $limit";
            return $this->_wpdb->get_results($query);
        }//function for getting search videos ends

         public function get_playlist_detail($vid)
        {//function for getting Tag name starts
            global $wpdb;
           $video_count = $this->_wpdb->get_results("SELECT t3.playlist_name,t3.pid"
                . " FROM " . $wpdb->prefix . "hdflvvideoshare_playlist AS t3"
                . " LEFT JOIN  ". $wpdb->prefix . "hdflvvideoshare_med2play AS t2"
                . " ON t3.pid = t2.playlist_id"
                . " WHERE t3.is_publish='1' AND t2.media_id='" . intval($vid) . "'");
           return $video_count;
        }
        
        public function video_edit($videoId) {
            global $current_user, $wpdb;           
            if (isset($videoId) && !current_user_can('manage_options')) {
                $user_id = $current_user->ID;
                $video_count = $wpdb->get_var("SELECT count(*) FROM $this->_videotable WHERE vid = $videoId and member_id = $user_id");
                if ($video_count == 0) {                   
                    wp_die( __( 'You do not have permission to access this page.' ) );
                }
            }
            //function for getting single video starts
            return $this->_wpdb->get_row("SELECT a.*,b.tags_name FROM " . $this->_videotable . " as a LEFT JOIN " . $this->_wpdb->prefix . "hdflvvideoshare_tags b ON b.media_id=a.vid WHERE a.vid ='$videoId'");
        }
        
        //function for getting single video ends
        public function video_count($searchValue,$searchBtn)
        {//function for getting single video starts
            $where='';
            $user_role = $this->get_current_user_role();
            $current_user = wp_get_current_user();
            if($user_role!='administrator'){
                $where .=  " WHERE member_id=".$current_user->ID;
            }
            if(isset($searchBtn))
            {
                if(empty($where)){
                    $where .= " WHERE";
                } else {
                    $where .= " AND";
                }
                $where .=  " (name LIKE '%" . $searchValue . "%' || description LIKE '%" . $searchValue . "%')";
            }
            return $this->_wpdb->get_var("SELECT COUNT(`vid`) FROM ".$this->_videotable.$where);
        }//function for getting single video ends
        
        public function video_delete($videoId)
        {//function for deleting video starts
            $slug = $this->_wpdb->get_col("SELECT slug FROM ".$this->_videotable."  WHERE vid IN ("."$videoId".")");
			$slugid = implode(",", $slug);
            $query = "DELETE FROM ".$this->_videotable."  WHERE vid IN ("."$videoId".")";
            $this->_wpdb->query($query);
            $query = "DELETE FROM ".$this->_posttable."  WHERE ID IN ("."$slugid".")";
            return $this->_wpdb->query($query);
        }//function for deleting video ends

         public function get_settingsdata()
        {//function for getting settings data starts
            $query = "SELECT * FROM " . $this->_videosettingstable ." WHERE settings_id = 1";
            return $this->_wpdb->get_row($query);
        }//function for getting settings data ends
        
    }//VideoModel class ends
}//checks the VideoModel class has been defined if ends