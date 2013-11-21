/*
Name: Wordpress Video Gallery
Plugin URI: http://www.apptha.com/category/extension/Wordpress/Video-Gallery
Description: VIdeo Gallery plugin script file.
Version: 2.3.1.0.1
Author: Apptha
Author URI: http://www.apptha.com
License: GPL2
*/
function mycarousel_initCallback(carousel){
                                            // Disable autoscrolling if the user clicks the prev or next button.
                                            carousel.buttonNext.bind("click", function() {
                                            carousel.startAuto(0);
                                            });

                                            carousel.buttonPrev.bind("click", function() {
                                            carousel.startAuto(0);
                                            });

                                            // Pause autoscrolling if the user moves with the cursor over the clip.
                                            carousel.clip.hover(function() {
                                            carousel.stopAuto();
                                            }, function() {
                                            carousel.startAuto();
                                            });carousel.buttonPrev.bind("click", function() {
                                            carousel.startAuto(0);
                                            });
                                            };
                                            jQuery(document).ready(function() {
                                            jQuery(".jcarousel-skin-tango").jcarousel({
                                            auto: 0,
                                            wrap: "last",
                                            scroll:1,
                                            initCallback: mycarousel_initCallback
                                            });
                                            });