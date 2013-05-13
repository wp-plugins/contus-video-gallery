<?php
/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: Video Settings Controller.
Version: 2.0
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/

WPimport('models/videosetting.php'); //including settings model file for get database information.

if (class_exists('SettingsController') != true) {//checks if the SettingsController class has been defined starts

    class SettingsController extends SettingsModel {//SettingsController class starts

        public $_msg;
        public $_update;
        public $_settingsUpdate;

        public function __construct() {//contructor starts
            parent::__construct();
            $this->_update = filter_input(INPUT_GET, 'update');
            $this->_settingsUpdate = filter_input(INPUT_POST, 'updatebutton');
        }

//contructor ends

        public function update_settingsdata() {//funcion for update settings starts
            if (isset($this->_settingsUpdate)) {//update for settings if starts
                $autoPlay = filter_input(INPUT_POST, 'autoplay');
                $hdDefault = filter_input(INPUT_POST, 'HD_default');
                $playListauto = filter_input(INPUT_POST, 'playlistauto');
                $keyApps = filter_input(INPUT_POST, 'keyApps');
                $keydisqusApps = filter_input(INPUT_POST, 'keydisqusApps');
                $hideLogo = filter_input(INPUT_POST, 'hideLogo');
                $embedVisible = filter_input(INPUT_POST, 'embed_visible');
                $downLoad = filter_input(INPUT_POST, 'download');
                $playerTimer = filter_input(INPUT_POST, 'timer');
                $playerZoom = filter_input(INPUT_POST, 'zoom');
                $shareEmail = filter_input(INPUT_POST, 'email');
                $skinAutohide = filter_input(INPUT_POST, 'skin_autohide');
                $homePopular = filter_input(INPUT_POST, 'popular');
                $homeRecent = filter_input(INPUT_POST, 'recent');
                $homeFeature = filter_input(INPUT_POST, 'feature');
                $homeCategory = filter_input(INPUT_POST, 'homecategory');
                $playerWidth = filter_input(INPUT_POST, 'width');
                $playerHeight = filter_input(INPUT_POST, 'height');
                $stageColor = filter_input(INPUT_POST, 'stagecolor');
                $commentOption = filter_input(INPUT_POST, 'comment_option');
                $logoTarget = filter_input(INPUT_POST, 'logotarget');
                $logoAlign = filter_input(INPUT_POST, 'logoalign');
                $logoAlpha = filter_input(INPUT_POST, 'logoalpha');
                $ffmpegPath = filter_input(INPUT_POST, 'ffmpeg_path');
                $normalScale = filter_input(INPUT_POST, 'normalscale');
                $fullScreenscale = filter_input(INPUT_POST, 'fullscreenscale');
                $licenseKey = filter_input(INPUT_POST, 'license');
                $preRoll = filter_input(INPUT_POST, 'preroll');
                $postRoll = filter_input(INPUT_POST, 'postroll');
                $buffer = filter_input(INPUT_POST, 'buffer');
                $volume = filter_input(INPUT_POST, 'volume');
                $gutterSpace = filter_input(INPUT_POST, 'gutterspace');
                $rowsPop = filter_input(INPUT_POST, 'rowsPop');
                $colPop = filter_input(INPUT_POST, 'colPop');
                $rowsRec = filter_input(INPUT_POST, 'rowsRec');
                $colRec = filter_input(INPUT_POST, 'colRec');
                $rowsFea = filter_input(INPUT_POST, 'rowsFea');
                $colFea = filter_input(INPUT_POST, 'colFea');
                $rowCat = filter_input(INPUT_POST, 'rowCat');
                $colCat = filter_input(INPUT_POST, 'colCat');
                $playList = filter_input(INPUT_POST, 'playlist');
                $fullScreen = filter_input(INPUT_POST, 'fullscreen');
                $playerSkin = filter_input(INPUT_POST, 'skin');

                $settingsData = array(
                    'autoplay' => $autoPlay,
                    'HD_default' => $hdDefault,
                    'playlistauto' => $playListauto,
                    'keyApps' => $keyApps,
                    'keydisqusApps' => $keydisqusApps,
                    'hideLogo' => $hideLogo,
                    'embed_visible' => $embedVisible,
                    'download' => $downLoad,
                    'timer' => $playerTimer,
                    'zoom' => $playerZoom,
                    'email' => $shareEmail,
                    'skin_autohide' => $skinAutohide,
                    'popular' => $homePopular,
                    'recent' => $homeRecent,
                    'feature' => $homeFeature,
                    'homecategory' => $homeCategory,
                    'width' => $playerWidth,
                    'height' => $playerHeight,
                    'stagecolor' => $stageColor,
                    'comment_option' => $commentOption,
                    'logo_target' => $logoTarget,
                    'logoalign' => $logoAlign,
                    'logoalpha' => $logoAlpha,
                    'ffmpeg_path' => $ffmpegPath,
                    'normalscale' => $normalScale,
                    'fullscreenscale' => $fullScreenscale,
                    'license' => $licenseKey,
                    'preroll' => $preRoll,
                    'postroll' => $postRoll,
                    'buffer' => $buffer,
                    'volume' => $volume,
                    'gutterspace' => $gutterSpace,
                    'rowsPop' => $rowsPop,
                    'colPop' => $colPop,
                    'rowsRec' => $rowsRec,
                    'colRec' => $colRec,
                    'rowsFea' => $rowsFea,
                    'colFea' => $colFea,
                    'rowCat' => $rowCat,
                    'colCat' => $colCat,
                    'playlist' => $playList,
                    'fullscreen' => $fullScreen,
                    'skin' => $playerSkin
                );

                $settingsDataformat = array('%d', '%d', '%d', '%s', '%s', '%d', '%d', '%d', '%d', '%d', '%d',
                    '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%s', '%s', '%s',
                    '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%d',
                    '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%s');

                $updateflag = $this->update_settings($settingsData, $settingsDataformat);
                if ($updateflag) {
                    $this->admin_redirect("admin.php?page=hdflvvideosharesettings&update=1");
                } else {
                    $this->admin_redirect("admin.php?page=hdflvvideosharesettings&update=0");
                }
            }//update for settings if ends
        }

//funcion for update settings starts

        public function admin_redirect($url) {//admin_redirection url function starts
            echo "<script>window.open('" . $url . "','_top',false)</script>";
        }

//admin_redirection url function ends

        public function settings_data() {//getting settings data function starts
            return $this->get_settingsdata();
        }

//getting settings data function ends

        public function get_message() {//displaying database message function starts
            if (isset($this->_update) && $this->_update == '1') {
                $this->_msg = 'Settings Updated Successfully ...';
            } else if ($this->_update == '0') {
                $this->_msg = 'Settings Not Updated  Successfully ...';
            }
            return $this->_msg;
        }

//displaying database message function ends
    }

    //SettingsController class ends
}//checks if the SettingsController class has been defined ends
$settingsOBJ = new SettingsController();
$settingsOBJ->update_settingsdata();
$settingsGrid = $settingsOBJ->settings_data();
$displayMsg = $settingsOBJ->get_message();
$adminPage = filter_input(INPUT_GET, 'page');
$ski = APPTHA_VGALLERY_BASEDIR . DS . 'hdflvplayer' . DS . 'skin';
$skins = array();
// Pull the directories listed in the skins folder to generate the dropdown list with valid skin files
chdir($ski);
if ($handle = opendir($ski)) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            if (is_dir($file)) {
                $skins[] = $file;
            }
        }
    }
    closedir($handle);
}
if ($adminPage == 'hdflvvideosharesettings') {//including settings form if starts
    require_once(APPTHA_VGALLERY_BASEDIR . DS . 'admin/views/videosetting.php');
}//including settings form if starts


