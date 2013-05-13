<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: Video ad view file.
Version: 2.0
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/
?>

<!--   MENU OPTIONS STARTS  --->
<div class="apptha_gallery">
<h2 class="nav-tab-wrapper">
    <a href="?page=video" class="nav-tab">All Videos</a>
    <a href="?page=playlist" class="nav-tab">Play List</a>
    <a href="?page=videoads" class="nav-tab nav-tab-active">Video Ads</a>
    <a href="?page=hdflvvideosharesettings" class="nav-tab">Settings</a>
</h2>

<!--  MENU OPTIONS ENDS --->

<div class="wrap">
    <h2 class="option_title">
       <?php echo "<img src='" . APPTHA_VGALLERY_BASEURL . "/images/vid_ad.png' alt='move' width='30'/>"; ?>
       <?php _e('Manage Video Ads','digi'); ?>
       <a class="button-primary" href="<?php echo get_bloginfo('url')?>/wp-admin/admin.php?page=newvideoad" >Add Video Ad</a>
    </h2>

        <?php if ($displayMsg): ?>
        <div class="updated below-h2">
        <p>
        <?php echo $displayMsg; ?>
        </p>
        </div>
        <?php endif;
        $orderField  =filter_input(INPUT_GET, 'order');
        $direction = isset($orderField) ? $orderField : false;
        $reverse_direction = ($direction == 'DESC' ? 'ASC' : 'DESC'); 
   if (isset($searchBtn)) {  ?>
    <div  class="updated below-h2">
    <?php
       $url = get_bloginfo('url').'/wp-admin/admin.php?page=videoads';
       if(count($gridVideoad)){
    echo count($gridVideoad)."    Search Result(s) for '".$searchMsg."'.&nbsp&nbsp&nbsp<a href='$url' >Back to Video Ads List</a>";
       }else { echo "No Search Result(s) for '".$searchMsg."'.&nbsp&nbsp&nbsp<a href='$url' >Back to Video Ads List</a>"; } ?> </div> <?php } ?>
    <form name="videoads" action="" method="post"  onsubmit="return prodttagsearch();" >
        <p class="search-box">
            <input type="text"  name="videoadssearchQuery" id="videoadssearchQuery" value="<?php echo $searchMsg;?>">
              <?php echo '<script>document.getElementById("videoadssearchQuery").value="'.$searchMsg.'"</script>';?>
            <input type="hidden"  name="page" value="videoads">
            <input type="submit" name="videoadsearchbtn"  class="button" value="Search Video Ads">
        </p>
    </form>
        <form  name="videoadsfrm" action="" method="post" onsubmit="return VideoaddeleteIds()" >
          <div class="alignleft actions">
            <select name="videoadactionup" id="videoadactionup">
                <option value="-1" selected="selected">
                    Bulk Actions
                </option>
                <option value="videoaddelete">
                    Delete
                </option>
            </select>
            <input type="submit" name="videoadapply"  class="button-secondary action" value="Apply">
        </div>

             <?php
                    $limit = 20;
                    $pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
                    $total = $videoad_count;
                    $num_of_pages = ceil($total / $limit);
                    $page_links = paginate_links(array(
                                'base' => add_query_arg('pagenum', '%#%'),
                                'format' => '',
                                'prev_text' => __('&laquo;', 'aag'),
                                'next_text' => __('&raquo;', 'aag'),
                                'total' => $num_of_pages,
                                'current' => $pagenum
                            ));

                    if ($page_links) {
                        echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
                    }
            ?>

            <table class="wp-list-table widefat fixed tags" cellspacing="0">
                <thead>
                    <tr>
                        <th scope="col"  class="manage-column column-cb check-column" style="">
                            <input type="checkbox" name="" >
                        </th>
                        <th scope="col"  class="manage-column column-name sortable desc" style="">
                            <a  href="<?php echo get_bloginfo('url')?>/wp-admin/admin.php?page=videoads&orderby=id&order=<?php echo $reverse_direction; ?>">
                                <span>ID</span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        <th scope="col" class="manage-column column-description sortable desc" style="">
                            <a href="<?php echo get_bloginfo('url')?>/wp-admin/admin.php?page=videoads&orderby=title&order=<?php echo $reverse_direction; ?>" ><span>Title</span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                          <th scope="col"  class="manage-column column-name sortable desc" style="">
                            <a  href="<?php echo get_bloginfo('url')?>/wp-admin/admin.php?page=videoads&orderby=path&order=<?php echo $reverse_direction; ?>">
                                <span>Path</span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                        
                        <th scope="col" class="manage-column column-description sortable desc" style="">
                            <a  href="<?php echo get_bloginfo('url')?>/wp-admin/admin.php?page=videoads&orderby=publish&order=<?php echo $reverse_direction; ?>" ><span>Publish</span>
                                <span class="sorting-indicator"></span>
                            </a>
                        </th>
                    </tr>
                </thead>                
                <tbody id="the-list" class="list:tag">
            <?php
                foreach ($gridVideoad as $videoAdview)
                  {
            ?>
                <tr>
                    <th scope="row" class="check-column">
                        <input type="checkbox" name="videoad_id[]" value="<?php echo $videoAdview->ads_id; ?>">
                    </th>
                    <td>
                        <a title="Edit <?php echo $videoAdview->title; ?>" href="<?php echo get_bloginfo('url')?>/wp-admin/admin.php?page=newvideoad&videoadId=<?php echo $videoAdview->ads_id;  ?>" ><?php echo $videoAdview->ads_id;?></a><div class="row-actions">
                    </td>
                    <td>
                        <a title="Edit <?php echo $videoAdview->title; ?>" class="row-title" href="<?php echo get_bloginfo('url')?>/wp-admin/admin.php?page=newvideoad&videoadId=<?php echo $videoAdview->ads_id;  ?>" ><?php echo  $videoAdview->title; ?></a>
                    </td>
                    <td class="description column-description">
                        <?php echo $videoAdview->file_path ?>
                    </td>
                        <?php $status = 1;
                        $image = "deactivate.jpg";
                        $publish = "Click here to Activate";
                        if($videoAdview->publish == 1)
                            {
                                $status = 0;
                                $image = "activate.jpg";
                                $publish = "Click here to Deactivate";
                            }
                        ?>
                    <td class="description column-description">
                        <a href="<?php echo get_bloginfo('url')?>/wp-admin/admin.php?page=videoads&videoadId=<?php echo $videoAdview->ads_id;?>&status=<?php echo $status;?>">   <img  src="<?php  echo APPTHA_VGALLERY_BASEURL.'images/'.$image ?>" title="<?php echo $publish;?>" title="<?php echo $publish;?>"  /></a>
                    </td>
                </tr>
             <?php
               }
        if(count($gridVideoad)==0){ ?>
        <tr class="no-items"><td class="colspanchange" colspan="5">No video ad found.</td></tr>
            <?php
             }
            ?>
       </tbody>
        <tfoot>
            <tr>
                <th scope="col"  class="manage-column column-cb check-column" style="">
                    <input type="checkbox" name="" >
                </th>
                <th scope="col"  class="manage-column column-name sortable desc" style="">
                    <a  href="<?php echo get_bloginfo('url')?>/wp-admin/admin.php?page=videoads&orderby=id&order=<?php echo $reverse_direction; ?>">
                        <span>ID</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th scope="col" class="manage-column column-description sortable desc" style="">
                    <a href="<?php echo get_bloginfo('url')?>/wp-admin/admin.php?page=videoads&orderby=title&order=<?php echo $reverse_direction; ?>" ><span>Title</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th scope="col"  class="manage-column column-name sortable desc" style="">
                    <a  href="<?php echo get_bloginfo('url')?>/wp-admin/admin.php?page=videoads&orderby=path&order=<?php echo $reverse_direction; ?>">
                        <span>Path</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th scope="col" class="manage-column column-description sortable desc" style="">
                    <a  href="<?php echo get_bloginfo('url')?>/wp-admin/admin.php?page=videoads&orderby=publish&order=<?php echo $reverse_direction; ?>" ><span>Publish</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
            </tr>
        </tfoot>
      </table>
        <div class="alignleft actions" style="margin-top:10px;">
            <select name="videoadactiondown" id="videoadactiondown">
                <option value="-1" selected="selected">
                    Bulk Actions
                </option>
                <option value="videoaddelete">
                    Delete
                </option>
            </select>
            <input type="submit" name="videoadapply"  class="button-secondary action" value="Apply">
        </div>
            <?php
            if ($page_links) {
                        echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
                    }
            ?>
       </form>
   </div>

</div>