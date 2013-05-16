<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: Playlist Controller.
Version: 2.1
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/

include_once($adminModelPath . 'playlist.php');//including Playlist model file for get database information.

if(class_exists('PlaylistController') != true)
{//checks if the PlaylistController class has been defined starts
    class PlaylistController extends PlaylistModel 
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
        
        public function add_playlist() 
        {//function for adding playlist starts

            if(isset($this->_status))
            {//updating status of video ad starts
            $this->status_update($this->_playListId,$this->_status);
            }//updating status of video ad ends

             if(isset($this->_addnewPlaylist))
            {   
                $playlistName = filter_input(INPUT_POST, 'playlistname');
                $playlistDescription = filter_input(INPUT_POST, 'playlistdescription');
                $playlistPublish = filter_input(INPUT_POST, 'ispublish');
                $playlistOrder = filter_input(INPUT_POST, 'playlist_order');
                $playlistordering = filter_input(INPUT_POST, 'ordering');
                
                $playlsitData = array(
                'playlist_name' => $playlistName,
                'playlist_desc' => $playlistDescription,
                'is_publish' => $playlistPublish,
                'playlist_order' => $playlistordering,
                );

                $playlistDataformat = array('%s', '%s', '%d', '%d','%d');

                if (isset($this->_playListId)) 
                {//update for playlist if starts
                    $updateflag = $this->playlist_update($playlsitData, $playlistDataformat, $this->_playListId);

                    if ($updateflag)
                    {
                        $this->admin_redirect("admin.php?page=newplaylist&playlistId=" . $this->_playListId . "&update=1");
                    }
                    else 
                    {
                    $this->admin_redirect("admin.php?page=newplaylist&playlistId=" . $this->_playListId . "&update=0");
                    }
                }//update for playlist if ends
                else 
                {//adding playlist else starts
                    $addflag = $this->insert_playlist($playlsitData, $playlistDataformat);
                    
                    if (!$addflag)
                    {
                          $this->admin_redirect("admin.php?page=playlist&add=0");
                    } 
                    else 
                    {
                         $this->admin_redirect("admin.php?page=playlist&add=1");
                    }
                }//adding playlist else ends
            }
        }//function for adding playlist ends

        public function admin_redirect($url)
        {//admin redirection url function starts
            echo "<script>window.open('".$url."','_top',false)</script>";
        }//admin redirection url function ends

        public function playlist_orderdata()
        {//admin redirection url function starts
            return $this->get_playlistorderdata();
        }//admin redirection url function ends

        public function playlist_data() 
        {//getting playlist data function starts
            $orderBy = array('id', 'title', 'desc', 'publish','sorder');
            $order = 'id';

            if (isset($this->_orderBy) && in_array($this->_orderBy, $orderBy))
            {
                $order = $this->_orderBy;
            }

            switch($order)
            {
                case 'id':
                    $order ='pid';
                break;

                case 'title':
                    $order ='playlist_name';
                break;

                case 'desc':
                    $order ='playlist_desc';
                break;

                case 'publish':
                    $order ='is_publish';
                break;

                case 'sorder':
                    $order ='ordering';
                break;

                default:
                    $order ='pid';
            }
            return $this->get_playlsitdata($this->_playlistsearchQuery,$this->_searchBtn,$order, $this->_orderDirection);
        }//getting playlist data function starts

         public function get_message()
         {//displaying database message function starts
            if (isset($this->_update) && $this->_update == '1')
            {
                $this->_msg = 'Playlist Updated Successfully ...';
            }
            else if($this->_update == '0')
            {
                $this->_msg = 'Playlist Not Updated  Successfully ...';
            }

            if (isset($this->_add) && $this->_add == '1')
            {
                $this->_msg ='Playlist Added Successfully ...';
            }

              if (isset($this->_del) && $this->_del == '1')
            {
                $this->_msg ='Playlist Deleted Successfully ...';
            }
               if (isset($this->_status) && $this->_status == '1')
            {
                $this->_msg ='Playlist Published Successfully ...';
            }
            else if($this->_status == '0')
            {
                $this->_msg = 'Playlist UnPublished Successfully ...';
            }

            return $this->_msg;
         }//displaying database message function ends

        public function get_delete()
        {//deleting playlist data function starts
            $playlistApply = filter_input(INPUT_POST, 'playlistapply');
            $playlistActionup = filter_input(INPUT_POST, 'playlistactionup');
            $playlistActiondown = filter_input(INPUT_POST, 'playlistactiondown');
            $playListcheckId = filter_input(INPUT_POST, 'pid', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
            if (isset($playlistApply)) 
            {//apply button if starts
               if ($playlistActionup || $playlistActiondown == 'playlistdelete')
                {
                    if (is_array($playListcheckId))
                    {//delete button if starts
                        $playListId = implode(",", $playListcheckId);
                        $deleteflag = $this->playlist_delete($playListId);
                            if(!$deleteflag)
                            {
                                $this->admin_redirect("admin.php?page=playlist&del=0");
                            }
                            else
                            {
                                $this->admin_redirect("admin.php?page=playlist&del=1");
                            }
                    }//delete button if ends
                }
            }//apply button if ends
        }//deleting playlist data function ends
    }//PlaylistController class ends
}//checks if the PlaylistController class has been defined ends



$playlistOBJ = new PlaylistController();//creating object for VideoadController class
$playlistOBJ->add_playlist();
$playlistOBJ->get_delete();
$playListId = $playlistOBJ->_playListId;
$searchMsg =  $playlistOBJ->_playlistsearchQuery;
$searchBtn =  $playlistOBJ->_searchBtn;
$gridPlaylist = $playlistOBJ->playlist_data();
$Playlistorder = $playlistOBJ->playlist_orderdata();
$Playlist_count = $playlistOBJ->Playlist_count($searchMsg,$searchBtn);
$playlistEdit = $playlistOBJ->playlist_edit($playListId);
$displayMsg = $playlistOBJ->get_message();
$adminPage = filter_input(INPUT_GET, 'page');



$adminPage = filter_input(INPUT_GET, 'page');
if ($adminPage == 'playlist')
{//including playlist form if starts
    require_once(APPTHA_VGALLERY_BASEDIR . DS . 'admin/views/playlist/playlist.php');
}//including playlist form if starts
else if ($adminPage == 'newplaylist')
{//including newplaylist ad form if starts
    require_once(APPTHA_VGALLERY_BASEDIR . DS . 'admin/views/playlist/addplaylist.php');
}//including newplaylist ad form if ends

