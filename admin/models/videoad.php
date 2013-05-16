<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: video ad model file.
Version: 2.1
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/

if(class_exists('VideoadModel') != true)
{//checks the VideoadModel class has been defined if starts
    class VideoadModel
    {//VideoadModel class starts
        public $_videoadId;
        
        public function __construct()
        {//contructor starts
            global $wpdb;
            $this->_wpdb = $wpdb;
            $this->_videoadtable = $this->_wpdb->prefix.'hdflvvideoshare_vgads';
            $this->_videoadId = filter_input(INPUT_GET, 'videoadId');
        }//contructor ends

        public function insert_videoad($videoadData, $videoadDataformat)
        {//function for inserting video starts
            if( $this->_wpdb->insert($this->_videoadtable, $videoadData,$videoadDataformat))
            {
               return  $this->_wpdb->insert_id;
            }
        }//function for inserting video ends

        public function videoad_update($videoadData, $videoadDataformat,$videoadId)
        {//function for updating video starts
            return $this->_wpdb->update( $this->_videoadtable, $videoadData, array( 'ads_id' => $videoadId ),$videoadDataformat);
        }//function for updating video ends
        
        public function  status_update($videoadId,$status)
        {//function for updating status of video starts
            return $this->_wpdb->update( $this->_videoadtable, array('publish' => $status), array( 'ads_id' => $videoadId ));
        }//function for updating status of video ends

        public function get_videoaddata($searchValue,$searchBtn,$order,$orderDirection)
        {//function for getting search videos starts
            $where='';
            $pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
            $limit = 20;
            $offset = ( $pagenum - 1 ) * $limit;
            if(isset($searchBtn))
            { 
                $where =  " WHERE title LIKE '%" . $searchValue . "%' ";
            }
            if(!isset($orderDirection))
            {
                $orderDirection = 'DESC';
            }
            $query = "SELECT * FROM ".$this->_videoadtable .$where ." ORDER BY ". $order . ' '.$orderDirection. " LIMIT $offset, $limit";
            return $this->_wpdb->get_results($query);
        }//function for getting search videos ends

         public function videoad_count($searchValue, $searchBtn) {//function for getting single video starts
            $where = '';
            if (isset($searchBtn)) {
                $where = " WHERE title LIKE '%" . $searchValue . "%' ";
            }
            return $this->_wpdb->get_var("SELECT COUNT(`ads_id`) FROM " . $this->_videoadtable. $where);
        }

        public function videoad_edit($videoadId)
        {//function for getting single video starts
            return $this->_wpdb->get_row("SELECT * FROM ".$this->_videoadtable." WHERE ads_id ='$videoadId'");
        }//function for getting single video ends
        
        public function videoad_delete($videoadId)
        {//function for deleting video starts
            $query = "DELETE FROM ".$this->_videoadtable."  WHERE ads_id IN ("."$videoadId".")";
            return $this->_wpdb->query($query);
        }//function for deleting video ends
        
    }//VideoadModel class ends
}//checks the VideoadModel class has been defined if ends

?>