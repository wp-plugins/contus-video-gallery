<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: Video Ad Controller.
Version: 2.0
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/

WPimport('models/videoad.php');//including videoad model file for get database information.

if(class_exists('VideoadController') != true)
    {//checks if the VideoadController class has been defined starts
    
        class VideoadController extends VideoadModel
        {//VideoadController class starts

            public $_status;
            public $_msg;
            public $_search;
            public $_videoadsearchQuery;
            public $_addnewVideoad;
            public $_searchBtn;
            public $_update;
            public $_add;
            public $_del;
            public $_orderDirection;
            public $_orderBy;

            public function __construct()
            {//contructor starts
                parent::__construct();
                $this->_addnewVideoad = filter_input(INPUT_POST, 'videoadsadd');
                $this->_status = filter_input(INPUT_GET, 'status');
                $this->_searchBtn = filter_input(INPUT_POST, 'videoadsearchbtn');
                $this->_videoadsearchQuery = filter_input(INPUT_POST, 'videoadssearchQuery');
                $this->_update = filter_input(INPUT_GET, 'update');
                $this->_add = filter_input(INPUT_GET, 'add');
                $this->_del = filter_input(INPUT_GET, 'del');
                $this->_orderDirection = filter_input(INPUT_GET, 'order');
                $this->_orderBy = filter_input(INPUT_GET, 'orderby');
                
            }//contructor ends

            public function add_newvideoad()
            {//function for adding video starts

                if(isset($this->_status))
                {//updating status of video ad starts
                    $this->status_update($this->_videoadId,$this->_status);
                }//updating status of video ad ends
                
                if(isset($this->_addnewVideoad))
                {
                    $videoadName = filter_input(INPUT_POST, 'videoadname');
                    $videoadFilepath = filter_input(INPUT_POST, 'videoadfilepath');
                    $videoadPublish = filter_input(INPUT_POST, 'videoadpublish');

                    $videoadData = array(
                    'title' => $videoadName,
                    'file_path' => $videoadFilepath,
                    'publish' => $videoadPublish,
                    );
                    
                    $videoadDataformat = array('%s', '%s' , '%d');

                    if(isset($this->_videoadId))
                    {//update for video ad if starts
                        $updateflag =  $this->videoad_update($videoadData, $videoadDataformat,$this->_videoadId);

                        if($updateflag)
                        {
                            $this->admin_redirect("admin.php?page=newvideoad&videoadId=".$this->_videoadId."&update=1");
                        }
                        else
                        {
                            $this->admin_redirect("admin.php?page=newvideoad&videoadId=".$this->_videoadId."&update=0");
                        }
                    }//update for video ad if ends
                    else
                    {//adding video ad else starts
                        $addflag = $this->insert_videoad($videoadData,$videoadDataformat);
                        
                        if(!$addflag)
                        {
                            $this->admin_redirect("admin.php?page=videoads&add=0");
                        }
                        else
                        {
                            $this->admin_redirect("admin.php?page=videoads&add=1");
                        }
                    }//adding video ad else ends
                }
            }//function for adding video ends
            
            public function admin_redirect($url)
            {//admin redirection url function starts
                echo "<script>window.open('".$url."','_top',false)</script>";
            }//admin redirection url function ends
            
            public function videoad_data()
            {//getting videoad data function starts
            $orderBy = array('id', 'title', 'path', 'publish');
            $order = 'id';
            
            if (isset($this->_orderBy) && in_array($this->_orderBy, $orderBy))
            {
                $order = $this->_orderBy;
            }

            switch($order)
            {
                case 'id':
                    $order ='ads_id';
                break;

                case 'title':
                    $order ='title';
                break;
            
                case 'publish':
                    $order ='publish';
                break;
            
                default:
                    $order ='ads_id';
            }

            return $this->get_videoaddata($this->_videoadsearchQuery,$this->_searchBtn,$order, $this->_orderDirection);
            }//getting videoad data function ends
            
            public function get_message()
            {//displaying database message function starts
                if (isset($this->_update) && $this->_update == '1') 
                {
                    $this->_msg = 'Videoad Updated Successfully ...';
                }
                else if($this->_update == '0') 
                {
                    $this->_msg = 'Videoad Not Updated  Successfully ...';
                }
               
                if (isset($this->_add) && $this->_add == '1')
                {
                    $this->_msg ='Videoad Added Successfully ...';
                }

                  if (isset($this->_del) && $this->_del == '1')
                {
                    $this->_msg ='Videoad Deleted Successfully ...';
                }
                   if (isset($this->_status) && $this->_status == '1')
                {
                    $this->_msg ='Videoad Published Successfully ...';
                }
                else if($this->_status == '0')
                {
                    $this->_msg = 'Videoad UnPublished Successfully ...';
                }

                return $this->_msg;
            }//displaying database message function ends
            
            public function get_delete()
            {//deleting videoad data function starts
                $videoadApply = filter_input(INPUT_POST, 'videoadapply');
                $videoadActionup = filter_input(INPUT_POST, 'videoadactionup');
                $videoadActiondown = filter_input(INPUT_POST, 'videoadactiondown');
                $videoadcheckId =filter_input(INPUT_POST, 'videoad_id', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
                
                if (isset($videoadApply))
                {//apply button if starts
                    if ( $videoadActionup || $videoadActiondown == 'videoaddelete' )
                    {//delete button if starts
                        if(is_array($videoadcheckId))
                        {
                            $videoadId = implode(",",$videoadcheckId);
                            $deleteflag = $this->videoad_delete($videoadId);
                            if(!$deleteflag)
                            {
                                $this->admin_redirect("admin.php?page=videoads&del=0");
                            }
                            else
                            {
                                $this->admin_redirect("admin.php?page=videoads&del=1");
                            }
                        }
                    }//delete button if ends
                }//apply button if ends
            }//deleting videoad data function ends

        }//VideoadController class ends
        
}//Checks if the VideoadController class has been defined ends

$videoadOBJ = new VideoadController();//creating object for VideoadController class
$videoadOBJ->add_newvideoad();
$videoadOBJ->get_delete();
$videoadId = $videoadOBJ->_videoadId;
$searchMsg = $videoadOBJ->_videoadsearchQuery;
$searchBtn = $videoadOBJ->_searchBtn;
//$reverse_direction = $videoadOBJ->_reverseDirection;
$gridVideoad = $videoadOBJ->videoad_data();
$videoad_count = $videoadOBJ->videoad_count($searchMsg,$searchBtn);
$videoadEdit = $videoadOBJ->videoad_edit($videoadId);
$displayMsg = $videoadOBJ->get_message();
$adminPage = filter_input(INPUT_GET, 'page');
//$videoadOBJ->_reverseDirection;

if ($adminPage == 'videoads')  
{//including videoad form if starts
    require_once(APPTHA_VGALLERY_BASEDIR . DS . 'admin/views/videoads/videoads.php');
}//including videoad form if starts
else if ($adminPage == 'newvideoad') 
{//including newvideo ad form if starts
    require_once(APPTHA_VGALLERY_BASEDIR . DS . 'admin/views/videoads/addvideoads.php');
}//including newvideo ad form if ends



?>


      